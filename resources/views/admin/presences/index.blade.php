@extends('layouts.admin')

@section('page-title', 'Gestion des Présences')
@section('page-subtitle', 'Suivi de l\'assiduité des membres')

@section('content')
<x-module-header 
    module="presences"
    title="✅ Gestion des Présences" 
    subtitle="Suivi de l'assiduité des membres"
    createRoute="{{ route('admin.presences.create') }}"
    createText="Marquer Présence"
    createPermission="presences.create" />

<div class="studiosdb-content space-y-6">
    
    <!-- Stats Présences StudiosDB -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="studiosdb-card hover:border-green-500/30 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Total Présences</p>
                    <p class="text-3xl font-bold text-green-400">{{ $stats['total_presences'] ?? 0 }}</p>
                    <p class="text-xs text-slate-500 mt-1">Toutes périodes</p>
                </div>
                <div class="w-14 h-14 bg-green-500/20 border-green-500/30 rounded-2xl flex items-center justify-center border">
                    <span class="text-green-400 text-2xl">✅</span>
                </div>
            </div>
        </div>
        
        <div class="studiosdb-card hover:border-blue-500/30 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Aujourd'hui</p>
                    <p class="text-3xl font-bold text-blue-400">{{ $stats['presences_aujourd_hui'] ?? 0 }}</p>
                    <p class="text-xs text-slate-500 mt-1">{{ now()->format('d/m/Y') }}</p>
                </div>
                <div class="w-14 h-14 bg-blue-500/20 border-blue-500/30 rounded-2xl flex items-center justify-center border">
                    <span class="text-blue-400 text-2xl">📅</span>
                </div>
            </div>
        </div>
        
        <div class="studiosdb-card hover:border-amber-500/30 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Taux Présence</p>
                    <p class="text-3xl font-bold text-amber-400">{{ number_format($stats['taux_presence'] ?? 0, 1) }}%</p>
                    <p class="text-xs text-slate-500 mt-1">Moyenne globale</p>
                </div>
                <div class="w-14 h-14 bg-amber-500/20 border-amber-500/30 rounded-2xl flex items-center justify-center border">
                    <span class="text-amber-400 text-2xl">📊</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres StudiosDB -->
    <div class="studiosdb-card">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Rechercher</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Nom du membre ou cours..."
                       class="studiosdb-input w-full">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Cours</label>
                <select name="cours_id" class="studiosdb-input w-full">
                    <option value="">Tous les cours</option>
                    @foreach($cours ?? [] as $c)
                        <option value="{{ $c->id }}" {{ request('cours_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Date</label>
                <input type="date" 
                       name="date_cours" 
                       value="{{ request('date_cours') }}"
                       class="studiosdb-input w-full">
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="studiosdb-btn-primary bg-green-600 hover:bg-green-700">
                    🔍 Filtrer
                </button>
                <a href="{{ route('admin.presences.index') }}" class="studiosdb-btn-primary bg-slate-600 hover:bg-slate-700">
                    🔄 Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Liste des présences StudiosDB -->
    <div class="studiosdb-card">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-white">Liste des Présences</h2>
            <div class="text-sm text-slate-400">
                {{ ($presences ?? collect([]))->count() }} résultat(s) sur {{ ($presences ?? collect([]))->total() ?? 0 }}
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-700">
                <thead class="bg-slate-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Membre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Cours</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Notes</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-slate-800 divide-y divide-slate-700">
                    @forelse($presences ?? [] as $presence)
                        <tr class="hover:bg-slate-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center">
                                        <span class="text-white font-medium text-sm">{{ substr($presence->user->name ?? 'U', 0, 2) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-white">{{ $presence->user->name ?? 'Utilisateur' }}</div>
                                        <div class="text-sm text-slate-400">{{ $presence->user->email ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-white font-medium">{{ $presence->cours->nom ?? 'Cours' }}</div>
                                <div class="text-sm text-slate-400">{{ $presence->cours->description ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-white">{{ $presence->date_formatted }}</div>
                                <div class="text-sm text-slate-400">{{ $presence->date_human }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {!! $presence->status_badge !!}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-300 max-w-xs truncate">
                                    {{ $presence->notes ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <x-module-actions 
                                    :item="$presence"
                                    module="presences"
                                    :canView="auth()->user()->can('presences.view')"
                                    :canEdit="auth()->user()->can('presences.edit')"
                                    :canDelete="auth()->user()->can('presences.delete')"
                                    :extraActions="[
                                        [
                                            'url' => '#',
                                            'icon' => 'toggle',
                                            'label' => 'Toggle',
                                            'title' => 'Changer le statut',
                                            'color' => 'purple'
                                        ]
                                    ]" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <x-empty-state 
                                    icon="✅"
                                    title="Aucune présence enregistrée"
                                    description="Les présences apparaîtront ici une fois marquées"
                                    actionUrl="{{ route('admin.presences.create') }}"
                                    actionText="Marquer une présence"
                                    :action="auth()->user()->can('presences.create')" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination StudiosDB -->
        @if(($presences ?? collect([]))->hasPages())
        <div class="px-6 py-4 border-t border-slate-700 bg-slate-800/50">
            {{ $presences->links() }}
        </div>
        @endif
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="studiosdb-card">
            <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                <span class="text-green-400 mr-2">📊</span>
                Présences par statut
            </h3>
            <div class="space-y-3">
                @php
                    $totalPresents = ($presences ?? collect([]))->where('present', true)->count();
                    $totalAbsents = ($presences ?? collect([]))->where('present', false)->count();
                    $total = $totalPresents + $totalAbsents;
                @endphp
                
                <div class="flex items-center justify-between">
                    <span class="text-slate-300">✅ Présents</span>
                    <div class="flex items-center space-x-2">
                        <span class="text-green-400 font-bold">{{ $totalPresents }}</span>
                        @if($total > 0)
                            <span class="text-slate-500 text-sm">({{ round(($totalPresents / $total) * 100, 1) }}%)</span>
                        @endif
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-slate-300">❌ Absents</span>
                    <div class="flex items-center space-x-2">
                        <span class="text-red-400 font-bold">{{ $totalAbsents }}</span>
                        @if($total > 0)
                            <span class="text-slate-500 text-sm">({{ round(($totalAbsents / $total) * 100, 1) }}%)</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="studiosdb-card">
            <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                <span class="text-blue-400 mr-2">⚡</span>
                Actions rapides
            </h3>
            <div class="space-y-3">
                <a href="{{ route('admin.presences.create') }}" 
                   class="studiosdb-btn-primary bg-green-600 hover:bg-green-700 w-full text-center block">
                    ➕ Marquer une présence
                </a>
                <a href="{{ route('admin.presences.index', ['date_cours' => today()->format('Y-m-d')]) }}" 
                   class="studiosdb-btn-primary bg-blue-600 hover:bg-blue-700 w-full text-center block">
                    📅 Présences d'aujourd'hui
                </a>
                <a href="{{ route('admin.presences.index', ['present' => '0']) }}" 
                   class="studiosdb-btn-primary bg-red-600 hover:bg-red-700 w-full text-center block">
                    ❌ Voir les absents
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
