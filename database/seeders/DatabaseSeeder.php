<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Food;
use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        User::query()->create([
            'name' => 'Developer Master',
            'email' => 'dev@dev.dev',
            'cpf' => '00000000000',
            'phone' => '00000000000',
            'is_admin' => 1,
            'active' => 1,
            'password' => bcrypt('dev'),
        ]);
        $users = User::factory()->count(10)->create();
        $this->call([UnitsTableSeeder::class, FoodsTableSeeder::class]);
        $companies = Company::factory()->count(10)->create();
        $foods = Food::withTrashed()->get();
        $companies->each(static function (Company $company) use ($foods, $users) {
            $users->random(random_int(1, 4))->each(static function (User $user) use ($company) {
                $company->users()->attach($user->id);
            });
            $foods->random(random_int(1, 30))->each(static function (Food $food) use ($company) {
                $unit = $food->units(true)->inRandomOrder()->limit(1)->first();
                $user = $company->users(true)->inRandomOrder()->limit(1)->first();
                if (null === $unit || null === $user) {
                    return;
                }
                $company->foods()->attach($food->id, [
                    'unit_id' => $unit->id,
                    'requested_by' => $user->id,
                    'type' => random_int(0, 1) ? 'interest' : 'available',
                    'amount' => (float)random_int(1, 1000) / (float)random_int(1, 3),
                ]);
            });
        });
    }
}
