@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header principal -->
    <div class="bg-gradient-to-r from-blue-500 via-cyan-600 to-transparent rounded-xl p-6 text-white relative overflow-hidden mb-8">
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-transparent to-slate-900 opacity-60"></div>
        
        <div class="relative z-10">
            <h1 class="text-3xl font-bold mb-2">Tableau de bord</h1>
            <p class="text-white/80">
                Bienvenue, {{ auth()->user()->name }}
                @if(auth()->user()->roles->count() > 0)
                    ({{ auth()->user()->roles->first()->name }})
                @endif
            </p>
        </div>
    </div>

    <!-- Modules principaux -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-white mb-6">Modules de gestion</h2>
        
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Utilisateurs -->
            <a href="{{ route('admin.users.index') }}" 
               class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:border-blue-500 transition-colors group">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-3xl">👤</span>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-white">{{ \App\Models\User::count() }}</p>
                        <p class="text-slate-400 text-sm">Total</p>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-white group-hover:text-blue-400">Utilisateurs</h3>
                <p class="text-slate-400 text-sm">Gestion des membres</p>
            </a>

            <!-- Écoles -->
            <a href="{{ route('admin.ecoles.index') }}" 
               class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:border-green-500 transition-colors group">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-3xl">🏫</span>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-white">{{ \App\Models\Ecole::count() }}</p>
                        <p class="text-slate-400 text-sm">Total</p>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-white group-hover:text-green-400">Écoles</h3>
                <p class="text-slate-400 text-sm">Réseau d'écoles</p>
            </a>

            <!-- Cours -->
            <a href="{{ route('admin.cours.index') }}" 
               class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:border-purple-500 transition-colors group">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-3xl">📚</span>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-white">{{ \App\Models\Cours::count() }}</p>
                        <p class="text-slate-400 text-sm">Total</p>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-white group-hover:text-purple-400">Cours</h3>
                <p class="text-slate-400 text-sm">Planning et sessions</p>
            </a>

            <!-- Ceintures -->
            <a href="{{ route('admin.ceintures.index') }}" 
               class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:border-orange-500 transition-colors group">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-3xl">🥋</span>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-white">{{ \App\Models\Ceinture::count() }}</p>
                        <p class="text-slate-400 text-sm">Types</p>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-white group-hover:text-orange-400">Ceintures</h3>
                <p class="text-slate-400 text-sm">Système de progression</p>
            </a>

            <!-- Séminaires -->
            <a href="{{ route('admin.seminaires.index') }}" 
               class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:border-pink-500 transition-colors group">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-3xl">🎯</span>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-white">{{ \App\Models\Seminaire::count() }}</p>
                        <p class="text-slate-400 text-sm">Total</p>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-white group-hover:text-pink-400">Séminaires</h3>
                <p class="text-slate-400 text-sm">Événements spéciaux</p>
            </a>

            <!-- Paiements -->
            <a href="{{ route('admin.paiements.index') }}" 
               class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:border-yellow-500 transition-colors group">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-3xl">💰</span>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-white">{{ \App\Models\Paiement::count() }}</p>
                        <p class="text-slate-400 text-sm">Total</p>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-white group-hover:text-yellow-400">Paiements</h3>
                <p class="text-slate-400 text-sm">Gestion financière</p>
            </a>

            <!-- Présences -->
            <a href="{{ route('admin.presences.index') }}" 
               class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:border-teal-500 transition-colors group">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-3xl">✅</span>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-white">{{ \App\Models\Presence::count() }}</p>
                        <p class="text-slate-400 text-sm">Total</p>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-white group-hover:text-teal-400">Présences</h3>
                <p class="text-slate-400 text-sm">Suivi assiduité</p>
            </a>
        </div>
    </div>

    <!-- Séparateur -->
    <div class="border-t border-slate-700 my-8"></div>

    <!-- Outils administratifs -->
    <div>
        <h2 class="text-2xl font-bold text-white mb-6">Outils administratifs</h2>
        
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Telescope -->
            @if(auth()->user()->hasRole('superadmin'))
                <a href="{{ url('/telescope') }}" 
                   target="_blank"
                   class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:border-indigo-500 transition-colors group">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-3xl">🔭</span>
                        <span class="text-xs bg-indigo-600 text-white px-2 py-1 rounded">ADMIN</span>
                    </div>
                    <h3 class="text-lg font-semibold text-white group-hover:text-indigo-400">Laravel Telescope</h3>
                    <p class="text-slate-400 text-sm">Monitoring et debugging</p>
                </a>
            @endif

            <!-- Logs -->
            @can('viewAny', App\Models\User::class)
                <a href="{{ route('admin.logs.index') }}" 
                   class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:border-red-500 transition-colors group">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-3xl">📋</span>
                        <span class="text-xs bg-red-600 text-white px-2 py-1 rounded">LOGS</span>
                    </div>
                    <h3 class="text-lg font-semibold text-white group-hover:text-red-400">Logs système</h3>
                    <p class="text-slate-400 text-sm">Journaux d'activité</p>
                </a>
            @endcan

            <!-- Export données -->
            <a href="{{ route('admin.users.export') }}" 
               class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:border-cyan-500 transition-colors group">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-3xl">📊</span>
                    <span class="text-xs bg-cyan-600 text-white px-2 py-1 rounded">EXPORT</span>
                </div>
                <h3 class="text-lg font-semibold text-white group-hover:text-cyan-400">Export données</h3>
                <p class="text-slate-400 text-sm">Rapports et statistiques</p>
            </a>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="mt-8 bg-slate-800 rounded-xl border border-slate-700 p-6">
        <h3 class="text-lg font-bold text-white mb-4">Statistiques rapides</h3>
        
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-blue-400">{{ \App\Models\User::where('active', true)->count() }}</p>
                <p class="text-slate-400 text-sm">Membres actifs</p>
            </div>
            
            <div class="text-center">
                <p class="text-2xl font-bold text-green-400">{{ \App\Models\Ecole::where('active', true)->count() }}</p>
                <p class="text-slate-400 text-sm">Écoles actives</p>
            </div>
            
            <div class="text-center">
                <p class="text-2xl font-bold text-purple-400">{{ \App\Models\Cours::where('active', true)->count() }}</p>
                <p class="text-slate-400 text-sm">Cours actifs</p>
            </div>
            
            <div class="text-center">
                <p class="text-2xl font-bold text-orange-400">{{ \App\Models\User::whereHas('roles', function($q) { $q->where('name', 'instructeur'); })->count() }}</p>
                <p class="text-slate-400 text-sm">Instructeurs</p>
            </div>
        </div>
    </div>
</div>
@endsection
