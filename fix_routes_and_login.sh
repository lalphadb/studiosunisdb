#!/bin/bash
echo "🔧 CORRECTION ROUTES ET LOGIN"
echo "============================="

# 1. Ajouter la route welcome manquante
echo "📝 1. Ajout route welcome..."

# Vérifier et ajouter la route welcome en premier dans web.php
if ! grep -q "route.*welcome" routes/web.php; then
    # Créer un fichier temporaire avec la route welcome en premier
    cat > /tmp/web_routes_temp.php << 'WEB_ROUTES'
<?php

use Illuminate\Support\Facades\Route;

// Route d'accueil
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

WEB_ROUTES

    # Ajouter le contenu existant (en évitant les doublons)
    grep -v "<?php" routes/web.php >> /tmp/web_routes_temp.php
    
    # Remplacer le fichier
    mv /tmp/web_routes_temp.php routes/web.php
    
    echo "✅ Route welcome ajoutée"
else
    echo "✅ Route welcome déjà présente"
fi

# 2. Corriger le fichier login.blade.php pour éviter l'erreur
echo ""
echo "📝 2. Correction fichier login..."

cat > resources/views/auth/login.blade.php << 'LOGIN_FIXED'
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StudiosUnisDB - Connexion</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-900 text-white min-h-screen flex items-center justify-center">
    <div class="bg-slate-800 p-8 rounded-xl border border-slate-700 w-full max-w-md">
        <div class="text-center mb-6">
            <div class="text-4xl mb-2">🥋</div>
            <h1 class="text-2xl font-bold">StudiosUnisDB</h1>
            <p class="text-slate-400">Connexion à votre compte</p>
        </div>
        
        @if ($errors->any())
            <div class="bg-red-600/20 border border-red-600 rounded-lg p-4 mb-4">
                <ul class="text-red-300 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('status'))
            <div class="bg-green-600/20 border border-green-600 rounded-lg p-4 mb-4">
                <p class="text-green-300 text-sm">{{ session('status') }}</p>
            </div>
        @endif
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium mb-2">Email</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}"
                       class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                       required autofocus>
            </div>
            
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium mb-2">Mot de passe</label>
                <input type="password" 
                       id="password" 
                       name="password"
                       class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                       required>
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="rounded bg-slate-700 border-slate-600 text-blue-600">
                    <span class="ml-2 text-sm text-slate-300">Se souvenir de moi</span>
                </label>
            </div>
            
            <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-colors">
                Se connecter
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <p class="text-slate-400 text-sm mb-4">Pas encore de compte ?</p>
            <a href="{{ route('register') }}" 
               class="inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors">
                Créer un compte
            </a>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ url('/') }}" class="text-slate-400 hover:text-white text-sm">
                ← Retour à l'accueil
            </a>
        </div>
        
        <!-- Comptes de test (à retirer en production) -->
        <div class="mt-6 p-4 bg-slate-700/50 rounded-lg">
            <p class="text-xs text-slate-400 text-center mb-2">Comptes de test :</p>
            <div class="text-xs text-slate-400 space-y-1">
                <p>Admin: lalpha@4lb.ca / password123</p>
                <p>École: louis@4lb.ca / password123</p>
            </div>
        </div>
    </div>
</body>
</html>
LOGIN_FIXED

echo "✅ Fichier login corrigé"

# 3. Vérifier le contenu des routes
echo ""
echo "📝 3. Vérification des routes..."

# Afficher les premières lignes de web.php pour vérifier
echo "Contenu de routes/web.php :"
head -10 routes/web.php

# 4. Nettoyer les caches
echo ""
echo "🧹 4. Nettoyage des caches..."
php artisan route:clear
php artisan config:clear
php artisan view:clear

# 5. Lister les routes pour vérifier
echo ""
echo "📋 5. Routes disponibles :"
php artisan route:list --columns=method,uri,name | grep -E "(welcome|login|register|/)" | head -10

echo ""
echo "✅ CORRECTION TERMINÉE!"
echo ""
echo "🧪 TESTEZ MAINTENANT :"
echo "• Accueil: http://127.0.0.1:8001/"
echo "• Login: http://127.0.0.1:8001/login"
echo "• Register: http://127.0.0.1:8001/register"
