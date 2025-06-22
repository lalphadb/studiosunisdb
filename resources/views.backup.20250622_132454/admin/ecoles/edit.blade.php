@extends('layouts.admin')

@section('title', 'Modifier École')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h1 class="text-3xl font-bold text-white">
                    ✏️ Modifier École
                </h1>
                <p class="mt-1 text-sm text-gray-300">
                    Modification des informations de l'école "{{ $ecole->nom }}"
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                <a href="{{ route('admin.ecoles.show', $ecole) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                    👁️ Voir École
                </a>
                <a href="{{ route('admin.ecoles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                    ← Retour Liste
                </a>
            </div>
        </div>

        <!-- Messages -->
        @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
        @endif

        <!-- Formulaire -->
        <div class="bg-gray-800 rounded-lg shadow-xl border border-gray-700">
            <form action="{{ route('admin.ecoles.update', $ecole) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="px-6 py-4 border-b border-gray-700">
                    <h3 class="text-lg font-medium text-white">Informations de l'École</h3>
                    <p class="text-sm text-gray-400">Modifiez les informations de l'école ci-dessous</p>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Nom de l'école -->
                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-300 mb-2">
                            Nom de l'école *
                        </label>
                        <input type="text" 
                               name="nom" 
                               id="nom" 
                               value="{{ old('nom', $ecole->nom) }}"
                               class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nom') border-red-500 @enderror" 
                               required>
                        @error('nom')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Adresse -->
                    <div>
                        <label for="adresse" class="block text-sm font-medium text-gray-300 mb-2">
                            Adresse complète
                        </label>
                        <textarea name="adresse" 
                                  id="adresse" 
                                  rows="3"
                                  class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('adresse') border-red-500 @enderror">{{ old('adresse', $ecole->adresse) }}</textarea>
                        @error('adresse')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ville et Province -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="ville" class="block text-sm font-medium text-gray-300 mb-2">
                                Ville *
                            </label>
                            <input type="text" 
                                   name="ville" 
                                   id="ville" 
                                   value="{{ old('ville', $ecole->ville) }}"
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('ville') border-red-500 @enderror" 
                                   required>
                            @error('ville')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="province" class="block text-sm font-medium text-gray-300 mb-2">
                                Province
                            </label>
                            <select name="province" id="province" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('province') border-red-500 @enderror">
                                <option value="Quebec" {{ old('province', $ecole->province) == 'Quebec' ? 'selected' : '' }}>Québec</option>
                                <option value="Ontario" {{ old('province', $ecole->province) == 'Ontario' ? 'selected' : '' }}>Ontario</option>
                                <option value="Alberta" {{ old('province', $ecole->province) == 'Alberta' ? 'selected' : '' }}>Alberta</option>
                                <option value="British Columbia" {{ old('province', $ecole->province) == 'British Columbia' ? 'selected' : '' }}>Colombie-Britannique</option>
                            </select>
                            @error('province')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Code postal et Téléphone -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="code_postal" class="block text-sm font-medium text-gray-300 mb-2">
                                Code postal
                            </label>
                            <input type="text" 
                                   name="code_postal" 
                                   id="code_postal" 
                                   value="{{ old('code_postal', $ecole->code_postal) }}"
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('code_postal') border-red-500 @enderror"
                                   placeholder="H1A 1A1">
                            @error('code_postal')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="telephone" class="block text-sm font-medium text-gray-300 mb-2">
                                Téléphone
                            </label>
                            <input type="text" 
                                   name="telephone" 
                                   id="telephone" 
                                   value="{{ old('telephone', $ecole->telephone) }}"
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('telephone') border-red-500 @enderror"
                                   placeholder="514-123-4567">
                            @error('telephone')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email et Site web -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                                Email
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   value="{{ old('email', $ecole->email) }}"
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="site_web" class="block text-sm font-medium text-gray-300 mb-2">
                                Site web
                            </label>
                            <input type="url" 
                                   name="site_web" 
                                   id="site_web" 
                                   value="{{ old('site_web', $ecole->site_web) }}"
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('site_web') border-red-500 @enderror"
                                   placeholder="https://exemple.com">
                            @error('site_web')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Directeur et Capacité -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="directeur" class="block text-sm font-medium text-gray-300 mb-2">
                                Directeur
                            </label>
                            <input type="text" 
                                   name="directeur" 
                                   id="directeur" 
                                   value="{{ old('directeur', $ecole->directeur) }}"
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('directeur') border-red-500 @enderror">
                            @error('directeur')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="capacite_max" class="block text-sm font-medium text-gray-300 mb-2">
                                Capacité maximale
                            </label>
                            <input type="number" 
                                   name="capacite_max" 
                                   id="capacite_max" 
                                   value="{{ old('capacite_max', $ecole->capacite_max) }}"
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('capacite_max') border-red-500 @enderror"
                                   min="10" 
                                   max="500">
                            @error('capacite_max')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Statut -->
                    <div>
                        <label for="statut" class="block text-sm font-medium text-gray-300 mb-2">
                            Statut
                        </label>
                        <select name="statut" id="statut" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('statut') border-red-500 @enderror">
                            <option value="actif" {{ old('statut', $ecole->statut) == 'actif' ? 'selected' : '' }}>✅ Actif</option>
                            <option value="inactif" {{ old('statut', $ecole->statut) == 'inactif' ? 'selected' : '' }}>❌ Inactif</option>
                        </select>
                        @error('statut')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-300 mb-2">
                            Description
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4"
                                  class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                                  placeholder="Description de l'école, services offerts, spécialités...">{{ old('description', $ecole->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="px-6 py-4 bg-gray-750 border-t border-gray-700 flex justify-end space-x-3 rounded-b-lg">
                    <a href="{{ route('admin.ecoles.show', $ecole) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                        Annuler
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                        💾 Enregistrer Modifications
                    </button>
                </div>
            </form>
        </div>

        <!-- Informations actuelles -->
        <div class="mt-8 bg-gray-800 rounded-lg shadow border border-gray-700 p-6">
            <h3 class="text-lg font-medium text-white mb-4">📊 Informations Actuelles</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="text-gray-400">Créée le:</span>
                    <span class="text-white ml-2">{{ $ecole->created_at ? $ecole->created_at->format('d/m/Y à H:i') : 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-gray-400">Dernière modification:</span>
                    <span class="text-white ml-2">{{ $ecole->updated_at ? $ecole->updated_at->format('d/m/Y à H:i') : 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-gray-400">Membres inscrits:</span>
                    <span class="text-white ml-2">{{ $ecole->membres()->count() ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
