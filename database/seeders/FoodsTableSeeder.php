<?php

namespace Database\Seeders;

use App\Models\Food;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use JsonException;

class FoodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws JsonException
     */
    final public function run(): void
    {
        $foods = File::get(database_path('seeders/foods.json'));
        $foods = json_decode($foods, true, 512, JSON_THROW_ON_ERROR);
        $foods = collect($foods);
        $foods = $foods->filter(static fn(array $food) => $foods->where('name', '=', $food['name'])->count() === 1)
            ->map(static fn(array $food) => [
                'name' => $food['name'],
                'category' => $food['category'] === '' ? null : $food['category'],
                'approved' => 1,
            ])->toArray();
        Food::query()->insert($foods);
        $unitsIds = Unit::query()->pluck('id');
        Food::all()->each(static function (Food $food) use ($unitsIds) {
            $food->units()->sync($unitsIds);
        });
    }
}
