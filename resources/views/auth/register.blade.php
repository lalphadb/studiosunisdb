<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StudiosDB - Créer un compte</title>
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
                <div class="flex flex-col sm:flex-flex flex-wrap -mx-2 gap-4">
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
