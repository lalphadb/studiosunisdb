@extends('layouts.admin')

@section('title', 'Gestion des Ceintures')

@section('content')
<div class="space-y-6">
    {{-- Header avec actions --}}
    <div class="bg-gradient-to-r from-yellow-600 to-orange-600 rounded-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">🥋 Gestion des Ceintures</h1>
                <p class="text-yellow-100 text-lg">Suivi des progressions et approbations</p>
            </div>
            <div class="flex space-x-3">
                @can('assign-ceintures')
                <a href="{{ route('admin.ceintures.create') }}" class="bg-white text-yellow-600 hover:bg-yellow-50 px-4 py-2 rounded-lg font-medium transition-colors">
                    ➕ Attribuer Ceinture
                </a>
                @endcan
            </div>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="bg-gray-800 rounded-lg shadow-md border border-gray-700 p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Rechercher membre</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Nom ou prénom..." 
                       class="w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-yellow-500 focus:ring-yellow-500">
            </div>
            
            @if(auth()->user()->hasRole('superadmin'))
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">École</label>
                <select name="ecole_id" class="w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-yellow-500 focus:ring-yellow-500">
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
                <label class="block text-sm font-medium text-gray-300 mb-2">Statut</label>
                <select name="statut" class="w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-yellow-500 focus:ring-yellow-500">
                    <option value="">Tous les statuts</option>
                    <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>⏳ En attente</option>
                    <option value="approuve" {{ request('statut') == 'approuve' ? 'selected' : '' }}>✅ Approuvé</option>
                    <option value="rejete" {{ request('statut') == 'rejete' ? 'selected' : '' }}>❌ Rejeté</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md font-medium">
                    🔍 Filtrer
                </button>
                <a href="{{ route('admin.ceintures.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md font-medium">
                    ↻ Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Liste des progressions - STYLE UNIFORME --}}
    <div class="bg-gray-800 rounded-lg shadow-md border border-gray-700">
        <div class="p-6 border-b border-gray-700">
            <h3 class="text-lg font-medium text-white">
                📋 Progressions de Ceintures ({{ $progressions->total() }} résultats)
            </h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Membre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Ceinture</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">École</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($progressions as $progression)
                    <tr class="hover:bg-gray-700 transition-colors">
                        {{-- Membre --}}
                        <td class="px-6 py-4 whitespace-nowrap">
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
                                        Membre #{{ $progression->user->id }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Ceinture --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $progression->ceinture->couleur_badge ?? 'bg-gray-200 text-gray-800 border-gray-400' }}">
                                {{ $progression->ceinture->emoji ?? '🥋' }} {{ $progression->ceinture->nom }}
                            </span>
                            <div class="text-xs text-gray-500 mt-1">
                                Niveau {{ $progression->ceinture->niveau }}
                            </div>
                        </td>

                        {{-- Date --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-white">
                                {{ $progression->date_obtention->format('d/m/Y') }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $progression->date_obtention->diffForHumans() }}
                            </div>
                        </td>

                        {{-- École --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-300">
                                {{ $progression->user->ecole->nom ?? 'N/A' }}
                            </div>
                        </td>

                        {{-- Statut --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(isset($progression->statut))
                                @if($progression->statut === 'approuve')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                                        ✅ Approuvé
                                    </span>
                                @elseif($progression->statut === 'en_attente')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                        ⏳ En attente
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-200">
                                        ❌ Rejeté
                                    </span>
                                @endif
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                    📋 Ancien
                                </span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex justify-center space-x-3">
                                {{-- Voir --}}
                                <a href="{{ route('admin.ceintures.show', $progression) }}" 
                                   class="text-blue-400 hover:text-blue-300 transition-colors text-lg" 
                                   title="Voir détails">
                                    👁️
                                </a>

                                {{-- Modifier --}}
                                @can('manage-ceintures')
                                <a href="{{ route('admin.ceintures.edit', $progression) }}" 
                                   class="text-yellow-400 hover:text-yellow-300 transition-colors text-lg" 
                                   title="Modifier">
                                    ✏️
                                </a>
                                @endcan

                                {{-- Boutons d'approbation/rejet --}}
                                @if(isset($progression->statut) && $progression->statut === 'en_attente' && auth()->user()->hasAnyRole(['superadmin', 'admin']))
                                    {{-- Approuver --}}
                                    <form method="POST" action="{{ route('admin.ceintures.approuver', $progression) }}" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="text-green-400 hover:text-green-300 transition-colors text-lg" 
                                                onclick="return confirm('Approuver cette progression de ceinture ?')" 
                                                title="Approuver">
                                            ✅
                                        </button>
                                    </form>

                                    {{-- Rejeter --}}
                                    <form method="POST" action="{{ route('admin.ceintures.rejeter', $progression) }}" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="text-red-400 hover:text-red-300 transition-colors text-lg" 
                                                onclick="return confirm('Rejeter cette progression de ceinture ?')" 
                                                title="Rejeter">
                                            ❌
                                        </button>
                                    </form>
                                @endif

                                {{-- Supprimer (superadmin seulement) --}}
                                @if(auth()->user()->hasRole('superadmin'))
                                <form method="POST" action="{{ route('admin.ceintures.destroy', $progression) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-500 hover:text-red-400 transition-colors text-lg" 
                                            onclick="return confirm('Supprimer définitivement cette progression ?')" 
                                            title="Supprimer">
                                        🗑️
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="text-6xl mb-4">🥋</div>
                            <p class="text-lg font-medium text-white">Aucune progression trouvée</p>
                            <p class="text-sm text-gray-400">Commencez par attribuer des ceintures aux membres</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($progressions->hasPages())
        <div class="px-6 py-4 border-t border-gray-700">
            {{ $progressions->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
