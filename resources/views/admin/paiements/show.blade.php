@extends('layouts.admin')

@section('title', 'Détails Paiement')

@section('content')
<div class="space-y-6">
    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-white">Détails du Paiement</h1>
            <a href="{{ route('admin.paiements.index') }}" class="px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg transition-colors">
                Retour à la liste
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-slate-400 text-sm font-medium mb-1">Référence</label>
                    <p class="text-white text-lg">{{ $paiement->reference_interne }}</p>
                </div>
                
                <div>
                    <label class="block text-slate-400 text-sm font-medium mb-1">Utilisateur</label>
                    <p class="text-white">{{ $paiement->user->name ?? 'N/A' }}</p>
                </div>
                
                <div>
                    <label class="block text-slate-400 text-sm font-medium mb-1">Montant</label>
                    <p class="text-emerald-400 text-xl font-bold">${{ number_format($paiement->montant, 2) }}</p>
                </div>
                
                <div>
                    <label class="block text-slate-400 text-sm font-medium mb-1">Motif</label>
                    <p class="text-white">{{ $paiement->motif_libelle ?? $paiement->motif }}</p>
                </div>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-slate-400 text-sm font-medium mb-1">Statut</label>
                    <span class="inline-block px-3 py-1 rounded-full text-sm {{ $paiement->statut_class ?? 'text-slate-400' }}">
                        {{ $paiement->statut_libelle ?? $paiement->statut }}
                    </span>
                </div>
                
                <div>
                    <label class="block text-slate-400 text-sm font-medium mb-1">Date de création</label>
                    <p class="text-white">{{ $paiement->created_at->format('d/m/Y à H:i') }}</p>
                </div>
                
                @if($paiement->description)
                <div>
                    <label class="block text-slate-400 text-sm font-medium mb-1">Description</label>
                    <p class="text-white">{{ $paiement->description }}</p>
                </div>
                @endif
            </div>
        </div>
        
        <div class="mt-6 pt-6 border-t border-slate-700">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.paiements.edit', $paiement) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    Modifier
                </a>
                <form method="POST" action="{{ route('admin.paiements.destroy', $paiement) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce paiement ?')">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
