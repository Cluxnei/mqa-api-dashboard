<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    final public function run(): void
    {
        Unit::query()->insert($this->getUnits());

    }

    final public function getUnits(): array
    {
        return [
            $this->createUnit('mg', 'miligrama'),
            $this->createUnit('g', 'grama'),
            $this->createUnit('kg', 'quilograma'),
            $this->createUnit('t', 'tonelada'),
            $this->createUnit('l', 'litro'),
            $this->createUnit('xicara', 'xícara'),
            $this->createUnit('un', 'unidade'),
            $this->createUnit('fatia', 'fatia'),
            $this->createUnit('colher-de-sopa', 'colher de sopa'),
        ];
    }

    private function createUnit(string $slug, string $unit): array
    {
        return compact('slug', 'unit');
    }
}
