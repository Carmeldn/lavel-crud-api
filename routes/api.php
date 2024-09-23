<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\FlareClient\Api;

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

Route::middleware('auth:sanctum')->group(function () {
Route::apiResource('categories',CategoryController::class);
Route::get('/categories/{id}/products', [CategoryController::class, 'getCategoryProducts']);
Route::apiResource('products',ProductController::class);
Route::apiResource('customers',CustomerController::class);
Route::get('/customers/{id}/orders', [CustomerController::class, 'getCustomerOrders']);
Route::apiResource('orders',OrderController::class);
Route::post('/logout',[AuthController::class, 'logout']);
});

Route::post('/register',[AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);