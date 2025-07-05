@extends('layouts.admin')

@section('title', 'Gestion des Paiements')

@section('content')
<div class="space-y-6">
    <!-- Header Module Paiements -->
    <div class="gradient-paiements text-white p-8 rounded-2xl border border-amber-500/20 relative overflow-hidden backdrop-blur-sm studiosdb-fade-in">
        <div class="absolute inset-0 bg-gradient-to-br from-white/3 via-transparent to-white/2"></div>
        
        <div class="relative z-10 flex justify-between items-center">
            <div class="flex items-center space-x-6">
                <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur border border-white/20">
                    <x-admin-icon name="paiements" size="w-8 h-8" color="text-white" />
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white drop-shadow-sm">Gestion des Paiements</h1>
                    <p class="text-lg text-white/90 font-medium">Administration financière et transactions</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                @can('create', App\Models\Paiement::class)
                    <a href="{{ route('admin.paiements.create') }}" 
                       class="bg-white/15 hover:bg-white/25 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 flex items-center space-x-3 backdrop-blur border border-white/20">
                        <x-admin-icon name="plus" size="w-5 h-5" color="text-white" />
                        <span>Nouveau Paiement</span>
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Stats Cards Paiements -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="studiosdb-card hover:scale-105 cursor-pointer group">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Total Paiements</p>
                    <p class="text-3xl font-bold text-white group-hover:text-amber-400 transition-colors">{{ $paiements->total() ?? 0 }}</p>
                    <p class="text-xs text-slate-500 mt-1">Toutes transactions</p>
                </div>
                <div class="w-14 h-14 bg-amber-500/20 border-amber-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="paiements" size="w-7 h-7" color="text-amber-400" />
                </div>
            </div>
        </div>
        
        <div class="studiosdb-card">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Validés</p>
                    <p class="text-3xl font-bold text-emerald-400">{{ isset($paiements) ? $paiements->where('statut', 'valide')->count() : 0 }}</p>
                    <p class="text-xs text-slate-500 mt-1">Paiements confirmés</p>
                </div>
                <div class="w-14 h-14 bg-emerald-500/20 border-emerald-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="presences" size="w-7 h-7" color="text-emerald-400" />
                </div>
            </div>
        </div>
        
        <div class="studiosdb-card">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">En Attente</p>
                    <p class="text-3xl font-bold text-orange-400">{{ isset($paiements) ? $paiements->where('statut', 'en_attente')->count() : 0 }}</p>
                    <p class="text-xs text-slate-500 mt-1">À valider</p>
                </div>
                <div class="w-14 h-14 bg-orange-500/20 border-orange-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="stats" size="w-7 h-7" color="text-orange-400" />
                </div>
            </div>
        </div>
        
        <div class="studiosdb-card">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Montant Total</p>
                    <p class="text-3xl font-bold text-green-400">${{ isset($paiements) ? number_format($paiements->where('statut', 'valide')->sum('montant'), 0) : 0 }}</p>
                    <p class="text-xs text-slate-500 mt-1">Revenus validés</p>
                </div>
                <div class="w-14 h-14 bg-green-500/20 border-green-500/30 rounded-2xl flex items-center justify-center border">
                    <span class="text-green-400 text-lg font-bold">$</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Paiements -->
    <div class="studiosdb-card">
        <div class="flex items-center justify-between mb-6 pb-6 border-b border-slate-700/30">
            <div class="flex-1 max-w-lg">
                <div class="relative">
                    <input type="text" placeholder="Rechercher un paiement..." 
                           class="studiosdb-search w-full pl-12">
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 studiosdb-search-icon">
                        <x-admin-icon name="search" size="w-5 h-5" />
                    </div>
                </div>
            </div>
            
            <div class="flex items-center space-x-3 ml-6">
                <select class="studiosdb-select">
                    <option value="">Tous les paiements</option>
                    <option value="valide">Validés</option>
                    <option value="en_attente">En attente</option>
                    <option value="refuse">Refusés</option>
                </select>
                <button class="studiosdb-btn studiosdb-btn-paiements">
                    <x-admin-icon name="filter" size="w-4 h-4" />
                    <span class="ml-2">Filtrer</span>
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="studiosdb-table">
                <thead>
                    <tr>
                        <th class="w-4">
                            <input type="checkbox" class="w-4 h-4 text-amber-500 bg-slate-700/50 border-slate-600/50 rounded">
                        </th>
                        <th>Paiement</th>
                        <th>Utilisateur</th>
                        <th>Montant</th>
                        <th>Méthode</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th class="w-20">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/30">
                    @forelse($paiements ?? [] as $paiement)
                        <tr class="hover:bg-slate-700/20 transition-colors">
                            <td>
                                <input type="checkbox" class="w-4 h-4 text-amber-500 bg-slate-700/50 border-slate-600/50 rounded">
                            </td>
                            <td>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full flex items-center justify-center">
                                        <span class="text-white font-bold">$</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-white font-medium">#{{ $paiement->id ?? '1001' }}</div>
                                        <div class="text-slate-400 text-sm">{{ $paiement->description ?? 'Inscription cours' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($paiement->user ?? null)
                                    <span class="text-blue-400">{{ $paiement->user->name }}</span>
                                @else
                                    <span class="text-slate-500">Utilisateur supprimé</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-green-400 font-bold">${{ number_format($paiement->montant ?? 150, 2) }}</span>
                            </td>
                            <td>
                                <span class="studiosdb-badge studiosdb-badge-paiements">{{ $paiement->methode ?? 'Carte' }}</span>
                            </td>
                            <td>
                                @if(($paiement->statut ?? 'valide') == 'valide')
                                    <span class="studiosdb-badge studiosdb-badge-validated">Validé</span>
                                @elseif(($paiement->statut ?? 'en_attente') == 'en_attente')
                                    <span class="studiosdb-badge studiosdb-badge-pending">En attente</span>
                                @else
                                    <span class="studiosdb-badge studiosdb-badge-expired">Refusé</span>
                                @endif
                            </td>
                            <td class="text-slate-400">
                                {{ isset($paiement->created_at) ? $paiement->created_at->format('d/m/Y') : now()->format('d/m/Y') }}
                            </td>
                            <td>
                                <x-actions-dropdown :model="$paiement" module="paiements" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <x-empty-state
                                    icon="paiements"
                                    title="Aucun paiement trouvé"
                                    description="Les paiements apparaîtront ici une fois les premières transactions effectuées."
                                    action-label="Nouveau paiement"
                                    :action-route="route('admin.paiements.create')"
                                    action-color="amber"
                                />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if(isset($paiements) && method_exists($paiements, 'links'))
        <div class="flex justify-center">
            {{ $paiements->links() }}
        </div>
    @endif
</div>
@endsection
