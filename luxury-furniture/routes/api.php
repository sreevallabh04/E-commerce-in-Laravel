<?php

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

// Product API Routes
Route::get('/products', [App\Http\Controllers\ProductController::class, 'apiIndex']);
Route::get('/products/{product}', [App\Http\Controllers\ProductController::class, 'apiShow']);
Route::get('/categories', [App\Http\Controllers\ProductController::class, 'apiCategories']);

// Cart API Routes
Route::get('/cart', [App\Http\Controllers\CartController::class, 'apiIndex']);
Route::post('/cart/add', [App\Http\Controllers\CartController::class, 'apiAdd']);
Route::put('/cart/update', [App\Http\Controllers\CartController::class, 'apiUpdate']);
Route::delete('/cart/remove', [App\Http\Controllers\CartController::class, 'apiRemove']);
Route::post('/cart/clear', [App\Http\Controllers\CartController::class, 'apiClear']); 