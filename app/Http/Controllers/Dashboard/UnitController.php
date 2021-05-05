<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Services\PaginationService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    private const UNITS_PER_PAGE = 10;

    final public function index(Request $request): Renderable
    {
        $builder = Unit::query()->withCount('foods');
        $units = PaginationService::paginateEloquentResults($request, $builder, self::UNITS_PER_PAGE, [
            'sort' => 'desc',
            'sortKey' => 'created_at',
        ]);
        $paginator = PaginationService::withSortKeys($units);
        return view('dashboard.units.index', compact('units', 'paginator'));
    }
}
