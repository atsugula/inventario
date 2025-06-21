<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\V1\ProductController;
use App\Http\Controllers\V1\CategoryController;

// Público
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Requieren autenticación
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Rutas de solo lectura para todos los usuarios autenticados
    Route::apiResource('products', ProductController::class)->only(['index', 'show']);
    Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);

    // Rutas de modificación solo para admin
    Route::middleware('admin')->group(function () {
        Route::apiResource('products', ProductController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('categories', CategoryController::class)->only(['store', 'update', 'destroy']);
    });
});
