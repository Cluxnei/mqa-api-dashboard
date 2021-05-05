<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Food;
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

//    final public function show(Company $company): Renderable
//    {
//        $company->load('users');
//        $timeline = TimelineService::fromCompany($company);
//        return view('dashboard.companies.show', compact('company', 'timeline'));
//    }
//
//    final public function active(Company $company): RedirectResponse
//    {
//        $company->update(['active' => 1]);
//        return redirect()->route('dashboard.companies.show', $company->id);
//    }
//
//    final public function inactive(Company $company): RedirectResponse
//    {
//        $company->update(['active' => 0]);
//        return redirect()->route('dashboard.companies.show', $company->id);
//    }
}
