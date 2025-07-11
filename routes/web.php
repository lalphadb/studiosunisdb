<?php

use Illuminate\Support\Facades\Route;

// Redirection de la racine vers Filament admin
Route::get('/', function () {
    return redirect('/admin');
});

// Toutes les routes admin sont gérées par Filament
// Les routes d'authentification sont gérées par Filament
