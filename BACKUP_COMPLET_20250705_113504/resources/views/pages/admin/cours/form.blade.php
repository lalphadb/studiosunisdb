<div class="space-y-6">
    <!-- Informations de base -->
    <div class="studiosdb-card">
        <h3 class="text-lg font-semibold text-white mb-6 flex items-center">
            <span class="text-2xl mr-3">📚</span>
            Informations de base
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nom du cours -->
            <div>
                <label for="nom" class="block text-sm font-medium text-white mb-2">
                    <span class="text-red-400">*</span> Nom du cours
                </label>
                <input type="text" 
                       id="nom" 
                       name="nom" 
                       value="{{ old('nom', $cours->nom ?? '') }}"
                       placeholder="Ex: Karaté Adultes, Enfants Débutants..."
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('nom') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                       required>
                @error('nom')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Niveau -->
            <div>
                <label for="niveau" class="block text-sm font-medium text-white mb-2">
                    <span class="text-red-400">*</span> Niveau requis
                </label>
                <select id="niveau" 
                        name="niveau" 
                        class="studiosdb-select @error('niveau') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
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
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Durée -->
            <div>
                <label for="duree_minutes" class="block text-sm font-medium text-white mb-2">
                    <span class="text-red-400">*</span> Durée (minutes)
                </label>
                <input type="number" 
                       id="duree_minutes" 
                       name="duree_minutes" 
                       value="{{ old('duree_minutes', $cours->duree_minutes ?? 60) }}"
                       min="15" 
                       max="180" 
                       step="15"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('duree_minutes') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                       required>
                @error('duree_minutes')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-slate-400">Entre 15 et 180 minutes, par pas de 15</p>
            </div>

            <!-- Instructeur -->
            <div>
                <label for="instructeur" class="block text-sm font-medium text-white mb-2">
                    👨‍🏫 Instructeur principal
                </label>
                <input type="text" 
                       id="instructeur" 
                       name="instructeur" 
                       value="{{ old('instructeur', $cours->instructeur ?? '') }}"
                       placeholder="Nom de l'instructeur principal"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('instructeur') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                @error('instructeur')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Description -->
        <div class="mt-6">
            <label for="description" class="block text-sm font-medium text-white mb-2">
                📝 Description du cours
            </label>
            <textarea id="description" 
                      name="description" 
                      rows="4"
                      placeholder="Décrivez le contenu, les objectifs et le public cible de ce cours..."
                      class="studiosdb-textarea @error('description') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror">{{ old('description', $cours->description ?? '') }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Paramètres par défaut -->
    <div class="studiosdb-card">
        <h3 class="text-lg font-semibold text-white mb-6 flex items-center">
            <span class="text-2xl mr-3">⚙️</span>
            Paramètres par défaut
        </h3>
        <p class="text-sm text-slate-400 mb-6">
            Ces valeurs seront utilisées par défaut lors de la création d'horaires pour ce cours.
        </p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Capacité maximale -->
            <div>
                <label for="capacite_max" class="block text-sm font-medium text-white mb-2">
                    👥 Capacité maximale
                </label>
                <input type="number" 
                       id="capacite_max" 
                       name="capacite_max" 
                       value="{{ old('capacite_max', $cours->capacite_max ?? '') }}"
                       min="1" 
                       max="50"
                       placeholder="20"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('capacite_max') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                <p class="mt-1 text-sm text-slate-400">Laisser vide si cela varie selon l'horaire</p>
                @error('capacite_max')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Prix par cours -->
            <div>
                <label for="prix" class="block text-sm font-medium text-white mb-2">
                    💰 Prix par cours ($)
                </label>
                <input type="number" 
                       id="prix" 
                       name="prix" 
                       value="{{ old('prix', $cours->prix ?? '') }}"
                       min="0" 
                       step="0.01"
                       placeholder="0.00"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('prix') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                <p class="mt-1 text-sm text-slate-400">Peut être modifié pour chaque horaire</p>
                @error('prix')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- École (Super Admin seulement) -->
        @if(auth()->user()->hasRole('super_admin') && isset($ecoles) && $ecoles->count() > 1)
        <div class="mt-6">
            <label for="ecole_id" class="block text-sm font-medium text-white mb-2">
                <span class="text-red-400">*</span> École
            </label>
            <select id="ecole_id" 
                    name="ecole_id" 
                    class="studiosdb-select @error('ecole_id') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                    required>
                <option value="">Sélectionner une école...</option>
                @foreach($ecoles as $ecole)
                <option value="{{ $ecole->id }}" 
                        {{ old('ecole_id', $cours->ecole_id ?? auth()->user()->ecole_id) == $ecole->id ? 'selected' : '' }}>
                    {{ $ecole->nom }} ({{ $ecole->code_ecole }})
                </option>
                @endforeach
            </select>
            @error('ecole_id')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>
        @endif

        <!-- Statut -->
        <div class="mt-6">
            <label class="flex items-center">
                <input type="checkbox" 
                       name="active" 
                       value="1" 
                       {{ old('active', $cours->active ?? true) ? 'checked' : '' }}
                       class="mr-3 h-4 w-4 text-violet-500 focus:ring-violet-500 border-slate-600 rounded bg-slate-700">
                <span class="text-sm font-medium text-white">
                    ✅ Cours actif (disponible pour la création d'horaires)
                </span>
            </label>
            @error('active')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Boutons -->
    <div class="flex justify-between pt-6">
        <a href="{{ route('admin.cours.index') }}" 
           class="studiosdb-btn studiosdb-btn-cancel">
            ← Retour à la liste
        </a>
        <button type="submit" 
                class="studiosdb-btn studiosdb-btn-cours studiosdb-btn-lg">
            💾 {{ isset($cours) && $cours->exists ? 'Mettre à jour' : 'Créer' }} le cours
        </button>
    </div>
</div>
