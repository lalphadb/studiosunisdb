@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header du module -->
    <x-module-header 
        module="paiements"
        title="Gestion des Paiements" 
        subtitle="Suivi financier et validation de masse"
        create-route="{{ route('admin.paiements.create') }}"
        create-permission="create,App\Models\Paiement"
    />

    <!-- Section principale -->
    <div class="bg-slate-800 rounded-xl shadow-xl border border-slate-700 overflow-hidden mt-6">
        <!-- Barre de recherche et filtres -->
        <div class="p-6 border-b border-slate-700">
            <form method="GET" action="{{ route('admin.paiements.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <!-- Recherche -->
                <div class="md:col-span-2">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Rechercher par référence, montant, nom..."
                        aria-label="Rechercher des paiements"
                        class="w-full bg-slate-700 border border-slate-600 text-white placeholder-slate-400 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                    >
                </div>

                <!-- Filtre statut -->
                <div>
                    <select name="statut" aria-label="Filtrer par statut" class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="recu" {{ request('statut') === 'recu' ? 'selected' : '' }}>Reçu</option>
                        <option value="valide" {{ request('statut') === 'valide' ? 'selected' : '' }}>Validé</option>
                        <option value="rembourse" {{ request('statut') === 'rembourse' ? 'selected' : '' }}>Remboursé</option>
                        <option value="annule" {{ request('statut') === 'annule' ? 'selected' : '' }}>Annulé</option>
                    </select>
                </div>

                <!-- Filtre type -->
                <div>
                    <select name="type_paiement" aria-label="Filtrer par type de paiement" class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                        <option value="">Tous les types</option>
                        <option value="interac" {{ request('type_paiement') === 'interac' ? 'selected' : '' }}>Interac</option>
                        <option value="especes" {{ request('type_paiement') === 'especes' ? 'selected' : '' }}>Espèces</option>
                        <option value="carte" {{ request('type_paiement') === 'carte' ? 'selected' : '' }}>Carte</option>
                        <option value="virement" {{ request('type_paiement') === 'virement' ? 'selected' : '' }}>Virement</option>
                        <option value="cheque" {{ request('type_paiement') === 'cheque' ? 'selected' : '' }}>Chèque</option>
                    </select>
                </div>

                <!-- Filtre motif -->
                <div>
                    <select name="motif" aria-label="Filtrer par motif" class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                        <option value="">Tous les motifs</option>
                        <option value="session_automne" {{ request('motif') === 'session_automne' ? 'selected' : '' }}>Session Automne</option>
                        <option value="session_hiver" {{ request('motif') === 'session_hiver' ? 'selected' : '' }}>Session Hiver</option>
                        <option value="session_printemps" {{ request('motif') === 'session_printemps' ? 'selected' : '' }}>Session Printemps</option>
                        <option value="session_ete" {{ request('motif') === 'session_ete' ? 'selected' : '' }}>Session Été</option>
                        <option value="seminaire" {{ request('motif') === 'seminaire' ? 'selected' : '' }}>Séminaire</option>
                        <option value="examen_ceinture" {{ request('motif') === 'examen_ceinture' ? 'selected' : '' }}>Examen Ceinture</option>
                        <option value="equipement" {{ request('motif') === 'equipement' ? 'selected' : '' }}>Équipement</option>
                        <option value="autre" {{ request('motif') === 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>

                <!-- Bouton recherche -->
                <div>
                    <button type="submit" aria-label="Lancer la recherche" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg px-4 py-2 font-medium transition-colors duration-200">
                        🔍 Rechercher
                    </button>
                </div>
            </form>
        </div>

        @if($paiements->count() > 0)
            <!-- Actions de masse -->
            <div class="p-6 border-b border-slate-700 bg-slate-750">
                <form id="bulk-action-form" method="POST" action="{{ route('admin.paiements.bulk-validate') }}">
                    @csrf
                    <div class="flex flex-wrap items-center gap-4">
                        <!-- Sélection -->
                        <div class="flex items-center space-x-3">
                            <input 
                                type="checkbox" 
                                id="select-all" 
                                aria-label="Sélectionner tous les paiements"
                                class="w-4 h-4 text-yellow-600 bg-slate-700 border-slate-600 rounded focus:ring-yellow-500"
                            >
                            <label for="select-all" class="text-sm text-slate-300">Tout sélectionner</label>
                            <span id="selected-count" class="text-sm text-yellow-400 font-medium" aria-live="polite">0 sélectionné(s)</span>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-2">
                            <select name="action" id="bulk-action" aria-label="Choisir une action de masse" class="bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
                                <option value="">Choisir une action</option>
                                <option value="marquer_recu">📨 Marquer comme reçu</option>
                                <option value="valider">✅ Valider les paiements</option>
                                <option value="attente">⏳ Remettre en attente</option>
                                @if(auth()->user()->hasAnyRole(['superadmin', 'admin_ecole']))
                                    <option value="supprimer">🗑️ Supprimer</option>
                                @endif
                            </select>
                            <button 
                                type="submit" 
                                id="bulk-submit"
                                aria-label="Appliquer l'action sélectionnée"
                                class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                disabled
                            >
                                Appliquer
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table des paiements -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-900">
                        <tr class="text-left">
                            <th class="px-6 py-4 text-xs font-medium text-slate-300 uppercase tracking-wider w-12"></th>
                            <th class="px-6 py-4 text-xs font-medium text-slate-300 uppercase tracking-wider">Référence</th>
                            <th class="px-6 py-4 text-xs font-medium text-slate-300 uppercase tracking-wider">Membre</th>
                            <th class="px-6 py-4 text-xs font-medium text-slate-300 uppercase tracking-wider">Motif</th>
                            <th class="px-6 py-4 text-xs font-medium text-slate-300 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-4 text-xs font-medium text-slate-300 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-4 text-xs font-medium text-slate-300 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-4 text-xs font-medium text-slate-300 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-xs font-medium text-slate-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        @foreach($paiements as $paiement)
                            <tr class="hover:bg-slate-750 transition-colors duration-150">
                                <!-- Checkbox -->
                                <td class="px-6 py-4">
                                    <input 
                                        type="checkbox" 
                                        name="paiement_ids[]" 
                                        value="{{ $paiement->id }}" 
                                        aria-label="Sélectionner le paiement {{ $paiement->reference_interne }}"
                                        aria-checked="false"
                                        class="paiement-checkbox w-4 h-4 text-yellow-600 bg-slate-700 border-slate-600 rounded focus:ring-yellow-500"
                                    >
                                </td>
                                <!-- Référence -->
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-white">{{ $paiement->reference_interne }}</div>
                                    @if($paiement->reference_interac)
                                        <div class="text-xs text-slate-400">{{ $paiement->reference_interac }}</div>
                                    @endif
                                </td>
                                <!-- Membre -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-300">{{ $paiement->user->name }}</div>
                                    @if($paiement->ecole)
                                        <div class="text-xs text-slate-500">{{ $paiement->ecole->nom }}</div>
                                    @endif
                                </td>
                                <!-- Motif -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-300">{{ $paiement->motif_text }}</div>
                                    @if($paiement->description)
                                        <div class="text-xs text-slate-400">{{ Str::limit($paiement->description, 30) }}</div>
                                    @endif
                                </td>
                                <!-- Montant -->
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-white">${{ number_format($paiement->montant, 2) }}</div>
                                    @if($paiement->frais > 0)
                                        <div class="text-xs text-slate-400">
                                            Frais: ${{ number_format($paiement->frais, 2) }}
                                        </div>
                                        <div class="text-xs text-green-400">
                                            Net: ${{ number_format($paiement->montant_net, 2) }}
                                        </div>
                                    @endif
                                </td>
                                <!-- Type -->
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        @if($paiement->type_paiement === 'interac') bg-blue-600 text-white
                                        @elseif($paiement->type_paiement === 'especes') bg-green-600 text-white
                                        @elseif($paiement->type_paiement === 'carte') bg-purple-600 text-white
                                        @else bg-gray-600 text-white @endif">
                                        {{ ucfirst($paiement->type_paiement) }}
                                    </span>
                                </td>
                                <!-- Statut avec validation rapide (couleurs module paiements) -->
                                <td class="px-6 py-4">
                                    <button 
                                        type="button"
                                        onclick="quickValidate({{ $paiement->id }}, this)"
                                        aria-label="Changer le statut du paiement {{ $paiement->reference_interne }}"
                                        data-paiement-id="{{ $paiement->id }}"
                                        data-original-statut="{{ $paiement->statut }}"
                                        data-original-content="{{ $paiement->statut_text }}"
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium transition-colors duration-200 cursor-pointer
                                            @if($paiement->statut === 'valide') 
                                                bg-green-600 text-white hover:bg-green-700
                                            @elseif($paiement->statut === 'recu') 
                                                bg-blue-600 text-white hover:bg-blue-700
                                            @elseif($paiement->statut === 'en_attente') 
                                                bg-yellow-600 text-white hover:bg-yellow-700
                                            @elseif($paiement->statut === 'rembourse') 
                                                bg-purple-600 text-white hover:bg-purple-700
                                            @else 
                                                bg-red-600 text-white hover:bg-red-700
                                            @endif"
                                    >
                                        @if($paiement->statut === 'valide')
                                            ✅ Validé
                                        @elseif($paiement->statut === 'recu')
                                            📨 Reçu
                                        @elseif($paiement->statut === 'en_attente')
                                            ⏳ En attente
                                        @elseif($paiement->statut === 'rembourse')
                                            💜 Remboursé
                                        @else
                                            ❌ {{ ucfirst($paiement->statut) }}
                                        @endif
                                    </button>
                                </td>
                                <!-- Date -->
                                <td class="px-6 py-4 text-sm text-slate-300">
                                    <div>{{ $paiement->created_at->format('d/m/Y') }}</div>
                                    @if($paiement->date_reception)
                                        <div class="text-xs text-blue-400">Reçu: {{ $paiement->date_reception->format('d/m/Y') }}</div>
                                    @endif
                                    @if($paiement->date_validation)
                                        <div class="text-xs text-green-400">Validé: {{ $paiement->date_validation->format('d/m/Y') }}</div>
                                    @endif
                                </td>
                                <!-- Actions -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        @can('view', $paiement)
                                            <a href="{{ route('admin.paiements.show', $paiement) }}" 
                                               aria-label="Voir le détail du paiement"
                                               class="text-blue-400 hover:text-blue-300 transition-colors duration-200">
                                                👁️
                                            </a>
                                        @endcan
                                        @can('update', $paiement)
                                            <a href="{{ route('admin.paiements.edit', $paiement) }}" 
                                               aria-label="Modifier le paiement"
                                               class="text-yellow-400 hover:text-yellow-300 transition-colors duration-200">
                                                ✏️
                                            </a>
                                        @endcan
                                        @can('delete', $paiement)
                                            <form method="POST" action="{{ route('admin.paiements.destroy', $paiement) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce paiement ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" aria-label="Supprimer le paiement" class="text-red-400 hover:text-red-300 transition-colors duration-200">
                                                    🗑️
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-slate-750 border-t border-slate-700">
                {{ $paiements->appends(request()->query())->links() }}
            </div>

        @else
            <!-- État vide -->
            <div class="text-center py-16">
                <div class="text-6xl mb-4">💰</div>
                <h3 class="text-xl font-semibold text-white mb-2">Aucun paiement trouvé</h3>
                <p class="text-slate-400 mb-6">
                    @if(request()->hasAny(['search', 'statut', 'type_paiement', 'motif']))
                        Aucun paiement ne correspond à vos critères de recherche.
                    @else
                        Commencez par ajouter un paiement au système.
                    @endif
                </p>
                @can('create', App\Models\Paiement::class)
                    <a href="{{ route('admin.paiements.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <span class="mr-2">💰</span>
                        Nouveau paiement
                    </a>
                @endcan
            </div>
        @endif
    </div>

    <!-- Zone de notifications ARIA -->
    <div id="notification-area" aria-live="polite" aria-atomic="true" class="sr-only"></div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const paiementCheckboxes = document.querySelectorAll('.paiement-checkbox');
    const selectedCountSpan = document.getElementById('selected-count');
    const bulkActionSelect = document.getElementById('bulk-action');
    const bulkSubmitButton = document.getElementById('bulk-submit');
    const bulkForm = document.getElementById('bulk-action-form');

    // File d'attente pour optimiser les requêtes AJAX
    const validationQueue = new Set();
    let debounceTimer = null;
    const DEBOUNCE_DELAY = 500; // 500ms de délai pour regrouper les requêtes

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.paiement-checkbox:checked');
        const count = checkedBoxes.length;
        selectedCountSpan.textContent = `${count} sélectionné(s)`;
        const hasSelection = count > 0;
        const hasAction = bulkActionSelect.value !== '';
        bulkSubmitButton.disabled = !hasSelection || !hasAction;
        
        // Mise à jour ARIA
        checkedBoxes.forEach(checkbox => {
            checkbox.setAttribute('aria-checked', checkbox.checked ? 'true' : 'false');
        });
        
        if (count === 0) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = false;
        } else if (count === paiementCheckboxes.length) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = true;
        } else {
            selectAllCheckbox.indeterminate = true;
        }
    }

    selectAllCheckbox.addEventListener('change', function() {
        paiementCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    paiementCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    bulkActionSelect.addEventListener('change', updateBulkActions);

    bulkForm.addEventListener('submit', function(e) {
        const checkedBoxes = document.querySelectorAll('.paiement-checkbox:checked');
        const action = bulkActionSelect.value;
        const count = checkedBoxes.length;
        let message = '';
        
        switch(action) {
            case 'marquer_recu':
                message = `Êtes-vous sûr de vouloir marquer ${count} paiement(s) comme reçu(s) ?`;
                break;
            case 'valider':
                message = `Êtes-vous sûr de vouloir valider ${count} paiement(s) ?`;
                break;
            case 'attente':
                message = `Êtes-vous sûr de vouloir remettre en attente ${count} paiement(s) ?`;
                break;
            case 'supprimer':
                message = `⚠️ ATTENTION : Êtes-vous sûr de vouloir supprimer ${count} paiement(s) ? Cette action est irréversible.`;
                break;
        }
        
        if (!confirm(message)) {
            e.preventDefault();
        }
    });

    updateBulkActions();

    // Fonction de validation rapide optimisée avec file d'attente
    window.quickValidate = function(paiementId, button) {
        // Confirmation avant ajout à la file d'attente
        const currentStatut = button.dataset.originalStatut;
        const nextStatut = getNextStatut(currentStatut);
        const confirmMessage = `Changer le statut vers "${getStatutText(nextStatut)}" ?`;
        
        if (!confirm(confirmMessage)) {
            return;
        }

        // Ajouter à la file d'attente
        validationQueue.add(paiementId);
        
        // UI optimiste - mise à jour immédiate
        updateButtonOptimistically(button, nextStatut);
        
        // Débounce pour regrouper les requêtes
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            processBulkValidation();
        }, DEBOUNCE_DELAY);
    };

    function getNextStatut(currentStatut) {
        const statusCycle = {
            'en_attente': 'recu',
            'recu': 'valide',
            'valide': 'en_attente'
        };
        return statusCycle[currentStatut] || 'en_attente';
    }

    function getStatutText(statut) {
        const statusTexts = {
            'en_attente': 'En attente',
            'recu': 'Reçu',
            'valide': 'Validé',
            'rembourse': 'Remboursé',
            'annule': 'Annulé'
        };
        return statusTexts[statut] || statut;
    }

    function getStatutIcon(statut) {
        const statusIcons = {
            'en_attente': '⏳',
            'recu': '📨',
            'valide': '✅',
            'rembourse': '💜',
            'annule': '❌'
        };
        return statusIcons[statut] || '❓';
    }

    function getStatutClasses(statut) {
        const statusClasses = {
            'valide': 'bg-green-600 text-white hover:bg-green-700',
            'recu': 'bg-blue-600 text-white hover:bg-blue-700',
            'en_attente': 'bg-yellow-600 text-white hover:bg-yellow-700',
            'rembourse': 'bg-purple-600 text-white hover:bg-purple-700',
            'annule': 'bg-red-600 text-white hover:bg-red-700'
        };
        return statusClasses[statut] || 'bg-gray-600 text-white hover:bg-gray-700';
    }

    function updateButtonOptimistically(button, newStatut) {
        button.innerHTML = '⏳ ...';
        button.disabled = true;
        button.className = button.className.replace(/bg-\w+-\d+|text-\w+|hover:bg-\w+-\d+/g, '');
        button.className += ' bg-gray-500 text-white';
    }

    function updateButtonFromResult(button, result) {
        button.disabled = false;
        
        if (result.success) {
            const newStatut = result.new_statut;
            button.dataset.originalStatut = newStatut;
            button.dataset.originalContent = getStatutText(newStatut);
            
            // Mettre à jour les classes CSS
            button.className = button.className.replace(/bg-\w+-\d+|text-\w+|hover:bg-\w+-\d+/g, '');
            button.className += ' ' + getStatutClasses(newStatut);
            
            // Mettre à jour le contenu
            button.innerHTML = getStatutIcon(newStatut) + ' ' + getStatutText(newStatut);
            
            // Mettre à jour ARIA
            button.setAttribute('aria-label', `Changer le statut du paiement (actuellement ${getStatutText(newStatut)})`);
        } else {
            // Restaurer l'état original en cas d'erreur
            const originalStatut = button.dataset.originalStatut;
            const originalContent = button.dataset.originalContent;
            
            button.className = button.className.replace(/bg-\w+-\d+|text-\w+|hover:bg-\w+-\d+/g, '');
            button.className += ' ' + getStatutClasses(originalStatut);
            button.innerHTML = getStatutIcon(originalStatut) + ' ' + originalContent;
        }
    }

    async function processBulkValidation() {
        if (validationQueue.size === 0) return;
        
        const paiementIds = Array.from(validationQueue);
        validationQueue.clear();
        
        try {
            const response = await fetch('/admin/paiements/quick-bulk-validate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ paiement_ids: paiementIds })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Mettre à jour chaque bouton selon les résultats
                Object.entries(data.results).forEach(([paiementId, result]) => {
                    const button = document.querySelector(`[data-paiement-id="${paiementId}"]`);
                    if (button) {
                        updateButtonFromResult(button, result);
                    }
                });
                
                showNotification('Statuts mis à jour avec succès', 'success');
            } else {
                throw new Error(data.message || 'Erreur lors de la validation groupée');
            }
            
        } catch (error) {
            console.error('Erreur validation groupée:', error);
            
            // Restaurer tous les boutons en cas d'erreur
            paiementIds.forEach(paiementId => {
                const button = document.querySelector(`[data-paiement-id="${paiementId}"]`);
                if (button) {
                    updateButtonFromResult(button, { success: false });
                }
            });
            
            showNotification('Erreur lors de la validation groupée', 'error');
        }
    }

    function showNotification(message, type) {
        // Notification visuelle
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
            type === 'success' ? 'bg-green-600 text-white' : 'bg-red-600 text-white'
        }`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        // Notification pour les lecteurs d'écran
        const notificationArea = document.getElementById('notification-area');
        notificationArea.textContent = message;
        
        setTimeout(() => {
            notification.remove();
            notificationArea.textContent = '';
        }, 3000);
    }
});
</script>
@endpush
@endsection
