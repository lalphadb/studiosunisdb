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
    
    // Écoles
    Route::resource('ecoles', EcoleController::class);
    
    // Membres
    Route::resource('membres', MembreController::class);
    Route::get('membres/export', [MembreController::class, 'export'])->name('membres.export');
    
    // Routes pour ceintures et séminaires membres (futures)
    Route::post('membres/{membre}/attribuer-ceinture', [MembreController::class, 'attribuerCeinture'])->name('membres.attribuer-ceinture');
    Route::post('membres/{membre}/inscrire-seminaire', [MembreController::class, 'inscrireSeminaire'])->name('membres.inscrire-seminaire');
    
    // Cours (maintenant activé)
    Route::resource('cours', CoursController::class);
    
    // Présences (temporairement désactivé) 
    // Route::resource('presences', PresenceController::class);
});
