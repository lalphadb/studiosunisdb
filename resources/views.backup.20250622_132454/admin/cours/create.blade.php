@extends('layouts.admin')

@section('title', 'Nouveau Cours')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-white">📚 Nouveau Cours</h1>
            <p class="text-slate-400 mt-1">Créer un nouveau cours de karaté</p>
        </div>
        <a href="{{ route('admin.cours.index') }}" 
           class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
            ← Retour
        </a>
    </div>

    <div class="bg-slate-800 border border-slate-700 rounded-lg p-6">
        <form method="POST" action="{{ route('admin.cours.store') }}">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Colonne gauche -->
                <div class="space-y-6">
                    <h3 class="text-xl font-semibold text-white border-b border-slate-700 pb-3">
                        Informations générales
                    </h3>
                    
                    @if(auth()->user()->hasRole('superadmin'))
                    <div>
                        <label for="ecole_id" class="block text-sm font-medium text-white mb-2">École *</label>
                        <select name="ecole_id" id="ecole_id" required
                                class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('ecole_id') border-red-500 @enderror">
                            <option value="">Sélectionner une école</option>
                            @foreach($ecoles as $ecole)
                            <option value="{{ $ecole->id }}" {{ old('ecole_id') == $ecole->id ? 'selected' : '' }}>
                                {{ $ecole->nom }}
                            </option>
                            @endforeach
                        </select>
                        @error('ecole_id')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    @else
                    <input type="hidden" name="ecole_id" value="{{ auth()->user()->ecole_id }}">
                    @endif

                    <div>
                        <label for="nom" class="block text-sm font-medium text-white mb-2">Nom du cours *</label>
                        <input type="text" name="nom" id="nom" required
                               class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('nom') border-red-500 @enderror" 
                               value="{{ old('nom') }}"
                               placeholder="Ex: Karaté parents-enfants 18h-19h">
                        @error('nom')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-white mb-2">Description</label>
                        <textarea name="description" id="description" rows="3"
                                  class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('description') border-red-500 @enderror"
                                  placeholder="Détails du cours, public cible, spécificités...">{{ old('description') }}</textarea>
                        @error('description')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="niveau" class="block text-sm font-medium text-white mb-2">Niveau *</label>
                        <select name="niveau" id="niveau" required
                                class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('niveau') border-red-500 @enderror">
                            <option value="">Sélectionner un niveau</option>
                            <option value="debutant" {{ old('niveau') == 'debutant' ? 'selected' : '' }}>Débutant</option>
                            <option value="intermediaire" {{ old('niveau') == 'intermediaire' ? 'selected' : '' }}>Intermédiaire</option>
                            <option value="avance" {{ old('niveau') == 'avance' ? 'selected' : '' }}>Avancé</option>
                            <option value="tous_niveaux" {{ old('niveau') == 'tous_niveaux' ? 'selected' : '' }}>Tous niveaux</option>
                        </select>
                        @error('niveau')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Colonne droite -->
                <div class="space-y-6">
                    <h3 class="text-xl font-semibold text-white border-b border-slate-700 pb-3">
                        Détails pratiques
                    </h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="capacite_max" class="block text-sm font-medium text-white mb-2">Capacité max *</label>
                            <input type="number" name="capacite_max" id="capacite_max" min="1" max="100" required
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('capacite_max') border-red-500 @enderror" 
                                   value="{{ old('capacite_max', 20) }}">
                            @error('capacite_max')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="duree_minutes" class="block text-sm font-medium text-white mb-2">Durée (min) *</label>
                            <input type="number" name="duree_minutes" id="duree_minutes" min="30" max="180" required
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('duree_minutes') border-red-500 @enderror" 
                                   value="{{ old('duree_minutes', 60) }}">
                            @error('duree_minutes')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label for="prix" class="block text-sm font-medium text-white mb-2">Prix session ($)</label>
                        <input type="number" name="prix" id="prix" min="0" step="0.01"
                               class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('prix') border-red-500 @enderror" 
                               value="{{ old('prix') }}"
                               placeholder="Prix pour la session complète">
                        @error('prix')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="instructeur" class="block text-sm font-medium text-white mb-2">Instructeur</label>
                        <input type="text" name="instructeur" id="instructeur"
                               class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('instructeur') border-red-500 @enderror" 
                               value="{{ old('instructeur') }}"
                               placeholder="Nom de l'instructeur">
                        @error('instructeur')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="flex items-center space-x-3">
                            <input type="hidden" name="active" value="0">
                            <input type="checkbox" name="active" value="1" 
                                   class="w-4 h-4 text-blue-600 bg-slate-700 border-slate-600 rounded focus:ring-blue-500"
                                   {{ old('active', true) ? 'checked' : '' }}>
                            <span class="text-white font-medium">Cours actif</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-slate-700">
                <a href="{{ route('admin.cours.index') }}" 
                   class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                    Annuler
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                    💾 Créer le cours
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
