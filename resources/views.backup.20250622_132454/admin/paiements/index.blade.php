@extends('layouts.admin')

@section('title', 'Gestion des Paiements')

@section('content')
<div class="min-h-screen bg-slate-900 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header avec statistiques -->
        <div class="mb-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold leading-7 text-white sm:text-3xl sm:truncate">
                        🏦 Gestion des Paiements
                    </h2>
                    <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                        <div class="mt-2 flex items-center text-sm text-slate-300">
                            Total ce mois: <span class="ml-1 font-semibold text-green-400">${{ number_format($stats['total_mois'], 2) }}</span>
                        </div>
                        <div class="mt-2 flex items-center text-sm text-slate-300">
                            En attente: <span class="ml-1 font-semibold text-yellow-400">{{ $stats['en_attente'] }}</span>
                        </div>
                        <div class="mt-2 flex items-center text-sm text-slate-300">
                            À valider: <span class="ml-1 font-semibold text-blue-400">{{ $stats['a_valider'] }}</span>
                        </div>
                        <div class="mt-2 flex items-center text-sm text-slate-300">
                            Validés ce mois: <span class="ml-1 font-semibold text-green-400">{{ $stats['valides_mois'] }}</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    @can('create-paiements')
                        <a href="{{ route('admin.paiements.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Nouveau Paiement
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-slate-800 rounded-lg shadow-lg p-6 mb-6">
            <form method="GET" action="{{ route('admin.paiements.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Recherche -->
                    <div>
                        <label for="recherche" class="block text-sm font-medium text-slate-300 mb-1">Recherche</label>
                        <input type="text" 
                               id="recherche" 
                               name="recherche" 
                               value="{{ request('recherche') }}"
                               placeholder="Référence, membre..."
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-md text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Statut -->
                    <div>
                        <label for="statut" class="block text-sm font-medium text-slate-300 mb-1">Statut</label>
                        <select id="statut" 
                                name="statut"
                                class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tous les statuts</option>
                            <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                            <option value="recu" {{ request('statut') == 'recu' ? 'selected' : '' }}>Reçu - À valider</option>
                            <option value="valide" {{ request('statut') == 'valide' ? 'selected' : '' }}>Validé</option>
                            <option value="rejete" {{ request('statut') == 'rejete' ? 'selected' : '' }}>Rejeté</option>
                            <option value="rembourse" {{ request('statut') == 'rembourse' ? 'selected' : '' }}>Remboursé</option>
                        </select>
                    </div>

                    <!-- Motif -->
                    <div>
                        <label for="motif" class="block text-sm font-medium text-slate-300 mb-1">Motif</label>
                        <select id="motif" 
                                name="motif"
                                class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tous les motifs</option>
                            <option value="session_automne" {{ request('motif') == 'session_automne' ? 'selected' : '' }}>Session Hiver</option>
                            <option value="session_printemps" {{ request('motif') == 'session_printemps' ? 'selected' : '' }}>Session Printemps</option>
                            <option value="session_ete" {{ request('motif') == 'session_ete' ? 'selected' : '' }}>Session Été</option>
                            <option value="session_hiver" {{ request('motif') == 'session_hiver' ? 'selected' : '' }}>Session Printemps</option>
                            <option value="session_ete" {{ request('motif') == 'session_ete' ? 'selected' : '' }}>Session Été</option>
                            <option value="seminaire" {{ request('motif') == 'seminaire' ? 'selected' : '' }}>Séminaire</option>
                            <option value="examen_ceinture" {{ request('motif') == 'examen_ceinture' ? 'selected' : '' }}>Examen ceinture</option>
                            <option value="equipement" {{ request('motif') == 'equipement' ? 'selected' : '' }}>Équipement</option>
                            <option value="autre" {{ request('motif') == 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>

                    <!-- École (SuperAdmin seulement) -->
                    @role('superadmin')
                    <div>
                        <label for="ecole_id" class="block text-sm font-medium text-slate-300 mb-1">École</label>
                        <select id="ecole_id" 
                                name="ecole_id"
                                class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Toutes les écoles</option>
                            @foreach($ecoles as $ecole)
                                <option value="{{ $ecole->id }}" {{ request('ecole_id') == $ecole->id ? 'selected' : '' }}>
                                    {{ $ecole->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endrole
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.paiements.index') }}" 
                       class="px-4 py-2 border border-slate-600 rounded-md text-slate-300 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200">
                        Réinitialiser
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200">
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Tableau des paiements -->
        <div class="bg-slate-800 rounded-lg shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-700">
                    <thead class="bg-slate-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Référence</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Membre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">École</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Motif</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-slate-800 divide-y divide-slate-700">
                        @forelse($paiements as $paiement)
                            <tr class="hover:bg-slate-700 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-white">{{ $paiement->reference_interne }}</div>
                                    @if($paiement->reference_interac)
                                        <div class="text-xs text-slate-400">Interac: {{ $paiement->reference_interac }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-white">{{ $paiement->user->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $paiement->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-300">{{ $paiement->ecole->nom }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-300">
                                        @switch($paiement->motif)
                                            @case('session_automne')
                                                🍂 Session Automne (Sep-Nov)
                                                @break
                                            @case('session_hiver')
                                                ❄️ Session Hiver (Déc-Fév)
                                                @break
                                            @case('session_printemps')
                                                🌸 Session Printemps (Mar-Mai)
                                                @break
                                            @case('session_ete')
                                                ☀️ Session Été (Jun-Aoû)
                                                @break
                                            @case('seminaire')
                                                🎓 Séminaire
                                                @break
                                            @case('examen_ceinture')
                                                🥋 Examen ceinture
                                                @break
                                            @case('equipement')
                                                👕 Équipement
                                                @break
                                            @default
                                                {{ ucfirst($paiement->motif) }}
                                        @endswitch
                                    </div>
                                    @if($paiement->description)
                                        <div class="text-xs text-slate-400">{{ Str::limit($paiement->description, 30) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-white">${{ number_format($paiement->montant, 2) }}</div>
                                    @if($paiement->frais > 0)
                                        <div class="text-xs text-slate-400">Frais: ${{ number_format($paiement->frais, 2) }}</div>
                                        <div class="text-xs text-green-400">Net: ${{ number_format($paiement->montant_net, 2) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $paiement->statut_badge }}">
                                        {{ $paiement->statut_text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-300">
                                    <div>{{ $paiement->created_at->format('d/m/Y') }}</div>
                                    <div class="text-xs text-slate-400">{{ $paiement->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('admin.paiements.show', $paiement) }}" 
                                           class="text-blue-400 hover:text-blue-300 transition-colors duration-200"
                                           title="Voir détails">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        
                                        @if($paiement->statut === 'en_attente')
                                            @can('edit-paiements')
                                                <a href="{{ route('admin.paiements.edit', $paiement) }}" 
                                                   class="text-yellow-400 hover:text-yellow-300 transition-colors duration-200"
                                                   title="Modifier">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </a>
                                            @endcan
                                        @endif

                                        @if($paiement->statut === 'valide')
                                            <a href="{{ route('admin.paiements.recu', $paiement) }}" 
                                               class="text-green-400 hover:text-green-300 transition-colors duration-200"
                                               title="Télécharger reçu">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="text-slate-400">
                                        <svg class="mx-auto h-12 w-12 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                        <p class="text-lg font-medium">Aucun paiement trouvé</p>
                                        <p class="text-sm">Les paiements apparaîtront ici une fois créés.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($paiements->hasPages())
                <div class="bg-slate-800 px-4 py-3 border-t border-slate-700">
                    {{ $paiements->withQueryString()->links() }}
                </div>
            @endif
        </div>

        <!-- Actions rapides -->
        <div class="mt-6 flex justify-between items-center">
            <div class="text-sm text-slate-400">
                Affichage de {{ $paiements->firstItem() ?? 0 }} à {{ $paiements->lastItem() ?? 0 }} sur {{ $paiements->total() }} paiements
            </div>
            
            <div class="flex space-x-3">
                @can('export-paiements')
                    <a href="{{ route('admin.paiements.export') }}" 
                       class="inline-flex items-center px-4 py-2 border border-slate-600 rounded-md text-sm font-medium text-slate-300 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Exporter Excel
                    </a>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
