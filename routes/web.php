<?php

use App\Http\Controllers\Dashboard\CompanyController;
use App\Http\Controllers\Dashboard\FoodController;
use App\Http\Controllers\Dashboard\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware('auth')->name('dashboard.')->prefix('dashboard')->group(static function() {
    Route::get('/', [App\Http\Controllers\Dashboard\HomeController::class, 'index'])->name('home');
    Route::resources([
        'users' => UserController::class,
        'companies' => CompanyController::class,
        'foods' => FoodController::class,
    ]);
    Route::name('users.')->prefix('/users')->group(static function () {
        Route::get('/active/{user}', [UserController::class, 'active'])->name('active');
        Route::get('/inactive/{user}', [UserController::class, 'inactive'])->name('inactive');
    });
    Route::name('companies.')->prefix('/companies')->group(static function () {
        Route::get('/active/{company}', [CompanyController::class, 'active'])->name('active');
        Route::get('/inactive/{company}', [CompanyController::class, 'inactive'])->name('inactive');
    });
});


