@extends('layouts.admin')

@section('title', 'Gestion des Ceintures')

@section('content')
<div class="admin-content">
    {{-- Header uniforme --}}
    <x-module-header 
        title="Gestion des Ceintures"
        subtitle="Suivi des progressions et attributions"
        icon="🥋"
        gradient="from-yellow-600 to-orange-600"
        :create-route="route('admin.ceintures.create')"
        create-text="Attribuer Ceinture" />

    {{-- Métriques --}}
    @php
        $metrics = [
            [
                'label' => 'Total progressions',
                'value' => $progressions->total(),
                'icon' => '🏆',
                'color' => '#f59e0b',
                'subtitle' => 'Toutes ceintures'
            ],
            [
                'label' => 'Validées',
                'value' => $progressions->where('valide', true)->count(),
                'icon' => '✅',
                'color' => '#10b981',
                'subtitle' => 'Confirmées'
            ],
            [
                'label' => 'En attente',
                'value' => $progressions->where('valide', false)->count(),
                'icon' => '⏳',
                'color' => '#f97316',
                'subtitle' => 'À valider'
            ],
            [
                'label' => 'Ce mois',
                'value' => $progressions->where('created_at', '>=', now()->startOfMonth())->count(),
                'icon' => '📅',
                'color' => '#8b5cf6',
                'subtitle' => 'Nouvelles'
            ]
        ];
    @endphp
    
    <x-metric-cards :metrics="$metrics" />

    {{-- Filtres --}}
    <div class="bg-gray-800 rounded-xl border border-gray-700 p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Rechercher</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Nom du membre..." 
                       class="form-input">
            </div>
            
            @if(auth()->user()->hasRole('superadmin'))
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">École</label>
                <select name="ecole_id" class="form-select">
                    <option value="">Toutes les écoles</option>
                    @foreach($ecoles as $ecole)
                        <option value="{{ $ecole->id }}" {{ request('ecole_id') == $ecole->id ? 'selected' : '' }}>
                            {{ $ecole->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Période</label>
                <select name="periode" class="form-select">
                    <option value="">Toutes les périodes</option>
                    <option value="mois" {{ request('periode') == 'mois' ? 'selected' : '' }}>Ce mois</option>
                    <option value="trimestre" {{ request('periode') == 'trimestre' ? 'selected' : '' }}>Ce trimestre</option>
                    <option value="annee" {{ request('periode') == 'annee' ? 'selected' : '' }}>Cette année</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    🔍 Filtrer
                </button>
                <a href="{{ route('admin.ceintures.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    ↻ Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Section Liste avec header violet comme le module cours --}}
    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
        {{-- Header section comme module cours --}}
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 p-4">
            <h3 class="text-lg font-bold text-white flex items-center">
                <span class="mr-2">📋</span>
                Liste des Progressions
            </h3>
        </div>
        
        {{-- Contenu liste --}}
        <div class="p-6">
            @if($progressions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Membre</th>
                                <th>Ceinture</th>
                                <th>Date</th>
                                <th>École</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($progressions as $progression)
                            <tr>
                                {{-- Membre --}}
                                <td>
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">
                                                {{ strtoupper(substr($progression->user->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-white">
                                                {{ $progression->user->name }}
                                            </div>
                                            <div class="text-sm text-gray-400">
                                                {{ $progression->user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Ceinture --}}
                                <td>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                        🥋 {{ $progression->ceinture->nom }}
                                    </span>
                                    <div class="text-xs text-gray-400 mt-1">
                                        Ordre {{ $progression->ceinture->ordre }}
                                    </div>
                                </td>

                                {{-- Date --}}
                                <td>
                                    <div class="text-sm text-white">
                                        {{ $progression->date_obtention->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $progression->date_obtention->diffForHumans() }}
                                    </div>
                                </td>

                                {{-- École --}}
                                <td class="text-gray-300">
                                    {{ $progression->user->ecole->nom ?? 'N/A' }}
                                </td>

                                {{-- Statut --}}
                                <td>
                                    @if($progression->valide)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            ✅ Validée
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            ⏳ En attente
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.ceintures.show', $progression) }}" 
                                           class="text-blue-400 hover:text-blue-300 transition-colors" title="Voir détails">
                                            👁️
                                        </a>
                                        
                                        @can('edit-ceinture')
                                            <a href="{{ route('admin.ceintures.edit', $progression) }}" 
                                               class="text-yellow-400 hover:text-yellow-300 transition-colors" title="Modifier">
                                                ✏️
                                            </a>
                                        @endcan

                                        @can('delete-ceinture')
                                            <form method="POST" action="{{ route('admin.ceintures.destroy', $progression) }}" 
                                                  class="inline"
                                                  onsubmit="return confirm('Supprimer cette attribution ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-300 transition-colors" title="Supprimer">
                                                    🗑️
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-6">
                    {{ $progressions->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">🥋</div>
                    <p class="text-xl font-medium text-white mb-2">Aucune progression trouvée</p>
                    <p class="text-gray-400 mb-6">Commencez par attribuer des ceintures aux membres</p>
                    @can('assign-ceintures')
                        <a href="{{ route('admin.ceintures.create') }}" 
                           class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                            Attribuer la première ceinture
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
