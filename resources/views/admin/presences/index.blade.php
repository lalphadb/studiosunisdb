@extends('layouts.admin')

@section('title', 'Gestion des Présences')

@section('content')
<div class="space-y-6">
    <!-- Header Module Présences -->
    <div class="gradient-presences text-white p-8 rounded-2xl border border-cyan-500/20 relative overflow-hidden backdrop-blur-sm studiosdb-fade-in">
        <div class="absolute inset-0 bg-gradient-to-br from-white/3 via-transparent to-white/2"></div>
        
        <div class="relative z-10 flex justify-between items-center">
            <div class="flex items-center space-x-6">
                <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur border border-white/20">
                    <x-admin-icon name="presences" size="w-8 h-8" color="text-white" />
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white drop-shadow-sm">Gestion des Présences</h1>
                    <p class="text-lg text-white/90 font-medium">Suivi et contrôle des présences du réseau</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                @can('create', App\Models\Presence::class)
                    <a href="{{ route('admin.presences.create') }}" 
                       class="bg-white/15 hover:bg-white/25 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 flex items-center space-x-3 backdrop-blur border border-white/20">
                        <x-admin-icon name="plus" size="w-5 h-5" color="text-white" />
                        <span>Nouvelle Présence</span>
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Stats Cards Présences -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="studiosdb-card hover:scale-105 cursor-pointer group">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Total Présences</p>
                    <p class="text-3xl font-bold text-white group-hover:text-cyan-400 transition-colors">{{ $presences->total() ?? 0 }}</p>
                    <p class="text-xs text-slate-500 mt-1">Toutes enregistrées</p>
                </div>
                <div class="w-14 h-14 bg-cyan-500/20 border-cyan-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="presences" size="w-7 h-7" color="text-cyan-400" />
                </div>
            </div>
        </div>
        
        <div class="studiosdb-card">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Aujourd'hui</p>
                    <p class="text-3xl font-bold text-emerald-400">{{ isset($presences) ? $presences->count() : 0 }}</p>
                    <p class="text-xs text-slate-500 mt-1">Présences du jour</p>
                </div>
                <div class="w-14 h-14 bg-emerald-500/20 border-emerald-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="stats" size="w-7 h-7" color="text-emerald-400" />
                </div>
            </div>
        </div>
        
        <div class="studiosdb-card">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Cette Semaine</p>
                    <p class="text-3xl font-bold text-blue-400">0</p>
                    <p class="text-xs text-slate-500 mt-1">Assiduité hebdo</p>
                </div>
                <div class="w-14 h-14 bg-blue-500/20 border-blue-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="users" size="w-7 h-7" color="text-blue-400" />
                </div>
            </div>
        </div>
        
        <div class="studiosdb-card">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Taux Moyen</p>
                    <p class="text-3xl font-bold text-violet-400">85%</p>
                    <p class="text-xs text-slate-500 mt-1">Assiduité générale</p>
                </div>
                <div class="w-14 h-14 bg-violet-500/20 border-violet-500/30 rounded-2xl flex items-center justify-center border">
                    <span class="text-violet-400 text-lg font-bold">%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Présences -->
    <div class="studiosdb-card">
        <div class="flex items-center justify-between mb-6 pb-6 border-b border-slate-700/30">
            <div class="flex-1 max-w-lg">
                <div class="relative">
                    <input type="text" placeholder="Rechercher une présence..." 
                           class="studiosdb-search w-full pl-12">
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 studiosdb-search-icon">
                        <x-admin-icon name="search" size="w-5 h-5" />
                    </div>
                </div>
            </div>
            
            <div class="flex items-center space-x-3 ml-6">
                <select class="studiosdb-select">
                    <option value="">Toutes les présences</option>
                    <option value="present">Présents</option>
                    <option value="absent">Absents</option>
                    <option value="retard">Retards</option>
                </select>
                <button class="studiosdb-btn studiosdb-btn-presences">
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
                            <input type="checkbox" class="w-4 h-4 text-cyan-500 bg-slate-700/50 border-slate-600/50 rounded">
                        </th>
                        <th>Utilisateur</th>
                        <th>Cours</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Statut</th>
                        <th>Notes</th>
                        <th class="w-20">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/30">
                    @forelse($presences ?? [] as $presence)
                        <tr class="hover:bg-slate-700/20 transition-colors">
                            <td>
                                <input type="checkbox" class="w-4 h-4 text-cyan-500 bg-slate-700/50 border-slate-600/50 rounded">
                            </td>
                            <td>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-cyan-500 to-teal-500 rounded-full flex items-center justify-center">
                                        <span class="text-white font-medium">{{ substr($presence->user->name ?? 'U', 0, 1) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-white font-medium">{{ $presence->user->name ?? 'Utilisateur Test' }}</div>
                                        <div class="text-slate-400 text-sm">{{ $presence->user->email ?? 'user@test.ca' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($presence->cours ?? null)
                                    <span class="text-violet-400">{{ $presence->cours->nom }}</span>
                                @else
                                    <span class="text-slate-500">Cours supprimé</span>
                                @endif
                            </td>
                            <td class="text-slate-300">
                                {{ isset($presence->date) ? $presence->date->format('d/m/Y') : now()->format('d/m/Y') }}
                            </td>
                            <td class="text-slate-300">
                                {{ isset($presence->heure_arrivee) ? $presence->heure_arrivee->format('H:i') : now()->format('H:i') }}
                            </td>
                            <td>
                                @if(($presence->statut ?? 'present') == 'present')
                                    <span class="studiosdb-badge studiosdb-badge-active">Présent</span>
                                @elseif(($presence->statut ?? 'absent') == 'absent')
                                    <span class="studiosdb-badge studiosdb-badge-expired">Absent</span>
                                @else
                                    <span class="studiosdb-badge studiosdb-badge-pending">Retard</span>
                                @endif
                            </td>
                            <td class="text-slate-400">
                                {{ Str::limit($presence->notes ?? 'Aucune note', 30) }}
                            </td>
                            <td>
                                <x-actions-dropdown :model="$presence" module="presences" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <x-empty-state
                                    icon="presences"
                                    title="Aucune présence enregistrée"
                                    description="Les présences apparaîtront ici lors des cours et événements."
                                    action-label="Enregistrer une présence"
                                    :action-route="route('admin.presences.create')"
                                    action-color="cyan"
                                />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if(isset($presences) && method_exists($presences, 'links'))
        <div class="flex justify-center">
            {{ $presences->links() }}
        </div>
    @endif
</div>
@endsection
