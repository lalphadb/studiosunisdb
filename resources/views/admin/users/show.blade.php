@extends('layouts.admin')
@section('title', 'Profil Utilisateur')

@section('content')
<div class="space-y-6">
    <!-- Header avec couleur bleue -->
    <div class="bg-gradient-to-r from-blue-500 to-cyan-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-6">
                <!-- Avatar avec initiales -->
                <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center">
                    <span class="text-2xl font-bold text-white">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </span>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">{{ $user->name }}</h1>
                    <p class="text-blue-100 text-lg">{{ $user->email }}</p>
                    <!-- Badges de rôles -->
                    <div class="flex space-x-2 mt-3">
                        @if($user->roles->isNotEmpty())
                            @foreach($user->roles as $role)
                                <span class="px-3 py-1 text-sm font-medium rounded-md bg-blue-800 text-blue-100">
                                    {{ ucfirst(str_replace(['-', '_'], ' ', $role->name)) }}
                                </span>
                            @endforeach
                        @endif
                        @if($user->active)
                            <span class="px-3 py-1 text-sm font-medium rounded-md bg-green-600 text-green-100">
                                Actif
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Boutons d'action -->
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.edit', $user) }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Modifier
                </a>
                <a href="{{ route('admin.users.index') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour à la liste
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne gauche - Informations principales -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Informations Personnelles -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
                <div class="bg-blue-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Informations Personnelles
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Colonne 1 -->
                        <div class="space-y-4">
                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Nom complet</div>
                                <div class="text-white font-medium">{{ $user->name }}</div>
                            </div>

                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Email</div>
                                <div class="text-white">{{ $user->email }}</div>
                            </div>
                            
                            @if($user->telephone)
                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Téléphone</div>
                                <div class="text-white">{{ $user->telephone }}</div>
                            </div>
                            @endif

                            @if($user->sexe)
                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Sexe</div>
                                <div class="text-white">{{ $user->sexe === 'M' ? 'Masculin' : ($user->sexe === 'F' ? 'Féminin' : $user->sexe) }}</div>
                            </div>
                            @endif
                        </div>

                        <!-- Colonne 2 -->
                        <div class="space-y-4">
                            @if($user->date_naissance)
                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Date de naissance</div>
                                <div class="text-white">{{ $user->date_naissance->format('d/m/Y') }}</div>
                                <div class="text-blue-400">{{ $user->age ?? $user->date_naissance->age }} ans</div>
                            </div>
                            @endif

                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Date d'inscription</div>
                                <div class="text-white">{{ $user->date_inscription ? $user->date_inscription->format('d/m/Y') : 'N/A' }}</div>
                                @if($user->date_inscription)
                                    <div class="text-slate-400 text-sm">{{ $user->date_inscription->diffForHumans() }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($user->adresse)
                    <div class="mt-6 pt-6 border-t border-slate-700">
                        <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Adresse</div>
                        <div class="text-white">{{ $user->adresse }}</div>
                        @if($user->ville || $user->code_postal)
                            <div class="text-slate-300">
                                {{ $user->ville }}{{ $user->ville && $user->code_postal ? ', ' : '' }}{{ $user->code_postal }}
                            </div>
                        @endif
                    </div>
                    @endif

                    <!-- Contact d'urgence -->
                    @if($user->contact_urgence_nom)
                    <div class="mt-6 pt-6 border-t border-slate-700">
                        <div class="border-2 border-red-500 rounded-lg p-4 bg-red-900/20">
                            <div class="text-sm font-medium text-red-400 uppercase tracking-wider mb-2">🚨 Contact d'urgence</div>
                            <div class="text-white font-medium">{{ $user->contact_urgence_nom }}</div>
                            @if($user->contact_urgence_telephone)
                                <div class="text-red-300">{{ $user->contact_urgence_telephone }}</div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($user->notes)
                    <div class="mt-6 pt-6 border-t border-slate-700">
                        <div class="bg-blue-900/20 rounded-lg p-4">
                            <h4 class="font-medium text-blue-300 mb-2">📝 Notes</h4>
                            <p class="text-blue-200">{{ $user->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne droite - École et Progression -->
        <div class="space-y-6">
            <!-- École -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
                <div class="bg-green-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3z"/>
                        </svg>
                        École
                    </h3>
                </div>
                <div class="p-6 text-center">
                    @if($user->ecole)
                        <div class="w-16 h-16 bg-green-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <span class="text-white font-bold">
                                {{ $user->ecole->code ?? strtoupper(substr($user->ecole->nom, 0, 2)) }}
                            </span>
                        </div>
                        <h4 class="text-white font-medium">{{ $user->ecole->nom }}</h4>
                        <p class="text-slate-400">{{ $user->ecole->ville ?? 'Localisation non définie' }}</p>
                        <a href="{{ route('admin.ecoles.show', $user->ecole) }}" 
                           class="text-green-400 hover:text-green-300 mt-2 inline-block text-sm">
                            Voir détails de l'école →
                        </a>
                    @else
                        <div class="w-16 h-16 bg-slate-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <span class="text-slate-400">❓</span>
                        </div>
                        <p class="text-slate-400">Aucune école assignée</p>
                    @endif
                </div>
            </div>

            <!-- Progression Ceintures -->
            @if($user->ceintureActuelle())
                @php $ceinture_actuelle = $user->ceintureActuelle(); @endphp
                <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
                    <div class="bg-orange-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z"/>
                            </svg>
                            Progression Ceintures
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="bg-gradient-to-r from-orange-500 to-red-500 rounded-lg p-4 text-center">
                            <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center mx-auto mb-3">
                                <span class="text-orange-600 font-bold">🥋</span>
                            </div>
                            <h4 class="text-white font-bold">{{ $ceinture_actuelle->ceinture->nom }}</h4>
                            <p class="text-orange-100 text-sm">Niveau {{ $ceinture_actuelle->ceinture->ordre }}/21</p>
                            <p class="text-orange-200 text-xs">
                                Obtenue le {{ $ceinture_actuelle->date_obtention->format('d/m/Y') }}
                            </p>
                        </div>

                        <div class="mt-4">
                            <div class="flex justify-between text-xs text-slate-400 mb-2">
                                <span>Progression</span>
                                <span>{{ round(($ceinture_actuelle->ceinture->ordre / 21) * 100) }}%</span>
                            </div>
                            <div class="w-full bg-slate-700 rounded-full h-2">
                                <div class="bg-orange-500 h-2 rounded-full" style="width: {{ round(($ceinture_actuelle->ceinture->ordre / 21) * 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
                    <div class="bg-slate-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z"/>
                            </svg>
                            Progression Ceintures
                        </h3>
                    </div>
                    <div class="p-6 text-center">
                        <div class="w-16 h-16 bg-slate-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <span class="text-slate-400">🥋</span>
                        </div>
                        <p class="text-slate-400">Aucune ceinture attribuée</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Actions rapides - CARTES PLUS PETITES -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('admin.users.edit', $user) }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-lg text-center transition-colors block">
            <div class="text-2xl mb-2">✏️</div>
            <h4 class="font-medium text-sm">Modifier Profil</h4>
            <p class="text-blue-200 text-xs mt-1">Informations personnelles</p>
        </a>

        <a href="#" 
           class="bg-orange-600 hover:bg-orange-700 text-white p-4 rounded-lg text-center transition-colors block">
            <div class="text-2xl mb-2">🥋</div>
            <h4 class="font-medium text-sm">Attribuer Ceinture</h4>
            <p class="text-orange-200 text-xs mt-1">Gestion des grades</p>
        </a>

        <div class="bg-red-600 hover:bg-red-700 text-white p-4 rounded-lg text-center transition-colors cursor-pointer">
            <div class="text-2xl mb-2">🎯</div>
            <h4 class="font-medium text-sm">Ajouter Séminaire</h4>
            <p class="text-red-200 text-xs mt-1">Inscrire à un séminaire</p>
        </div>

        <div class="bg-slate-600 hover:bg-slate-700 text-white p-4 rounded-lg text-center transition-colors cursor-pointer">
            <div class="text-2xl mb-2">📊</div>
            <h4 class="font-medium text-sm">Présences</h4>
            <p class="text-slate-300 text-xs mt-1">{{ $user->presences->count() ?? 0 }} présences</p>
        </div>
    </div>
</div>
@endsection
