<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\CompanyController;
use App\Http\Controllers\V1\EmployeeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function() {
	Route::apiResource('companies', CompanyController::class);
	Route::apiResource('companies.employees', EmployeeController::class);
});

Route::fallback(function(){
    return response()->json([
        'error' => 'Nieobsługiwany endpoint api.'], 404);
});