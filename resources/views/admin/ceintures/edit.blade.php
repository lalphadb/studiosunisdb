@extends('layouts.admin')
@section('title', 'Modifier Ceinture')

@section('content')
<div class="space-y-6">
    <!-- Header avec couleur orange -->
    <div class="bg-gradient-to-r from-orange-500 to-red-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Modifier {{ $ceinture->nom }}
                </h1>
                <p class="text-orange-100 text-lg">Ordre {{ $ceinture->ordre }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.ceintures.show', $ceinture) }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg font-medium transition duration-200">
                    Voir
                </a>
                <a href="{{ route('admin.ceintures.index') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg font-medium transition duration-200">
                    Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <form method="POST" action="{{ route('admin.ceintures.update', $ceinture) }}">
        @csrf
        @method('PUT')
        
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
                           value="{{ old('nom', $ceinture->nom) }}"
                           required
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
                        <option value="#FFFFFF" {{ old('couleur', $ceinture->couleur) == '#FFFFFF' ? 'selected' : '' }}>Blanc</option>
                        <option value="#FFFF00" {{ old('couleur', $ceinture->couleur) == '#FFFF00' ? 'selected' : '' }}>Jaune</option>
                        <option value="#FFA500" {{ old('couleur', $ceinture->couleur) == '#FFA500' ? 'selected' : '' }}>Orange</option>
                        <option value="#008000" {{ old('couleur', $ceinture->couleur) == '#008000' ? 'selected' : '' }}>Vert</option>
                        <option value="#0000FF" {{ old('couleur', $ceinture->couleur) == '#0000FF' ? 'selected' : '' }}>Bleu</option>
                        <option value="#8B4513" {{ old('couleur', $ceinture->couleur) == '#8B4513' ? 'selected' : '' }}>Marron</option>
                        <option value="#000000" {{ old('couleur', $ceinture->couleur) == '#000000' ? 'selected' : '' }}>Noir</option>
                        <option value="#FF0000" {{ old('couleur', $ceinture->couleur) == '#FF0000' ? 'selected' : '' }}>Rouge</option>
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
                           value="{{ old('ordre', $ceinture->ordre) }}"
                           required
                           min="1"
                           max="100"
                           class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('ordre') border-red-500 @enderror">
                    @error('ordre')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
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
                          class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('description') border-red-500 @enderror">{{ old('description', $ceinture->description) }}</textarea>
                @error('description')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-between mt-8 pt-6 border-t border-slate-700">
                <a href="{{ route('admin.ceintures.show', $ceinture) }}" 
                   class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                    Annuler
                </a>
                
                <button type="submit" 
                        class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                    Mettre à jour
                </button>
            </div>
        </div>
    </form>

    <!-- Statistiques actuelles -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <h3 class="text-lg font-medium text-white mb-4">📊 Statistiques</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <span class="text-slate-400">Créée le:</span>
                <span class="text-white ml-2">{{ $ceinture->created_at->format('d/m/Y') }}</span>
            </div>
            <div>
                <span class="text-slate-400">Modifiée le:</span>
                <span class="text-white ml-2">{{ $ceinture->updated_at->format('d/m/Y') }}</span>
            </div>
            <div>
                <span class="text-slate-400">Utilisateurs:</span>
                <span class="text-white ml-2">{{ $ceinture->userCeintures()->where('valide', true)->count() }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
