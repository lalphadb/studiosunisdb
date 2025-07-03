@extends('layouts.admin')

@section('title', 'Modifier Utilisateur')

@section('content')
<div class="space-y-6">
    <x-module-header 
        module="users"
        title="Modifier l'utilisateur"
        subtitle="Modification des informations de {{ $user->name }}"
        :createRoute="null"
    />

    <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-6">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Informations personnelles -->
            <div class="bg-slate-700/30 rounded-lg p-6">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                    👤 <span class="ml-2">Informations personnelles</span>
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom complet -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Nom complet <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Email <span class="text-red-400">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        @error('email')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Téléphone -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Téléphone</label>
                        <input type="text" name="telephone" value="{{ old('telephone', $user->telephone) }}"
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        @error('telephone')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date de naissance -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Date de naissance</label>
                        <input type="date" name="date_naissance" value="{{ old('date_naissance', $user->date_naissance?->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        @error('date_naissance')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sexe -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Sexe</label>
                        <select name="sexe" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="">Sélectionner</option>
                            <option value="M" {{ old('sexe', $user->sexe) === 'M' ? 'selected' : '' }}>Masculin</option>
                            <option value="F" {{ old('sexe', $user->sexe) === 'F' ? 'selected' : '' }}>Féminin</option>
                            <option value="Autre" {{ old('sexe', $user->sexe) === 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('sexe')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- École -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            École <span class="text-red-400">*</span>
                        </label>
                        <select name="ecole_id" required class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="">Sélectionner une école</option>
                            @foreach($ecoles as $ecole)
                                <option value="{{ $ecole->id }}" {{ old('ecole_id', $user->ecole_id) == $ecole->id ? 'selected' : '' }}>
                                    {{ $ecole->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('ecole_id')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- SECTION MOT DE PASSE -->
            <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-6">
                <h3 class="text-lg font-bold text-red-400 mb-4 flex items-center">
                    🔐 <span class="ml-2">Modifier le mot de passe</span>
                </h3>
                <p class="text-slate-400 text-sm mb-4">
                    Laissez vide pour conserver le mot de passe actuel
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nouveau mot de passe -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Nouveau mot de passe
                        </label>
                        <input type="password" name="password" 
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-red-500 focus:ring-1 focus:ring-red-500"
                               placeholder="Minimum 8 caractères">
                        @error('password')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirmation mot de passe -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Confirmer le mot de passe
                        </label>
                        <input type="password" name="password_confirmation" 
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-red-500 focus:ring-1 focus:ring-red-500"
                               placeholder="Répéter le mot de passe">
                    </div>
                </div>
            </div>

            <!-- Adresse -->
            <div class="bg-slate-700/30 rounded-lg p-6">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                    🏠 <span class="ml-2">Adresse</span>
                </h3>
                
                <div class="space-y-4">
                    <!-- Adresse complète -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Adresse complète</label>
                        <input type="text" name="adresse" value="{{ old('adresse', $user->adresse) }}"
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                               placeholder="3434 RU DES PETIT PIEDS">
                        @error('adresse')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Ville -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Ville</label>
                            <input type="text" name="ville" value="{{ old('ville', $user->ville) }}"
                                   class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                   placeholder="QC">
                            @error('ville')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Code postal -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Code postal</label>
                            <input type="text" name="code_postal" value="{{ old('code_postal', $user->code_postal) }}"
                                   class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                   placeholder="G4G3H5">
                            @error('code_postal')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact d'urgence -->
            <div class="bg-orange-500/10 border border-orange-500/30 rounded-lg p-6">
                <h3 class="text-lg font-bold text-orange-400 mb-4 flex items-center">
                    🚨 <span class="ml-2">Contact d'urgence</span>
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nom du contact -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Nom du contact</label>
                        <input type="text" name="contact_urgence_nom" value="{{ old('contact_urgence_nom', $user->contact_urgence_nom) }}"
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-orange-500 focus:ring-1 focus:ring-orange-500"
                               placeholder="MAMAN">
                        @error('contact_urgence_nom')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Téléphone d'urgence -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Téléphone d'urgence</label>
                        <input type="text" name="contact_urgence_telephone" value="{{ old('contact_urgence_telephone', $user->contact_urgence_telephone) }}"
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-orange-500 focus:ring-1 focus:ring-orange-500"
                               placeholder="111-111-1111">
                        @error('contact_urgence_telephone')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- SECTION INFORMATIONS FAMILLE -->
            <div class="bg-purple-500/10 border border-purple-500/30 rounded-lg p-6">
                <h3 class="text-lg font-bold text-purple-400 mb-4 flex items-center">
                    👨‍👩‍👧‍👦 <span class="ml-2">Informations Famille</span>
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom de famille groupé -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Nom de famille groupé</label>
                        <input type="text" name="nom_famille_groupe" value="{{ old('nom_famille_groupe', $user->nom_famille_groupe) }}"
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-purple-500 focus:ring-1 focus:ring-purple-500"
                               placeholder="Ex: Famille Durand">
                        <p class="text-xs text-slate-400 mt-1">Optionnel: seulement si membre nom différent</p>
                        @error('nom_famille_groupe')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact principal famille -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Contact principal famille</label>
                        <input type="text" name="contact_principal_famille" value="{{ old('contact_principal_famille', $user->contact_principal_famille) }}"
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-purple-500 focus:ring-1 focus:ring-purple-500"
                               placeholder="Ex: Papa Jean Durand">
                        @error('contact_principal_famille')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Téléphone principal famille -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Téléphone principal famille</label>
                        <input type="text" name="telephone_principal_famille" value="{{ old('telephone_principal_famille', $user->telephone_principal_famille) }}"
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-purple-500 focus:ring-1 focus:ring-purple-500"
                               placeholder="Téléphone du contact principal">
                        @error('telephone_principal_famille')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes famille -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Notes famille</label>
                        <textarea name="notes_famille" rows="3" 
                                  class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-purple-500 focus:ring-1 focus:ring-purple-500"
                                  placeholder="Notes partagées pour l'école de famille">{{ old('notes_famille', $user->notes_famille) }}</textarea>
                        @error('notes_famille')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Chef de famille -->
                @php
                    $famillesPossibles = \App\Models\User::where('ecole_id', $user->ecole_id)
                        ->where('id', '!=', $user->id)
                        ->orderBy('name')
                        ->get();
                @endphp
                
                @if($famillesPossibles->count() > 0)
                <div class="mt-4">
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Famille principale (si ce membre fait partie d'une famille)
                    </label>
                    <select name="famille_principale_id" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-purple-500 focus:ring-1 focus:ring-purple-500">
                        <option value="">Ce membre est indépendant</option>
                        @foreach($famillesPossibles as $membreFamille)
                            <option value="{{ $membreFamille->id }}" 
                                    {{ old('famille_principale_id', $user->famille_principale_id) == $membreFamille->id ? 'selected' : '' }}>
                                {{ $membreFamille->name }} ({{ $membreFamille->email }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-400 mt-1">
                        Sélectionnez le chef de famille si cette personne fait partie d'une famille existante
                    </p>
                    @error('famille_principale_id')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                @endif
            </div>

            <!-- Rôle et statut -->
            <div class="bg-slate-700/30 rounded-lg p-6">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                    🛡️ <span class="ml-2">Rôle et Permissions</span>
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Rôle -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Rôle <span class="text-red-400">*</span>
                        </label>
                        <select name="role" required class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="">Sélectionner un rôle</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role', $user->roles->first()?->name) === $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Statut actif -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Statut</label>
                        <div class="flex items-center space-x-3 mt-3">
                            <input type="hidden" name="active" value="0">
                            <input type="checkbox" name="active" value="1" id="active" 
                                   {{ old('active', $user->active) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-500 bg-slate-700 border-slate-600 rounded focus:ring-blue-500">
                            <label for="active" class="text-slate-300">Membre actif</label>
                        </div>
                        @error('active')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Notes administratives -->
            <div class="bg-slate-700/30 rounded-lg p-6">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                    📝 <span class="ml-2">Notes administratives</span>
                </h3>
                
                <textarea name="notes" rows="4" 
                          class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                          placeholder="Notes ou commentaires administratifs...">{{ old('notes', $user->notes) }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-slate-700">
                <a href="{{ route('admin.users.show', $user) }}" 
                   class="px-6 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition-colors">
                    ← Retour au profil
                </a>
                
                <div class="flex space-x-4">
                    <a href="{{ route('admin.users.index') }}" 
                       class="px-6 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition-colors">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        💾 Enregistrer les modifications
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
