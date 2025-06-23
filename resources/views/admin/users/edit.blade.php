@extends('layouts.admin')

@section('title', 'Modifier ' . $user->name)

@section('content')
<div class="admin-content">
    {{-- Header uniforme --}}
    <div class="bg-gradient-to-r from-yellow-600 to-orange-600 rounded-xl p-6 text-white mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">✏️ Modifier Membre</h1>
                <p class="text-yellow-100">{{ $user->name }} - {{ $user->email }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.show', $user) }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg">
                    👁️ Voir profil
                </a>
                <a href="{{ route('admin.users.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg">
                    ← Retour à la liste
                </a>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="bg-gray-800 rounded-xl border border-gray-700">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-6">
                <!-- Colonne Gauche -->
                <div class="space-y-6">
                    <h4 class="text-lg font-medium text-white border-b border-gray-700 pb-2">👤 Informations Personnelles</h4>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                            Nom complet <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                        @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                            Email <span class="text-red-400">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                        @error('email') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="telephone" class="block text-sm font-medium text-gray-300 mb-2">Téléphone</label>
                        <input type="tel" id="telephone" name="telephone" value="{{ old('telephone', $user->telephone) }}"
                               class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                        @error('telephone') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="date_naissance" class="block text-sm font-medium text-gray-300 mb-2">Date de naissance</label>
                        <input type="date" id="date_naissance" name="date_naissance" 
                               value="{{ old('date_naissance', $user->date_naissance ? $user->date_naissance->format('Y-m-d') : '') }}"
                               class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                        @error('date_naissance') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="sexe" class="block text-sm font-medium text-gray-300 mb-2">Sexe</label>
                        <select id="sexe" name="sexe"
                                class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                            <option value="">Non spécifié</option>
                            <option value="M" {{ old('sexe', $user->sexe) == 'M' ? 'selected' : '' }}>Masculin</option>
                            <option value="F" {{ old('sexe', $user->sexe) == 'F' ? 'selected' : '' }}>Féminin</option>
                            <option value="Autre" {{ old('sexe', $user->sexe) == 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>

                    <div>
                        <label for="adresse" class="block text-sm font-medium text-gray-300 mb-2">Adresse</label>
                        <textarea id="adresse" name="adresse" rows="2"
                                  class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">{{ old('adresse', $user->adresse) }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="ville" class="block text-sm font-medium text-gray-300 mb-2">Ville</label>
                            <input type="text" id="ville" name="ville" value="{{ old('ville', $user->ville) }}"
                                   class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="code_postal" class="block text-sm font-medium text-gray-300 mb-2">Code postal</label>
                            <input type="text" id="code_postal" name="code_postal" value="{{ old('code_postal', $user->code_postal) }}"
                                   class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="border-t border-gray-600 pt-6">
                        <h4 class="text-lg font-medium text-white mb-4">🚨 Contact d'urgence</h4>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="contact_urgence_nom" class="block text-sm font-medium text-gray-300 mb-2">
                                    Nom du contact d'urgence
                                </label>
                                <input type="text" id="contact_urgence_nom" name="contact_urgence_nom" 
                                       value="{{ old('contact_urgence_nom', $user->contact_urgence_nom) }}"
                                       class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="contact_urgence_telephone" class="block text-sm font-medium text-gray-300 mb-2">
                                    Téléphone d'urgence
                                </label>
                                <input type="tel" id="contact_urgence_telephone" name="contact_urgence_telephone" 
                                       value="{{ old('contact_urgence_telephone', $user->contact_urgence_telephone) }}"
                                       class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Colonne Droite -->
                <div class="space-y-6">
                    <h4 class="text-lg font-medium text-white border-b border-gray-700 pb-2">🏫 Informations Système</h4>
                    
                    <div>
                        <label for="ecole_id" class="block text-sm font-medium text-gray-300 mb-2">
                            École <span class="text-red-400">*</span>
                        </label>
                        <select id="ecole_id" name="ecole_id" required
                                class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                            <option value="">Sélectionner une école</option>
                            @foreach($ecoles ?? [] as $ecole)
                                <option value="{{ $ecole->id }}" {{ old('ecole_id', $user->ecole_id) == $ecole->id ? 'selected' : '' }}>
                                    {{ $ecole->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('ecole_id') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-300 mb-2">
                            Rôle <span class="text-red-400">*</span>
                        </label>
                        <select id="role" name="role" required
                                class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                            <option value="">Sélectionner un rôle</option>
                            @foreach($availableRoles ?? [] as $roleValue => $roleLabel)
                                @php
                                    $currentRole = old('role', $user->roles->first()->name ?? '');
                                @endphp
                                <option value="{{ $roleValue }}" {{ $currentRole == $roleValue ? 'selected' : '' }}>
                                    {{ $roleLabel }}
                                </option>
                            @endforeach
                        </select>
                        @error('role') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="active" value="1" {{ old('active', $user->active) ? 'checked' : '' }}
                                   class="rounded border-gray-600 text-blue-600 focus:ring-blue-500 bg-gray-900">
                            <span class="ml-2 text-gray-300">Utilisateur actif</span>
                        </label>
                    </div>

                    <div>
                        <label for="date_inscription" class="block text-sm font-medium text-gray-300 mb-2">Date d'inscription</label>
                        <input type="date" id="date_inscription" name="date_inscription" 
                               value="{{ old('date_inscription', $user->date_inscription ? $user->date_inscription->format('Y-m-d') : '') }}"
                               class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Section mot de passe optionnelle -->
                    <div class="border-t border-gray-600 pt-6">
                        <h4 class="text-gray-300 font-medium mb-4">🔒 Modifier le mot de passe (optionnel)</h4>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                                    Nouveau mot de passe
                                </label>
                                <input type="password" id="password" name="password"
                                       class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500"
                                       placeholder="Laisser vide pour conserver l'actuel">
                                @error('password') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">
                                    Confirmer nouveau mot de passe
                                </label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500"
                                       placeholder="Confirmer le nouveau mot de passe">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-300 mb-2">Notes</label>
                        <textarea id="notes" name="notes" rows="4"
                                  class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">{{ old('notes', $user->notes) }}</textarea>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-600">
                <a href="{{ route('admin.users.show', $user) }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition-colors">
                    ❌ Annuler
                </a>
                <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-lg transition-colors">
                    ✅ Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
