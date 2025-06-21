@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    {{-- Header avec gradient moderne --}}
    <div class="relative bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 rounded-xl p-6 text-white overflow-hidden shadow-2xl border border-gray-700">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white opacity-5 rounded-full -ml-16 -mb-16"></div>
        <div class="relative flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold mb-2">🎯 Dashboard StudiosUnisDB</h1>
                <p class="text-blue-100 text-lg">
                    @if(auth()->user()->hasRole('superadmin'))
                        Vue d'ensemble du réseau Studios Unis - 22 écoles
                    @elseif(auth()->user()->ecole)
                        École {{ auth()->user()->ecole->nom }}
                    @else
                        Système de gestion karaté v3.9.3-DEV-FINAL
                    @endif
                </p>
            </div>
            <div class="flex space-x-3">
                @if(auth()->user()->hasPermissionTo('create-membre'))
                <a href="{{ route('admin.membres.create') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-4 py-2 rounded-lg font-medium transition-all">
                    ➕ Nouveau membre
                </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Métriques principales --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Membres actifs --}}
        <div class="card-modern p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium">Membres actifs</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['total'] ?? 0 }}</p>
                    <p class="text-green-400 text-sm">
                        +{{ $stats['nouveaux'] ?? 0 }} ce mois
                    </p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">👥</span>
                </div>
            </div>
        </div>

        {{-- Cours programmés --}}
        <div class="card-modern p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium">Cours actifs</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['total_cours'] ?? 0 }}</p>
                    <p class="text-blue-400 text-sm">Cette semaine</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">📚</span>
                </div>
            </div>
        </div>

        {{-- Présences du jour --}}
        <div class="card-modern p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium">Présences aujourd'hui</p>
                    <p class="text-3xl font-bold text-white">0</p>
                    <p class="text-yellow-400 text-sm">{{ now()->format('d/m/Y') }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">✅</span>
                </div>
            </div>
        </div>

        {{-- Revenus mensuels --}}
        @if(auth()->user()->hasPermissionTo('view-paiements'))
        <div class="card-modern p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium">Revenus ce mois</p>
                    <p class="text-3xl font-bold text-white">0$</p>
                    <p class="text-green-400 text-sm">CAD</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-pink-600 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">💰</span>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Navigation rapide vers les modules --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Navigation modules --}}
        <div class="card-modern">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-4 rounded-t-xl">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <span class="text-2xl mr-3">🚀</span>
                    Modules disponibles
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Écoles (SuperAdmin seulement) --}}
                    @if(auth()->user()->hasRole('superadmin'))
                    <a href="{{ route('admin.ecoles.index') }}" 
                       class="flex items-center p-4 rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 transition-all transform hover:scale-105">
                        <span class="text-3xl mr-4">🏢</span>
                        <div>
                            <p class="font-semibold text-white">Écoles</p>
                            <p class="text-blue-200 text-sm">22 Studios Unis</p>
                        </div>
                    </a>
                    @endif

                    {{-- Membres - Utiliser hasPermissionTo au lieu de @can --}}
                    @if(auth()->user()->hasPermissionTo('view-membres'))
                    <a href="{{ route('admin.membres.index') }}" 
                       class="flex items-center p-4 rounded-lg bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 transition-all transform hover:scale-105">
                        <span class="text-3xl mr-4">👥</span>
                        <div>
                            <p class="font-semibold text-white">Membres</p>
                            <p class="text-green-200 text-sm">Gestion des karatékas</p>
                        </div>
                    </a>
                    @endif

                    {{-- Cours --}}
                    @if(auth()->user()->hasPermissionTo('view-cours'))
                    <a href="{{ route('admin.cours.index') }}" 
                       class="flex items-center p-4 rounded-lg bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 transition-all transform hover:scale-105">
                        <span class="text-3xl mr-4">📚</span>
                        <div>
                            <p class="font-semibold text-white">Cours</p>
                            <p class="text-purple-200 text-sm">Planning et horaires</p>
                        </div>
                    </a>
                    @endif

                    {{-- Présences --}}
                    @if(auth()->user()->hasPermissionTo('view-presences'))
                    <a href="{{ route('admin.presences.index') }}" 
                       class="flex items-center p-4 rounded-lg bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-700 hover:to-orange-700 transition-all transform hover:scale-105">
                        <span class="text-3xl mr-4">✅</span>
                        <div>
                            <p class="font-semibold text-white">Présences</p>
                            <p class="text-yellow-200 text-sm">Scan QR & suivi</p>
                        </div>
                    </a>
                    @endif

                    {{-- Ceintures --}}
                    @if(auth()->user()->hasPermissionTo('view-ceintures'))
                    <a href="{{ route('admin.ceintures.index') }}" 
                       class="flex items-center p-4 rounded-lg bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 transition-all transform hover:scale-105">
                        <span class="text-3xl mr-4">🥋</span>
                        <div>
                            <p class="font-semibold text-white">Ceintures</p>
                            <p class="text-red-200 text-sm">21 niveaux progression</p>
                        </div>
                    </a>
                    @endif

                    {{-- Séminaires --}}
                    @if(auth()->user()->hasPermissionTo('view-seminaires'))
                    <a href="{{ route('admin.seminaires.index') }}" 
                       class="flex items-center p-4 rounded-lg bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 transition-all transform hover:scale-105">
                        <span class="text-3xl mr-4">🎓</span>
                        <div>
                            <p class="font-semibold text-white">Séminaires</p>
                            <p class="text-indigo-200 text-sm">Événements & formations</p>
                        </div>
                    </a>
                    @endif

                    {{-- Paiements --}}
                    @if(auth()->user()->hasPermissionTo('view-paiements'))
                    <a href="{{ route('admin.paiements.index') }}" 
                       class="flex items-center p-4 rounded-lg bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 transition-all transform hover:scale-105">
                        <span class="text-3xl mr-4">💳</span>
                        <div>
                            <p class="font-semibold text-white">Paiements</p>
                            <p class="text-emerald-200 text-sm">Facturation & reçus</p>
                        </div>
                    </a>
                    @endif

                    {{-- Placeholder pour utilisateurs sans permissions --}}
                    @if(!auth()->user()->hasAnyPermission(['view-membres', 'view-cours', 'view-presences', 'view-ceintures', 'view-seminaires', 'view-paiements']))
                    <div class="flex items-center p-4 rounded-lg bg-gradient-to-r from-gray-600 to-gray-700 opacity-50">
                        <span class="text-3xl mr-4">🔒</span>
                        <div>
                            <p class="font-semibold text-white">Accès limité</p>
                            <p class="text-gray-300 text-sm">Contactez l'administrateur</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Actions rapides --}}
        <div class="card-modern">
            <div class="bg-gradient-to-r from-green-600 to-blue-600 p-4 rounded-t-xl">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                    </svg>
                    Actions rapides
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 gap-4">
                    @if(auth()->user()->hasPermissionTo('create-membre'))
                    <a href="{{ route('admin.membres.create') }}" 
                       class="flex items-center p-4 rounded-lg bg-blue-600 hover:bg-blue-700 transition-colors">
                        <span class="text-2xl mr-4">👤</span>
                        <div>
                            <p class="font-semibold text-white">Nouveau membre</p>
                            <p class="text-blue-200 text-sm">Ajouter un karatéka</p>
                        </div>
                    </a>
                    @endif

                    @if(auth()->user()->hasPermissionTo('create-cours'))
                    <a href="{{ route('admin.cours.create') }}" 
                       class="flex items-center p-4 rounded-lg bg-purple-600 hover:bg-purple-700 transition-colors">
                        <span class="text-2xl mr-4">📚</span>
                        <div>
                            <p class="font-semibold text-white">Programmer un cours</p>
                            <p class="text-purple-200 text-sm">Créer un nouveau cours</p>
                        </div>
                    </a>
                    @endif

                    @if(auth()->user()->hasPermissionTo('create-presence'))
                    <a href="{{ route('admin.presences.create') }}" 
                       class="flex items-center p-4 rounded-lg bg-green-600 hover:bg-green-700 transition-colors">
                        <span class="text-2xl mr-4">📱</span>
                        <div>
                            <p class="font-semibold text-white">Prendre présences</p>
                            <p class="text-green-200 text-sm">Scanner QR Code</p>
                        </div>
                    </a>
                    @endif

                    @if(auth()->user()->hasPermissionTo('assign-ceintures'))
                    <a href="{{ route('admin.ceintures.create') }}" 
                       class="flex items-center p-4 rounded-lg bg-yellow-600 hover:bg-yellow-700 transition-colors">
                        <span class="text-2xl mr-4">🥋</span>
                        <div>
                            <p class="font-semibold text-white">Attribuer ceinture</p>
                            <p class="text-yellow-200 text-sm">Promotions et certificats</p>
                        </div>
                    </a>
                    @endif

                    @if(auth()->user()->hasPermissionTo('create-paiements'))
                    <a href="{{ route('admin.paiements.create') }}" 
                       class="flex items-center p-4 rounded-lg bg-emerald-600 hover:bg-emerald-700 transition-colors">
                        <span class="text-2xl mr-4">💳</span>
                        <div>
                            <p class="font-semibold text-white">Nouveau paiement</p>
                            <p class="text-emerald-200 text-sm">Facturer un membre</p>
                        </div>
                    </a>
                    @endif

                    {{-- Message si aucune permission de création --}}
                    @if(!auth()->user()->hasAnyPermission(['create-membre', 'create-cours', 'create-presence', 'assign-ceintures', 'create-paiements']))
                    <div class="flex items-center p-4 rounded-lg bg-gradient-to-r from-gray-600 to-gray-700 opacity-50">
                        <span class="text-2xl mr-4">👀</span>
                        <div>
                            <p class="font-semibold text-white">Mode consultation</p>
                            <p class="text-gray-300 text-sm">Accès en lecture seule</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Section SuperAdmin uniquement --}}
    @if(auth()->user()->hasRole('superadmin'))
    <div class="card-modern">
        <div class="bg-gradient-to-r from-red-600 to-pink-600 p-4 rounded-t-xl">
            <h3 class="text-xl font-bold text-white flex items-center">
                <span class="text-2xl mr-3">🔥</span>
                Administration SuperAdmin
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">🏢</span>
                    </div>
                    <h4 class="text-lg font-semibold text-white mb-2">22 Écoles</h4>
                    <p class="text-gray-400">Réseau Studios Unis</p>
                    <a href="{{ route('admin.ecoles.index') }}" class="inline-block mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Gérer
                    </a>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">👥</span>
                    </div>
                    <h4 class="text-lg font-semibold text-white mb-2">{{ $stats['total'] ?? 0 }} Utilisateurs</h4>
                    <p class="text-gray-400">Tous rôles confondus</p>
                    <a href="{{ route('admin.membres.index') }}" class="inline-block mt-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Voir tous
                    </a>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">🔭</span>
                    </div>
                    <h4 class="text-lg font-semibold text-white mb-2">Telescope</h4>
                    <p class="text-gray-400">Monitoring & Debug</p>
                    <a href="{{ url('/telescope') }}" target="_blank" class="inline-block mt-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        Ouvrir
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Debug permissions (temporaire) --}}
    @if(config('app.debug'))
    <div class="card-modern p-4">
        <h4 class="text-white font-bold mb-2">🔧 Debug Permissions</h4>
        <p class="text-gray-400 text-sm">Rôle: {{ auth()->user()->roles->pluck('name')->join(', ') }}</p>
        <p class="text-gray-400 text-sm">Permissions: {{ auth()->user()->getAllPermissions()->pluck('name')->take(10)->join(', ') }}{{ auth()->user()->getAllPermissions()->count() > 10 ? '...' : '' }}</p>
    </div>
    @endif
</div>

<script>
// Actualisation automatique des métriques toutes les 30 secondes
setInterval(function() {
    fetch(window.location.href)
        .then(response => response.text())
        .then(html => {
            console.log('Métriques mises à jour');
        })
        .catch(error => console.log('Erreur actualisation:', error));
}, 30000);
</script>
@endsection
