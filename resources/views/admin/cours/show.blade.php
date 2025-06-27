@extends('layouts.admin')
@section('title', 'Détails du cours')

@section('content')
<div class="space-y-6">
    <!-- Header avec couleur purple pour cours -->
    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-6">
                <!-- Icône cours -->
                <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center">
                    <span class="text-2xl font-bold text-white">📚</span>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">{{ $cours->nom }}</h1>
                    <p class="text-purple-100 text-lg">{{ $cours->ecole->nom }}</p>
                    <!-- Badges -->
                    <div class="flex space-x-2 mt-3">
                        @if($cours->niveau)
                            <span class="px-3 py-1 text-sm font-medium rounded-md bg-purple-800 text-purple-100">
                                {{ ucfirst($cours->niveau) }}
                            </span>
                        @endif
                        @if($cours->active)
                            <span class="px-3 py-1 text-sm font-medium rounded-md bg-green-600 text-green-100">
                                Actif
                            </span>
                        @else
                            <span class="px-3 py-1 text-sm font-medium rounded-md bg-red-600 text-red-100">
                                Inactif
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Boutons d'action -->
            <div class="flex space-x-3">
                @can('update', $cours)
                <a href="{{ route('admin.cours.edit', $cours->id) }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Modifier
                </a>
                @endcan
                <a href="{{ route('admin.cours.index') }}" 
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
            <!-- Informations du Cours -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
                <div class="bg-purple-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Informations du Cours
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Colonne 1 -->
                        <div class="space-y-4">
                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Nom du cours</div>
                                <div class="text-white font-medium">{{ $cours->nom }}</div>
                            </div>

                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">École</div>
                                <div class="text-white">{{ $cours->ecole->nom }}</div>
                            </div>
                            
                            @if($cours->instructeur)
                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Instructeur</div>
                                <div class="text-white">{{ $cours->instructeur }}</div>
                            </div>
                            @endif

                            @if($cours->niveau)
                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Niveau</div>
                                <div class="text-white">{{ ucfirst($cours->niveau) }}</div>
                            </div>
                            @endif
                        </div>

                        <!-- Colonne 2 -->
                        <div class="space-y-4">
                            @if($cours->prix)
                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Prix</div>
                                <div class="text-white">{{ number_format($cours->prix, 2) }} $</div>
                            </div>
                            @endif

                            @if($cours->capacite_max)
                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Capacité maximale</div>
                                <div class="text-white">{{ $cours->capacite_max }} participants</div>
                            </div>
                            @endif

                            @if($cours->duree_minutes)
                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Durée</div>
                                <div class="text-white">{{ $cours->duree_minutes }} minutes</div>
                            </div>
                            @endif

                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Statut</div>
                                <div class="text-white">{{ $cours->active ? 'Actif' : 'Inactif' }}</div>
                            </div>
                        </div>
                    </div>

                    @if($cours->description)
                    <div class="mt-6 pt-6 border-t border-slate-700">
                        <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Description</div>
                        <div class="text-white">{{ $cours->description }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne droite - École et Statistiques -->
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
                    <div class="w-16 h-16 bg-green-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-white font-bold">
                            {{ $cours->ecole->code ?? strtoupper(substr($cours->ecole->nom, 0, 3)) }}
                        </span>
                    </div>
                    <h4 class="text-white font-medium">{{ $cours->ecole->nom }}</h4>
                    <p class="text-slate-400">{{ $cours->ecole->ville ?? 'Localisation non définie' }}</p>
                    @can('view', $cours->ecole)
                        <a href="{{ route('admin.ecoles.show', $cours->ecole) }}" 
                           class="text-green-400 hover:text-green-300 mt-2 inline-block text-sm">
                            Voir détails de l'école →
                        </a>
                    @endcan
                </div>
            </div>

            <!-- Statistiques -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
                <div class="bg-blue-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">📊 Statistiques</h3>
                </div>
                <div class="p-6 text-center">
                    <div class="text-slate-400">
                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <p class="text-sm mt-2">Inscriptions à venir</p>
                        <p class="text-xs text-slate-500">Fonctionnalité prochainement</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Modifier Cours -->
        @can('update', $cours)
        <a href="{{ route('admin.cours.edit', $cours->id) }}" 
           class="bg-purple-600 hover:bg-purple-700 text-white p-4 rounded-lg text-center transition-colors block">
            <div class="text-2xl mb-2">✏️</div>
            <h4 class="font-medium text-sm">Modifier Cours</h4>
            <p class="text-purple-200 text-xs mt-1">Informations du cours</p>
        </a>
        @endcan

        <!-- Dupliquer Cours -->
        @can('create', App\Models\Cours::class)
        <a href="{{ route('admin.cours.clone.form', $cours->id) }}" 
           class="bg-indigo-600 hover:bg-indigo-700 text-white p-4 rounded-lg text-center transition-colors block">
            <div class="text-2xl mb-2">📋</div>
            <h4 class="font-medium text-sm">Dupliquer</h4>
            <p class="text-indigo-200 text-xs mt-1">Créer plusieurs cours</p>
        </a>
        @endcan

        <!-- Voir École -->
        @can('view', $cours->ecole)
        <a href="{{ route('admin.ecoles.show', $cours->ecole) }}" 
           class="bg-green-600 hover:bg-green-700 text-white p-4 rounded-lg text-center transition-colors block">
            <div class="text-2xl mb-2">🏫</div>
            <h4 class="font-medium text-sm">Voir École</h4>
            <p class="text-green-200 text-xs mt-1">{{ $cours->ecole->nom }}</p>
        </a>
        @endcan

        <!-- Présences -->
        <a href="{{ route('admin.presences.index') }}" 
           class="bg-teal-600 hover:bg-teal-700 text-white p-4 rounded-lg text-center transition-colors block">
            <div class="text-2xl mb-2">✅</div>
            <h4 class="font-medium text-sm">Présences</h4>
            <p class="text-teal-200 text-xs mt-1">Suivre les présences</p>
        </a>
    </div>
</div>
@endsection
