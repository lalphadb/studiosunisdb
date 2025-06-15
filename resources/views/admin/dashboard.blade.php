@extends('layouts.admin')

@section('title', 'Dashboard StudiosUnisDB')

@section('content')
<div class="space-y-6">
    {{-- Header selon le rôle --}}
    @if($user_role === 'superadmin')
        {{-- Header SuperAdmin --}}
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">👑 Dashboard SuperAdmin</h1>
                    <p class="text-purple-100 text-lg">Vue globale - 22 Studios Unis du Québec</p>
                </div>
                <div class="text-right">
                    <p class="text-xl font-semibold">Bonjour {{ $user->name }}</p>
                    <p class="text-purple-200">Accès complet système</p>
                    <div class="mt-2">
                        <span class="bg-purple-500 bg-opacity-50 px-3 py-1 rounded-full text-sm font-medium">
                            🌟 SuperAdministrateur
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats SuperAdmin --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                            <span class="text-white text-xl">🏫</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Écoles</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_ecoles'] }}</p>
                        <p class="text-sm text-green-600">
                            <span class="font-medium">{{ $stats['ecoles_actives'] }}</span> actives
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                            <span class="text-white text-xl">👥</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Membres</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_membres'] }}</p>
                        <p class="text-sm text-green-600">
                            <span class="font-medium">{{ $stats['membres_actifs'] }}</span> actifs
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                            <span class="text-white text-xl">🥋</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Cours</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_cours'] }}</p>
                        <p class="text-sm text-green-600">
                            <span class="font-medium">{{ $stats['cours_actifs'] }}</span> actifs
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                            <span class="text-white text-xl">📊</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Taux Présence</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['taux_presence_global'] }}%</p>
                        <p class="text-sm text-gray-500">Ce mois</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistiques utilisateurs --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">👤 Utilisateurs par Rôle</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600">{{ $stats_utilisateurs['superadmins'] }}</div>
                    <div class="text-sm text-gray-500">SuperAdmins</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats_utilisateurs['admins'] }}</div>
                    <div class="text-sm text-gray-500">Admins École</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $stats_utilisateurs['instructeurs'] }}</div>
                    <div class="text-sm text-gray-500">Instructeurs</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-600">{{ $stats_utilisateurs['membres_users'] }}</div>
                    <div class="text-sm text-gray-500">Membres</div>
                </div>
            </div>
        </div>

        {{-- Top écoles et données --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Top 5 écoles --}}
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">🏆 Top 5 Écoles (Membres)</h3>
                </div>
                <div class="p-6">
                    @if($top_ecoles->count() > 0)
                        @foreach($top_ecoles as $index => $ecole)
                        <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $ecole->nom }}</p>
                                    <p class="text-sm text-gray-500">{{ $ecole->ville }}, {{ $ecole->province }}</p>
                                </div>
                            </div>
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                {{ $ecole->membres_count }} membres
                            </span>
                        </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 text-center py-4">Aucune donnée disponible</p>
                    @endif
                </div>
            </div>

            {{-- Revenus par école --}}
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">💰 Top Revenus (Estimation)</h3>
                </div>
                <div class="p-6">
                    @if($revenus_ecoles->count() > 0)
                        @foreach($revenus_ecoles as $index => $ecole)
                        <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $ecole['nom'] }}</p>
                                    <p class="text-sm text-gray-500">{{ $ecole['membres'] }} membres</p>
                                </div>
                            </div>
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                ${{ number_format($ecole['revenus']) }}
                            </span>
                        </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 text-center py-4">Aucune donnée disponible</p>
                    @endif
                </div>
            </div>
        </div>

    @elseif($user_role === 'admin')
        {{-- Header Admin École --}}
        <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">🏫 {{ $stats['ecole_nom'] ?? 'École' }}</h1>
                    <p class="text-green-100 text-lg">Administration de votre école</p>
                    @if(isset($stats['ecole_ville']))
                        <p class="text-green-200">📍 {{ $stats['ecole_ville'] }}</p>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-xl font-semibold">Bonjour {{ $user->name }}</p>
                    <p class="text-green-200">Administrateur d'école</p>
                    <div class="mt-2">
                        <span class="bg-green-500 bg-opacity-50 px-3 py-1 rounded-full text-sm font-medium">
                            🛡️ Admin École
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Alerte si pas d'école assignée --}}
        @if(isset($stats['error']))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <span class="text-red-600 text-xl mr-3">⚠️</span>
                    <div>
                        <p class="font-medium text-red-800">Erreur de configuration</p>
                        <p class="text-sm text-red-600">{{ $stats['error'] }}</p>
                    </div>
                </div>
            </div>
        @else
            {{-- Indicateur de limitation --}}
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                <div class="flex items-center">
                    <span class="text-amber-600 text-xl mr-3">⚡</span>
                    <div>
                        <p class="font-medium text-amber-800">Dashboard Admin École</p>
                        <p class="text-sm text-amber-600">
                            Vous gérez uniquement {{ $stats['ecole_nom'] }}
                            ({{ $stats['capacite_utilisee'] }}% de capacité utilisée - {{ $stats['total_membres'] }}/{{ $stats['capacite_max'] }} membres)
                        </p>
                    </div>
                </div>
            </div>

            {{-- Stats Admin École --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                                <span class="text-white text-xl">👥</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Membres</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['total_membres'] }}</p>
                            <p class="text-sm text-green-600">{{ $stats['membres_actifs'] }} actifs</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                                <span class="text-white text-xl">🥋</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Cours</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['total_cours'] }}</p>
                            <p class="text-sm text-green-600">{{ $stats['cours_actifs'] }} actifs</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                                <span class="text-white text-xl">📊</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Taux Présence</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['taux_presence'] }}%</p>
                            <p class="text-sm text-gray-500">Ce mois</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                                <span class="text-white text-xl">💰</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Revenus Mois</p>
                            <p class="text-3xl font-bold text-gray-900">${{ number_format($stats['revenus_mois']) }}</p>
                            <p class="text-sm text-gray-500">Estimation</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Données spécifiques à l'école --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Membres récents --}}
                @if(isset($membres_recents) && $membres_recents->count() > 0)
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">👤 Membres Récents</h3>
                    </div>
                    <div class="p-6">
                        @foreach($membres_recents as $membre)
                        <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                            <div>
                                <p class="font-medium text-gray-900">{{ $membre->prenom }} {{ $membre->nom }}</p>
                                <p class="text-sm text-gray-500">
                                    Inscrit le {{ $membre->date_inscription ? \Carbon\Carbon::parse($membre->date_inscription)->format('d/m/Y') : 'N/A' }}
                                </p>
                            </div>
                            <span class="bg-{{ $membre->statut === 'actif' ? 'green' : 'gray' }}-100 text-{{ $membre->statut === 'actif' ? 'green' : 'gray' }}-800 px-2 py-1 rounded-full text-xs font-medium">
                                {{ ucfirst($membre->statut) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Cours populaires --}}
                @if(isset($cours_populaires) && $cours_populaires->count() > 0)
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">🥋 Cours Populaires</h3>
                    </div>
                    <div class="p-6">
                        @foreach($cours_populaires as $cours)
                        <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                            <div>
                                <p class="font-medium text-gray-900">{{ $cours->nom }}</p>
                                <p class="text-sm text-gray-500">Capacité: {{ $cours->capacite_max }} places</p>
                            </div>
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                {{ $cours->inscriptions_count }} inscrits
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        @endif

    @elseif($user_role === 'instructeur')
        {{-- Header Instructeur --}}
        <div class="bg-gradient-to-r from-orange-600 to-red-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">🥋 Dashboard Instructeur</h1>
                    <p class="text-orange-100 text-lg">Gestion de vos cours et élèves</p>
                    <p class="text-orange-200">📍 {{ $stats['ecole_nom'] }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xl font-semibold">Bonjour {{ $user->name }}</p>
                    <p class="text-orange-200">Instructeur de karaté</p>
                    <div class="mt-2">
                        <span class="bg-orange-500 bg-opacity-50 px-3 py-1 rounded-full text-sm font-medium">
                            🎯 Instructeur
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Instructeur --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center">
                            <span class="text-white text-xl">🥋</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Mes Cours</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['mes_cours'] }}</p>
                        <p class="text-sm text-green-600">{{ $stats['cours_actifs'] }} actifs</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                            <span class="text-white text-xl">👥</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Mes Élèves</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_eleves'] }}</p>
                        <p class="text-sm text-gray-500">Total</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                            <span class="text-white text-xl">✅</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Présents Aujourd'hui</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['presences_aujourd_hui'] }}</p>
                        <p class="text-sm text-gray-500">Élèves</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                            <span class="text-white text-xl">📊</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Présences Semaine</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['presences_semaine'] }}</p>
                        <p class="text-sm text-gray-500">Total</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mes cours avec détails --}}
        @if(isset($mes_cours_stats) && $mes_cours_stats->count() > 0)
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">📚 Détails de Mes Cours</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($mes_cours_stats as $cours)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-medium text-gray-900">{{ $cours['nom'] }}</h4>
                            <span class="bg-{{ $cours['statut'] === 'actif' ? 'green' : 'gray' }}-100 text-{{ $cours['statut'] === 'actif' ? 'green' : 'gray' }}-800 px-2 py-1 rounded-full text-xs font-medium">
                                {{ ucfirst($cours['statut']) }}
                            </span>
                        </div>
                        <div class="space-y-1 text-sm text-gray-600">
                            <p>👥 {{ $cours['inscriptions'] }}/{{ $cours['capacite_max'] }} élèves</p>
                            <p>📊 {{ $cours['taux_remplissage'] }}% de remplissage</p>
                        </div>
                        <div class="mt-2">
                            <div class="bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $cours['taux_remplissage'] }}%"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

    @else
        {{-- Header Membre --}}
        <div class="bg-gradient-to-r from-gray-600 to-gray-800 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">👤 Mon Espace Membre</h1>
                    <p class="text-gray-100 text-lg">Tableau de bord personnel</p>
                    <p class="text-gray-200">📍 {{ $stats['ecole_nom'] }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xl font-semibold">Bonjour {{ $user->name }}</p>
                    <p class="text-gray-200">Membre karatéka</p>
                    <div class="mt-2">
                        <span class="bg-gray-500 bg-opacity-50 px-3 py-1 rounded-full text-sm font-medium">
                            🥋 Membre
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Contenu membre basique --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-center py-8">
                <div class="text-6xl mb-4">🥋</div>
                <h3 class="text-xl font-medium text-gray-900 mb-2">Bienvenue dans votre espace membre</h3>
                <p class="text-gray-600">
                    Ici vous pourrez consulter vos informations personnelles et suivre votre progression.
                </p>
                <div class="mt-6 space-y-2">
                    <p class="text-sm text-gray-500">📧 {{ $stats['email'] }}</p>
                    <p class="text-sm text-gray-500">🏫 {{ $stats['ecole_nom'] }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Activité récente (commune à tous) --}}
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">📈 Activité Récente</h3>
        </div>
        <div class="p-6">
            @if(count($activite_recente) > 0)
                <div class="space-y-4">
                    @foreach($activite_recente as $activite)
                    <div class="flex items-start space-x-4 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 {{ $activite['color'] }} rounded-lg flex items-center justify-center">
                                <span class="text-lg">{{ $activite['icon'] }}</span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900">{{ $activite['titre'] }}</p>
                            <p class="text-sm text-gray-600">{{ $activite['description'] }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $activite['date'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Aucune activité récente</p>
            @endif
        </div>
    </div>

    {{-- Actions rapides selon le rôle --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">⚡ Actions Rapides</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @if($user_role === 'superadmin')
                <a href="{{ route('admin.ecoles.index') }}" class="flex flex-col items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                    <span class="text-2xl mb-2">🏫</span>
                    <span class="text-sm font-medium text-blue-800">Gérer Écoles</span>
                </a>
                <a href="{{ route('admin.membres.index') }}" class="flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                    <span class="text-2xl mb-2">👥</span>
                    <span class="text-sm font-medium text-green-800">Tous Membres</span>
                </a>
                <a href="{{ route('admin.cours.index') }}" class="flex flex-col items-center p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition-colors">
                    <span class="text-2xl mb-2">🥋</span>
                    <span class="text-sm font-medium text-yellow-800">Tous Cours</span>
                </a>
                <a href="{{ route('admin.presences.index') }}" class="flex flex-col items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                <a href="{{ route('admin.ceintures.index') }}" class="flex flex-col items-center p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition-colors">
                    <span class="text-2xl mb-2">🥋</span>
                    <span class="text-sm font-medium text-yellow-800">Ceintures</span>
                </a>
                    <span class="text-2xl mb-2">📊</span>
                    <span class="text-sm font-medium text-purple-800">Statistiques</span>
                </a>
            @elseif($user_role === 'admin')
                <a href="{{ route('admin.membres.index') }}" class="flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                    <span class="text-2xl mb-2">👥</span>
                    <span class="text-sm font-medium text-green-800">Mes Membres</span>
                </a>
                <a href="{{ route('admin.cours.index') }}" class="flex flex-col items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                    <span class="text-2xl mb-2">🥋</span>
                    <span class="text-sm font-medium text-blue-800">Mes Cours</span>
                </a>
                <a href="{{ route('admin.presences.index') }}" class="flex flex-col items-center p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition-colors">
                <a href="{{ route('admin.ceintures.index') }}" class="flex flex-col items-center p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition-colors">
                    <span class="text-2xl mb-2">🥋</span>
                    <span class="text-sm font-medium text-yellow-800">Ceintures</span>
                </a>
                    <span class="text-2xl mb-2">✅</span>
                    <span class="text-sm font-medium text-yellow-800">Présences</span>
                </a>
                <a href="{{ route('admin.membres.create') }}" class="flex flex-col items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                    <span class="text-2xl mb-2">➕</span>
                    <span class="text-sm font-medium text-purple-800">Nouveau Membre</span>
                </a>
            @elseif($user_role === 'instructeur')
                <a href="{{ route('admin.cours.index') }}" class="flex flex-col items-center p-4 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors">
                    <span class="text-2xl mb-2">🥋</span>
                    <span class="text-sm font-medium text-orange-800">Mes Cours</span>
                </a>
                <a href="{{ route('admin.presences.index') }}" class="flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                <a href="{{ route('admin.ceintures.index') }}" class="flex flex-col items-center p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition-colors">
                    <span class="text-2xl mb-2">🥋</span>
                    <span class="text-sm font-medium text-yellow-800">Ceintures</span>
                </a>
                    <span class="text-2xl mb-2">✅</span>
                    <span class="text-sm font-medium text-green-800">Présences</span>
                </a>
                <a href="{{ route('admin.membres.index') }}" class="flex flex-col items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                    <span class="text-2xl mb-2">👥</span>
                    <span class="text-sm font-medium text-blue-800">Mes Élèves</span>
                </a>
                <div class="flex flex-col items-center p-4 bg-gray-50 rounded-lg opacity-50">
                    <span class="text-2xl mb-2">📈</span>
                    <span class="text-sm font-medium text-gray-600">Statistiques</span>
                </div>
            @else
                <div class="flex flex-col items-center p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                    <span class="text-2xl mb-2">👤</span>
                    <span class="text-sm font-medium text-gray-800">Mon Profil</span>
                </div>
                <div class="flex flex-col items-center p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                    <span class="text-2xl mb-2">🥋</span>
                    <span class="text-sm font-medium text-gray-800">Mes Cours</span>
                </div>
                <div class="flex flex-col items-center p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                    <span class="text-2xl mb-2">📊</span>
                    <span class="text-sm font-medium text-gray-800">Progression</span>
                </div>
                <div class="flex flex-col items-center p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                    <span class="text-2xl mb-2">💳</span>
                    <span class="text-sm font-medium text-gray-800">Paiements</span>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
