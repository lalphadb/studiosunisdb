
<?php
/**
 * ============================================================================
 * StudiosDB v5 Pro - ROUTES PRINCIPALES (web.php)
 * ============================================================================
 * TABLE DES MATIÈRES :
 * 1. Redirections & Pages publiques
 * 2. Authentification (inclus auth.php)
 * 3. Dashboard & Profil
 * 4. Modules principaux (Membres, Cours, Présences, Paiements, etc.)
 * 5. Administration & Statistiques
 * 6. Utilitaires & Système
 * 7. (Optionnel) Inclusion routes avancées (DEPRECATED cours.php)
 * 8. (Optionnel) Debug & Test (en dev uniquement)
 * ============================================================================
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MembreController;
use App\Http\Controllers\CoursController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\PaiementController;
use Inertia\Inertia;

// 1. Redirections & Pages publiques
Route::get('/', fn() => redirect('/dashboard'));

// 2. Authentification
require __DIR__.'/auth.php';

// 3. Dashboard & Profil
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 4. Modules principaux
Route::middleware(['auth', 'verified'])->group(function () {
    // Membres
    Route::resource('membres', MembreController::class);
    Route::post('membres/{membre}/changer-ceinture', [MembreController::class, 'changerCeinture'])->name('membres.changer-ceinture');
    Route::get('export/membres', [MembreController::class, 'export'])->name('membres.export');

    // Cours
    Route::resource('cours', CoursController::class);
    Route::patch('cours/{cours}/toggle-statut', [CoursController::class, 'toggleStatut'])->name('cours.toggle-statut');
    Route::get('cours/{cours}/horaires', [CoursController::class, 'horaires'])->name('cours.horaires');
    Route::post('cours/{cours}/horaires', [CoursController::class, 'storeHoraire'])->name('cours.horaires.store');
    Route::put('cours/{cours}/horaires/{horaire}', [CoursController::class, 'updateHoraire'])->name('cours.horaires.update');
    Route::delete('cours/{cours}/horaires/{horaire}', [CoursController::class, 'destroyHoraire'])->name('cours.horaires.destroy');
    Route::post('cours/{cours}/generer-sessions', [CoursController::class, 'genererSessionsSaison'])->name('cours.generer-sessions');

    // Présences
    Route::resource('presences', PresenceController::class);
    Route::get('presences/tablette', [PresenceController::class, 'tablette'])->name('presences.tablette');
    Route::post('presences/sauvegarder', [PresenceController::class, 'sauvegarder'])->name('presences.sauvegarder');
    Route::get('presences/rapport', [PresenceController::class, 'rapport'])->name('presences.rapport');

    // Paiements
    Route::resource('paiements', PaiementController::class);
    Route::patch('paiements/{paiement}/marquer-paye', [PaiementController::class, 'marquerPaye'])->name('paiements.marquer-paye');
    Route::post('paiements/{paiement}/rappel', [PaiementController::class, 'envoyerRappel'])->name('paiements.rappel');
    Route::post('paiements/rappels-globaux', [PaiementController::class, 'rappelsGlobaux'])->name('paiements.rappels-globaux');
    Route::get('paiements/export', [PaiementController::class, 'export'])->name('paiements.export');
});

// 5. Administration & Statistiques
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin', fn() => Inertia::render('Admin/Index'))->name('admin.index');
    Route::get('/statistiques', fn() => Inertia::render('Statistiques/Index'))->name('statistiques.index');
    Route::get('/loi25', fn() => Inertia::render('Loi25'))->name('loi25');
});

// 6. Utilitaires & Système
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/phpinfo', fn() => view('phpinfo'));
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
            'timestamp' => now()
        ]);
    });
});

// 7. (Optionnel) Inclusion routes avancées (cours, API, etc.)
// Deprecated: ancien fichier routes/cours.php non chargé pour éviter doublons

// 8. (Optionnel) Debug & Test (en dev uniquement)
if (app()->environment('local')) {
    if (file_exists(__DIR__.'/debug.php')) require __DIR__.'/debug.php';
    if (file_exists(__DIR__.'/test.php')) require __DIR__.'/test.php';
}
