@extends('layouts.admin')

@section('title', 'Créer une Nouvelle École')

@section('content')
<!-- Header avec breadcrumb -->
<div class="mb-8">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-4">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="text-slate-400 hover:text-slate-300">Dashboard</a>
            </li>
            <li>
                <span class="text-slate-600">/</span>
            </li>
            <li>
                <a href="{{ route('admin.ecoles.index') }}" class="text-slate-400 hover:text-slate-300">Écoles</a>
            </li>
            <li>
                <span class="text-slate-600">/</span>
            </li>
            <li>
                <span class="text-white">Nouvelle École</span>
            </li>
        </ol>
    </nav>
    
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-white">Créer une Nouvelle École</h1>
        <a href="{{ route('admin.ecoles.index') }}" 
           class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center space-x-2">
            <span>↩️</span>
            <span>Retour à la liste</span>
        </a>
    </div>
</div>

<!-- Messages d'erreur -->
@if($errors->any())
<div class="mb-6 bg-red-900 border border-red-700 text-red-100 px-4 py-3 rounded-lg">
    <h4 class="font-medium mb-2">Erreurs de validation :</h4>
    <ul class="list-disc list-inside space-y-1">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Formulaire COMPLET -->
<form action="{{ route('admin.ecoles.store') }}" method="POST">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Informations de base -->
        <div class="bg-slate-800 rounded-lg border border-slate-700">
            <div class="px-6 py-4 border-b border-slate-700">
                <h3 class="text-lg font-medium text-white flex items-center space-x-2">
                    <span>🏫</span>
                    <span>Informations de Base</span>
                </h3>
            </div>
            <div class="p-6 space-y-6">
                <!-- Nom -->
                <div>
                    <label for="nom" class="block text-sm font-medium text-slate-300 mb-2">
                        Nom de l'École <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           id="nom" 
                           name="nom" 
                           value="{{ old('nom') }}"
                           required
                           placeholder="Ex: Studios Unis Montréal"
                           class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nom') border-red-500 @enderror">
                    @error('nom')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Code -->
                <div>
                    <label for="code" class="block text-sm font-medium text-slate-300 mb-2">
                        Code École <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           id="code" 
                           name="code" 
                           value="{{ old('code') }}"
                           required
                           maxlength="10"
                           placeholder="Ex: MTL, QBC, TR"
                           class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('code') border-red-500 @enderror">
                    @error('code')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-slate-300 mb-2">
                        Description
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="4"
                              placeholder="Description de l'école, spécialités, histoire..."
                              class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Statut -->
                <div>
                    <label for="active" class="block text-sm font-medium text-slate-300 mb-2">
                        Statut <span class="text-red-400">*</span>
                    </label>
                    <select id="active" 
                            name="active" 
                            required
                            class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('active') border-red-500 @enderror">
                        <option value="1" {{ old('active', 1) == 1 ? 'selected' : '' }}>Actif</option>
                        <option value="0" {{ old('active') == 0 ? 'selected' : '' }}>Inactif</option>
                    </select>
                    @error('active')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Adresse et Contact -->
        <div class="bg-slate-800 rounded-lg border border-slate-700">
            <div class="px-6 py-4 border-b border-slate-700">
                <h3 class="text-lg font-medium text-white flex items-center space-x-2">
                    <span>📍</span>
                    <span>Adresse et Contact</span>
                </h3>
            </div>
            <div class="p-6 space-y-6">
                <!-- Adresse -->
                <div>
                    <label for="adresse" class="block text-sm font-medium text-slate-300 mb-2">
                        Adresse <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           id="adresse" 
                           name="adresse" 
                           value="{{ old('adresse') }}"
                           required
                           placeholder="Ex: 123 Rue Principale"
                           class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('adresse') border-red-500 @enderror">
                    @error('adresse')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ville et Province -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="ville" class="block text-sm font-medium text-slate-300 mb-2">
                            Ville <span class="text-red-400">*</span>
                        </label>
                        <input type="text" 
                               id="ville" 
                               name="ville" 
                               value="{{ old('ville') }}"
                               required
                               placeholder="Ex: Montréal"
                               class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('ville') border-red-500 @enderror">
                        @error('ville')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="province" class="block text-sm font-medium text-slate-300 mb-2">
                            Province <span class="text-red-400">*</span>
                        </label>
                        <select id="province" 
                                name="province" 
                                required
                                class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('province') border-red-500 @enderror">
                            <option value="QC" {{ old('province', 'QC') == 'QC' ? 'selected' : '' }}>Québec</option>
                            <option value="ON" {{ old('province') == 'ON' ? 'selected' : '' }}>Ontario</option>
                            <option value="BC" {{ old('province') == 'BC' ? 'selected' : '' }}>Colombie-Britannique</option>
                            <option value="AB" {{ old('province') == 'AB' ? 'selected' : '' }}>Alberta</option>
                            <option value="MB" {{ old('province') == 'MB' ? 'selected' : '' }}>Manitoba</option>
                            <option value="SK" {{ old('province') == 'SK' ? 'selected' : '' }}>Saskatchewan</option>
                            <option value="NS" {{ old('province') == 'NS' ? 'selected' : '' }}>Nouvelle-Écosse</option>
                            <option value="NB" {{ old('province') == 'NB' ? 'selected' : '' }}>Nouveau-Brunswick</option>
                            <option value="NL" {{ old('province') == 'NL' ? 'selected' : '' }}>Terre-Neuve-et-Labrador</option>
                            <option value="PE" {{ old('province') == 'PE' ? 'selected' : '' }}>Île-du-Prince-Édouard</option>
                            <option value="NT" {{ old('province') == 'NT' ? 'selected' : '' }}>Territoires du Nord-Ouest</option>
                            <option value="NU" {{ old('province') == 'NU' ? 'selected' : '' }}>Nunavut</option>
                            <option value="YT" {{ old('province') == 'YT' ? 'selected' : '' }}>Yukon</option>
                        </select>
                        @error('province')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Code Postal -->
                <div>
                    <label for="code_postal" class="block text-sm font-medium text-slate-300 mb-2">
                        Code Postal <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           id="code_postal" 
                           name="code_postal" 
                           value="{{ old('code_postal') }}"
                           required
                           placeholder="Ex: H1H 1H1"
                           class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('code_postal') border-red-500 @enderror">
                    @error('code_postal')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Téléphone -->
                <div>
                    <label for="telephone" class="block text-sm font-medium text-slate-300 mb-2">
                        Téléphone
                    </label>
                    <input type="tel" 
                           id="telephone" 
                           name="telephone" 
                           value="{{ old('telephone') }}"
                           placeholder="Ex: 418-123-4567"
                           class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('telephone') border-red-500 @enderror">
                    @error('telephone')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300 mb-2">
                        Email
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           placeholder="Ex: contact@ecole.ca"
                           class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
                    @error('email')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Site Web -->
                <div>
                    <label for="site_web" class="block text-sm font-medium text-slate-300 mb-2">
                        Site Web
                    </label>
                    <input type="url" 
                           id="site_web" 
                           name="site_web" 
                           value="{{ old('site_web') }}"
                           placeholder="https://exemple.com"
                           class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('site_web') border-red-500 @enderror">
                    @error('site_web')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Boutons d'action -->
    <div class="mt-8 flex items-center justify-between">
        <a href="{{ route('admin.ecoles.index') }}" 
           class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg text-sm font-medium flex items-center space-x-2">
            <span>↩️</span>
            <span>Annuler</span>
        </a>
        
        <button type="submit" 
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg text-sm font-medium flex items-center space-x-2">
            <span>✨</span>
            <span>Créer l'École</span>
        </button>
    </div>
</form>
@endsection
