@extends('layouts.admin')

@section('title', 'Nouveau Paiement')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">💳 Nouveau Paiement</h2>
                <p class="text-slate-400">Créer un nouveau paiement</p>
            </div>
            <a href="{{ route('admin.paiements.index') }}" 
               class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-lg">
                ← Retour
            </a>
        </div>
    </div>

    <!-- Formulaire SIMPLE -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <form action="{{ route('admin.paiements.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Membre -->
            <div>
                <label for="user_id" class="block text-sm font-medium text-slate-300 mb-2">
                    Membre <span class="text-red-400">*</span>
                </label>
                <select id="user_id" name="user_id" required
                        class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="">Sélectionner un membre</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} - {{ $user->email }}
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Montant -->
            <div>
                <label for="montant" class="block text-sm font-medium text-slate-300 mb-2">
                    Montant (CAD) <span class="text-red-400">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-slate-400">$</span>
                    </div>
                    <input type="number" id="montant" name="montant" step="0.01" min="0.01"
                           value="{{ old('montant') }}" required
                           placeholder="0.00"
                           class="w-full pl-8 bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>
                @error('montant')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Statut -->
            <div>
                <label for="statut" class="block text-sm font-medium text-slate-300 mb-2">
                    Statut <span class="text-red-400">*</span>
                </label>
                <select id="statut" name="statut" required
                        class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="en_attente" {{ old('statut', 'en_attente') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="paye" {{ old('statut') == 'paye' ? 'selected' : '' }}>Payé</option>
                    <option value="rembourse" {{ old('statut') == 'rembourse' ? 'selected' : '' }}>Remboursé</option>
                    <option value="annule" {{ old('statut') == 'annule' ? 'selected' : '' }}>Annulé</option>
                </select>
                @error('statut')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Méthode de paiement -->
            <div>
                <label for="methode_paiement" class="block text-sm font-medium text-slate-300 mb-2">
                    Méthode de paiement
                </label>
                <select id="methode_paiement" name="methode_paiement"
                        class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="">Sélectionner</option>
                    <option value="especes" {{ old('methode_paiement') == 'especes' ? 'selected' : '' }}>Espèces</option>
                    <option value="carte" {{ old('methode_paiement') == 'carte' ? 'selected' : '' }}>Carte</option>
                    <option value="virement" {{ old('methode_paiement') == 'virement' ? 'selected' : '' }}>Virement</option>
                    <option value="cheque" {{ old('methode_paiement') == 'cheque' ? 'selected' : '' }}>Chèque</option>
                    <option value="interac" {{ old('methode_paiement') == 'interac' ? 'selected' : '' }}>Interac</option>
                </select>
                @error('methode_paiement')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-slate-300 mb-2">
                    Notes (optionnel)
                </label>
                <textarea id="notes" name="notes" rows="3"
                          placeholder="Commentaires, motif du paiement..."
                          class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 pt-4 border-t border-slate-700">
                <a href="{{ route('admin.paiements.index') }}" 
                   class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-2 rounded-lg transition-colors">
                    Annuler
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                    💾 Créer le paiement
                </button>
            </div>
        </form>
    </div>

    <!-- Info -->
    <div class="mt-4 bg-blue-900/20 border border-blue-800 rounded-lg p-4">
        <div class="flex items-center">
            <span class="text-blue-400 mr-2">💡</span>
            <div class="text-blue-200 text-sm">
                <p><strong>Info :</strong> La référence interne sera générée automatiquement si laissée vide.</p>
                <p>L'école sera automatiquement assignée selon le membre sélectionné.</p>
            </div>
        </div>
    </div>
</div>
@endsection
