<?php

namespace App\Http\Controllers\Api;

use App\Events\UserEmailVerification;
use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmailCodeVerificationRequest;
use App\Http\Requests\EmailConfirmationRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use App\Models\VerificationCode;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    final public function login(LoginRequest $request): JsonResponse
    {
        $user = User::query()->firstWhere('email', '=', $request->email);
        if (null === $user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciais inválidas'], Response::HTTP_UNAUTHORIZED);
        }
        if (!$user->isActive()) {
            return response()->json(['message' => 'Usuário em aprovação'], Response::HTTP_UNAUTHORIZED);
        }
        $token = $user->createToken('AuthToken');
        $token = [
            'accessToken' => $token->accessToken,
            'expires_at' => $token->token->expires_at
        ];
        $user->load([
            'companies' => static function ($query) {
                $query->with([
                    'interestFoods' => static function ($query) {
                        $query->with('units');
                    },
                    'availableFoods' => static function ($query) {
                        $query->with('units');
                    },
                ]);
            },
        ]);
        return response()->json(compact('user', 'token'));
    }

    /**
     * @param EmailConfirmationRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    final public function emailConfirmation(EmailConfirmationRequest $request): JsonResponse
    {
        if (User::query()->where('email', '=', $request->email)->exists()) {
            return response()->json([
                'success' => false,
                'exists' => true,
            ]);
        }
        event(new UserEmailVerification($request->email));
        return response()->json([
            'success' => true,
            'exists' => false,
        ]);
    }

    /**
     * @param EmailCodeVerificationRequest $request
     * @return JsonResponse
     */
    final public function emailCodeVerification(EmailCodeVerificationRequest $request): JsonResponse
    {
        VerificationCode::query()->where('expires_at', '<=', now()->toDateString())->delete();
        $verified = VerificationCode::query()->where([
            'email' => $request->email,
            'code' => $request->code,
        ])->exists();
        $verified && VerificationCode::query()->where('email', '=', $request->email)->delete();
        return response()->json(compact('verified'));
    }

    final public function registration(RegistrationRequest $request): JsonResponse
    {
        $user = User::query()->create([
            'is_admin' => 0,
            'active' => 0,
            'name' => trim($request->name),
            'cpf' => preg_replace('/\D/', '', trim($request->cpf)),
            'phone' => preg_replace('/\D/', '', trim($request->phone)),
            'email' => trim($request->email),
            'email_verified_at' => now()->toDateTimeString(),
            'password' => Hash::make(trim($request->password)),
            'gender' => trim($request->gender),
        ]);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Erro ao criar seu usuário']);
        }
        event(new UserRegistered($user));
        return response()->json([
            'success' => true,
            'message' => 'Sua conta foi criada com sucesso!'
                . PHP_EOL
                . 'Você ainda precisa ser aprovado por nossos administradores, fique tranquil'
                . ($user->gender === 'F' ? 'a' : 'o')
                . ' que te notificaremos de novas atualizações por e-mail.',
        ]);
    }
}
