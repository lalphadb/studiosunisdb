@extends('layouts.admin')

@section('title', 'Créer un Utilisateur')

@section('content')
<div class="admin-content">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-6 text-white shadow-xl mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2 flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z"/>
                    </svg>
                    Créer un Nouvel Utilisateur
                </h1>
                <p class="text-blue-100">Ajouter un nouveau membre au système</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour à la liste
            </a>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="admin-card">
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-4">
            <h3 class="text-lg font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                </svg>
                Informations de l'Utilisateur
            </h3>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Informations de base -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-white mb-4">Informations de Base</h4>
                    
                    <div>
                        <label for="name" class="form-label">Nom complet *</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               class="form-input @error('name') border-red-500 @enderror" 
                               placeholder="Ex: Jean Dupont" 
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
                               value="{{ old('email') }}" 
                               class="form-input @error('email') border-red-500 @enderror" 
                               placeholder="Ex: jean.dupont@email.com" 
                               required>
                        @error('email')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="form-label">Mot de passe *</label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="form-input @error('password') border-red-500 @enderror" 
                               placeholder="Minimum 8 caractères" 
                               required>
                        @error('password')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="form-label">Confirmer mot de passe *</label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               class="form-input @error('password_confirmation') border-red-500 @enderror" 
                               placeholder="Répéter le mot de passe" 
                               required>
                        @error('password_confirmation')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="telephone" class="form-label">Téléphone</label>
                        <input type="tel" 
                               id="telephone" 
                               name="telephone" 
                               value="{{ old('telephone') }}" 
                               class="form-input @error('telephone') border-red-500 @enderror" 
                               placeholder="Ex: (514) 123-4567">
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
                                <option value="{{ $ecole->id }}" {{ old('ecole_id') == $ecole->id ? 'selected' : '' }}>
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
                                <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
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
                               value="{{ old('date_naissance') }}" 
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
                            <option value="M" {{ old('sexe') == 'M' ? 'selected' : '' }}>Masculin</option>
                            <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>Féminin</option>
                            <option value="Autre" {{ old('sexe') == 'Autre' ? 'selected' : '' }}>Autre</option>
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
                                   {{ old('active', true) ? 'checked' : '' }}
                                   class="rounded border-gray-600 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-700">
                            <span class="ml-2 text-gray-300">Utilisateur actif</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Adresse -->
            <div class="mt-8">
                <h4 class="text-lg font-semibold text-white mb-4">Adresse (Optionnel)</h4>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2">
                        <label for="adresse" class="form-label">Adresse</label>
                        <textarea id="adresse" 
                                  name="adresse" 
                                  rows="3" 
                                  class="form-textarea @error('adresse') border-red-500 @enderror" 
                                  placeholder="Ex: 123 Rue Principal">{{ old('adresse') }}</textarea>
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
                                   value="{{ old('ville') }}" 
                                   class="form-input @error('ville') border-red-500 @enderror" 
                                   placeholder="Ex: Montréal">
                            @error('ville')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="code_postal" class="form-label">Code postal</label>
                            <input type="text" 
                                   id="code_postal" 
                                   name="code_postal" 
                                   value="{{ old('code_postal') }}" 
                                   class="form-input @error('code_postal') border-red-500 @enderror" 
                                   placeholder="Ex: H3A 1A1">
                            @error('code_postal')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact d'urgence -->
            <div class="mt-8">
                <h4 class="text-lg font-semibold text-white mb-4">Contact d'Urgence (Optionnel)</h4>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="contact_urgence_nom" class="form-label">Nom du contact</label>
                        <input type="text" 
                               id="contact_urgence_nom" 
                               name="contact_urgence_nom" 
                               value="{{ old('contact_urgence_nom') }}" 
                               class="form-input @error('contact_urgence_nom') border-red-500 @enderror" 
                               placeholder="Ex: Marie Dupont">
                        @error('contact_urgence_nom')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="contact_urgence_telephone" class="form-label">Téléphone du contact</label>
                        <input type="tel" 
                               id="contact_urgence_telephone" 
                               name="contact_urgence_telephone" 
                               value="{{ old('contact_urgence_telephone') }}" 
                               class="form-input @error('contact_urgence_telephone') border-red-500 @enderror" 
                               placeholder="Ex: (514) 987-6543">
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
                          class="form-textarea @error('notes') border-red-500 @enderror" 
                          placeholder="Notes internes sur l'utilisateur...">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-600">
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                    Annuler
                </a>
                <button type="submit" class="btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Créer l'Utilisateur
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
