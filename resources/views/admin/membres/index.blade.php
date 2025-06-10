@extends('layouts.admin')

@section('title', 'Gestion Membres')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h1 class="text-3xl font-bold text-white">
                    👥 Gestion des Membres
                </h1>
                <p class="mt-1 text-sm text-gray-300">
                    {{ $membres->total() }} membres
                    @cannot('manage-system')
                        de votre école
                    @else
                        dans le système
                    @endcannot
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                @can('viewAny', App\Models\Membre::class)
                <a href="{{ route('admin.membres.export', ['format' => 'excel']) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition">
                    📊 Export Excel
                </a>
                @endcan
                
                @can('create', App\Models\Membre::class)
                <a href="{{ route('admin.membres.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                    ➕ Nouveau Membre
                </a>
                @endcan
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-gray-800 rounded-lg shadow mb-6 border border-gray-700">
            <div class="p-6">
                <form method="GET" action="{{ route('admin.membres.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Recherche -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-300 mb-2">Rechercher</label>
                        <input type="text" 
                               name="search" 
                               id="search" 
                               value="{{ request('search') }}"
                               placeholder="Nom, prénom, email..."
                               class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- École (seulement pour superadmin) -->
                    @can('manage-system')
                    <div>
                        <label for="ecole_id" class="block text-sm font-medium text-gray-300 mb-2">École</label>
                        <select name="ecole_id" id="ecole_id" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Toutes les écoles</option>
                            @foreach($ecoles as $ecole)
                                <option value="{{ $ecole->id }}" {{ request('ecole_id') == $ecole->id ? 'selected' : '' }}>
                                    {{ $ecole->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endcan

                    <!-- Statut -->
                    <div>
                        <label for="statut" class="block text-sm font-medium text-gray-300 mb-2">Statut</label>
                        <select name="statut" id="statut" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Tous les statuts</option>
                            <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>✅ Actif</option>
                            <option value="inactif" {{ request('statut') == 'inactif' ? 'selected' : '' }}>❌ Inactif</option>
                            <option value="suspendu" {{ request('statut') == 'suspendu' ? 'selected' : '' }}>⏸️ Suspendu</option>
                        </select>
                    </div>

                    <!-- Actions filtres -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                            🔍 Filtrer
                        </button>
                        <a href="{{ route('admin.membres.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                            🔄 Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des membres -->
        <div class="bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-700">
            @if($membres->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-900">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Membre</th>
                                @can('manage-system')
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">École</th>
                                @endcan
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Contact</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Ceinture</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Statut</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Inscription</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            @foreach($membres as $membre)
                            <tr class="hover:bg-gray-700">
                                <!-- Membre -->
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gray-600 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-sm font-medium text-white">
                                                {{ strtoupper(substr($membre->prenom, 0, 1)) }}{{ strtoupper(substr($membre->nom, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-white">
                                                {{ $membre->prenom }} {{ $membre->nom }}
                                            </div>
                                            @if($membre->date_naissance)
                                                <div class="text-sm text-gray-400">
                                                    {{ $membre->date_naissance->age }} ans
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- École (seulement pour superadmin) -->
                                @can('manage-system')
                                <td class="px-4 py-4">
                                    <div class="text-sm text-white">{{ $membre->ecole->nom ?? 'N/A' }}</div>
                                    @if($membre->ecole)
                                        <div class="text-sm text-gray-400">{{ $membre->ecole->ville }}</div>
                                    @endif
                                </td>
                                @endcan

                                <!-- Contact -->
                                <td class="px-4 py-4">
                                    @if($membre->email)
                                        <div class="text-sm text-white">{{ $membre->email }}</div>
                                    @endif
                                    @if($membre->telephone)
                                        <div class="text-sm text-gray-400">{{ $membre->telephone }}</div>
                                    @endif
                                </td>

                                <!-- Ceinture -->
                                <td class="px-4 py-4">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-white text-gray-800 border">
                                        🥋 Ceinture Blanche
                                    </span>
                                </td>

                                <!-- Statut -->
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @switch($membre->statut)
                                        @case('actif')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">✅ Actif</span>
                                            @break
                                        @case('inactif')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">❌ Inactif</span>
                                            @break
                                        @case('suspendu')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">⏸️ Suspendu</span>
                                            @break
                                    @endswitch
                                </td>

                                <!-- Date inscription -->
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-white">
                                        {{ $membre->date_inscription ? $membre->date_inscription->format('d/m/Y') : 'N/A' }}
                                    </div>
                                    @if($membre->date_inscription)
                                        <div class="text-sm text-gray-400">
                                            {{ $membre->date_inscription->diffForHumans() }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        @can('view', $membre)
                                        <a href="{{ route('admin.membres.show', $membre) }}" 
                                           class="text-blue-400 hover:text-blue-300 text-lg"
                                           title="Voir profil">
                                            👁️
                                        </a>
                                        @endcan
                                        
                                        @can('update', $membre)
                                        <a href="{{ route('admin.membres.edit', $membre) }}" 
                                           class="text-green-400 hover:text-green-300 text-lg"
                                           title="Modifier">
                                            ✏️
                                        </a>
                                        @endcan
                                        
                                        @can('update', $membre)
                                        <button onclick="alert('Attribution ceinture - En développement')" 
                                                class="text-yellow-400 hover:text-yellow-300 text-lg"
                                                title="Attribuer ceinture">
                                            🥋
                                        </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-700">
                    {{ $membres->links() }}
                </div>
            @else
                <!-- État vide -->
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">👥</div>
                    <h3 class="text-lg font-medium text-white mb-2">Aucun membre trouvé</h3>
                    <p class="text-gray-400 mb-4">
                        @if(request()->hasAny(['search', 'ecole_id', 'statut']))
                            Aucun membre ne correspond aux critères de recherche.
                        @else
                            Commencez par ajouter votre premier membre.
                        @endif
                    </p>
                    @can('create', App\Models\Membre::class)
                    <a href="{{ route('admin.membres.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                        ➕ Ajouter Premier Membre
                    </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
