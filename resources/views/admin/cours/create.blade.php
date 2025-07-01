<x-admin-layout>
    <x-slot name="header">
        <x-module-header 
            title="Nouveau Cours"
            subtitle="Créer un nouveau template de cours"
            color="violet"
            icon="plus"
        />
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Breadcrumb --}}
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.cours.index') }}" 
                           class="text-gray-700 hover:text-violet-600 dark:text-gray-300 dark:hover:text-violet-400">
                            Cours
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-500 dark:text-gray-400">Nouveau</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                <form method="POST" action="{{ route('admin.cours.store') }}" class="p-6 space-y-6">
                    @csrf

                    {{-- Informations de base --}}
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            Informations de base
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Nom --}}
                            <div class="md:col-span-2">
                                <label for="nom" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Nom du cours *
                                </label>
                                <input type="text" 
                                       name="nom" 
                                       id="nom" 
                                       value="{{ old('nom') }}" 
                                       placeholder="Ex: Parents-Enfants, Karaté Adultes..."
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-violet-500 focus:ring-violet-500"
                                       required>
                                @error('nom')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Niveau --}}
                            <div>
                                <label for="niveau" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Niveau requis *
                                </label>
                                <select name="niveau" 
                                        id="niveau" 
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-violet-500 focus:ring-violet-500"
                                        required>
                                    <option value="">Sélectionner un niveau...</option>
                                    <option value="debutant" {{ old('niveau') === 'debutant' ? 'selected' : '' }}>Débutant</option>
                                    <option value="intermediaire" {{ old('niveau') === 'intermediaire' ? 'selected' : '' }}>Intermédiaire</option>
                                    <option value="avance" {{ old('niveau') === 'avance' ? 'selected' : '' }}>Avancé</option>
                                    <option value="tous_niveaux" {{ old('niveau') === 'tous_niveaux' ? 'selected' : '' }}>Tous niveaux</option>
                                </select>
                                @error('niveau')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Durée --}}
                            <div>
                                <label for="duree_minutes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Durée (minutes) *
                                </label>
                                <input type="number" 
                                       name="duree_minutes" 
                                       id="duree_minutes" 
                                       value="{{ old('duree_minutes', 60) }}" 
                                       min="30" 
                                       max="300" 
                                       step="15"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-violet-500 focus:ring-violet-500"
                                       required>
                                @error('duree_minutes')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Description du cours
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="4" 
                                      placeholder="Décrivez le contenu, les objectifs et le public cible de ce cours..."
                                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-violet-500 focus:ring-violet-500">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Paramètres par défaut --}}
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            Paramètres par défaut
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                            Ces valeurs seront utilisées par défaut lors de la création d'horaires pour ce cours.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {{-- Capacité --}}
                            <div>
                                <label for="capacite_max" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Capacité maximale *
                                </label>
                                <input type="number" 
                                       name="capacite_max" 
                                       id="capacite_max" 
                                       value="{{ old('capacite_max', 20) }}" 
                                       min="1" 
                                       max="100"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-violet-500 focus:ring-violet-500"
                                       required>
                                @error('capacite_max')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Prix --}}
                            <div>
                                <label for="prix" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Prix par cours ($)
                                </label>
                                <input type="number" 
                                       name="prix" 
                                       id="prix" 
                                       value="{{ old('prix') }}" 
                                       min="0" 
                                       max="999999.99" 
                                       step="0.01"
                                       placeholder="0.00"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-violet-500 focus:ring-violet-500">
                                @error('prix')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Laissez vide si le prix varie selon l'horaire
                                </p>
                            </div>

                            {{-- Instructeur --}}
                            <div>
                                <label for="instructeur" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Instructeur principal
                                </label>
                                <input type="text" 
                                       name="instructeur" 
                                       id="instructeur" 
                                       value="{{ old('instructeur') }}" 
                                       placeholder="Nom de l'instructeur"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-violet-500 focus:ring-violet-500">
                                @error('instructeur')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Peut être modifié pour chaque horaire
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Options --}}
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="active" 
                                   id="active" 
                                   value="1"
                                   {{ old('active', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 dark:border-gray-600 text-violet-600 shadow-sm focus:border-violet-500 focus:ring-violet-500">
                            <label for="active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                Cours actif (disponible pour la création d'horaires)
                            </label>
                        </div>
                    </div>

                    {{-- Prochaine étape --}}
                    @if($sessions->count() > 0)
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <div class="bg-violet-50 dark:bg-violet-900/20 rounded-lg p-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <x-admin-icon name="lightbulb" class="h-5 w-5 text-violet-600 dark:text-violet-400" />
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-violet-800 dark:text-violet-200">
                                            Prochaine étape
                                        </h4>
                                        <p class="mt-1 text-sm text-violet-700 dark:text-violet-300">
                                            Après la création du cours, vous pourrez définir ses horaires pour les sessions disponibles : 
                                            {{ $sessions->pluck('nom')->join(', ') }}.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Boutons d'action --}}
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.cours.index') }}" 
                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-violet-600 border border-transparent rounded-md shadow-sm hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500">
                            Créer le cours
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
