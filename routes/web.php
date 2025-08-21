<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/**
 * ============================================================
 * Routes Web — StudiosDB (Mono-école)
 * ------------------------------------------------------------
 * TABLE DES MATIÈRES
 *  1) Imports & configuration
 *  2) Pages publiques
 *  3) Auth (Breeze/Sanctum)
 *  4) Espace protégé (auth + verified)
 *     4.1) Dashboard
 *     4.2) Profil (Breeze)
 *     4.3) Membres
 *     4.4) Cours
 *     4.5) Présences
 *     4.6) Paiements
 *     4.7) Utilisateurs (admin only)
 *     4.8) Ceintures & Examens (lecture / gestion)
 *     4.9) Exports
 *  5) Outils / Debug (optionnel, admin only)
 *  6) Fallback (404 Inertia)
 * ============================================================
 */

/* 1) Imports & configuration */
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MembreController;
use App\Http\Controllers\CoursController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CeintureController;
use App\Http\Controllers\ExamenController;
use App\Http\Controllers\BladeController;

/* 2) Pages publiques */
Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::get('/loi-25', fn () => Inertia::render('Loi25'))
    ->name('loi25');

/* 3) Auth (Breeze/Sanctum) */
require __DIR__.'/auth.php';

/* 4) Espace protégé (auth + verified) */
Route::middleware(['auth', 'verified'])->group(function () {

    /* 4.1) Dashboard (rôle-aware côté contrôleur) */
    Route::get('/dashboard', DashboardController::class)
        ->name('dashboard');

    /* 4.2) Profil (Breeze) */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* 4.3) Membres */
    Route::resource('membres', MembreController::class);
    // Export (Excel/PDF) — ajuster l’action si tu utilises un service dédié
    Route::get('membres-export/{format?}', [MembreController::class, 'export'])
        ->whereIn('format', ['xlsx','csv','pdf'])
        ->name('membres.export');

    /* 4.4) Cours */
    Route::resource('cours', CoursController::class);
    // Planning / gestion des horaires (Pages/Cours/Planning.vue)
    Route::get('cours/planning', [CoursController::class, 'planning'])
        ->name('cours.planning');

    /* 4.5) Présences */
    Route::get('presences/tablette', [PresenceController::class, 'tablette'])
        ->name('presences.tablette');
    Route::resource('presences', PresenceController::class)->only(['index','store','update','destroy','show']);

    /* 4.6) Paiements */
    Route::resource('paiements', PaiementController::class)->only(['index','show','store','update']);
    Route::post('paiements/{paiement}/refund', [PaiementController::class, 'refund'])
        ->name('paiements.refund');

    /* 4.7) Utilisateurs (admin only) */
    Route::middleware('can:admin-panel')->group(function () {
        Route::resource('utilisateurs', UserController::class)->except(['show']);
    });

    /* 4.8) Ceintures & Examens */
    Route::resource('ceintures', CeintureController::class)->only(['index','show']);
    Route::resource('examens', ExamenController::class)->only(['index','store','update']);

    /* 4.9) Exports additionnels (si besoin) */
    // Exemple: export global des membres via service
    // Route::get('exports/membres', [\App\Http\Controllers\Export\MembreExportController::class, 'download'])
    //      ->name('exports.membres');
});

/* 5) Outils / Debug (optionnel) — admin only */
Route::middleware(['auth', 'can:admin-panel'])->group(function () {
    // Blade de debug si tu les utilises encore
    Route::get('/debug/phpinfo', [BladeController::class, 'phpinfo'])->name('debug.phpinfo');
    Route::get('/debug/dashboard-simple', [BladeController::class, 'dashboardSimple'])->name('debug.dashboard.simple');
    Route::get('/debug/dashboard-dynamic', [BladeController::class, 'dashboardDynamic'])->name('debug.dashboard.dynamic');
});

/* 6) Fallback (404 Inertia) */
Route::fallback(function () {
    // Si tu as une page Inertia Error.vue
    if (class_exists(Inertia::class)) {
        return Inertia::render('Error', [
            'status' => 404,
            'message' => 'Page non trouvée.',
        ])->toResponse(request())->setStatusCode(404);
    }
    abort(404);
});
