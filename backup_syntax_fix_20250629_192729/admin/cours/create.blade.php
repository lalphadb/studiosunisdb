@extends('layouts.admin')
@section('title', 'Créer un cours')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-6">
                <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center">
                    <span class="text-2xl font-bold text-white">📚</span>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">Créer un nouveau cours</h1>
                    <p class="text-purple-100 text-lg">Ajouter un cours à votre école</p>
                </div>
            </div>
            <a href="{{ route('admin.cours.index') }}" 
               class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                Retour à la liste
            </a>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="bg-purple-600 px-6 py-4">
            <h3 class="text-lg font-semibold text-white">Informations du cours</h3>
        </div>
        
        <form method="POST" action="{{ route('admin.cours.store') }}" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Colonne 1 - Informations de base -->
                <div class="space-y-6">
                    <!-- Nom du cours -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Nom du cours *
                        </label>
                        <input type="text" name="nom" value="{{ old('nom') }}" required
                               class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 placeholder-slate-400"
                               placeholder="Ex: Parents-Enfants, Karaté Débutant, etc.">
                        @error('nom')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- École -->
                    @if(auth()->user()->hasRole('superadmin'))
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            École *
                        </label>
                        <select name="ecole_id" required
                                class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="" class="bg-slate-700 text-slate-400">Sélectionner une école</option>
                            @foreach($ecoles as $ecole)
                                <option value="{{ $ecole->id }}" {{ old('ecole_id') == $ecole->id ? 'selected' : '' }} class="bg-slate-700 text-white">
                                    {{ $ecole->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('ecole_id')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    @endif

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Description
                        </label>
                        <textarea name="description" rows="3"
                                  class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 placeholder-slate-400"
                                  placeholder="Description du cours...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Niveau -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Niveau
                        </label>
                        <select name="niveau"
                                class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="" class="bg-slate-700 text-slate-400">-- Sélectionner --</option>
                            <option value="debutant" {{ old('niveau') == 'debutant' ? 'selected' : '' }} class="bg-slate-700 text-white">Débutant</option>
                            <option value="intermediaire" {{ old('niveau') == 'intermediaire' ? 'selected' : '' }} class="bg-slate-700 text-white">Intermédiaire</option>
                            <option value="avance" {{ old('niveau') == 'avance' ? 'selected' : '' }} class="bg-slate-700 text-white">Avancé</option>
                            <option value="tous_niveaux" {{ old('niveau') == 'tous_niveaux' ? 'selected' : '' }} class="bg-slate-700 text-white">Tous niveaux</option>
                        </select>
                        @error('niveau')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Colonne 2 - Détails techniques -->
                <div class="space-y-6">
                    <!-- Instructeur -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Instructeur
                        </label>
                        <input type="text" name="instructeur" value="{{ old('instructeur') }}"
                               class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 placeholder-slate-400"
                               placeholder="Nom de l'instructeur">
                        @error('instructeur')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prix -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Prix ($)
                        </label>
                        <input type="number" name="prix" value="{{ old('prix') }}" step="0.01" min="0"
                               class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 placeholder-slate-400"
                               placeholder="0.00">
                        @error('prix')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Capacité maximale -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Capacité maximale
                        </label>
                        <input type="number" name="capacite_max" value="{{ old('capacite_max') }}" min="1"
                               class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 placeholder-slate-400"
                               placeholder="Nombre de participants">
                        @error('capacite_max')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Durée -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Durée (minutes)
                        </label>
                        <input type="number" name="duree_minutes" value="{{ old('duree_minutes') }}" min="1"
                               class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 placeholder-slate-400"
                               placeholder="60">
                        @error('duree_minutes')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Statut -->
                    <div class="flex items-center">
                        <input type="checkbox" name="active" value="1" {{ old('active', true) ? 'checked' : '' }}
                               class="w-4 h-4 text-purple-600 bg-slate-700 border-slate-600 rounded focus:ring-purple-500 focus:ring-2">
                        <label class="ml-3 text-slate-300">Cours actif</label>
                    </div>
                </div>
            </div>

            <!-- Section Duplication -->
            <div class="mt-8 pt-8 border-t border-slate-600">
                <div class="flex items-center mb-6">
                    <input type="checkbox" id="enable_duplication" name="enable_duplication" value="1" 
                           {{ old('enable_duplication') ? 'checked' : '' }}
                           class="w-4 h-4 text-purple-600 bg-slate-700 border-slate-600 rounded focus:ring-purple-500 focus:ring-2">
                    <label for="enable_duplication" class="ml-3 text-white font-medium">
                        🔄 Créer plusieurs cours similaires (duplication)
                    </label>
                </div>

                <div id="duplication-section" style="display: {{ old('enable_duplication') ? 'block' : 'none' }};" class="bg-slate-700 border border-slate-600 rounded-lg p-6">
                    <h4 class="text-white font-medium mb-4">Configuration de la duplication</h4>
                    
                    <!-- Nombre de copies -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Nombre total de cours à créer
                        </label>
                        <select name="nombre_copies" id="nombre_copies" 
                                class="w-full bg-slate-600 border border-slate-500 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="1" {{ old('nombre_copies', 2) == 1 ? 'selected' : '' }} class="bg-slate-600 text-white">1 cours</option>
                            <option value="2" {{ old('nombre_copies', 2) == 2 ? 'selected' : '' }} class="bg-slate-600 text-white">2 cours</option>
                            <option value="3" {{ old('nombre_copies', 2) == 3 ? 'selected' : '' }} class="bg-slate-600 text-white">3 cours</option>
                            <option value="4" {{ old('nombre_copies', 2) == 4 ? 'selected' : '' }} class="bg-slate-600 text-white">4 cours</option>
                            <option value="5" {{ old('nombre_copies', 2) == 5 ? 'selected' : '' }} class="bg-slate-600 text-white">5 cours</option>
                            <option value="6" {{ old('nombre_copies', 2) == 6 ? 'selected' : '' }} class="bg-slate-600 text-white">6 cours</option>
                            <option value="7" {{ old('nombre_copies', 2) == 7 ? 'selected' : '' }} class="bg-slate-600 text-white">7 cours</option>
                        </select>
                    </div>

                    <!-- Configuration des copies -->
                    <div id="copies-config">
                        <!-- Sera rempli dynamiquement -->
                    </div>

                    <!-- Options avancées -->
                    <div class="mt-6 p-4 bg-slate-600 border border-slate-500 rounded-lg">
                        <h5 class="text-white font-medium mb-4">Options avancées</h5>
                        
                        <div class="flex items-center">
                            <input type="checkbox" id="modifier_horaires" name="modifier_horaires" value="1" 
                                   {{ old('modifier_horaires') ? 'checked' : '' }}
                                   class="w-4 h-4 text-purple-600 bg-slate-700 border-slate-500 rounded focus:ring-purple-500 focus:ring-2">
                            <label for="modifier_horaires" class="ml-3 text-slate-300">
                                Permettre la modification des horaires pour chaque cours
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('admin.cours.index') }}" 
                   class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg transition duration-200">
                    Annuler
                </a>
                <button type="submit" 
                        class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg transition duration-200">
                    Créer le(s) cours
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const enableDuplication = document.getElementById('enable_duplication');
    const duplicationSection = document.getElementById('duplication-section');
    const nombreCopies = document.getElementById('nombre_copies');
    const copiesConfig = document.getElementById('copies-config');
    const modifierHoraires = document.getElementById('modifier_horaires');
    
    const jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
    
    function toggleDuplicationSection() {
        duplicationSection.style.display = enableDuplication.checked ? 'block' : 'none';
        if (enableDuplication.checked) {
            updateCopiesConfig();
        }
    }
    
    function updateCopiesConfig() {
        const nombre = parseInt(nombreCopies.value);
        copiesConfig.innerHTML = '';
        
        for (let i = 0; i < nombre; i++) {
            const jourSuggere = jours[i] || `Cours ${i + 1}`;
            
            const div = document.createElement('div');
            div.className = 'mb-6 p-4 bg-slate-600 border border-slate-500 rounded-lg';
            div.innerHTML = `
                <h5 class="text-white font-medium mb-4">Cours ${i + 1}</h5>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Suffixe/Nom distinctif
                        </label>
                        <input type="text" name="suffixes[]" value="${jourSuggere}" 
                               class="w-full !bg-slate-700 !border-slate-600 !text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 placeholder:text-slate-400"
                               placeholder="Ex: ${jourSuggere}, Groupe A, 19h00, etc."
                               style="background-color: rgb(51 65 85) !important; border-color: rgb(71 85 105) !important; color: white !important;">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Jour de la semaine (optionnel)
                        </label>
                        <select name="jours_semaine[]" 
                                class="w-full !bg-slate-700 !border-slate-600 !text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                                style="background-color: rgb(51 65 85) !important; border-color: rgb(71 85 105) !important; color: white !important;">
                            <option value="" style="background-color: rgb(51 65 85) !important; color: rgb(148 163 184) !important;">-- Aucun --</option>
                            <option value="lundi" ${jourSuggere === 'Lundi' ? 'selected' : ''} style="background-color: rgb(51 65 85) !important; color: white !important;">Lundi</option>
                            <option value="mardi" ${jourSuggere === 'Mardi' ? 'selected' : ''} style="background-color: rgb(51 65 85) !important; color: white !important;">Mardi</option>
                            <option value="mercredi" ${jourSuggere === 'Mercredi' ? 'selected' : ''} style="background-color: rgb(51 65 85) !important; color: white !important;">Mercredi</option>
                            <option value="jeudi" ${jourSuggere === 'Jeudi' ? 'selected' : ''} style="background-color: rgb(51 65 85) !important; color: white !important;">Jeudi</option>
                            <option value="vendredi" ${jourSuggere === 'Vendredi' ? 'selected' : ''} style="background-color: rgb(51 65 85) !important; color: white !important;">Vendredi</option>
                            <option value="samedi" ${jourSuggere === 'Samedi' ? 'selected' : ''} style="background-color: rgb(51 65 85) !important; color: white !important;">Samedi</option>
                            <option value="dimanche" ${jourSuggere === 'Dimanche' ? 'selected' : ''} style="background-color: rgb(51 65 85) !important; color: white !important;">Dimanche</option>
                        </select>
                    </div>
                </div>
                
                <div class="mt-4 horaires-section" style="display: none;">
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Nouveaux horaires (optionnel)
                    </label>
                    <input type="text" name="nouvelles_heures[]" 
                           class="w-full !bg-slate-700 !border-slate-600 !text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 placeholder:text-slate-400"
                           placeholder="Ex: 19:00 - 20:00, 18:30 - 19:30, etc."
                           style="background-color: rgb(51 65 85) !important; border-color: rgb(71 85 105) !important; color: white !important;">
                </div>
            `;
            
            copiesConfig.appendChild(div);
        }
        
        toggleHorairesSection();
    }
    
    function toggleHorairesSection() {
        const horaireSections = document.querySelectorAll('.horaires-section');
        horaireSections.forEach(section => {
            section.style.display = modifierHoraires.checked ? 'block' : 'none';
        });
    }
    
    enableDuplication.addEventListener('change', toggleDuplicationSection);
    nombreCopies.addEventListener('change', updateCopiesConfig);
    modifierHoraires.addEventListener('change', toggleHorairesSection);
    
    // Initialize
    toggleDuplicationSection();
});
</script>
@endsection
