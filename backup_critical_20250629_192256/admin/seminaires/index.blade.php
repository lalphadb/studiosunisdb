@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header du module -->
    <x-module-header 
        module="seminaires"
        title="Gestion des Séminaires" 
        subtitle="Organisation et suivi des séminaires inter-écoles"
        create-route="{{ route('admin.seminaires.create') }}"
        create-permission="create,App\Models\Seminaire"
    />

    <!-- Section principale -->
    <div class="bg-slate-800 rounded-xl shadow-xl border border-slate-700 overflow-hidden mt-6">
        <!-- Barre de recherche et filtres -->
        <div class="p-6 border-b border-slate-700">
            <form method="GET" action="{{ route('admin.seminaires.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Recherche -->
                <div class="md:col-span-2">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Rechercher par titre, instructeur, lieu..."
                        aria-label="Rechercher des séminaires"
                        class="w-full bg-slate-700 border border-slate-600 text-white placeholder-slate-400 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                    >
                </div>

                <!-- Filtre type -->
                <div>
                    <select name="type" aria-label="Filtrer par type" class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500">
                        <option value="">Tous les types</option>
                        <option value="technique" {{ request('type') === 'technique' ? 'selected' : '' }}>Technique</option>
                        <option value="kata" {{ request('type') === 'kata' ? 'selected' : '' }}>Kata</option>
                        <option value="competition" {{ request('type') === 'competition' ? 'selected' : '' }}>Compétition</option>
                        <option value="arbitrage" {{ request('type') === 'arbitrage' ? 'selected' : '' }}>Arbitrage</option>
                    </select>
                </div>

                <!-- Filtre niveau -->
                <div>
                    <select name="niveau_requis" aria-label="Filtrer par niveau" class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500">
                        <option value="">Tous niveaux</option>
                        <option value="debutant" {{ request('niveau_requis') === 'debutant' ? 'selected' : '' }}>Débutant</option>
                        <option value="intermediaire" {{ request('niveau_requis') === 'intermediaire' ? 'selected' : '' }}>Intermédiaire</option>
                        <option value="avance" {{ request('niveau_requis') === 'avance' ? 'selected' : '' }}>Avancé</option>
                        <option value="tous_niveaux" {{ request('niveau_requis') === 'tous_niveaux' ? 'selected' : '' }}>Tous niveaux</option>
                    </select>
                </div>

                <!-- Bouton recherche -->
                <div>
                    <button type="submit" aria-label="Lancer la recherche" class="w-full bg-pink-600 hover:bg-pink-700 text-white rounded-lg px-4 py-2 font-medium transition-colors duration-200">
                        🔍 Rechercher
                    </button>
                </div>
            </form>
        </div>

        @if($seminaires->count() > 0)
            <!-- Table des séminaires -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-900">
                        <tr class="text-left">
                            <th class="px-6 py-4 text-xs font-medium text-slate-300 uppercase tracking-wider">Séminaire</th>
                            <th class="px-6 py-4 text-xs font-medium text-slate-300 uppercase tracking-wider">Date & Lieu</th>
                            <th class="px-6 py-4 text-xs font-medium text-slate-300 uppercase tracking-wider">Type & Niveau</th>
                            <th class="px-6 py-4 text-xs font-medium text-slate-300 uppercase tracking-wider">Prix</th>
                            <th class="px-6 py-4 text-xs font-medium text-slate-300 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-4 text-xs font-medium text-slate-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        @foreach($seminaires as $seminaire)
                            <tr class="hover:bg-slate-750 transition-colors duration-150">
                                <!-- Séminaire -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-pink-500 to-purple-600 flex items-center justify-center">
                                                <span class="text-white font-bold text-lg">🎯</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-white">{{ $seminaire->titre }}</div>
                                            <div class="text-sm text-slate-400">{{ $seminaire->instructeur }}</div>
                                        </div>
                                    </div>
                                </td>
                                <!-- Date & Lieu -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-white">{{ $seminaire->date_debut_formatee }}</div>
                                    <div class="text-sm text-slate-400">{{ $seminaire->heure_debut_formatee }} - {{ $seminaire->heure_fin_formatee }}</div>
                                    <div class="text-sm text-slate-400">{{ $seminaire->lieu }}</div>
                                </td>
                                <!-- Type & Niveau -->
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-pink-600 text-white">
                                        {{ $seminaire->type_text }}
                                    </span>
                                    <div class="text-xs text-slate-400 mt-1">{{ $seminaire->niveau_requis_text }}</div>
                                </td>
                                <!-- Prix -->
                                <td class="px-6 py-4">
                                    @if($seminaire->prix)
                                        <div class="text-sm text-white">${{ number_format($seminaire->prix, 2) }}</div>
                                    @else
                                        <div class="text-sm text-slate-400">Gratuit</div>
                                    @endif
                                </td>
                                <!-- Statut -->
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $seminaire->statut_badge }}">
                                        {{ $seminaire->statut_text }}
                                    </span>
                                    @if($seminaire->inscription_ouverte)
                                        <div class="text-xs text-green-400 mt-1">Inscriptions ouvertes</div>
                                    @else
                                        <div class="text-xs text-red-400 mt-1">Inscriptions fermées</div>
                                    @endif
                                </td>
                                <!-- Actions -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        @can('view', $seminaire)
                                            <a href="{{ route('admin.seminaires.show', $seminaire) }}" 
                                               aria-label="Voir le détail du séminaire"
                                               class="text-blue-400 hover:text-blue-300 transition-colors duration-200">
                                                👁️
                                            </a>
                                        @endcan
                                        @can('update', $seminaire)
                                            <a href="{{ route('admin.seminaires.edit', $seminaire) }}" 
                                               aria-label="Modifier le séminaire"
                                               class="text-yellow-400 hover:text-yellow-300 transition-colors duration-200">
                                                ✏️
                                            </a>
                                        @endcan
                                        @can('delete', $seminaire)
                                            <form method="POST" action="{{ route('admin.seminaires.destroy', $seminaire) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce séminaire ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" aria-label="Supprimer le séminaire" class="text-red-400 hover:text-red-300 transition-colors duration-200">
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

            <!-- Pagination -->
            <div class="px-6 py-4 bg-slate-750 border-t border-slate-700">
                {{ $seminaires->appends(request()->query())->links() }}
            </div>

        @else
            <!-- État vide -->
            <div class="text-center py-16">
                <div class="text-6xl mb-4">🎯</div>
                <h3 class="text-xl font-semibold text-white mb-2">Aucun séminaire trouvé</h3>
                <p class="text-slate-400 mb-6">
                    @if(request()->hasAny(['search', 'type', 'niveau_requis']))
                        Aucun séminaire ne correspond à vos critères de recherche.
                    @else
                        Commencez par organiser votre premier séminaire.
                    @endif
                </p>
                @can('create', App\Models\Seminaire::class)
                    <a href="{{ route('admin.seminaires.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-pink-600 hover:bg-pink-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <span class="mr-2">🎯</span>
                        Nouveau séminaire
                    </a>
                @endcan
            </div>
        @endif
    </div>
</div>
@endsection
