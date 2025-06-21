@extends('layouts.admin')

@section('title', 'Gestion des Ceintures')

@section('content')
<div class="admin-content">
    {{-- Header --}}
    <div class="admin-header">
        <div>
            <h1 class="admin-title">🥋 Gestion des Ceintures</h1>
            <p class="admin-subtitle">Suivi des progressions et attributions</p>
        </div>
        @can('assign-ceintures')
            <div class="admin-actions">
                <a href="{{ route('admin.ceintures.create') }}" class="btn btn-primary">
                    <span>➕</span>
                    Attribuer Ceinture
                </a>
            </div>
        @endcan
    </div>

    {{-- Filtres --}}
    <div class="admin-card mb-6">
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
                <button type="submit" class="btn btn-secondary">
                    🔍 Filtrer
                </button>
                <a href="{{ route('admin.ceintures.index') }}" class="btn btn-outline">
                    ↻ Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Liste des progressions --}}
    <div class="admin-card">
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
                                    <div class="w-10 h-10 bg-gray-600 rounded-full flex items-center justify-center">
                                        <span class="text-white font-medium text-sm">
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
                                <span class="badge badge-ceinture">
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
                                    <span class="badge badge-success">✅ Validée</span>
                                @else
                                    <span class="badge badge-warning">⏳ En attente</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td>
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.ceintures.show', $progression) }}" 
                                       class="btn btn-sm btn-info" title="Voir détails">
                                        Voir
                                    </a>
                                    
                                    @can('edit-ceinture')
                                        <a href="{{ route('admin.ceintures.edit', $progression) }}" 
                                           class="btn btn-sm btn-warning" title="Modifier">
                                            Modifier
                                        </a>
                                    @endcan

                                    @can('delete-ceinture')
                                        <form method="POST" action="{{ route('admin.ceintures.destroy', $progression) }}" 
                                              class="inline"
                                              onsubmit="return confirm('Supprimer cette attribution ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                Supprimer
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
                <div class="text-gray-400 text-6xl mb-4">🥋</div>
                <p class="text-gray-400 text-lg mb-4">Aucune progression trouvée</p>
                @can('assign-ceintures')
                    <a href="{{ route('admin.ceintures.create') }}" class="btn btn-primary">
                        Attribuer la première ceinture
                    </a>
                @endcan
            </div>
        @endif
    </div>
</div>
@endsection
