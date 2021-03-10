<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    final public function login(LoginRequest $request): JsonResponse {
        $user = User::query()->firstWhere('cpf', '=', $request->cpf);
        if (null === $user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciais inválidas'], Response::HTTP_UNAUTHORIZED);
        }
        $token = $user->createToken('AuthToken');
        return response()->json(compact('user', 'token'));
    }
}
