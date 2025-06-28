@extends('layouts.admin')

@section('title', 'Gestion des Séminaires')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-white">🎯 Gestion des Séminaires</h1>
        <a href="{{ route('admin.seminaires.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md">
            ➕ Nouveau Séminaire
        </a>
    </div>

    <div class="bg-gray-800 shadow rounded-lg border border-gray-700">
        <div class="px-6 py-4">
            @if($seminaires->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="border-b border-gray-700">
                                <th class="text-left py-3 px-4 text-gray-200">Nom</th>
                                <th class="text-left py-3 px-4 text-gray-200">École</th>
                                <th class="text-left py-3 px-4 text-gray-200">Date</th>
                                <th class="text-left py-3 px-4 text-gray-200">Lieu</th>
                                <th class="text-left py-3 px-4 text-gray-200">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($seminaires as $seminaire)
                            <tr class="border-b border-gray-700 hover:bg-gray-700">
                                <td class="py-3 px-4 text-white font-medium">{{ $seminaire->nom ?? 'N/A' }}</td>
                                <td class="py-3 px-4 text-gray-200">{{ $seminaire->ecole->nom ?? 'Toutes écoles' }}</td>
                                <td class="py-3 px-4 text-gray-200">{{ $seminaire->date_debut ?? 'N/A' }}</td>
                                <td class="py-3 px-4 text-gray-200">{{ $seminaire->lieu ?? 'N/A' }}</td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('admin.seminaires.show', $seminaire) }}" class="text-blue-400 hover:text-blue-300 mr-3">Voir</a>
                                    <a href="{{ route('admin.seminaires.edit', $seminaire) }}" class="text-yellow-400 hover:text-yellow-300">Éditer</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $seminaires->links() }}
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-6xl mb-4">🎯</div>
                    <p class="text-xl font-medium text-white mb-2">Aucun séminaire planifié</p>
                    <p class="text-gray-400 mb-6">Commencez par créer le premier séminaire</p>
                    <a href="{{ route('admin.seminaires.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md">
                        Créer le premier séminaire
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
