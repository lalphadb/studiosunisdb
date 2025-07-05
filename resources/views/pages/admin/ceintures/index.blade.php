@extends('layouts.admin')

@section('title', 'Attributions de Ceintures')

@section('content')
<div class="space-y-6">
    <!-- Header Module Ceintures CORRIGÉ -->
    <div class="gradient-ceintures text-white p-8 rounded-2xl border border-orange-500/20 relative overflow-hidden backdrop-blur-sm studiosdb-fade-in">
        <div class="absolute inset-0 bg-gradient-to-br from-white/3 via-transparent to-white/2"></div>
        
        <div class="relative z-10 flex justify-between items-center">
            <div class="flex items-center space-x-6">
                <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur border border-white/20">
                    <x-admin-icon name="ceintures" size="w-8 h-8" color="text-white" />
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white drop-shadow-sm">Attributions de Ceintures</h1>
                    <p class="text-lg text-white/90 font-medium">Suivi des ceintures attribuées aux membres</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.ceintures.create-masse') }}" 
                   class="bg-white/15 hover:bg-white/25 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 flex items-center space-x-3 backdrop-blur border border-white/20">
                    <x-admin-icon name="plus" size="w-5 h-5" color="text-white" />
                    <span>Attribution en Masse</span>
                </a>
                <a href="{{ route('admin.ceintures.create') }}" 
                   class="bg-white/10 hover:bg-white/20 text-white px-6 py-3 rounded-xl font-medium transition-all duration-300 flex items-center space-x-3 backdrop-blur border border-white/20">
                    <x-admin-icon name="plus" size="w-5 h-5" color="text-white" />
                    <span>Attribution Individuelle</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards CORRIGÉES -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="studiosdb-card hover:scale-105 cursor-pointer group">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Total Attributions</p>
                    <p class="text-3xl font-bold text-white group-hover:text-orange-400 transition-colors">
                        {{ $stats['total_attributions'] ?? 0 }}
                    </p>
                    <p class="text-xs text-slate-500 mt-1">Ceintures attribuées</p>
                </div>
                <div class="w-14 h-14 bg-orange-500/20 border-orange-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="ceintures" size="w-7 h-7" color="text-orange-400" />
                </div>
            </div>
        </div>
        
        <div class="studiosdb-card">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Cette Semaine</p>
                    <p class="text-3xl font-bold text-emerald-400">{{ $stats['cette_semaine'] ?? 0 }}</p>
                    <p class="text-xs text-slate-500 mt-1">Nouvelles attributions</p>
                </div>
                <div class="w-14 h-14 bg-emerald-500/20 border-emerald-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="users" size="w-7 h-7" color="text-emerald-400" />
                </div>
            </div>
        </div>
        
        <div class="studiosdb-card">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Ce Mois</p>
                    <p class="text-3xl font-bold text-blue-400">{{ $stats['ce_mois'] ?? 0 }}</p>
                    <p class="text-xs text-slate-500 mt-1">Progressions</p>
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
                    <p class="text-3xl font-bold text-violet-400">{{ $stats['cette_annee'] ?? 0 }}</p>
                    <p class="text-xs text-slate-500 mt-1">Total année</p>
                </div>
                <div class="w-14 h-14 bg-violet-500/20 border-violet-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="presences" size="w-7 h-7" color="text-violet-400" />
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="studiosdb-card">
        <form method="GET" action="{{ route('admin.ceintures.index') }}" class="flex items-center space-x-4">
            <div class="flex-1 max-w-lg">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Rechercher un membre..." 
                       class="studiosdb-search w-full">
            </div>
            
            <select name="ceinture_id" class="studiosdb-select">
                <option value="">Toutes les ceintures</option>
                @foreach($ceintures as $ceinture)
                    <option value="{{ $ceinture->id }}" {{ request('ceinture_id') == $ceinture->id ? 'selected' : '' }}>
                        {{ $ceinture->nom }}
                    </option>
                @endforeach
            </select>
            
            <select name="periode" class="studiosdb-select">
                <option value="">Toute période</option>
                <option value="7j" {{ request('periode') == '7j' ? 'selected' : '' }}>7 derniers jours</option>
                <option value="30j" {{ request('periode') == '30j' ? 'selected' : '' }}>30 derniers jours</option>
                <option value="3m" {{ request('periode') == '3m' ? 'selected' : '' }}>3 derniers mois</option>
                <option value="6m" {{ request('periode') == '6m' ? 'selected' : '' }}>6 derniers mois</option>
            </select>
            
            <button type="submit" class="studiosdb-btn studiosdb-btn-ceintures">
                <x-admin-icon name="filter" size="w-4 h-4" />
                <span class="ml-2">Filtrer</span>
            </button>
        </form>
    </div>

    <!-- Table Attributions CORRIGÉE -->
    <div class="studiosdb-card">
        <div class="overflow-x-auto">
            <table class="studiosdb-table">
                <thead>
                    <tr>
                        <th>Membre</th>
                        <th>Ceinture Obtenue</th>
                        <th>Date Obtention</th>
                        <th>Examinateur</th>
                        <th>Statut</th>
                        <th>École</th>
                        <th class="w-20">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/30">
                    @forelse($attributions ?? [] as $attribution)
                        <tr class="hover:bg-slate-700/20 transition-colors">
                            <td>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full flex items-center justify-center">
                                        <span class="text-white font-medium">{{ substr($attribution->user->name ?? 'U', 0, 2) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-white font-medium">{{ $attribution->user->name ?? 'Utilisateur' }}</div>
                                        <div class="text-slate-400 text-sm">{{ $attribution->user->email ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center">
                                    <div class="w-6 h-6 rounded-full mr-3" style="background-color: {{ $attribution->ceinture->couleur ?? '#6B7280' }}"></div>
                                    <div>
                                        <div class="text-white font-medium">{{ $attribution->ceinture->nom ?? 'Ceinture' }}</div>
                                        <div class="text-slate-400 text-sm">Ordre {{ $attribution->ceinture->ordre ?? 0 }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-slate-300">
                                {{ $attribution->date_obtention ? \Carbon\Carbon::parse($attribution->date_obtention)->format('d/m/Y') : '-' }}
                            </td>
                            <td class="text-slate-300">
                                {{ $attribution->examinateur ?: ($attribution->instructeur->name ?? 'Non spécifié') }}
                            </td>
                            <td>
                                @if($attribution->valide)
                                    <span class="studiosdb-badge studiosdb-badge-success">Validée</span>
                                @else
                                    <span class="studiosdb-badge studiosdb-badge-warning">En attente</span>
                                @endif
                            </td>
                            <td class="text-slate-300">
                                {{ $attribution->ecole->nom ?? 'Non définie' }}
                            </td>
                            <td>
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.ceintures.edit', $attribution) }}" 
                                       class="text-blue-400 hover:text-blue-300" title="Modifier">
                                        <x-admin-icon name="edit" size="w-4 h-4" />
                                    </a>
                                    <form method="POST" action="{{ route('admin.ceintures.destroy', $attribution) }}" 
                                          onsubmit="return confirm('Supprimer cette attribution ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300" title="Supprimer">
                                            <x-admin-icon name="delete" size="w-4 h-4" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="text-center py-12">
                                    <x-admin-icon name="ceintures" size="w-16 h-16" color="text-slate-500 mx-auto mb-4" />
                                    <h3 class="text-lg font-medium text-slate-300 mb-2">Aucune attribution trouvée</h3>
                                    <p class="text-slate-500 mb-6">Commencez par attribuer des ceintures aux membres.</p>
                                    <a href="{{ route('admin.ceintures.create-masse') }}" 
                                       class="studiosdb-btn studiosdb-btn-ceintures">
                                        <x-admin-icon name="plus" size="w-4 h-4" />
                                        <span class="ml-2">Attribution en Masse</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if(isset($attributions) && method_exists($attributions, 'links'))
        <div class="flex justify-center">
            {{ $attributions->links() }}
        </div>
    @endif
</div>
@endsection
