@extends('layouts.admin')
@section('title', 'Créer un Séminaire')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    Créer un Séminaire
                </h1>
                <p class="text-purple-100 text-lg">Organiser un nouveau séminaire inter-écoles</p>
            </div>
            <a href="{{ route('admin.seminaires.index') }}" 
               class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour
            </a>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="bg-purple-600 px-6 py-4">
            <h3 class="text-lg font-semibold text-white">Informations du Séminaire</h3>
        </div>

        <form action="{{ route('admin.seminaires.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Titre et Type -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Titre du Séminaire *</label>
                    <input type="text" name="titre" value="{{ old('titre') }}" required
                           class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                           placeholder="Ex: Formation Kata Avancé">
                    @error('titre')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Type de Séminaire *</label>
                    <select name="type" required
                            class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Choisir un type</option>
                        <option value="technique" {{ old('type') == 'technique' ? 'selected' : '' }}>Technique</option>
                        <option value="kata" {{ old('type') == 'kata' ? 'selected' : '' }}>Kata</option>
                        <option value="competition" {{ old('type') == 'competition' ? 'selected' : '' }}>Compétition</option>
                        <option value="arbitrage" {{ old('type') == 'arbitrage' ? 'selected' : '' }}>Arbitrage</option>
                        <option value="formation" {{ old('type') == 'formation' ? 'selected' : '' }}>Formation</option>
                    </select>
                    @error('type')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Description</label>
                <textarea name="description" rows="3"
                          class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500"
                          placeholder="Description détaillée du séminaire...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Dates -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Date de Début *</label>
                    <input type="date" name="date_debut" value="{{ old('date_debut') }}" required
                           class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                    @error('date_debut')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Date de Fin *</label>
                    <input type="date" name="date_fin" value="{{ old('date_fin') }}" required
                           class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                    @error('date_fin')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Heures -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Heure de Début *</label>
                    <input type="time" name="heure_debut" value="{{ old('heure_debut') }}" required
                           class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                    @error('heure_debut')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Heure de Fin *</label>
                    <input type="time" name="heure_fin" value="{{ old('heure_fin') }}" required
                           class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                    @error('heure_fin')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Lieu -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Lieu *</label>
                    <input type="text" name="lieu" value="{{ old('lieu') }}" required
                           class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500"
                           placeholder="Ex: Dojo Central">
                    @error('lieu')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Adresse</label>
                    <input type="text" name="adresse_lieu" value="{{ old('adresse_lieu') }}"
                           class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500"
                           placeholder="1234 rue Principale">
                    @error('adresse_lieu')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Ville</label>
                    <input type="text" name="ville_lieu" value="{{ old('ville_lieu') }}"
                           class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500"
                           placeholder="Québec">
                    @error('ville_lieu')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Instructeur et Niveau -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Instructeur Principal *</label>
                    <input type="text" name="instructeur" value="{{ old('instructeur') }}" required
                           class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500"
                           placeholder="Nom de l'instructeur">
                    @error('instructeur')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Niveau Requis *</label>
                    <select name="niveau_requis" required
                            class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Choisir un niveau</option>
                        <option value="debutant" {{ old('niveau_requis') == 'debutant' ? 'selected' : '' }}>Débutant</option>
                        <option value="intermediaire" {{ old('niveau_requis') == 'intermediaire' ? 'selected' : '' }}>Intermédiaire</option>
                        <option value="avance" {{ old('niveau_requis') == 'avance' ? 'selected' : '' }}>Avancé</option>
                        <option value="tous_niveaux" {{ old('niveau_requis') == 'tous_niveaux' ? 'selected' : '' }}>Tous Niveaux</option>
                    </select>
                    @error('niveau_requis')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Prix et Options -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Prix (CAD $)</label>
                    <input type="number" name="prix" value="{{ old('prix') }}" step="0.01" min="0"
                           class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500"
                           placeholder="0.00">
                    @error('prix')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" name="inscription_ouverte" value="1" 
                               {{ old('inscription_ouverte') ? 'checked' : '' }}
                               class="w-5 h-5 text-purple-600 bg-slate-700 border-slate-600 rounded focus:ring-purple-500">
                        <span class="text-slate-300">Inscriptions ouvertes</span>
                    </label>
                </div>
            </div>

            <!-- Matériel requis -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Matériel Requis</label>
                <textarea name="materiel_requis" rows="2"
                          class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500"
                          placeholder="Ex: Kimono, protections, armes...">{{ old('materiel_requis') }}</textarea>
                @error('materiel_requis')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Boutons -->
            <div class="flex items-center justify-end space-x-4 pt-6">
                <a href="{{ route('admin.seminaires.index') }}" 
                   class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                    Annuler
                </a>
                <button type="submit" 
                        class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Créer le Séminaire
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
