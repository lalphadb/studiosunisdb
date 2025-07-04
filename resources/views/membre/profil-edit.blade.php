@extends('layouts.membre')

@section('title', 'Modifier mon profil')

@section('content')
<div class="p-6 space-y-6">
    <!-- Header Profil -->
    <div class="bg-gradient-to-r from-blue-500 to-cyan-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">✏️ Modifier mon Profil</h1>
                <p class="text-blue-100">{{ $user->ecole->nom ?? 'École non assignée' }}</p>
            </div>
            <div class="text-right">
                <div class="bg-blue-500 bg-opacity-50 px-4 py-2 rounded-lg">
                    <div class="text-sm text-blue-100">{{ $user->name }}</div>
                    <div class="text-xs text-blue-200">Membre</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire de modification -->
    <div class="bg-slate-800 rounded-xl p-6 border border-slate-700">
        <form method="POST" action="{{ route('membre.profil.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Informations personnelles -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-white border-b border-slate-600 pb-2">
                    👤 Informations personnelles
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nom -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-300 mb-2">
                            <span class="text-red-400">*</span> Nom complet
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $user->name) }}"
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 @error('name') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-300 mb-2">
                            <span class="text-red-400">*</span> Email
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}"
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 @error('email') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Téléphone -->
                    <div>
                        <label for="telephone" class="block text-sm font-medium text-slate-300 mb-2">
                            📞 Téléphone
                        </label>
                        <input type="tel" 
                               id="telephone" 
                               name="telephone" 
                               value="{{ old('telephone', $user->telephone) }}"
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 @error('telephone') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                        @error('telephone')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date de naissance -->
                    <div>
                        <label for="date_naissance" class="block text-sm font-medium text-slate-300 mb-2">
                            🎂 Date de naissance
                        </label>
                        <input type="date" 
                               id="date_naissance" 
                               name="date_naissance" 
                               value="{{ old('date_naissance', $user->date_naissance?->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 @error('date_naissance') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                        @error('date_naissance')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sexe -->
                    <div>
                        <label for="sexe" class="block text-sm font-medium text-slate-300 mb-2">
                            Sexe
                        </label>
                        <select id="sexe" 
                                name="sexe" 
                                class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 @error('sexe') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                            <option value="">Sélectionner...</option>
                            <option value="M" {{ old('sexe', $user->sexe) === 'M' ? 'selected' : '' }}>Homme</option>
                            <option value="F" {{ old('sexe', $user->sexe) === 'F' ? 'selected' : '' }}>Femme</option>
                            <option value="Autre" {{ old('sexe', $user->sexe) === 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('sexe')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Adresse -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-white border-b border-slate-600 pb-2">
                    🏠 Adresse
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Adresse -->
                    <div class="md:col-span-2">
                        <label for="adresse" class="block text-sm font-medium text-slate-300 mb-2">
                            Adresse
                        </label>
                        <input type="text" 
                               id="adresse" 
                               name="adresse" 
                               value="{{ old('adresse', $user->adresse) }}"
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 @error('adresse') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                        @error('adresse')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ville -->
                    <div>
                        <label for="ville" class="block text-sm font-medium text-slate-300 mb-2">
                            Ville
                        </label>
                        <input type="text" 
                               id="ville" 
                               name="ville" 
                               value="{{ old('ville', $user->ville) }}"
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 @error('ville') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                        @error('ville')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Code postal -->
                    <div>
                        <label for="code_postal" class="block text-sm font-medium text-slate-300 mb-2">
                            Code postal
                        </label>
                        <input type="text" 
                               id="code_postal" 
                               name="code_postal" 
                               value="{{ old('code_postal', $user->code_postal) }}"
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 @error('code_postal') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                        @error('code_postal')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Contact d'urgence -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-white border-b border-slate-600 pb-2">
                    🚨 Contact d'urgence
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nom contact urgence -->
                    <div>
                        <label for="contact_urgence_nom" class="block text-sm font-medium text-slate-300 mb-2">
                            Nom du contact
                        </label>
                        <input type="text" 
                               id="contact_urgence_nom" 
                               name="contact_urgence_nom" 
                               value="{{ old('contact_urgence_nom', $user->contact_urgence_nom) }}"
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 @error('contact_urgence_nom') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                        @error('contact_urgence_nom')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Téléphone contact urgence -->
                    <div>
                        <label for="contact_urgence_telephone" class="block text-sm font-medium text-slate-300 mb-2">
                            Téléphone du contact
                        </label>
                        <input type="tel" 
                               id="contact_urgence_telephone" 
                               name="contact_urgence_telephone" 
                               value="{{ old('contact_urgence_telephone', $user->contact_urgence_telephone) }}"
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 @error('contact_urgence_telephone') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                        @error('contact_urgence_telephone')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Informations famille -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-white border-b border-slate-600 pb-2">
                    👨‍👩‍👧‍👦 Informations famille
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nom famille/groupe -->
                    <div>
                        <label for="nom_famille_groupe" class="block text-sm font-medium text-slate-300 mb-2">
                            Nom famille/groupe
                        </label>
                        <input type="text" 
                               id="nom_famille_groupe" 
                               name="nom_famille_groupe" 
                               value="{{ old('nom_famille_groupe', $user->nom_famille_groupe) }}"
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 @error('nom_famille_groupe') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                        @error('nom_famille_groupe')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact principal famille -->
                    <div>
                        <label for="contact_principal_famille" class="block text-sm font-medium text-slate-300 mb-2">
                            Contact principal famille
                        </label>
                        <input type="text" 
                               id="contact_principal_famille" 
                               name="contact_principal_famille" 
                               value="{{ old('contact_principal_famille', $user->contact_principal_famille) }}"
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 @error('contact_principal_famille') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                        @error('contact_principal_famille')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Téléphone principal famille -->
                    <div>
                        <label for="telephone_principal_famille" class="block text-sm font-medium text-slate-300 mb-2">
                            Téléphone principal famille
                        </label>
                        <input type="tel" 
                               id="telephone_principal_famille" 
                               name="telephone_principal_famille" 
                               value="{{ old('telephone_principal_famille', $user->telephone_principal_famille) }}"
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 @error('telephone_principal_famille') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                        @error('telephone_principal_famille')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes famille -->
                    <div>
                        <label for="notes_famille" class="block text-sm font-medium text-slate-300 mb-2">
                            Notes famille
                        </label>
                        <textarea id="notes_famille" 
                                  name="notes_famille" 
                                  rows="3"
                                  class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 @error('notes_famille') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">{{ old('notes_famille', $user->notes_famille) }}</textarea>
                        @error('notes_famille')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Boutons -->
            <div class="flex justify-between pt-6 border-t border-slate-600">
                <a href="{{ route('membre.profil') }}" 
                   class="px-6 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg transition-colors">
                    ← Retour
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    💾 Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
