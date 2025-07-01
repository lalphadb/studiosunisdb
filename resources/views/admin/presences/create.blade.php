@extends('layouts.admin')

@section('title', 'Nouvelle Présence')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <x-module-header 
            module="presences"
            title="Nouvelle Présence" 
            subtitle="Enregistrer la présence d'un membre à un cours"
            create-route="{{ route('admin.presences.index') }}"
            create-text="← Retour"
            create-permission="viewAny,App\Models\Presence"
        />

        <!-- Formulaire sophistiqué -->
        <div class="bg-slate-800/40 backdrop-blur-xl/40 backdrop-blur-xl overflow-hidden shadow-xl sm:rounded-xl border border-slate-700/30/30/20mt-6">
            <div class="p-6">
                <form method="POST" action="{{ route('admin.presences.store') }}" class="space-y-8">
                    @csrf

                    <!-- Section 1: Sélection Cours/Membre -->
                    <div class="bg-slate-750 rounded-xl p-6 border border-slate-600">
                        <h3 class="text-lg font-semibold text-slate-100 tracking-tight flex items-center gap-3">
                            🎯 Sélection du Cours et du Membre
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Cours -->
                            <div>
                                <label for="cours_id" class="block text-sm font-medium text-gray-300 mb-2">
                                    Cours <span class="text-red-400">*</span>
                                </label>
                                <select id="cours_id" name="cours_id" required 
                                        class="w-full rounded-md bg-slate-700 border-slate-600 text-white focus:border-teal-500 focus:ring-teal-500"
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
                                <label for="user_id" class="block text-sm font-medium text-gray-300 mb-2">
                                    Membre <span class="text-red-400">*</span>
                                </label>
                                <select id="user_id" name="user_id" required 
                                        class="w-full rounded-md bg-slate-700 border-slate-600 text-white focus:border-teal-500 focus:ring-teal-500">
                                    <option value="">Sélectionnez d'abord un cours</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" 
                                                data-ecole-id="{{ $user->ecole_id }}"
                                                style="display: none;"
                                                {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Détails de Présence -->
                    <div class="bg-slate-750 rounded-xl p-6 border border-slate-600">
                        <h3 class="text-lg font-semibold text-slate-100 tracking-tight flex items-center gap-3">
                            📋 Détails de la Présence
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Date -->
                            <div>
                                <label for="date_presence" class="block text-sm font-medium text-gray-300 mb-2">
                                    Date de présence <span class="text-red-400">*</span>
                                </label>
                                <input type="date" id="date_presence" name="date_presence" 
                                       value="{{ old('date_presence', date('Y-m-d')) }}" required
                                       class="w-full rounded-md bg-slate-700 border-slate-600 text-white focus:border-teal-500 focus:ring-teal-500">
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
                                        class="w-full rounded-md bg-slate-700 border-slate-600 text-white focus:border-teal-500 focus:ring-teal-500"
                                        onchange="toggleHeureFields()">
                                    <option value="">Sélectionnez un statut</option>
                                    <option value="present" {{ old('statut') == 'present' ? 'selected' : '' }}>✅ Présent</option>
                                    <option value="absent" {{ old('statut') == 'absent' ? 'selected' : '' }}>❌ Absent</option>
                                    <option value="retard" {{ old('statut') == 'retard' ? 'selected' : '' }}>⏰ Retard</option>
                                    <option value="excuse" {{ old('statut') == 'excuse' ? 'selected' : '' }}>📝 Excusé</option>
                                </select>
                                @error('statut')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Heures (affichées conditionnellement) -->
                        <div id="heures-section" class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6" style="display: none;">
                            <div>
                                <label for="heure_arrivee" class="block text-sm font-medium text-gray-300 mb-2">
                                    Heure d'arrivée
                                </label>
                                <input type="time" id="heure_arrivee" name="heure_arrivee" 
                                       value="{{ old('heure_arrivee') }}"
                                       class="w-full rounded-md bg-slate-700 border-slate-600 text-white focus:border-teal-500 focus:ring-teal-500">
                            </div>

                            <div>
                                <label for="heure_depart" class="block text-sm font-medium text-gray-300 mb-2">
                                    Heure de départ
                                </label>
                                <input type="time" id="heure_depart" name="heure_depart" 
                                       value="{{ old('heure_depart') }}"
                                       class="w-full rounded-md bg-slate-700 border-slate-600 text-white focus:border-teal-500 focus:ring-teal-500">
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Notes -->
                    <div class="bg-slate-750 rounded-xl p-6 border border-slate-600">
                        <h3 class="text-lg font-semibold text-slate-100 tracking-tight flex items-center gap-3">
                            📝 Notes et Observations
                        </h3>
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-300 mb-2">
                                Notes (optionnel)
                            </label>
                            <textarea id="notes" name="notes" rows="4" 
                                      class="w-full rounded-md bg-slate-700 border-slate-600 text-white focus:border-teal-500 focus:ring-teal-500"
                                      placeholder="Remarques particulières, comportement, progrès observés...">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-slate-700/30/30/20>
                        <a href="{{ route('admin.presences.index') }}" 
                           class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-xl">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-6 rounded-xl">
                            ✅ Enregistrer la présence
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
    const membreSelect = document.getElementById('user_id');
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
    const membreOptions = document.querySelectorAll('#user_id option[data-ecole-id]');
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
