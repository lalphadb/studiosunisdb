@extends('layouts.admin')

@section('title', 'Gestion des Paiements')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-white">💰 Gestion des Paiements</h1>
        <a href="{{ route('admin.paiements.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
            ➕ Nouveau Paiement
        </a>
    </div>

    <div class="bg-gray-800 shadow rounded-lg border border-gray-700">
        <div class="px-6 py-4">
            @if($paiements->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="border-b border-gray-700">
                                <th class="text-left py-3 px-4 text-gray-200">Utilisateur</th>
                                <th class="text-left py-3 px-4 text-gray-200">Montant</th>
                                <th class="text-left py-3 px-4 text-gray-200">Type</th>
                                <th class="text-left py-3 px-4 text-gray-200">Statut</th>
                                <th class="text-left py-3 px-4 text-gray-200">Date</th>
                                <th class="text-left py-3 px-4 text-gray-200">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paiements as $paiement)
                            <tr class="border-b border-gray-700 hover:bg-gray-700">
                                <td class="py-3 px-4 text-white">{{ $paiement->user->name ?? 'N/A' }}</td>
                                <td class="py-3 px-4 text-green-400 font-bold">${{ $paiement->montant ?? '0' }}</td>
                                <td class="py-3 px-4 text-gray-200">{{ $paiement->type_paiement ?? 'N/A' }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 rounded text-xs {{ $paiement->statut === 'completed' ? 'bg-green-600' : 'bg-yellow-600' }} text-white">
                                        {{ $paiement->statut ?? 'pending' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-gray-200">{{ $paiement->created_at ? $paiement->created_at->format('d/m/Y') : 'N/A' }}</td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('admin.paiements.show', $paiement) }}" class="text-blue-400 hover:text-blue-300">Voir</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $paiements->links() }}
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-6xl mb-4">💰</div>
                    <p class="text-xl font-medium text-white mb-2">Aucun paiement enregistré</p>
                    <p class="text-gray-400 mb-6">Les paiements apparaîtront ici</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
