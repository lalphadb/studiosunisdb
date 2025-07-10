@extends('layouts.admin')

@section('title', 'Dashboard SuperAdmin')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header SuperAdmin -->
    <div class="bg-gradient-to-r from-emerald-900/50 to-emerald-800/50 p-8 rounded-xl border border-emerald-500/20">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">
                    🌟 SuperAdmin StudiosDB 
                </h1>
                <p class="text-emerald-200">
                    Administration Globale - Réseau Studios Unis (22 écoles)
                </p>
            </div>
            <div class="text-right">
                <p class="text-emerald-200 text-sm">Système Global</p>
                <p class="text-white font-medium">{{ now()->format('H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Stats SuperAdmin -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-slate-800 p-6 rounded-xl border border-slate-700">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Users Global</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['users'] ?? 0 }}</p>
                    <p class="text-xs text-slate-500 mt-1">Tous Studios Unis</p>
                </div>
                <div class="w-14 h-14 bg-blue-500/20 border-blue-500/30 rounded-2xl flex items-center justify-center border">
                    <span class="text-blue-400 text-2xl">👥</span>
                </div>
            </div>
        </div>

        <div class="bg-slate-800 p-6 rounded-xl border border-slate-700">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Réseau</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['ecoles'] ?? 22 }}</p>
                    <p class="text-xs text-slate-500 mt-1">Studios actifs</p>
                </div>
                <div class="w-14 h-14 bg-emerald-500/20 border-emerald-500/30 rounded-2xl flex items-center justify-center border">
                    <span class="text-emerald-400 text-2xl">🏫</span>
                </div>
            </div>
        </div>

        <div class="bg-slate-800 p-6 rounded-xl border border-slate-700">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Cours Global</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['cours'] ?? 0 }}</p>
                    <p class="text-xs text-slate-500 mt-1">Toutes écoles</p>
                </div>
                <div class="w-14 h-14 bg-purple-500/20 border-purple-500/30 rounded-2xl flex items-center justify-center border">
                    <span class="text-purple-400 text-2xl">📚</span>
                </div>
            </div>
        </div>

        <div class="bg-slate-800 p-6 rounded-xl border border-slate-700">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Revenus</p>
                    <p class="text-3xl font-bold text-white">${{ number_format($stats['revenus_mois'] ?? 0, 0) }}</p>
                    <p class="text-xs text-slate-500 mt-1">Ce mois</p>
                </div>
                <div class="w-14 h-14 bg-amber-500/20 border-amber-500/30 rounded-2xl flex items-center justify-center border">
                    <span class="text-amber-400 text-2xl">💰</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions SuperAdmin -->
    <div class="bg-slate-800 p-6 rounded-xl border border-slate-700">
        <h2 class="text-xl font-bold text-white mb-6">🔧 Actions SuperAdmin</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-4 bg-slate-700 rounded-lg hover:bg-slate-600 transition-colors group">
                <div class="flex items-center space-x-3">
                    <span class="text-2xl group-hover:scale-110 transition-transform">🏫</span>
                    <div>
                        <p class="font-medium text-white">Gestion Écoles</p>
                        <p class="text-sm text-slate-400">Réseau Studios</p>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-slate-700 rounded-lg hover:bg-slate-600 transition-colors group">
                <div class="flex items-center space-x-3">
                    <span class="text-2xl group-hover:scale-110 transition-transform">🔍</span>
                    <div>
                        <p class="font-medium text-white">Telescope</p>
                        <p class="text-sm text-slate-400">Diagnostics</p>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-slate-700 rounded-lg hover:bg-slate-600 transition-colors group">
                <div class="flex items-center space-x-3">
                    <span class="text-2xl group-hover:scale-110 transition-transform">📊</span>
                    <div>
                        <p class="font-medium text-white">Rapports Global</p>
                        <p class="text-sm text-slate-400">Analytics</p>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-slate-700 rounded-lg hover:bg-slate-600 transition-colors group">
                <div class="flex items-center space-x-3">
                    <span class="text-2xl group-hover:scale-110 transition-transform">💰</span>
                    <div>
                        <p class="font-medium text-white">Finances Global</p>
                        <p class="text-sm text-slate-400">Revenus réseau</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
