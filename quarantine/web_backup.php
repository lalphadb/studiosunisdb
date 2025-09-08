<?php

use App\Http\Controllers\BladeController;
use App\Http\Controllers\CeintureController;
/**
 * ============================================================
 * StudiosDB v7 - Routes Web Consolidées
 * ============================================================
 * TABLE DES MATIÈRES
 *  1) Imports & configuration
 *  2) Pages publiques
 *  3) Auth (Breeze/Sanctum)
 *  4) Espace protégé (auth + verified)
 *     4.1) Dashboard
 *     4.2) Profil (Breeze)
 *     4.3) Membres
 *     4.4) Cours (complet avec toutes actions)
 *     4.5) Présences
 *     4.6) Paiements
 *     4.7) Utilisateurs (admin only)
 *     4.8) Ceintures & Examens
 *     4.9) Exports
 *  5) Administration & Debug
 *  6) Fallback (404 Inertia)
 * ============================================================
 */

/* 1) Imports & configuration */
use App\Http\Controllers\CoursController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamenController;
use App\Http\Controllers\MembreController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/* 2) Pages publiques */
Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::get('/loi-25', fn () => Inertia::render('Loi25'))
    ->name('loi25');

/* 2.1) Test serveur sans auth */
Route::get('/test-server', function () {
    return response()->json([
        'status' => 'OK',
        'message' => 'ServeurStudiosDB fonctionne',
        'timestamp' => now()->toISOString(),
        'laravel_version' => app()->version(),
        'php_version' => PHP_VERSION,
    ]);
})->name('test-server');

/* 3) Auth (Breeze/Sanctum) */
require __DIR__.'/auth.php';

/* 4) Espace protégé (auth + verified) */
Route::middleware(['auth', 'verified'])->group(function () {

    /* 4.1) Dashboard */
    Route::get('/dashboard', DashboardController::class)
        ->name('dashboard');

    /* 4.2) Profil (Breeze) */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* 4.3) Membres */
    Route::resource('membres', MembreController::class);
    // Changement de ceinture
    Route::post('membres/{membre}/ceinture', [MembreController::class, 'changerCeinture'])
        ->name('membres.changer-ceinture');
    // Export (Excel/PDF)
    Route::get('membres-export/{format?}', [MembreController::class, 'export'])
        ->whereIn('format', ['xlsx', 'csv', 'pdf'])
        ->name('membres.export');

    /* 4.4) Cours - Routes complètes consolidées */
    // Route model binding sécurisé pour cours
    Route::bind('cours', function ($value, $route) {
        $user = auth()->user();

        // Pour superadmin : pas de restriction
        if ($user?->hasRole('superadmin')) {
            return \App\Models\Cours::withTrashed()->findOrFail($value);
        }

        // Pour autres utilisateurs : avec GlobalScope normal mais withTrashed pour restore
        return \App\Models\Cours::withTrashed()->findOrFail($value);
    });

    // CRUD de base
    // IMPORTANT: Laravel singularise "cours" en "cour" => paramètre généré {cour}
    // Nos méthodes de contrôleur utilisent $cours (avec s). On force donc le nom du paramètre
    // pour que l'injection de modèle fonctionne (sinon $cours->id reste null et forceDelete ne fait rien).
    Route::resource('cours', CoursController::class)
        ->parameters(['cours' => 'cours']);

    // Actions spéciales cours
    Route::post('cours/{cours}/restore', [CoursController::class, 'restore'])->name('cours.restore');
    Route::post('cours/{cours}/duplicate', [CoursController::class, 'duplicate'])->name('cours.duplicate');
    Route::post('cours/{cours}/duplicate-jour', [CoursController::class, 'duplicateJour'])->name('cours.duplicate.jour');
    Route::post('cours/{cours}/duplicate-session', [CoursController::class, 'duplicateSession'])->name('cours.duplicate.session');

    // Gestion des sessions
    Route::get('cours/{cours}/sessions', [CoursController::class, 'sessionsForm'])->name('cours.sessions.form');
    Route::post('cours/{cours}/sessions', [CoursController::class, 'createSessions'])->name('cours.sessions.create');
    Route::post('cours/{cours}/sessions/annuler', [CoursController::class, 'annulerSession'])->name('cours.sessions.annuler');
    Route::post('cours/{cours}/sessions/reporter', [CoursController::class, 'reporterSession'])->name('cours.sessions.reporter');

    // Gestion des inscriptions
    Route::post('cours/{cours}/inscrire', [CoursController::class, 'inscrireMembre'])->name('cours.inscrire');
    Route::post('cours/{cours}/desinscrire', [CoursController::class, 'desinscrireMembre'])->name('cours.desinscrire');
    Route::get('cours/{cours}/membres', [CoursController::class, 'listeMembres'])->name('cours.membres');
    Route::post('cours/{cours}/choisir-horaire', [CoursController::class, 'choisirHoraire'])->name('cours.choisir_horaire');
    Route::post('cours/{cours}/membre/{membre}/valider', [CoursController::class, 'validerInscription'])->name('cours.valider_inscription');
    Route::post('cours/{cours}/membre/{membre}/refuser', [CoursController::class, 'refuserInscription'])->name('cours.refuser_inscription');
    Route::post('cours/{cours}/membre/{membre}/alternative', [CoursController::class, 'proposerAlternative'])->name('cours.proposer_alternative');

    // Planning & Export
    Route::get('planning', [CoursController::class, 'planning'])->name('cours.planning');
    Route::get('cours/export', [CoursController::class, 'export'])->name('cours.export');

    // Statistiques & données
    Route::get('cours/{cours}/statistiques', [CoursController::class, 'statistiques'])->name('cours.statistiques');
    Route::get('cours/{cours}/presences', [CoursController::class, 'presences'])->name('cours.presences');

    // API endpoints pour AJAX
    Route::prefix('cours/api')->name('cours.api.')->group(function () {
        Route::get('disponibilites', [CoursController::class, 'checkDisponibilites'])->name('disponibilites');
        Route::get('conflits', [CoursController::class, 'checkConflits'])->name('conflits');
        Route::get('search', [CoursController::class, 'search'])->name('search');
        Route::get('calendrier', [CoursController::class, 'calendrier'])->name('calendrier');
    });

    /* 4.5) Présences */
    Route::get('presences/tablette', [PresenceController::class, 'tablette'])
        ->name('presences.tablette');
    Route::resource('presences', PresenceController::class)->only(['index', 'store', 'update', 'destroy', 'show']);

    /* 4.6) Paiements */
    Route::resource('paiements', PaiementController::class)->only(['index', 'show', 'store', 'update']);
    Route::post('paiements/{paiement}/refund', [PaiementController::class, 'refund'])
        ->name('paiements.refund');

    /* 4.7) Utilisateurs (admin only) */
    Route::middleware('can:admin-panel')->group(function () {
        Route::resource('utilisateurs', UserController::class)->except(['show']);
    });

    /* 4.8) Ceintures & Examens */
    Route::resource('ceintures', CeintureController::class)->only(['index', 'show']);
    Route::resource('examens', ExamenController::class)->only(['index', 'store', 'update']);

    /* 4.9) Exports additionnels (si besoin) */
    // Routes d'exports globaux peuvent être ajoutées ici
});

