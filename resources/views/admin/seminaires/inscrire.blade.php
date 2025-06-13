@extends('layouts.admin')

@section('title', 'Inscrire un membre - ' . $seminaire->nom)

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <div class="flex items-center">
                    <a href="{{ route('admin.seminaires.inscriptions', $seminaire) }}" 
                       class="text-gray-400 hover:text-white transition duration-150 mr-4">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-white">
                            ➕ Inscrire un membre
                        </h1>
                        <p class="mt-1 text-sm text-gray-300">
                            {{ $seminaire->nom }} - {{ $seminaire->date_debut->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations du séminaire -->
        <div class="bg-slate-800 border border-slate-700 rounded-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <h3 class="text-lg font-medium text-white mb-2">{{ $seminaire->nom }}</h3>
                    <p class="text-sm text-gray-300">{{ $seminaire->intervenant }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Date</p>
                    <p class="text-sm text-white">{{ $seminaire->date_debut->format('d/m/Y H:i') }}</p>
                    <p class="text-sm text-gray-400 mt-1">Lieu</p>
                    <p class="text-sm text-white">{{ $seminaire->lieu }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Prix</p>
                    <p class="text-lg font-semibold text-white">{{ number_format($seminaire->prix, 2) }} $</p>
                    <p class="text-sm text-gray-400 mt-1">Places restantes</p>
                    <p class="text-sm text-white">{{ $seminaire->capacite_max - $seminaire->inscriptions()->count() }}/{{ $seminaire->capacite_max }}</p>
                </div>
            </div>
        </div>

        <!-- Messages -->
        @if(session('success'))
            <div class="rounded-md bg-green-50 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-md bg-red-50 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Formulaire d'inscription -->
        <div class="bg-slate-800 border border-slate-700 rounded-lg overflow-hidden">
            <form method="POST" action="{{ route('admin.seminaires.inscriptions.store', $seminaire) }}" class="p-6">
                @csrf

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Sélection du membre -->
                    <div class="sm:col-span-2">
                        <label for="membre_id" class="block text-sm font-medium text-gray-300">
                            Sélectionner un membre <span class="text-red-400">*</span>
                        </label>
                        <select name="membre_id" 
                                id="membre_id" 
                                required
                                class="mt-1 block w-full bg-slate-700 border border-slate-600 rounded-md text-white text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Choisir un membre...</option>
                            @foreach($membres as $membre)
                                <option value="{{ $membre->id }}" {{ old('membre_id') == $membre->id ? 'selected' : '' }}>
                                    {{ $membre->nom }} {{ $membre->prenom }} - {{ $membre->ecole->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('membre_id')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-400">
                            {{ $membres->count() }} membres disponibles
                        </p>
                    </div>

                    <!-- Montant payé -->
                    <div>
                        <label for="montant_paye" class="block text-sm font-medium text-gray-300">
                            Montant payé ($)
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="number" 
                                   name="montant_paye" 
                                   id="montant_paye" 
                                   step="0.01"
                                   min="0"
                                   value="{{ old('montant_paye', $seminaire->prix) }}"
                                   class="block w-full bg-slate-700 border border-slate-600 rounded-md text-white text-sm focus:ring-blue-500 focus:border-blue-500 pr-12"
                                   placeholder="0.00">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-400 sm:text-sm">$</span>
                            </div>
                        </div>
                        @error('montant_paye')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-400">
                            Prix du séminaire : {{ number_format($seminaire->prix, 2) }} $
                        </p>
                    </div>

                    <!-- Bouton rapide pour le prix complet -->
                    <div class="flex items-end">
                        <button type="button" 
                                onclick="document.getElementById('montant_paye').value = '{{ $seminaire->prix }}'"
                                class="w-full px-4 py-2 border border-blue-600 rounded-md shadow-sm text-sm font-medium text-blue-400 bg-slate-700 hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                            💰 Prix complet ({{ number_format($seminaire->prix, 2) }} $)
                        </button>
                    </div>

                    <!-- Notes -->
                    <div class="sm:col-span-2">
                        <label for="notes_participant" class="block text-sm font-medium text-gray-300">
                            Notes (optionnel)
                        </label>
                        <textarea name="notes_participant" 
                                  id="notes_participant" 
                                  rows="3"
                                  class="mt-1 block w-full bg-slate-700 border border-slate-600 rounded-md text-white text-sm focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Commentaires, besoins spéciaux, etc.">{{ old('notes_participant') }}</textarea>
                        @error('notes_participant')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex items-center justify-end mt-8 pt-6 border-t border-slate-600 space-x-4">
                    <a href="{{ route('admin.seminaires.inscriptions', $seminaire) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-300 bg-slate-700 hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Inscrire le membre
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
