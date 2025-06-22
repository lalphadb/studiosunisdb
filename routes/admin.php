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
    
    // Route de test temporaire
    Route::get('/test', function() {
        $user = auth()->user();
        return response()->json([
            'connected' => auth()->check(),
            'user' => $user ? $user->name : 'Non connecté',
            'roles' => $user ? $user->getRoleNames() : [],
            'route_works' => true
        ]);
    })->name('admin.test');
    
    // Dashboard principal accessible à tous les admins (DEUX ROUTES)
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Routes temporaires sans restrictions pour débugger
    Route::resource('users', UserController::class);
    Route::resource('ecoles', EcoleController::class);
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
});
