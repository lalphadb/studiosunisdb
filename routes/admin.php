<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EcoleController;
use App\Http\Controllers\Admin\MembreController;
use Illuminate\Support\Facades\Route;

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Écoles
Route::resource('ecoles', EcoleController::class);

// Membres
Route::resource('membres', MembreController::class);
Route::get('membres/export', [MembreController::class, 'export'])->name('membres.export');

// Cours (temporaire)
Route::get('/cours', function() { 
    return view('admin.cours.index', ['cours' => collect()]); 
})->name('cours.index');

// Présences (temporaire)
Route::get('/presences', function() { 
    return view('admin.presences.index', ['presences' => collect()]); 
})->name('presences.index');
