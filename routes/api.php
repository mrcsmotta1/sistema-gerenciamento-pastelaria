<?php

use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('/customers', CustomerController::class);
Route::post('/customers/{customer}/restore', [CustomerController::class, 'restore']);

Route::apiResource('/product-types', ProductTypeController::class);
Route::post('/product-types/{productType}/restore', [ProductTypeController::class, 'restore']);

Route::apiResource('/products', ProductController::class);
Route::post('/products/{product}/restore', [ProductController::class, 'restore']);
