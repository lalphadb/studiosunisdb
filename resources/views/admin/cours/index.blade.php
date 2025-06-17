@extends('layouts.admin')

@section('title', 'Gestion des Cours')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-white flex items-center">
                🥋 Gestion des Cours
            </h1>
            <p class="text-slate-400 mt-1">Administration des cours de karaté</p>
        </div>
        <a href="{{ route('admin.cours.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors flex items-center space-x-2">
            <span>➕</span>
            <span>Nouveau Cours</span>
        </a>
    </div>

    <!-- Filtres -->
    <div class="bg-slate-800 border border-slate-700 rounded-lg p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @if(auth()->user()->hasRole('superadmin'))
            <div>
                <select name="ecole_id" class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2">
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
                <select name="status" class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2">
                    <option value="">Tous les statuts</option>
                    <option value="actif" {{ request('status') == 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="inactif" {{ request('status') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                    <option value="complet" {{ request('status') == 'complet' ? 'selected' : '' }}>Complet</option>
                    <option value="annule" {{ request('status') == 'annule' ? 'selected' : '' }}>Annulé</option>
                </select>
            </div>

            <div>
                <select name="type_cours" class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2">
                    <option value="">Tous les types</option>
                    <option value="regulier" {{ request('type_cours') == 'regulier' ? 'selected' : '' }}>Régulier</option>
                    <option value="specialise" {{ request('type_cours') == 'specialise' ? 'selected' : '' }}>Spécialisé</option>
                    <option value="competition" {{ request('type_cours') == 'competition' ? 'selected' : '' }}>Compétition</option>
                    <option value="examen" {{ request('type_cours') == 'examen' ? 'selected' : '' }}>Examen</option>
                </select>
            </div>

            <div class="flex space-x-2">
                <input type="text" name="search" 
                       class="flex-1 bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2" 
                       placeholder="Rechercher..." value="{{ request('search') }}">
                <button type="submit" 
                        class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-lg transition-colors">
                    🔍
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des cours -->
    <div class="bg-slate-800 border border-slate-700 rounded-lg overflow-hidden">
        @if($cours->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-700 border-b border-slate-600">
                    <tr>
                        <th class="text-left px-6 py-4 text-white font-semibold">Nom du Cours</th>
                        @if(auth()->user()->hasRole('superadmin'))
                        <th class="text-left px-6 py-4 text-white font-semibold">École</th>
                        @endif
                        <th class="text-left px-6 py-4 text-white font-semibold">Type</th>
                        <th class="text-left px-6 py-4 text-white font-semibold">Horaires</th>
                        <th class="text-left px-6 py-4 text-white font-semibold">Inscriptions</th>
                        <th class="text-left px-6 py-4 text-white font-semibold">Instructeur</th>
                        <th class="text-left px-6 py-4 text-white font-semibold">Statut</th>
                        <th class="text-center px-6 py-4 text-white font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-600">
                    @foreach($cours as $cour)
                    <tr class="hover:bg-slate-700 transition-colors">
                        <td class="px-6 py-4">
                            <div>
                                <div class="font-semibold text-white">{{ $cour->nom }}</div>
                                @if($cour->description)
                                <div class="text-sm text-slate-400">{{ Str::limit($cour->description, 50) }}</div>
                                @endif
                            </div>
                        </td>
                        @if(auth()->user()->hasRole('superadmin'))
                        <td class="px-6 py-4 text-white">{{ $cour->ecole->nom ?? 'N/A' }}</td>
                        @endif
                        <td class="px-6 py-4">
                            <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-medium">
                                {{ ucfirst($cour->type_cours) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($cour->jour_semaine && $cour->heure_debut)
                                <div class="text-sm text-slate-300">
                                    {{ ucfirst($cour->jour_semaine) }}<br>
                                    {{ $cour->heure_debut }} - {{ $cour->heure_fin }}
                                </div>
                            @else
                                <span class="text-slate-500 text-sm">Non défini</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-white font-semibold">0/{{ $cour->capacite_max }}</div>
                            <div class="text-sm text-green-400">{{ $cour->capacite_max }} places</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($cour->instructeurPrincipal)
                                <span class="text-white">{{ $cour->instructeurPrincipal->name }}</span>
                            @else
                                <span class="text-slate-500">Non assigné</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusClasses = [
                                    'actif' => 'bg-green-600',
                                    'inactif' => 'bg-slate-600',
                                    'complet' => 'bg-yellow-600',
                                    'annule' => 'bg-red-600'
                                ];
                                $statusClass = $statusClasses[$cour->status] ?? 'bg-slate-600';
                            @endphp
                            <span class="{{ $statusClass }} text-white px-3 py-1 rounded-full text-sm font-medium">
                                {{ ucfirst($cour->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('admin.cours.show', $cour->id) }}" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm transition-colors" 
                                   title="Voir">
                                    👁️
                                </a>
                                
                                <a href="{{ route('admin.cours.edit', $cour->id) }}" 
                                   class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-2 rounded-lg text-sm transition-colors" 
                                   title="Modifier">
                                    ✏️
                                </a>
                                
                                <form method="POST" action="{{ route('admin.cours.destroy', $cour->id) }}" class="inline"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm transition-colors" 
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

        @if($cours->hasPages())
        <div class="px-6 py-4 border-t border-slate-600">
            {{ $cours->withQueryString()->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-12">
            <div class="text-6xl mb-4">📚</div>
            <h3 class="text-xl font-semibold text-white mb-2">Aucun cours trouvé</h3>
            <p class="text-slate-400 mb-6">Commencez par créer votre premier cours</p>
            <a href="{{ route('admin.cours.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                Créer le premier cours
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
