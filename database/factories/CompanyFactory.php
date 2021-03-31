<?php

namespace Database\Factories;

use App\Models\Company;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws Exception
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'cnpj' => $this->faker->unique()->numerify('##############'),
            'zipcode' => $this->faker->numerify('########'),
            'email' => $this->faker->email,
            'phone' => $this->faker->numerify('###########'),
            'street' => $this->faker->streetName,
            'neighborhood' => implode(' ', $this->faker->words(3)),
            'address_number' => $this->faker->buildingNumber,
            'city' => $this->faker->city,
            'state' => $this->faker->state,
            'country' => $this->faker->country,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'deleted_at' => random_int(1, 5) === 5 ? now() : null,
        ];
    }
}
