<?php

namespace Database\Factories;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws Exception
     */
    public function definition(): array
    {
        return [
            'is_admin' => random_int(0, 1),
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->email,
            'cpf' => $this->faker->unique()->numerify('###########'),
            'phone' =>  $this->faker->unique()->numerify('###########'),
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'deleted_at' => random_int(1, 5) === 5 ? now() : null,
        ];
    }
}
