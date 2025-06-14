<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EcoleController;
use App\Http\Controllers\Admin\MembreController;
use App\Http\Controllers\Admin\CoursController;
use App\Http\Controllers\Admin\PresenceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes - StudiosUnisDB v3.7.0.0
|--------------------------------------------------------------------------
| Routes d'administration pour la gestion des écoles de karaté
| Sécurité : Auth + Permissions Spatie + Restrictions Multi-École
*/

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard principal avec métriques KPI
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Module Écoles - CRUD complet avec restrictions par rôle
    Route::resource('ecoles', EcoleController::class);
    Route::get('ecoles/export', [EcoleController::class, 'export'])
          ->name('ecoles.export')
          ->middleware('can:ecole.export');
    
    // Module Membres - CRUD avec export Excel et policies
    Route::resource('membres', MembreController::class);
    Route::get('membres/export', [MembreController::class, 'export'])
          ->name('membres.export')
          ->middleware('can:membre.export');
    
    // Module Cours - CRUD avec gestion instructeurs
    Route::resource('cours', CoursController::class);
    Route::get('cours/{cours}/membres', [CoursController::class, 'showMembres'])
          ->name('cours.membres')
          ->middleware('can:view,cours');
    
    // Module Présences - CRUD + Prise de présence rapide + Export PDF
    Route::resource('presences', PresenceController::class);
    
    // Routes spécialisées présences
    Route::prefix('presences')->name('presences.')->group(function () {
        Route::get('export-pdf', [PresenceController::class, 'exportPdf'])
              ->name('export-pdf')
              ->middleware('can:presence.export');
              
        Route::get('statistiques', [PresenceController::class, 'statistiques'])
              ->name('statistiques')
              ->middleware('can:presence.view');
    });
    
    // Interface prise de présence par cours
    Route::prefix('cours/{cours}')->name('presences.')->group(function () {
        Route::get('prise-presence', [PresenceController::class, 'prisePresence'])
              ->name('prise-presence')
              ->middleware('can:prisePresence,cours');
              
        Route::post('prise-presence', [PresenceController::class, 'storePrisePresence'])
              ->name('store-prise-presence')
              ->middleware('can:prisePresence,cours');
    });
    
    // Routes futures modules (préparation v3.8.0.0)
    // Route::resource('ceintures', CeintureController::class);
    // Route::resource('seminaires', SeminaireController::class);
    // Route::resource('paiements', PaiementController::class);
});
