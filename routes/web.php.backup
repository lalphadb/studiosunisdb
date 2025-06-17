<?php

use App\Http\Controllers\LegalController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - StudiosUnisDB v3.8.3
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
