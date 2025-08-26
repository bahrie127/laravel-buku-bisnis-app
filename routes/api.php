<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes (tidak perlu authentication)
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected routes (perlu authentication dengan Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Auth routes
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Account routes
    Route::apiResource('accounts', AccountController::class);

    // Category routes
    Route::apiResource('categories', CategoryController::class);

    // Transaction routes
    Route::apiResource('transactions', TransactionController::class);

    // Additional transaction endpoints
    Route::post('/transactions/transfer', [TransactionController::class, 'createTransfer']);
    Route::get('/transactions-statistics', [TransactionController::class, 'statistics']);
});
