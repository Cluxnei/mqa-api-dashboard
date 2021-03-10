<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\api\CompanyController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(static function () {
    Route::apiResources([
        'company' => CompanyController::class,
    ]);
});
