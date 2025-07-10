@extends('layouts.admin')

@section('title', 'Dashboard Admin StudiosDB')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-blue-900/50 to-blue-800/50 p-8 rounded-xl border border-blue-500/20">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">
                    Bienvenue, {{ $user->name ?? 'Admin' }} 👋
                </h1>
                <p class="text-blue-200">
                    Administration StudiosDB v4.1.10.2 - {{ $ecole->nom ?? 'Système Global' }}
                </p>
            </div>
            <div class="text-right">
                <div class="bg-blue-500/20 px-4 py-2 rounded-lg border border-blue-500/30">
                    <p class="text-blue-200 text-sm">{{ now()->format('d/m/Y H:i') }}</p>
                    <p class="text-white font-medium">{{ $user->email ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards avec data attributes pour JS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">👥 Utilisateurs</p>
                    <p class="text-3xl font-bold text-white" data-counter="{{ $stats['total_users'] ?? 0 }}" data-stat="total_users">0</p>
                    <p class="text-xs text-slate-500 mt-1">Total membres</p>
                </div>
                <div class="w-14 h-14 bg-blue-500/20 border-blue-500/30 rounded-2xl flex items-center justify-center border">
                    <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">📚 Cours</p>
                    <p class="text-3xl font-bold text-white" data-counter="{{ $stats['total_cours'] ?? 0 }}" data-stat="total_cours">0</p>
                    <p class="text-xs text-slate-500 mt-1">Programmes actifs</p>
                </div>
                <div class="w-14 h-14 bg-purple-500/20 border-purple-500/30 rounded-2xl flex items-center justify-center border">
                    <svg class="w-7 h-7 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">🥋 Ceintures</p>
                    <p class="text-3xl font-bold text-white" data-counter="{{ $stats['total_ceintures'] ?? 0 }}" data-stat="total_ceintures">0</p>
                    <p class="text-xs text-slate-500 mt-1">Types disponibles</p>
                </div>
                <div class="w-14 h-14 bg-orange-500/20 border-orange-500/30 rounded-2xl flex items-center justify-center border">
                    <svg class="w-7 h-7 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">📊 Activité</p>
                    <p class="text-3xl font-bold text-white" data-counter="{{ ($stats['presences_mois'] ?? 0) + ($stats['paiements_mois'] ?? 0) }}" data-stat="activity_month">0</p>
                    <p class="text-xs text-slate-500 mt-1">Ce mois</p>
                </div>
                <div class="w-14 h-14 bg-cyan-500/20 border-cyan-500/30 rounded-2xl flex items-center justify-center border">
                    <svg class="w-7 h-7 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique avec Canvas -->
    <div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden">
        <h2 class="text-xl font-bold text-white mb-6">Évolution (30 derniers jours)</h2>
        <div class="relative">
            <canvas id="trendChart" class="w-full h-64"></canvas>
            <div id="chartLoading" class="absolute inset-0 flex items-center justify-center bg-slate-800/50 rounded">
                <div class="text-slate-400 text-center">
                    <div class="animate-spin w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full mb-2 mx-auto"></div>
                    <p class="text-sm">Chargement des données...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Rapides -->
    <div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden">
        <h2 class="text-xl font-bold text-white mb-6">Actions Rapides</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.users.index') }}" class="group p-4 bg-slate-700/30 rounded-lg hover:bg-blue-600/20 transition-all duration-300">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-white">Utilisateurs</p>
                        <p class="text-sm text-slate-400">Gérer les membres</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.cours.index') }}" class="group p-4 bg-slate-700/30 rounded-lg hover:bg-purple-600/20 transition-all duration-300">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-white">Cours</p>
                        <p class="text-sm text-slate-400">Programmes</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.presences.index') }}" class="group p-4 bg-slate-700/30 rounded-lg hover:bg-cyan-600/20 transition-all duration-300">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-cyan-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-white">Présences</p>
                        <p class="text-sm text-slate-400">Assiduité</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.paiements.index') }}" class="group p-4 bg-slate-700/30 rounded-lg hover:bg-amber-600/20 transition-all duration-300">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-amber-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-white">Paiements</p>
                        <p class="text-sm text-slate-400">Finances</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
