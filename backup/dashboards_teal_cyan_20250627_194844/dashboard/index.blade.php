@extends('layouts.admin')

@section('title', 'Dashboard SuperAdmin')

@section('content')
<div class="space-y-6">
    <!-- Header SuperAdmin - VOTRE STYLE ORIGINAL -->
    <div class="bg-gradient-to-r from-purple-600 via-blue-600 to-transparent rounded-xl p-6 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-transparent to-slate-900 opacity-60"></div>
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">👑 Dashboard SuperAdmin</h1>
                <p class="text-purple-100 text-lg">Vue globale - {{ $stats['total_ecoles'] ?? 0 }} Studios Unis du Québec</p>
            </div>
            <div class="text-right">
                <div class="bg-purple-500 bg-opacity-50 px-4 py-2 rounded-lg">
                    <div class="text-sm text-purple-100">⭐ SuperAdministrateur</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Telescope Monitoring -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-white flex items-center">
                <span class="mr-2">🔭</span>
                Telescope - Monitoring Système
            </h3>
            <a href="/telescope" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                🔍 Ouvrir Telescope
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-red-400">18</div>
                <div class="text-sm text-slate-400">Exceptions</div>
                <div class="text-xs text-slate-500">Dernières 24h</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-yellow-400">0</div>
                <div class="text-sm text-slate-400">Logs Erreur</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-orange-400">0</div>
                <div class="text-sm text-slate-400">Requêtes Lentes</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-400">54</div>
                <div class="text-sm text-slate-400">Requêtes Échouées</div>
            </div>
        </div>
    </div>

    <!-- Métriques principales -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Écoles -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-orange-500 to-red-600">
                    <span class="text-xl text-white">🏫</span>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-white">{{ $stats['total_ecoles'] ?? 0 }}</div>
                    <div class="text-sm text-slate-400">Écoles</div>
                    <div class="text-xs text-slate-500">{{ $stats['total_ecoles'] ?? 0 }} actives</div>
                </div>
            </div>
        </div>

        <!-- Membres -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-green-500 to-teal-600">
                    <span class="text-xl text-white">👥</span>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-white">{{ $stats['total_users'] ?? 0 }}</div>
                    <div class="text-sm text-slate-400">Membres</div>
                    <div class="text-xs text-slate-500">{{ $stats['total_users'] ?? 0 }} actifs</div>
                </div>
            </div>
        </div>

        <!-- Cours -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-yellow-500 to-orange-600">
                    <span class="text-xl text-white">📚</span>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-white">{{ $stats['total_cours'] ?? 0 }}</div>
                    <div class="text-sm text-slate-400">Cours</div>
                    <div class="text-xs text-slate-500">{{ $stats['cours_actifs'] ?? 0 }} actifs</div>
                </div>
            </div>
        </div>

        <!-- Taux Présence -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-pink-600">
                    <span class="text-xl text-white">📊</span>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-white">85%</div>
                    <div class="text-sm text-slate-400">Taux Présence</div>
                    <div class="text-xs text-slate-500">Ce mois</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reste du dashboard... -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <h3 class="text-lg font-bold text-white mb-4 flex items-center">
            <span class="mr-2">🎯</span>
            Actions Rapides SuperAdmin
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.users.index') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-lg text-center block transition-colors">
                👥 Gestion Utilisateurs
            </a>
            <a href="{{ route('admin.ecoles.index') }}" 
               class="bg-green-600 hover:bg-green-700 text-white p-4 rounded-lg text-center block transition-colors">
                🏫 Gestion Écoles
            </a>
            <a href="{{ route('admin.ceintures.index') }}" 
               class="bg-orange-600 hover:bg-orange-700 text-white p-4 rounded-lg text-center block transition-colors">
                🥋 Gestion Ceintures
            </a>
        </div>
    </div>
</div>
@endsection
