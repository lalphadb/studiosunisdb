@extends('layouts.admin')

@section('title', 'Gestion Écoles')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h1 class="text-3xl font-bold text-white">
                    🏢 @if(Gate::allows('manage-system')) Gestion des Écoles @else Mon École @endif
                </h1>
                <p class="mt-1 text-sm text-gray-300">
                    @if(Gate::allows('manage-system'))
                        {{ $ecoles->total() }} écoles dans le réseau Studios Unis
                    @else
                        Gestion de votre école "{{ auth()->user()->ecole->nom ?? 'École assignée' }}"
                    @endif
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                @can('create', App\Models\Ecole::class)
                <a href="{{ route('admin.ecoles.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                    ➕ Nouvelle École
                </a>
                @endcan
            </div>
        </div>

        <!-- Filtres (seulement pour superadmin avec plusieurs écoles) -->
        @if(Gate::allows('manage-system') && $ecoles->total() > 1)
        <div class="bg-gray-800 rounded-lg shadow mb-6 border border-gray-700">
            <div class="p-6">
                <form method="GET" action="{{ route('admin.ecoles.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Recherche -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-300 mb-2">Rechercher</label>
                        <input type="text" 
                               name="search" 
                               id="search" 
                               value="{{ request('search') }}"
                               placeholder="Nom école, ville..."
                               class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Statut -->
                    <div>
                        <label for="statut" class="block text-sm font-medium text-gray-300 mb-2">Statut</label>
                        <select name="statut" id="statut" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Tous les statuts</option>
                            <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>✅ Actif</option>
                            <option value="inactif" {{ request('statut') == 'inactif' ? 'selected' : '' }}>❌ Inactif</option>
                        </select>
                    </div>

                    <!-- Actions filtres -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                            🔍 Filtrer
                        </button>
                        <a href="{{ route('admin.ecoles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                            🔄 Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <!-- Liste des écoles -->
        <div class="bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-700">
            @if($ecoles->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-900">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">École</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Localisation</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Contact</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Capacité</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Statut</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            @foreach($ecoles as $ecole)
                            <tr class="hover:bg-gray-700">
                                <!-- École -->
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-white">{{ $ecole->nom }}</div>
                                        @if($ecole->directeur)
                                            <div class="text-sm text-gray-400">Dir: {{ $ecole->directeur }}</div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Localisation -->
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-white">{{ $ecole->ville ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-400">{{ $ecole->province ?? 'Quebec' }}</div>
                                </td>

                                <!-- Contact -->
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if($ecole->telephone)
                                        <div class="text-sm text-white">{{ $ecole->telephone }}</div>
                                    @endif
                                    @if($ecole->email)
                                        <div class="text-sm text-gray-400">{{ $ecole->email }}</div>
                                    @endif
                                </td>

                                <!-- Capacité -->
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-white">{{ $ecole->capacite_max ?? 100 }} membres</div>
                                    <div class="text-sm text-gray-400">{{ $ecole->membres()->count() }} inscrits</div>
                                </td>

                                <!-- Statut -->
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if($ecole->statut === 'actif')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">✅ Actif</span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">❌ Inactif</span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        @can('view', $ecole)
                                        <a href="{{ route('admin.ecoles.show', $ecole) }}" 
                                           class="text-blue-400 hover:text-blue-300"
                                           title="Voir détails">
                                            👁️
                                        </a>
                                        @endcan
                                        
                                        @can('update', $ecole)
                                        <a href="{{ route('admin.ecoles.edit', $ecole) }}" 
                                           class="text-green-400 hover:text-green-300"
                                           title="Modifier">
                                            ✏️
                                        </a>
                                        @endcan
                                        
                                        @can('delete', $ecole)
                                        @if($ecole->membres()->count() === 0)
                                            <form action="{{ route('admin.ecoles.destroy', $ecole) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette école ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-400 hover:text-red-300"
                                                        title="Supprimer">
                                                    🗑️
                                                </button>
                                            </form>
                                        @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($ecoles->hasPages())
                <div class="px-6 py-4 border-t border-gray-700">
                    {{ $ecoles->links() }}
                </div>
                @endif
            @else
                <!-- État vide -->
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">🏢</div>
                    <h3 class="text-lg font-medium text-white mb-2">Aucune école trouvée</h3>
                    <p class="text-gray-400 mb-4">
                        @if(request()->hasAny(['search', 'statut']))
                            Aucune école ne correspond aux critères de recherche.
                        @else
                            @if(Gate::allows('manage-system'))
                                Commencez par ajouter votre première école.
                            @else
                                Aucune école n'est assignée à votre compte.
                            @endif
                        @endif
                    </p>
                    @can('create', App\Models\Ecole::class)
                    <a href="{{ route('admin.ecoles.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                        ➕ Ajouter Première École
                    </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
