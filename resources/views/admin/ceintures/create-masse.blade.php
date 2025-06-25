@extends('layouts.admin')
@section('title', 'Attribution en Masse')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-orange-500 to-red-600 rounded-xl p-6 text-white">
        <h1 class="text-3xl font-bold flex items-center">
            <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Attribution en Masse de Ceintures
        </h1>
        <p class="text-orange-100 text-lg">Attribuer la même ceinture à plusieurs membres en une fois</p>
    </div>

    <!-- Formulaire -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <form method="POST" action="{{ route('admin.ceintures.store-masse') }}" class="space-y-6">
            @csrf
            
            <!-- Informations examen -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Ceinture -->
                <div>
                    <label for="ceinture_id" class="block text-sm font-medium text-slate-300 mb-2">
                        Ceinture à attribuer <span class="text-red-400">*</span>
                    </label>
                    <select name="ceinture_id" id="ceinture_id" required
                            class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500 @error('ceinture_id') border-red-500 @enderror">
                        <option value="">Sélectionner une ceinture</option>
                        @foreach($ceintures as $ceinture)
                        <option value="{{ $ceinture->id }}" 
                                data-nom="{{ $ceinture->nom }}"
                                {{ old('ceinture_id') == $ceinture->id ? 'selected' : '' }}>
                            {{ $ceinture->nom }} ({{ $ceinture->couleur }})
                        </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="ceinture_nom" id="ceinture_nom" value="{{ old('ceinture_nom') }}">
                    @error('ceinture_id')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date examen -->
                <div>
                    <label for="date_obtention" class="block text-sm font-medium text-slate-300 mb-2">
                        Date de l'examen <span class="text-red-400">*</span>
                    </label>
                    <input type="date" 
                           name="date_obtention" 
                           id="date_obtention"
                           value="{{ old('date_obtention', now()->format('Y-m-d')) }}"
                           max="{{ now()->format('Y-m-d') }}"
                           required
                           class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500 @error('date_obtention') border-red-500 @enderror">
                    @error('date_obtention')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Notes communes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-slate-300 mb-2">
                    Notes communes (optionnel)
                </label>
                <textarea name="notes" 
                          id="notes" 
                          rows="3"
                          placeholder="Notes qui s'appliqueront à tous les membres sélectionnés..."
                          class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                @error('notes')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Sélection des membres -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-4">
                    Membres ayant réussi l'examen <span class="text-red-400">*</span>
                    <span class="text-sm text-slate-400 font-normal">(sélectionnez tous les membres qui ont réussi)</span>
                </label>
                
                <!-- Barre de recherche -->
                <div class="mb-4">
                    <input type="text" 
                           id="search-membres" 
                           placeholder="Rechercher un membre par nom..."
                           class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <!-- Actions de sélection -->
                <div class="flex items-center space-x-4 mb-4">
                    <button type="button" 
                            id="select-all"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                        Sélectionner tous
                    </button>
                    <button type="button" 
                            id="deselect-all"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                        Désélectionner tous
                    </button>
                    <span id="selection-count" class="text-slate-400 text-sm">0 membre(s) sélectionné(s)</span>
                </div>
                  <!-- Liste des membres -->
               <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-96 overflow-y-auto bg-slate-900 p-4 rounded-lg">
                   @foreach($membres as $membre)
                   <div class="membre-item bg-slate-800 border border-slate-700 rounded-lg p-3 hover:border-orange-500 transition-colors">
                       <label class="flex items-center cursor-pointer">
                           <input type="checkbox" 
                                  name="attributions[{{ $loop->index }}][user_id]" 
                                  value="{{ $membre->id }}"
                                  class="w-4 h-4 text-orange-600 bg-slate-700 border-slate-600 rounded focus:ring-orange-500 focus:ring-2 membre-checkbox">
                           <div class="ml-3 flex-1">
                               <div class="text-white font-medium membre-name">{{ $membre->name }}</div>
                               <div class="text-slate-400 text-sm">{{ $membre->ecole->nom ?? 'École non assignée' }}</div>
                               @if($membre->userCeintures->isNotEmpty())
                               <div class="text-xs text-blue-400 mt-1">
                                   Dernière: {{ $membre->userCeintures->sortByDesc('date_obtention')->first()->ceinture->nom ?? 'Aucune' }}
                               </div>
                               @endif
                           </div>
                       </label>
                   </div>
                   @endforeach
               </div>
               @error('attributions')
               <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
               @enderror
           </div>

           <!-- Boutons -->
           <div class="flex items-center justify-between pt-6 border-t border-slate-700">
               <a href="{{ route('admin.ceintures.index') }}" 
                  class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                   Annuler
               </a>
               <button type="submit" 
                       id="submit-btn"
                       disabled
                       class="bg-orange-600 hover:bg-orange-700 text-white px-8 py-3 rounded-lg font-medium transition duration-200 flex items-center disabled:opacity-50 disabled:cursor-not-allowed">
                   <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                   </svg>
                   <span id="submit-text">Sélectionner des membres</span>
               </button>
           </div>
       </form>
   </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
   const ceintureSelect = document.getElementById('ceinture_id');
   const ceintureNomInput = document.getElementById('ceinture_nom');
   const searchInput = document.getElementById('search-membres');
   const selectAllBtn = document.getElementById('select-all');
   const deselectAllBtn = document.getElementById('deselect-all');
   const selectionCount = document.getElementById('selection-count');
   const submitBtn = document.getElementById('submit-btn');
   const submitText = document.getElementById('submit-text');
   const membreItems = document.querySelectorAll('.membre-item');
   const membreCheckboxes = document.querySelectorAll('.membre-checkbox');

   // Mettre à jour le nom de ceinture caché
   ceintureSelect.addEventListener('change', function() {
       const selectedOption = this.options[this.selectedIndex];
       ceintureNomInput.value = selectedOption.dataset.nom || '';
   });

   // Recherche de membres
   searchInput.addEventListener('keyup', function() {
       const searchTerm = this.value.toLowerCase();
       
       membreItems.forEach(function(item) {
           const memberName = item.querySelector('.membre-name').textContent.toLowerCase();
           if (memberName.includes(searchTerm)) {
               item.style.display = 'block';
           } else {
               item.style.display = 'none';
           }
       });
   });

   // Sélectionner tous
   selectAllBtn.addEventListener('click', function() {
       membreCheckboxes.forEach(function(checkbox) {
           const item = checkbox.closest('.membre-item');
           if (item.style.display !== 'none') {
               checkbox.checked = true;
           }
       });
       updateSelectionCount();
   });

   // Désélectionner tous
   deselectAllBtn.addEventListener('click', function() {
       membreCheckboxes.forEach(function(checkbox) {
           checkbox.checked = false;
       });
       updateSelectionCount();
   });

   // Mettre à jour le compteur et le bouton
   membreCheckboxes.forEach(function(checkbox) {
       checkbox.addEventListener('change', updateSelectionCount);
   });

   function updateSelectionCount() {
       const checkedCount = document.querySelectorAll('.membre-checkbox:checked').length;
       selectionCount.textContent = `${checkedCount} membre(s) sélectionné(s)`;
       
       if (checkedCount > 0) {
           submitBtn.disabled = false;
           submitText.textContent = `Attribuer à ${checkedCount} membre(s)`;
           submitBtn.classList.remove('disabled:opacity-50', 'disabled:cursor-not-allowed');
       } else {
           submitBtn.disabled = true;
           submitText.textContent = 'Sélectionner des membres';
           submitBtn.classList.add('disabled:opacity-50', 'disabled:cursor-not-allowed');
       }
   }

   // Initialiser le compteur
   updateSelectionCount();
});
</script>
@endsection
