@extends('layouts.admin')

@section('title', 'Modifier Paiement')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">✏️ Modifier Paiement</h2>
                <p class="text-slate-400">Paiement de {{ $paiement->user->name ?? 'N/A' }} - {{ $paiement->reference_interne }}</p>
            </div>
            <a href="{{ route('admin.paiements.show', $paiement) }}" 
               class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-lg">
                ← Retour
            </a>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <form action="{{ route('admin.paiements.update', $paiement) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Membre (lecture seule) -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Membre
                    </label>
                    <div class="w-full bg-slate-900 border border-slate-600 text-slate-400 rounded-lg px-3 py-2">
                        {{ $paiement->user->name ?? 'N/A' }} - {{ $paiement->user->email ?? 'N/A' }}
                    </div>
                </div>

                <!-- Référence interne (lecture seule) -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Référence interne
                    </label>
                    <div class="w-full bg-slate-900 border border-slate-600 text-slate-400 rounded-lg px-3 py-2">
                        {{ $paiement->reference_interne }}
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Type de paiement -->
                <div>
                    <label for="type_paiement" class="block text-sm font-medium text-slate-300 mb-2">
                        Type de paiement <span class="text-red-400">*</span>
                    </label>
                    <select id="type_paiement" name="type_paiement" required
                            class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="interac" {{ old('type_paiement', $paiement->type_paiement) == 'interac' ? 'selected' : '' }}>Interac</option>
                        <option value="especes" {{ old('type_paiement', $paiement->type_paiement) == 'especes' ? 'selected' : '' }}>Espèces</option>
                        <option value="carte" {{ old('type_paiement', $paiement->type_paiement) == 'carte' ? 'selected' : '' }}>Carte</option>
                        <option value="virement" {{ old('type_paiement', $paiement->type_paiement) == 'virement' ? 'selected' : '' }}>Virement</option>
                        <option value="cheque" {{ old('type_paiement', $paiement->type_paiement) == 'cheque' ? 'selected' : '' }}>Chèque</option>
                    </select>
                    @error('type_paiement')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Motif -->
                <div>
                    <label for="motif" class="block text-sm font-medium text-slate-300 mb-2">
                        Motif <span class="text-red-400">*</span>
                    </label>
                    <select id="motif" name="motif" required
                            class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="session_automne" {{ old('motif', $paiement->motif) == 'session_automne' ? 'selected' : '' }}>Session Automne</option>
                        <option value="session_hiver" {{ old('motif', $paiement->motif) == 'session_hiver' ? 'selected' : '' }}>Session Hiver</option>
                        <option value="session_printemps" {{ old('motif', $paiement->motif) == 'session_printemps' ? 'selected' : '' }}>Session Printemps</option>
                        <option value="session_ete" {{ old('motif', $paiement->motif) == 'session_ete' ? 'selected' : '' }}>Session Été</option>
                        <option value="seminaire" {{ old('motif', $paiement->motif) == 'seminaire' ? 'selected' : '' }}>Séminaire</option>
                        <option value="examen_ceinture" {{ old('motif', $paiement->motif) == 'examen_ceinture' ? 'selected' : '' }}>Examen Ceinture</option>
                        <option value="equipement" {{ old('motif', $paiement->motif) == 'equipement' ? 'selected' : '' }}>Équipement</option>
                        <option value="autre" {{ old('motif', $paiement->motif) == 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('motif')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Statut -->
                <div>
                    <label for="statut" class="block text-sm font-medium text-slate-300 mb-2">
                        Statut <span class="text-red-400">*</span>
                    </label>
                    <select id="statut" name="statut" required
                            class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="en_attente" {{ old('statut', $paiement->statut) == 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="recu" {{ old('statut', $paiement->statut) == 'recu' ? 'selected' : '' }}>Reçu</option>
                        <option value="valide" {{ old('statut', $paiement->statut) == 'valide' ? 'selected' : '' }}>Validé</option>
                        <option value="rembourse" {{ old('statut', $paiement->statut) == 'rembourse' ? 'selected' : '' }}>Remboursé</option>
                        <option value="annule" {{ old('statut', $paiement->statut) == 'annule' ? 'selected' : '' }}>Annulé</option>
                    </select>
                    @error('statut')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Période de facturation -->
                <div>
                    <label for="periode_facturation" class="block text-sm font-medium text-slate-300 mb-2">
                        Période de facturation
                    </label>
                    <input type="text" id="periode_facturation" name="periode_facturation"
                           value="{{ old('periode_facturation', $paiement->periode_facturation) }}"
                           placeholder="Ex: Automne 2024, Janvier 2025..."
                           class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    @error('periode_facturation')
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
                               value="{{ old('montant', $paiement->montant) }}" required
                               class="w-full pl-8 bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    </div>
                    @error('montant')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Frais -->
                <div>
                    <label for="frais" class="block text-sm font-medium text-slate-300 mb-2">
                        Frais
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-slate-400">$</span>
                        </div>
                        <input type="number" id="frais" name="frais" step="0.01" min="0"
                               value="{{ old('frais', $paiement->frais ?? '0.00') }}"
                               class="w-full pl-8 bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    </div>
                    @error('frais')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Champs Interac (affichés conditionnellement) -->
            <div id="interac-fields" class="space-y-6">
                <div class="border-t border-slate-600 pt-6">
                    <h3 class="text-lg font-medium text-slate-200 mb-4">📨 Informations Interac</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Email expéditeur -->
                        <div>
                            <label for="email_expediteur" class="block text-sm font-medium text-slate-300 mb-2">
                                Email expéditeur
                            </label>
                            <input type="email" id="email_expediteur" name="email_expediteur"
                                   value="{{ old('email_expediteur', $paiement->email_expediteur) }}"
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
                                   value="{{ old('nom_expediteur', $paiement->nom_expediteur) }}"
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
                               value="{{ old('reference_interac', $paiement->reference_interac) }}"
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
                                  class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">{{ old('message_interac', $paiement->message_interac) }}</textarea>
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
                       value="{{ old('description', $paiement->description) }}"
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
                          class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">{{ old('notes_admin', $paiement->notes_admin) }}</textarea>
                @error('notes_admin')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Informations sur les dates (lecture seule) -->
            @if($paiement->date_reception || $paiement->date_validation)
                <div class="border-t border-slate-600 pt-6">
                    <h3 class="text-lg font-medium text-slate-200 mb-4">📅 Historique des dates</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Date de création</label>
                            <div class="w-full bg-slate-900 border border-slate-600 text-slate-400 rounded-lg px-3 py-2">
                                {{ $paiement->created_at->format('d/m/Y à H:i') }}
                            </div>
                        </div>
                        @if($paiement->date_reception)
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Date de réception</label>
                                <div class="w-full bg-slate-900 border border-slate-600 text-blue-400 rounded-lg px-3 py-2">
                                    {{ $paiement->date_reception->format('d/m/Y à H:i') }}
                                </div>
                            </div>
                        @endif
                        @if($paiement->date_validation)
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Date de validation</label>
                                <div class="w-full bg-slate-900 border border-slate-600 text-green-400 rounded-lg px-3 py-2">
                                    {{ $paiement->date_validation->format('d/m/Y à H:i') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="flex justify-between items-center pt-6 border-t border-slate-700">
                <a href="{{ route('admin.paiements.show', $paiement) }}" 
                   class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-2 rounded-lg transition-colors">
                    ❌ Annuler
                </a>
                <button type="submit" 
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors">
                    ✅ Mettre à jour
                </button>
            </div>
        </form>
    </div>

    <!-- Info sur le montant net -->
    <div class="mt-4 bg-green-900/20 border border-green-800 rounded-lg p-4">
        <div class="flex items-center">
            <span class="text-green-400 mr-2">💰</span>
            <div class="text-green-200 text-sm">
                <p><strong>Montant net calculé :</strong> ${{ number_format($paiement->montant_net ?? ($paiement->montant - ($paiement->frais ?? 0)), 2) }}</p>
                <p>Le montant net sera recalculé automatiquement lors de la sauvegarde.</p>
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
    
    // Appelé au chargement initial
    toggleInteracFields();
});
</script>
@endpush
@endsection
