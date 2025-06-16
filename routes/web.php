<?php

use App\Http\Controllers\LegalController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - StudiosUnisDB v3.7.0.0
|--------------------------------------------------------------------------
| Système de Gestion des Écoles de Karaté
| 22 Studios Unis du Québec
*/

// Page d'accueil StudiosUnisDB
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Redirection dashboard standard Laravel -> Admin StudiosUnisDB
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Pages légales - Conformité Loi 25 Québec
Route::controller(LegalController::class)->group(function () {
    Route::get('/politique-confidentialite', 'privacy')->name('legal.privacy');
    Route::get('/conditions-utilisation', 'terms')->name('legal.terms');  
    Route::get('/contact', 'contact')->name('legal.contact');
    Route::post('/contact', 'sendContact')->name('legal.contact.send');
});

// Alias routes légales pour compatibilité footer
Route::redirect('/privacy', '/politique-confidentialite')->name('privacy');
Route::redirect('/terms', '/conditions-utilisation')->name('terms');
Route::redirect('/contact-us', '/contact')->name('contact');

// Profil utilisateur (Laravel Breeze standard)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes d'authentification Laravel Breeze
require __DIR__.'/auth.php';

// Routes administration StudiosUnisDB
require __DIR__.'/admin.php';

// ============================================================================
// ROUTES DE DEBUG TEMPORAIRES - À SUPPRIMER APRÈS DIAGNOSTIC
// ============================================================================

Route::middleware('auth')->group(function () {
    
    // Debug général des permissions
    Route::get('/debug-permissions', function() {
        if (!auth()->check()) {
            return response()->json(['error' => 'Non connecté'], 401);
        }
        
        $user = auth()->user();
        
        return response()->json([
            'session_info' => [
                'user_id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'ecole_id' => $user->ecole_id,
                'auth_check' => auth()->check(),
            ],
            'roles_info' => [
                'roles' => $user->roles->pluck('name')->toArray(),
                'has_superadmin' => $user->hasRole('superadmin'),
            ],
            'permissions_test' => [
                'can_view_ceintures' => $user->can('view-ceintures'),
                'can_view_presences' => $user->can('view-presences'),
                'total_permissions' => $user->getAllPermissions()->count(),
            ],
        ], 200, [], JSON_PRETTY_PRINT);
    });
});

// Test sans authentification
Route::get('/debug-routes-list', function() {
    $adminRoutes = collect(\Illuminate\Support\Facades\Route::getRoutes())
        ->filter(function($route) {
            return str_contains($route->uri(), 'admin/');
        })
        ->take(10)
        ->map(function($route) {
            return [
                'uri' => $route->uri(),
                'name' => $route->getName(),
                'methods' => $route->methods(),
            ];
        })
        ->values();
    
    return response()->json([
        'total_admin_routes' => $adminRoutes->count(),
        'sample_admin_routes' => $adminRoutes->toArray()
    ], 200, [], JSON_PRETTY_PRINT);
});
