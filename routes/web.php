<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\SaleController;
use App\Http\Controllers\Web\StoreController;
use App\Http\Controllers\Web\POSController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/login', function () { return redirect()->route('login'); });
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Stores (Super Admin only)
    Route::resource('stores', StoreController::class)->middleware('role:super_admin');
    
    // Users (Super Admin only)
    Route::resource('users', \App\Http\Controllers\Web\UserController::class)->middleware('role:super_admin');
    
    // Products (Admin and Super Admin)
    Route::resource('products', ProductController::class)->middleware('role:admin,super_admin');
    
    // Sales (All authenticated users)
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
    
    // POS (Admin and Cashier)
    Route::get('/pos', [POSController::class, 'index'])->name('pos')->middleware('role:admin,cashier');
    Route::post('/pos', [POSController::class, 'store'])->name('pos.store')->middleware('role:admin,cashier');
});
