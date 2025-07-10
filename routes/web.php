<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\StudiosDBAuthController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes - StudiosDB Enterprise v4.1.10.2
|--------------------------------------------------------------------------
*/

// Redirection racine vers login
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
});

// Routes d'authentification
require __DIR__.'/auth.php';

// Routes authentifiées de base
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        // Rediriger vers l'admin si c'est un admin
        if (auth()->user()->hasAnyRole(['superadmin', 'admin_ecole'])) {
            return redirect()->route('admin.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');

    // API de test pour l'authentification
    Route::get('/api/test-auth', function () {
        return response()->json([
            'status' => 'success',
            'user' => auth()->user()->only(['id', 'name', 'email']),
            'roles' => auth()->user()->roles->pluck('name'),
            'ecole' => auth()->user()->ecole ? auth()->user()->ecole->only(['id', 'nom']) : null,
            'timestamp' => now()
        ]);
    })->name('api.test.auth');
});

// Routes ADMIN avec middleware admin
Route::middleware(['auth', 'verified', 'admin', 'enforce2fa'])->prefix('admin')->name('admin.')->group(function () {
    require __DIR__.'/admin.php';
});

// Déconnexion GET (pour les liens directs)
Route::get('/logout', function() {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login')->with('success', 'Déconnecté avec succès');
})->name('logout.get');

// Route de test pour l'admin (développement uniquement)
Route::get('/test-admin', function() {
    if (!app()->environment('local')) {
        abort(404);
    }
    
    return response()->json([
        'user' => Auth::user() ? Auth::user()->only(['id', 'name', 'email']) : null,
        'is_authenticated' => Auth::check(),
        'roles' => Auth::check() ? Auth::user()->roles->pluck('name') : [],
        'ecole' => Auth::check() && Auth::user()->ecole ? Auth::user()->ecole->only(['id', 'nom']) : null,
        'admin_middleware_exists' => file_exists(app_path('Http/Middleware/AdminMiddleware.php')),
        'route_exists' => Route::has('admin.dashboard'),
        'middleware_registered' => array_key_exists('admin', app('router')->getMiddleware()),
        'timestamp' => now()
    ]);
})->name('test.admin');
