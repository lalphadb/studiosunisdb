<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    DashboardController,
    UserController,
    EcoleController,
    CoursController,
    PresenceController,
    PaiementController,
    CeintureController
};

// Routes Admin protégées
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Users (ex-membres)
    Route::resource('users', UserController::class);
    Route::get('users/{user}/qrcode', [UserController::class, 'qrcode'])->name('users.qrcode');
    Route::get('users/export', [UserController::class, 'export'])->name('users.export');
    
    // Écoles (SuperAdmin seulement)
    Route::resource('ecoles', EcoleController::class)
        ->middleware('role:superadmin');
    
    // Cours
    Route::resource('cours', CoursController::class);
    
    // Présences
    Route::resource('presences', PresenceController::class);
    Route::post('presences/scan-qr', [PresenceController::class, 'scanQR'])->name('presences.scan-qr');
    
    // Paiements
    Route::resource('paiements', PaiementController::class);
    
    // Ceintures
    Route::resource('ceintures', CeintureController::class);
    Route::post('ceintures/attribuer', [CeintureController::class, 'attribuer'])->name('ceintures.attribuer');
    
});
