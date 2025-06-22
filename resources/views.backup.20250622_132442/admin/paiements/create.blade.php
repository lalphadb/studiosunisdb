@extends('layouts.admin')

@section('title', 'Nouveau Paiement')

@section('content')
<div class="min-h-screen bg-slate-900 py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold leading-7 text-white sm:text-3xl">
                        💳 Nouveau Paiement
                    </h2>
                    <p class="mt-1 text-sm text-slate-400">
                        Créer une demande de paiement pour un membre
                    </p>
                </div>
                <a href="{{ route('admin.paiements.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-slate-600 rounded-md text-sm font-medium text-slate-300 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour à la liste
                </a>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="bg-slate-800 rounded-lg shadow-lg">
            <form action="{{ route('admin.paiements.store') }}" method="POST" class="space-y-6 p-6">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Informations du membre -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-white border-b border-slate-700 pb-2">
                            Informations du membre
                        </h3>

                        <!-- Sélection membre -->
                        <div>
                            <label for="membre_id" class="block text-sm font-medium text-slate-300 mb-1">
                                Membre <span class="text-red-400">*</span>
                            </label>
                            <select id="membre_id" 
                                    name="membre_id" 
                                    required
                                    class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('membre_id') border-red-500 @enderror">
                                <option value="">Sélectionner un membre</option>
                                @foreach($membres as $membre)
                                    <option value="{{ $membre->id }}" {{ old('membre_id') == $membre->id ? 'selected' : '' }}>
                                        {{ $membre->nom }} {{ $membre->prenom }} - {{ $membre->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('membre_id')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Motif - SESSIONS STUDIOS UNIS -->
                        <div>
                            <label for="motif" class="block text-sm font-medium text-slate-300 mb-1">
                                Motif du paiement <span class="text-red-400">*</span>
                            </label>
                            <select id="motif" 
                                    name="motif" 
                                    required
                                    class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('motif') border-red-500 @enderror">
                                <option value="">Sélectionner un motif</option>
                                <optgroup label="🗓️ Sessions Studios Unis (3 mois chaque)">
                                    <option value="session_automne" {{ old('motif') == 'session_automne' ? 'selected' : '' }}>
                                        🍂 Session Automne (Septembre - Octobre - Novembre)
                                    </option>
                                    <option value="session_hiver" {{ old('motif') == 'session_hiver' ? 'selected' : '' }}>
                                        ❄️ Session Hiver (Décembre - Janvier - Février)
                                    </option>
                                    <option value="session_printemps" {{ old('motif') == 'session_printemps' ? 'selected' : '' }}>
                                        🌸 Session Printemps (Mars - Avril - Mai)
                                    </option>
                                    <option value="session_ete" {{ old('motif') == 'session_ete' ? 'selected' : '' }}>
                                        ☀️ Session Été (Juin - Juillet - Août)
                                    </option>
                                </optgroup>
                                <optgroup label="🎯 Autres Paiements">
                                    <option value="seminaire" {{ old('motif') == 'seminaire' ? 'selected' : '' }}>
                                        🎓 Séminaire spécialisé
                                    </option>
                                    <option value="examen_ceinture" {{ old('motif') == 'examen_ceinture' ? 'selected' : '' }}>
                                        🥋 Examen de ceinture
                                    </option>
                                    <option value="equipement" {{ old('motif') == 'equipement' ? 'selected' : '' }}>
                                        👕 Équipement (gi, ceintures, etc.)
                                    </option>
                                    <option value="autre" {{ old('motif') == 'autre' ? 'selected' : '' }}>
                                        📝 Autre
                                    </option>
                                </optgroup>
                            </select>
                            @error('motif')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-blue-300">
                                📅 Calendrier Studios Unis : Automne (Sep-Nov) → Hiver (Déc-Fév) → Printemps (Mar-Mai) → Été (Jun-Aoû)
                            </p>
                        </div>

                        <!-- Session actuelle -->
                        <div class="bg-green-900/20 border border-green-800 rounded-lg p-3">
                            <div class="flex items-center">
                                <span class="text-green-400 mr-2">🗓️</span>
                                <div>
                                    <p class="text-sm font-medium text-green-300">Session actuelle suggérée</p>
                                    <p class="text-xs text-green-200">
                                        @php
                                            $mois = now()->month;
                                            if ($mois >= 9 && $mois <= 11) {
                                                echo "🍂 Session Automne (Septembre - Octobre - Novembre)";
                                            } elseif ($mois == 12 || $mois >= 1 && $mois <= 2) {
                                                echo "❄️ Session Hiver (Décembre - Janvier - Février)";
                                            } elseif ($mois >= 3 && $mois <= 5) {
                                                echo "🌸 Session Printemps (Mars - Avril - Mai)";
                                            } else {
                                                echo "☀️ Session Été (Juin - Juillet - Août)";
                                            }
                                        @endphp
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-slate-300 mb-1">
                                Description (optionnel)
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="3"
                                      placeholder="Détails supplémentaires..."
                                      class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-md text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Informations financières -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-white border-b border-slate-700 pb-2">
                            Informations financières
                        </h3>

                        <!-- Tarifs suggérés par session -->
                        <div class="bg-blue-900/20 border border-blue-800 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-blue-300 mb-2">💰 Tarifs suggérés Studios Unis</h4>
                            <div class="text-sm text-blue-200 space-y-1">
                                <p>• <strong>Session complète (3 mois)</strong>: $300 - $450</p>
                                <p>• <strong>Séminaire spécialisé</strong>: $50 - $150</p>
                                <p>• <strong>Examen de ceinture</strong>: $25 - $75</p>
                                <p>• <strong>Équipement (gi complet)</strong>: $80 - $150</p>
                                <p class="text-xs text-blue-300 mt-2">
                                    💡 4 sessions par année = environ $1200-$1800 annuel
                                </p>
                            </div>
                        </div>

                        <!-- Montant -->
                        <div>
                            <label for="montant" class="block text-sm font-medium text-slate-300 mb-1">
                                Montant (CAD) <span class="text-red-400">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-slate-400 sm:text-sm">$</span>
                                </div>
                                <input type="number" 
                                       id="montant" 
                                       name="montant" 
                                       step="0.01" 
                                       min="0.01"
                                       placeholder="0.00"
                                       value="{{ old('montant') }}"
                                       required
                                       class="w-full pl-7 pr-3 py-2 bg-slate-700 border border-slate-600 rounded-md text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('montant') border-red-500 @enderror">
                            </div>
                            @error('montant')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Frais -->
                        <div>
                            <label for="frais" class="block text-sm font-medium text-slate-300 mb-1">
                                Frais Interac (optionnel)
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-slate-400 sm:text-sm">$</span>
                                </div>
                                <input type="number" 
                                       id="frais" 
                                       name="frais" 
                                       step="0.01" 
                                       min="0"
                                       placeholder="0.00"
                                       value="{{ old('frais', '0.00') }}"
                                       class="w-full pl-7 pr-3 py-2 bg-slate-700 border border-slate-600 rounded-md text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('frais') border-red-500 @enderror">
                            </div>
                            <p class="mt-1 text-xs text-slate-400">
                                Frais bancaires applicables au virement Interac
                            </p>
                            @error('frais')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Montant net (calculé automatiquement) -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1">
                                Montant net
                            </label>
                            <div class="w-full px-3 py-2 bg-slate-600 border border-slate-500 rounded-md text-slate-300 text-lg font-semibold">
                                <span id="montant_net">$0.00</span>
                            </div>
                            <p class="mt-1 text-xs text-slate-400">
                                Montant que l'école recevra après déduction des frais
                            </p>
                        </div>

                        <!-- Date d'échéance -->
                        <div>
                            <label for="date_echeance" class="block text-sm font-medium text-slate-300 mb-1">
                                Date d'échéance
                            </label>
                            <input type="date" 
                                   id="date_echeance" 
                                   name="date_echeance" 
                                   value="{{ old('date_echeance', now()->addDays(30)->format('Y-m-d')) }}"
                                   min="{{ now()->format('Y-m-d') }}"
                                   class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('date_echeance') border-red-500 @enderror">
                            @error('date_echeance')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Instructions Interac -->
                <div class="bg-blue-900/20 border border-blue-800 rounded-lg p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-blue-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h4 class="text-sm font-medium text-blue-300 mb-2">Instructions pour le paiement Interac</h4>
                            <div class="text-sm text-blue-200 space-y-1">
                                <p>• Le membre recevra les instructions par email après création</p>
                                <p>• Demandez au membre d'inclure la référence dans le message du virement</p>
                                <p>• Une fois le virement reçu, marquez le paiement comme "Reçu" puis "Validé"</p>
                                <p>• Un reçu PDF pourra être généré après validation</p>
                                <p>• <strong>Sessions Studios Unis</strong>: 4 sessions de 3 mois par année</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-slate-700">
                    <a href="{{ route('admin.paiements.index') }}" 
                       class="px-6 py-2 border border-slate-600 rounded-md text-slate-300 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200">
                        Créer le paiement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const montantInput = document.getElementById('montant');
    const fraisInput = document.getElementById('frais');
    const montantNetSpan = document.getElementById('montant_net');
    
    function calculerMontantNet() {
        const montant = parseFloat(montantInput.value) || 0;
        const frais = parseFloat(fraisInput.value) || 0;
        const net = Math.max(0, montant - frais);
        
        montantNetSpan.textContent = '$' + net.toFixed(2);
        
        // Changer la couleur si le montant net est différent du montant brut
        if (frais > 0) {
            montantNetSpan.parentElement.classList.add('text-yellow-300');
            montantNetSpan.parentElement.classList.remove('text-slate-300');
        } else {
            montantNetSpan.parentElement.classList.remove('text-yellow-300');
            montantNetSpan.parentElement.classList.add('text-slate-300');
        }
    }
    
    montantInput.addEventListener('input', calculerMontantNet);
    fraisInput.addEventListener('input', calculerMontantNet);
    
    // Calcul initial
    calculerMontantNet();
});
</script>
@endsection
