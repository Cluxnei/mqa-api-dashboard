<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CompanyController extends Controller
{
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
     * @param Request $request
     * @return Response
     */
    final public function store(Request $request): JsonResponse
    {
        return response()->json([]);
    }

    /**
     * Display the specified resource.
     *
     * @param Company $company
     * @return Response
     */
    final public function show(Company $company): JsonResponse
    {
        return response()->json([]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Company $company
     * @return JsonResponse
     */
    final public function update(Request $request, Company $company): JsonResponse
    {
        return response()->json([]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Company $company
     * @return Response
     */
    final public function destroy(Company $company): JsonResponse
    {
        return response()->json([]);
    }
}
