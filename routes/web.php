<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisterController;

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

// Route profil membre (interface simple sans sidebar admin)
Route::middleware('auth')->group(function () {
    Route::get('/profil', function () {
        return view('membre.profil', ['user' => auth()->user()]);
    })->name('membre.profil');
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
