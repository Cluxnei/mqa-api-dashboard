<?php

namespace Database\Factories;

use App\Models\Food;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class FoodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Food::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws Exception
     */
    public function definition(): array
    {
        $approved = (bool)random_int(0, 1);
        return [
            'name' => $this->faker->unique()->name,
            'approved' => $approved,
            'approved_by' => $approved
                ? User::admin()->inRandomOrder()->limit(1)->first()->id
                : null,
            'requested_by' => (bool)random_int(0, 1)
                ? User::query()->inRandomOrder()->limit(1)->first()->id
                : null,
            'deleted_at' => random_int(1, 5) === 5 ? now() : null,
        ];
    }
}
