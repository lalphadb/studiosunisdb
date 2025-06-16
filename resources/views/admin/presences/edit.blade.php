@extends('layouts.admin')

@section('title', 'Présences')

@section('content')
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                {{ __('Modifier Présence') }} ✏️
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('admin.presences.show', $presence) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                    <i class="fas fa-eye mr-2"></i>Voir
                </a>
                <a href="{{ route('admin.presences.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-800 overflow-hidden shadow-xl sm:rounded-lg border border-slate-700">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.presences.update', $presence) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

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
                                            {{ old('cours_id', $presence->cours_id) == $c->id ? 'selected' : '' }}>
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
                                <option value="">Sélectionnez un membre</option>
                                @foreach($membres as $membre)
                                    <option value="{{ $membre->id }}" 
                                            data-ecole-id="{{ $membre->ecole_id }}"
                                            {{ old('membre_id', $presence->membre_id) == $membre->id ? 'selected' : '' }}>
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
                                       value="{{ old('date_presence', $presence->date_presence) }}" required
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
                                    <option value="present" {{ old('statut', $presence->statut) == 'present' ? 'selected' : '' }}>Présent</option>
                                    <option value="absent" {{ old('statut', $presence->statut) == 'absent' ? 'selected' : '' }}>Absent</option>
                                    <option value="retard" {{ old('statut', $presence->statut) == 'retard' ? 'selected' : '' }}>Retard</option>
                                    <option value="excuse" {{ old('statut', $presence->statut) == 'excuse' ? 'selected' : '' }}>Excusé</option>
                                </select>
                                @error('statut')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Heures -->
                        <div id="heures-section" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="heure_arrivee" class="block text-sm font-medium text-gray-300 mb-2">
                                    Heure d'arrivée
                                </label>
                                <input type="time" id="heure_arrivee" name="heure_arrivee" 
                                       value="{{ old('heure_arrivee', $presence->heure_arrivee ? \Carbon\Carbon::parse($presence->heure_arrivee)->format('H:i') : '') }}"
                                       class="w-full rounded-md bg-slate-700 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500">
                                @error('heure_arrivee')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="heure_depart" class="block text-sm font-medium text-gray-300 mb-2">
                                    Heure de départ
                                </label>
                                <input type="time" id="heure_depart" name="heure_depart" 
                                       value="{{ old('heure_depart', $presence->heure_depart ? \Carbon\Carbon::parse($presence->heure_depart)->format('H:i') : '') }}"
                                       class="w-full rounded-md bg-slate-700 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500">
                                @error('heure_depart')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-300 mb-2">
                                Notes (optionnel)
                            </label>
                            <textarea id="notes" name="notes" rows="3" 
                                      class="w-full rounded-md bg-slate-700 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="Remarques particulières...">{{ old('notes', $presence->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-3 pt-6 border-t border-slate-700">
                            <a href="{{ route('admin.presences.show', $presence) }}" 
                               class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg transition duration-150 ease-in-out">
                                Annuler
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-150 ease-in-out">
                                <i class="fas fa-save mr-2"></i>Mettre à jour
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
            const currentMembreId = {{ $presence->membre_id }};
            
            if (!selectedCoursId) {
                return;
            }
            
            const selectedCours = coursSelect.options[coursSelect.selectedIndex];
            const ecoleId = selectedCours.getAttribute('data-ecole-id');
            
            // Masquer tous les membres d'abord
            const allOptions = membreSelect.querySelectorAll('option[data-ecole-id]');
            allOptions.forEach(option => {
                if (option.getAttribute('data-ecole-id') === ecoleId) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                    if (option.selected && option.value != currentMembreId) {
                        option.selected = false;
                    }
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
            }
        }

        // Initialiser à la charge de la page
        document.addEventListener('DOMContentLoaded', function() {
            updateMembres();
            toggleHeureFields();
        });
    </script>
@endsection
