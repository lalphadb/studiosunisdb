<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EcoleController;
use App\Http\Controllers\Admin\MembreController;
use App\Http\Controllers\Admin\CoursController;
use App\Http\Controllers\Admin\PresenceController;
use Illuminate\Support\Facades\Route;

// Routes admin avec middleware auth et permission
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.index');
    
    // Écoles
    Route::resource('ecoles', EcoleController::class)->names([
        'index' => 'admin.ecoles.index',
        'create' => 'admin.ecoles.create',
        'store' => 'admin.ecoles.store',
        'show' => 'admin.ecoles.show',
        'edit' => 'admin.ecoles.edit',
        'update' => 'admin.ecoles.update',
        'destroy' => 'admin.ecoles.destroy',
    ]);
    
    // Membres
    Route::resource('membres', MembreController::class)->names([
        'index' => 'admin.membres.index',
        'create' => 'admin.membres.create',
        'store' => 'admin.membres.store',
        'show' => 'admin.membres.show',
        'edit' => 'admin.membres.edit',
        'update' => 'admin.membres.update',
        'destroy' => 'admin.membres.destroy',
    ]);
    Route::get('membres/export', [MembreController::class, 'export'])->name('admin.membres.export');
    
    // Cours
    Route::resource('cours', CoursController::class)->names([
        'index' => 'admin.cours.index',
        'create' => 'admin.cours.create',
        'store' => 'admin.cours.store',
        'show' => 'admin.cours.show',
        'edit' => 'admin.cours.edit',
        'update' => 'admin.cours.update',
        'destroy' => 'admin.cours.destroy',
    ]);
    
    // Route de duplication des cours
    Route::get('cours/{cours}/duplicate', [CoursController::class, 'duplicate'])->name('admin.cours.duplicate');
    
    // Présences
    Route::resource('presences', PresenceController::class)->names([
        'index' => 'admin.presences.index',
        'create' => 'admin.presences.create',
        'store' => 'admin.presences.store',
        'show' => 'admin.presences.show',
        'edit' => 'admin.presences.edit',
        'update' => 'admin.presences.update',
        'destroy' => 'admin.presences.destroy',
    ]);
});
