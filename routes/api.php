<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Support\Facades\Route;

// User
Route::post('/users/register', [UserController::class, 'register']);
Route::post('/users/login', [UserController::class, 'login']);

Route::middleware(ApiAuthMiddleware::class)->group(function () {
    // User
    Route::get('/users/profile', [UserController::class, 'profile']);
    Route::delete('/users/logout', [UserController::class, 'logout']);

    // Category
    Route::post('/categories/create', [CategoryController::class, 'create']);
    Route::put('/categories/update/{id}', [CategoryController::class, 'update'])
        ->where('id', '[0-9]+');
    Route::get('/categories/view/{id}', [CategoryController::class, 'view'])
        ->where('id', '[0-9]+');
    Route::delete('/categories/delete/{id}', [CategoryController::class, 'delete'])
        ->where('id', '[0-9]+');
    Route::get('/categories', [CategoryController::class, 'index']);

    // Expense
    Route::post('/expenses/create', [ExpenseController::class, 'create']);
    Route::put('/expenses/update/{id}', [ExpenseController::class, 'update'])
        ->where('id', '[0-9]+');
    Route::get('/expenses/view/{id}', [ExpenseController::class, 'view'])
        ->where('id', '[0-9]+');
    Route::delete('/expenses/delete/{id}', [ExpenseController::class, 'delete'])
        ->where('id', '[0-9]+');
    Route::get('/expenses', [ExpenseController::class, 'index']);
});
