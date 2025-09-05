<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/**
 * ============================================================
 * StudiosDB v7 - Routes Web Consolidées (POST-CALENDRIER)
 * ============================================================
 * AJOUT: Route planning/calendrier pour module Cours
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

/* 2.1) Test serveur sans auth */
Route::get('/test-server', function() {
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
        ->whereIn('format', ['xlsx','csv','pdf'])
        ->name('membres.export');

    /* 4.4) Cours - Routes complètes avec planning */
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
    
    // CRUD de base + paramètre fixé pour injection modèle
    Route::resource('cours', CoursController::class)
        ->parameters(['cours' => 'cours']);
    
    // Vue planning/calendrier
    Route::get('cours-planning', [CoursController::class, 'planning'])->name('cours.planning');
    Route::get('planning', [CoursController::class, 'planning'])->name('planning'); // Alias
    
    // Actions fonctionnelles uniquement
    Route::post('cours/{cours}/restore', [CoursController::class, 'restore'])->name('cours.restore');
    Route::post('cours/{cours}/duplicate', [CoursController::class, 'duplicate'])->name('cours.duplicate');
    Route::get('cours/{cours}/duplicate-form', [CoursController::class, 'duplicateForm'])->name('cours.duplicate.form');
    Route::post('cours/{cours}/duplicate-jour', [CoursController::class, 'duplicateJour'])->name('cours.duplicate.jour');
    Route::post('cours/{cours}/duplicate-session', [CoursController::class, 'duplicateSession'])->name('cours.duplicate.session');
    
    // Gestion des sessions multiples (fonctionnel)
    Route::get('cours/{cours}/sessions', [CoursController::class, 'sessionsForm'])->name('cours.sessions.form');
    Route::post('cours/{cours}/sessions', [CoursController::class, 'createSessions'])->name('cours.sessions.create');

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
Route::middleware(['auth'])->get('/debug/cours-access', function() {
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
            'timestamp' => now()
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
