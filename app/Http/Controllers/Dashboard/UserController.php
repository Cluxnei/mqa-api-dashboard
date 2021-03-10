<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class UserController extends Controller
{
    final public function index(Request $request): Renderable {
        $users = User::query()->orderByDesc('is_admin')->paginate(10);
        return view('dashboard.users.index', compact('users'));
    }
}
