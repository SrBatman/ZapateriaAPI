<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductsController;
use App\Http\Controllers\API\ProvidersController;
use App\Http\Controllers\API\AdminsController;
use App\Http\Controllers\API\CostumersController;
use App\Http\Controllers\API\UserAuthController as UserAuth;
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


Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/products/{id}', [ProductsController::class, 'show'])->name('products.show');
    Route::post('/products', [ProductsController::class, 'store'])->name('products.store');
    Route::put('/products/{id}', [ProductsController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductsController::class, 'destroy'])->name('products.destroy');

    Route::get('/providers', [ProvidersController::class, 'index'])->name('providers.index');
    Route::get('/providers/{id}', [ProvidersController::class, 'show'])->name('providers.show');
    Route::post('/providers', [ProvidersController::class, 'store'])->name('providers.store');
    Route::put('/providers/{id}', [ProvidersController::class, 'update'])->name('providers.update');
    Route::delete('/providers/{id}', [ProvidersController::class, 'destroy'])->name('providers.destroy');

    Route::get('/admins', [AdminsController::class, 'index'])->name('admins.index');
    Route::get('/admins/{id}', [AdminsController::class, 'show'])->name('admins.show');
    Route::post('/admins', [AdminsController::class, 'store'])->name('admins.store');
    Route::put('/admins/{id}', [AdminsController::class, 'update'])->name('admins.update');
    Route::delete('/admins/{id}', [AdminsController::class, 'destroy'])->name('admins.destroy');

    Route::get('/customers', [CostumersController::class, 'index'])->name('customers.index');
    Route::get('/customers/{id}', [CostumersController::class, 'show'])->name('customers.show');
    Route::post('/customers', [CostumersController::class, 'store'])->name('customers.store');
    Route::put('/customers/{id}', [CostumersController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{id}', [CostumersController::class, 'destroy'])->name('customers.destroy');





});

// Rutas de autenticación
Route::post('/register', [UserAuth::class, 'store']);
Route::post('/login', [UserAuth::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [UserAuth::class, 'logout']);