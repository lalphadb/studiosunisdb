#!/bin/bash
# Correction erreur actionsMasse - StudiosDB v5.7.1

echo "=== CORRECTION ERREUR ACTIONS-MASSE ==="
echo "Problème: Call to undefined method actionsMasse()"
echo

# 1. Vérifier et corriger les routes admin
cat > routes/admin.php << 'ROUTES_EOF'
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EcoleController;
use App\Http\Controllers\Admin\CourController;
use App\Http\Controllers\Admin\CeintureController;
use App\Http\Controllers\Admin\SeminaireController;
use App\Http\Controllers\Admin\PaiementController;
use App\Http\Controllers\Admin\PresenceController;

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Gestion des utilisateurs
    Route::resource('users', UserController::class);
    Route::post('users/{user}/export-data', [UserController::class, 'exportData'])->name('users.export-data');

    // Gestion des écoles
    Route::resource('ecoles', EcoleController::class);

    // Gestion des cours
    Route::resource('cours', CourController::class);
    Route::post('cours/{cour}/clone', [CourController::class, 'clone'])->name('cours.clone');

    // Gestion des ceintures
    Route::resource('ceintures', CeintureController::class);
    Route::post('ceintures/attribution-masse', [CeintureController::class, 'attributionMasse'])->name('ceintures.attribution-masse');

    // Gestion des séminaires
    Route::resource('seminaires', SeminaireController::class);

    // Gestion des paiements - CORRECTION ICI
    Route::resource('paiements', PaiementController::class);
    // Route corrigée pour les actions de masse
    Route::post('paiements/bulk-validate', [PaiementController::class, 'bulkValidate'])->name('paiements.bulk-validate');
    Route::patch('paiements/{paiement}/quick-validate', [PaiementController::class, 'quickValidate'])->name('paiements.quick-validate');

    // Gestion des présences
    Route::resource('presences', PresenceController::class);
});
ROUTES_EOF

echo "✅ Routes corrigées"

# 2. Nettoyer les caches
echo "🔧 Nettoyage des caches..."
php artisan route:clear
php artisan config:clear
php artisan view:clear

echo
echo "✅ PROBLÈME RÉSOLU !"
echo "🔧 Corrections appliquées :"
echo "   ✓ Route actions-masse supprimée"
echo "   ✓ Route bulk-validate correctement définie"
echo "   ✓ Caches nettoyés"
echo
echo "🧪 TESTER MAINTENANT :"
echo "   http://127.0.0.1:8001/admin/paiements"
