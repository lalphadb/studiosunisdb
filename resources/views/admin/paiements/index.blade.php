@extends('layouts.admin')

@section('title', 'Gestion des Paiements')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <x-module-header 
        module="paiement"
        title="Gestion des Paiements"
        subtitle="Administration des paiements du système"
        create-route="{{ route('admin.paiements.create') }}"
        create-text="Nouveau"
        create-permission="create,App\Models\Paiement"
    />

    <div class="mt-6">
        <!-- Barre d'actions -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-4 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- Filtres -->
                <form method="GET" action="{{ route('admin.paiements.index') }}" class="flex gap-4 flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Rechercher..."
                           class="flex-1 bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2">
                    
                    <select name="statut" class="bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2">
                        <option value="">Tous</option>
                        <option value="attente" {{ request('statut') == 'attente' ? 'selected' : '' }}>En attente</option>
                        <option value="paye" {{ request('statut') == 'paye' ? 'selected' : '' }}>Payé</option>
                        <option value="rembourse" {{ request('statut') == 'rembourse' ? 'selected' : '' }}>Remboursé</option>
                        <option value="annule" {{ request('statut') == 'annule' ? 'selected' : '' }}>Annulé</option>
                    </select>
                    
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        🔍
                    </button>
                </form>
                
                <!-- Actions de masse -->
                <div class="flex space-x-2">
                    <a href="{{ route('admin.paiements.actions-masse') }}" 
                       class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm">
                        ⚡ Actions de Masse
                    </a>
                </div>
            </div>
        </div>

        <!-- Liste des paiements -->
        <div class="bg-slate-800 rounded-xl border border-slate-700">
            @if($paiements->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-900">
                            <tr class="border-b border-slate-700">
                                <th class="text-left py-3 px-4 text-slate-300 font-semibold">Utilisateur</th>
                                <th class="text-left py-3 px-4 text-slate-300 font-semibold">Montant</th>
                                <th class="text-left py-3 px-4 text-slate-300 font-semibold">Statut</th>
                                <th class="text-left py-3 px-4 text-slate-300 font-semibold">Référence</th>
                                <th class="text-left py-3 px-4 text-slate-300 font-semibold">Date</th>
                                <th class="text-left py-3 px-4 text-slate-300 font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            @foreach($paiements as $paiement)
                            <tr class="hover:bg-slate-700/50">
                                <td class="py-3 px-4">
                                    <div class="text-white font-medium">{{ $paiement->user->name ?? 'N/A' }}</div>
                                    <div class="text-slate-400 text-sm">{{ $paiement->user->email ?? '' }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="text-green-400 font-bold">${{ number_format($paiement->montant ?? 0, 2) }}</span>
                                </td>
                                <td class="py-3 px-4">
                                    @php
                                        $statusColors = [
                                            'attente' => 'bg-yellow-600 text-yellow-100',
                                            'paye' => 'bg-green-600 text-green-100',
                                            'rembourse' => 'bg-blue-600 text-blue-100',
                                            'annule' => 'bg-red-600 text-red-100'
                                        ];
                                        $statusLabels = [
                                            'attente' => 'En attente',
                                            'paye' => 'Payé',
                                            'rembourse' => 'Remboursé',
                                            'annule' => 'Annulé'
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusColors[$paiement->statut] ?? 'bg-gray-600 text-gray-100' }}">
                                        {{ $statusLabels[$paiement->statut] ?? ucfirst($paiement->statut) }}
                                    </span>
                                    
                                    <!-- Action rapide si en attente -->
                                    @if($paiement->statut === 'attente')
                                        <form method="POST" action="{{ route('admin.paiements.marquer-recu', $paiement) }}" class="inline-block mt-1">
                                            @csrf
                                            <input type="text" name="reference_externe" placeholder="Réf. virement" 
                                                   class="w-24 bg-slate-700 border border-slate-600 text-white rounded px-2 py-1 text-xs">
                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs ml-1">
                                                ✅ Reçu
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <div class="text-white text-sm">{{ $paiement->reference_interne ?? 'N/A' }}</div>
                                    @if($paiement->reference_externe)
                                        <div class="text-slate-400 text-xs">{{ $paiement->reference_externe }}</div>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-slate-300">
                                    {{ $paiement->created_at ? $paiement->created_at->format('d/m/Y') : 'N/A' }}
                                    @if($paiement->date_paiement)
                                        <div class="text-green-400 text-xs">Payé: {{ $paiement->date_paiement->format('d/m/Y') }}</div>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.paiements.show', $paiement) }}" 
                                           class="text-blue-400 hover:text-blue-300 text-sm">Voir</a>
                                        <a href="{{ route('admin.paiements.edit', $paiement) }}" 
                                           class="text-yellow-400 hover:text-yellow-300 text-sm">Modifier</a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="p-4 border-t border-slate-700">
                    {{ $paiements->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">💰</div>
                    <p class="text-xl font-medium text-white mb-2">Aucun paiement trouvé</p>
                    <p class="text-slate-400 mb-6">Les paiements apparaîtront ici</p>
                    <a href="{{ route('admin.paiements.create') }}" 
                       class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors">
                        <span class="mr-2">➕</span>
                        Créer le premier paiement
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
