<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\AdminWalletController;

// Public routes — no token needed
// These two routes are accessible by anyone
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

// Protected routes — token required
// auth:api middleware runs BEFORE the controller method
// If token is missing/invalid → 401 automatically, controller never runs
Route::middleware('auth:api')->group(function () {

    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::get('/me',     [AuthController::class, 'me']);
        Route::post('/logout',[AuthController::class, 'logout']);
    });

    // Wallet routes (any authencticated user)
    Route::prefix('wallet')->group(function () {
        Route::get('/',      [WalletController::class, 'balance']);
        Route::post('/spend',[WalletController::class, 'spend']);
    });

        // Admin routes (authenticated + admin role)
    // 'role:admin' runs CheckRole middleware with $role = 'admin'
    Route::middleware('role:admin')->prefix('admin/wallet')->group(function () {
        Route::post('/{user}/credit', [AdminWalletController::class, 'credit']);
        Route::post('/{user}/debit',  [AdminWalletController::class, 'debit']);
    });

});