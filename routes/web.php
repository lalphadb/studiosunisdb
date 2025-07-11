<?php

use Illuminate\Support\Facades\Route;

// Redirection de la racine vers Filament admin
Route::get('/', function () {
    return redirect('/admin');
});

// Route de fallback pour 'login' - redirige vers Filament
Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login');

// Filament gère toutes les routes /admin/*

// Redirection après déconnexion
Route::get('/logout', function () {
    return redirect('/admin/login');
})->name('logout');
