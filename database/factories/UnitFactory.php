<?php

namespace Database\Factories;

use App\Models\Unit;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UnitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Unit::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws Exception
     */
    public function definition(): array
    {
        $unit = $this->faker->unique()->word;
        return [
            'unit' => $unit,
            'slug' => Str::slug($unit),
            'deleted_at' => random_int(1, 5) === 5 ? now() : null,
        ];
    }
}
