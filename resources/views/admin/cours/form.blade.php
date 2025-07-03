<div class="space-y-6">
    <!-- Informations de base -->
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            📚 Informations de base
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nom du cours -->
            <div>
                <label for="nom" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <span class="text-red-500">*</span> Nom du cours
                </label>
                <input type="text" 
                       id="nom" 
                       name="nom" 
                       value="{{ old('nom', $cours->nom ?? '') }}"
                       placeholder="Ex: Karaté Adultes, Enfants Débutants..."
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 dark:bg-gray-700 dark:text-white @error('nom') border-red-500 @enderror"
                       required>
                @error('nom')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Niveau -->
            <div>
                <label for="niveau" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <span class="text-red-500">*</span> Niveau requis
                </label>
                <select id="niveau" 
                        name="niveau" 
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 dark:bg-gray-700 dark:text-white @error('niveau') border-red-500 @enderror"
                        required>
                    <option value="">Sélectionner le niveau...</option>
                    <option value="debutant" {{ old('niveau', $cours->niveau ?? '') === 'debutant' ? 'selected' : '' }}>
                        🟢 Débutant
                    </option>
                    <option value="intermediaire" {{ old('niveau', $cours->niveau ?? '') === 'intermediaire' ? 'selected' : '' }}>
                        🟡 Intermédiaire
                    </option>
                    <option value="avance" {{ old('niveau', $cours->niveau ?? '') === 'avance' ? 'selected' : '' }}>
                        🔴 Avancé
                    </option>
                    <option value="tous_niveaux" {{ old('niveau', $cours->niveau ?? '') === 'tous_niveaux' ? 'selected' : '' }}>
                        🔵 Tous niveaux
                    </option>
                </select>
                @error('niveau')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Durée -->
            <div>
                <label for="duree_minutes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <span class="text-red-500">*</span> Durée (minutes)
                </label>
                <input type="number" 
                       id="duree_minutes" 
                       name="duree_minutes" 
                       value="{{ old('duree_minutes', $cours->duree_minutes ?? 60) }}"
                       min="15" 
                       max="180" 
                       step="15"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 dark:bg-gray-700 dark:text-white @error('duree_minutes') border-red-500 @enderror"
                       required>
                @error('duree_minutes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Instructeur -->
            <div>
                <label for="instructeur" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    👨‍🏫 Instructeur principal
                </label>
                <input type="text" 
                       id="instructeur" 
                       name="instructeur" 
                       value="{{ old('instructeur', $cours->instructeur ?? '') }}"
                       placeholder="Nom de l'instructeur principal"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 dark:bg-gray-700 dark:text-white @error('instructeur') border-red-500 @enderror">
                @error('instructeur')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Description -->
        <div class="mt-6">
            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                📝 Description du cours
            </label>
            <textarea id="description" 
                      name="description" 
                      rows="4"
                      placeholder="Décrivez le contenu, les objectifs et le public cible de ce cours..."
                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 dark:bg-gray-700 dark:text-white @error('description') border-red-500 @enderror">{{ old('description', $cours->description ?? '') }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Paramètres par défaut -->
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            ⚙️ Paramètres par défaut
        </h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
            Ces valeurs seront utilisées par défaut lors de la création d'horaires pour ce cours.
        </p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Capacité maximale -->
            <div>
                <label for="capacite_max" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    👥 Capacité maximale
                </label>
                <input type="number" 
                       id="capacite_max" 
                       name="capacite_max" 
                       value="{{ old('capacite_max', $cours->capacite_max ?? 20) }}"
                       min="1" 
                       max="50"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 dark:bg-gray-700 dark:text-white @error('capacite_max') border-red-500 @enderror">
                <p class="mt-1 text-sm text-gray-500">Laisser vide si la priorité varie selon l'horaire</p>
                @error('capacite_max')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Prix par cours -->
            <div>
                <label for="prix" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    💰 Prix par cours ($)
                </label>
                <input type="number" 
                       id="prix" 
                       name="prix" 
                       value="{{ old('prix', $cours->prix ?? '') }}"
                       min="0" 
                       step="0.01"
                       placeholder="0.00"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 dark:bg-gray-700 dark:text-white @error('prix') border-red-500 @enderror">
                <p class="mt-1 text-sm text-gray-500">Peut être modifié pour chaque horaire</p>
                @error('prix')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Statut -->
        <div class="mt-6">
            <label class="flex items-center">
                <input type="checkbox" 
                       name="active" 
                       value="1" 
                       {{ old('active', $cours->active ?? true) ? 'checked' : '' }}
                       class="mr-3 h-4 w-4 text-violet-600 focus:ring-violet-500 border-gray-300 rounded">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    ✅ Cours actif (disponible pour la création d'horaires)
                </span>
            </label>
            @error('active')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Boutons -->
    <div class="flex justify-between pt-6">
        <a href="{{ route('admin.cours.index') }}" 
           class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
            ← Retour à la liste
        </a>
        <button type="submit" 
                class="px-6 py-2 bg-violet-600 text-white rounded-md hover:bg-violet-700 transition-colors">
            💾 {{ isset($cours) ? 'Mettre à jour' : 'Créer' }} le cours
        </button>
    </div>
</div>
