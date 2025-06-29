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
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\ExportController;

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
    
    // COURS - Routes spécialisées EN PREMIER
    Route::get('cours/{cour}/clone-form', [CoursController::class, 'showCloneForm'])->name('cours.clone.form');
    Route::post('cours/{cour}/clone', [CoursController::class, 'clone'])->name('cours.clone');
    
    // Gestion des cours - RESOURCE APRÈS
    Route::resource('cours', CoursController::class);
    
    // Gestion des ceintures
    Route::get('ceintures/attribution-masse', [CeintureController::class, 'createMasse'])->name('ceintures.create-masse');
    Route::post('ceintures/attribution-masse', [CeintureController::class, 'storeMasse'])->name('ceintures.store-masse');
    Route::resource('ceintures', CeintureController::class);
    
    // Gestion des séminaires
    Route::resource('seminaires', SeminaireController::class);
    Route::match(['get', 'post'], 'seminaires/{seminaire}/inscrire', [SeminaireController::class, 'inscrire'])->name('seminaires.inscrire');
    
    // Gestion des paiements
    Route::resource('paiements', PaiementController::class);
    Route::post('paiements/bulk-validate', [PaiementController::class, 'bulkValidate'])->name('paiements.bulk-validate');
    Route::patch('paiements/{paiement}/quick-validate', [PaiementController::class, 'quickValidate'])->name('paiements.quick-validate');
    
    // Gestion des présences
    Route::resource('presences', PresenceController::class);
    
    // Gestion des inscriptions aux séminaires
    Route::resource('inscriptions-seminaires', InscriptionSeminaireController::class)->only(['index', 'destroy']);
    
    // Logs et monitoring
    Route::get('logs', [LogController::class, 'index'])->name('logs.index');
    Route::post('logs/clear', [LogController::class, 'clear'])->name('logs.clear');
    
    // ROUTES EXPORTS & LOGS (LOI 25)
    Route::prefix('exports')->name('exports.')->group(function () {
        Route::get('/', [ExportController::class, 'index'])->name('index');
        Route::get('/logs', [ExportController::class, 'exportLogs'])->name('logs');
    });
});

    // Route pour validation rapide groupée (optimisation AJAX)
    Route::post('paiements/quick-bulk-validate', [PaiementController::class, 'quickBulkValidate'])->name('paiements.quick-bulk-validate');

    // Routes spéciales pour séminaires avec inscriptions
    Route::get('seminaires/{seminaire}/inscriptions', [SeminaireController::class, 'inscriptions'])->name('seminaires.inscriptions');
    Route::post('seminaires/{seminaire}/bulk-validate-inscriptions', [SeminaireController::class, 'bulkValidateInscriptions'])->name('seminaires.bulk-validate-inscriptions');
