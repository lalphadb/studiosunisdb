<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Route de base
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard public - redirection vers admin
Route::get('/dashboard', function () {
    return redirect('/admin/dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes Profile (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Inclure les routes auth
require __DIR__.'/auth.php';

// Inclure les routes admin séparées
require __DIR__.'/admin.php';
