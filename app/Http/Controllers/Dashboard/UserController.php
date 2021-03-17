<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PaginationService;
use App\Services\TimelineService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private const USERS_PER_PAGE = 10;

    final public function index(Request $request): Renderable {
        $builder = User::query()->withCount('companies');
        $users = PaginationService::paginateEloquentResults($request, $builder, self::USERS_PER_PAGE, [
            'sort' => 'desc',
            'sortKey' => 'created_at',
        ]);
        $paginator = PaginationService::withSortKeys($users);
        return view('dashboard.users.index', compact('users', 'paginator'));
    }

    final public function show(User $user): Renderable {
        $user->load('companies');
        $timeline = TimelineService::fromUser($user);
        return view('dashboard.users.show', compact('user', 'timeline'));
    }

    final public function active(User $user): RedirectResponse
    {
        $user->update(['active' => 1]);
        return redirect()->route('dashboard.users.show', $user->id);
    }

    final public function inactive(User $user): RedirectResponse
    {
        $user->update(['active' => 0]);
        return redirect()->route('dashboard.users.show', $user->id);
    }
}
