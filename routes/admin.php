<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    DashboardController,
    UserController,
    EcoleController,
    CeintureController,
    CoursController
};

// Routes Admin protégées
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Users (ex-membres) - PAS membres !
    Route::resource('users', UserController::class);
    Route::get('users/{user}/qrcode', [UserController::class, 'qrcode'])->name('users.qrcode');
    Route::get('users/export', [UserController::class, 'export'])->name('users.export');
    
    // Écoles (permissions vérifiées dans le contrôleur)
    Route::resource('ecoles', EcoleController::class);
    
    // Ceintures
    Route::resource('ceintures', CeintureController::class);
    Route::post('ceintures/{ceinture}/attribuer', [CeintureController::class, 'attribuer'])->name('ceintures.attribuer');
    
    // Cours - MAINTENANT ACTIF !
    Route::resource('cours', CoursController::class);
    
    // TODO: Modules futurs
    // Route::resource('seminaires', SeminaireController::class);
    // Route::resource('presences', PresenceController::class);
    // Route::resource('paiements', PaiementController::class);
});
