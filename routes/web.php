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

// Route de test temporaire
Route::get('/test', function () {
    return Inertia::render('Test');
});

// Route de test login
Route::get('/test-login', function () {
    return Inertia::render('TestLogin');
});

// Route de debug Vite
require __DIR__.'/debug.php';

// Route de nettoyage session pour debug
Route::get('/session-cleanup', function () {
    session()->flush();
    session()->regenerate();
    return redirect()->to('/session-cleanup.html');
});

// Route de test authentification
Route::get('/test-auth', function () {
    $user = \Illuminate\Support\Facades\Auth::user();
    if ($user) {
        return response()->json([
            'authenticated' => true,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ?? 'admin'
            ]
        ]);
    } else {
        return response()->json([
            'authenticated' => false,
            'redirect' => '/login'
        ]);
    }
});

// Route dashboard HTML pur
Route::get('/dashboard-html', function () {
    $user = \Illuminate\Support\Facades\Auth::user();
    if (!$user) {
        return redirect('/login');
    }
    
    // Statistiques de base
    $totalMembres = \App\Models\Membre::count();
    $totalCours = \App\Models\Cours::count();
    $totalPresences = \App\Models\Presence::count();
    $totalPaiements = \App\Models\Paiement::count();
    
    $stats = [
        'total_membres' => $totalMembres,
        'total_cours' => $totalCours,
        'total_presences' => $totalPresences,
        'total_paiements' => $totalPaiements,
        'user_name' => $user->name,
        'user_email' => $user->email,
        'user_role' => $user->role ?? 'admin'
    ];
    
    $html = view('dashboard-dynamic', $stats)->render();
    return response($html);
})->middleware('auth');

// Routes authentifiées - STRUCTURE ULTRA-PROFESSIONNELLE LARAVEL 12.x
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

// Routes de test et debug
// require __DIR__.'/test.php'; // Désactivé temporairement

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

// Route Loi 25
Route::get('/loi25', function() {
    return Inertia::render('Loi25');
})->name('loi25');

Route::get('/test', function () {
    return '<h1>✅ StudiosDB v5 Pro fonctionne!</h1><p>Timestamp: ' . now() . '</p>';
});

// Routes pour les actions rapides du dashboard
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/phpinfo', function () {
        return view('phpinfo');
    });
    
    Route::get('/logs', function () {
        $logFile = storage_path('logs/laravel.log');
        $logs = file_exists($logFile) ? file_get_contents($logFile) : 'Aucun log trouvé';
        return response($logs, 200, ['Content-Type' => 'text/plain']);
    });
    
    Route::get('/cache/clear', function () {
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        return redirect('/dashboard')->with('success', 'Cache vidé avec succès');
    });
    
    Route::get('/system-info', function () {
        return response()->json([
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'environment' => app()->environment(),
            'debug_mode' => config('app.debug'),
            'database_connection' => config('database.default'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'telescope_entries' => \Illuminate\Support\Facades\DB::table('telescope_entries')->count(),
            'users_count' => \App\Models\User::count(),
            'memory_usage' => memory_get_usage(true),
            'timestamp' => now()
        ]);
    });
    
    // Routes temporaires pour les sections du dashboard
    Route::get('/membres', function () {
        return view('sections.membres');
    });
    
    Route::get('/cours', function () {
        return view('sections.cours');
    });
    
    Route::get('/presences', function () {
        return view('sections.presences');
    });
    
    Route::get('/paiements', function () {
        return view('sections.paiements');
    });
    
    Route::get('/users', function () {
        return view('sections.users');
    });
    
    Route::get('/settings', function () {
        return view('sections.settings');
    });
    
    Route::get('/backup', function () {
        return view('sections.backup');
    });
});
