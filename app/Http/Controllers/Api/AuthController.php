<?php

namespace App\Http\Controllers\Api;

use App\Events\UserEmailVerification;
use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmailCodeVerificationRequest;
use App\Http\Requests\EmailConfirmationRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\Company;
use App\Models\Food;
use App\Models\Unit;
use App\Models\User;
use App\Models\VerificationCode;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private const userExceptFields = ['history', 'created_at', 'updated_at', 'deleted_at', 'email_verified_at', 'phone_verified_at'];
    private const foodExceptFields = ['pivot', 'created_at', 'updated_at', 'deleted_at', 'approved_by', 'requested_by', 'approved', 'category'];
    private const companyExceptFields = ['created_at', 'updated_at', 'deleted_at', 'history', 'pivot'];

    private static function optimizeArray(array $array, array $exceptFields): array
    {
        foreach ($exceptFields as $key) {
            unset($array[$key]);
        }
        return $array;
    }

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
        $companies = $user->companies()->with([
            'interestFoods' => static fn($query) => $query->where('approved', '=', 1)->with('units:id'),
            'availableFoods' => static fn($query) => $query->where('approved', '=', 1)->with('units:id'),
        ])->get()->map(static function (Company $company) {
            $interestFoods = $company->interestFoods->map(static fn(Food $food): array => [
                'food_id' => $food->pivot->food_id,
                'amount' => $food->pivot->amount,
                'unity_id' => $food->pivot->unit_id,
            ]);
            $availableFoods = $company->availableFoods->map(static fn(Food $food): array => [
                'food_id' => $food->pivot->food_id,
                'amount' => $food->pivot->amount,
                'unity_id' => $food->pivot->unit_id,
            ]);
            $company->setAttribute('_interest_foods', $interestFoods)->setAttribute('_available_foods', $availableFoods);
            $c = $company->toArray();
            unset($c['interest_foods'], $c['available_foods']);
            return self::optimizeArray($c, self::companyExceptFields);
        });
        $units = Unit::query()->select('id', 'unit', 'slug')->get(['id', 'unit', 'slug'])->toArray();
        $foods = Food::approved()->with('units:id')->get()->map(
            static function (Food $food) {
                $units = $food->units->pluck('id')->values()->toArray();
                $f = $food->setAttribute('_units', $units)->toArray();
                unset($f['units']);
                return self::optimizeArray($f, self::foodExceptFields);
            }
        );
        $user->setAttribute('_foods', $foods)->setAttribute('_units', $units)->setAttribute('_companies', $companies);
        return response()->json([
            'user' => self::optimizeArray($user->toArray(), self::userExceptFields),
            'token' => [
                'accessToken' => $token->accessToken,
                'expires_at' => $token->token->expires_at
            ]
        ]);
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
