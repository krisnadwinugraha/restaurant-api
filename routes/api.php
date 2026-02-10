<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Tamu / Guest)
|--------------------------------------------------------------------------
*/
Route::get('/tables', [TableController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Protected Routes (Authenticated Staff)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth & Profile
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // View-Only Resources (Both Waiter & Cashier need these)
    Route::get('/food', [FoodController::class, 'index']);
    Route::get('/food/{food}', [FoodController::class, 'show']);
    Route::get('/tables/{table}', [TableController::class, 'show']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::get('/orders/{order}/receipt', [OrderController::class, 'downloadReceipt']);
    /*
    |--------------------------------------------------------------------------
    | Waiter Specific (Pelayan)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:waiter')->group(function () {
        // Food CRUD
        Route::post('/food', [FoodController::class, 'store']);
        Route::put('/food/{food}', [FoodController::class, 'update']); 
        Route::delete('/food/{food}', [FoodController::class, 'destroy']);

        // Order Management
        Route::post('/orders', [OrderController::class, 'store']); // Open Order
        
        Route::post('/orders/{order}/items', [OrderController::class, 'addItem']);
        Route::put('/orders/{order}/items/{item}', [OrderController::class, 'updateItem']); 
        Route::delete('/orders/{order}/items/{item}', [OrderController::class, 'removeItem']);
    });

    /*
    |--------------------------------------------------------------------------
    | Shared Actions (Cashier & Waiter)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:cashier|waiter')->group(function () {
        // Tutup Pesanan
        Route::post('/orders/{order}/close', [OrderController::class, 'close']);
    });
});