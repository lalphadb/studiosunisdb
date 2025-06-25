@extends('layouts.admin')
@section('title', 'Modifier Utilisateur')

@section('content')
<div class="space-y-6">
    <!-- Header avec couleur bleue -->
    <div class="bg-gradient-to-r from-blue-500 to-cyan-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Modifier Utilisateur
                </h1>
                <p class="text-blue-100 text-lg">{{ $user->name }} - {{ $user->email }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.users.show', $user) }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Voir Profil
                </a>
                <a href="{{ route('admin.users.index') }}" 
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

    <!-- Formulaire -->
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Informations personnelles -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
                <div class="bg-blue-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Informations Personnelles
                    </h3>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Nom -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-300 mb-2">
                            Nom complet <span class="text-red-400">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $user->name) }}"
                               required
                               class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                        @error('name')
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
                               value="{{ old('email', $user->email) }}"
                               required
                               class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                        @error('email')
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
                               value="{{ old('telephone', $user->telephone) }}"
                               class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('telephone') border-red-500 @enderror">
                        @error('telephone')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date de naissance -->
                    <div>
                        <label for="date_naissance" class="block text-sm font-medium text-slate-300 mb-2">
                            Date de naissance
                        </label>
                        <input type="date" 
                               id="date_naissance" 
                               name="date_naissance" 
                               value="{{ old('date_naissance', $user->date_naissance ? $user->date_naissance->format('Y-m-d') : '') }}"
                               class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('date_naissance') border-red-500 @enderror">
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
                                class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sexe') border-red-500 @enderror">
                            <option value="">Non spécifié</option>
                            <option value="M" {{ old('sexe', $user->sexe) == 'M' ? 'selected' : '' }}>Masculin</option>
                            <option value="F" {{ old('sexe', $user->sexe) == 'F' ? 'selected' : '' }}>Féminin</option>
                            <option value="Autre" {{ old('sexe', $user->sexe) == 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>

                    <!-- Adresse -->
                    <div>
                        <label for="adresse" class="block text-sm font-medium text-slate-300 mb-2">
                            Adresse
                        </label>
                        <textarea id="adresse" 
                                  name="adresse" 
                                  rows="2"
                                  class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('adresse') border-red-500 @enderror">{{ old('adresse', $user->adresse) }}</textarea>
                    </div>

                    <!-- Ville et Code postal -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="ville" class="block text-sm font-medium text-slate-300 mb-2">
                                Ville
                            </label>
                            <input type="text" 
                                   id="ville" 
                                   name="ville" 
                                   value="{{ old('ville', $user->ville) }}"
                                   class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('ville') border-red-500 @enderror">
                        </div>
                        <div>
                            <label for="code_postal" class="block text-sm font-medium text-slate-300 mb-2">
                                Code postal
                            </label>
                            <input type="text" 
                                   id="code_postal" 
                                   name="code_postal" 
                                   value="{{ old('code_postal', $user->code_postal) }}"
                                   class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('code_postal') border-red-500 @enderror">
                        </div>
                    </div>

                    <!-- Contact d'urgence -->
                    <div class="border-t border-slate-600 pt-6">
                        <h4 class="text-lg font-medium text-white mb-4">🚨 Contact d'urgence</h4>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="contact_urgence_nom" class="block text-sm font-medium text-slate-300 mb-2">
                                    Nom du contact d'urgence
                                </label>
                                <input type="text" 
                                       id="contact_urgence_nom" 
                                       name="contact_urgence_nom" 
                                       value="{{ old('contact_urgence_nom', $user->contact_urgence_nom) }}"
                                       class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="contact_urgence_telephone" class="block text-sm font-medium text-slate-300 mb-2">
                                    Téléphone d'urgence
                                </label>
                                <input type="tel" 
                                       id="contact_urgence_telephone" 
                                       name="contact_urgence_telephone" 
                                       value="{{ old('contact_urgence_telephone', $user->contact_urgence_telephone) }}"
                                       class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations système -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
                <div class="bg-cyan-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3z"/>
                        </svg>
                        Informations Système
                    </h3>
                </div>
                <div class="p-6 space-y-6">
                    <!-- École -->
                    <div>
                        <label for="ecole_id" class="block text-sm font-medium text-slate-300 mb-2">
                            École <span class="text-red-400">*</span>
                        </label>
                        <select id="ecole_id" 
                                name="ecole_id" 
                                required
                                class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('ecole_id') border-red-500 @enderror">
                            <option value="">Sélectionner une école</option>
                            @foreach($ecoles ?? [] as $ecole)
                                <option value="{{ $ecole->id }}" {{ old('ecole_id', $user->ecole_id) == $ecole->id ? 'selected' : '' }}>
                                    {{ $ecole->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('ecole_id')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rôle -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-slate-300 mb-2">
                            Rôle <span class="text-red-400">*</span>
                        </label>
                        <select id="role" 
                                name="role" 
                                required
                                class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-500 @enderror">
                            <option value="">Sélectionner un rôle</option>
                            @foreach($roles ?? [] as $roleValue => $roleLabel)
                                @php
                                    $currentRole = old('role', $user->roles->first()->name ?? '');
                                @endphp
                                <option value="{{ $roleValue }}" {{ $currentRole == $roleValue ? 'selected' : '' }}>
                                    {{ $roleLabel }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Statut actif -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="active" 
                                   value="1" 
                                   {{ old('active', $user->active) ? 'checked' : '' }}
                                   class="rounded border-slate-600 text-blue-600 focus:ring-blue-500 bg-slate-700">
                            <span class="ml-2 text-slate-300">Utilisateur actif</span>
                        </label>
                    </div>

                    <!-- Date d'inscription -->
                    <div>
                        <label for="date_inscription" class="block text-sm font-medium text-slate-300 mb-2">
                            Date d'inscription
                        </label>
                        <input type="date" 
                               id="date_inscription" 
                               name="date_inscription" 
                               value="{{ old('date_inscription', $user->date_inscription ? $user->date_inscription->format('Y-m-d') : '') }}"
                               class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Mot de passe optionnel -->
                    <div class="border-t border-slate-600 pt-6">
                        <h4 class="text-slate-300 font-medium mb-4">🔒 Modifier le mot de passe (optionnel)</h4>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-slate-300 mb-2">
                                    Nouveau mot de passe
                                </label>
                                <input type="password" 
                                       id="password" 
                                       name="password"
                                       placeholder="Laisser vide pour conserver l'actuel"
                                       class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror">
                                @error('password')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-2">
                                    Confirmer nouveau mot de passe
                                </label>
                                <input type="password" 
                                       id="password_confirmation" 
                                       name="password_confirmation"
                                       placeholder="Confirmer le nouveau mot de passe"
                                       class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-slate-300 mb-2">
                            Notes
                        </label>
                        <textarea id="notes" 
                                  name="notes" 
                                  rows="4"
                                  class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('notes', $user->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="flex items-center justify-between bg-slate-800 rounded-xl border border-slate-700 px-6 py-4">
            <a href="{{ route('admin.users.show', $user) }}" 
               class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Annuler
            </a>
            
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Mettre à jour
            </button>
        </div>
    </form>

    <!-- Statistiques actuelles -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <h3 class="text-lg font-medium text-white mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Statistiques Actuelles
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <span class="text-slate-400">Créé le:</span>
                <span class="text-white ml-2">{{ $user->created_at->format('d/m/Y à H:i') }}</span>
            </div>
            <div>
                <span class="text-slate-400">Dernière modification:</span>
                <span class="text-white ml-2">{{ $user->updated_at->format('d/m/Y à H:i') }}</span>
            </div>
            <div>
                <span class="text-slate-400">Âge:</span>
                <span class="text-white ml-2">{{ $user->age ?? 'Non défini' }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
