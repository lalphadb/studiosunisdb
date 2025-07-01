@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">👥 Inscriptions - {{ $seminaire->titre }}</h1>
                <p class="text-slate-400 mt-1">
                    {{ $seminaire->instructeur }} • {{ $seminaire->date_debut->format('d/m/Y H:i') }} • 
                    {{ $inscriptions->total() }} participant(s)
                </p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.seminaires.inscrire', $seminaire) }}" 
                   class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-lg transition-colors">
                    ➕ Inscrire
                </a>
                <a href="{{ route('admin.seminaires.show', $seminaire) }}" 
                   class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-lg transition-colors">
                    ← Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Section principale -->
    <div class="bg-slate-800 rounded-xl shadow-xl border border-slate-700 overflow-hidden">
        @if($inscriptions->count() > 0)
            <!-- Actions de masse -->
            <div class="p-6 border-b border-slate-700 bg-slate-750">
                <form id="bulk-action-form" method="POST" action="{{ route('admin.seminaires.bulk-validate-inscriptions', $seminaire) }}">
                    @csrf
                    <div class="flex flex-wrap items-center gap-4">
                        <!-- Sélection -->
                        <div class="flex items-center space-x-3">
                            <input 
                                type="checkbox" 
                                id="select-all" 
                                aria-label="Sélectionner toutes les inscriptions"
                                class="w-4 h-4 text-pink-600 bg-slate-700 border-slate-600 rounded focus:ring-pink-500"
                            >
                            <label for="select-all" class="text-sm text-slate-300">Tout sélectionner</label>
                            <span id="selected-count" class="text-sm text-pink-400 font-medium" aria-live="polite">0 sélectionné(s)</span>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-2">
                            <select name="action" id="bulk-action" aria-label="Choisir une action de masse" class="bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                                <option value="">Choisir une action</option>
                                <option value="confirmer_inscription">✅ Confirmer inscription</option>
                                <option value="marquer_present">👋 Marquer présent</option>
                                <option value="marquer_absent">❌ Marquer absent</option>
                                @if($seminaire->certificat)
                                    <option value="attribuer_certificat">🏆 Attribuer certificat</option>
                                @endif
                                <option value="annuler_inscription">🚫 Annuler inscription</option>
                                @if(auth()->user()->hasAnyRole(['superadmin', 'admin_ecole']))
                                    <option value="supprimer">🗑️ Supprimer</option>
                                @endif
                            </select>
                            <button 
                                type="submit" 
                                id="bulk-submit"
                                aria-label="Appliquer l'action sélectionnée"
                                class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                disabled
                            >
                                Appliquer
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table des inscriptions -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-900">
                        <tr class="text-left">
                            <th class="px-6 py-4 text-xs font-medium text-slate-300 uppercase tracking-wider w-12"></th>
                            <th class="px-6 py-4 text-xs font-medium text-slate-300 uppercase tracking-wider">Participant</th>
                            <th class="px-6 py-4 text-xs font-medium text-slate-300 uppercase tracking-wider">École</th>
                            <th class="px-6 py-4 text-xs font-medium text-slate-300 uppercase tracking-wider">Inscription</th>
                            <th class="px-6 py-4 text-xs font-medium text-slate-300 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-4 text-xs font-medium text-slate-300 uppercase tracking-wider">Certificat</th>
                            <th class="px-6 py-4 text-xs font-medium text-slate-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        @foreach($inscriptions as $inscription)
                            <tr class="hover:bg-slate-750 transition-colors duration-150">
                                <!-- Checkbox -->
                                <td class="px-6 py-4">
                                    <input 
                                        type="checkbox" 
                                        name="inscription_ids[]" 
                                        value="{{ $inscription->id }}" 
                                        aria-label="Sélectionner l'inscription de {{ $inscription->user->name }}"
                                        class="inscription-checkbox w-4 h-4 text-pink-600 bg-slate-700 border-slate-600 rounded focus:ring-pink-500"
                                    >
                                </td>
                                <!-- Participant -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-pink-500 to-purple-600 flex items-center justify-center">
                                                <span class="text-white font-medium text-sm">
                                                    {{ substr($inscription->user->name, 0, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-white">{{ $inscription->user->name }}</div>
                                            <div class="text-sm text-slate-400">{{ $inscription->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <!-- École -->
                                <td class="px-6 py-4">
                                    @if($inscription->user->ecole)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-600 text-white">
                                            {{ $inscription->user->ecole->nom }}
                                        </span>
                                    @else
                                        <span class="text-slate-500">Non assignée</span>
                                    @endif
                                </td>
                                <!-- Date inscription -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-300">{{ $inscription->created_at->format('d/m/Y') }}</div>
                                    @if($inscription->montant_paye)
                                        <div class="text-xs text-green-400">${{ number_format($inscription->montant_paye, 2) }}</div>
                                    @endif
                                </td>
                                <!-- Statut -->
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        @switch($inscription->statut)
                                            @case('present') bg-green-600 text-white @break
                                            @case('absent') bg-red-600 text-white @break
                                            @case('confirme') bg-blue-600 text-white @break
                                            @case('annule') bg-gray-600 text-white @break
                                            @default bg-yellow-600 text-white
                                        @endswitch">
                                        @switch($inscription->statut)
                                            @case('present') ✅ Présent @break
                                            @case('absent') ❌ Absent @break
                                            @case('confirme') 🎯 Confirmé @break
                                            @case('annule') 🚫 Annulé @break
                                            @default 📝 En attente
                                        @endswitch
                                    </span>
                                </td>
                                <!-- Certificat -->
                                <td class="px-6 py-4">
                                    @if($seminaire->certificat)
                                        @if($inscription->certificat_obtenu)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-600 text-white">
                                                🏆 Obtenu
                                            </span>
                                        @elseif($inscription->statut === 'present')
                                            <span class="text-yellow-400 text-xs">Éligible</span>
                                        @else
                                            <span class="text-slate-500 text-xs">-</span>
                                        @endif
                                    @else
                                        <span class="text-slate-500 text-xs">N/A</span>
                                    @endif
                                </td>
                                <!-- Actions -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <button onclick="quickAction({{ $inscription->id }}, 'toggle_status')" 
                                                class="text-blue-400 hover:text-blue-300 transition-colors duration-200"
                                                title="Basculer le statut">
                                            🔄
                                        </button>
                                        @if($seminaire->certificat && $inscription->statut === 'present' && !$inscription->certificat_obtenu)
                                            <button onclick="quickAction({{ $inscription->id }}, 'certificat')" 
                                                    class="text-purple-400 hover:text-purple-300 transition-colors duration-200"
                                                    title="Attribuer certificat">
                                                🏆
                                            </button>
                                        @endif
                                        @can('delete', $inscription)
                                            <form method="POST" action="{{ route('admin.seminaires.inscriptions.destroy', [$seminaire, $inscription]) }}" class="inline" onsubmit="return confirm('Supprimer cette inscription ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-300 transition-colors duration-200">
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
                {{ $inscriptions->links() }}
            </div>

        @else
            <!-- État vide -->
            <div class="text-center py-16">
                <div class="text-6xl mb-4">👥</div>
                <h3 class="text-xl font-semibold text-white mb-2">Aucune inscription</h3>
                <p class="text-slate-400 mb-6">Ce séminaire n'a encore aucun participant inscrit.</p>
                <a href="{{ route('admin.seminaires.inscrire', $seminaire) }}" 
                   class="inline-flex items-center px-6 py-3 bg-pink-600 hover:bg-pink-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <span class="mr-2">➕</span>
                    Inscrire le premier participant
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const inscriptionCheckboxes = document.querySelectorAll('.inscription-checkbox');
    const selectedCountSpan = document.getElementById('selected-count');
    const bulkActionSelect = document.getElementById('bulk-action');
    const bulkSubmitButton = document.getElementById('bulk-submit');
    const bulkForm = document.getElementById('bulk-action-form');

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.inscription-checkbox:checked');
        const count = checkedBoxes.length;
        selectedCountSpan.textContent = `${count} sélectionné(s)`;
        const hasSelection = count > 0;
        const hasAction = bulkActionSelect.value !== '';
        bulkSubmitButton.disabled = !hasSelection || !hasAction;
        
        if (count === 0) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = false;
        } else if (count === inscriptionCheckboxes.length) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = true;
        } else {
            selectAllCheckbox.indeterminate = true;
        }
    }

    selectAllCheckbox.addEventListener('change', function() {
        inscriptionCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    inscriptionCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    bulkActionSelect.addEventListener('change', updateBulkActions);

    bulkForm.addEventListener('submit', function(e) {
        const action = bulkActionSelect.value;
        const count = document.querySelectorAll('.inscription-checkbox:checked').length;
        let message = '';
        
        switch(action) {
            case 'confirmer_inscription':
                message = `Confirmer ${count} inscription(s) ?`;
                break;
            case 'marquer_present':
                message = `Marquer ${count} participant(s) comme présent(s) ?`;
                break;
            case 'marquer_absent':
                message = `Marquer ${count} participant(s) comme absent(s) ?`;
                break;
            case 'attribuer_certificat':
                message = `Attribuer le certificat à ${count} participant(s) ?`;
                break;
            case 'annuler_inscription':
                message = `Annuler ${count} inscription(s) ?`;
                break;
            case 'supprimer':
                message = `⚠️ SUPPRIMER définitivement ${count} inscription(s) ?`;
                break;
        }
        
        if (!confirm(message)) {
            e.preventDefault();
        }
    });

    updateBulkActions();
});

function quickAction(inscriptionId, action) {
    // Actions rapides individuelles
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/admin/inscriptions-seminaires/${inscriptionId}/quick-action`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({ action: action })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload(); // Recharger pour voir les changements
        } else {
            alert('Erreur: ' + data.message);
        }
    })
    .catch(error => {
        alert('Erreur de connexion');
    });
}
</script>
@endpush
@endsection
