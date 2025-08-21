<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MembreController;
use App\Http\Controllers\CoursController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\CeintureController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

    Route::middleware(['auth', 'verified'])->group(function () {
        ->name('dashboard'); // rend Dashboard/Admin pour les admins
});
   
    // Membres - Routes en français (FR canonique)
    Route::resource('membres', MembreController::class);
    Route::get('/membres/export', [MembreController::class, 'export'])->name('membres.export');
    Route::post('/membres/bulk', [MembreController::class, 'bulk'])->name('membres.bulk');
    Route::post('/membres/{membre}/changer-ceinture', [MembreController::class, 'changerCeinture'])->name('membres.changer-ceinture');

    // Cours - Routes en français
    Route::resource('cours', CoursController::class);
    Route::get('/cours/planning', [CoursController::class, 'planning'])->name('cours.planning');
    Route::post('/cours/{cours}/inscrire', [CoursController::class, 'inscrire'])->name('cours.inscrire');
    
    // Présences - Routes en français
    Route::resource('presences', PresenceController::class);
    Route::get('presences/tablette', [PresenceController::class, 'tablette'])->name('presences.tablette');
    Route::post('presences/marquer', [PresenceController::class, 'marquer'])->name('presences.marquer');
    Route::get('presences/export', [PresenceController::class, 'export'])->name('presences.export');
    Route::get('presences/rapports', [PresenceController::class, 'rapports'])->name('presences.rapports');
    
    // Paiements - Routes en français
    Route::resource('paiements', PaiementController::class);
    Route::post('paiements/{paiement}/confirmer', [PaiementController::class, 'confirmer'])->name('paiements.confirmer');
    Route::post('paiements/{paiement}/rembourser', [PaiementController::class, 'rembourser'])->name('paiements.rembourser');
    Route::get('paiements/factures/generer', [PaiementController::class, 'genererFactures'])->name('paiements.generer-factures');
    
    // Ceintures - Routes en français
    Route::resource('ceintures', CeintureController::class);
    
    // Utilisateurs - Routes en français (admin seulement)
    Route::middleware(['can:admin-panel'])->group(function () {
        Route::resource('utilisateurs', UserController::class);
        Route::post('utilisateurs/{user}/reset-password', [UserController::class, 'resetPassword'])->name('utilisateurs.reset-password');
        Route::post('utilisateurs/{user}/manage-roles', [UserController::class, 'manageRoles'])->name('utilisateurs.manage-roles');
    });
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

use App\Http\Controllers\DashboardController;

Route::middleware(['auth','verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
});
