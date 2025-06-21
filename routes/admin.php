<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EcoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CoursController;
use App\Http\Controllers\Admin\PresenceController;
use App\Http\Controllers\Admin\CeintureController;
use App\Http\Controllers\Admin\SeminaireController;
use App\Http\Controllers\Admin\PaiementController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Écoles (SuperAdmin uniquement)
    Route::resource('ecoles', EcoleController::class);
    Route::get('ecoles/export', [EcoleController::class, 'export'])->name('ecoles.export');
    
    // Membres (Utilisateurs du système)
    Route::resource('users', UserController::class);
    Route::get('users/export', [UserController::class, 'export'])->name('users.export');
    Route::get('users/{user}/qrcode', [UserController::class, 'qrcode'])->name('users.qrcode');
    
    // Cours
    Route::resource('cours', CoursController::class);
    
    // Présences
    Route::resource('presences', PresenceController::class);
    Route::get('presences/scan', [PresenceController::class, 'scan'])->name('presences.scan');
    Route::post('presences/scan-qr', [PresenceController::class, 'scanQr'])->name('presences.scan-qr');
    
    // Ceintures
    Route::resource('ceintures', CeintureController::class);
    Route::get('ceintures/{ceinture}/certificat', [CeintureController::class, 'certificat'])->name('ceintures.certificat');
    
    // Séminaires
    Route::resource('seminaires', SeminaireController::class);
    
    // Paiements
    Route::resource('paiements', PaiementController::class);
    Route::post('paiements/{paiement}/valider', [PaiementController::class, 'valider'])->name('paiements.valider');
    Route::post('paiements/{paiement}/rejeter', [PaiementController::class, 'rejeter'])->name('paiements.rejeter');
    Route::get('paiements/{paiement}/recu', [PaiementController::class, 'genererRecu'])->name('paiements.recu');
});
