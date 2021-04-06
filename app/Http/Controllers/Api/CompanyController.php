<?php

namespace App\Http\Controllers\Api;

use App\Events\CompanyCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetCompanyDataRequest;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Services\GeoLocationService;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CompanyController extends Controller
{
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
        } catch (Exception $th) {
            return null;
        }
    }

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
        return CompanyResource::collection($request->user()->companies()->get());
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
}
