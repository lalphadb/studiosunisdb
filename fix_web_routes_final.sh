#!/bin/bash
echo "🔧 CORRECTION DÉFINITIVE WEB.PHP"
echo "==============================="

# 1. Sauvegarder l'ancien fichier
echo "📝 1. Sauvegarde..."
cp routes/web.php routes/web.php.broken

# 2. Créer un fichier web.php propre et organisé
echo "📝 2. Création fichier web.php propre..."

cat > routes/web.php << 'FINAL_WEB_ROUTES'
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

// ===============================================================================
// ROUTES ADMIN (StudiosUnisDB v4.1.8.6-DEV)
// OBLIGATOIRE selon prompt XML - toutes les routes admin
// ===============================================================================
require __DIR__.'/admin.php';
FINAL_WEB_ROUTES

echo "✅ Fichier web.php recréé proprement"

# 3. Vérifier la syntaxe PHP
echo ""
echo "📝 3. Vérification syntaxe..."
php -l routes/web.php

if [ $? -eq 0 ]; then
    echo "✅ Syntaxe PHP correcte"
else
    echo "❌ Erreur de syntaxe - restauration..."
    cp routes/web.php.broken routes/web.php
    exit 1
fi

# 4. Vérifier que admin.php existe et est correct
echo ""
echo "📝 4. Vérification admin.php..."
if [ -f "routes/admin.php" ]; then
    php -l routes/admin.php
    if [ $? -eq 0 ]; then
        echo "✅ admin.php syntaxe correcte"
    else
        echo "❌ Erreur dans admin.php"
    fi
else
    echo "❌ admin.php manquant"
fi

# 5. Nettoyer les caches
echo ""
echo "🧹 5. Nettoyage complet..."
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan cache:clear

# 6. Lister les routes principales
echo ""
echo "📋 6. Routes principales créées:"
php artisan route:list --columns=method,uri,name | grep -E "(GET|POST).*(welcome|login|register|dashboard)" | head -10

echo ""
echo "✅ CORRECTION TERMINÉE!"
echo ""
echo "🌐 ROUTES DISPONIBLES:"
echo "• Accueil: http://127.0.0.1:8001/"
echo "• Login: http://127.0.0.1:8001/login"  
echo "• Register: http://127.0.0.1:8001/register"
echo "• Dashboard: http://127.0.0.1:8001/dashboard"
