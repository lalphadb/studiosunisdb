@extends('layouts.admin')

@section('title', 'Dashboard Admin')

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
                    Administration StudiosDB - {{ $ecole->nom ?? 'Système Global' }}
                </p>
            </div>
            <div class="text-right">
                <p class="text-blue-200 text-sm">{{ now()->format('d/m/Y') }}</p>
                <p class="text-white font-medium">{{ now()->format('H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Utilisateurs -->
        <div class="bg-slate-800 p-6 rounded-xl border border-slate-700 hover:border-blue-500/50 transition-colors">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Utilisateurs</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['users'] ?? 0 }}</p>
                    <p class="text-xs text-slate-500 mt-1">Total membres</p>
                </div>
                <div class="w-14 h-14 bg-blue-500/20 border-blue-500/30 rounded-2xl flex items-center justify-center border">
                    <span class="text-blue-400 text-2xl">👥</span>
                </div>
            </div>
        </div>

        <!-- Écoles -->
        <div class="bg-slate-800 p-6 rounded-xl border border-slate-700 hover:border-emerald-500/50 transition-colors">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Écoles</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['ecoles'] ?? 0 }}</p>
                    <p class="text-xs text-slate-500 mt-1">Studios actifs</p>
                </div>
                <div class="w-14 h-14 bg-emerald-500/20 border-emerald-500/30 rounded-2xl flex items-center justify-center border">
                    <span class="text-emerald-400 text-2xl">🏫</span>
                </div>
            </div>
        </div>

        <!-- Cours -->
        <div class="bg-slate-800 p-6 rounded-xl border border-slate-700 hover:border-purple-500/50 transition-colors">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Cours</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['cours'] ?? 0 }}</p>
                    <p class="text-xs text-slate-500 mt-1">Programmes actifs</p>
                </div>
                <div class="w-14 h-14 bg-purple-500/20 border-purple-500/30 rounded-2xl flex items-center justify-center border">
                    <span class="text-purple-400 text-2xl">📚</span>
                </div>
            </div>
        </div>

        <!-- Présences ce mois -->
        <div class="bg-slate-800 p-6 rounded-xl border border-slate-700 hover:border-cyan-500/50 transition-colors">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Présences</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['presences_mois'] ?? 0 }}</p>
                    <p class="text-xs text-slate-500 mt-1">Ce mois-ci</p>
                </div>
                <div class="w-14 h-14 bg-cyan-500/20 border-cyan-500/30 rounded-2xl flex items-center justify-center border">
                    <span class="text-cyan-400 text-2xl">✅</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Rapides -->
    <div class="bg-slate-800 p-6 rounded-xl border border-slate-700">
        <h2 class="text-xl font-bold text-white mb-6">Actions Rapides</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-4 bg-slate-700 rounded-lg hover:bg-slate-600 transition-colors group">
                <div class="flex items-center space-x-3">
                    <span class="text-2xl group-hover:scale-110 transition-transform">👥</span>
                    <div>
                        <p class="font-medium text-white">Présences</p>
                        <p class="text-sm text-slate-400">Gérer présences</p>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-slate-700 rounded-lg hover:bg-slate-600 transition-colors group">
                <div class="flex items-center space-x-3">
                    <span class="text-2xl group-hover:scale-110 transition-transform">💰</span>
                    <div>
                        <p class="font-medium text-white">Paiements</p>
                        <p class="text-sm text-slate-400">Finances</p>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-slate-700 rounded-lg hover:bg-slate-600 transition-colors group">
                <div class="flex items-center space-x-3">
                    <span class="text-2xl group-hover:scale-110 transition-transform">🎯</span>
                    <div>
                        <p class="font-medium text-white">Séminaires</p>
                        <p class="text-sm text-slate-400">Événements</p>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-slate-700 rounded-lg hover:bg-slate-600 transition-colors group">
                <div class="flex items-center space-x-3">
                    <span class="text-2xl group-hover:scale-110 transition-transform">📅</span>
                    <div>
                        <p class="font-medium text-white">Sessions</p>
                        <p class="text-sm text-slate-400">Planning</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Message de succès -->
    <div class="bg-emerald-900/20 border border-emerald-500/30 p-6 rounded-lg">
        <div class="flex items-center space-x-3">
            <span class="text-emerald-400 text-2xl">🎉</span>
            <div>
                <h3 class="text-emerald-200 font-semibold">StudiosDB Opérationnel !</h3>
                <p class="text-emerald-300">Le système admin est maintenant fonctionnel. Toutes les sécurités sont actives.</p>
            </div>
        </div>
    </div>
</div>
@endsection
