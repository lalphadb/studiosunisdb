@extends('layouts.admin')

@section('title', 'Gestion des Écoles')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-white flex items-center">
                🏫 Gestion des Écoles
            </h1>
            <p class="text-slate-400 mt-1">Réseau Studios Unis du Québec</p>
        </div>
        @can('create-ecole')
        <a href="{{ route('admin.ecoles.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors flex items-center space-x-2">
            <span>➕</span>
            <span>Nouvelle École</span>
        </a>
        @endcan
    </div>

    <!-- Liste des écoles -->
    <div class="bg-slate-800 border border-slate-700 rounded-lg overflow-hidden">
        @if($ecoles->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-700 border-b border-slate-600">
                    <tr>
                        <th class="text-left px-6 py-4 text-white font-semibold">École</th>
                        <th class="text-left px-6 py-4 text-white font-semibold">Localisation</th>
                        <th class="text-left px-6 py-4 text-white font-semibold">Directeur</th>
                        <th class="text-left px-6 py-4 text-white font-semibold">Membres</th>
                        <th class="text-left px-6 py-4 text-white font-semibold">Statut</th>
                        <th class="text-center px-6 py-4 text-white font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-600">
                    @foreach($ecoles as $ecole)
                    <tr class="hover:bg-slate-700 transition-colors">
                        <td class="px-6 py-4">
                            <div>
                                <div class="font-semibold text-white text-lg">{{ $ecole->nom }}</div>
                                <div class="text-slate-400">{{ $ecole->email }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-white">{{ $ecole->ville }}</div>
                            <div class="text-slate-400">{{ $ecole->code_postal }}</div>
                        </td>
                        <td class="px-6 py-4 text-white">{{ $ecole->directeur }}</td>
                        <td class="px-6 py-4">
                            <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-medium">
                                {{ $ecole->membres_count ?? 0 }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusClasses = [
                                    'actif' => 'bg-green-600',
                                    'inactif' => 'bg-red-600'
                                ];
                                $statusClass = $statusClasses[$ecole->statut] ?? 'bg-slate-600';
                            @endphp
                            <span class="{{ $statusClass }} text-white px-3 py-1 rounded-full text-sm font-medium">
                                {{ ucfirst($ecole->statut) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center space-x-2">
                                @can('view-ecole')
                                <a href="{{ route('admin.ecoles.show', $ecole) }}" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm transition-colors" 
                                   title="Voir">
                                    👁️
                                </a>
                                @endcan
                                
                                @can('edit-ecole')
                                <a href="{{ route('admin.ecoles.edit', $ecole) }}" 
                                   class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-2 rounded-lg text-sm transition-colors" 
                                   title="Modifier">
                                    ✏️
                                </a>
                                @endcan
                                
                                @can('delete-ecole')
                                <form method="POST" action="{{ route('admin.ecoles.destroy', $ecole) }}" class="inline"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette école ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm transition-colors" 
                                            title="Supprimer">
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

        @if($ecoles->hasPages())
        <div class="px-6 py-4 border-t border-slate-600">
            {{ $ecoles->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-12">
            <div class="text-6xl mb-4">🏫</div>
            <h3 class="text-xl font-semibold text-white mb-2">Aucune école trouvée</h3>
            <p class="text-slate-400 mb-6">Commencez par créer votre première école</p>
            @can('create-ecole')
            <a href="{{ route('admin.ecoles.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                Créer la première école
            </a>
            @endcan
        </div>
        @endif
    </div>
</div>
@endsection
