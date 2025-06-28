@extends('layouts.admin')
@section('title', 'Dupliquer le cours')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-6">
                <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center">
                    <span class="text-2xl font-bold text-white">📋</span>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">Dupliquer le cours</h1>
                    <p class="text-purple-100 text-lg">{{ $cours->nom }}</p>
                </div>
            </div>
            <a href="{{ route('admin.cours.show', $cours->id) }}" 
               class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                Annuler
            </a>
        </div>
    </div>

    <!-- Formulaire de duplication -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="bg-purple-600 px-6 py-4">
            <h3 class="text-lg font-semibold text-white">Configuration de la duplication</h3>
        </div>
        
        <form method="POST" action="{{ route('admin.cours.clone', $cours->id) }}" class="p-6">
            @csrf
            
            <!-- Nombre de copies -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">
                    Nombre de cours à créer
                </label>
                <select name="nombre_copies" id="nombre_copies" 
                        class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="1">1 cours</option>
                    <option value="2" selected>2 cours</option>
                    <option value="3">3 cours</option>
                    <option value="4">4 cours</option>
                    <option value="5">5 cours</option>
                    <option value="6">6 cours (toute la semaine)</option>
                    <option value="7">7 cours (tous les jours)</option>
                </select>
            </div>

            <!-- Configuration des copies -->
            <div id="copies-config">
                <!-- Sera rempli dynamiquement -->
            </div>

            <!-- Options avancées -->
            <div class="mt-6 p-4 bg-slate-700 rounded-lg">
                <h4 class="text-white font-medium mb-4">Options avancées</h4>
                
                <div class="flex items-center mb-4">
                    <input type="checkbox" id="modifier_horaires" name="modifier_horaires" value="1" 
                           class="rounded bg-slate-600 border-slate-500 text-purple-600 focus:ring-purple-500">
                    <label for="modifier_horaires" class="ml-2 text-slate-300">
                        Permettre la modification des horaires pour chaque cours
                    </label>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('admin.cours.show', $cours->id) }}" 
                   class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg transition duration-200">
                    Annuler
                </a>
                <button type="submit" 
                        class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg transition duration-200">
                    Créer les cours
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nombreCopies = document.getElementById('nombre_copies');
    const copiesConfig = document.getElementById('copies-config');
    const modifierHoraires = document.getElementById('modifier_horaires');
    
    const jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
    
    function updateCopiesConfig() {
        const nombre = parseInt(nombreCopies.value);
        copiesConfig.innerHTML = '';
        
        for (let i = 0; i < nombre; i++) {
            const jourSuggere = jours[i] || `Jour ${i + 1}`;
            
            const div = document.createElement('div');
            div.className = 'mb-6 p-4 bg-slate-700 rounded-lg';
            div.innerHTML = `
                <h4 class="text-white font-medium mb-4">Cours ${i + 1}</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-2">
                            Suffixe/Nom distinctif
                        </label>
                        <input type="text" name="suffixes[]" value="${jourSuggere}" 
                               class="w-full bg-slate-600 border border-slate-500 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                               placeholder="Ex: ${jourSuggere}, Groupe A, 19h00, etc.">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-2">
                            Jour de la semaine (optionnel)
                        </label>
                        <select name="jours_semaine[]" 
                                class="w-full bg-slate-600 border border-slate-500 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">-- Aucun --</option>
                            <option value="lundi" ${jourSuggere === 'Lundi' ? 'selected' : ''}>Lundi</option>
                            <option value="mardi" ${jourSuggere === 'Mardi' ? 'selected' : ''}>Mardi</option>
                            <option value="mercredi" ${jourSuggere === 'Mercredi' ? 'selected' : ''}>Mercredi</option>
                            <option value="jeudi" ${jourSuggere === 'Jeudi' ? 'selected' : ''}>Jeudi</option>
                            <option value="vendredi" ${jourSuggere === 'Vendredi' ? 'selected' : ''}>Vendredi</option>
                            <option value="samedi" ${jourSuggere === 'Samedi' ? 'selected' : ''}>Samedi</option>
                            <option value="dimanche" ${jourSuggere === 'Dimanche' ? 'selected' : ''}>Dimanche</option>
                        </select>
                    </div>
                </div>
                
                <div class="mt-4 horaires-section" style="display: none;">
                    <label class="block text-sm font-medium text-slate-400 mb-2">
                        Nouveaux horaires (optionnel)
                    </label>
                    <input type="text" name="nouvelles_heures[]" 
                           class="w-full bg-slate-600 border border-slate-500 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                           placeholder="Ex: 19:00 - 20:00, 18:30 - 19:30, etc.">
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
    
    nombreCopies.addEventListener('change', updateCopiesConfig);
    modifierHoraires.addEventListener('change', toggleHorairesSection);
    
    // Initialize
    updateCopiesConfig();
});
</script>
@endsection
