<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Membre\MembreController;

/*
|--------------------------------------------------------------------------
| Routes Web - StudiosUnisDB
|--------------------------------------------------------------------------
*/

// Route d'accueil
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Routes d'authentification Laravel Breeze
require __DIR__.'/auth.php';

// Routes d'inscription personnalisées
Route::get('register', [RegisterController::class, 'create'])->name('register');
Route::post('register', [RegisterController::class, 'store']);

// Routes du profil utilisateur (Laravel Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Dashboard avec redirection selon le rôle
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        // Rediriger les admins vers l'interface admin
        if (auth()->user()->hasRole(['superadmin', 'admin_ecole', 'instructeur'])) {
            return redirect()->route('admin.dashboard');
        }
        // Dashboard membre normal
        return view('dashboard');
    })->name('dashboard');
});

// ===============================================================================
// ROUTES MEMBRE - Interface profil membre (NOUVELLES ROUTES)
// ===============================================================================
Route::middleware('auth')->group(function () {
    // Profil membre - MÊME DONNÉES que admin/users/{id} mais interface membre
    Route::get('/profil', [MembreController::class, 'profil'])->name('membre.profil');
    Route::get('/profil/modifier', [MembreController::class, 'edit'])->name('membre.profil.edit');
    Route::put('/profil', [MembreController::class, 'update'])->name('membre.profil.update');
    
    // Mot de passe membre
    Route::get('/profil/mot-de-passe', function() {
        return view('membre.profil-password');
    })->name('membre.profil.password');
    Route::put('/profil/mot-de-passe', [MembreController::class, 'updatePassword'])->name('membre.profil.password.update');
});

// ===============================================================================
// ROUTES ADMIN (StudiosUnisDB v4.1.10.2)
// OBLIGATOIRE selon prompt XML - toutes les routes admin
// ===============================================================================
require __DIR__.'/admin.php';

// Pages légales et conformité
Route::get('/politique-confidentialite', function () {
    return view('legal.privacy');
})->name('privacy');

Route::get('/conditions-utilisation', function () {
    return view('legal.terms');
})->name('terms');

Route::get('/contact', function () {
    return view('legal.contact');
})->name('contact');
