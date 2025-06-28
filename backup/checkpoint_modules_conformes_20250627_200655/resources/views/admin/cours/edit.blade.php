@extends('layouts.admin')
@section('title', 'Modifier le Cours')

@section('content')
<div class="space-y-6">
    <!-- Header avec couleur violette -->
    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Modifier: {{ $cours->nom }}
                </h1>
                <p class="text-purple-100 text-lg">Modification des informations du cours</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.cours.show', $cours) }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Voir Profil
                </a>
                <a href="{{ route('admin.cours.index') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour Liste
                </a>
            </div>
        </div>
    </div>

    <!-- Messages d'erreur/succès -->
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
    <form method="POST" action="{{ route('admin.cours.update', $cours) }}">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Informations générales -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
                <div class="bg-purple-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Informations Générales
                    </h3>
                </div>
                <div class="p-6 space-y-6">
                    @if(auth()->user()->hasRole('superadmin'))
                    <div>
                        <label for="ecole_id" class="block text-sm font-medium text-slate-300 mb-2">
                            École <span class="text-red-400">*</span>
                        </label>
                        <select name="ecole_id" id="ecole_id" required
                                class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Sélectionner une école</option>
                            @foreach($ecoles as $ecole)
                            <option value="{{ $ecole->id }}" {{ (old('ecole_id', $cours->ecole_id) == $ecole->id) ? 'selected' : '' }}>
                                {{ $ecole->nom }}
                            </option>
                            @endforeach
                        </select>
                        @error('ecole_id')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                    </div>
                    @else
                    <input type="hidden" name="ecole_id" value="{{ $cours->ecole_id }}">
                    <div class="bg-slate-700 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-slate-300 mb-1">École</label>
                        <p class="text-white font-medium">{{ $cours->ecole->nom ?? 'École non trouvée' }}</p>
                    </div>
                    @endif

                    <div>
                        <label for="nom" class="block text-sm font-medium text-slate-300 mb-2">
                            Nom du cours <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="nom" id="nom" required
                               class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500" 
                               value="{{ old('nom', $cours->nom) }}"
                               placeholder="Ex: Karaté parents-enfants 18h-19h">
                        @error('nom')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-300 mb-2">Description</label>
                        <textarea name="description" id="description" rows="3"
                                  class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                  placeholder="Détails du cours, public cible, spécificités...">{{ old('description', $cours->description) }}</textarea>
                        @error('description')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="niveau" class="block text-sm font-medium text-slate-300 mb-2">
                            Niveau <span class="text-red-400">*</span>
                        </label>
                        <select name="niveau" id="niveau" required
                                class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Sélectionner un niveau</option>
                            <option value="debutant" {{ old('niveau', $cours->niveau) == 'debutant' ? 'selected' : '' }}>Débutant</option>
                            <option value="intermediaire" {{ old('niveau', $cours->niveau) == 'intermediaire' ? 'selected' : '' }}>Intermédiaire</option>
                            <option value="avance" {{ old('niveau', $cours->niveau) == 'avance' ? 'selected' : '' }}>Avancé</option>
                            <option value="tous_niveaux" {{ old('niveau', $cours->niveau) == 'tous_niveaux' ? 'selected' : '' }}>Tous niveaux</option>
                        </select>
                        @error('niveau')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Détails pratiques -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
                <div class="bg-indigo-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3z"/>
                        </svg>
                        Détails Pratiques
                    </h3>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="capacite_max" class="block text-sm font-medium text-slate-300 mb-2">
                                Capacité max <span class="text-red-400">*</span>
                            </label>
                            <input type="number" name="capacite_max" id="capacite_max" min="1" max="100" required
                                   class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500" 
                                   value="{{ old('capacite_max', $cours->capacite_max) }}">
                            @error('capacite_max')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="duree_minutes" class="block text-sm font-medium text-slate-300 mb-2">
                                Durée (min) <span class="text-red-400">*</span>
                            </label>
                            <input type="number" name="duree_minutes" id="duree_minutes" min="30" max="180" required
                                   class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500" 
                                   value="{{ old('duree_minutes', $cours->duree_minutes) }}">
                            @error('duree_minutes')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label for="prix" class="block text-sm font-medium text-slate-300 mb-2">Prix session ($)</label>
                        <input type="number" name="prix" id="prix" min="0" step="0.01"
                               class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500" 
                               value="{{ old('prix', $cours->prix) }}"
                               placeholder="Prix pour la session complète">
                        @error('prix')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="instructeur" class="block text-sm font-medium text-slate-300 mb-2">Instructeur</label>
                        <input type="text" name="instructeur" id="instructeur"
                               class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500" 
                               value="{{ old('instructeur', $cours->instructeur) }}"
                               placeholder="Nom de l'instructeur">
                        @error('instructeur')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="active" value="1" 
                                   class="rounded border-slate-600 text-purple-600 focus:ring-purple-500 bg-slate-700"
                                   {{ old('active', $cours->active) ? 'checked' : '' }}>
                            <span class="ml-2 text-slate-300">Cours actif</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="flex items-center justify-between bg-slate-800 rounded-xl border border-slate-700 px-6 py-4">
            <a href="{{ route('admin.cours.show', $cours) }}" 
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
                Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection
