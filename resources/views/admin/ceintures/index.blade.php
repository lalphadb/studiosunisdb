@extends('layouts.admin')

@section('title', 'Gestion des Ceintures')

@section('content')
<div class="space-y-6">
    {{-- Header avec actions --}}
    <div class="bg-gradient-to-r from-yellow-600 to-orange-600 rounded-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">🥋 Gestion des Ceintures</h1>
                <p class="text-yellow-100 text-lg">Suivi des progressions et attributions</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.ceintures.dashboard') }}" class="bg-yellow-500 hover:bg-yellow-600 px-4 py-2 rounded-lg font-medium transition-colors">
                    📊 Dashboard
                </a>
                @can('assign-ceintures')
                <a href="{{ route('admin.ceintures.create') }}" class="bg-white text-yellow-600 hover:bg-yellow-50 px-4 py-2 rounded-lg font-medium transition-colors">
                    ➕ Attribuer Ceinture
                </a>
                @endcan
            </div>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher membre</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Nom ou prénom..." 
                       class="w-full rounded-md border-gray-300 focus:border-yellow-500 focus:ring-yellow-500">
            </div>
            
            @if(auth()->user()->hasRole('superadmin'))
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">École</label>
                <select name="ecole_id" class="w-full rounded-md border-gray-300 focus:border-yellow-500 focus:ring-yellow-500">
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
                <label class="block text-sm font-medium text-gray-700 mb-2">Ceinture</label>
                <select name="ceinture_id" class="w-full rounded-md border-gray-300 focus:border-yellow-500 focus:ring-yellow-500">
                    <option value="">Toutes les ceintures</option>
                    @foreach($ceintures as $ceinture)
                        <option value="{{ $ceinture->id }}" {{ request('ceinture_id') == $ceinture->id ? 'selected' : '' }}>
                            {{ $ceinture->nom }}
                        </option>
                    @endforeach
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

    {{-- Liste des progressions --}}
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                📋 Progressions de Ceintures ({{ $progressions->total() }} résultats)
            </h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Membre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ceinture</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Examinateur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scores</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($progressions as $progression)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $progression->membre->prenom }} {{ $progression->membre->nom }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        🏫 {{ $progression->membre->ecole->nom }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $progression->ceinture->couleur_badge }}">
                                {{ $progression->ceinture->nom }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $progression->date_obtention->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $progression->examinateur->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($progression->score_global)
                                <span class="font-medium">{{ number_format($progression->score_global, 1) }}/20</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($progression->statut === 'reussie')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    ✅ Réussie
                                </span>
                            @elseif($progression->statut === 'echouee')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    ❌ Échouée
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    ⏳ En attente
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('admin.ceintures.show', $progression) }}" class="text-blue-600 hover:text-blue-900">
                                👁️ Voir
                            </a>
                            @can('manage-ceintures')
                            <a href="{{ route('admin.ceintures.edit', $progression) }}" class="text-yellow-600 hover:text-yellow-900">
                                ✏️ Modifier
                            </a>
                            @endcan
                            @if($progression->statut === 'reussie')
                            <a href="{{ route('admin.ceintures.certificat', $progression) }}" class="text-green-600 hover:text-green-900">
                                📜 Certificat
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <div class="text-6xl mb-4">🤷‍♂️</div>
                            <p class="text-lg font-medium">Aucune progression trouvée</p>
                            <p class="text-sm">Commencez par attribuer des ceintures aux membres</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($progressions->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $progressions->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
