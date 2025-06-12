@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h1 class="text-3xl font-bold text-white">
                    🏢 Dashboard StudiosUnisDB
                </h1>
                <p class="mt-1 text-sm text-gray-300">
                    Vue d'ensemble du réseau Studios Unis - {{ now()->format('d/m/Y à H:i') }}
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                @can('create', App\Models\Ecole::class)
                <a href="{{ route('admin.ecoles.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                    ➕ Nouvelle École
                </a>
                @endcan
            </div>
        </div>

        <!-- Métriques principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Écoles -->
            <div class="bg-gray-800 rounded-lg shadow border border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="text-2xl">🏢</div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-300">Total Écoles</div>
                        <div class="text-2xl font-bold text-white">{{ $stats['total_ecoles'] ?? 0 }}</div>
                    </div>
                </div>
            </div>

            <!-- Total Membres -->
            <div class="bg-gray-800 rounded-lg shadow border border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="text-2xl">👥</div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-300">Total Membres</div>
                        <div class="text-2xl font-bold text-white">{{ $stats['total_membres'] ?? 0 }}</div>
                    </div>
                </div>
            </div>

            <!-- Cours Actifs -->
            <div class="bg-gray-800 rounded-lg shadow border border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="text-2xl">📚</div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-300">Cours Actifs</div>
                        <div class="text-2xl font-bold text-white">{{ $stats['total_cours'] ?? 0 }}</div>
                    </div>
                </div>
            </div>

            <!-- Présences Mois -->
            <div class="bg-gray-800 rounded-lg shadow border border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="text-2xl">✅</div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-300">Présences Mois</div>
                        <div class="text-2xl font-bold text-white">{{ $stats['presences_mois'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Top 5 Écoles -->
            <div class="bg-gray-800 rounded-lg shadow border border-gray-700">
                <div class="px-6 py-4 border-b border-gray-700">
                    <h3 class="text-lg font-medium text-white">🏆 Top 5 Écoles</h3>
                    <p class="text-sm text-gray-400">Classement par nombre de membres</p>
                </div>
                <div class="p-6">
                    @forelse($top_ecoles as $index => $ecole)
                    <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-600' : '' }}">
                        <div class="flex items-center">
                            <span class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium">
                                {{ $index + 1 }}
                            </span>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-white">{{ $ecole->nom }}</p>
                                <p class="text-sm text-gray-400">{{ $ecole->ville }}</p>
                            </div>
                        </div>
                        <div class="text-sm font-medium text-white">
                            {{ $ecole->membres_count }} membres
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-400 text-center py-4">Aucune école trouvée</p>
                    @endforelse
                </div>
            </div>

            <!-- Activité Récente -->
            <div class="bg-gray-800 rounded-lg shadow border border-gray-700">
                <div class="px-6 py-4 border-b border-gray-700">
                    <h3 class="text-lg font-medium text-white">📝 Activité Récente</h3>
                    <p class="text-sm text-gray-400">Dernières inscriptions et activités</p>
                </div>
                <div class="p-6">
                    @forelse($activite_recente as $activite)
                    <div class="flex items-center py-3 {{ !$loop->last ? 'border-b border-gray-600' : '' }}">
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
                            <p class="text-sm font-medium text-white">{{ $activite['titre'] }}</p>
                            <p class="text-sm text-gray-400">{{ $activite['description'] }}</p>
                        </div>
                        <div class="text-sm text-gray-400">
                            {{ $activite['date'] }}
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-400 text-center py-4">Aucune activité récente</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Actions Rapides -->
        <div class="mt-8">
            <div class="bg-gray-800 rounded-lg shadow border border-gray-700">
                <div class="px-6 py-4 border-b border-gray-700">
                    <h3 class="text-lg font-medium text-white">⚡ Actions Rapides</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @can('create', App\Models\Ecole::class)
                        <a href="{{ route('admin.ecoles.create') }}" class="flex flex-col items-center p-4 border border-gray-600 rounded-lg hover:bg-gray-700 transition-colors">
                            <div class="text-2xl mb-2">🏢</div>
                            <div class="text-sm font-medium text-white">Nouvelle École</div>
                        </a>
                        @endcan
                        
                        @can('create', App\Models\Membre::class)
                        <a href="{{ route('admin.membres.create') }}" class="flex flex-col items-center p-4 border border-gray-600 rounded-lg hover:bg-gray-700 transition-colors">
                            <div class="text-2xl mb-2">👤</div>
                            <div class="text-sm font-medium text-white">Nouveau Membre</div>
                        </a>
                        @endcan
                        
                        <div class="flex flex-col items-center p-4 border border-gray-600 rounded-lg opacity-50 cursor-not-allowed">
                            <div class="text-2xl mb-2">📚</div>
                            <div class="text-sm font-medium text-gray-400">Nouveau Cours (bientôt)</div>
                        </div>
                        
                        <a href="{{ route('admin.membres.index') }}" class="flex flex-col items-center p-4 border border-gray-600 rounded-lg hover:bg-gray-700 transition-colors">
                            <div class="text-2xl mb-2">✅</div>
                            <div class="text-sm font-medium text-white">Voir Membres</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
