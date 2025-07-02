@extends('layouts.membre')

@section('title', 'Mon Profil')

@section('content')
<div class="p-6 space-y-6">
    <!-- Header Profil -->
    <div class="bg-gradient-to-r from-blue-500 to-cyan-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">👤 Mon Profil</h1>
                <p class="text-blue-100">{{ $user->ecole->nom ?? 'École non assignée' }}</p>
            </div>
            <div class="text-right">
                <div class="bg-blue-500 bg-opacity-50 px-4 py-2 rounded-lg">
                    <div class="text-sm text-blue-100">{{ $user->name }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations personnelles -->
    <div class="bg-slate-800 rounded-xl p-6 border border-slate-700">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-white flex items-center">
                👤 <span class="ml-2">Mes Informations</span>
            </h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Colonne gauche -->
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-slate-400">Nom complet</label>
                    <div class="text-white font-medium">{{ $user->name }}</div>
                </div>
                
                <div>
                    <label class="text-sm font-medium text-slate-400">Email</label>
                    <div class="text-white">{{ $user->email }}</div>
                </div>
                
                <div>
                    <label class="text-sm font-medium text-slate-400">Téléphone</label>
                    <div class="text-white">{{ $user->telephone ?? 'Non renseigné' }}</div>
                </div>
                
                <div>
                    <label class="text-sm font-medium text-slate-400">Date de naissance</label>
                    <div class="text-white">
                        @if($user->date_naissance)
                            {{ $user->date_naissance->format('d/m/Y') }} 
                            ({{ $user->date_naissance->age }} ans)
                        @else
                            Non renseignée
                        @endif
                    </div>
                </div>
                
                <div>
                    <label class="text-sm font-medium text-slate-400">Sexe</label>
                    <div class="text-white">{{ $user->sexe ?? 'Non renseigné' }}</div>
                </div>
            </div>
            
            <!-- Colonne droite -->
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-slate-400">École</label>
                    <div class="text-white font-medium">{{ $user->ecole->nom ?? 'Non assignée' }}</div>
                </div>
                
                <div>
                    <label class="text-sm font-medium text-slate-400">Date d'inscription</label>
                    <div class="text-white">{{ $user->date_inscription ? \Carbon\Carbon::parse($user->date_inscription)->format('d/m/Y') : $user->created_at->format('d/m/Y') }}</div>
                </div>
                
                <div>
                    <label class="text-sm font-medium text-slate-400">Statut</label>
                    <div>
                        @if($user->active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/20 text-green-300 border border-green-500/30">
                                ✅ Actif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-300 border border-red-500/30">
                                ❌ Inactif
                            </span>
                        @endif
                    </div>
                </div>
                
                @if($user->adresse)
                <div>
                    <label class="text-sm font-medium text-slate-400">Adresse</label>
                    <div class="text-white">
                        {{ $user->adresse }}<br>
                        {{ $user->ville }} {{ $user->code_postal }}
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Contact d'urgence -->
        @if($user->contact_urgence_nom || $user->contact_urgence_telephone)
        <div class="mt-6 pt-6 border-t border-slate-700">
            <h3 class="text-lg font-medium text-white mb-4">🚨 Contact d'urgence</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($user->contact_urgence_nom)
                <div>
                    <label class="text-sm font-medium text-slate-400">Nom</label>
                    <div class="text-white">{{ $user->contact_urgence_nom }}</div>
                </div>
                @endif
                @if($user->contact_urgence_telephone)
                <div>
                    <label class="text-sm font-medium text-slate-400">Téléphone</label>
                    <div class="text-white">{{ $user->contact_urgence_telephone }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Ceinture actuelle et progression -->
    <div class="bg-slate-800 rounded-xl p-6 border border-slate-700">
        <h2 class="text-xl font-bold text-white mb-6 flex items-center">
            🥋 <span class="ml-2">Ma Progression Martial</span>
        </h2>
        
        @php
            $ceintureActuelle = $user->userCeintures()
                ->with('ceinture')
                ->where('valide', true)
                ->latest('date_obtention')
                ->first();
        @endphp
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Ceinture actuelle -->
            <div class="bg-gradient-to-br from-orange-500/20 to-red-500/20 border border-orange-500/30 p-6 rounded-lg">
                <h3 class="text-lg font-medium text-white mb-4">🥋 Ceinture actuelle</h3>
                @if($ceintureActuelle)
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full mr-4 border-2 border-white shadow-md" 
                             style="background-color: {{ $ceintureActuelle->ceinture->couleur }}"></div>
                        <div>
                            <div class="font-bold text-lg text-white">
                                {{ $ceintureActuelle->ceinture->nom }}
                            </div>
                            <div class="text-sm text-gray-300">
                                Obtenue le {{ $ceintureActuelle->date_obtention->format('d/m/Y') }}
                            </div>
                            @if($ceintureActuelle->examinateur)
                            <div class="text-xs text-gray-400">
                                Par {{ $ceintureActuelle->examinateur }}
                            </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-white rounded-full mr-4 border-2 border-gray-300 shadow-md"></div>
                        <div>
                            <div class="font-bold text-lg text-white">Ceinture Blanche</div>
                            <div class="text-sm text-gray-300">Débutant</div>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Statistiques -->
            <div class="bg-gradient-to-br from-blue-500/20 to-cyan-500/20 border border-blue-500/30 p-6 rounded-lg">
                <h3 class="text-lg font-medium text-white mb-4">📊 Mes Statistiques</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-300">Ceintures obtenues:</span>
                        <span class="text-white font-medium">{{ $user->userCeintures->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-300">Cours inscrits:</span>
                        <span class="text-white font-medium">{{ $user->inscriptionsCours->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-300">Présences:</span>
                        <span class="text-white font-medium">{{ $user->presences->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-300">Ancienneté:</span>
                        <span class="text-white font-medium">{{ $user->created_at->diffInMonths(now()) }} mois</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modifier mes informations personnelles -->
    <div class="bg-slate-800 rounded-xl p-6 border border-slate-700">
        <h2 class="text-xl font-bold text-white mb-6 flex items-center">
            ✏️ <span class="ml-2">Modifier mes informations</span>
        </h2>
        
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            @method('PATCH')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Téléphone</label>
                    <input type="text" name="telephone" value="{{ old('telephone', $user->telephone) }}" 
                           class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Adresse</label>
                    <input type="text" name="adresse" value="{{ old('adresse', $user->adresse) }}" 
                           class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Contact d'urgence</label>
                    <input type="text" name="contact_urgence_nom" value="{{ old('contact_urgence_nom', $user->contact_urgence_nom) }}" 
                           class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Téléphone d'urgence</label>
                    <input type="text" name="contact_urgence_telephone" value="{{ old('contact_urgence_telephone', $user->contact_urgence_telephone) }}" 
                           class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    💾 Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>

    <!-- Changer mot de passe -->
    <div class="bg-slate-800 rounded-xl p-6 border border-slate-700">
        <h2 class="text-xl font-bold text-white mb-6 flex items-center">
            🔐 <span class="ml-2">Changer le mot de passe</span>
        </h2>
        
        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Mot de passe actuel</label>
                <input type="password" name="current_password" 
                       class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Nouveau mot de passe</label>
                <input type="password" name="password" 
                       class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" 
                       class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    🔑 Changer le mot de passe
                </button>
            </div>
        </form>
    </div>

    <!-- Retour au dashboard -->
    <div class="flex justify-center">
        <a href="{{ route('dashboard') }}" class="px-6 py-2 bg-slate-700 text-white rounded-lg hover:bg-slate-600 transition-colors">
            ← Retour au dashboard
        </a>
    </div>
</div>
@endsection
