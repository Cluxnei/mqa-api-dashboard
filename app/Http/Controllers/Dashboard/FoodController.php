<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreFoodRequest;
use App\Models\Food;
use App\Models\Unit;
use App\Services\PaginationService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    private const FOODS_PER_PAGE = 10;

    final public function index(Request $request): Renderable
    {
        $builder = Food::with('approvedByAdmin', 'requestedByUser')->withCount('units');
        $foods = PaginationService::paginateEloquentResults($request, $builder, self::FOODS_PER_PAGE, [
            'sort' => 'desc',
            'sortKey' => 'created_at',
        ]);
        $paginator = PaginationService::withSortKeys($foods);
        return view('dashboard.foods.index', compact('foods', 'paginator'));
    }

    final public function create(): Renderable
    {
        $units = Unit::all();
        return view('dashboard.foods.create', compact('units'));
    }

    final public function store(StoreFoodRequest $request)
    {
        $food = Food::query()->create([
            'name' => $request->name,
            'approved' => 1,
            'approved_by' => auth()->id(),
            'requested_by' => auth()->id(),
        ]);
        $food->units()->sync($request->units);
        return redirect()->route('dashboard.foods.index');
    }
}
