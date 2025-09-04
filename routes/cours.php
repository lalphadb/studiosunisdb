<?php

use App\Http\Controllers\CoursController;
use App\Http\Controllers\API\CoursApiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    // Routes principales du module Cours
    Route::prefix('cours')->name('cours.')->group(function () {
        // CRUD de base
        Route::get('/', [CoursController::class, 'index'])->name('index');
        Route::get('/create', [CoursController::class, 'create'])->name('create');
        Route::post('/', [CoursController::class, 'store'])->name('store');
        Route::get('/{cours}', [CoursController::class, 'show'])->name('show');
        Route::get('/{cours}/edit', [CoursController::class, 'edit'])->name('edit');
        Route::put('/{cours}', [CoursController::class, 'update'])->name('update');
        Route::delete('/{cours}', [CoursController::class, 'destroy'])->name('destroy');

        // Actions spéciales
        Route::post('/{cours}/duplicate', [CoursController::class, 'duplicate'])->name('duplicate');
        Route::get('/planning/view', [CoursController::class, 'planning'])->name('planning');
        Route::get('/export/csv', [CoursController::class, 'export'])->name('export');

        // Gestion des inscriptions
        Route::post('/{cours}/inscrire', [CoursController::class, 'inscrireMembre'])->name('inscrire');
        Route::post('/{cours}/desinscrire', [CoursController::class, 'desinscrireMembre'])->name('desinscrire');
        Route::get('/{cours}/membres', [CoursController::class, 'listeMembres'])->name('membres');

        // Gestion des sessions
        Route::get('/{cours}/sessions', [CoursController::class, 'sessions'])->name('sessions');
        Route::post('/{cours}/sessions/annuler', [CoursController::class, 'annulerSession'])->name('sessions.annuler');
        Route::post('/{cours}/sessions/reporter', [CoursController::class, 'reporterSession'])->name('sessions.reporter');

        // Statistiques
        Route::get('/{cours}/statistiques', [CoursController::class, 'statistiques'])->name('statistiques');
        Route::get('/{cours}/presences', [CoursController::class, 'presences'])->name('presences');

        // API endpoints pour AJAX
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/disponibilites', [CoursController::class, 'checkDisponibilites'])->name('disponibilites');
            Route::get('/conflits', [CoursController::class, 'checkConflits'])->name('conflits');
            Route::get('/search', [CoursController::class, 'search'])->name('search');
            Route::get('/calendrier', [CoursController::class, 'calendrier'])->name('calendrier');
        });
    });

    // Route pour le planning général (accessible depuis le menu principal)
    Route::get('/planning', [CoursController::class, 'planningGeneral'])->name('planning');

    // API REST légère (lecture) pour intégration front plus moderne
    Route::prefix('api')->name('api.cours.')->group(function(){
        Route::get('/cours', [CoursApiController::class,'index'])->name('index');
        Route::get('/cours/{cours}', [CoursApiController::class,'show'])->name('show');
    });
});