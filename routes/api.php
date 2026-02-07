<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

// Public/Guest Routes
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/food', [FoodController::class, 'index']);
    Route::get('/food/{food}', [FoodController::class, 'show']);

    Route::get('/tables', [TableController::class, 'index']);
    Route::get('/tables/{table}', [TableController::class, 'show']);

    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);

    Route::middleware('role:waiter')->group(function () {
        Route::post('/food', [FoodController::class, 'store']);
        Route::put('/food/{food}', [FoodController::class, 'update']); 
        Route::delete('/food/{food}', [FoodController::class, 'destroy']);

        Route::post('/orders', [OrderController::class, 'store']); 
        Route::post('/orders/{order}/items', [OrderController::class, 'addItem']); 
    });

    Route::middleware('role:cashier|waiter')->group(function () {
        Route::put('/orders/{order}/close', [OrderController::class, 'close']);
    });
});