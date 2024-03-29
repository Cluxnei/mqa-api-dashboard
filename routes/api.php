<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CompanyController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(static function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/email-confirmation', [AuthController::class, 'emailConfirmation']);
    Route::post('/email-code-verification', [AuthController::class, 'emailCodeVerification']);
    Route::post('/registration', [AuthController::class, 'registration']);
   Route::middleware('auth:api')->group(static function () {
       Route::post('get-company-data', [CompanyController::class, 'getData']);
       Route::apiResources([
           'company' => CompanyController::class,
       ]);
       Route::post('/company/store-available-item/{company}', [
           CompanyController::class,
           'storeAvailableItem'
       ]);
       Route::post('/company/store-interest-item/{company}', [
           CompanyController::class,
           'storeInterestItem'
       ]);
       Route::post('/company/update-interest-item/{company}', [
           CompanyController::class,
           'updateInterestItem'
       ]);
       Route::post('/company/update-available-item/{company}', [
           CompanyController::class,
           'updateAvailableItem'
       ]);
       Route::post('/company/remove-interest-item/{company}', [
           CompanyController::class,
           'removeInterestItem'
       ]);
       Route::post('/company/remove-available-item/{company}', [
           CompanyController::class,
           'removeAvailableItem'
       ]);
       Route::post('/company/store-donation/{company}', [
           CompanyController::class,
           'storeDonation'
       ]);
       Route::post('/company/store-reception/{company}', [
           CompanyController::class,
           'storeReception'
       ]);
       Route::get('/company/closest-compatible-donations/{company}', [CompanyController::class, 'closestCompatibleDonations']);
       Route::get('/company/closest-compatible-receptions/{company}', [CompanyController::class, 'closestCompatibleReceptions']);
   });
});

