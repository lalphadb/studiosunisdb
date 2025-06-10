<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.cours.show', $cours) }}" class="text-slate-400 hover:text-white">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                Modifier {{ $cours->nom }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-800 border border-slate-700 rounded-lg overflow-hidden">
                <form action="{{ route('admin.cours.update', $cours) }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Informations de base -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nom" class="block text-sm font-medium text-slate-300 mb-2">
                                Nom du cours *
                            </label>
                            <input type="text" name="nom" id="nom" value="{{ old('nom', $cours->nom) }}" required
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('nom')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="type_cours" class="block text-sm font-medium text-slate-300 mb-2">
                                Type de cours *
                            </label>
                            <select name="type_cours" id="type_cours" required
                                    class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Sélectionner un type</option>
                                <option value="karate" {{ old('type_cours', $cours->type_cours) == 'karate' ? 'selected' : '' }}>Karaté</option>
                                <option value="boxe" {{ old('type_cours', $cours->type_cours) == 'boxe' ? 'selected' : '' }}>Boxe</option>
                                <option value="kickboxing" {{ old('type_cours', $cours->type_cours) == 'kickboxing' ? 'selected' : '' }}>Kickboxing</option>
                                <option value="cardiobox" {{ old('type_cours', $cours->type_cours) == 'cardiobox' ? 'selected' : '' }}>Cardiobox</option>
                                <option value="enfants" {{ old('type_cours', $cours->type_cours) == 'enfants' ? 'selected' : '' }}>Enfants</option>
                                <option value="adultes" {{ old('type_cours', $cours->type_cours) == 'adultes' ? 'selected' : '' }}>Adultes</option>
                            </select>
                            @error('type_cours')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-300 mb-2">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="3"
                                  class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $cours->description) }}</textarea>
                        @error('description')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- École et instructeur -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="ecole_id" class="block text-sm font-medium text-slate-300 mb-2">
                                École *
                            </label>
                            <select name="ecole_id" id="ecole_id" required
                                    class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Sélectionner une école</option>
                                @foreach($ecoles as $ecole)
                                    <option value="{{ $ecole->id }}" {{ old('ecole_id', $cours->ecole_id) == $ecole->id ? 'selected' : '' }}>
                                        {{ $ecole->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ecole_id')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="instructeur_id" class="block text-sm font-medium text-slate-300 mb-2">
                                Instructeur
                            </label>
                            <select name="instructeur_id" id="instructeur_id"
                                    class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Sélectionner un instructeur</option>
                                @foreach($instructeurs as $instructeur)
                                    <option value="{{ $instructeur->id }}" {{ old('instructeur_id', $cours->instructeur_id) == $instructeur->id ? 'selected' : '' }}>
                                        {{ $instructeur->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('instructeur_id')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Horaires -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="jour_semaine" class="block text-sm font-medium text-slate-300 mb-2">
                                Jour de la semaine
                            </label>
                            <select name="jour_semaine" id="jour_semaine"
                                    class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Sélectionner un jour</option>
                                <option value="lundi" {{ old('jour_semaine', $cours->jour_semaine) == 'lundi' ? 'selected' : '' }}>Lundi</option>
                                <option value="mardi" {{ old('jour_semaine', $cours->jour_semaine) == 'mardi' ? 'selected' : '' }}>Mardi</option>
                                <option value="mercredi" {{ old('jour_semaine', $cours->jour_semaine) == 'mercredi' ? 'selected' : '' }}>Mercredi</option>
                                <option value="jeudi" {{ old('jour_semaine', $cours->jour_semaine) == 'jeudi' ? 'selected' : '' }}>Jeudi</option>
                                <option value="vendredi" {{ old('jour_semaine', $cours->jour_semaine) == 'vendredi' ? 'selected' : '' }}>Vendredi</option>
                                <option value="samedi" {{ old('jour_semaine', $cours->jour_semaine) == 'samedi' ? 'selected' : '' }}>Samedi</option>
                                <option value="dimanche" {{ old('jour_semaine', $cours->jour_semaine) == 'dimanche' ? 'selected' : '' }}>Dimanche</option>
                            </select>
                            @error('jour_semaine')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="heure_debut" class="block text-sm font-medium text-slate-300 mb-2">
                                Heure de début
                            </label>
                            <input type="time" name="heure_debut" id="heure_debut" 
                                   value="{{ old('heure_debut', $cours->heure_debut ? date('H:i', strtotime($cours->heure_debut)) : '') }}"
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('heure_debut')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="heure_fin" class="block text-sm font-medium text-slate-300 mb-2">
                                Heure de fin
                            </label>
                            <input type="time" name="heure_fin" id="heure_fin" 
                                   value="{{ old('heure_fin', $cours->heure_fin ? date('H:i', strtotime($cours->heure_fin)) : '') }}"
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('heure_fin')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Paramètres du cours -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <label for="capacite_max" class="block text-sm font-medium text-slate-300 mb-2">
                                Capacité max *
                            </label>
                            <input type="number" name="capacite_max" id="capacite_max" value="{{ old('capacite_max', $cours->capacite_max) }}" min="1" max="50" required
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('capacite_max')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="duree_minutes" class="block text-sm font-medium text-slate-300 mb-2">
                                Durée (minutes)
                            </label>
                            <input type="number" name="duree_minutes" id="duree_minutes" value="{{ old('duree_minutes', $cours->duree_minutes) }}" min="30" max="180"
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('duree_minutes')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="age_min" class="block text-sm font-medium text-slate-300 mb-2">
                                Âge minimum
                            </label>
                            <input type="number" name="age_min" id="age_min" value="{{ old('age_min', $cours->age_min) }}" min="3" max="100"
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('age_min')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="age_max" class="block text-sm font-medium text-slate-300 mb-2">
                                Âge maximum
                            </label>
                            <input type="number" name="age_max" id="age_max" value="{{ old('age_max', $cours->age_max) }}" min="3" max="100"
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('age_max')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Statut, niveau et prix -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <label for="status" class="block text-sm font-medium text-slate-300 mb-2">
                                Statut *
                            </label>
                            <select name="status" id="status" required
                                    class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="actif" {{ old('status', $cours->status) == 'actif' ? 'selected' : '' }}>Actif</option>
                                <option value="inactif" {{ old('status', $cours->status) == 'inactif' ? 'selected' : '' }}>Inactif</option>
                                <option value="complet" {{ old('status', $cours->status) == 'complet' ? 'selected' : '' }}>Complet</option>
                            </select>
                            @error('status')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="niveau_requis" class="block text-sm font-medium text-slate-300 mb-2">
                                Niveau requis
                            </label>
                            <input type="text" name="niveau_requis" id="niveau_requis" value="{{ old('niveau_requis', $cours->niveau_requis) }}"
                                   placeholder="Ex: Débutant, Intermédiaire..."
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('niveau_requis')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="prix_mensuel" class="block text-sm font-medium text-slate-300 mb-2">
                                Prix mensuel ($) *
                            </label>
                            <input type="number" name="prix_mensuel" id="prix_mensuel" value="{{ old('prix_mensuel', $cours->prix_mensuel) }}" step="0.01" min="0" required
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('prix_mensuel')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="prix_session" class="block text-sm font-medium text-slate-300 mb-2">
                                Prix par session ($)
                            </label>
                            <input type="number" name="prix_session" id="prix_session" value="{{ old('prix_session', $cours->prix_session) }}" step="0.01" min="0"
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('prix_session')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Dates de session -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="date_debut" class="block text-sm font-medium text-slate-300 mb-2">
                                Date de début de session
                            </label>
                            <input type="date" name="date_debut" id="date_debut" 
                                   value="{{ old('date_debut', $cours->date_debut ? $cours->date_debut->format('Y-m-d') : '') }}"
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('date_debut')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="date_fin" class="block text-sm font-medium text-slate-300 mb-2">
                                Date de fin de session
                            </label>
                            <input type="date" name="date_fin" id="date_fin" 
                                   value="{{ old('date_fin', $cours->date_fin ? $cours->date_fin->format('Y-m-d') : '') }}"
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('date_fin')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-700">
                        <a href="{{ route('admin.cours.show', $cours) }}" 
                           class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-2 rounded-lg transition duration-200">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                            <i class="fas fa-save mr-2"></i>Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
