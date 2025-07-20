<?php

use Illuminate\Support\Facades\Route;
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

// Route debug HTML simple (SANS erreur de syntaxe)
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
            pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; }
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
        
        // Test tables
        try {
            $tables = DB::select('SHOW TABLES');
            $html .= '<p class="info">📋 Tables: ' . count($tables) . ' trouvées</p>';
        } catch (Exception $e) {
            $html .= '<p class="warning">⚠️ Tables non listables</p>';
        }
        
    } catch (Exception $e) {
        $html .= '<p class="error">❌ Erreur DB: ' . htmlspecialchars($e->getMessage()) . '</p>';
        $html .= '<p class="warning">💡 Solution: Vérifiez la configuration dans .env</p>';
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
                <a href="/phptest.php" class="btn">🐘 Test PHP Pur</a>
            </div>';

    $html .= '</div>
    </body>
    </html>';

    return response($html);
});

// Route racine - redirection vers debug
Route::get('/', function () {
    return redirect('/debug');
});

// Routes authentifiées (si auth fonctionne)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    
    // Routes membres (à développer)
    Route::get('/membres', function () {
        return response()->json(['message' => 'Module membres à développer']);
    })->name('membres.index');
});

// Auth routes si elles existent
if (file_exists(__DIR__ . '/auth.php')) {
    require __DIR__ . '/auth.php';
}
