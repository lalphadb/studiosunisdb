@extends('layouts.admin')

@section('title', 'Créer Cours')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.cours.index') }}" class="text-gray-400 hover:text-white">
                    <i class="fas fa-arrow-left"></i> ←
                </a>
                <h1 class="text-3xl font-bold text-white">
                    ➕ Créer un nouveau cours
                </h1>
            </div>
        </div>

        <div class="bg-gray-800 border border-gray-700 rounded-lg overflow-hidden">
            <form action="{{ route('admin.cours.store') }}" method="POST" class="p-6 space-y-6">
                @csrf
                
                <!-- Informations de base -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-300 mb-2">
                            Nom du cours *
                        </label>
                        <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
                               class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('nom')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type_cours" class="block text-sm font-medium text-gray-300 mb-2">
                            Type de cours *
                        </label>
                        <select name="type_cours" id="type_cours" required
                                class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Sélectionner un type</option>
                            <option value="karate" {{ old('type_cours') == 'karate' ? 'selected' : '' }}>Karaté</option>
                            <option value="boxe" {{ old('type_cours') == 'boxe' ? 'selected' : '' }}>Boxe</option>
                            <option value="kickboxing" {{ old('type_cours') == 'kickboxing' ? 'selected' : '' }}>Kickboxing</option>
                            <option value="cardiobox" {{ old('type_cours') == 'cardiobox' ? 'selected' : '' }}>Cardiobox</option>
                            <option value="enfants" {{ old('type_cours') == 'enfants' ? 'selected' : '' }}>Enfants</option>
                            <option value="adultes" {{ old('type_cours') == 'adultes' ? 'selected' : '' }}>Adultes</option>
                        </select>
                        @error('type_cours')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-300 mb-2">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- École et instructeur -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="ecole_id" class="block text-sm font-medium text-gray-300 mb-2">
                            École *
                        </label>
                        <select name="ecole_id" id="ecole_id" required
                                class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Sélectionner une école</option>
                            @foreach($ecoles as $ecole)
                                <option value="{{ $ecole->id }}" {{ old('ecole_id') == $ecole->id ? 'selected' : '' }}>
                                    {{ $ecole->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('ecole_id')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="instructeur_id" class="block text-sm font-medium text-gray-300 mb-2">
                            Instructeur
                        </label>
                        <select name="instructeur_id" id="instructeur_id"
                                class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Sélectionner un instructeur</option>
                            @foreach($instructeurs as $instructeur)
                                <option value="{{ $instructeur->id }}" {{ old('instructeur_id') == $instructeur->id ? 'selected' : '' }}>
                                    {{ $instructeur->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('instructeur_id')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Paramètres du cours -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="capacite_max" class="block text-sm font-medium text-gray-300 mb-2">
                            Capacité max *
                        </label>
                        <input type="number" name="capacite_max" id="capacite_max" value="{{ old('capacite_max', 20) }}" min="1" max="50" required
                               class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('capacite_max')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="duree_minutes" class="block text-sm font-medium text-gray-300 mb-2">
                            Durée (minutes)
                        </label>
                        <input type="number" name="duree_minutes" id="duree_minutes" value="{{ old('duree_minutes', 60) }}" min="30" max="180"
                               class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('duree_minutes')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="prix_mensuel" class="block text-sm font-medium text-gray-300 mb-2">
                            Prix mensuel ($) *
                        </label>
                        <input type="number" name="prix_mensuel" id="prix_mensuel" value="{{ old('prix_mensuel') }}" step="0.01" min="0" required
                               class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('prix_mensuel')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-700">
                    <a href="{{ route('admin.cours.index') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition duration-200">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                        💾 Créer le cours
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
