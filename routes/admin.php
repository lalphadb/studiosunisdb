<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EcoleController;
use App\Http\Controllers\Admin\MembreController;
use App\Http\Controllers\Admin\CoursController;
use App\Http\Controllers\Admin\PresenceController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    
    // Écoles
    Route::resource('ecoles', EcoleController::class);
    
    // Membres
    Route::resource('membres', MembreController::class);
    Route::get('membres/export', [MembreController::class, 'export'])->name('membres.export');
    
    // Cours
    Route::resource('cours', CoursController::class);
    Route::get('cours/{cours}/duplicate', [CoursController::class, 'duplicate'])->name('cours.duplicate');
    
    // Présences
    Route::resource('presences', PresenceController::class);
});
