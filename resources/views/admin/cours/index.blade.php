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
                <a href="{{ route('admin.cours.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                    ➕ Nouveau Cours
                </a>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-white">{{ $stats['total_cours'] }}</div>
                    <div class="text-gray-400">Total Cours</div>
                </div>
            </div>
            
            <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-400">{{ $stats['cours_actifs'] }}</div>
                    <div class="text-gray-400">Cours Actifs</div>
                </div>
            </div>
            
            <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-400">{{ $stats['cours_complets'] }}</div>
                    <div class="text-gray-400">Cours Complets</div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-gray-800 rounded-lg shadow mb-6 border border-gray-700">
            <div class="p-6">
                <form method="GET" action="{{ route('admin.cours.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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

                    <!-- Statut -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-300 mb-2">Statut</label>
                        <select name="status" id="status" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Tous les statuts</option>
                            <option value="actif" {{ request('status') == 'actif' ? 'selected' : '' }}>✅ Actif</option>
                            <option value="inactif" {{ request('status') == 'inactif' ? 'selected' : '' }}>⏸️ Inactif</option>
                            <option value="complet" {{ request('status') == 'complet' ? 'selected' : '' }}>🔴 Complet</option>
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
                                        <div class="text-sm text-gray-400">{{ ucfirst($coursItem->type_cours ?? 'Non défini') }}</div>
                                        @if($coursItem->ecole)
                                            <div class="text-xs text-gray-500">{{ $coursItem->ecole->nom }}</div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Horaire -->
                                <td class="px-4 py-4">
                                    @if($coursItem->jour_semaine)
                                        <div class="text-sm text-white">{{ ucfirst($coursItem->jour_semaine) }}</div>
                                    @endif
                                    @if($coursItem->heure_debut && $coursItem->heure_fin)
                                        <div class="text-sm text-gray-400">
                                            {{ date('H:i', strtotime($coursItem->heure_debut)) }} - {{ date('H:i', strtotime($coursItem->heure_fin)) }}
                                        </div>
                                    @endif
                                    @if($coursItem->duree_minutes)
                                        <div class="text-xs text-gray-500">{{ $coursItem->duree_minutes }}min</div>
                                    @endif
                                </td>

                                <!-- Capacité -->
                                <td class="px-4 py-4">
                                    <div class="text-sm text-white">0/{{ $coursItem->capacite_max }}</div>
                                    <div class="w-full bg-gray-700 rounded-full h-2 mt-1">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: 0%"></div>
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1">0%</div>
                                </td>

                                <!-- Prix -->
                                <td class="px-4 py-4">
                                    @if($coursItem->prix_mensuel > 0)
                                        <div class="text-sm text-white">${{ number_format($coursItem->prix_mensuel, 2) }}/mois</div>
                                    @endif
                                    @if($coursItem->prix_session > 0)
                                        <div class="text-xs text-gray-400">${{ number_format($coursItem->prix_session, 2) }}/session</div>
                                    @endif
                                    @if($coursItem->prix_mensuel == 0 && $coursItem->prix_session == 0)
                                        <div class="text-sm text-gray-400">Prix à définir</div>
                                    @endif
                                </td>

                                <!-- Statut -->
                                <td class="px-4 py-4">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($coursItem->status == 'actif') bg-green-100 text-green-800
                                        @elseif($coursItem->status == 'complet') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($coursItem->status) }}
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <!-- Voir -->
                                        <a href="{{ route('admin.cours.show', $coursItem) }}" 
                                           class="text-blue-400 hover:text-blue-300 text-lg"
                                           title="Voir détails">
                                            👁️
                                        </a>
                                        
                                        <!-- Modifier -->
                                        <a href="{{ route('admin.cours.edit', $coursItem) }}" 
                                           class="text-yellow-400 hover:text-yellow-300 text-lg"
                                           title="Modifier">
                                            ✏️
                                        </a>
                                        
                                        <!-- Dupliquer -->
                                        <a href="{{ route('admin.cours.duplicate', $coursItem) }}" 
                                           class="text-purple-400 hover:text-purple-300 text-lg"
                                           title="Dupliquer cours">
                                            📋
                                        </a>
                                        
                                        <!-- Supprimer -->
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
                    <a href="{{ route('admin.cours.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                        ➕ Créer Premier Cours
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
