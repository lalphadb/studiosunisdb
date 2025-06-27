@extends('layouts.admin')

@section('title', 'Dashboard Admin École')

@section('content')
<div class="space-y-6">
    <!-- Header Simple - VERT comme votre thème -->
    <div class="bg-gradient-to-r from-green-500 via-emerald-600 to-transparent rounded-xl p-6 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-transparent to-slate-900 opacity-60"></div>
        <div class="relative z-10">
            <h1 class="text-3xl font-bold">🏫 {{ auth()->user()->ecole->nom ?? 'Mon École' }}</h1>
            <p class="text-green-100 text-lg">Dashboard Administrateur</p>
        </div>
    </div>

    <!-- Stats Simples et Utiles -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Membres -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-600">
                    <span class="text-xl text-white">👥</span>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-white">{{ $stats['mes_membres'] ?? 0 }}</div>
                    <div class="text-sm text-slate-400">Total Membres</div>
                </div>
            </div>
        </div>

        <!-- Nouveaux ce mois -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-600">
                    <span class="text-xl text-white">📈</span>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-white">{{ $stats['nouveaux_mois'] ?? 0 }}</div>
                    <div class="text-sm text-slate-400">Nouveaux ce mois</div>
                </div>
            </div>
        </div>

        <!-- Cours Actifs -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-600">
                    <span class="text-xl text-white">📚</span>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-white">{{ $stats['mes_cours'] ?? 0 }}</div>
                    <div class="text-sm text-slate-400">Cours Actifs</div>
                </div>
            </div>
        </div>

        <!-- Revenus Estimés -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-yellow-600">
                    <span class="text-xl text-white">💰</span>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-white">${{ $stats['revenus_mois'] ?? 0 }}</div>
                    <div class="text-sm text-slate-400">Revenus Mois</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Nouveaux Membres - PRINCIPAL -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-white flex items-center">
                <span class="mr-2">👥</span>
                Nouveaux Membres
            </h3>
            <a href="{{ route('admin.users.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200">
                + Ajouter Membre
            </a>
        </div>
        
        <div class="space-y-3">
            @forelse($derniers_membres ?? [] as $membre)
            <div class="flex items-center justify-between p-4 bg-slate-900 rounded-lg border border-slate-700">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center mr-4">
                        <span class="text-white font-bold">{{ substr($membre->name, 0, 2) }}</span>
                    </div>
                    <div>
                        <div class="text-white font-medium">{{ $membre->name }}</div>
                        <div class="text-sm text-slate-400">{{ $membre->email }}</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-slate-300">{{ $membre->created_at->format('d/m/Y') }}</div>
                    <div class="text-xs text-slate-500">{{ $membre->created_at->diffForHumans() }}</div>
                </div>
            </div>
            @empty
            <div class="text-center py-12 text-slate-400">
                <div class="text-6xl mb-4">👋</div>
                <h3 class="text-xl font-medium mb-2">Bienvenue !</h3>
                <p class="mb-6">Commencez par ajouter vos premiers membres.</p>
                <a href="{{ route('admin.users.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition duration-200">
                    Ajouter Premier Membre
                </a>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Actions Rapides -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Gestion Membres -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <h4 class="text-lg font-semibold text-white mb-4">👥 Gestion Membres</h4>
            <div class="space-y-3">
                <a href="{{ route('admin.users.index') }}" 
                   class="block w-full bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-lg text-center transition duration-200">
                    Voir Tous les Membres
                </a>
                <a href="{{ route('admin.users.create') }}" 
                   class="block w-full bg-green-600 hover:bg-green-700 text-white p-3 rounded-lg text-center transition duration-200">
                    Ajouter un Membre
                </a>
            </div>
        </div>

        <!-- Gestion Cours -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <h4 class="text-lg font-semibold text-white mb-4">📚 Gestion Cours</h4>
            <div class="space-y-3">
                <a href="{{ route('admin.cours.index') }}" 
                   class="block w-full bg-purple-600 hover:bg-purple-700 text-white p-3 rounded-lg text-center transition duration-200">
                    Voir Mes Cours
                </a>
                <a href="{{ route('admin.cours.create') }}" 
                   class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white p-3 rounded-lg text-center transition duration-200">
                    Créer un Cours
                </a>
            </div>
        </div>

        <!-- Actions Spéciales -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <h4 class="text-lg font-semibold text-white mb-4">⚡ Actions Rapides</h4>
            <div class="space-y-3">
                <a href="{{ route('admin.ceintures.create') }}" 
                   class="block w-full bg-orange-600 hover:bg-orange-700 text-white p-3 rounded-lg text-center transition duration-200">
                    Attribuer Ceinture
                </a>
                <a href="{{ route('admin.presences.index') }}" 
                   class="block w-full bg-teal-600 hover:bg-teal-700 text-white p-3 rounded-lg text-center transition duration-200">
                    Gérer Présences
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques Détaillées -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <h3 class="text-xl font-bold text-white mb-6">📊 Statistiques Mon École</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="text-3xl font-bold text-blue-400">{{ $stats['mes_membres'] ?? 0 }}</div>
                <div class="text-sm text-slate-400">Total Membres</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-green-400">{{ $stats['membres_actifs'] ?? 0 }}</div>
                <div class="text-sm text-slate-400">Membres Actifs</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-purple-400">{{ $stats['mes_instructeurs'] ?? 0 }}</div>
                <div class="text-sm text-slate-400">Instructeurs</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-yellow-400">{{ $stats['cours_actifs'] ?? 0 }}</div>
                <div class="text-sm text-slate-400">Cours Actifs</div>
            </div>
        </div>
    </div>
</div>
@endsection
