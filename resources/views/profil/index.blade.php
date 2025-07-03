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
                    <div class="text-xs text-blue-200">Membre</div>
                </div>
            </div>
        </div>
    </div>

    <!-- EXACTEMENT LES MÊMES INFORMATIONS QUE L'ADMIN VOIT -->
    
    <!-- Informations personnelles -->
    <div class="bg-slate-800 rounded-xl p-6 border border-slate-700">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-white flex items-center">
                👤 <span class="ml-2">Mes Informations</span>
            </h2>
            <a href="{{ route('membre.profil.edit') }}" 
               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                ✏️ Modifier
            </a>
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
                    <div class="text-white">
                        @if($user->sexe === 'M')
                            Masculin
                        @elseif($user->sexe === 'F')
                            Féminin
                        @elseif($user->sexe === 'Autre')
                            Autre
                        @else
                            Non renseigné
                        @endif
                    </div>
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
                
                <!-- Adresse complète (comme l'admin voit) -->
                @if($user->adresse || $user->ville || $user->code_postal)
                <div>
                    <label class="text-sm font-medium text-slate-400">Adresse</label>
                    <div class="text-white">
                        @if($user->adresse){{ $user->adresse }}<br>@endif
                        @if($user->ville || $user->code_postal){{ $user->ville }} {{ $user->code_postal }}@endif
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Contact d'urgence (comme l'admin voit) -->
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

        <!-- Informations famille (comme l'admin voit) -->
        @if($user->nom_famille_groupe || $user->contact_principal_famille || $user->famille_principale_id)
        <div class="mt-6 pt-6 border-t border-slate-700">
            <h3 class="text-lg font-medium text-white mb-4">👨‍👩‍👧‍👦 Informations Famille</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($user->nom_famille_groupe)
                <div>
                    <label class="text-sm font-medium text-slate-400">Nom de famille groupé</label>
                    <div class="text-white">{{ $user->nom_famille_groupe }}</div>
                </div>
                @endif
                
                @if($user->contact_principal_famille)
                <div>
                    <label class="text-sm font-medium text-slate-400">Contact principal famille</label>
                    <div class="text-white">{{ $user->contact_principal_famille }}</div>
                </div>
                @endif
                
                @if($user->telephone_principal_famille)
                <div>
                    <label class="text-sm font-medium text-slate-400">Téléphone principal famille</label>
                    <div class="text-white">{{ $user->telephone_principal_famille }}</div>
                </div>
                @endif
                
                @if($user->famille_principale_id)
                <div>
                    <label class="text-sm font-medium text-slate-400">Chef de famille</label>
                    <div class="text-white">{{ $user->famillePrincipale->name ?? 'Chef de famille introuvable' }}</div>
                </div>
                @endif
                
                @if($user->notes_famille)
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-slate-400">Notes famille</label>
                    <div class="text-white">{{ $user->notes_famille }}</div>
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

    <!-- Actions rapides -->
    <div class="bg-slate-800 rounded-xl p-6 border border-slate-700">
        <h2 class="text-xl font-bold text-white mb-6 flex items-center">
            🚀 <span class="ml-2">Actions Rapides</span>
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Modifier profil -->
            <a href="{{ route('membre.profil.edit') }}" 
               class="block p-4 bg-blue-500/10 border border-blue-500/30 rounded-lg hover:bg-blue-500/20 transition-colors">
                <div class="flex items-center">
                    <div class="text-blue-400 mr-3 text-2xl">✏️</div>
                    <div>
                        <h4 class="font-medium text-white">Modifier mes informations</h4>
                        <p class="text-sm text-gray-300">Mettre à jour mon profil</p>
                    </div>
                </div>
            </a>

            <!-- Changer mot de passe -->
            <a href="{{ route('membre.profil.password') }}" 
               class="block p-4 bg-red-500/10 border border-red-500/30 rounded-lg hover:bg-red-500/20 transition-colors">
                <div class="flex items-center">
                    <div class="text-red-400 mr-3 text-2xl">🔐</div>
                    <div>
                        <h4 class="font-medium text-white">Changer mot de passe</h4>
                        <p class="text-sm text-gray-300">Sécurité de mon compte</p>
                    </div>
                </div>
            </a>

            <!-- Retour dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="block p-4 bg-green-500/10 border border-green-500/30 rounded-lg hover:bg-green-500/20 transition-colors">
                <div class="flex items-center">
                    <div class="text-green-400 mr-3 text-2xl">🏠</div>
                    <div>
                        <h4 class="font-medium text-white">Mon dashboard</h4>
                        <p class="text-sm text-gray-300">Retour à l'accueil</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
