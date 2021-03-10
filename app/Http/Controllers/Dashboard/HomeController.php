<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Food;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    final public function index(): Renderable
    {
        $counters = $this->getCounters();
        return view('dashboard.home', compact('counters'));
    }

    private function getCounters(): object
    {
        return (object)[
            'users' => $this->getTotalActiveInactiveCounts(User::class),
            'companies' => $this->getTotalActiveInactiveCounts(Company::class),
            'units' => $this->getTotalActiveInactiveCounts(Unit::class),
            'foods' => $this->getTotalActiveInactiveCounts(Food::class),
            'foods_available' => (object)[
                'total' => DB::table('companies_foods')->where('type', '=', 'available')->count(),
                'active' => DB::table('companies_foods')->whereNull('deleted_at')
                    ->where('type', '=', 'available')->count(),
                'inactive' => DB::table('companies_foods')->whereNotNull('deleted_at')
                    ->where('type', '=', 'available')->count(),
            ],
            'foods_interests' => (object)[
                'total' => DB::table('companies_foods')->where('type', '=', 'interest')->count(),
                'active' => DB::table('companies_foods')->whereNull('deleted_at')
                    ->where('type', '=', 'interest')->count(),
                'inactive' => DB::table('companies_foods')->whereNotNull('deleted_at')
                    ->where('type', '=', 'interest')->count(),
            ],
        ];
    }

    private function getTotalActiveInactiveCounts(string $model): object
    {
        $instance = new $model();
        return (object)[
            'total' => $instance->withTrashed()->count(),
            'active' => $instance->query()->count(),
            'inactive' => $instance->withTrashed()->whereNotNull('deleted_at')->count(),
        ];
    }
}
