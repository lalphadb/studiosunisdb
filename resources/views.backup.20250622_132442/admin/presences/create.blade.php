@extends('layouts.admin')

@section('title', 'Nouvelle Présence')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-slate-800 rounded-lg p-6 mb-6 border border-slate-700">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-100">Nouvelle Présence ➕</h1>
                    <p class="text-gray-400 mt-1">Enregistrer la présence d'un membre à un cours</p>
                </div>
                <a href="{{ route('admin.presences.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="bg-slate-800 overflow-hidden shadow-xl sm:rounded-lg border border-slate-700">
            <div class="p-6">
                <form method="POST" action="{{ route('admin.presences.store') }}" class="space-y-6">
                    @csrf

                    <!-- Cours -->
                    <div>
                        <label for="cours_id" class="block text-sm font-medium text-gray-300 mb-2">
                            Cours <span class="text-red-400">*</span>
                        </label>
                        <select id="cours_id" name="cours_id" required 
                                class="w-full rounded-md bg-slate-700 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500"
                                onchange="updateMembres()">
                            <option value="">Sélectionnez un cours</option>
                            @foreach($cours as $c)
                                <option value="{{ $c->id }}" 
                                        data-ecole-id="{{ $c->ecole_id }}"
                                        {{ old('cours_id', $selectedCours) == $c->id ? 'selected' : '' }}>
                                    {{ $c->nom }} - {{ $c->ecole->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('cours_id')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Membre -->
                    <div>
                        <label for="membre_id" class="block text-sm font-medium text-gray-300 mb-2">
                            Membre <span class="text-red-400">*</span>
                        </label>
                        <select id="membre_id" name="membre_id" required 
                                class="w-full rounded-md bg-slate-700 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Sélectionnez d'abord un cours</option>
                            @foreach($membres as $membre)
                                <option value="{{ $membre->id }}" 
                                        data-ecole-id="{{ $membre->ecole_id }}"
                                        style="display: none;"
                                        {{ old('membre_id') == $membre->id ? 'selected' : '' }}>
                                    {{ $membre->prenom }} {{ $membre->nom }} ({{ $membre->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('membre_id')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Date -->
                        <div>
                            <label for="date_presence" class="block text-sm font-medium text-gray-300 mb-2">
                                Date de présence <span class="text-red-400">*</span>
                            </label>
                            <input type="date" id="date_presence" name="date_presence" 
                                   value="{{ old('date_presence', date('Y-m-d')) }}" required
                                   class="w-full rounded-md bg-slate-700 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500">
                            @error('date_presence')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Statut -->
                        <div>
                            <label for="statut" class="block text-sm font-medium text-gray-300 mb-2">
                                Statut <span class="text-red-400">*</span>
                            </label>
                            <select id="statut" name="statut" required 
                                    class="w-full rounded-md bg-slate-700 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500"
                                    onchange="toggleHeureFields()">
                                <option value="">Sélectionnez un statut</option>
                                <option value="present" {{ old('statut') == 'present' ? 'selected' : '' }}>Présent</option>
                                <option value="absent" {{ old('statut') == 'absent' ? 'selected' : '' }}>Absent</option>
                                <option value="retard" {{ old('statut') == 'retard' ? 'selected' : '' }}>Retard</option>
                                <option value="excuse" {{ old('statut') == 'excuse' ? 'selected' : '' }}>Excusé</option>
                            </select>
                            @error('statut')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Heures -->
                    <div id="heures-section" class="grid grid-cols-1 md:grid-cols-2 gap-6" style="display: none;">
                        <div>
                            <label for="heure_arrivee" class="block text-sm font-medium text-gray-300 mb-2">
                                Heure d'arrivée
                            </label>
                            <input type="time" id="heure_arrivee" name="heure_arrivee" 
                                   value="{{ old('heure_arrivee') }}"
                                   class="w-full rounded-md bg-slate-700 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="heure_depart" class="block text-sm font-medium text-gray-300 mb-2">
                                Heure de départ
                            </label>
                            <input type="time" id="heure_depart" name="heure_depart" 
                                   value="{{ old('heure_depart') }}"
                                   class="w-full rounded-md bg-slate-700 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-300 mb-2">
                            Notes (optionnel)
                        </label>
                        <textarea id="notes" name="notes" rows="3" 
                                  class="w-full rounded-md bg-slate-700 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="Remarques particulières...">{{ old('notes') }}</textarea>
                    </div>

                    <div class="flex justify-end space-x-3 pt-6 border-t border-slate-700">
                        <a href="{{ route('admin.presences.index') }}" 
                           class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                            <i class="fas fa-save mr-2"></i>Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function updateMembres() {
    const coursSelect = document.getElementById('cours_id');
    const membreSelect = document.getElementById('membre_id');
    const selectedCoursId = coursSelect.value;
    
    if (!selectedCoursId) {
        membreSelect.innerHTML = '<option value="">Sélectionnez d\'abord un cours</option>';
        return;
    }
    
    const selectedCours = coursSelect.options[coursSelect.selectedIndex];
    const ecoleId = selectedCours.getAttribute('data-ecole-id');
    
    // Réinitialiser le select des membres
    membreSelect.innerHTML = '<option value="">Sélectionnez un membre</option>';
    
    // Afficher seulement les membres de la même école
    const membreOptions = document.querySelectorAll('#membre_id option[data-ecole-id]');
    membreOptions.forEach(option => {
        if (option.getAttribute('data-ecole-id') === ecoleId) {
            const newOption = option.cloneNode(true);
            newOption.style.display = '';
            membreSelect.appendChild(newOption);
        }
    });
}

function toggleHeureFields() {
    const statutSelect = document.getElementById('statut');
    const heuresSection = document.getElementById('heures-section');
    const statut = statutSelect.value;
    
    if (statut === 'present' || statut === 'retard') {
        heuresSection.style.display = 'block';
    } else {
        heuresSection.style.display = 'none';
        document.getElementById('heure_arrivee').value = '';
        document.getElementById('heure_depart').value = '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    updateMembres();
    toggleHeureFields();
});
</script>
@endsection
