@extends('layouts.admin')

@section('title', 'Modifier le Cours')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-white">📚 Modifier: {{ $cours->nom }}</h1>
            <p class="text-slate-400 mt-1">Modification des informations du cours</p>
        </div>
        <a href="{{ route('admin.cours.show', $cours) }}" 
           class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
            ← Retour
        </a>
    </div>

    <div class="bg-slate-800 border border-slate-700 rounded-lg p-6">
        <form method="POST" action="{{ route('admin.cours.update', $cours) }}">
            @csrf
            @method('PUT')

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
                            <option value="{{ $ecole->id }}" {{ (old('ecole_id', $cours->ecole_id) == $ecole->id) ? 'selected' : '' }}>
                                {{ $ecole->nom }}
                            </option>
                            @endforeach
                        </select>
                        @error('ecole_id')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    @else
                    <input type="hidden" name="ecole_id" value="{{ $cours->ecole_id }}">
                    @endif

                    <div>
                        <label for="nom" class="block text-sm font-medium text-white mb-2">Nom du cours *</label>
                        <input type="text" name="nom" id="nom" required
                               class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('nom') border-red-500 @enderror" 
                               value="{{ old('nom', $cours->nom) }}">
                        @error('nom')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-white mb-2">Description</label>
                        <textarea name="description" id="description" rows="3"
                                  class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('description') border-red-500 @enderror">{{ old('description', $cours->description) }}</textarea>
                        @error('description')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="type_cours" class="block text-sm font-medium text-white mb-2">Type *</label>
                            <select name="type_cours" id="type_cours" required
                                    class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('type_cours') border-red-500 @enderror">
                                <option value="">Sélectionner</option>
                                <option value="regulier" {{ old('type_cours', $cours->type_cours) == 'regulier' ? 'selected' : '' }}>Régulier</option>
                                <option value="specialise" {{ old('type_cours', $cours->type_cours) == 'specialise' ? 'selected' : '' }}>Spécialisé</option>
                                <option value="competition" {{ old('type_cours', $cours->type_cours) == 'competition' ? 'selected' : '' }}>Compétition</option>
                                <option value="examen" {{ old('type_cours', $cours->type_cours) == 'examen' ? 'selected' : '' }}>Examen</option>
                            </select>
                            @error('type_cours')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-white mb-2">Statut *</label>
                            <select name="status" id="status" required
                                    class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('status') border-red-500 @enderror">
                                <option value="actif" {{ old('status', $cours->status) == 'actif' ? 'selected' : '' }}>Actif</option>
                                <option value="inactif" {{ old('status', $cours->status) == 'inactif' ? 'selected' : '' }}>Inactif</option>
                                <option value="complet" {{ old('status', $cours->status) == 'complet' ? 'selected' : '' }}>Complet</option>
                                <option value="annule" {{ old('status', $cours->status) == 'annule' ? 'selected' : '' }}>Annulé</option>
                            </select>
                            @error('status')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label for="niveau_requis" class="block text-sm font-medium text-white mb-2">Niveau requis</label>
                        <input type="text" name="niveau_requis" id="niveau_requis"
                               class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('niveau_requis') border-red-500 @enderror" 
                               value="{{ old('niveau_requis', $cours->niveau_requis) }}" 
                               placeholder="Ex: Débutant, Ceinture jaune...">
                        @error('niveau_requis')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Colonne droite -->
                <div class="space-y-6">
                    <h3 class="text-xl font-semibold text-white border-b border-slate-700 pb-3">
                        Détails du cours
                    </h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="age_min" class="block text-sm font-medium text-white mb-2">Âge min *</label>
                            <input type="number" name="age_min" id="age_min" min="3" max="99" required
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('age_min') border-red-500 @enderror" 
                                   value="{{ old('age_min', $cours->age_min) }}">
                            @error('age_min')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="age_max" class="block text-sm font-medium text-white mb-2">Âge max *</label>
                            <input type="number" name="age_max" id="age_max" min="3" max="99" required
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('age_max') border-red-500 @enderror" 
                                   value="{{ old('age_max', $cours->age_max) }}">
                            @error('age_max')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="capacite_max" class="block text-sm font-medium text-white mb-2">Capacité *</label>
                            <input type="number" name="capacite_max" id="capacite_max" min="1" max="50" required
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('capacite_max') border-red-500 @enderror" 
                                   value="{{ old('capacite_max', $cours->capacite_max) }}">
                            @error('capacite_max')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="duree_minutes" class="block text-sm font-medium text-white mb-2">Durée (min) *</label>
                            <input type="number" name="duree_minutes" id="duree_minutes" min="30" max="180" required
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('duree_minutes') border-red-500 @enderror" 
                                   value="{{ old('duree_minutes', $cours->duree_minutes) }}">
                            @error('duree_minutes')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label for="prix_mensuel" class="block text-sm font-medium text-white mb-2">Prix mensuel ($)</label>
                        <input type="number" name="prix_mensuel" id="prix_mensuel" min="0" step="0.01"
                               class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('prix_mensuel') border-red-500 @enderror" 
                               value="{{ old('prix_mensuel', $cours->prix_mensuel) }}">
                        @error('prix_mensuel')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="instructeur_principal_id" class="block text-sm font-medium text-white mb-2">Instructeur principal</label>
                        <select name="instructeur_principal_id" id="instructeur_principal_id"
                                class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('instructeur_principal_id') border-red-500 @enderror">
                            <option value="">Sélectionner</option>
                            @foreach($instructeurs as $instructeur)
                            <option value="{{ $instructeur->id }}" {{ old('instructeur_principal_id', $cours->instructeur_principal_id) == $instructeur->id ? 'selected' : '' }}>
                                {{ $instructeur->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('instructeur_principal_id')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="salle" class="block text-sm font-medium text-white mb-2">Salle</label>
                        <input type="text" name="salle" id="salle"
                               class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('salle') border-red-500 @enderror" 
                               value="{{ old('salle', $cours->salle) }}">
                        @error('salle')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-slate-700">
                <a href="{{ route('admin.cours.show', $cours) }}" 
                   class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                    Annuler
                </a>
                <button type="submit" 
                        class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors flex items-center space-x-2">
                    <span>💾</span>
                    <span>Modifier le cours</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
