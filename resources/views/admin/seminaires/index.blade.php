@extends('layouts.admin')

@section('title', 'Gestion Séminaires')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h1 class="text-3xl font-bold text-white">🥋 Gestion des Séminaires</h1>
                <p class="mt-1 text-sm text-gray-300">{{ $seminaires->total() }} séminaires planifiés</p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('admin.seminaires.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Nouveau Séminaire
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-md bg-green-50 p-4 mb-6">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-slate-800 border border-slate-700 rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-slate-600">
                <thead class="bg-slate-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Séminaire</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Intervenant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Inscriptions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Prix</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Statut</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-slate-800 divide-y divide-slate-600">
                    @forelse($seminaires as $seminaire)
                    <tr class="hover:bg-slate-700 transition duration-150">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-white">{{ $seminaire->nom }}</div>
                            <div class="text-sm text-gray-400">{{ ucfirst(str_replace('_', ' ', $seminaire->type_seminaire ?? '')) }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-white">{{ $seminaire->intervenant ?? '' }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-white">{{ $seminaire->date_debut->format('d/m/Y') }}</div>
                            <div class="text-sm text-gray-400">{{ $seminaire->lieu }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-white">{{ $seminaire->inscriptions->count() }}/{{ $seminaire->capacite_max }}</td>
                        <td class="px-6 py-4 text-sm text-white">{{ number_format($seminaire->prix, 2) }} $</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                {{ ucfirst($seminaire->statut) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-3">
                                <a href="{{ route('admin.seminaires.inscriptions', $seminaire) }}" 
                                   class="text-blue-400 hover:text-blue-300 text-lg" title="Inscriptions">
                                    👥
                                </a>
                                <a href="{{ route('admin.seminaires.edit', $seminaire) }}" 
                                   class="text-yellow-400 hover:text-yellow-300 text-lg" title="Modifier">
                                    ✏️
                                </a>
                                <form method="POST" action="{{ route('admin.seminaires.destroy', $seminaire) }}" 
                                      class="inline" onsubmit="return confirm('Supprimer ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 text-lg" title="Supprimer">🗑️</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">Aucun séminaire trouvé</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
