@extends('layouts.admin-enhanced')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="space-y-6">
    <!-- Header avec nouvelles couleurs et icônes SVG -->
    <div class="gradient-admin-blue text-white p-8 rounded-2xl border border-admin-blue/30 relative overflow-hidden backdrop-blur-sm">
        <div class="absolute inset-0 bg-gradient-to-br from-white/3 via-transparent to-white/2"></div>
        
        <div class="relative z-10 flex justify-between items-center">
            <div class="flex items-center space-x-6">
                <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur border border-white/20">
                    <x-admin-icon name="users" size="w-8 h-8" color="text-white" />
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white drop-shadow-sm">Gestion des Utilisateurs</h1>
                    <p class="text-lg text-white/90 font-medium">Gestion de vos utilisateurs du réseau</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="#" 
                   class="bg-white/10 hover:bg-white/20 text-white px-5 py-3 rounded-xl transition-all duration-300 font-medium backdrop-blur border border-white/20">
                    <x-admin-icon name="settings" size="w-4 h-4" color="text-white" />
                    <span class="ml-2">Actions</span>
                </a>
                <a href="{{ route('admin.users.create') }}" 
                   class="bg-white/15 hover:bg-white/25 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 flex items-center space-x-3 backdrop-blur border border-white/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Nouvel Utilisateur</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques avec couleurs améliorées et icônes SVG -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Carte 1 - Total Utilisateurs -->
        <div class="bg-slate-800/40 backdrop-blur-xl border border-admin-blue/20 rounded-2xl p-6 hover:bg-slate-800/60 hover:border-admin-blue/40 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Total Utilisateurs</p>
                    <p class="text-3xl font-bold text-white">{{ \App\Models\User::count() ?? 4 }}</p>
                    <p class="text-xs text-slate-500 mt-1">Système complet</p>
                </div>
                <div class="w-14 h-14 bg-admin-blue/20 rounded-2xl flex items-center justify-center">
                    <x-admin-icon name="users" size="w-7 h-7" color="text-admin-blue" />
                </div>
            </div>
            <div class="mt-4 w-full bg-slate-700/30 rounded-full h-2">
                <div class="bg-gradient-to-r from-admin-blue to-admin-cyan h-2 rounded-full" style="width: 75%"></div>
            </div>
        </div>

        <!-- Carte 2 - Actifs -->
        <div class="bg-slate-800/40 backdrop-blur-xl border border-admin-emerald/20 rounded-2xl p-6 hover:bg-slate-800/60 hover:border-admin-emerald/40 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Actifs</p>
                    <p class="text-3xl font-bold text-admin-emerald">-</p>
                    <p class="text-xs text-slate-500 mt-1">En ligne maintenant</p>
                </div>
                <div class="w-14 h-14 bg-admin-emerald/20 rounded-2xl flex items-center justify-center">
                    <x-admin-icon name="presences" size="w-7 h-7" color="text-admin-emerald" />
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-admin-emerald">
                <div class="w-2 h-2 bg-admin-emerald rounded-full mr-2 animate-pulse"></div>
                <span>Statut en temps réel</span>
            </div>
        </div>

        <!-- Carte 3 - Ce Mois -->
        <div class="bg-slate-800/40 backdrop-blur-xl border border-admin-violet/20 rounded-2xl p-6 hover:bg-slate-800/60 hover:border-admin-violet/40 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Ce Mois</p>
                    <p class="text-3xl font-bold text-admin-violet">{{ \App\Models\User::whereMonth('created_at', now()->month)->count() ?? 4 }}</p>
                    <p class="text-xs text-slate-500 mt-1">Nouveaux inscrits</p>
                </div>
                <div class="w-14 h-14 bg-admin-violet/20 rounded-2xl flex items-center justify-center">
                    <x-admin-icon name="stats" size="w-7 h-7" color="text-admin-violet" />
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-admin-violet">
                <span class="mr-2">+25%</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
        </div>

        <!-- Carte 4 - Nouveaux -->
        <div class="bg-slate-800/40 backdrop-blur-xl border border-admin-amber/20 rounded-2xl p-6 hover:bg-slate-800/60 hover:border-admin-amber/40 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Nouveaux</p>
                    <p class="text-3xl font-bold text-admin-amber">{{ \App\Models\User::whereDate('created_at', today())->count() ?? 0 }}</p>
                    <p class="text-xs text-slate-500 mt-1">Aujourd'hui</p>
                </div>
                <div class="w-14 h-14 bg-admin-amber/20 rounded-2xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-admin-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-admin-amber">
                <div class="w-2 h-2 bg-admin-amber rounded-full mr-2"></div>
                <span>Aujourd'hui seulement</span>
            </div>
        </div>
    </div>

    <!-- Reste identique mais avec nouvelles couleurs dans les boutons -->
    <div class="bg-slate-800/40 backdrop-blur-xl rounded-2xl border border-slate-700/30 p-6">
        <div class="flex items-center justify-between">
            <div class="flex-1 max-w-lg">
                <div class="relative">
                    <input type="text" 
                           placeholder="Rechercher un utilisateur..." 
                           class="w-full bg-slate-700/50 border border-slate-600/50 text-white rounded-xl px-5 py-3 pl-12 focus:outline-none focus:ring-2 focus:ring-admin-blue/50 focus:border-admin-blue/50 backdrop-blur transition-all duration-300">
                    <x-admin-icon name="default" size="w-5 h-5" color="text-slate-400" />
                </div>
            </div>
            <div class="flex items-center space-x-3 ml-6">
                <select class="bg-slate-700/50 border border-slate-600/50 text-white rounded-xl px-4 py-3 backdrop-blur focus:outline-none focus:ring-2 focus:ring-admin-blue/50">
                    <option value="">Tous les statuts</option>
                    <option value="actif">Actif</option>
                    <option value="inactif">Inactif</option>
                </select>
                <button class="bg-admin-blue/80 hover:bg-admin-blue text-white px-6 py-3 rounded-xl transition-all duration-300 font-medium backdrop-blur">
                    Filtrer
                </button>
            </div>
        </div>
    </div>

    <!-- Table avec amélioration des couleurs -->
    <div class="bg-slate-800/40 backdrop-blur-xl rounded-2xl border border-slate-700/30 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-900/50">
                    <tr>
                        <th class="px-6 py-4 text-left">
                            <input type="checkbox" class="w-4 h-4 text-admin-blue bg-slate-700/50 border-slate-600/50 rounded">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Utilisateur</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">École</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Rôle</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/30">
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="w-20 h-20 bg-admin-blue/10 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                <x-admin-icon name="users" size="w-10 h-10" color="text-admin-blue" />
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-2">Module Utilisateurs</h3>
                            <p class="text-slate-400 mb-8 text-lg">Interface moderne avec icônes SVG - StudiosDB v4.1.10.2</p>
                            
                            <div class="flex justify-center space-x-4">
                                <a href="{{ route('admin.users.create') }}" 
                                   class="inline-flex items-center px-6 py-3 bg-admin-blue hover:bg-admin-blue/80 text-white rounded-xl transition-all duration-300 font-medium">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Créer un utilisateur
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
