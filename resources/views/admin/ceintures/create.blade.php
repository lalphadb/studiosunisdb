@extends('layouts.admin')
@section('title', 'Créer une Ceinture')

@section('content')
<div class="space-y-6">
    <!-- Header avec couleur orange -->
    <div class="bg-gradient-to-r from-orange-500 to-red-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Créer une Ceinture
                </h1>
                <p class="text-orange-100 text-lg">Ajouter un nouveau grade au système</p>
            </div>
            <a href="{{ route('admin.ceintures.index') }}" 
               class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour
            </a>
        </div>
    </div>

    <!-- Formulaire -->
    <form method="POST" action="{{ route('admin.ceintures.store') }}">
        @csrf
        
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Nom -->
                <div>
                    <label for="nom" class="block text-sm font-medium text-slate-300 mb-2">
                        Nom de la Ceinture <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           id="nom" 
                           name="nom" 
                           value="{{ old('nom') }}"
                           required
                           placeholder="Ex: Ceinture Blanche"
                           class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('nom') border-red-500 @enderror">
                    @error('nom')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Couleur -->
                <div>
                    <label for="couleur" class="block text-sm font-medium text-slate-300 mb-2">
                        Couleur <span class="text-red-400">*</span>
                    </label>
                    <select id="couleur" 
                            name="couleur" 
                            required
                            class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('couleur') border-red-500 @enderror">
                        <option value="">Sélectionner une couleur</option>
                        <option value="#FFFFFF" {{ old('couleur') == '#FFFFFF' ? 'selected' : '' }}>Blanc</option>
                        <option value="#FFFF00" {{ old('couleur') == '#FFFF00' ? 'selected' : '' }}>Jaune</option>
                        <option value="#FFA500" {{ old('couleur') == '#FFA500' ? 'selected' : '' }}>Orange</option>
                        <option value="#008000" {{ old('couleur') == '#008000' ? 'selected' : '' }}>Vert</option>
                        <option value="#0000FF" {{ old('couleur') == '#0000FF' ? 'selected' : '' }}>Bleu</option>
                        <option value="#8B4513" {{ old('couleur') == '#8B4513' ? 'selected' : '' }}>Marron</option>
                        <option value="#000000" {{ old('couleur') == '#000000' ? 'selected' : '' }}>Noir</option>
                        <option value="#FF0000" {{ old('couleur') == '#FF0000' ? 'selected' : '' }}>Rouge</option>
                    </select>
                    @error('couleur')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ordre -->
                <div>
                    <label for="ordre" class="block text-sm font-medium text-slate-300 mb-2">
                        Ordre <span class="text-red-400">*</span>
                    </label>
                    <input type="number" 
                           id="ordre" 
                           name="ordre" 
                           value="{{ old('ordre') }}"
                           required
                           min="1"
                           max="100"
                           placeholder="Ex: 1, 2, 3..."
                           class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('ordre') border-red-500 @enderror">
                    @error('ordre')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-slate-400">1-10 pour les Kyu, 11+ pour les Dan</p>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-slate-300 mb-2">
                    Description
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="4"
                          placeholder="Description optionnelle de la ceinture, prérequis, techniques..."
                          class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-between mt-8 pt-6 border-t border-slate-700">
                <a href="{{ route('admin.ceintures.index') }}" 
                   class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                    Annuler
                </a>
                
                <button type="submit" 
                        class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                    Créer la Ceinture
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
