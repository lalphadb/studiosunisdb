@extends('layouts.admin')

@section('title', 'Modifier Paiement')

@section('content')
<div class="space-y-6">
    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700">
        <h1 class="text-2xl font-bold text-white mb-6">Modifier le Paiement</h1>
        
        <form method="POST" action="{{ route('admin.paiements.update', $paiement) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-slate-300 font-medium mb-2">Référence</label>
                    <input type="text" value="{{ $paiement->reference_interne }}" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-400" readonly>
                </div>
                
                <div>
                    <label class="block text-slate-300 font-medium mb-2">Utilisateur</label>
                    <input type="text" value="{{ $paiement->user->name ?? 'N/A' }}" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-400" readonly>
                </div>
                
                <div>
                    <label class="block text-slate-300 font-medium mb-2">Montant</label>
                    <input type="number" step="0.01" name="montant" value="{{ $paiement->montant }}" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white" required>
                    @error('montant')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-slate-300 font-medium mb-2">Statut</label>
                    <select name="statut" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white" required>
                        <option value="en_attente" {{ $paiement->statut === 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="valide" {{ $paiement->statut === 'valide' ? 'selected' : '' }}>Validé</option>
                        <option value="refuse" {{ $paiement->statut === 'refuse' ? 'selected' : '' }}>Refusé</option>
                    </select>
                    @error('statut')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div>
                <label class="block text-slate-300 font-medium mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white">{{ $paiement->description }}</textarea>
                @error('description')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex items-center space-x-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    Mettre à jour
                </button>
                <a href="{{ route('admin.paiements.show', $paiement) }}" class="px-6 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg transition-colors">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
