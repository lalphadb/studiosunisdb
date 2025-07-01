@extends('layouts.admin')

@section('title', 'Gestion des Ceintures')

@section('content')
<div class="space-y-6">
    <!-- Header Module Ceintures -->
    <div class="gradient-ceintures text-white p-8 rounded-2xl border border-orange-500/20 relative overflow-hidden backdrop-blur-sm studiosdb-fade-in">
        <div class="absolute inset-0 bg-gradient-to-br from-white/3 via-transparent to-white/2"></div>
        
        <div class="relative z-10 flex justify-between items-center">
            <div class="flex items-center space-x-6">
                <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur border border-white/20">
                    <x-admin-icon name="ceintures" size="w-8 h-8" color="text-white" />
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white drop-shadow-sm">Gestion des Ceintures</h1>
                    <p class="text-lg text-white/90 font-medium">Administration des ceintures et niveaux du système</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.ceintures.create') }}" 
                   class="bg-white/15 hover:bg-white/25 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 flex items-center space-x-3 backdrop-blur border border-white/20">
                    <x-admin-icon name="plus" size="w-5 h-5" color="text-white" />
                    <span>Nouvelle Ceinture</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards SÉCURISÉES -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="studiosdb-card hover:scale-105 cursor-pointer group">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Total Ceintures</p>
                    <p class="text-3xl font-bold text-white group-hover:text-orange-400 transition-colors">
                        {{ isset($ceintures) ? (method_exists($ceintures, 'total') ? $ceintures->total() : $ceintures->count()) : 0 }}
                    </p>
                    <p class="text-xs text-slate-500 mt-1">Niveaux disponibles</p>
                </div>
                <div class="w-14 h-14 bg-orange-500/20 border-orange-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="ceintures" size="w-7 h-7" color="text-orange-400" />
                </div>
            </div>
        </div>
        
        <div class="studiosdb-card">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Attributions</p>
                    <p class="text-3xl font-bold text-emerald-400">0</p>
                    <p class="text-xs text-slate-500 mt-1">Total attribuées</p>
                </div>
                <div class="w-14 h-14 bg-emerald-500/20 border-emerald-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="users" size="w-7 h-7" color="text-emerald-400" />
                </div>
            </div>
        </div>
        
        <div class="studiosdb-card">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Cette Semaine</p>
                    <p class="text-3xl font-bold text-blue-400">0</p>
                    <p class="text-xs text-slate-500 mt-1">Nouvelles attributions</p>
                </div>
                <div class="w-14 h-14 bg-blue-500/20 border-blue-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="stats" size="w-7 h-7" color="text-blue-400" />
                </div>
            </div>
        </div>
        
        <div class="studiosdb-card">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Cette Année</p>
                    <p class="text-3xl font-bold text-violet-400">0</p>
                    <p class="text-xs text-slate-500 mt-1">Promotions</p>
                </div>
                <div class="w-14 h-14 bg-violet-500/20 border-violet-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="presences" size="w-7 h-7" color="text-violet-400" />
                </div>
            </div>
        </div>
    </div>

    <!-- Table Ceintures SÉCURISÉE -->
    <div class="studiosdb-card">
        <div class="flex items-center justify-between mb-6 pb-6 border-b border-slate-700/30">
            <div class="flex-1 max-w-lg">
                <div class="relative">
                    <input type="text" placeholder="Rechercher une ceinture..." 
                           class="studiosdb-search w-full pl-12">
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 studiosdb-search-icon">
                        <x-admin-icon name="search" size="w-5 h-5" />
                    </div>
                </div>
            </div>
            
            <div class="flex items-center space-x-3 ml-6">
                <select class="studiosdb-select">
                    <option value="">Toutes les ceintures</option>
                    <option value="debutant">Débutant</option>
                    <option value="intermediaire">Intermédiaire</option>
                    <option value="avance">Avancé</option>
                </select>
                <button class="studiosdb-btn studiosdb-btn-ceintures">
                    <x-admin-icon name="filter" size="w-4 h-4" />
                    <span class="ml-2">Filtrer</span>
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="studiosdb-table">
                <thead>
                    <tr>
                        <th class="w-4">
                            <input type="checkbox" class="w-4 h-4 text-orange-500 bg-slate-700/50 border-slate-600/50 rounded">
                        </th>
                        <th>Ceinture</th>
                        <th>Couleur</th>
                        <th>Niveau</th>
                        <th>Attributions</th>
                        <th>Ordre</th>
                        <th class="w-20">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/30">
                    @forelse($ceintures ?? [] as $ceinture)
                        <tr class="hover:bg-slate-700/20 transition-colors">
                            <td>
                                <input type="checkbox" class="w-4 h-4 text-orange-500 bg-slate-700/50 border-slate-600/50 rounded">
                            </td>
                            <td>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-500 rounded-full flex items-center justify-center">
                                        <span class="text-white font-medium">{{ substr($ceinture->nom ?? 'C', 0, 1) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-white font-medium">{{ $ceinture->nom ?? 'Ceinture' }}</div>
                                        <div class="text-slate-400 text-sm">{{ $ceinture->description ?? 'Pas de description' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center">
                                    <div class="w-6 h-6 rounded-full mr-3" style="background-color: {{ $ceinture->couleur ?? '#6B7280' }}"></div>
                                    <span class="text-slate-300">{{ $ceinture->couleur ?? 'Non définie' }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="studiosdb-badge studiosdb-badge-ceintures">{{ $ceinture->niveau ?? 'Standard' }}</span>
                            </td>
                            <td>
                                <span class="text-blue-400 font-medium">0</span>
                            </td>
                            <td class="text-slate-300">
                                {{ $ceinture->ordre ?? 0 }}
                            </td>
                            <td>
                                <x-actions-dropdown :model="$ceinture" module="ceintures" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-empty-state
                                    icon="ceintures"
                                    title="Aucune ceinture trouvée"
                                    description="Créez vos premières ceintures pour organiser le système de niveaux."
                                    action-label="Créer une ceinture"
                                    :action-route="route('admin.ceintures.create')"
                                    action-color="orange"
                                />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if(isset($ceintures) && method_exists($ceintures, 'links'))
        <div class="flex justify-center">
            {{ $ceintures->links() }}
        </div>
    @endif
</div>
@endsection
