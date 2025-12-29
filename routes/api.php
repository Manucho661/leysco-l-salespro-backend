<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\InventoryController;
use App\Http\Controllers\Api\V1\ProductController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    // Auth routes with rate limiting
    Route::middleware('throttle:5,1')->group(function () {
        Route::post('/auth/login', [AuthController::class, 'login']);
    });

    // Password reset (public)
    Route::post('/auth/password/forgot', [AuthController::class, 'forgotPassword']);
    Route::post('/auth/password/reset', [AuthController::class, 'resetPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/user', [AuthController::class, 'user']);
        Route::post('/auth/refresh', [AuthController::class, 'refresh']);

        // Inventory Management
        Route::prefix('products')->group(function () {
            Route::get('/', [ProductController::class, 'index']); // List products with pagination
            Route::get('/{product}', [ProductController::class, 'show']); // Product details
            Route::post('/', [ProductController::class, 'store']); // Create product
            Route::put('/{id}', [ProductController::class, 'update']); // Update product
            Route::delete('/{id}', [ProductController::class, 'destroy']); // Soft delete


            Route::get('/low-stock', [InventoryController::class, 'lowStock']); // Products below reorder level
            Route::get('/{product}/stock', [InventoryController::class, 'stock']); // Real-time stock
            Route::post('/{id}/reserve', [InventoryController::class, 'reserve']); // Reserve stock
            Route::post('/{id}/release', [InventoryController::class, 'release']); // Release reserved stock
        });
    });
});
