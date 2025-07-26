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
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

// Route de test API simple
Route::get('/test', function () {
    return response()->json([
        'status' => 'SUCCESS',
        'message' => 'StudiosDB V5 - Laravel API fonctionne parfaitement!',
        'timestamp' => now(),
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version(),
        'environment' => app()->environment()
    ]);
});

// Route debug HTML
Route::get('/debug', function () {
    $html = '<!DOCTYPE html>
    <html>
    <head>
        <title>StudiosDB V5 Debug</title>
        <meta charset="utf-8">
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; background: #f8f9fa; margin: 0; }
            .container { max-width: 1200px; margin: 0 auto; }
            .box { background: white; padding: 20px; margin: 15px 0; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
            .success { color: #28a745; font-weight: bold; }
            .error { color: #dc3545; font-weight: bold; }
            .warning { color: #ffc107; font-weight: bold; }
            .info { color: #17a2b8; font-weight: bold; }
            .btn { display: inline-block; padding: 12px 24px; margin: 5px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
            .btn:hover { background: #0056b3; }
            .btn-success { background: #28a745; }
            .btn-warning { background: #ffc107; color: #000; }
            h1 { color: #343a40; border-bottom: 3px solid #007bff; padding-bottom: 10px; }
            h2 { color: #495057; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="box">
                <h1>🎉 StudiosDB V5 - Diagnostic Système</h1>
                <p class="success">✅ Laravel Version: ' . app()->version() . '</p>
                <p class="success">✅ PHP Version: ' . PHP_VERSION . '</p>
                <p class="success">✅ Environment: ' . app()->environment() . '</p>
                <p class="success">✅ Debug Mode: ' . (config('app.debug') ? 'Activé' : 'Désactivé') . '</p>
                <p class="info">🕒 Timestamp: ' . now()->format('Y-m-d H:i:s') . '</p>
            </div>';

    // Test base de données
    $html .= '<div class="box">
                <h2>🗃️ Statut Base de Données</h2>';

    try {
        $dbConnection = DB::connection()->getPdo();
        $dbName = DB::connection()->getDatabaseName();
        $html .= '<p class="success">✅ Connexion DB réussie</p>';
        $html .= '<p class="info">📊 Base: ' . $dbName . '</p>';

        try {
            $tables = DB::select('SHOW TABLES');
            $html .= '<p class="info">📋 Tables: ' . count($tables) . ' trouvées</p>';
        } catch (Exception $e) {
            $html .= '<p class="warning">⚠️ Tables non listables</p>';
        }

    } catch (Exception $e) {
        $html .= '<p class="error">❌ Erreur DB: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }

    $html .= '</div>';

    // Extensions PHP
    $html .= '<div class="box">
                <h2>🔧 Extensions PHP</h2>';

    $extensions = ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'curl', 'json', 'xml', 'fileinfo'];
    foreach ($extensions as $ext) {
        $status = extension_loaded($ext) ? '✅' : '❌';
        $class = extension_loaded($ext) ? 'success' : 'error';
        $html .= '<p class="' . $class . '">' . $status . ' ' . $ext . '</p>';
    }

    $html .= '</div>';

    // Navigation
    $html .= '<div class="box">
                <h2>🎯 Navigation</h2>
                <a href="/test" class="btn">🔍 Test API JSON</a>
                <a href="/dashboard" class="btn btn-success">🏠 Dashboard</a>
                <a href="/membres" class="btn btn-warning">👥 Membres</a>
                <a href="/login" class="btn">🔐 Login</a>
            </div>';

    $html .= '</div>
    </body>
    </html>';

    return response($html);
});

// Route racine - redirection vers debug
Route::get('/', function () {
    return redirect('/dashboard');
});

// Routes authentifiées
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Gestion des membres
    Route::resource('membres', MembreController::class);
    Route::post('membres/{membre}/changer-ceinture', [MembreController::class, 'changerCeinture'])->name('membres.changer-ceinture');
    Route::get('export/membres', [MembreController::class, 'export'])->name('membres.export');

    // Gestion des cours
    Route::resource('cours', CoursController::class);
    Route::patch('cours/{cours}/toggle-statut', [CoursController::class, 'toggleStatut'])->name('cours.toggle-statut');
    
    // Gestion des présences
    Route::get('presences', [PresenceController::class, 'index'])->name('presences.index');
    Route::get('presences/tablette', [PresenceController::class, 'tablette'])->name('presences.tablette');
    Route::post('presences/sauvegarder', [PresenceController::class, 'sauvegarder'])->name('presences.sauvegarder');
    Route::get('presences/rapport', [PresenceController::class, 'rapport'])->name('presences.rapport');
    
    // Gestion des paiements
    Route::resource('paiements', PaiementController::class);
    Route::patch('paiements/{paiement}/marquer-paye', [PaiementController::class, 'marquerPaye'])->name('paiements.marquer-paye');
    Route::post('paiements/{paiement}/rappel', [PaiementController::class, 'envoyerRappel'])->name('paiements.rappel');
    Route::post('paiements/rappels-globaux', [PaiementController::class, 'rappelsGlobaux'])->name('paiements.rappels-globaux');
    Route::get('paiements/export', [PaiementController::class, 'export'])->name('paiements.export');

    // Routes admin
    Route::get('/admin', function () {
        return Inertia::render('Admin/Index');
    })->name('admin.index');

    Route::get('/statistiques', function () {
        return Inertia::render('Statistiques/Index');
    })->name('statistiques.index');
});

// Auth routes
require __DIR__.'/auth.php';

// API Dashboard
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/api/dashboard/metriques', [DashboardController::class, 'metriquesTempsReel'])->name('dashboard.metriques');
});

/*
|--------------------------------------------------------------------------
| Routes Blade Fonctionnelles (Bypass Inertia)
|--------------------------------------------------------------------------
*/

Route::prefix('blade')->group(function () {
    Route::get('/debug', [App\Http\Controllers\BladeController::class, 'debug']);
    Route::get('/login', [App\Http\Controllers\BladeController::class, 'login'])->name('blade.login');
    Route::post('/login', [App\Http\Controllers\BladeController::class, 'loginPost']);
    Route::get('/dashboard', [App\Http\Controllers\BladeController::class, 'dashboard'])->name('blade.dashboard');
    Route::get('/membres', [App\Http\Controllers\BladeController::class, 'membres'])->name('blade.membres');
    Route::post('/logout', [App\Http\Controllers\BladeController::class, 'logout'])->name('blade.logout');
});

// Route redirect temporaire
Route::get('/working', function() {
    return redirect('/blade/debug');
});
