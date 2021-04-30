<?php

namespace App\Http\Controllers\Api;

use App\Events\CompanyCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetCompanyDataRequest;
use App\Http\Requests\StoreCompanyItemRequest;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Services\GeoLocationService;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CompanyController extends Controller
{

    /**
     * @param string $cnpj
     * @return array|null
     */
    private function getCompanyDataByCnpj(string $cnpj): ?array
    {
        try {
            $cnpj = trim(preg_replace('/\D/', '', $cnpj));
            $client = new Client([
                'base_uri' => env('RECEITA_URL', ''),
                'timeout' => 3,
            ]);
            $request = $client->get("/v1/cnpj/{$cnpj}");
            $response = (string)$request->getBody();
            return json_decode($response, true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception | GuzzleException $th) {
            return null;
        }
    }

    /**
     * @return string[]
     */
    private function companyRelevantFields(): array
    {
        return ['nome', 'telefone', 'email', 'bairro', 'logradouro', 'numero', 'cep', 'municipio', 'fantasia', 'uf', 'cnpj', 'complemento'];
    }

    /**
     * @param GetCompanyDataRequest $request
     * @return JsonResponse
     */
    final public function getData(GetCompanyDataRequest $request): JsonResponse
    {
        $cnpj = trim(preg_replace('/\D/', '', $request->cnpj));
        $companyData = $this->getCompanyDataByCnpj($cnpj);
        if (null === $companyData || $companyData['status'] !== 'OK') {
            return response()->json(['success' => false, 'data' => null]);
        }
        $parsedCompanyData = collect($companyData)->only($this->companyRelevantFields())->toArray();
        if (isset($parsedCompanyData['cep'])) {
            $zipcode = trim(preg_replace('/\D/', '', $parsedCompanyData['cep']));
            $address = GeoLocationService::getAddressByZipcode($zipcode);
            if (is_array($address) && ($address['latitude'] ?? false) && ($address['longitude'] ?? false)) {
                $parsedCompanyData['latitude'] = (float)$address['latitude'];
                $parsedCompanyData['longitude'] = (float)$address['longitude'];
            }
        }
        return response()->json(['success' => true, 'data' => $parsedCompanyData]);
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    final public function index(Request $request): AnonymousResourceCollection
    {
        $companies = $request->user()->companies()->with([
            'interestFoods' => static function ($query) {
                $query->with('units');
            },
            'availableFoods' => static function ($query) {
                $query->with('units');
            },
        ]);
        return CompanyResource::collection($companies->get()->unique('id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCompanyRequest $request
     * @return JsonResponse
     */
    final public function store(StoreCompanyRequest $request): JsonResponse
    {
        $company = $request->user()->companies()->create(array_merge($request->validated(), ['active' => 0]));
        if (!$company) {
            return response()->json(['success' => false, 'message' => 'Erro ao criar sua empresa']);
        }
        $company->load('users');
        event(new CompanyCreated($company));
        return response()->json([
            'success' => true,
            'message' => "{$request->name}, foi adicionada com sucesso." . PHP_EOL
                . 'Aguarde a aprovação de nossos administradores para fazer e receber doações.' . PHP_EOL
                . 'Fique tranquil' . ($request->user()->gender === 'F' ? 'a' : 'o')
                . ', notificaremos atualizações por e-mail.'
        ]);
    }

    /**
     * @param StoreCompanyItemRequest $request
     * @param Company $company
     * @return JsonResponse
     */
    final public function storeAvailableItem(StoreCompanyItemRequest $request, Company $company): JsonResponse
    {
        $company->availableFoods()->attach($request->food_id, [
            'company_id' => $company->id,
            'unit_id' => $request->unit_id,
            'requested_by' => $request->user()->id,
            'type' => 'available',
            'amount' => $request->amount,
        ]);
        return response()->json([
            'success' => true,
            'message' => '',
        ], Response::HTTP_CREATED);
    }

    /**
     * @param StoreCompanyItemRequest $request
     * @param Company $company
     * @return JsonResponse
     */
    final public function storeInterestItem(StoreCompanyItemRequest $request, Company $company): JsonResponse
    {
        $company->availableFoods()->attach($request->food_id, [
            'company_id' => $company->id,
            'unit_id' => $request->unit_id,
            'requested_by' => $request->user()->id,
            'type' => 'interest',
            'amount' => $request->amount,
        ]);
        return response()->json([
            'success' => true,
            'message' => '',
        ], Response::HTTP_CREATED);
    }

    /**
     * @param Company $company
     * @return JsonResponse
     */
    final public function closestCompatibleDonations(Company $company): JsonResponse
    {
        $donations = $company->compatibleDonations();
        $companies = Company::with(['interestFoods' => static function ($query) use ($donations) {
            $query->with('units')->whereIn('companies_foods.id', $donations->pluck('id'));
        }])->whereIn('id', $donations->pluck('company_id'))->get()
            ->map(static function (Company $c) use ($company) {
                $distance = GeoLocationService::distanceBetweenTowCoordinates(
                    $c->latitude, $c->longitude, $company->latitude, $company->longitude
                );
                $c->setAttribute('distanceInKilometers', $distance);
                return $c;
            })->sortBy('distanceInKilometers');
        return response()->json($companies->values());
    }

    /**
     * @param Company $company
     * @return JsonResponse
     */
    final public function closestCompatibleReceptions(Company $company): JsonResponse
    {
        $receptions = $company->compatibleReceptions();
        $companies = Company::with(['availableFoods' => static function ($query) use ($receptions) {
            $query->with('units')->whereIn('companies_foods.id', $receptions->pluck('id'));
        }])->whereIn('id', $receptions->pluck('company_id'))->get()
            ->map(static function (Company $c) use ($company) {
                $distance = GeoLocationService::distanceBetweenTowCoordinates(
                    $c->latitude, $c->longitude, $company->latitude, $company->longitude
                );
                $c->setAttribute('distanceInKilometers', $distance);
                return $c;
            })->sortBy('distanceInKilometers');
        return response()->json($companies->values());
    }
}
