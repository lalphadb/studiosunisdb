@extends('layouts.admin')

@section('title', 'Actions de Masse - Paiements')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">⚡ Actions de Masse - Paiements</h2>
                <p class="text-slate-400">Valider plusieurs paiements en une fois</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.paiements.validation-rapide') }}" 
                   class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg">
                    🚀 Validation Rapide
                </a>
                <a href="{{ route('admin.paiements.index') }}" 
                   class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-lg">
                    ← Retour
                </a>
            </div>
        </div>
    </div>

    @if($paiementsEnAttente->count() > 0)
        <form method="POST" action="{{ route('admin.paiements.traiter-actions-masse') }}" id="actionsMasseForm">
            @csrf
            
            <!-- Actions disponibles -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 p-4 mb-6">
                <h3 class="text-lg font-semibold text-white mb-4">🎯 Action à effectuer</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="flex items-center p-3 bg-green-600/20 border border-green-600/30 rounded-lg cursor-pointer hover:bg-green-600/30">
                        <input type="radio" name="action" value="marquer_paye" class="mr-3" required>
                        <div>
                            <div class="text-green-300 font-medium">✅ Marquer comme payés</div>
                            <div class="text-green-200 text-sm">Virements reçus et confirmés</div>
                        </div>
                    </label>
                    
                    <label class="flex items-center p-3 bg-red-600/20 border border-red-600/30 rounded-lg cursor-pointer hover:bg-red-600/30">
                        <input type="radio" name="action" value="marquer_annule" class="mr-3" required>
                        <div>
                            <div class="text-red-300 font-medium">❌ Marquer comme annulés</div>
                            <div class="text-red-200 text-sm">Paiements annulés ou expirés</div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Liste des paiements en attente -->
            <div class="bg-slate-800 rounded-xl border border-slate-700">
                <div class="p-4 border-b border-slate-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white">
                            💰 Paiements en attente ({{ $paiementsEnAttente->count() }})
                        </h3>
                        <div class="flex space-x-2">
                            <button type="button" onclick="selectAll()" 
                                    class="text-blue-400 hover:text-blue-300 text-sm">
                                Tout sélectionner
                            </button>
                            <button type="button" onclick="selectNone()" 
                                    class="text-slate-400 hover:text-slate-300 text-sm">
                                Tout désélectionner
                            </button>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-900">
                            <tr class="border-b border-slate-700">
                                <th class="text-left py-3 px-4 text-slate-300">
                                    <input type="checkbox" id="selectAllCheck" onchange="toggleAll()">
                                </th>
                                <th class="text-left py-3 px-4 text-slate-300">Membre</th>
                                <th class="text-left py-3 px-4 text-slate-300">Montant</th>
                                <th class="text-left py-3 px-4 text-slate-300">Référence</th>
                                <th class="text-left py-3 px-4 text-slate-300">Date création</th>
                                <th class="text-left py-3 px-4 text-slate-300">Réf. virement</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            @foreach($paiementsEnAttente as $paiement)
                            <tr class="hover:bg-slate-700/50 paiement-row">
                                <td class="py-3 px-4">
                                    <input type="checkbox" name="paiements[]" value="{{ $paiement->id }}" 
                                           class="paiement-checkbox">
                                </td>
                                <td class="py-3 px-4">
                                    <div class="text-white font-medium">{{ $paiement->user->name }}</div>
                                    <div class="text-slate-400 text-sm">{{ $paiement->user->email }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="text-green-400 font-bold">${{ number_format($paiement->montant, 2) }}</span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="text-blue-300">{{ $paiement->reference_interne }}</span>
                                </td>
                                <td class="py-3 px-4 text-slate-300">
                                    {{ $paiement->created_at->format('d/m/Y H:i') }}
                                    <div class="text-slate-500 text-xs">
                                        {{ $paiement->created_at->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <input type="text" name="reference_{{ $paiement->id }}" 
                                           placeholder="Réf. virement..." 
                                           class="w-full bg-slate-700 border border-slate-600 text-white rounded px-2 py-1 text-sm">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Actions -->
                <div class="p-4 border-t border-slate-700 bg-slate-900/50">
                    <div class="flex items-center justify-between">
                        <div class="text-slate-400 text-sm">
                            <span id="selectedCount">0</span> paiement(s) sélectionné(s)
                        </div>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                                id="submitBtn" disabled>
                            🚀 Traiter les paiements sélectionnés
                        </button>
                    </div>
                </div>
            </div>
        </form>
    @else
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-12 text-center">
            <div class="text-6xl mb-4">🎉</div>
            <h3 class="text-xl font-bold text-white mb-2">Aucun paiement en attente</h3>
            <p class="text-slate-400 mb-6">Tous les paiements ont été traités !</p>
            <a href="{{ route('admin.paiements.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">
                ➕ Créer un nouveau paiement
            </a>
        </div>
    @endif
</div>

<script>
function selectAll() {
    document.querySelectorAll('.paiement-checkbox').forEach(cb => cb.checked = true);
    updateSelectedCount();
}

function selectNone() {
    document.querySelectorAll('.paiement-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('selectAllCheck').checked = false;
    updateSelectedCount();
}

function toggleAll() {
    const masterCheck = document.getElementById('selectAllCheck');
    document.querySelectorAll('.paiement-checkbox').forEach(cb => cb.checked = masterCheck.checked);
    updateSelectedCount();
}

function updateSelectedCount() {
    const count = document.querySelectorAll('.paiement-checkbox:checked').length;
    document.getElementById('selectedCount').textContent = count;
    document.getElementById('submitBtn').disabled = count === 0;
}

// Event listeners
document.querySelectorAll('.paiement-checkbox').forEach(cb => {
    cb.addEventListener('change', updateSelectedCount);
});

// Validation du formulaire
document.getElementById('actionsMasseForm').addEventListener('submit', function(e) {
    const selectedCount = document.querySelectorAll('.paiement-checkbox:checked').length;
    const action = document.querySelector('input[name="action"]:checked');
    
    if (selectedCount === 0) {
        e.preventDefault();
        alert('Veuillez sélectionner au moins un paiement.');
        return;
    }
    
    if (!action) {
        e.preventDefault();
        alert('Veuillez sélectionner une action.');
        return;
    }
    
    const actionText = action.value === 'marquer_paye' ? 'marqués comme payés' : 'annulés';
    
    if (!confirm(`Êtes-vous sûr de vouloir traiter ${selectedCount} paiement(s) ? Ils seront ${actionText}.`)) {
        e.preventDefault();
    }
});
</script>
@endsection
