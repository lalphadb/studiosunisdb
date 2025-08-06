<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Routes de diagnostic pour identifier le problème exact
Route::middleware(['auth'])->group(function () {
    
    // Test 1: Pure JSON (sans Vue, sans Inertia)
    Route::get('/test-json', function() {
        return response()->json([
            'status' => 'Laravel OK',
            'user' => auth()->user()->name,
            'timestamp' => now(),
            'memory' => memory_get_usage(),
            'php_version' => phpversion()
        ]);
    });
    
    // Test 2: HTML simple (sans Vue, sans Inertia)
    Route::get('/test-html', function() {
        return response('
            <h1>✅ Laravel + PHP OK</h1>
            <p>Utilisateur: ' . (auth()->user()->name ?? 'N/A') . '</p>
            <p>Timestamp: ' . now() . '</p>
            <p>PHP Version: ' . phpversion() . '</p>
            <a href="/dashboard">Retour Dashboard</a>
        ');
    });
    
    // Test 3: Inertia minimal (sans données complexes)
    Route::get('/test-inertia', function() {
        return Inertia::render('TestInertia', [
            'message' => 'Inertia.js fonctionne !',
            'timestamp' => now()->toISOString()
        ]);
    });
    
    // Test 4: Dashboard avec données minimales
    Route::get('/test-dashboard', function() {
        return Inertia::render('Dashboard/Admin', [
            'stats' => [
                'total_membres' => 1,
                'membres_actifs' => 1,
                'cours_actifs' => 8,
                'revenus_mois' => 3250
            ],
            'user' => [
                'id' => auth()->id(),
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'roles' => []
            ]
        ]);
    });
    
});
