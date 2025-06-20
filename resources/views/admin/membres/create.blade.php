@extends('layouts.admin')

@section('title', 'Nouveau Membre')

@section('content')
<div class="space-y-6">
    {{-- Header moderne avec gradient --}}
    <div class="relative bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 rounded-lg p-6 text-white overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white opacity-5 rounded-full -ml-16 -mb-16"></div>
        
        <div class="relative flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold mb-2">👤 Nouveau Membre</h1>
                <p class="text-blue-100 text-lg">Création d'un nouveau membre du réseau Studios Unis</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.membres.index') }}" 
                   class="bg-white bg-opacity-20 hover:bg-opacity-30 px-6 py-3 rounded-lg font-medium transition-all duration-300 backdrop-blur-sm border border-white border-opacity-30">
                    ← Retour Liste
                </a>
            </div>
        </div>
    </div>

    {{-- Formulaire avec style Studios Unis --}}
    <div class="bg-gray-800 rounded-lg shadow-2xl border border-gray-700 overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-emerald-500 p-4">
            <h3 class="text-xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z"></path>
                </svg>
                Informations du Nouveau Membre
            </h3>
        </div>
        
        <form method="POST" action="{{ route('admin.membres.store') }}">
            @csrf
            
            <div class="p-6 space-y-6">
                
                {{-- École d'inscription --}}
                <div>
                    <label for="ecole_id" class="block text-sm font-bold text-blue-400 mb-2">
                        🏫 École d'inscription *
                    </label>
                    <select name="ecole_id" id="ecole_id" required
                            class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('ecole_id') border-red-500 @enderror">
                        <option value="">Sélectionner une école</option>
                        @foreach($ecoles as $ecole)
                            <option value="{{ $ecole->id }}" {{ old('ecole_id') == $ecole->id ? 'selected' : '' }}>
                                {{ $ecole->nom }} - {{ $ecole->ville }}
                            </option>
                        @endforeach
                    </select>
                    @error('ecole_id')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nom et Prénom --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="prenom" class="block text-sm font-bold text-green-400 mb-2">
                            👤 Prénom *
                        </label>
                        <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}" required
                               class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('prenom') border-red-500 @enderror"
                               placeholder="Prénom du membre">
                        @error('prenom')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="nom" class="block text-sm font-bold text-green-400 mb-2">
                            👤 Nom de famille *
                        </label>
                        <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
                               class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('nom') border-red-500 @enderror"
                               placeholder="Nom de famille">
                        @error('nom')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Contact --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-sm font-bold text-cyan-400 mb-2">
                            📧 Email
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                               class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent @error('email') border-red-500 @enderror"
                               placeholder="email@exemple.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="telephone" class="block text-sm font-bold text-yellow-400 mb-2">
                            📞 Téléphone
                        </label>
                        <input type="text" name="telephone" id="telephone" value="{{ old('telephone') }}"
                               class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('telephone') border-red-500 @enderror"
                               placeholder="514-123-4567">
                        @error('telephone')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Date de naissance et Sexe --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date_naissance" class="block text-sm font-bold text-purple-400 mb-2">
                            🎂 Date de naissance
                        </label>
                        <input type="date" name="date_naissance" id="date_naissance" value="{{ old('date_naissance') }}"
                               class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('date_naissance') border-red-500 @enderror">
                        @error('date_naissance')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="sexe" class="block text-sm font-bold text-pink-400 mb-2">
                            ⚧️ Sexe
                        </label>
                        <select name="sexe" id="sexe"
                                class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('sexe') border-red-500 @enderror">
                            <option value="">Sélectionner</option>
                            <option value="M" {{ old('sexe') == 'M' ? 'selected' : '' }}>👨 Masculin</option>
                            <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>👩 Féminin</option>
                            <option value="Autre" {{ old('sexe') == 'Autre' ? 'selected' : '' }}>🏳️‍⚧️ Autre</option>
                        </select>
                        @error('sexe')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Date d'inscription et Statut --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date_inscription" class="block text-sm font-bold text-orange-400 mb-2">
                            📅 Date d'inscription *
                        </label>
                        <input type="date" name="date_inscription" id="date_inscription" 
                               value="{{ old('date_inscription', date('Y-m-d')) }}" required
                               class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('date_inscription') border-red-500 @enderror">
                        @error('date_inscription')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="active" class="block text-sm font-bold text-emerald-400 mb-2">
                            ✅ Statut du membre *
                        </label>
                        <select name="active" id="active" required
                                class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('active') border-red-500 @enderror">
                            <option value="1" {{ old('active', '1') == '1' ? 'selected' : '' }}>✅ Actif</option>
                            <option value="0" {{ old('active') == '0' ? 'selected' : '' }}>❌ Inactif</option>
                        </select>
                        @error('active')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Actions avec style Studios Unis --}}
            <div class="px-6 py-4 bg-gray-750 border-t border-gray-700 flex justify-end space-x-3 rounded-b-lg">
                <a href="{{ route('admin.membres.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition-all">
                    Annuler
                </a>
                <button type="submit" 
                        class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-3 rounded-lg font-medium transition-all">
                    ➕ Créer le membre
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
