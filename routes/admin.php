<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EcoleController;
use App\Http\Controllers\Admin\MembreController;
use App\Http\Controllers\Admin\CoursController;
use Illuminate\Support\Facades\Route;

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Écoles
Route::resource('ecoles', EcoleController::class);

// Membres
Route::resource('membres', MembreController::class);
Route::get('membres/export', [MembreController::class, 'export'])->name('membres.export');

// Cours
Route::resource('cours', CoursController::class);

// Présences (temporaire)
Route::get('/presences', function() { 
    return view('admin.presences.index', ['presences' => collect()]); 
})->name('presences.index');
