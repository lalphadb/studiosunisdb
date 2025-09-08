<?php

use App\Http\Controllers\CoursController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/dashboard/metrics', [DashboardController::class, 'metricsRealtime'])->name('dashboard.metrics');

    // Members - Resource complet avec routes supplémentaires
    Route::resource('members', MemberController::class);
    Route::post('/members/{member}/change-belt', [MemberController::class, 'changeBelt'])->name('members.change-belt');
    Route::post('/members/bulk-update', [MemberController::class, 'bulkUpdate'])->name('members.bulk-update');
    Route::get('/members/export', [MemberController::class, 'export'])->name('members.export');

    // Garder les anciennes routes pour compatibilité
    Route::resource('membres', MemberController::class);

    // Cours
    Route::resource('cours', CoursController::class);
    Route::post('/cours/{cours}/duplicate', [CoursController::class, 'duplicate'])->name('cours.duplicate');
    Route::get('/planning', [CoursController::class, 'planning'])->name('cours.planning');

    // Présences
    Route::resource('presences', PresenceController::class);
    Route::get('/presences/tablette', [PresenceController::class, 'tablette'])->name('presences.tablette');
    Route::post('/presences/marquer', [PresenceController::class, 'marquer'])->name('presences.marquer');
    Route::get('/presences/rapports', [PresenceController::class, 'rapports'])->name('presences.rapports');

    // Paiements
    Route::resource('paiements', PaiementController::class);
    Route::patch('/paiements/{paiement}/confirmer', [PaiementController::class, 'confirmer'])->name('paiements.confirmer');
    Route::get('/paiements-tableau-bord', [PaiementController::class, 'tableauBord'])->name('paiements.tableau-bord');
    Route::post('/paiements/generer-factures', [PaiementController::class, 'genererFactures'])->name('paiements.generer-factures');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
