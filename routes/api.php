<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - StudiosDB Enterprise v4.1.10.2
|--------------------------------------------------------------------------
| 
| API pour l'application mobile et intégrations tierces
|
*/

// Route de santé pour monitoring
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'version' => '4.1.10.2',
        'timestamp' => now(),
        'environment' => app()->environment(),
    ]);
});

// Routes API authentifiées
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Profil utilisateur
    Route::get('/user', function (Request $request) {
        return response()->json([
            'user' => $request->user()->load(['ecole', 'roles']),
            'permissions' => $request->user()->getAllPermissions()->pluck('name'),
        ]);
    });
    
    // API pour l'application mobile (futures fonctionnalités)
    Route::prefix('mobile')->group(function () {
        Route::get('/dashboard', function (Request $request) {
            $user = $request->user();
            
            return response()->json([
                'user' => $user->only(['id', 'name', 'email']),
                'ecole' => $user->ecole ? $user->ecole->only(['id', 'nom']) : null,
                'stats' => [
                    'presences_month' => $user->presences()->whereMonth('created_at', now()->month)->count(),
                    'last_presence' => $user->presences()->latest()->first(),
                ],
                'notifications' => [], // Future implementation
            ]);
        });
    });
});

// API publique (limitée)
Route::middleware(['throttle:60,1'])->group(function () {
    
    // Informations publiques des écoles (pour annuaire)
    Route::get('/ecoles/public', function () {
        return response()->json([
            'ecoles' => \App\Models\Ecole::where('active', true)
                ->select(['id', 'nom', 'ville', 'province', 'telephone', 'email'])
                ->get()
        ]);
    });
});
