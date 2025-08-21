<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MembreController;
use App\Http\Controllers\CoursController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    // Paiements - Routes en français
    Route::resource('paiements', \App\Http\Controllers\PaiementController::class);
    // Présences - Routes en français
    Route::get('presences', [\App\Http\Controllers\PresenceController::class, 'index'])->name('presences.index');
    Route::get('presences/tablette', [\App\Http\Controllers\PresenceController::class, 'tablette'])->name('presences.tablette');
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, '__invoke'])->name('dashboard');
    
    // Membres - Routes en français (FR canonique)
    Route::resource('membres', MembreController::class);
    Route::get('/membres/export', [MembreController::class, 'export'])->name('membres.export');
    Route::post('/membres/bulk', [MembreController::class, 'bulk'])->name('membres.bulk');
    Route::post('/membres/{membre}/changer-ceinture', [MembreController::class, 'changerCeinture'])->name('membres.changer-ceinture');

    // Cours - Routes en français
    Route::resource('cours', CoursController::class);
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
Route::post('/membres/bulk', [\App\Http\Controllers\MembreController::class, 'bulk'])->name('membres.bulk');
