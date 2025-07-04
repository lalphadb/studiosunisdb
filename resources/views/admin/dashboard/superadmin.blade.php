@extends('layouts.admin')

@section('title', 'Dashboard SuperAdmin')

@section('content')
<div class="space-y-6">
    <!-- Header SuperAdmin DISTINCTIF -->
    <div class="bg-gradient-to-r from-red-600 via-red-700 to-red-800 rounded-xl p-8 text-white shadow-2xl border-2 border-red-500/30 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-white/5 via-transparent to-white/10"></div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <div class="w-16 h-16 bg-red-500/30 rounded-2xl flex items-center justify-center backdrop-blur border border-red-400/50 shadow-lg">
                        <span class="text-3xl">🛡️</span>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white drop-shadow-sm">🛡️ SUPER ADMINISTRATEUR</h1>
                        <p class="text-lg text-red-100 font-medium">Gestion Système StudiosDB v4.1.10.2</p>
                        <p class="text-sm text-red-200">Accès global à toutes les écoles du réseau</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="bg-red-500/30 px-6 py-3 rounded-xl border border-red-400/50 backdrop-blur">
                        <div class="text-sm text-red-100 font-medium">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-red-200">Super Administrateur</div>
                        <div class="text-xs text-red-300 mt-1">{{ now()->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Système Global -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-slate-800/50 border border-red-500/20 rounded-xl p-6 hover:border-red-500/40 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Total Écoles</p>
                    <p class="text-3xl font-bold text-red-400">{{ $stats['total_ecoles'] }}</p>
                    <p class="text-xs text-slate-500 mt-1">{{ $stats['ecoles_actives'] }} actives</p>
                </div>
                <div class="w-12 h-12 bg-red-500/20 rounded-xl flex items-center justify-center">
                    <span class="text-red-400 text-xl">🏫</span>
                </div>
            </div>
        </div>

        <div class="bg-slate-800/50 border border-blue-500/20 rounded-xl p-6 hover:border-blue-500/40 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Utilisateurs Global</p>
                    <p class="text-3xl font-bold text-blue-400">{{ $stats['total_users'] }}</p>
                    <p class="text-xs text-slate-500 mt-1">Toutes écoles</p>
                </div>
                <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                    <span class="text-blue-400 text-xl">👥</span>
                </div>
            </div>
        </div>

        <div class="bg-slate-800/50 border border-emerald-500/20 rounded-xl p-6 hover:border-emerald-500/40 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Nouvelles Écoles</p>
                    <p class="text-3xl font-bold text-emerald-400">{{ $stats['nouvelles_ecoles_mois'] }}</p>
                    <p class="text-xs text-slate-500 mt-1">Ce mois</p>
                </div>
                <div class="w-12 h-12 bg-emerald-500/20 rounded-xl flex items-center justify-center">
                    <span class="text-emerald-400 text-xl">🆕</span>
                </div>
            </div>
        </div>

        <div class="bg-slate-800/50 border border-purple-500/20 rounded-xl p-6 hover:border-purple-500/40 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Cours Global</p>
                    <p class="text-3xl font-bold text-purple-400">{{ $stats['total_cours'] }}</p>
                    <p class="text-xs text-slate-500 mt-1">{{ $stats['cours_actifs'] }} actifs</p>
                </div>
                <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                    <span class="text-purple-400 text-xl">📚</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions SuperAdmin -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Gestion Écoles -->
        <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-6">
            <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                🏫 <span class="ml-2">Gestion des Écoles</span>
            </h3>
            <div class="space-y-3">
                <a href="{{ route('admin.ecoles.index') }}" 
                   class="block w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-center font-medium">
                    📋 Toutes les Écoles ({{ $stats['total_ecoles'] }})
                </a>
                <a href="{{ route('admin.ecoles.create') }}" 
                   class="block w-full px-4 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-center font-medium">
                    ➕ Créer une Nouvelle École
                </a>
            </div>
        </div>

        <!-- Support Système -->
        <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-6">
            <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                🔧 <span class="ml-2">Support & Système</span>
            </h3>
            <div class="space-y-3">
                <a href="{{ route('admin.users.index') }}" 
                   class="block w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-center font-medium">
                    👥 Utilisateurs Globaux ({{ $stats['total_users'] }})
                </a>
                <a href="{{ route('admin.logs.index') }}" 
                   class="block w-full px-4 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors text-center font-medium">
                    📊 Logs & Monitoring
                </a>
            </div>
        </div>
    </div>

    <!-- Écoles du Réseau -->
    <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-6">
        <h3 class="text-xl font-bold text-white mb-6 flex items-center">
            🌐 <span class="ml-2">Vue d'ensemble du Réseau</span>
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($stats_ecoles as $ecole)
                <div class="bg-slate-700/30 border border-slate-600/30 rounded-lg p-4 hover:border-emerald-500/30 transition-colors">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-sm">{{ substr($ecole->nom, 0, 2) }}</span>
                            </div>
                            <div>
                                <div class="text-white font-medium text-sm">{{ $ecole->nom }}</div>
                                <div class="text-slate-400 text-xs">{{ $ecole->code ?? 'Code non défini' }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-emerald-400 font-bold text-lg">{{ $ecole->users_count ?? 0 }}</div>
                            <div class="text-slate-500 text-xs">membres</div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-400">Créée le {{ $ecole->created_at->format('d/m/Y') }}</span>
                        @if($ecole->active ?? true)
                            <span class="px-2 py-1 bg-emerald-500/20 text-emerald-300 rounded">Active</span>
                        @else
                            <span class="px-2 py-1 bg-red-500/20 text-red-300 rounded">Inactive</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-8 text-slate-400">
                    <p>Aucune école dans le réseau</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Message de distinction -->
    <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-6">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-red-500/20 rounded-xl flex items-center justify-center">
                <span class="text-red-400 text-xl">ℹ️</span>
            </div>
            <div>
                <h4 class="text-lg font-bold text-red-400">Interface Super Administrateur</h4>
                <p class="text-red-300 text-sm">Vous êtes connecté en tant que Super Administrateur avec accès global à toutes les écoles du réseau StudiosDB. Cette interface est destinée à la gestion système et au support.</p>
            </div>
        </div>
    </div>
</div>
@endsection
