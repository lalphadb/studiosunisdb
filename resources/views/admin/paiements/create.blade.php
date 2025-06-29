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

    <!-- Formulaire -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <form action="{{ route('admin.paiements.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                <!-- Type de paiement -->
                <div>
                    <label for="type_paiement" class="block text-sm font-medium text-slate-300 mb-2">
                        Type de paiement <span class="text-red-400">*</span>
                    </label>
                    <select id="type_paiement" name="type_paiement" required
                            class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner un type</option>
                        <option value="interac" {{ old('type_paiement', 'interac') == 'interac' ? 'selected' : '' }}>Interac</option>
                        <option value="especes" {{ old('type_paiement') == 'especes' ? 'selected' : '' }}>Espèces</option>
                        <option value="carte" {{ old('type_paiement') == 'carte' ? 'selected' : '' }}>Carte</option>
                        <option value="virement" {{ old('type_paiement') == 'virement' ? 'selected' : '' }}>Virement</option>
                        <option value="cheque" {{ old('type_paiement') == 'cheque' ? 'selected' : '' }}>Chèque</option>
                    </select>
                    @error('type_paiement')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Motif -->
                <div>
                    <label for="motif" class="block text-sm font-medium text-slate-300 mb-2">
                        Motif <span class="text-red-400">*</span>
                    </label>
                    <select id="motif" name="motif" required
                            class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner un motif</option>
                        <option value="session_automne" {{ old('motif') == 'session_automne' ? 'selected' : '' }}>Session Automne</option>
                        <option value="session_hiver" {{ old('motif') == 'session_hiver' ? 'selected' : '' }}>Session Hiver</option>
                        <option value="session_printemps" {{ old('motif') == 'session_printemps' ? 'selected' : '' }}>Session Printemps</option>
                        <option value="session_ete" {{ old('motif') == 'session_ete' ? 'selected' : '' }}>Session Été</option>
                        <option value="seminaire" {{ old('motif') == 'seminaire' ? 'selected' : '' }}>Séminaire</option>
                        <option value="examen_ceinture" {{ old('motif') == 'examen_ceinture' ? 'selected' : '' }}>Examen Ceinture</option>
                        <option value="equipement" {{ old('motif') == 'equipement' ? 'selected' : '' }}>Équipement</option>
                        <option value="autre" {{ old('motif') == 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('motif')
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
                        <option value="">Sélectionner un statut</option>
                        <option value="en_attente" {{ old('statut', 'en_attente') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="recu" {{ old('statut') == 'recu' ? 'selected' : '' }}>Reçu</option>
                        <option value="valide" {{ old('statut') == 'valide' ? 'selected' : '' }}>Validé</option>
                        <option value="rembourse" {{ old('statut') == 'rembourse' ? 'selected' : '' }}>Remboursé</option>
                        <option value="annule" {{ old('statut') == 'annule' ? 'selected' : '' }}>Annulé</option>
                    </select>
                    @error('statut')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                <!-- Frais -->
                <div>
                    <label for="frais" class="block text-sm font-medium text-slate-300 mb-2">
                        Frais (optionnel)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-slate-400">$</span>
                        </div>
                        <input type="number" id="frais" name="frais" step="0.01" min="0"
                               value="{{ old('frais', '0.00') }}"
                               placeholder="0.00"
                               class="w-full pl-8 bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    </div>
                    @error('frais')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Champs Interac (affichés conditionnellement) -->
            <div id="interac-fields" class="space-y-6" style="display: none;">
                <div class="border-t border-slate-600 pt-6">
                    <h3 class="text-lg font-medium text-slate-200 mb-4">📨 Informations Interac</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Email expéditeur -->
                        <div>
                            <label for="email_expediteur" class="block text-sm font-medium text-slate-300 mb-2">
                                Email expéditeur
                            </label>
                            <input type="email" id="email_expediteur" name="email_expediteur"
                                   value="{{ old('email_expediteur') }}"
                                   placeholder="email@exemple.com"
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                            @error('email_expediteur')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nom expéditeur -->
                        <div>
                            <label for="nom_expediteur" class="block text-sm font-medium text-slate-300 mb-2">
                                Nom expéditeur
                            </label>
                            <input type="text" id="nom_expediteur" name="nom_expediteur"
                                   value="{{ old('nom_expediteur') }}"
                                   placeholder="Nom de l'expéditeur"
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                            @error('nom_expediteur')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Référence Interac -->
                    <div class="mt-4">
                        <label for="reference_interac" class="block text-sm font-medium text-slate-300 mb-2">
                            Référence Interac
                        </label>
                        <input type="text" id="reference_interac" name="reference_interac"
                               value="{{ old('reference_interac') }}"
                               placeholder="Référence ou numéro de transaction"
                               class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        @error('reference_interac')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Message Interac -->
                    <div class="mt-4">
                        <label for="message_interac" class="block text-sm font-medium text-slate-300 mb-2">
                            Message Interac
                        </label>
                        <textarea id="message_interac" name="message_interac" rows="3"
                                  placeholder="Message accompagnant le transfert Interac..."
                                  class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">{{ old('message_interac') }}</textarea>
                        @error('message_interac')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-slate-300 mb-2">
                    Description
                </label>
                <input type="text" id="description" name="description"
                       value="{{ old('description') }}"
                       placeholder="Description courte du paiement"
                       class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                @error('description')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes administrateur -->
            <div>
                <label for="notes_admin" class="block text-sm font-medium text-slate-300 mb-2">
                    Notes administrateur
                </label>
                <textarea id="notes_admin" name="notes_admin" rows="3"
                          placeholder="Notes internes pour l'administration..."
                          class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">{{ old('notes_admin') }}</textarea>
                @error('notes_admin')
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
                <p><strong>Info :</strong> La référence interne sera générée automatiquement.</p>
                <p>Le montant net sera calculé automatiquement (montant - frais).</p>
                <p>Les champs Interac apparaîtront automatiquement si vous sélectionnez "Interac" comme type de paiement.</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typePaiementSelect = document.getElementById('type_paiement');
    const interacFields = document.getElementById('interac-fields');

    function toggleInteracFields() {
        if (typePaiementSelect.value === 'interac') {
            interacFields.style.display = 'block';
        } else {
            interacFields.style.display = 'none';
        }
    }

    typePaiementSelect.addEventListener('change', toggleInteracFields);
    
    // Appelé au chargement initial pour gérer le cas où la valeur est déjà sélectionnée (old input)
    toggleInteracFields();
});
</script>
@endpush
@endsection
