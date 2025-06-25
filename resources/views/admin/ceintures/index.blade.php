@extends('layouts.admin')
@section('title', 'Suivi Progression Ceintures')

@section('content')
<div class="space-y-6">
    <!-- Header avec actions - DÉGRADÉ ORANGE COMME STANDARD -->
    <div class="bg-gradient-to-r from-yellow-500 to-orange-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                    Suivi Progression Ceintures
                </h1>
                <p class="text-yellow-100 text-lg">Suivi des attributions de ceintures de votre école</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.ceintures.create') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Attribution Individuelle
                </a>
                <!-- BOUTON ATTRIBUTION EN MASSE AVEC DÉGRADÉ -->
                <a href="{{ route('admin.ceintures.create-masse') }}" 
                   class="bg-gradient-to-r from-white to-yellow-50 text-orange-600 hover:from-yellow-50 hover:to-orange-50 px-6 py-3 rounded-lg font-medium transition duration-200 flex items-center shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Attribution en Masse
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques avec dégradés colorés -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-yellow-500 to-orange-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-white">{{ $stats['total_attributions'] }}</div>
                    <div class="text-sm text-slate-400">Total Attributions</div>
                </div>
            </div>
        </div>
        
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-green-500 to-emerald-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-white">{{ $stats['cette_semaine'] }}</div>
                    <div class="text-sm text-slate-400">Cette Semaine</div>
                </div>
            </div>
        </div>
        
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-white">{{ $stats['ce_mois'] }}</div>
                    <div class="text-sm text-slate-400">Ce Mois</div>
                </div>
            </div>
        </div>
        
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-pink-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-white">{{ $stats['cette_annee'] }}</div>
                    <div class="text-sm text-slate-400">Cette Année</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Recherche membre -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Rechercher membre</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Nom du membre..."
                       class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-yellow-500">
            </div>

            <!-- Filtre ceinture -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Ceinture</label>
                <select name="ceinture_id" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    <option value="">Toutes les ceintures</option>
                    @foreach($ceintures as $ceinture)
                    <option value="{{ $ceinture->id }}" {{ request('ceinture_id') == $ceinture->id ? 'selected' : '' }}>
                        {{ $ceinture->nom }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Filtre période -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Période</label>
                <select name="periode" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    <option value="">Toutes les périodes</option>
                    <option value="7j" {{ request('periode') == '7j' ? 'selected' : '' }}>7 derniers jours</option>
                    <option value="30j" {{ request('periode') == '30j' ? 'selected' : '' }}>30 derniers jours</option>
                    <option value="3m" {{ request('periode') == '3m' ? 'selected' : '' }}>3 derniers mois</option>
                    <option value="6m" {{ request('periode') == '6m' ? 'selected' : '' }}>6 derniers mois</option>
                </select>
            </div>

            @if(auth()->user()->hasRole('superadmin'))
            <!-- Filtre école -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">École</label>
                <select name="ecole_id" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    <option value="">Toutes les écoles</option>
                    @foreach($ecoles as $ecole)
                    <option value="{{ $ecole->id }}" {{ request('ecole_id') == $ecole->id ? 'selected' : '' }}>
                        {{ $ecole->nom }}
                    </option>
                    @endforeach
                </select>
            </div>
            @endif

            <!-- Boutons -->
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-gradient-to-r from-yellow-500 to-orange-600 hover:from-yellow-600 hover:to-orange-700 text-white px-6 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Filtrer
                </button>
                <a href="{{ route('admin.ceintures.index') }}" class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-lg transition duration-200">
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Liste des attributions avec header dégradé orange -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="bg-gradient-to-r from-yellow-500 to-orange-600 px-6 py-4">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                </svg>
                Progression des Membres
            </h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-slate-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Membre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Ceinture</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Examinateur</th>
                        @if(auth()->user()->hasRole('superadmin'))
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">École</th>
                        @endif
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse($attributions as $attribution)
                    <tr class="hover:bg-slate-700/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-yellow-500 to-orange-600 flex items-center justify-center">
                                        <span class="text-white font-bold text-sm">{{ substr($attribution->user->name, 0, 2) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-white">{{ $attribution->user->name }}</div>
                                    <div class="text-sm text-slate-400">{{ $attribution->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $attribution->ceinture->couleur }}"></div>
                                <div>
                                    <div class="text-sm font-medium text-white">{{ $attribution->ceinture->nom }}</div>
                                    <div class="text-sm text-slate-400">Ordre {{ $attribution->ceinture->ordre }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-white">{{ $attribution->date_obtention->format('d/m/Y') }}</div>
                            <div class="text-sm text-slate-400">{{ $attribution->date_obtention->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-white">{{ $attribution->examinateur ?? 'Non renseigné' }}</div>
                            @if($attribution->examen_id)
                            <div class="text-xs text-blue-400">Examen groupé</div>
                            @endif
                        </td>
                        @if(auth()->user()->hasRole('superadmin'))
                        <td class="px-6 py-4">
                            <div class="text-sm text-white">{{ $attribution->user->ecole->nom ?? 'Non assignée' }}</div>
                            <div class="text-sm text-slate-400">{{ $attribution->user->ecole->ville ?? '' }}</div>
                        </td>
                        @endif
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                <!-- Bouton Modifier -->
                                <a href="{{ route('admin.ceintures.edit', $attribution) }}" 
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-br from-yellow-500 to-orange-600 hover:from-yellow-600 hover:to-orange-700 text-white transition-colors duration-200"
                                   title="Modifier">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                
                                <!-- Bouton Supprimer -->
                                <form method="POST" action="{{ route('admin.ceintures.destroy', $attribution) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-600 hover:bg-red-700 text-white transition-colors duration-200"
                                            title="Supprimer"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette attribution ?')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ auth()->user()->hasRole('superadmin') ? '6' : '5' }}" class="px-6 py-12 text-center">
                            <div class="text-slate-400">
                                <svg class="w-16 h-16 mx-auto text-slate-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-slate-300">Aucune attribution trouvée</h3>
                                <p class="mt-1 text-sm text-slate-500">Commencez par attribuer des ceintures à vos membres.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($attributions->hasPages())
        <div class="px-6 py-4 border-t border-slate-700">
            {{ $attributions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
