<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EcoleController;
use App\Http\Controllers\Admin\CoursController;
use App\Http\Controllers\Admin\CeintureController;
use App\Http\Controllers\Admin\SeminaireController;
use App\Http\Controllers\Admin\PaiementController;
use App\Http\Controllers\Admin\PresenceController;
use App\Http\Controllers\Admin\InscriptionSeminaireController;

/*
|--------------------------------------------------------------------------
| Routes Administrateur - Laravel 12.19
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard principal
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    
    // Gestion des utilisateurs (membres)
    Route::resource('users', UserController::class);
    Route::get('users/{user}/qrcode', [UserController::class, 'qrcode'])->name('users.qrcode');
    Route::get('users/export', [UserController::class, 'export'])->name('users.export');
    
    // Gestion des écoles
    Route::resource('ecoles', EcoleController::class);
    
    // Gestion des cours
    Route::resource('cours', CoursController::class);
    
    // Gestion des ceintures - SUIVI PROGRESSION - ROUTES SPÉCIALES EN PREMIER
    Route::get('ceintures/attribution-masse', [CeintureController::class, 'createMasse'])->name('ceintures.create-masse');
    Route::post('ceintures/attribution-masse', [CeintureController::class, 'storeMasse'])->name('ceintures.store-masse');
    
    // Gestion des ceintures - RESOURCE APRÈS (suivi progression)
    Route::resource('ceintures', CeintureController::class);
    
    // Gestion des séminaires
    Route::resource('seminaires', SeminaireController::class);
    Route::match(['get', 'post'], 'seminaires/{seminaire}/inscrire', [SeminaireController::class, 'inscrire'])->name('seminaires.inscrire');
    
    // Gestion des paiements
    Route::resource('paiements', PaiementController::class);
    
    // Gestion des présences
    Route::resource('presences', PresenceController::class);
    
    // Gestion des inscriptions aux séminaires
    Route::resource('inscriptions-seminaires', InscriptionSeminaireController::class)->only(['index', 'destroy']);
    
    // Logs et monitoring (accès restreint)
    Route::get('logs', function() {
        abort_unless(auth()->user()->hasRole('superadmin'), 403);
        return view('admin.logs.index');
    })->name('logs.index');
    
});
