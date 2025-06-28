#!/bin/bash
echo "🔧 CRÉATION SYSTÈME AUTHENTIFICATION COMPLET"
echo "============================================"

# 1. Créer le contrôleur d'inscription personnalisé
echo "📝 1. Création contrôleur inscription personnalisé..."

cat > app/Http/Controllers/Auth/RegisterController.php << 'REGISTER_CONTROLLER'
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ecole;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Afficher le formulaire d'inscription
     */
    public function create(): View
    {
        $ecoles = Ecole::where('active', true)->orderBy('nom')->get();
        return view('auth.register', compact('ecoles'));
    }

    /**
     * Traiter l'inscription
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'ecole_id' => ['required', 'exists:ecoles,id'],
            'telephone' => ['nullable', 'string', 'max:20'],
            'date_naissance' => ['required', 'date', 'before:today'],
            'sexe' => ['required', 'in:M,F,Autre'],
            'adresse' => ['required', 'string', 'max:255'],
            'ville' => ['required', 'string', 'max:100'],
            'code_postal' => ['required', 'string', 'max:10'],
            'contact_urgence_nom' => ['required', 'string', 'max:255'],
            'contact_urgence_telephone' => ['required', 'string', 'max:20'],
            'accepte_loi25' => ['accepted'],
            'accepte_conditions' => ['accepted'],
        ], [
            'accepte_loi25.accepted' => 'Vous devez accepter la politique de confidentialité (Loi 25).',
            'accepte_conditions.accepted' => 'Vous devez accepter les conditions d\'utilisation.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'ecole_id' => $request->ecole_id,
            'telephone' => $request->telephone,
            'date_naissance' => $request->date_naissance,
            'sexe' => $request->sexe,
            'adresse' => $request->adresse,
            'ville' => $request->ville,
            'code_postal' => $request->code_postal,
            'contact_urgence_nom' => $request->contact_urgence_nom,
            'contact_urgence_telephone' => $request->contact_urgence_telephone,
            'active' => true,
            'date_inscription' => now(),
        ]);

        // Assigner le rôle membre par défaut
        $user->assignRole('membre');

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Votre compte a été créé avec succès !');
    }
}
REGISTER_CONTROLLER

echo "✅ Contrôleur inscription créé"

# 2. Créer la page d'accueil (welcome)
echo ""
echo "📝 2. Création page d'accueil..."

cat > resources/views/welcome.blade.php << 'WELCOME_VIEW'
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StudiosUnisDB - Gestion d'Écoles de Karaté</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-900 text-white">
    <!-- Navigation -->
    <nav class="bg-slate-800 border-b border-slate-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-4">
                    <span class="text-2xl">🥋</span>
                    <h1 class="text-xl font-bold">StudiosUnisDB</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-blue-400 hover:text-blue-300">Tableau de bord</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-slate-300 hover:text-white">Déconnexion</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300">Connexion</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg">S'inscrire</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-transparent py-20">
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-transparent to-slate-900 opacity-60"></div>
            
            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl md:text-6xl font-bold mb-6">
                        Gestion d'Écoles de Karaté
                    </h1>
                    <p class="text-xl md:text-2xl text-white/80 mb-8">
                        Système complet pour gérer les membres, cours, ceintures et paiements
                    </p>
                    
                    @guest
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('register') }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg text-lg font-semibold">
                                Créer un compte
                            </a>
                            <a href="{{ route('login') }}" 
                               class="bg-white/20 hover:bg-white/30 text-white px-8 py-4 rounded-lg text-lg font-semibold">
                                Se connecter
                            </a>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </div>

    <!-- Fonctionnalités -->
    <div class="py-20 bg-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">Fonctionnalités</h2>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-slate-700 p-6 rounded-xl">
                    <div class="text-3xl mb-4">👤</div>
                    <h3 class="text-xl font-bold mb-2">Gestion des Membres</h3>
                    <p class="text-slate-300">Inscriptions, profils complets, suivi des progressions</p>
                </div>
                
                <div class="bg-slate-700 p-6 rounded-xl">
                    <div class="text-3xl mb-4">📚</div>
                    <h3 class="text-xl font-bold mb-2">Cours et Séminaires</h3>
                    <p class="text-slate-300">Planning, inscriptions, présences automatisées</p>
                </div>
                
                <div class="bg-slate-700 p-6 rounded-xl">
                    <div class="text-3xl mb-4">🥋</div>
                    <h3 class="text-xl font-bold mb-2">Système de Ceintures</h3>
                    <p class="text-slate-300">Suivi des progressions, examens, certifications</p>
                </div>
                
                <div class="bg-slate-700 p-6 rounded-xl">
                    <div class="text-3xl mb-4">💰</div>
                    <h3 class="text-xl font-bold mb-2">Gestion Financière</h3>
                    <p class="text-slate-300">Paiements, factures, rapports automatisés</p>
                </div>
                
                <div class="bg-slate-700 p-6 rounded-xl">
                    <div class="text-3xl mb-4">🏫</div>
                    <h3 class="text-xl font-bold mb-2">Multi-École</h3>
                    <p class="text-slate-300">Gestion de plusieurs dojos, permissions granulaires</p>
                </div>
                
                <div class="bg-slate-700 p-6 rounded-xl">
                    <div class="text-3xl mb-4">📊</div>
                    <h3 class="text-xl font-bold mb-2">Rapports et Analyses</h3>
                    <p class="text-slate-300">Statistiques détaillées, tableaux de bord</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-slate-900 border-t border-slate-700 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-slate-400">
                &copy; {{ date('Y') }} StudiosUnisDB. Conforme à la Loi 25 sur la protection des renseignements personnels.
            </p>
        </div>
    </footer>
</body>
</html>
WELCOME_VIEW

echo "✅ Page d'accueil créée"

# 3. Créer la page de connexion améliorée
echo ""
echo "📝 3. Création page de connexion..."

cat > resources/views/auth/login.blade.php << 'LOGIN_VIEW'
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
            <a href="{{ route('welcome') }}" class="text-slate-400 hover:text-white text-sm">
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
LOGIN_VIEW

echo "✅ Page de connexion créée"

# 4. Créer la page d'inscription complète avec Loi 25
echo ""
echo "📝 4. Création page d'inscription avec Loi 25..."

cat > resources/views/auth/register.blade.php << 'REGISTER_VIEW'
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StudiosUnisDB - Créer un compte</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-900 text-white min-h-screen py-8">
    <div class="max-w-2xl mx-auto px-4">
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-8">
            <div class="text-center mb-8">
                <div class="text-4xl mb-2">🥋</div>
                <h1 class="text-3xl font-bold">Créer votre compte</h1>
                <p class="text-slate-400">Rejoignez notre réseau d'écoles de karaté</p>
            </div>
            
            @if ($errors->any())
                <div class="bg-red-600/20 border border-red-600 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-red-300 mb-2">Erreurs de validation :</h3>
                    <ul class="text-red-300 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Informations de base -->
                <div class="bg-slate-700/50 p-6 rounded-lg">
                    <h2 class="text-xl font-semibold mb-4 flex items-center">
                        <span class="text-2xl mr-2">👤</span>
                        Informations personnelles
                    </h2>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium mb-2">Nom complet *</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" 
                                   class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-blue-500" required>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium mb-2">Email *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" 
                                   class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-blue-500" required>
                        </div>
                        
                        <div>
                            <label for="telephone" class="block text-sm font-medium mb-2">Téléphone</label>
                            <input type="tel" id="telephone" name="telephone" value="{{ old('telephone') }}" 
                                   class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label for="date_naissance" class="block text-sm font-medium mb-2">Date de naissance *</label>
                            <input type="date" id="date_naissance" name="date_naissance" value="{{ old('date_naissance') }}" 
                                   class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-blue-500" required>
                        </div>
                        
                        <div>
                            <label for="sexe" class="block text-sm font-medium mb-2">Sexe *</label>
                            <select id="sexe" name="sexe" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-blue-500" required>
                                <option value="">Sélectionner...</option>
                                <option value="M" {{ old('sexe') == 'M' ? 'selected' : '' }}>Masculin</option>
                                <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>Féminin</option>
                                <option value="Autre" {{ old('sexe') == 'Autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="ecole_id" class="block text-sm font-medium mb-2">École *</label>
                            <select id="ecole_id" name="ecole_id" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-blue-500" required>
                                <option value="">Choisir une école...</option>
                                @foreach($ecoles as $ecole)
                                    <option value="{{ $ecole->id }}" {{ old('ecole_id') == $ecole->id ? 'selected' : '' }}>
                                        {{ $ecole->nom }} - {{ $ecole->ville }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Adresse -->
                <div class="bg-slate-700/50 p-6 rounded-lg">
                    <h2 class="text-xl font-semibold mb-4 flex items-center">
                        <span class="text-2xl mr-2">🏠</span>
                        Adresse
                    </h2>
                    
                    <div class="grid md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label for="adresse" class="block text-sm font-medium mb-2">Adresse *</label>
                            <input type="text" id="adresse" name="adresse" value="{{ old('adresse') }}" 
                                   class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-blue-500" required>
                        </div>
                        
                        <div>
                            <label for="code_postal" class="block text-sm font-medium mb-2">Code postal *</label>
                            <input type="text" id="code_postal" name="code_postal" value="{{ old('code_postal') }}" 
                                   class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-blue-500" required>
                        </div>
                        
                        <div class="md:col-span-3">
                            <label for="ville" class="block text-sm font-medium mb-2">Ville *</label>
                            <input type="text" id="ville" name="ville" value="{{ old('ville') }}" 
                                   class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-blue-500" required>
                        </div>
                    </div>
                </div>

                <!-- Contact d'urgence -->
                <div class="bg-slate-700/50 p-6 rounded-lg">
                    <h2 class="text-xl font-semibold mb-4 flex items-center">
                        <span class="text-2xl mr-2">🚨</span>
                        Contact d'urgence
                    </h2>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label for="contact_urgence_nom" class="block text-sm font-medium mb-2">Nom du contact *</label>
                            <input type="text" id="contact_urgence_nom" name="contact_urgence_nom" value="{{ old('contact_urgence_nom') }}" 
                                   class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-blue-500" required>
                        </div>
                        
                        <div>
                            <label for="contact_urgence_telephone" class="block text-sm font-medium mb-2">Téléphone du contact *</label>
                            <input type="tel" id="contact_urgence_telephone" name="contact_urgence_telephone" value="{{ old('contact_urgence_telephone') }}" 
                                   class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-blue-500" required>
                        </div>
                    </div>
                </div>

                <!-- Mot de passe -->
                <div class="bg-slate-700/50 p-6 rounded-lg">
                    <h2 class="text-xl font-semibold mb-4 flex items-center">
                        <span class="text-2xl mr-2">🔒</span>
                        Sécurité
                    </h2>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium mb-2">Mot de passe *</label>
                            <input type="password" id="password" name="password" 
                                   class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-blue-500" required>
                            <p class="text-xs text-slate-400 mt-1">Minimum 8 caractères</p>
                        </div>
                        
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium mb-2">Confirmer le mot de passe *</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" 
                                   class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-blue-500" required>
                        </div>
                    </div>
                </div>

                <!-- Loi 25 et conditions -->
                <div class="bg-slate-700/50 p-6 rounded-lg">
                    <h2 class="text-xl font-semibold mb-4 flex items-center">
                        <span class="text-2xl mr-2">📋</span>
                        Consentements requis
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <input type="checkbox" id="accepte_loi25" name="accepte_loi25" value="1" 
                                   class="mt-1 rounded bg-slate-700 border-slate-600 text-blue-600" required>
                            <div>
                                <label for="accepte_loi25" class="text-sm cursor-pointer">
                                    <strong>J'accepte la politique de confidentialité (Loi 25) *</strong>
                                </label>
                                <p class="text-xs text-slate-400 mt-1">
                                    Vos renseignements personnels sont collectés pour gérer votre inscription et participation aux activités de karaté. 
                                    Ils ne seront jamais partagés avec des tiers sans votre consentement.
                                    <a href="#" class="text-blue-400 hover:text-blue-300">Lire la politique complète</a>
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <input type="checkbox" id="accepte_conditions" name="accepte_conditions" value="1" 
                                   class="mt-1 rounded bg-slate-700 border-slate-600 text-blue-600" required>
                            <div>
                                <label for="accepte_conditions" class="text-sm cursor-pointer">
                                    <strong>J'accepte les conditions d'utilisation *</strong>
                                </label>
                                <p class="text-xs text-slate-400 mt-1">
                                    En créant un compte, vous acceptez nos conditions d'utilisation et notre code de conduite.
                                    <a href="#" class="text-blue-400 hover:text-blue-300">Lire les conditions</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="submit" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-colors">
                        Créer mon compte
                    </button>
                    
                    <a href="{{ route('login') }}" 
                       class="flex-1 bg-slate-600 hover:bg-slate-500 text-white font-medium py-3 px-6 rounded-lg text-center transition-colors">
                        J'ai déjà un compte
                    </a>
                </div>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('welcome') }}" class="text-slate-400 hover:text-white text-sm">
                    ← Retour à l'accueil
                </a>
            </div>
        </div>
    </div>
</body>
</html>
REGISTER_VIEW

echo "✅ Page d'inscription créée"

# 5. Ajouter les routes d'authentification
echo ""
echo "📝 5. Ajout des routes d'authentification..."

# Vérifier si les routes existent déjà
if ! grep -q "RegisterController" routes/web.php; then
    cat >> routes/web.php << 'ROUTES_EOF'

// Routes d'authentification personnalisées
use App\Http\Controllers\Auth\RegisterController;

Route::get('register', [RegisterController::class, 'create'])->name('register');
Route::post('register', [RegisterController::class, 'store']);
ROUTES_EOF
    echo "✅ Routes d'inscription ajoutées"
fi

# 6. Mettre à jour les routes par défaut
echo ""
echo "📝 6. Mise à jour routes par défaut..."

# S'assurer que la route welcome existe
if ! grep -q "Route::get('/', " routes/web.php; then
    sed -i "1i Route::get('/', function () { return view('welcome'); })->name('welcome');" routes/web.php
    echo "✅ Route welcome ajoutée"
fi

# 7. Nettoyer et reconfigurer
echo ""
echo "🧹 7. Nettoyage final..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo ""
echo "✅ SYSTÈME D'AUTHENTIFICATION COMPLET CRÉÉ!"
echo ""
echo "🌐 PAGES DISPONIBLES:"
echo "• Accueil: http://127.0.0.1:8001/"
echo "• Connexion: http://127.0.0.1:8001/login"
echo "• Inscription: http://127.0.0.1:8001/register"
echo ""
echo "📋 FONCTIONNALITÉS:"
echo "• ✅ Formulaire d'inscription complet avec tous les champs utilisateur"
echo "• ✅ Conformité Loi 25 avec consentements explicites"
echo "• ✅ Page d'accueil attractive"
echo "• ✅ Validation complète des données"
echo "• ✅ Assignment automatique du rôle 'membre'"
