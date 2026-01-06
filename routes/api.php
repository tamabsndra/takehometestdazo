<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\SaleController;
use App\Http\Controllers\API\StoreController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Test route
Route::get('/test', function () {
    return response()->json([
        'message' => 'API is working!',
        'timestamp' => now()->toDateTimeString(),
    ]);
});

// Protected routes (require JWT authentication)
Route::middleware('jwt')->group(function () {
    
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // Profile management (all authenticated users)
    Route::put('/profile', [UserController::class, 'updateProfile']);
    
    // Super Admin only routes
    Route::middleware('role:super_admin')->group(function () {
        // Stores management
        Route::apiResource('stores', StoreController::class);
        
        // Users management (all roles)
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::put('/users/{user}', [UserController::class, 'update']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);
    });
    
    // Admin and Super Admin routes
    Route::middleware('role:admin,super_admin')->group(function () {
        // Products management
        Route::apiResource('products', ProductController::class);
        
        // Cashiers management (admin can manage their store's cashiers)
        Route::get('/cashiers', [UserController::class, 'index']);
        Route::post('/cashiers', [UserController::class, 'store']);
        Route::get('/cashiers/{user}', [UserController::class, 'show']);
        Route::put('/cashiers/{user}', [UserController::class, 'update']);
        Route::delete('/cashiers/{user}', [UserController::class, 'destroy']);
    });
    
    // All authenticated users (Admin, Cashier, Super Admin)
    Route::group([], function () {
        // Products (read-only for cashiers)
        Route::get('/products', [ProductController::class, 'index']);
        Route::get('/products/{product}', [ProductController::class, 'show']);
        
        // Sales
        Route::get('/sales', [SaleController::class, 'index']);
        Route::post('/sales', [SaleController::class, 'store']);
        Route::get('/sales/{sale}', [SaleController::class, 'show']);
        Route::get('/sales/report/statistics', [SaleController::class, 'report']);
    });
});
