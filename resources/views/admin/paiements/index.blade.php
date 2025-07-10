@extends('layouts.admin')

@section('title', 'Gestion des Paiements')

@section('content')
<div class="space-y-6">
    <!-- Header Module -->
    <div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">Gestion des Paiements</h1>
                <p class="text-slate-300">Administration financière et transactions</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.paiements.create') }}" 
                   class="studiosdb-btn studiosdb-btn-primary">
                    <span class="ml-2">Nouveau Paiement</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Total Paiements</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-amber-500/20 border-amber-500/30 rounded-2xl flex items-center justify-center border">
                    <span class="text-amber-400 text-2xl">💰</span>
                </div>
            </div>
        </div>
        
        <div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden">
            <div class="flex-1">
                <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Validés</p>
                <p class="text-3xl font-bold text-emerald-400">{{ $stats['valides'] ?? 0 }}</p>
            </div>
        </div>
        
        <div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden">
            <div class="flex-1">
                <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">En Attente</p>
                <p class="text-3xl font-bold text-amber-400">{{ $stats['en_attente'] ?? 0 }}</p>
            </div>
        </div>
        
        <div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden">
            <div class="flex-1">
                <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Montant Total</p>
                <p class="text-3xl font-bold text-emerald-400">${{ number_format($stats['montant_total'] ?? 0, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Table Paiements -->
    <div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <min-w-full divide-y divide-gray-200 class="studiosdb-min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Utilisateur</th>
                        <th>Montant</th>
                        <th>Motif</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/30">
                    @forelse($paiements as $paiement)
                        <tr class="hover:bg-slate-700/20 transition-colors">
                            <td class="text-blue-400">{{ $paiement->reference_interne }}</td>
                            <td class="text-white">{{ $paiement->user->name ?? 'N/A' }}</td>
                            <td class="text-emerald-400">${{ number_format($paiement->montant, 2) }}</td>
                            <td class="text-slate-300">{{ $paiement->motif_libelle }}</td>
                            <td>
                                <span class="{{ $paiement->statut_class }}">
                                    {{ $paiement->statut_libelle }}
                                </span>
                            </td>
                            <td class="text-slate-300">{{ $paiement->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.paiements.show', $paiement) }}" class="text-blue-400 hover:text-blue-300 mr-2">Voir</a>
                                <a href="{{ route('admin.paiements.edit', $paiement) }}" class="text-amber-400 hover:text-amber-300">Modifier</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-slate-400 py-8">
                                Aucun paiement trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </min-w-full divide-y divide-gray-200>
        </div>
        
        <!-- Pagination -->
        @if($paiements->hasPages())
            <div class="mt-6">
                {{ $paiements->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
