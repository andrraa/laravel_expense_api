<?php

use Illuminate\Support\Facades\Route;

Route::post('/users/register', [\App\Http\Controllers\UserController::class, 'register']);
