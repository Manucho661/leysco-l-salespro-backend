<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\InventoryController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\OrdersController;

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
            Route::get('/', [ProductController::class, 'index']); // List products
            Route::get('/{product}', [ProductController::class, 'show']); // Product details
            Route::post('/', [ProductController::class, 'store']); // Create product
            Route::put('/{id}', [ProductController::class, 'update']); // Update product
            Route::delete('/{id}', [ProductController::class, 'destroy']); // Soft delete

            // Route::get('/low-stock', [InventoryController::class, 'lowStock']); // Low stock
            // Route::get('/{product}/stock', [InventoryController::class, 'stock']); // Stock
            // Route::post('/{id}/reserve', [InventoryController::class, 'reserve']); // Reserve
            // Route::post('/{id}/release', [InventoryController::class, 'release']); // Release
        });

        // Sales Orders Management
        Route::prefix('orders')->group(function () {
            Route::get('/', [OrdersController::class, 'index']); // List orders with filters
            Route::get('/{id}', [OrdersController::class, 'show']); // Order details
            Route::post('/', [OrdersController::class, 'store']); // Create new order
            Route::put('/{id}/status', [OrdersController::class, 'updateStatus']); // Update order status
            Route::get('/{id}/invoice', [OrdersController::class, 'invoice']); // Generate invoice
            Route::post('/calculate-total', [OrdersController::class, 'calculateTotal']); // Preview order calculation
        });
    });
});
