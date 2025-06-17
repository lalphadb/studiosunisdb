<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EcoleController;
use App\Http\Controllers\Admin\MembreController;
use App\Http\Controllers\Admin\CoursController;
use App\Http\Controllers\Admin\PresenceController;
use App\Http\Controllers\Admin\CeintureController;
use App\Http\Controllers\Admin\SeminaireController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard principal
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Module Écoles
    Route::get('/admin/ecoles', [EcoleController::class, 'index'])->name('admin.ecoles.index');
    Route::get('/admin/ecoles/create', [EcoleController::class, 'create'])->name('admin.ecoles.create');
    Route::post('/admin/ecoles', [EcoleController::class, 'store'])->name('admin.ecoles.store');
    Route::get('/admin/ecoles/{ecole}', [EcoleController::class, 'show'])->name('admin.ecoles.show');
    Route::get('/admin/ecoles/{ecole}/edit', [EcoleController::class, 'edit'])->name('admin.ecoles.edit');
    Route::put('/admin/ecoles/{ecole}', [EcoleController::class, 'update'])->name('admin.ecoles.update');
    Route::delete('/admin/ecoles/{ecole}', [EcoleController::class, 'destroy'])->name('admin.ecoles.destroy');
    Route::get('/admin/ecoles/export', [EcoleController::class, 'export'])->name('admin.ecoles.export');
    
    // Module Membres
    Route::get('/admin/membres', [MembreController::class, 'index'])->name('admin.membres.index');
    Route::get('/admin/membres/create', [MembreController::class, 'create'])->name('admin.membres.create');
    Route::post('/admin/membres', [MembreController::class, 'store'])->name('admin.membres.store');
    Route::get('/admin/membres/{membre}', [MembreController::class, 'show'])->name('admin.membres.show');
    Route::get('/admin/membres/{membre}/edit', [MembreController::class, 'edit'])->name('admin.membres.edit');
    Route::put('/admin/membres/{membre}', [MembreController::class, 'update'])->name('admin.membres.update');
    Route::delete('/admin/membres/{membre}', [MembreController::class, 'destroy'])->name('admin.membres.destroy');
    Route::get('/admin/membres/export', [MembreController::class, 'export'])->name('admin.membres.export');
    
    // Module Cours - Routes manuelles pour éviter les conflits
    Route::get('/admin/cours', [CoursController::class, 'index'])->name('admin.cours.index');
    Route::get('/admin/cours/create', [CoursController::class, 'create'])->name('admin.cours.create');
    Route::post('/admin/cours', [CoursController::class, 'store'])->name('admin.cours.store');
    Route::get('/admin/cours/{cours}', [CoursController::class, 'show'])->name('admin.cours.show');
    Route::get('/admin/cours/{cours}/edit', [CoursController::class, 'edit'])->name('admin.cours.edit');
    Route::put('/admin/cours/{cours}', [CoursController::class, 'update'])->name('admin.cours.update');
    Route::delete('/admin/cours/{cours}', [CoursController::class, 'destroy'])->name('admin.cours.destroy');
    
    // Module Présences
    Route::get('/admin/presences', [PresenceController::class, 'index'])->name('admin.presences.index');
    Route::get('/admin/presences/create', [PresenceController::class, 'create'])->name('admin.presences.create');
    Route::post('/admin/presences', [PresenceController::class, 'store'])->name('admin.presences.store');
    Route::get('/admin/presences/{presence}', [PresenceController::class, 'show'])->name('admin.presences.show');
    Route::get('/admin/presences/{presence}/edit', [PresenceController::class, 'edit'])->name('admin.presences.edit');
    Route::put('/admin/presences/{presence}', [PresenceController::class, 'update'])->name('admin.presences.update');
    Route::delete('/admin/presences/{presence}', [PresenceController::class, 'destroy'])->name('admin.presences.destroy');
    Route::get('/admin/presences/export-pdf', [PresenceController::class, 'exportPdf'])->name('admin.presences.export-pdf');
    Route::get('/admin/presences/statistiques', [PresenceController::class, 'statistiques'])->name('admin.presences.statistiques');
    
    // Prise de présence par cours
    Route::get('/admin/cours/{cours}/prise-presence', [PresenceController::class, 'prisePresence'])->name('admin.presences.prise-presence');
    Route::post('/admin/cours/{cours}/prise-presence', [PresenceController::class, 'storePrisePresence'])->name('admin.presences.store-prise-presence');
    
    // Module Ceintures
    Route::get('/admin/ceintures', [CeintureController::class, 'index'])->name('admin.ceintures.index');
    Route::get('/admin/ceintures/create', [CeintureController::class, 'create'])->name('admin.ceintures.create');
    Route::post('/admin/ceintures', [CeintureController::class, 'store'])->name('admin.ceintures.store');
    Route::get('/admin/ceintures/{ceinture}', [CeintureController::class, 'show'])->name('admin.ceintures.show');
    Route::get('/admin/ceintures/{ceinture}/edit', [CeintureController::class, 'edit'])->name('admin.ceintures.edit');
    Route::put('/admin/ceintures/{ceinture}', [CeintureController::class, 'update'])->name('admin.ceintures.update');
    Route::delete('/admin/ceintures/{ceinture}', [CeintureController::class, 'destroy'])->name('admin.ceintures.destroy');
    Route::get('/admin/ceintures/dashboard', [CeintureController::class, 'dashboard'])->name('admin.ceintures.dashboard');
    Route::get('/admin/ceintures/{id}/certificat', [CeintureController::class, 'certificat'])->name('admin.ceintures.certificat');
    Route::post('/admin/ceintures/{ceinture}/approuver', [CeintureController::class, 'approuver'])->name('admin.ceintures.approuver');
    Route::post('/admin/ceintures/{ceinture}/rejeter', [CeintureController::class, 'rejeter'])->name('admin.ceintures.rejeter');
    
    // Module Séminaires
    Route::get('/admin/seminaires', [SeminaireController::class, 'index'])->name('admin.seminaires.index');
    Route::get('/admin/seminaires/create', [SeminaireController::class, 'create'])->name('admin.seminaires.create');
    Route::post('/admin/seminaires', [SeminaireController::class, 'store'])->name('admin.seminaires.store');
    Route::get('/admin/seminaires/{seminaire}', [SeminaireController::class, 'show'])->name('admin.seminaires.show');
    Route::get('/admin/seminaires/{seminaire}/edit', [SeminaireController::class, 'edit'])->name('admin.seminaires.edit');
    Route::put('/admin/seminaires/{seminaire}', [SeminaireController::class, 'update'])->name('admin.seminaires.update');
    Route::delete('/admin/seminaires/{seminaire}', [SeminaireController::class, 'destroy'])->name('admin.seminaires.destroy');
    Route::post('/admin/seminaires/{seminaire}/inscrire', [SeminaireController::class, 'inscrire'])->name('admin.seminaires.inscrire');
    Route::post('/admin/seminaires/{seminaire}/presence', [SeminaireController::class, 'marquerPresence'])->name('admin.seminaires.presence');
    Route::get('/admin/seminaires/{seminaire}/inscriptions', [SeminaireController::class, 'inscriptions'])->name('admin.seminaires.inscriptions');
});
