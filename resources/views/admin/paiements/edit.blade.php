@extends('layouts.admin')

@section('title', 'Modifier Paiement')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-white">✏️ Modifier Paiement</h1>
            <p class="text-gray-400">Paiement de {{ $paiement->user->name ?? 'N/A' }}</p>
        </div>
        <a href="{{ route('admin.paiements.show', $paiement) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
            ← Retour
        </a>
    </div>

    <!-- Formulaire -->
    <div class="bg-gray-800 shadow rounded-lg border border-gray-700">
        <div class="px-6 py-4">
            <form method="POST" action="{{ route('admin.paiements.update', $paiement) }}">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Utilisateur (lecture seule) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Utilisateur</label>
                        <div class="bg-gray-900 px-3 py-2 rounded border border-gray-600 text-gray-400">
                            {{ $paiement->user->name ?? 'N/A' }}
                        </div>
                    </div>

                    <!-- Montant -->
                    <div>
                        <label for="montant" class="block text-sm font-medium text-gray-300 mb-2">
                            Montant ($) <span class="text-red-400">*</span>
                        </label>
                        <input type="number" step="0.01" name="montant" id="montant" required
                               value="{{ old('montant', $paiement->montant) }}"
                               class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                        @error('montant')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type de paiement -->
                    <div>
                        <label for="type_paiement" class="block text-sm font-medium text-gray-300 mb-2">
                            Type de paiement <span class="text-red-400">*</span>
                        </label>
                        <select name="type_paiement" id="type_paiement" required
                                class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                            <option value="cotisation" {{ old('type_paiement', $paiement->type_paiement) == 'cotisation' ? 'selected' : '' }}>Cotisation</option>
                            <option value="cours" {{ old('type_paiement', $paiement->type_paiement) == 'cours' ? 'selected' : '' }}>Cours</option>
                            <option value="seminaire" {{ old('type_paiement', $paiement->type_paiement) == 'seminaire' ? 'selected' : '' }}>Séminaire</option>
                            <option value="equipement" {{ old('type_paiement', $paiement->type_paiement) == 'equipement' ? 'selected' : '' }}>Équipement</option>
                            <option value="autre" {{ old('type_paiement', $paiement->type_paiement) == 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('type_paiement')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Statut -->
                    <div>
                        <label for="statut" class="block text-sm font-medium text-gray-300 mb-2">
                            Statut <span class="text-red-400">*</span>
                        </label>
                        <select name="statut" id="statut" required
                                class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                            <option value="pending" {{ old('statut', $paiement->statut) == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="completed" {{ old('statut', $paiement->statut) == 'completed' ? 'selected' : '' }}>Complété</option>
                            <option value="failed" {{ old('statut', $paiement->statut) == 'failed' ? 'selected' : '' }}>Échoué</option>
                            <option value="refunded" {{ old('statut', $paiement->statut) == 'refunded' ? 'selected' : '' }}>Remboursé</option>
                        </select>
                        @error('statut')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Méthode de paiement -->
                    <div>
                        <label for="methode_paiement" class="block text-sm font-medium text-gray-300 mb-2">
                            Méthode de paiement <span class="text-red-400">*</span>
                        </label>
                        <select name="methode_paiement" id="methode_paiement" required
                                class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                            <option value="especes" {{ old('methode_paiement', $paiement->methode_paiement) == 'especes' ? 'selected' : '' }}>Espèces</option>
                            <option value="carte" {{ old('methode_paiement', $paiement->methode_paiement) == 'carte' ? 'selected' : '' }}>Carte</option>
                            <option value="virement" {{ old('methode_paiement', $paiement->methode_paiement) == 'virement' ? 'selected' : '' }}>Virement</option>
                            <option value="cheque" {{ old('methode_paiement', $paiement->methode_paiement) == 'cheque' ? 'selected' : '' }}>Chèque</option>
                        </select>
                        @error('methode_paiement')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Référence -->
                    <div>
                        <label for="reference" class="block text-sm font-medium text-gray-300 mb-2">
                            Référence
                        </label>
                        <input type="text" name="reference" id="reference"
                               value="{{ old('reference', $paiement->reference) }}"
                               placeholder="Référence du paiement"
                               class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                        @error('reference')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Notes -->
                <div class="mt-6">
                    <label for="notes" class="block text-sm font-medium text-gray-300 mb-2">
                        Notes
                    </label>
                    <textarea name="notes" id="notes" rows="3"
                              placeholder="Notes sur le paiement..."
                              class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">{{ old('notes', $paiement->notes) }}</textarea>
                    @error('notes')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-700">
                    <a href="{{ route('admin.paiements.show', $paiement) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                        ❌ Annuler
                    </a>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                        ✅ Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
