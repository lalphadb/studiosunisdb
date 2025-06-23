<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    DashboardController,
    UserController,
    EcoleController,
    CoursController,
    CeintureController,
    SeminaireController,
    InscriptionSeminaireController,
    PaiementController,
    PresenceController,
    LogController
};

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard principal
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Routes SANS middleware can: pour déboguer
    Route::resource('ecoles', EcoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('cours', CoursController::class);
    Route::resource('ceintures', CeintureController::class);
    Route::resource('seminaires', SeminaireController::class);
    Route::resource('paiements', PaiementController::class);
    Route::resource('presences', PresenceController::class);
    
    // Routes spéciales
    Route::get('users/export', [UserController::class, 'export'])->name('users.export');
    Route::get('users/{user}/qrcode', [UserController::class, 'qrcode'])->name('users.qrcode');
    Route::post('ceintures/{ceinture}/attribuer', [CeintureController::class, 'attribuer'])->name('ceintures.attribuer');
    
    // Inscriptions seminaires
    Route::get('seminaires/{seminaire}/inscrire', [InscriptionSeminaireController::class, 'create'])->name('seminaires.inscrire');
    Route::post('seminaires/{seminaire}/inscrire', [InscriptionSeminaireController::class, 'store'])->name('seminaires.inscription.store');
    Route::get('inscriptions-seminaires', [InscriptionSeminaireController::class, 'index'])->name('inscriptions-seminaires.index');
    Route::delete('inscriptions-seminaires/{inscription}', [InscriptionSeminaireController::class, 'destroy'])->name('inscriptions-seminaires.destroy');
    
    // Logs
    Route::resource('logs', LogController::class)->only(['index', 'show']);
    
    // Route de test
    Route::get('/test', function() {
        return response()->json([
            'status' => 'OK',
            'user' => auth()->user()->name ?? 'Non connecté',
            'timestamp' => now()
        ]);
    })->name('test');
});
