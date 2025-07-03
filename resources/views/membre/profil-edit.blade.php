@extends('layouts.app')

@section('title', 'Modifier mon profil')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Modifier mon profil
                </h1>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    École: {{ $user->ecole->nom ?? 'Non assigné' }}
                </span>
            </div>

            <form method="POST" action="{{ route('profil.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Informations personnelles -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Informations personnelles</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nom -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span class="text-red-500">*</span> Nom complet
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('name') border-red-500 @enderror"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span class="text-red-500">*</span> Email
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('email') border-red-500 @enderror"
                                   required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Téléphone -->
                        <div>
                            <label for="telephone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Téléphone
                            </label>
                            <input type="tel" 
                                   id="telephone" 
                                   name="telephone" 
                                   value="{{ old('telephone', $user->telephone) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('telephone') border-red-500 @enderror">
                            @error('telephone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date de naissance -->
                        <div>
                            <label for="date_naissance" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Date de naissance
                            </label>
                            <input type="date" 
                                   id="date_naissance" 
                                   name="date_naissance" 
                                   value="{{ old('date_naissance', $user->date_naissance) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('date_naissance') border-red-500 @enderror">
                            @error('date_naissance')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sexe -->
                        <div>
                            <label for="sexe" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Sexe
                            </label>
                            <select id="sexe" 
                                    name="sexe" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('sexe') border-red-500 @enderror">
                                <option value="">Sélectionner...</option>
                                <option value="M" {{ old('sexe', $user->sexe) === 'M' ? 'selected' : '' }}>Homme</option>
                                <option value="F" {{ old('sexe', $user->sexe) === 'F' ? 'selected' : '' }}>Femme</option>
                                <option value="Autre" {{ old('sexe', $user->sexe) === 'Autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('sexe')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Adresse -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Adresse</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Adresse -->
                        <div class="md:col-span-2">
                            <label for="adresse" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Adresse
                            </label>
                            <input type="text" 
                                   id="adresse" 
                                   name="adresse" 
                                   value="{{ old('adresse', $user->adresse) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('adresse') border-red-500 @enderror">
                            @error('adresse')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ville -->
                        <div>
                            <label for="ville" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Ville
                            </label>
                            <input type="text" 
                                   id="ville" 
                                   name="ville" 
                                   value="{{ old('ville', $user->ville) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('ville') border-red-500 @enderror">
                            @error('ville')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Code postal -->
                        <div>
                            <label for="code_postal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Code postal
                            </label>
                            <input type="text" 
                                   id="code_postal" 
                                   name="code_postal" 
                                   value="{{ old('code_postal', $user->code_postal) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('code_postal') border-red-500 @enderror">
                            @error('code_postal')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Contact d'urgence -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Contact d'urgence</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nom contact urgence -->
                        <div>
                            <label for="contact_urgence_nom" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nom du contact
                            </label>
                            <input type="text" 
                                   id="contact_urgence_nom" 
                                   name="contact_urgence_nom" 
                                   value="{{ old('contact_urgence_nom', $user->contact_urgence_nom) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('contact_urgence_nom') border-red-500 @enderror">
                            @error('contact_urgence_nom')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Téléphone contact urgence -->
                        <div>
                            <label for="contact_urgence_telephone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Téléphone du contact
                            </label>
                            <input type="tel" 
                                   id="contact_urgence_telephone" 
                                   name="contact_urgence_telephone" 
                                   value="{{ old('contact_urgence_telephone', $user->contact_urgence_telephone) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('contact_urgence_telephone') border-red-500 @enderror">
                            @error('contact_urgence_telephone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Informations famille -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Informations famille</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nom famille/groupe -->
                        <div>
                            <label for="nom_famille_groupe" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nom famille/groupe
                            </label>
                            <input type="text" 
                                   id="nom_famille_groupe" 
                                   name="nom_famille_groupe" 
                                   value="{{ old('nom_famille_groupe', $user->nom_famille_groupe) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('nom_famille_groupe') border-red-500 @enderror">
                            @error('nom_famille_groupe')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contact principal famille -->
                        <div>
                            <label for="contact_principal_famille" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Contact principal famille
                            </label>
                            <input type="text" 
                                   id="contact_principal_famille" 
                                   name="contact_principal_famille" 
                                   value="{{ old('contact_principal_famille', $user->contact_principal_famille) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('contact_principal_famille') border-red-500 @enderror">
                            @error('contact_principal_famille')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Téléphone principal famille -->
                        <div>
                            <label for="telephone_principal_famille" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Téléphone principal famille
                            </label>
                            <input type="tel" 
                                   id="telephone_principal_famille" 
                                   name="telephone_principal_famille" 
                                   value="{{ old('telephone_principal_famille', $user->telephone_principal_famille) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('telephone_principal_famille') border-red-500 @enderror">
                            @error('telephone_principal_famille')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes famille -->
                        <div>
                            <label for="notes_famille" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Notes famille
                            </label>
                            <textarea id="notes_famille" 
                                      name="notes_famille" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('notes_famille') border-red-500 @enderror">{{ old('notes_famille', $user->notes_famille) }}</textarea>
                            @error('notes_famille')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex justify-between pt-6">
                    <a href="{{ route('profil.index') }}" 
                       class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
                        ← Retour
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        💾 Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
