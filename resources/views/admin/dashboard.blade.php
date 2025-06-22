@extends('layouts.admin')

@section('title', 'Dashboard SuperAdmin')

@section('content')
<div class="space-y-6">
    <!-- Header SuperAdmin -->
    <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
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
                    <div class="text-2xl font-bold text-white">{{ $stats['total_ecoles'] ?? 22 }}</div>
                    <div class="text-sm text-slate-400">Écoles</div>
                    <div class="text-xs text-slate-500">{{ $stats['total_ecoles'] ?? 22 }} actives</div>
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
                    <div class="text-2xl font-bold text-white">{{ $stats['total_users'] ?? 2 }}</div>
                    <div class="text-sm text-slate-400">Membres</div>
                    <div class="text-xs text-slate-500">{{ $stats['total_users'] ?? 2 }} actifs</div>
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

    <!-- Utilisateurs par Rôle -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <h3 class="text-lg font-bold text-white mb-4 flex items-center">
            <span class="mr-2">👥</span>
            Utilisateurs par Rôle
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-slate-900 rounded-lg">
                <div class="text-2xl font-bold text-purple-400">2</div>
                <div class="text-sm text-slate-400">SuperAdmins</div>
            </div>
            <div class="text-center p-4 bg-slate-900 rounded-lg">
                <div class="text-2xl font-bold text-blue-400">2</div>
                <div class="text-sm text-slate-400">Admin École</div>
            </div>
            <div class="text-center p-4 bg-slate-900 rounded-lg">
                <div class="text-2xl font-bold text-green-400">0</div>
                <div class="text-sm text-slate-400">Instructeurs</div>
            </div>
            <div class="text-center p-4 bg-slate-900 rounded-lg">
                <div class="text-2xl font-bold text-orange-400">0</div>
                <div class="text-sm text-slate-400">Membres</div>
            </div>
        </div>
    </div>

    <!-- Top Écoles et Revenus -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top 5 Écoles -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                <span class="mr-2">🏆</span>
                Top 5 Écoles (Membres)
            </h3>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-slate-900 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-white font-bold text-sm mr-3">1</div>
                        <span class="text-white">Studios Unis St-Émile</span>
                    </div>
                    <span class="bg-blue-600 text-white px-2 py-1 rounded text-sm">2 membres</span>
                </div>
            </div>
        </div>

        <!-- Top Revenus -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                <span class="mr-2">💰</span>
                Top Revenus (Estimation)
            </h3>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-slate-900 rounded-lg">
                    <span class="text-white">Studios Unis St-Émile</span>
                    <span class="bg-green-600 text-white px-2 py-1 rounded text-sm">$160</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
