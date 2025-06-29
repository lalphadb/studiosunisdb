@extends('layouts.admin')

@section('title', 'Gestion des Paiements')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header avec statistiques -->
    <div class="mb-6">
        <x-module-header 
            module="paiement"
            title="Gestion des Paiements"
            subtitle="Administration des paiements du système"
            create-route="{{ route('admin.paiements.create') }}"
            create-text="Nouveau"
            create-permission="create,App\Models\Paiement"
        />
        
        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
            <div class="bg-yellow-600/20 border border-yellow-600/30 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-300 text-sm font-medium">En attente</p>
                        <p class="text-yellow-100 text-2xl font-bold">{{ $stats['en_attente'] }}</p>
                    </div>
                    <div class="text-yellow-400 text-3xl">⏳</div>
                </div>
                <p class="text-yellow-200 text-sm mt-2">
                    Total: ${{ number_format($stats['total_en_attente'], 2) }}
                </p>
            </div>
            
            <div class="bg-green-600/20 border border-green-600/30 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-300 text-sm font-medium">Payés</p>
                        <p class="text-green-100 text-2xl font-bold">{{ $stats['paye'] }}</p>
                    </div>
                    <div class="text-green-400 text-3xl">✅</div>
                </div>
            </div>
            
            <div class="bg-blue-600/20 border border-blue-600/30 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-300 text-sm font-medium">Actions rapides</p>
                        <p class="text-blue-100 text-sm">Gestion de masse</p>
                    </div>
                    <div class="text-blue-400 text-3xl">⚡</div>
                </div>
                <div class="mt-2 space-x-2">
                    @if($stats['en_attente'] > 0)
                        <a href="{{ route('admin.paiements.actions-masse') }}" 
                           class="text-blue-300 hover:text-blue-200 text-sm font-medium">
                            Traiter {{ $stats['en_attente'] }} paiements →
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <!-- Barre d'actions et filtres -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-4 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- Filtres -->
                <form method="GET" action="{{ route('admin.paiements.index') }}" class="flex gap-4 flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Rechercher..."
                           class="flex-1 bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2">
                    
                    <select name="statut" class="bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="paye" {{ request('statut') == 'paye' ? 'selected' : '' }}>Payé</option>
                        <option value="rembourse" {{ request('statut') == 'rembourse' ? 'selected' : '' }}>Remboursé</option>
                        <option value="annule" {{ request('statut') == 'annule' ? 'selected' : '' }}>Annulé</option>
                    </select>
                    
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        🔍
                    </button>
                </form>
                
                <!-- Actions de masse -->
                @if($stats['en_attente'] > 0)
                <div class="flex space-x-2">
                    <a href="{{ route('admin.paiements.validation-rapide') }}" 
                       class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm">
                        🚀 Validation Rapide
                    </a>
                    <a href="{{ route('admin.paiements.actions-masse') }}" 
                       class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm">
                        ⚡ Actions de Masse
                    </a>
                </div>
                @endif
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
                                <th class="text-left py-3 px-4 text-slate-300 font-semibold">Méthode</th>
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
                                    <span class="text-green-400 font-bold text-lg">${{ number_format($paiement->montant ?? 0, 2) }}</span>
                                </td>
                                <td class="py-3 px-4">
                                    @php
                                        $statusColors = [
                                            'en_attente' => 'bg-yellow-600 text-yellow-100',
                                            'paye' => 'bg-green-600 text-green-100',
                                            'rembourse' => 'bg-blue-600 text-blue-100',
                                            'annule' => 'bg-red-600 text-red-100'
                                        ];
                                        $statusLabels = [
                                            'en_attente' => 'En attente',
                                            'paye' => 'Payé',
                                            'rembourse' => 'Remboursé',
                                            'annule' => 'Annulé'
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusColors[$paiement->statut] ?? 'bg-gray-600 text-gray-100' }}">
                                        {{ $statusLabels[$paiement->statut] ?? ucfirst($paiement->statut) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-slate-300">
                                    {{ $paiement->methode_paiement ?? 'Virement' }}
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
                                        <div class="text-slate-400 text-xs">Payé: {{ $paiement->date_paiement->format('d/m/Y') }}</div>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.paiements.show', $paiement) }}" 
                                           class="text-blue-400 hover:text-blue-300 text-sm">Voir</a>
                                        @if($paiement->statut === 'en_attente')
                                            <a href="{{ route('admin.paiements.edit', $paiement) }}" 
                                               class="text-yellow-400 hover:text-yellow-300 text-sm">Modifier</a>
                                        @endif
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
