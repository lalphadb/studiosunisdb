@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h1 class="text-3xl font-bold text-gray-900">
                    🏢 Dashboard StudiosUnisDB
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Vue d'ensemble du réseau Studios Unis - {{ now()->format('d/m/Y à H:i') }}
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('admin.ecoles.create') }}" class="btn-primary">
                    ➕ Nouvelle École
                </a>
            </div>
        </div>

        <!-- Métriques principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Écoles -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="text-2xl">🏢</div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-500">Total Écoles</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['total_ecoles'] ?? 0 }}</div>
                    </div>
                </div>
            </div>

            <!-- Total Membres -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="text-2xl">👥</div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-500">Total Membres</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['total_membres'] ?? 0 }}</div>
                    </div>
                </div>
            </div>

            <!-- Cours Actifs -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="text-2xl">📚</div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-500">Cours Actifs</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['total_cours'] ?? 0 }}</div>
                    </div>
                </div>
            </div>

            <!-- Présences Mois -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="text-2xl">✅</div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-500">Présences Mois</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['presences_mois'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Top 5 Écoles -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">🏆 Top 5 Écoles</h3>
                    <p class="text-sm text-gray-500">Classement par nombre de membres</p>
                </div>
                <div class="p-6">
                    @forelse($top_ecoles as $index => $ecole)
                    <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                        <div class="flex items-center">
                            <span class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-800 rounded-full flex items-center justify-center text-sm font-medium">
                                {{ $index + 1 }}
                            </span>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">{{ $ecole->nom }}</p>
                                <p class="text-sm text-gray-500">{{ $ecole->ville }}</p>
                            </div>
                        </div>
                        <div class="text-sm font-medium text-gray-900">
                            {{ $ecole->membres_count }} membres
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">Aucune école trouvée</p>
                    @endforelse
                </div>
            </div>

            <!-- Activité Récente -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">📝 Activité Récente</h3>
                    <p class="text-sm text-gray-500">Dernières inscriptions et activités</p>
                </div>
                <div class="p-6">
                    @forelse($activite_recente as $activite)
                    <div class="flex items-center py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                        <div class="flex-shrink-0">
                            <span class="text-xl">
                                @if($activite['type'] == 'membre')
                                    👤
                                @elseif($activite['type'] == 'cours')
                                    📚
                                @else
                                    ✨
                                @endif
                            </span>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $activite['titre'] }}</p>
                            <p class="text-sm text-gray-500">{{ $activite['description'] }}</p>
                        </div>
                        <div class="text-sm text-gray-400">
                            {{ $activite['date'] }}
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">Aucune activité récente</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Actions Rapides -->
        <div class="mt-8">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">⚡ Actions Rapides</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('admin.ecoles.create') }}" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="text-2xl mb-2">🏢</div>
                            <div class="text-sm font-medium text-gray-900">Nouvelle École</div>
                        </a>
                        <a href="{{ route('admin.membres.create') }}" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="text-2xl mb-2">👤</div>
                            <div class="text-sm font-medium text-gray-900">Nouveau Membre</div>
                        </a>
                        <a href="{{ route('admin.cours.create') }}" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="text-2xl mb-2">📚</div>
                            <div class="text-sm font-medium text-gray-900">Nouveau Cours</div>
                        </a>
                        <a href="{{ route('admin.presences.index') }}" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="text-2xl mb-2">✅</div>
                            <div class="text-sm font-medium text-gray-900">Prendre Présences</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
