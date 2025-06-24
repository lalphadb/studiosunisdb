@extends('layouts.admin')
@section('title', 'Modifier École')

@section('content')
<div class="space-y-6">
    <!-- Header avec couleur verte -->
    <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Modifier École
                </h1>
                <p class="text-green-100 text-lg">Modification de "{{ $ecole->nom }}"</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.ecoles.show', $ecole) }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Voir École
                </a>
                <a href="{{ route('admin.ecoles.index') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour Liste
                </a>
            </div>
        </div>
    </div>

    <!-- Messages d'erreur/succès -->
    @if($errors->any())
    <div class="bg-red-900 border border-red-700 text-red-100 px-4 py-3 rounded-lg">
        <h4 class="font-medium mb-2">Erreurs de validation :</h4>
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session('success'))
    <div class="bg-green-900 border border-green-700 text-green-100 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <!-- Formulaire -->
    <form action="{{ route('admin.ecoles.update', $ecole) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Informations de base -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
                <div class="bg-green-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3z"/>
                        </svg>
                        Informations de Base
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
                               value="{{ old('nom', $ecole->nom) }}"
                               required
                               class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('nom') border-red-500 @enderror">
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
                               value="{{ old('code', $ecole->code) }}"
                               required
                               maxlength="10"
                               class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('code') border-red-500 @enderror">
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
                                  class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('description') border-red-500 @enderror">{{ old('description', $ecole->description) }}</textarea>
                        @error('description')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Propriétaire -->
                    <div>
                        <label for="proprietaire" class="block text-sm font-medium text-slate-300 mb-2">
                            Propriétaire
                        </label>
                        <input type="text" 
                               id="proprietaire" 
                               name="proprietaire" 
                               value="{{ old('proprietaire', $ecole->proprietaire) }}"
                               placeholder="Nom du propriétaire"
                               class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('proprietaire') border-red-500 @enderror">
                        @error('proprietaire')
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
                                class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('active') border-red-500 @enderror">
                            <option value="1" {{ old('active', $ecole->active) == 1 ? 'selected' : '' }}>✅ Actif</option>
                            <option value="0" {{ old('active', $ecole->active) == 0 ? 'selected' : '' }}>❌ Inactif</option>
                        </select>
                        @error('active')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Adresse et Contact -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
                <div class="bg-emerald-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Adresse et Contact
                    </h3>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Adresse -->
                    <div>
                        <label for="adresse" class="block text-sm font-medium text-slate-300 mb-2">
                            Adresse <span class="text-red-400">*</span>
                        </label>
                        <textarea id="adresse" 
                                  name="adresse" 
                                  rows="3"
                                  required
                                  class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('adresse') border-red-500 @enderror">{{ old('adresse', $ecole->adresse) }}</textarea>
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
                                   value="{{ old('ville', $ecole->ville) }}"
                                   required
                                   class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('ville') border-red-500 @enderror">
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
                                    class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('province') border-red-500 @enderror">
                                <option value="QC" {{ old('province', $ecole->province) == 'QC' ? 'selected' : '' }}>Québec</option>
                                <option value="ON" {{ old('province', $ecole->province) == 'ON' ? 'selected' : '' }}>Ontario</option>
                                <option value="BC" {{ old('province', $ecole->province) == 'BC' ? 'selected' : '' }}>Colombie-Britannique</option>
                                <option value="AB" {{ old('province', $ecole->province) == 'AB' ? 'selected' : '' }}>Alberta</option>
                                <option value="MB" {{ old('province', $ecole->province) == 'MB' ? 'selected' : '' }}>Manitoba</option>
                                <option value="SK" {{ old('province', $ecole->province) == 'SK' ? 'selected' : '' }}>Saskatchewan</option>
                                <option value="NS" {{ old('province', $ecole->province) == 'NS' ? 'selected' : '' }}>Nouvelle-Écosse</option>
                                <option value="NB" {{ old('province', $ecole->province) == 'NB' ? 'selected' : '' }}>Nouveau-Brunswick</option>
                                <option value="NL" {{ old('province', $ecole->province) == 'NL' ? 'selected' : '' }}>Terre-Neuve-et-Labrador</option>
                                <option value="PE" {{ old('province', $ecole->province) == 'PE' ? 'selected' : '' }}>Île-du-Prince-Édouard</option>
                                <option value="NT" {{ old('province', $ecole->province) == 'NT' ? 'selected' : '' }}>Territoires du Nord-Ouest</option>
                                <option value="NU" {{ old('province', $ecole->province) == 'NU' ? 'selected' : '' }}>Nunavut</option>
                                <option value="YT" {{ old('province', $ecole->province) == 'YT' ? 'selected' : '' }}>Yukon</option>
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
                               value="{{ old('code_postal', $ecole->code_postal) }}"
                               required
                               class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('code_postal') border-red-500 @enderror">
                        @error('code_postal')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Téléphone -->
                    <div>
                        <label for="telephone" class="block text-sm font-medium text-slate-300 mb-2">
                            Téléphone <span class="text-red-400">*</span>
                        </label>
                        <input type="tel" 
                               id="telephone" 
                               name="telephone" 
                               value="{{ old('telephone', $ecole->telephone) }}"
                               required
                               class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('telephone') border-red-500 @enderror">
                        @error('telephone')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-300 mb-2">
                            Email <span class="text-red-400">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $ecole->email) }}"
                               required
                               class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('email') border-red-500 @enderror">
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
                               value="{{ old('site_web', $ecole->site_web) }}"
                               class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('site_web') border-red-500 @enderror">
                        @error('site_web')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="flex items-center justify-between bg-slate-800 rounded-xl border border-slate-700 px-6 py-4">
            <a href="{{ route('admin.ecoles.show', $ecole) }}" 
               class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Annuler
            </a>
            
            <button type="submit" 
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Enregistrer les Modifications
            </button>
        </div>
    </form>

    <!-- Informations actuelles -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <h3 class="text-lg font-medium text-white mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Statistiques Actuelles
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <span class="text-slate-400">Utilisateurs inscrits:</span>
                <span class="text-white ml-2 font-medium">{{ $ecole->users()->count() }}</span>
            </div>
            <div>
                <span class="text-slate-400">Créée le:</span>
                <span class="text-white ml-2">{{ $ecole->created_at->format('d/m/Y à H:i') }}</span>
            </div>
            <div>
                <span class="text-slate-400">Dernière modification:</span>
                <span class="text-white ml-2">{{ $ecole->updated_at->format('d/m/Y à H:i') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
