<?php

namespace App\Http\Controllers\Api;

use App\Events\CompanyCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetCompanyDataRequest;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Services\GeoLocationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use JsonException;

class CompanyController extends Controller
{
    /**
     * @param GetCompanyDataRequest $request
     * @return JsonResponse
     * @throws JsonException
     */
    final public function getData(GetCompanyDataRequest $request): JsonResponse
    {
        $cnpj = trim(preg_replace('/\D/', '', $request->cnpj));
        $data = file_get_contents("http://receitaws.com.br/v1/cnpj/{$cnpj}");
        $parsed = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        if (!$parsed || $parsed['status'] !== 'OK') {
            return response()->json(['success' => false, 'data' => null]);
        }
        $parsed = collect($parsed)->only([
            'nome', 'telefone', 'email', 'bairro', 'logradouro', 'numero',
            'cep', 'municipio', 'fantasia', 'uf', 'cnpj', 'complemento'
        ])->toArray();
        if (isset($parsed['cep'])) {
            $zipcode = trim(preg_replace('/\D/', '', $parsed['cep']));
            $address = GeoLocationService::getAddressByZipcode($zipcode);
            if (is_array($address) && ($address['latitude'] ?? false) && ($address['longitude'] ?? false)) {
                $parsed['latitude'] = (float)$address['latitude'];
                $parsed['longitude'] = (float)$address['longitude'];
            }
        }
        return response()->json(['success' => true, 'data' => $parsed]);
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
