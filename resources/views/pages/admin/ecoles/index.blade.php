@extends('layouts.admin')

@section('title', 'Gestion des Écoles')

@section('content')
<div class="space-y-6">
    <!-- Header Module Écoles -->
    <div class="gradient-ecoles text-white p-8 rounded-2xl border border-emerald-500/20 relative overflow-hidden backdrop-blur-sm studiosdb-fade-in">
        <div class="absolute inset-0 bg-gradient-to-br from-white/3 via-transparent to-white/2"></div>
        
        <div class="relative z-10 flex justify-between items-center">
            <div class="flex items-center space-x-6">
                <div class="w-16 h-16 bg-slate-700/10 rounded-2xl flex items-center justify-center backdrop-blur border border-white/20">
                    <x-admin-icon name="ecoles" size="w-8 h-8" color="text-white" />
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white drop-shadow-sm">Gestion des Écoles</h1>
                    <p class="text-lg text-white/90 font-medium">Administration du réseau d'écoles StudiosDB</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                @can('create', App\Models\Ecole::class)
                    <a href="{{ route('admin.ecoles.create') }}" 
                       class="bg-slate-700/15 hover:bg-slate-700/25 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 flex items-center space-x-3 backdrop-blur border border-white/20">
                        <x-admin-icon name="plus" size="w-5 h-5" color="text-white" />
                        <span>Nouvelle École</span>
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Stats Cards Écoles -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="studiosdb-card hover:scale-105 cursor-pointer group">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Total Écoles</p>
                    <p class="text-3xl font-bold text-white group-hover:text-emerald-400 transition-colors">{{ $ecoles->total() ?? 0 }}</p>
                    <p class="text-xs text-slate-500 mt-1">Dans le réseau</p>
                </div>
                <div class="w-14 h-14 bg-emerald-500/20 border-emerald-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="ecoles" size="w-7 h-7" color="text-emerald-400" />
                </div>
            </div>
        </div>
        
        <div class="studiosdb-card">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Actives</p>
                    <p class="text-3xl font-bold text-green-400">{{ isset($ecoles) ? $ecoles->where('active', true)->count() : 0 }}</p>
                    <p class="text-xs text-slate-500 mt-1">Opérationnelles</p>
                </div>
                <div class="w-14 h-14 bg-green-500/20 border-green-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="presences" size="w-7 h-7" color="text-green-400" />
                </div>
            </div>
        </div>
        
        <div class="studiosdb-card">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Utilisateurs</p>
                    <p class="text-3xl font-bold text-blue-400">{{ isset($ecoles) ? $ecoles->sum(function($e) { return $e->users->count() ?? 0; }) : 0 }}</p>
                    <p class="text-xs text-slate-500 mt-1">Total membres</p>
                </div>
                <div class="w-14 h-14 bg-blue-500/20 border-blue-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="users" size="w-7 h-7" color="text-blue-400" />
                </div>
            </div>
        </div>
        
        <div class="studiosdb-card">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Cours</p>
                    <p class="text-3xl font-bold text-violet-400">{{ isset($ecoles) ? $ecoles->sum(function($e) { return $e->cours->count() ?? 0; }) : 0 }}</p>
                    <p class="text-xs text-slate-500 mt-1">Total programmes</p>
                </div>
                <div class="w-14 h-14 bg-violet-500/20 border-violet-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="cours" size="w-7 h-7" color="text-violet-400" />
                </div>
            </div>
        </div>
    </div>

    <!-- Table Écoles -->
    <div class="studiosdb-card">
        <div class="flex items-center justify-between mb-6 pb-6 border-b border-slate-700/30">
            <div class="flex-1 max-w-lg">
                <div class="relative">
                    <input type="text" placeholder="Rechercher une école..." 
                           class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 pl-12 focus:ring-2 focus:ring-emerald-500">
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 studiosdb-search-icon">
                        <x-admin-icon name="search" size="w-5 h-5" />
                    </div>
                </div>
            </div>
            
            <div class="flex items-center space-x-3 ml-6">
                <select class="studiosdb-select">
                    <option value="">Toutes les écoles</option>
                    <option value="active">Actives</option>
                    <option value="inactive">Inactives</option>
                </select>
                <button class="studiosdb-btn studiosdb-btn-ecoles">
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
                            <input type="checkbox" class="w-4 h-4 text-emerald-500 bg-slate-700/50 border-slate-600/50 rounded">
                        </th>
                        <th>École</th>
                        <th>Code</th>
                        <th>Adresse</th>
                        <th>Utilisateurs</th>
                        <th>Statut</th>
                        <th class="w-20">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/30">
                    @forelse($ecoles ?? [] as $ecole)
                        <tr class="hover:bg-slate-700/20 transition-colors">
                            <td>
                                <input type="checkbox" class="w-4 h-4 text-emerald-500 bg-slate-700/50 border-slate-600/50 rounded">
                            </td>
                            <td>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-emerald-500 to-green-500 rounded-full flex items-center justify-center">
                                        <span class="text-white font-medium">{{ substr($ecole->nom, 0, 1) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-white font-medium">{{ $ecole->nom }}</div>
                                        <div class="text-slate-400 text-sm">{{ $ecole->email ?? 'Pas d\'email' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="font-mono text-emerald-400 bg-emerald-500/10 px-2 py-1 rounded">{{ $ecole->code }}</span>
                            </td>
                            <td class="text-slate-300">
                                {{ $ecole->adresse ?? 'Non renseignée' }}
                            </td>
                            <td>
                                <span class="text-blue-400 font-medium">{{ $ecole->users->count() ?? 0 }}</span>
                            </td>
                            <td>
                                @if($ecole->active ?? true)
                                    <span class="studiosdb-badge studiosdb-badge-active">Active</span>
                                @else
                                    <span class="studiosdb-badge studiosdb-badge-inactive">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <x-actions-dropdown :model="$ecole" module="ecoles" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-empty-state
                                    icon="ecoles"
                                    title="Aucune école trouvée"
                                    description="Commencez par créer votre première école pour organiser le réseau."
                                    action-label="Créer une école"
                                    :action-route="route('admin.ecoles.create')"
                                    action-color="emerald"
                                />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if(isset($ecoles) && method_exists($ecoles, 'links'))
        <div class="flex justify-center">
            {{ $ecoles->links() }}
        </div>
    @endif
</div>
@endsection
