<?php

use App\Http\Controllers\Auth\StudiosDBAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [StudiosDBAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [StudiosDBAuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [StudiosDBAuthController::class, 'logout'])->name('logout');
});