/* 5) Administration & Debug (admin only) */
Route::middleware(['auth', 'can:admin-panel'])->group(function () {
    // Blade de debug si nécessaires
    Route::get('/debug/phpinfo', [BladeController::class, 'phpinfo'])->name('debug.phpinfo');
    Route::get('/debug/dashboard-simple', [BladeController::class, 'dashboardSimple'])->name('debug.dashboard.simple');
    Route::get('/debug/dashboard-dynamic', [BladeController::class, 'dashboardDynamic'])->name('debug.dashboard.dynamic');
});

/* 5.1) Routes diagnostic temporaires */
Route::middleware(['auth'])->get('/debug/cours-access', function () {
    $user = auth()->user();
    $diagnostic = [
        'user_authenticated' => auth()->check(),
        'user_id' => $user?->id,
        'user_email' => $user?->email,
        'user_ecole_id' => $user?->ecole_id,
        'user_roles' => $user?->getRoleNames()->toArray() ?? [],
        'can_view_any_cours' => $user ? $user->can('viewAny', \App\Models\Cours::class) : false,
        'can_create_cours' => $user ? $user->can('create', \App\Models\Cours::class) : false,
        'cours_count' => \App\Models\Cours::count(),
        'session_id' => request()->session()->getId(),
        'csrf_token' => csrf_token(),
    ];

    return response()->json($diagnostic, 200, [], JSON_PRETTY_PRINT);
})->name('debug.cours-access');

/* 6) Utilitaires système (admin only) */
Route::middleware(['auth', 'can:admin-panel'])->group(function () {
    Route::get('/cache/clear', function () {
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');

        return redirect('/dashboard')->with('success', 'Cache vidé avec succès');
    })->name('cache.clear');

    Route::get('/system-info', function () {
        return response()->json([
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'environment' => app()->environment(),
            'debug_mode' => config('app.debug'),
            'database_connection' => config('database.default'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'timestamp' => now(),
        ]);
    })->name('system.info');
});

/* 7) Fallback (404 Inertia) */
Route::fallback(function () {
    if (class_exists(Inertia::class)) {
        return Inertia::render('Error', [
            'status' => 404,
            'message' => 'Page non trouvée.',
        ])->toResponse(request())->setStatusCode(404);
    }
    abort(404);
});
