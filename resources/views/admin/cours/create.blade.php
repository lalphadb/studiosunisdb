@extends('layouts.admin')
@section('title', 'Créer un Cours')

@section('content')
<div class="space-y-6">
    <!-- Header avec couleur violette -->
    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Créer un Cours
                </h1>
                <p class="text-purple-100 text-lg">Créez votre cours personnalisé avec vos propres horaires</p>
            </div>
            <a href="{{ route('admin.cours.index') }}" 
               class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour à la liste
            </a>
        </div>
    </div>

    <!-- Messages d'erreur -->
    @if($errors->any())
    <div class="bg-red-900 border border-red-700 text-red-100 px-4 py-3 rounded-lg">
        <h4 class="font-medium mb-2">Erreurs de validation :</h4>
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Formulaire -->
    <form method="POST" action="{{ route('admin.cours.store') }}" class="space-y-8">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Informations de base -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
                <div class="bg-purple-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Informations de Base
                    </h3>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Nom du cours -->
                    <div>
                        <label for="nom" class="block text-sm font-medium text-slate-300 mb-2">
                            Nom du Cours <span class="text-red-400">*</span>
                        </label>
                        <input type="text" 
                               id="nom" 
                               name="nom" 
                               value="{{ old('nom') }}"
                               placeholder="Ex: Karaté Enfants 6-8 ans, Combat Avancé, Kata Débutant..."
                               required
                               class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        @error('nom')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- École -->
                    <div>
                        <label for="ecole_id" class="block text-sm font-medium text-slate-300 mb-2">
                            École <span class="text-red-400">*</span>
                        </label>
                        <select id="ecole_id" 
                                name="ecole_id" 
                                required
                                class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Sélectionner une école</option>
                            @foreach($ecoles as $ecole)
                                <option value="{{ $ecole->id }}" {{ old('ecole_id') == $ecole->id ? 'selected' : '' }}>
                                    {{ $ecole->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('ecole_id')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-300 mb-2">
                            Description
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="4"
                                  placeholder="Décrivez votre cours : objectifs, spécificités, matériel requis..."
                                  class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Niveau -->
                    <div>
                        <label for="niveau" class="block text-sm font-medium text-slate-300 mb-2">
                            Niveau <span class="text-red-400">*</span>
                        </label>
                        <select id="niveau" 
                                name="niveau" 
                                required
                                class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Sélectionner un niveau</option>
                            @foreach($niveaux as $key => $label)
                                <option value="{{ $key }}" {{ old('niveau') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('niveau')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Instructeur -->
                    <div>
                        <label for="instructeur" class="block text-sm font-medium text-slate-300 mb-2">
                            Instructeur
                        </label>
                        <input type="text" 
                               id="instructeur" 
                               name="instructeur" 
                               value="{{ old('instructeur') }}"
                               placeholder="Nom de l'instructeur principal"
                               class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        @error('instructeur')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Paramètres et Prix -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
                <div class="bg-indigo-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        Paramètres du Cours
                    </h3>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Capacité Maximum -->
                    <div>
                        <label for="capacite_max" class="block text-sm font-medium text-slate-300 mb-2">
                            Capacité Maximum <span class="text-red-400">*</span>
                        </label>
                        <input type="number" 
                               id="capacite_max" 
                               name="capacite_max" 
                               value="{{ old('capacite_max', 20) }}"
                               min="1" 
                               max="100"
                               placeholder="Nombre maximum de participants"
                               required
                               class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        @error('capacite_max')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prix -->
                    <div>
                        <label for="prix" class="block text-sm font-medium text-slate-300 mb-2">
                            Prix par Session (CAD)
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-slate-400">$</span>
                            <input type="number" 
                                   id="prix" 
                                   name="prix" 
                                   value="{{ old('prix') }}"
                                   step="0.01"
                                   min="0"
                                   placeholder="0.00"
                                   class="w-full bg-slate-700 border border-slate-600 rounded-lg pl-8 pr-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Laissez vide si gratuit</p>
                        @error('prix')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Durée -->
                    <div>
                        <label for="duree_minutes" class="block text-sm font-medium text-slate-300 mb-2">
                            Durée (minutes) <span class="text-red-400">*</span>
                        </label>
                        <select id="duree_minutes" 
                                name="duree_minutes" 
                                required
                                class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Sélectionner la durée</option>
                            <option value="30" {{ old('duree_minutes') == '30' ? 'selected' : '' }}>30 minutes</option>
                            <option value="45" {{ old('duree_minutes') == '45' ? 'selected' : '' }}>45 minutes</option>
                            <option value="60" {{ old('duree_minutes') == '60' ? 'selected' : '' }}>60 minutes</option>
                            <option value="90" {{ old('duree_minutes') == '90' ? 'selected' : '' }}>90 minutes</option>
                            <option value="120" {{ old('duree_minutes') == '120' ? 'selected' : '' }}>120 minutes</option>
                            <option value="180" {{ old('duree_minutes') == '180' ? 'selected' : '' }}>180 minutes (3h)</option>
                        </select>
                        @error('duree_minutes')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cours actif -->
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="active" 
                               name="active" 
                               value="1" 
                               {{ old('active', true) ? 'checked' : '' }}
                               class="rounded border-slate-600 text-purple-600 focus:ring-purple-500 bg-slate-700">
                        <label for="active" class="ml-2 text-slate-300">Cours actif (visible pour les inscriptions)</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION HORAIRES PERSONNALISABLES -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Créez Vos Horaires Personnalisés
                </h3>
            </div>
            <div class="p-6">
                <div class="bg-blue-900/20 border border-blue-500/30 rounded-lg p-4 mb-6">
                    <h4 class="text-blue-300 font-medium mb-2">💡 Créez vos horaires selon vos besoins :</h4>
                    <div class="text-sm text-blue-200 space-y-1">
                        <p>• <strong>Format 24H obligatoire</strong> (ex: 18:00, 19:30)</p>
                        <p>• <strong>Ajoutez autant d'horaires que nécessaire</strong> pour votre cours</p>
                        <p>• <strong>Chaque école peut avoir sa propre organisation</strong></p>
                        <p>• <strong>Les utilisateurs s'inscriront sur vos créneaux spécifiques</strong></p>
                    </div>
                </div>
                
                <div id="horaires-container">
                    <!-- Premier horaire par défaut -->
                    <div class="horaire-item bg-slate-700 rounded-lg p-4 mb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-white font-medium">Créneau 1</h4>
                            <button type="button" class="text-red-400 hover:text-red-300 remove-horaire" onclick="removeHoraire(this)">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Jour -->
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Jour de la semaine</label>
                                <select name="horaires[0][jour_semaine]" class="w-full bg-slate-600 border border-slate-500 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    <option value="lundi">Lundi</option>
                                    <option value="mardi">Mardi</option>
                                    <option value="mercredi">Mercredi</option>
                                    <option value="jeudi">Jeudi</option>
                                    <option value="vendredi">Vendredi</option>
                                    <option value="samedi">Samedi</option>
                                    <option value="dimanche">Dimanche</option>
                                </select>
                            </div>
                            
                            <!-- Heure début -->
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Heure de début (24H)</label>
                                <input type="time" 
                                       name="horaires[0][heure_debut]" 
                                       value="18:00"
                                       class="w-full bg-slate-600 border border-slate-500 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                            </div>
                            
                            <!-- Heure fin -->
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Heure de fin (24H)</label>
                                <input type="time" 
                                       name="horaires[0][heure_fin]" 
                                       value="19:00"
                                       class="w-full bg-slate-600 border border-slate-500 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Bouton ajouter horaire -->
                <button type="button" 
                        onclick="addHoraire()" 
                        class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Ajouter un Créneau Horaire
                </button>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="flex items-center justify-between bg-slate-800 rounded-xl border border-slate-700 px-6 py-4">
            <a href="{{ route('admin.cours.index') }}" 
               class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Annuler
            </a>
            
            <button type="submit" 
                    class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Créer Mon Cours
            </button>
        </div>
    </form>
</div>

<script>
let horaireIndex = 1;

function addHoraire() {
    const container = document.getElementById('horaires-container');
    const horaireItem = document.createElement('div');
    horaireItem.className = 'horaire-item bg-slate-700 rounded-lg p-4 mb-4';
    horaireItem.innerHTML = `
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-white font-medium">Créneau ${horaireIndex + 1}</h4>
            <button type="button" class="text-red-400 hover:text-red-300 remove-horaire" onclick="removeHoraire(this)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Jour de la semaine</label>
                <select name="horaires[${horaireIndex}][jour_semaine]" class="w-full bg-slate-600 border border-slate-500 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="lundi">Lundi</option>
                    <option value="mardi">Mardi</option>
                    <option value="mercredi">Mercredi</option>
                    <option value="jeudi">Jeudi</option>
                    <option value="vendredi">Vendredi</option>
                    <option value="samedi">Samedi</option>
                    <option value="dimanche">Dimanche</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Heure de début (24H)</label>
                <input type="time" name="horaires[${horaireIndex}][heure_debut]" value="18:00" class="w-full bg-slate-600 border border-slate-500 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Heure de fin (24H)</label>
                <input type="time" name="horaires[${horaireIndex}][heure_fin]" value="19:00" class="w-full bg-slate-600 border border-slate-500 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
        </div>
    `;
    
    container.appendChild(horaireItem);
    horaireIndex++;
}

function removeHoraire(button) {
    const horaireItem = button.closest('.horaire-item');
    horaireItem.remove();
    
    // Renuméroter les créneaux
    const horaires = document.querySelectorAll('.horaire-item');
    horaires.forEach((item, index) => {
        const title = item.querySelector('h4');
        title.textContent = `Créneau ${index + 1}`;
    });
}
</script>
@endsection
