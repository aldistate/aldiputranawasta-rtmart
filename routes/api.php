<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::middleware(['auth:sanctum'])->group(function () {
  Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
  Route::get('/authcheck', [AuthController::class, 'authCheck'])->name('authCheck');
  Route::post('/cart/{product}', [OrderController::class, 'addToCart'])->name('storeCart');
  Route::post('/checkout/{id}', [OrderController::class, 'checkout'])->name('checkout');
});

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/product', [ProductController::class, 'index'])->name('indexProduct');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('showProduct');
