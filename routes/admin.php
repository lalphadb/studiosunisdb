<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| Routes Administration
|--------------------------------------------------------------------------
|
| Routes pour l'interface d'administration de StudiosDB
| Toutes ces routes sont protégées par les middlewares 'auth' et 'admin'
|
*/

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    
    // TODO: Ajouter les autres routes au fur et à mesure de la création des contrôleurs
    
    // Users Management
    // Route::resource('users', UserController::class);
    
    // Cours Management
    // Route::resource('cours', CoursController::class);
    
    // Présences Management
    // Route::resource('presences', PresenceController::class);
    
    // Ceintures Management
    // Route::resource('ceintures', CeintureController::class);
    
    // Paiements Management
    // Route::resource('paiements', PaiementController::class);
    
    // Séminaires Management
    // Route::resource('seminaires', SeminaireController::class);
    
    // Sessions de Cours
    // Route::resource('sessions', SessionCoursController::class);
    
    // Écoles Management (SuperAdmin only)
    // Route::middleware('role:superadmin')->group(function () {
    //     Route::resource('ecoles', EcoleController::class);
    // });
});

// Route de test pour vérifier que le fichier est chargé
if (app()->environment('local')) {
    Route::get('/admin/test', function () {
        return 'Routes admin chargées correctement!';
    });
}
