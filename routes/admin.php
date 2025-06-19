<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EcoleController;
use App\Http\Controllers\Admin\MembreController;
use App\Http\Controllers\Admin\CoursController;
use App\Http\Controllers\Admin\PresenceController;
use App\Http\Controllers\Admin\CeintureController;
use App\Http\Controllers\Admin\SeminaireController;
use App\Http\Controllers\Admin\PaiementController;
use App\Http\Controllers\Admin\LogController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Modules CRUD
    Route::resource('ecoles', EcoleController::class);
    Route::resource('membres', MembreController::class);
    Route::resource('cours', CoursController::class);
    Route::resource('presences', PresenceController::class);
    Route::resource('ceintures', CeintureController::class);
    Route::resource('seminaires', SeminaireController::class);
    Route::resource('paiements', PaiementController::class);
    
    // Routes spécialisées
    Route::get('membres/export', [MembreController::class, 'export']);
    Route::get('membres/{membre}/qrcode', [MembreController::class, 'qrcode']);
    Route::get('presences/scan', [PresenceController::class, 'scan']);
    Route::post('presences/scan-qr', [PresenceController::class, 'scanQr']);
    Route::get('ceintures/{id}/certificat', [CeintureController::class, 'certificat']);
    Route::post('paiements/{paiement}/valider', [PaiementController::class, 'valider']);
    Route::get('paiements/export', [PaiementController::class, 'export'])->name('paiements.export');
    
    // Nouvelle route Logs
    Route::get('logs', [LogController::class, 'index'])->name('logs.index');
});
