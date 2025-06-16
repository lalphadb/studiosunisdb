@extends("layouts.admin")

@section("content")
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                {{ __('Prise de Présence') }} ⚡ - {{ $cours->nom }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('admin.cours.show', $cours) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                    <i class="fas fa-eye mr-2"></i>Voir le cours
                </a>
                <a href="{{ route('admin.presences.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Info du cours -->
            <div class="bg-slate-800 rounded-lg p-6 mb-6 border border-slate-700">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="flex items-center">
                        <i class="fas fa-graduation-cap text-2xl text-yellow-400 mr-3"></i>
                        <div>
                            <p class="text-sm text-gray-400">Cours</p>
                            <p class="font-semibold text-white">{{ $cours->nom }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-school text-2xl text-purple-400 mr-3"></i>
                        <div>
                            <p class="text-sm text-gray-400">École</p>
                            <p class="font-semibold text-white">{{ $cours->ecole->nom }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-users text-2xl text-green-400 mr-3"></i>
                        <div>
                            <p class="text-sm text-gray-400">Membres inscrits</p>
                            <p class="font-semibold text-white">{{ $membres->count() }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-calendar text-2xl text-blue-400 mr-3"></i>
                        <div>
                            <p class="text-sm text-gray-400">Date</p>
                            <p class="font-semibold text-white">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.presences.store-prise-presence', $cours) }}">
                @csrf
                
                <!-- Sélection de date -->
                <div class="bg-slate-800 rounded-lg p-6 mb-6 border border-slate-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <label for="date_presence" class="text-sm font-medium text-gray-300">
                                Date de la séance :
                            </label>
                            <input type="date" id="date_presence" name="date_presence" 
                                   value="{{ $date }}" 
                                   onchange="window.location.href='{{ route('admin.presences.prise-presence', $cours) }}?date=' + this.value"
                                   class="rounded-md bg-slate-700 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        
                        <div class="flex space-x-2">
                            <button type="button" onclick="marqueTousPresents()" 
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                                <i class="fas fa-check-double mr-1"></i>Tous présents
                            </button>
                            <button type="button" onclick="reinitialiser()" 
                                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm">
                                <i class="fas fa-undo mr-1"></i>Réinitialiser
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Liste des membres -->
                <div class="bg-slate-800 rounded-lg border border-slate-700 overflow-hidden">
                    <div class="p-6 border-b border-slate-700">
                        <h3 class="text-lg font-medium text-gray-100">
                            <i class="fas fa-clipboard-list mr-2 text-blue-400"></i>Liste de présence
                        </h3>
                    </div>

                    @if($membres->count() > 0)
                        <div class="divide-y divide-slate-700">
                            @foreach($membres as $index => $membre)
                                @php
                                    $presenceExistante = $presencesExistantes->get($membre->id);
                                    $statutActuel = $presenceExistante ? $presenceExistante->statut : 'present';
                                    $heureActuelle = $presenceExistante ? $presenceExistante->heure_arrivee : '';
                                @endphp
                                
                                <div class="p-4 hover:bg-slate-700 transition-colors duration-150">
                                    <div class="flex items-center justify-between">
                                        <!-- Info membre -->
                                        <div class="flex items-center space-x-4 flex-1">
                                            <div class="w-12 h-12 bg-slate-600 rounded-full flex items-center justify-center text-white font-bold">
                                                {{ strtoupper(substr($membre->prenom, 0, 1) . substr($membre->nom, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-white">
                                                    {{ $membre->prenom }} {{ $membre->nom }}
                                                </p>
                                                <p class="text-sm text-gray-400">{{ $membre->email }}</p>
                                            </div>
                                        </div>

                                        <input type="hidden" name="presences[{{ $index }}][membre_id]" value="{{ $membre->id }}">

                                        <!-- Statut -->
                                        <div class="flex items-center space-x-4">
                                            <div class="flex items-center space-x-2">
                                                <label class="inline-flex items-center">
                                                    <input type="radio" 
                                                           name="presences[{{ $index }}][statut]" 
                                                           value="present" 
                                                           {{ $statutActuel == 'present' ? 'checked' : '' }}
                                                           class="form-radio text-green-600 bg-slate-700 border-slate-600 focus:ring-green-500"
                                                           onchange="toggleHeureInput({{ $index }}, true)">
                                                    <span class="ml-2 text-green-400 text-sm">
                                                        <i class="fas fa-check-circle mr-1"></i>Présent
                                                    </span>
                                                </label>
                                            </div>

                                            <div class="flex items-center space-x-2">
                                                <label class="inline-flex items-center">
                                                    <input type="radio" 
                                                           name="presences[{{ $index }}][statut]" 
                                                           value="retard" 
                                                           {{ $statutActuel == 'retard' ? 'checked' : '' }}
                                                           class="form-radio text-orange-600 bg-slate-700 border-slate-600 focus:ring-orange-500"
                                                           onchange="toggleHeureInput({{ $index }}, true)">
                                                    <span class="ml-2 text-orange-400 text-sm">
                                                        <i class="fas fa-clock mr-1"></i>Retard
                                                    </span>
                                                </label>
                                            </div>

                                            <div class="flex items-center space-x-2">
                                                <label class="inline-flex items-center">
                                                    <input type="radio" 
                                                           name="presences[{{ $index }}][statut]" 
                                                           value="absent" 
                                                           {{ $statutActuel == 'absent' ? 'checked' : '' }}
                                                           class="form-radio text-red-600 bg-slate-700 border-slate-600 focus:ring-red-500"
                                                           onchange="toggleHeureInput({{ $index }}, false)">
                                                    <span class="ml-2 text-red-400 text-sm">
                                                        <i class="fas fa-times-circle mr-1"></i>Absent
                                                    </span>
                                                </label>
                                            </div>

                                            <div class="flex items-center space-x-2">
                                                <label class="inline-flex items-center">
                                                    <input type="radio" 
                                                           name="presences[{{ $index }}][statut]" 
                                                           value="excuse" 
                                                           {{ $statutActuel == 'excuse' ? 'checked' : '' }}
                                                           class="form-radio text-blue-600 bg-slate-700 border-slate-600 focus:ring-blue-500"
                                                           onchange="toggleHeureInput({{ $index }}, false)">
                                                    <span class="ml-2 text-blue-400 text-sm">
                                                        <i class="fas fa-user-check mr-1"></i>Excusé
                                                    </span>
                                                </label>
                                            </div>

                                            <!-- Heure d'arrivée -->
                                            <div class="heure-input-{{ $index }} {{ in_array($statutActuel, ['present', 'retard']) ? '' : 'hidden' }}">
                                                <input type="time" 
                                                       name="presences[{{ $index }}][heure_arrivee]" 
                                                       value="{{ $heureActuelle ? \Carbon\Carbon::parse($heureActuelle)->format('H:i') : '' }}"
                                                       class="w-20 text-sm rounded-md bg-slate-700 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500"
                                                       placeholder="HH:MM">
                                            </div>

                                            <!-- Notes rapides -->
                                            <div class="w-32">
                                                <input type="text" 
                                                       name="presences[{{ $index }}][notes]" 
                                                       value="{{ $presenceExistante ? $presenceExistante->notes : '' }}"
                                                       placeholder="Notes..."
                                                       class="w-full text-sm rounded-md bg-slate-700 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Actions -->
                        <div class="p-6 bg-slate-700 border-t border-slate-600">
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-400">
                                    {{ $membres->count() }} membre(s) dans ce cours
                                </div>
                                
                                <div class="flex space-x-3">
                                    <a href="{{ route('admin.cours.show', $cours) }}" 
                                       class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition duration-150 ease-in-out">
                                        Annuler
                                    </a>
                                    <button type="submit" 
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-150 ease-in-out">
                                        <i class="fas fa-save mr-2"></i>Enregistrer les présences
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="p-12 text-center">
                            <i class="fas fa-users text-4xl text-gray-500 mb-4"></i>
                            <p class="text-gray-400 text-lg">Aucun membre inscrit à ce cours</p>
                            <p class="text-gray-500 mt-2">Ajoutez des membres au cours pour pouvoir prendre les présences.</p>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleHeureInput(index, show) {
            const heureDiv = document.querySelector('.heure-input-' + index);
            if (show) {
                heureDiv.classList.remove('hidden');
            } else {
                heureDiv.classList.add('hidden');
                // Vider la valeur
                heureDiv.querySelector('input').value = '';
            }
        }

        function marqueTousPresents() {
            const presentRadios = document.querySelectorAll('input[type="radio"][value="present"]');
            presentRadios.forEach((radio, index) => {
                radio.checked = true;
                toggleHeureInput(index, true);
            });
        }

        function reinitialiser() {
            const allRadios = document.querySelectorAll('input[type="radio"]');
            allRadios.forEach(radio => {
                radio.checked = false;
            });
            
            const heureInputs = document.querySelectorAll('[class*="heure-input-"]');
            heureInputs.forEach(div => {
                div.classList.add('hidden');
                div.querySelector('input').value = '';
            });
            
            const notesInputs = document.querySelectorAll('input[name*="[notes]"]');
            notesInputs.forEach(input => {
                input.value = '';
            });
        }
    </script>
@endsection
