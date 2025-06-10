@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">
    <!-- En-tête Dashboard -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">🏢 Dashboard StudiosUnisDB</h1>
        <p class="text-gray-400 mt-2">Aperçu général du réseau Studios Unis</p>
    </div>

    <!-- Métriques principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Écoles -->
        <div class="card-bg p-6 rounded-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-full text-3xl mr-4" style="background-color: rgba(59, 130, 246, 0.2);">
                    🏢
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-400">Total Écoles</p>
                    <p class="text-2xl font-bold text-white">{{ $stats['total_ecoles'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Total Membres -->
        <div class="card-bg p-6 rounded-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-full text-3xl mr-4" style="background-color: rgba(34, 197, 94, 0.2);">
                    👥
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-400">Total Membres</p>
                    <p class="text-2xl font-bold text-white">{{ $stats['total_membres'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Membres Actifs -->
        <div class="card-bg p-6 rounded-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-full text-3xl mr-4" style="background-color: rgba(16, 185, 129, 0.2);">
                    ✅
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-400">Membres Actifs</p>
                    <p class="text-2xl font-bold text-white">{{ $stats['membres_actifs'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Total Cours -->
        <div class="card-bg p-6 rounded-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-full text-3xl mr-4" style="background-color: rgba(245, 158, 11, 0.2);">
                    📚
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-400">Total Cours</p>
                    <p class="text-2xl font-bold text-white">{{ $stats['total_cours'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Écoles et Membres Récents -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top 5 Écoles -->
        <div class="card-bg p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-white mb-4">🏆 Top 5 Écoles</h3>
            <div class="space-y-3">
                @forelse($topEcoles ?? [] as $ecole)
                    <div class="flex items-center justify-between py-2 border-b border-gray-600 last:border-b-0">
                        <div>
                            <p class="font-medium text-white">{{ $ecole->nom }}</p>
                            <p class="text-sm text-gray-400">{{ $ecole->ville }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-1 rounded-full text-sm font-medium" 
                                  style="background-color: rgba(59, 130, 246, 0.2); color: #60a5fa;">
                                {{ $ecole->membres_count }} membres
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400 text-center py-4">Aucune école trouvée</p>
                @endforelse
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.ecoles.index') }}" 
                   class="text-blue-400 hover:text-blue-300 text-sm font-medium">
                    Voir toutes les écoles →
                </a>
            </div>
        </div>

        <!-- Membres Récents -->
        <div class="card-bg p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-white mb-4">🆕 Derniers Membres</h3>
            <div class="space-y-3">
                @forelse($recentMembres ?? [] as $membre)
                    <div class="flex items-center justify-between py-2 border-b border-gray-600 last:border-b-0">
                        <div>
                            <p class="font-medium text-white">{{ $membre->prenom }} {{ $membre->nom }}</p>
                            <p class="text-sm text-gray-400">{{ $membre->ecole->nom ?? 'École inconnue' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-400">{{ $membre->created_at->diffForHumans() }}</p>
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $membre->statut === 'actif' ? 'bg-green-600 text-green-100' : 'bg-gray-600 text-gray-300' }}">
                                {{ ucfirst($membre->statut) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400 text-center py-4">Aucun membre récent</p>
                @endforelse
            </div>
            <div class="mt-4">
                <span class="text-gray-500 text-sm">
                    Gestion membres (bientôt disponible)
                </span>
            </div>
        </div>
    </div>

    <!-- Actions Rapides -->
    <div class="card-bg p-6 rounded-lg">
        <h3 class="text-lg font-semibold text-white mb-4">⚡ Actions Rapides</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.ecoles.index') }}" 
               class="flex items-center p-4 rounded-lg hover-bg transition-all border border-gray-600">
                <span class="text-2xl mr-3">🏢</span>
                <div>
                    <p class="font-medium text-white">Gérer Écoles</p>
                    <p class="text-sm text-gray-400">{{ $stats['total_ecoles'] ?? 0 }} écoles</p>
                </div>
            </a>
            
            <a href="{{ route('admin.ecoles.create') }}" 
               class="flex items-center p-4 rounded-lg hover-bg transition-all border border-gray-600">
                <span class="text-2xl mr-3">➕</span>
                <div>
                    <p class="font-medium text-white">Nouvelle École</p>
                    <p class="text-sm text-gray-400">Ajouter école</p>
                </div>
            </a>
            
            <div class="flex items-center p-4 rounded-lg border border-gray-600 opacity-50 cursor-not-allowed">
                <span class="text-2xl mr-3">👥</span>
                <div>
                    <p class="font-medium text-gray-400">Membres</p>
                    <p class="text-sm text-gray-500">Bientôt</p>
                </div>
            </div>
            
            <div class="flex items-center p-4 rounded-lg border border-gray-600 opacity-50 cursor-not-allowed">
                <span class="text-2xl mr-3">📚</span>
                <div>
                    <p class="font-medium text-gray-400">Cours</p>
                    <p class="text-sm text-gray-500">Bientôt</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
