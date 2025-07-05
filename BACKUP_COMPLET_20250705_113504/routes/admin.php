<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    DashboardController,
    UserController,
    EcoleController,
    CoursController,
    SessionCoursController,
    CoursHoraireController,
    CeintureController,
    SeminaireController,
    PaiementController,
    PresenceController,
    InscriptionSeminaireController,
    LogController,
    ExportController,
    RoleController
};

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard - ROUTE PRINCIPALE
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.alternative');
    Route::get('/dashboard/stats', [DashboardController::class, 'refreshStats'])->name('dashboard.stats');
    
    // Users Management
    Route::resource('users', UserController::class);
    Route::get('users/{user}/qrcode', [UserController::class, 'qrcode'])->name('users.qrcode');
    Route::get('users-export', [UserController::class, 'export'])->name('users.export');
    
    // Écoles Management (superadmin uniquement)
    Route::resource('ecoles', EcoleController::class);
    
    // COURS Management - Routes spécialisées avant resource
    Route::get('cours/{cours}/statistiques', [CoursController::class, 'statistiques'])->name('cours.statistiques');
    Route::get('cours/{cours}/duplicate-form', [CoursController::class, 'duplicateForm'])->name('cours.duplicate-form');
    Route::post('cours/{cours}/duplicate', [CoursController::class, 'duplicate'])->name('cours.duplicate');
    Route::patch('cours/{cours}/toggle-status', [CoursController::class, 'toggleStatus'])->name('cours.toggle-status');
    Route::resource('cours', CoursController::class);
    
    // Sessions Management
    Route::resource('sessions', SessionCoursController::class);
    Route::post('sessions/{session}/toggle-actif', [SessionCoursController::class, 'toggleActif'])->name('sessions.toggle-actif');
    
    // Horaires de cours
    Route::resource('cours-horaires', CoursHoraireController::class);
    Route::post('cours-horaires/{coursHoraire}/dupliquer', [CoursHoraireController::class, 'dupliquer'])->name('cours-horaires.dupliquer');
    
    // Ceintures Management
    Route::get('ceintures/attribution', [CeintureController::class, 'attribution'])->name('ceintures.attribution');
    Route::post('ceintures/attribution', [CeintureController::class, 'storeAttribution'])->name('ceintures.attribution.store');
    Route::get('ceintures/attribution-masse', [CeintureController::class, 'createMasse'])->name('ceintures.create-masse');
    Route::post('ceintures/attribution-masse', [CeintureController::class, 'storeMasse'])->name('ceintures.store-masse');
    Route::resource('ceintures', CeintureController::class);
    
    // Séminaires Management
    Route::get('seminaires/{seminaire}/inscriptions', [SeminaireController::class, 'inscriptions'])->name('seminaires.inscriptions');
    Route::post('seminaires/{seminaire}/bulk-validate-inscriptions', [SeminaireController::class, 'bulkValidateInscriptions'])->name('seminaires.bulk-validate-inscriptions');
    Route::match(['get', 'post'], 'seminaires/{seminaire}/inscrire', [SeminaireController::class, 'inscrire'])->name('seminaires.inscrire');
    Route::resource('seminaires', SeminaireController::class);
    
    // Paiements Management
    Route::post('paiements/bulk-validate', [PaiementController::class, 'bulkValidate'])->name('paiements.bulk-validate');
    Route::patch('paiements/{paiement}/quick-validate', [PaiementController::class, 'quickValidate'])->name('paiements.quick-validate');
    Route::post('paiements/quick-bulk-validate', [PaiementController::class, 'quickBulkValidate'])->name('paiements.quick-bulk-validate');
    Route::resource('paiements', PaiementController::class);
    
    // Présences Management
    Route::resource('presences', PresenceController::class);
    Route::post('presences/marquer', [PresenceController::class, 'marquer'])->name('presences.marquer');
    
    // Inscriptions séminaires
    Route::resource('inscriptions-seminaires', InscriptionSeminaireController::class)->only(['index', 'destroy']);
    
    // Roles Management (superadmin uniquement)
    Route::resource('roles', RoleController::class)->only(['index', 'edit', 'update']);
    
    // Logs Management (superadmin uniquement)
    Route::get('logs', [LogController::class, 'index'])->name('logs.index');
    Route::post('logs/clear', [LogController::class, 'clear'])->name('logs.clear');
    
    // Exports
    Route::prefix('exports')->name('exports.')->group(function () {
        Route::get('/', [ExportController::class, 'index'])->name('index');
        Route::get('/logs', [ExportController::class, 'exportLogs'])->name('logs');
        Route::get('/users', [UserController::class, 'export'])->name('users');
    });
});
