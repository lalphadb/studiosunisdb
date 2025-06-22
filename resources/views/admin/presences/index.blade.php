@extends('layouts.admin')

@section('title', 'Gestion des Présences')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-white">✅ Gestion des Présences</h1>
        <a href="{{ route('admin.presences.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
            ➕ Enregistrer Présence
        </a>
    </div>

    <div class="bg-gray-800 shadow rounded-lg border border-gray-700">
        <div class="px-6 py-4">
            @if($presences->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="border-b border-gray-700">
                                <th class="text-left py-3 px-4 text-gray-200">Utilisateur</th>
                                <th class="text-left py-3 px-4 text-gray-200">Cours</th>
                                <th class="text-left py-3 px-4 text-gray-200">Date</th>
                                <th class="text-left py-3 px-4 text-gray-200">Statut</th>
                                <th class="text-left py-3 px-4 text-gray-200">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($presences as $presence)
                            <tr class="border-b border-gray-700 hover:bg-gray-700">
                                <td class="py-3 px-4 text-white">{{ $presence->user->name ?? 'N/A' }}</td>
                                <td class="py-3 px-4 text-gray-200">{{ $presence->cours->nom ?? 'N/A' }}</td>
                                <td class="py-3 px-4 text-gray-200">{{ $presence->date_cours ?? 'N/A' }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 rounded text-xs {{ $presence->statut === 'present' ? 'bg-green-600' : 'bg-red-600' }} text-white">
                                        {{ $presence->statut ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('admin.presences.show', $presence) }}" class="text-blue-400 hover:text-blue-300 mr-3">Voir</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $presences->links() }}
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-6xl mb-4">✅</div>
                    <p class="text-xl font-medium text-white mb-2">Aucune présence enregistrée</p>
                    <p class="text-gray-400 mb-6">Commencez par enregistrer les présences aux cours</p>
                    <a href="{{ route('admin.presences.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                        Enregistrer la première présence
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
