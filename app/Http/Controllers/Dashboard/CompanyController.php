<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\CompanyActivated;
use App\Events\CompanyInactivated;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Services\PaginationService;
use App\Services\TimelineService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    private const COMPANIES_PER_PAGE = 10;

    final public function index(Request $request): Renderable
    {
        $builder = Company::query()->withCount('users');
        $companies = PaginationService::paginateEloquentResults($request, $builder, self::COMPANIES_PER_PAGE, [
            'sort' => 'desc',
            'sortKey' => 'created_at',
        ]);
        $paginator = PaginationService::withSortKeys($companies);
        return view('dashboard.companies.index', compact('companies', 'paginator'));
    }

    final public function show(Company $company): Renderable
    {
        $company->load('users');
        $timeline = TimelineService::fromCompany($company);
        return view('dashboard.companies.show', compact('company', 'timeline'));
    }

    final public function active(Company $company): RedirectResponse
    {
        $company->update(['active' => 1]);
        $company->load('users');
        event(new CompanyActivated($company));
        return redirect()->route('dashboard.companies.show', $company->id);
    }

    final public function inactive(Company $company): RedirectResponse
    {
        $company->update(['active' => 0]);
        $company->load('users');
        event(new CompanyInactivated($company));
        return redirect()->route('dashboard.companies.show', $company->id);
    }
}
