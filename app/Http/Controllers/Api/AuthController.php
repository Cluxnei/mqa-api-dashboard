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
        $itemFoodParser = static fn(Food $food): array => [
            'food_id' => $food->pivot->food_id,
            'amount' => $food->pivot->amount,
            'unit_id' => $food->pivot->unit_id,
        ];
        $companyParser = static fn(Company $company): array => [
            'id' => $company->id,
            'active' => $company->active,
            'name' => $company->name,
            'cnpj' => $company->cnpj,
            'phone' => $company->phone,
            'email' => $company->email,
            'zipcode' => $company->zipcode,
            'street' => $company->street,
            'neighborhood' => $company->neighborhood,
            'address_number' => $company->address_number,
            'city' => $company->city,
            'state' => $company->state,
            'country' => $company->country,
            'latitude' => $company->latitude,
            'longitude' => $company->longitude,
            'interest_foods' => $company->interestFoods->map($itemFoodParser),
            'available_foods' => $company->availableFoods->map($itemFoodParser),
        ];
        $foodParser = static fn(Food $food): array => [
            'units' => $food->units->pluck('id')->toArray(),
            'id' => $food->id,
            'name' => $food->name,
        ];
        $unitParser = static fn(Unit $unit): array => [
            'id' => $unit->id,
            'unit' => $unit->unit,
            'slug' => $unit->slug,
        ];
        $companies = [];
        foreach ($user->companies()->with([
            'interestFoods' => static fn($query) => $query->select('foods.id', 'name', 'approved')->where('approved', '=', 1),
            'availableFoods' => static fn($query) => $query->select('foods.id', 'name', 'approved')->where('approved', '=', 1),
        ])->get() as $company) {
            $companies[] = $companyParser($company);
        }
        $user = [
            'id' => $user->id,
            'name' => $user->name,
            'cpf' => $user->cpf,
            'email' => $user->email,
            'gender' => $user->gender,
            'phone' => $user->phone,
            'companies' => $companies,
        ];
        unset($companies);
        $units = [];
        foreach (Unit::query()->select('id', 'unit', 'slug')->get() as $unit) {
            $units[] = $unitParser($unit);
        }
        $user['units'] = $units;
        unset($units);
        $foods = [];
        foreach (Food::approved()->select('id', 'approved', 'name')->with('units:id')->get() as $food) {
            $foods[] = $foodParser($food);
        }
        $user['foods'] = $foods;
        unset($foods);
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
