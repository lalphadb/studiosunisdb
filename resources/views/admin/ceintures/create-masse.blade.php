@extends('layouts.admin')
@section('title', 'Attribution en Masse')

@section('content')
<div class="space-y-6">
    <!-- Header avec gradient orange qui fade vers transparent -->
    <div class="bg-gradient-to-r from-orange-500/15 via-red-600/20 to-transparent rounded-xl p-6 text-white relative overflow-hidden">
        <div class="absolute inset-0 "></div>
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Attribution en Masse - Examen Groupe
                </h1>
                <p class="text-orange-100 text-lg">Attribuer la même ceinture à plusieurs membres simultanément</p>
            </div>
            <a href="{{ route('admin.ceintures.index') }}" 
               class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-xl font-medium transition duration-200">
                Retour
            </a>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="bg-slate-800/40 backdrop-blur-xl/40 backdrop-blur-xl rounded-xl border border-slate-700/30/30/20p-6">
        <form method="POST" action="{{ route('admin.ceintures.store-masse') }}" class="space-y-6">
            @csrf
            
            <!-- Informations de l'examen -->
            <div class="bg-slate-900 rounded-xl p-4 border border-slate-600">
                <h3 class="text-lg font-semibold text-slate-100 tracking-tight flex items-center gap-3">📝 Informations de l'Examen</h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Sélection ceinture SANS codes couleur -->
                    <div>
                        <label for="ceinture_id" class="block text-sm font-medium text-slate-300 mb-2">
                            Ceinture à attribuer <span class="text-red-400">*</span>
                        </label>
                        <select name="ceinture_id" id="ceinture_id" required
                                class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-orange-500 @error('ceinture_id') border-red-500 @enderror">
                            <option value="">Sélectionner une ceinture</option>
                            @foreach($ceintures as $ceinture)
                            <option value="{{ $ceinture->id }}" data-nom="{{ $ceinture->nom }}" {{ old('ceinture_id') == $ceinture->id ? 'selected' : '' }}>
                                {{ $ceinture->ordre }}. {{ $ceinture->nom }}
                            </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="ceinture_nom" id="ceinture_nom" value="{{ old('ceinture_nom') }}">
                        @error('ceinture_id')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date d'obtention -->
                    <div>
                        <label for="date_obtention" class="block text-sm font-medium text-slate-300 mb-2">
                            Date d'obtention <span class="text-red-400">*</span>
                        </label>
                        <input type="date" 
                               name="date_obtention" 
                               id="date_obtention"
                               value="{{ old('date_obtention', now()->format('Y-m-d')) }}"
                               max="{{ now()->format('Y-m-d') }}"
                               required
                               class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-orange-500 @error('date_obtention') border-red-500 @enderror">
                        @error('date_obtention')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Notes générales -->
                <div class="mt-4">
                    <label for="notes" class="block text-sm font-medium text-slate-300 mb-2">
                        Notes sur l'examen
                    </label>
                    <textarea name="notes" 
                              id="notes" 
                              rows="2"
                              placeholder="Commentaires généraux sur cet examen de groupe..."
                              class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-xl text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                    @error('notes')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Sélection des membres -->
            <div class="bg-slate-900 rounded-xl border border-slate-600 overflow-hidden">
                <div class="bg-orange-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-slate-100 tracking-tight flex items-center gap-3">
                        <span>👥 Sélection des Membres ({{ $membres->count() }} disponibles)</span>
                        <div class="flex space-x-2">
                            <button type="button" id="select-all" 
                                    class="bg-white/20 hover:bg-white/30 text-white px-3 py-1 rounded text-sm transition duration-200">
                                Tout sélectionner
                            </button>
                            <button type="button" id="deselect-all" 
                                    class="bg-white/20 hover:bg-white/30 text-white px-3 py-1 rounded text-sm transition duration-200">
                                Tout déselectionner
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Barre de recherche -->
                <div class="px-6 py-4 border-b border-slate-700/30/30/20>
                    <input type="text" id="search-membres" 
                           placeholder="🔍 Rechercher un membre par nom..."
                           class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-xl text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <!-- Liste des membres avec checkboxes -->
                <div class="max-h-96 overflow-y-auto">
                    @if($membres->count() > 0)
                        <div class="p-4 space-y-3" id="membres-list">
                            @foreach($membres as $membre)
                            <div class="membre-item flex items-center p-3 bg-slate-800/40 backdrop-blur-xl/40 backdrop-blur-xl rounded-xl border border-slate-700/30/30/20hover:border-orange-500 transition duration-200">
                                <input type="checkbox" 
                                       name="attributions[{{ $loop->index }}][user_id]" 
                                       value="{{ $membre->id }}"
                                       id="membre_{{ $membre->id }}"
                                       class="w-4 h-4 text-orange-600 bg-slate-700 border-slate-600 rounded focus:ring-orange-500 focus:ring-2 membre-checkbox"
                                       {{ collect(old('attributions', []))->pluck('user_id')->contains($membre->id) ? 'checked' : '' }}>
                                
                                <label for="membre_{{ $membre->id }}" class="ml-3 flex-1 cursor-pointer">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-blue-500/15 via-blue-600/20 to-cyan-500/15 flex items-center justify-center">
                                                    <span class="text-white font-bold text-sm">{{ substr($membre->name, 0, 2) }}</span>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-white membre-name">{{ $membre->name }}</div>
                                                <div class="text-xs text-slate-400">{{ $membre->ecole->nom ?? 'École non assignée' }}</div>
                                            </div>
                                        </div>
                                        <div class="text-xs text-slate-400">
                                            @if($membre->userCeintures->where('valide', true)->latest('date_obtention')->first())
                                                Actuel: {{ $membre->userCeintures->where('valide', true)->latest('date_obtention')->first()->ceinture->nom }}
                                            @else
                                                Pas de ceinture
                                            @endif
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center">
                            <div class="text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <h3 class="text-lg font-semibold text-slate-100 tracking-tight flex items-center gap-3">Aucun membre disponible</h3>
                                <p class="text-xs text-slate-500">Assurez-vous d'avoir des membres dans votre école.</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Compteur de sélection -->
                <div class="px-6 py-4 border-t border-slate-700/30/30/20bg-slate-800/40 backdrop-blur-xl/40 backdrop-blur-xl">
                    <div class="text-sm text-slate-300">
                        <span id="selected-count">0</span> membre(s) sélectionné(s)
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-slate-700/30/30/20>
                <a href="{{ route('admin.ceintures.index') }}" 
                   class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-xl font-medium transition duration-200">
                    Annuler
                </a>
                <button type="submit" 
                        class="bg-orange-600 hover:bg-orange-700 text-white px-8 py-3 rounded-xl font-medium transition duration-200 flex items-center disabled:opacity-50 disabled:cursor-not-allowed"
                        id="submit-btn" disabled>
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span id="submit-text">Attribuer la Ceinture</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.membre-checkbox');
    const selectAllBtn = document.getElementById('select-all');
    const deselectAllBtn = document.getElementById('deselect-all');
    const selectedCount = document.getElementById('selected-count');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const searchInput = document.getElementById('search-membres');
    const ceintureSelect = document.getElementById('ceinture_id');
    const ceintureNomInput = document.getElementById('ceinture_nom');

    // Mise à jour du nom de ceinture caché
    ceintureSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        ceintureNomInput.value = selectedOption.dataset.nom || '';
    });

    // Fonction pour mettre à jour le compteur
    function updateCount() {
        const checked = document.querySelectorAll('.membre-checkbox:checked').length;
        selectedCount.textContent = checked;
        
        if (checked > 0) {
            submitBtn.disabled = false;
            submitText.textContent = `Attribuer à ${checked} membre${checked > 1 ? 's' : ''}`;
        } else {
            submitBtn.disabled = true;
            submitText.textContent = 'Sélectionner des membres';
        }
    }

    // Écouter les changements sur les checkboxes
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateCount);
    });

    // Bouton tout sélectionner
    selectAllBtn.addEventListener('click', function() {
        const visibleCheckboxes = document.querySelectorAll('.membre-item:not([style*="display: none"]) .membre-checkbox');
        visibleCheckboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
        updateCount();
    });

    // Bouton tout déselectionner
    deselectAllBtn.addEventListener('click', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateCount();
    });

    // Recherche de membres
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const membreItems = document.querySelectorAll('.membre-item');
        
        membreItems.forEach(item => {
            const name = item.querySelector('.membre-name').textContent.toLowerCase();
            if (name.includes(searchTerm)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Mise à jour initiale
    updateCount();
});
</script>
@endpush
@endsection
