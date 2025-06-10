@extends('layouts.admin')

@section('title', 'Gestion Cours')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h1 class="text-3xl font-bold text-white">
                    📚 Gestion des Cours
                </h1>
                <p class="mt-1 text-sm text-gray-300">
                    {{ $cours->total() }} cours au total
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                @can('create', App\Models\Cours::class)
                <a href="{{ route('admin.cours.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                    ➕ Nouveau Cours
                </a>
                @endcan
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-gray-800 rounded-lg shadow mb-6 border border-gray-700">
            <div class="p-6">
                <form method="GET" action="{{ route('admin.cours.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <!-- Recherche -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-300 mb-2">Rechercher</label>
                        <input type="text" 
                               name="search" 
                               id="search" 
                               value="{{ request('search') }}"
                               placeholder="Nom, description..."
                               class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- École (pour superadmin) -->
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
                        <label for="status" class="block text-sm font-medium text-gray-300 mb-2">Statut</label>
                        <select name="status" id="status" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Tous les statuts</option>
                            <option value="actif" {{ request('status') == 'actif' ? 'selected' : '' }}>✅ Actif</option>
                            <option value="inactif" {{ request('status') == 'inactif' ? 'selected' : '' }}>⏸️ Inactif</option>
                            <option value="complet" {{ request('status') == 'complet' ? 'selected' : '' }}>🔄 Complet</option>
                        </select>
                    </div>

                    <!-- Jour -->
                    <div>
                        <label for="jour_semaine" class="block text-sm font-medium text-gray-300 mb-2">Jour</label>
                        <select name="jour_semaine" id="jour_semaine" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Tous les jours</option>
                            <option value="lundi" {{ request('jour_semaine') == 'lundi' ? 'selected' : '' }}>Lundi</option>
                            <option value="mardi" {{ request('jour_semaine') == 'mardi' ? 'selected' : '' }}>Mardi</option>
                            <option value="mercredi" {{ request('jour_semaine') == 'mercredi' ? 'selected' : '' }}>Mercredi</option>
                            <option value="jeudi" {{ request('jour_semaine') == 'jeudi' ? 'selected' : '' }}>Jeudi</option>
                            <option value="vendredi" {{ request('jour_semaine') == 'vendredi' ? 'selected' : '' }}>Vendredi</option>
                            <option value="samedi" {{ request('jour_semaine') == 'samedi' ? 'selected' : '' }}>Samedi</option>
                            <option value="dimanche" {{ request('jour_semaine') == 'dimanche' ? 'selected' : '' }}>Dimanche</option>
                        </select>
                    </div>

                    <!-- Actions filtres -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                            🔍 Filtrer
                        </button>
                        <a href="{{ route('admin.cours.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                            🔄 Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des cours -->
        <div class="bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-700">
            @if($cours->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-900">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Cours</th>
                                @can('manage-system')
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">École</th>
                                @endcan
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Horaire</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Capacité</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Prix</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Statut</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            @foreach($cours as $coursItem)
                            <tr class="hover:bg-gray-700">
                                <!-- Cours -->
                                <td class="px-4 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-white">{{ $coursItem->nom }}</div>
                                        <div class="text-sm text-gray-400">{{ $coursItem->type_label }}</div>
                                        @if($coursItem->niveau_requis)
                                            <div class="text-xs text-gray-500">{{ $coursItem->niveau_requis }}</div>
                                        @endif
                                    </div>
                                </td>

                                <!-- École (pour superadmin) -->
                                @can('manage-system')
                                <td class="px-4 py-4">
                                    <div class="text-sm text-white">{{ $coursItem->ecole->nom }}</div>
                                    <div class="text-sm text-gray-400">{{ $coursItem->ecole->ville }}</div>
                                </td>
                                @endcan

                                <!-- Horaire -->
                                <td class="px-4 py-4">
                                    <div class="text-sm text-white">{{ $coursItem->jour_label }}</div>
                                    <div class="text-sm text-gray-400">{{ $coursItem->creneau }}</div>
                                    <div class="text-xs text-gray-500">{{ $coursItem->duree_minutes }}min</div>
                                </td>

                                <!-- Capacité -->
                                <td class="px-4 py-4">
                                    <div class="text-sm text-white">{{ $coursItem->inscriptions->count() }}/{{ $coursItem->capacite_max }}</div>
                                    <div class="w-full bg-gray-700 rounded-full h-2 mt-1">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $coursItem->taux_occupation }}%"></div>
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1">{{ $coursItem->taux_occupation }}%</div>
                                </td>

                                <!-- Prix -->
                                <td class="px-4 py-4">
                                    <div class="text-sm text-white">{{ number_format($coursItem->prix_mensuel, 2) }}$ /mois</div>
                                    @if($coursItem->age_min || $coursItem->age_max)
                                        <div class="text-xs text-gray-400">{{ $coursItem->age_range }}</div>
                                    @endif
                                </td>

                                <!-- Statut -->
                                <td class="px-4 py-4">
                                    {!! $coursItem->status_badge !!}
                                </td>

                                <!-- Actions -->
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        @can('view', $coursItem)
                                        <a href="{{ route('admin.cours.show', $coursItem) }}" 
                                           class="text-blue-400 hover:text-blue-300 text-lg"
                                           title="Voir détails">
                                            👁️
                                        </a>
                                        @endcan
                                        
                                        @can('update', $coursItem)
                                        <a href="{{ route('admin.cours.edit', $coursItem) }}" 
                                           class="text-green-400 hover:text-green-300 text-lg"
                                           title="Modifier">
                                            ✏️
                                        </a>
                                        @endcan
                                        
                                        @can('delete', $coursItem)
                                        @if($coursItem->inscriptions()->where('status', 'active')->count() === 0)
                                            <form action="{{ route('admin.cours.destroy', $coursItem) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-400 hover:text-red-300 text-lg"
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
                @if($cours->hasPages())
                <div class="px-6 py-4 border-t border-gray-700">
                    {{ $cours->links() }}
                </div>
                @endif
            @else
                <!-- État vide -->
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">📚</div>
                    <h3 class="text-lg font-medium text-white mb-2">Aucun cours trouvé</h3>
                    <p class="text-gray-400 mb-4">
                        @if(request()->hasAny(['search', 'status', 'jour_semaine']))
                            Aucun cours ne correspond aux critères de recherche.
                        @else
                            Commencez par créer votre premier cours.
                        @endif
                    </p>
                    @can('create', App\Models\Cours::class)
                    <a href="{{ route('admin.cours.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                        ➕ Créer Premier Cours
                    </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
