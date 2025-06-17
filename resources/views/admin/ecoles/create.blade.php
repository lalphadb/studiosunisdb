@extends('layouts.admin')

@section('title', 'Nouvelle École')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-white">🏫 Nouvelle École</h1>
            <p class="text-slate-400 mt-1">Ajouter une nouvelle école au réseau Studios Unis</p>
        </div>
        <a href="{{ route('admin.ecoles.index') }}" 
           class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
            ← Retour
        </a>
    </div>

    <div class="bg-slate-800 border border-slate-700 rounded-lg p-6">
        <form method="POST" action="{{ route('admin.ecoles.store') }}">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Colonne gauche -->
                <div class="space-y-6">
                    <h3 class="text-xl font-semibold text-white border-b border-slate-700 pb-3">
                        Informations générales
                    </h3>
                    
                    <div>
                        <label for="nom" class="block text-sm font-medium text-white mb-2">Nom de l'école *</label>
                        <input type="text" name="nom" id="nom" required
                               class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('nom') border-red-500 @enderror" 
                               value="{{ old('nom') }}">
                        @error('nom')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="directeur" class="block text-sm font-medium text-white mb-2">Directeur *</label>
                        <input type="text" name="directeur" id="directeur" required
                               class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('directeur') border-red-500 @enderror" 
                               value="{{ old('directeur') }}">
                        @error('directeur')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="adresse" class="block text-sm font-medium text-white mb-2">Adresse complète *</label>
                        <input type="text" name="adresse" id="adresse" required
                               class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('adresse') border-red-500 @enderror" 
                               value="{{ old('adresse') }}">
                        @error('adresse')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="ville" class="block text-sm font-medium text-white mb-2">Ville *</label>
                            <input type="text" name="ville" id="ville" required
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('ville') border-red-500 @enderror" 
                                   value="{{ old('ville') }}">
                            @error('ville')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="province" class="block text-sm font-medium text-white mb-2">Province *</label>
                            <select name="province" id="province" required
                                    class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('province') border-red-500 @enderror">
                                <option value="Quebec" {{ old('province') == 'Quebec' ? 'selected' : '' }}>Québec</option>
                                <option value="Ontario" {{ old('province') == 'Ontario' ? 'selected' : '' }}>Ontario</option>
                                <option value="Alberta" {{ old('province') == 'Alberta' ? 'selected' : '' }}>Alberta</option>
                                <option value="British Columbia" {{ old('province') == 'British Columbia' ? 'selected' : '' }}>Colombie-Britannique</option>
                            </select>
                            @error('province')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label for="code_postal" class="block text-sm font-medium text-white mb-2">Code postal *</label>
                        <input type="text" name="code_postal" id="code_postal" required
                               class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('code_postal') border-red-500 @enderror" 
                               value="{{ old('code_postal') }}" placeholder="A1A 1A1">
                        @error('code_postal')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Colonne droite -->
                <div class="space-y-6">
                    <h3 class="text-xl font-semibold text-white border-b border-slate-700 pb-3">
                        Contact & Configuration
                    </h3>

                    <div>
                        <label for="telephone" class="block text-sm font-medium text-white mb-2">Téléphone *</label>
                        <input type="tel" name="telephone" id="telephone" required
                               class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('telephone') border-red-500 @enderror" 
                               value="{{ old('telephone') }}" placeholder="514-123-4567">
                        @error('telephone')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-white mb-2">Email *</label>
                        <input type="email" name="email" id="email" required
                               class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('email') border-red-500 @enderror" 
                               value="{{ old('email') }}">
                        @error('email')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="site_web" class="block text-sm font-medium text-white mb-2">Site web</label>
                        <input type="url" name="site_web" id="site_web"
                               class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('site_web') border-red-500 @enderror" 
                               value="{{ old('site_web') }}" placeholder="https://exemple.com">
                        @error('site_web')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="capacite_max" class="block text-sm font-medium text-white mb-2">Capacité max *</label>
                            <input type="number" name="capacite_max" id="capacite_max" min="10" max="500" required
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('capacite_max') border-red-500 @enderror" 
                                   value="{{ old('capacite_max', 100) }}">
                            @error('capacite_max')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="statut" class="block text-sm font-medium text-white mb-2">Statut *</label>
                            <select name="statut" id="statut" required
                                    class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('statut') border-red-500 @enderror">
                                <option value="actif" {{ old('statut', 'actif') == 'actif' ? 'selected' : '' }}>Actif</option>
                                <option value="inactif" {{ old('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                            </select>
                            @error('statut')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-white mb-2">Description</label>
                        <textarea name="description" id="description" rows="4"
                                  class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 @error('description') border-red-500 @enderror"
                                  placeholder="Description de l'école, services offerts, spécialités...">{{ old('description') }}</textarea>
                        @error('description')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-slate-700">
                <a href="{{ route('admin.ecoles.index') }}" 
                   class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                    Annuler
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                    💾 Créer l'école
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
