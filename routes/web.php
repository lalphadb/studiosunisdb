<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MembreController;
use App\Http\Controllers\CoursController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\PaiementController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

// Route racine
Route::get('/', function () {
    return redirect('/dashboard');
});

// Routes authentifiées - STRUCTURE ULTRA-PROFESSIONNELLE LARAVEL 11.x
Route::middleware(['auth', 'verified'])->group(function () {

    // ============================================================================
    // DASHBOARD - ROUTE UNIQUE (PROBLÈME RÉSOLU!)
    // ============================================================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/dashboard/metriques', [DashboardController::class, 'metriquesTempsReel'])->name('dashboard.metriques');

    // ============================================================================
    // PROFIL UTILISATEUR
    // ============================================================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ============================================================================
    // GESTION MEMBRES (CRUD COMPLET)
    // ============================================================================
    Route::resource('membres', MembreController::class);
    Route::post('membres/{membre}/changer-ceinture', [MembreController::class, 'changerCeinture'])->name('membres.changer-ceinture');
    Route::get('export/membres', [MembreController::class, 'export'])->name('membres.export');

    // ============================================================================
    // GESTION COURS & PLANNING
    // ============================================================================
    Route::resource('cours', CoursController::class);
    Route::patch('cours/{cours}/toggle-statut', [CoursController::class, 'toggleStatut'])->name('cours.toggle-statut');
    Route::get('cours/{cours}/horaires', [CoursController::class, 'horaires'])->name('cours.horaires');
    Route::post('cours/{cours}/horaires', [CoursController::class, 'storeHoraire'])->name('cours.horaires.store');
    Route::put('cours/{cours}/horaires/{horaire}', [CoursController::class, 'updateHoraire'])->name('cours.horaires.update');
    Route::delete('cours/{cours}/horaires/{horaire}', [CoursController::class, 'destroyHoraire'])->name('cours.horaires.destroy');
    Route::post('cours/{cours}/generer-sessions', [CoursController::class, 'genererSessionsSaison'])->name('cours.generer-sessions');

    // ============================================================================
    // GESTION PRÉSENCES (INTERFACE TABLETTE)
    // ============================================================================
    Route::get('presences', [PresenceController::class, 'index'])->name('presences.index');
    Route::get('presences/tablette', [PresenceController::class, 'tablette'])->name('presences.tablette');
    Route::post('presences/sauvegarder', [PresenceController::class, 'sauvegarder'])->name('presences.sauvegarder');
    Route::get('presences/rapport', [PresenceController::class, 'rapport'])->name('presences.rapport');

    // ============================================================================
    // GESTION PAIEMENTS & FACTURATION
    // ============================================================================
    Route::resource('paiements', PaiementController::class);
    Route::patch('paiements/{paiement}/marquer-paye', [PaiementController::class, 'marquerPaye'])->name('paiements.marquer-paye');
    Route::post('paiements/{paiement}/rappel', [PaiementController::class, 'envoyerRappel'])->name('paiements.rappel');
    Route::post('paiements/rappels-globaux', [PaiementController::class, 'rappelsGlobaux'])->name('paiements.rappels-globaux');
    Route::get('paiements/export', [PaiementController::class, 'export'])->name('paiements.export');

    // ============================================================================
    // ADMINISTRATION & RAPPORTS
    // ============================================================================
    Route::get('/admin', function () {
        return Inertia::render('Admin/Index');
    })->name('admin.index');

    Route::get('/statistiques', function () {
        return Inertia::render('Statistiques/Index');
    })->name('statistiques.index');
});

// Routes d'authentification
require __DIR__.'/auth.php';

// Routes de test diagnostic (temporaires)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard-json', function() {
        return response()->json(['status' => 'OK', 'user' => auth()->user(), 'timestamp' => now()]);
    });
    
    Route::get('/dashboard-html', function() {
        return response('<h1>✅ Laravel + Blade OK</h1><p>Utilisateur: ' . (auth()->user()->name ?? 'N/A') . '</p>');
    });
    
    Route::get('/dashboard-simple', function() {
        return Inertia::render('Dashboard/Simple', [
            'message' => 'Laravel + Inertia + Vue OK!',
            'user' => auth()->user(),
            'timestamp' => now()->toISOString()
        ]);
    });
});
