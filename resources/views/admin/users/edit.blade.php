@extends('layouts.admin')

@section('title', 'Modifier ' . $user->name)

@section('content')
<div class="admin-content">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-6 text-white shadow-xl mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2 flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                    </svg>
                    Modifier {{ $user->name }}
                </h1>
                <p class="text-blue-100">Mettre à jour les informations utilisateur</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.show', $user) }}" class="btn-secondary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Voir détails
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour à la liste
                </a>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="admin-card">
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-4">
            <h3 class="text-lg font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                </svg>
                Modifier les Informations
            </h3>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Informations de base -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-white mb-4">Informations de Base</h4>
                    
                    <div>
                        <label for="name" class="form-label">Nom complet *</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $user->name) }}" 
                               class="form-input @error('name') border-red-500 @enderror" 
                               required>
                        @error('name')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               class="form-input @error('email') border-red-500 @enderror" 
                               required>
                        @error('email')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- MOT DE PASSE OPTIONNEL -->
                    <div class="bg-gray-700 border border-gray-600 rounded-lg p-4">
                        <h5 class="text-white font-medium mb-3">Changer le mot de passe (optionnel)</h5>
                        
                        <div class="space-y-3">
                            <div>
                                <label for="password" class="form-label">Nouveau mot de passe</label>
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       class="form-input @error('password') border-red-500 @enderror" 
                                       placeholder="Laisser vide pour garder l'actuel">
                                @error('password')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="form-label">Confirmer nouveau mot de passe</label>
                                <input type="password" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       class="form-input @error('password_confirmation') border-red-500 @enderror" 
                                       placeholder="Confirmer si changement">
                                @error('password_confirmation')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <p class="text-gray-400 text-sm mt-2">💡 Laisser vide pour conserver le mot de passe actuel</p>
                    </div>

                    <div>
                        <label for="telephone" class="form-label">Téléphone</label>
                        <input type="tel" 
                               id="telephone" 
                               name="telephone" 
                               value="{{ old('telephone', $user->telephone) }}" 
                               class="form-input @error('telephone') border-red-500 @enderror">
                        @error('telephone')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- École et rôle -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-white mb-4">École et Rôle</h4>
                    
                    <div>
                        <label for="ecole_id" class="form-label">École *</label>
                        <select id="ecole_id" 
                                name="ecole_id" 
                                class="form-select @error('ecole_id') border-red-500 @enderror" 
                                required>
                            <option value="">Sélectionner une école</option>
                            @foreach($ecoles as $ecole)
                                <option value="{{ $ecole->id }}" {{ old('ecole_id', $user->ecole_id) == $ecole->id ? 'selected' : '' }}>
                                    [{{ $ecole->code }}] {{ $ecole->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('ecole_id')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="form-label">Rôle *</label>
                        <select id="role" 
                                name="role" 
                                class="form-select @error('role') border-red-500 @enderror" 
                                required>
                            <option value="">Sélectionner un rôle</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role', $user->roles->first()?->name) == $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date_naissance" class="form-label">Date de naissance</label>
                        <input type="date" 
                               id="date_naissance" 
                               name="date_naissance" 
                               value="{{ old('date_naissance', $user->date_naissance?->format('Y-m-d')) }}" 
                               class="form-input @error('date_naissance') border-red-500 @enderror">
                        @error('date_naissance')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sexe" class="form-label">Sexe</label>
                        <select id="sexe" 
                                name="sexe" 
                                class="form-select @error('sexe') border-red-500 @enderror">
                            <option value="">Sélectionner</option>
                            <option value="M" {{ old('sexe', $user->sexe) == 'M' ? 'selected' : '' }}>Masculin</option>
                            <option value="F" {{ old('sexe', $user->sexe) == 'F' ? 'selected' : '' }}>Féminin</option>
                            <option value="Autre" {{ old('sexe', $user->sexe) == 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('sexe')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="active" 
                                   value="1" 
                                   {{ old('active', $user->active) ? 'checked' : '' }}
                                   class="rounded border-gray-600 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-700">
                            <span class="ml-2 text-gray-300">Utilisateur actif</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Adresse -->
            <div class="mt-8">
                <h4 class="text-lg font-semibold text-white mb-4">Adresse</h4>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2">
                        <label for="adresse" class="form-label">Adresse</label>
                        <textarea id="adresse" 
                                  name="adresse" 
                                  rows="3" 
                                  class="form-textarea @error('adresse') border-red-500 @enderror">{{ old('adresse', $user->adresse) }}</textarea>
                        @error('adresse')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label for="ville" class="form-label">Ville</label>
                            <input type="text" 
                                   id="ville" 
                                   name="ville" 
                                   value="{{ old('ville', $user->ville) }}" 
                                   class="form-input @error('ville') border-red-500 @enderror">
                            @error('ville')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="code_postal" class="form-label">Code postal</label>
                            <input type="text" 
                                   id="code_postal" 
                                   name="code_postal" 
                                   value="{{ old('code_postal', $user->code_postal) }}" 
                                   class="form-input @error('code_postal') border-red-500 @enderror">
                            @error('code_postal')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- CONTACT D'URGENCE ENCADRÉ ROUGE -->
            <div class="mt-8 border-2 border-red-500 rounded-lg bg-red-900 bg-opacity-10 p-4">
                <h4 class="text-lg font-semibold text-red-300 mb-4 flex items-center">
                    🚨 Contact d'Urgence
                </h4>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="contact_urgence_nom" class="form-label text-red-300">Nom du contact</label>
                        <input type="text" 
                               id="contact_urgence_nom" 
                               name="contact_urgence_nom" 
                               value="{{ old('contact_urgence_nom', $user->contact_urgence_nom) }}" 
                               class="form-input border-red-400 @error('contact_urgence_nom') border-red-500 @enderror">
                        @error('contact_urgence_nom')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="contact_urgence_telephone" class="form-label text-red-300">Téléphone du contact</label>
                        <input type="tel" 
                               id="contact_urgence_telephone" 
                               name="contact_urgence_telephone" 
                               value="{{ old('contact_urgence_telephone', $user->contact_urgence_telephone) }}" 
                               class="form-input border-red-400 @error('contact_urgence_telephone') border-red-500 @enderror">
                        @error('contact_urgence_telephone')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="mt-8">
                <label for="notes" class="form-label">Notes administratives</label>
                <textarea id="notes" 
                          name="notes" 
                          rows="4" 
                          class="form-textarea @error('notes') border-red-500 @enderror">{{ old('notes', $user->notes) }}</textarea>
                @error('notes')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-600">
                <a href="{{ route('admin.users.show', $user) }}" class="btn-secondary">
                    Annuler
                </a>
                <button type="submit" class="btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
