@extends('layouts.admin')

@section('title', 'Créer une École')

@section('content')
<div class="admin-content">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-xl p-6 text-white shadow-xl mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2 flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.84L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.84l-7-3z"/>
                    </svg>
                    Créer une Nouvelle École
                </h1>
                <p class="text-green-100">Ajouter un nouveau Studio Unis au réseau</p>
            </div>
            <a href="{{ route('admin.ecoles.index') }}" class="btn-secondary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour à la liste
            </a>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="admin-card">
        <div class="bg-gradient-to-r from-green-600 to-teal-600 p-4">
            <h3 class="text-lg font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                </svg>
                Informations de l'École
            </h3>
        </div>

        <form method="POST" action="{{ route('admin.ecoles.store') }}" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Informations de base -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-white mb-4">Informations de Base</h4>
                    
                    <div>
                        <label for="nom" class="form-label">Nom de l'École *</label>
                        <input type="text" 
                               id="nom" 
                               name="nom" 
                               value="{{ old('nom') }}" 
                               class="form-input @error('nom') border-red-500 @enderror" 
                               placeholder="Ex: Studios Unis Montréal Centre" 
                               required>
                        @error('nom')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="code" class="form-label">Code École *</label>
                        <input type="text" 
                               id="code" 
                               name="code" 
                               value="{{ old('code') }}" 
                               class="form-input @error('code') border-red-500 @enderror" 
                               placeholder="Ex: MTL, QBC, TR" 
                               maxlength="10"
                               style="text-transform: uppercase;"
                               required>
                        @error('code')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-400 text-sm mt-1">Code unique de 2-10 caractères (sera converti en majuscules)</p>
                    </div>

                    <div>
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="4" 
                                  class="form-textarea @error('description') border-red-500 @enderror" 
                                  placeholder="Description de l'école, spécialités, histoire...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="active" 
                                   value="1" 
                                   {{ old('active', true) ? 'checked' : '' }}
                                   class="rounded border-gray-600 text-green-600 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50 bg-gray-700">
                            <span class="ml-2 text-gray-300">École active</span>
                        </label>
                    </div>
                </div>

                <!-- Localisation -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-white mb-4">Localisation</h4>
                    
                    <div>
                        <label for="adresse" class="form-label">Adresse *</label>
                        <input type="text" 
                               id="adresse" 
                               name="adresse" 
                               value="{{ old('adresse') }}" 
                               class="form-input @error('adresse') border-red-500 @enderror" 
                               placeholder="Ex: 123 Rue Principal" 
                               required>
                        @error('adresse')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="ville" class="form-label">Ville *</label>
                            <input type="text" 
                                   id="ville" 
                                   name="ville" 
                                   value="{{ old('ville') }}" 
                                   class="form-input @error('ville') border-red-500 @enderror" 
                                   placeholder="Ex: Montréal" 
                                   required>
                            @error('ville')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="province" class="form-label">Province</label>
                            <select id="province" 
                                    name="province" 
                                    class="form-select @error('province') border-red-500 @enderror">
                                <option value="QC" {{ old('province', 'QC') === 'QC' ? 'selected' : '' }}>Québec</option>
                                <option value="ON" {{ old('province') === 'ON' ? 'selected' : '' }}>Ontario</option>
                                <option value="BC" {{ old('province') === 'BC' ? 'selected' : '' }}>Colombie-Britannique</option>
                                <option value="AB" {{ old('province') === 'AB' ? 'selected' : '' }}>Alberta</option>
                                <option value="MB" {{ old('province') === 'MB' ? 'selected' : '' }}>Manitoba</option>
                                <option value="SK" {{ old('province') === 'SK' ? 'selected' : '' }}>Saskatchewan</option>
                                <option value="NS" {{ old('province') === 'NS' ? 'selected' : '' }}>Nouvelle-Écosse</option>
                                <option value="NB" {{ old('province') === 'NB' ? 'selected' : '' }}>Nouveau-Brunswick</option>
                                <option value="NL" {{ old('province') === 'NL' ? 'selected' : '' }}>Terre-Neuve-et-Labrador</option>
                                <option value="PE" {{ old('province') === 'PE' ? 'selected' : '' }}>Île-du-Prince-Édouard</option>
                            </select>
                            @error('province')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="code_postal" class="form-label">Code Postal *</label>
                        <input type="text" 
                               id="code_postal" 
                               name="code_postal" 
                               value="{{ old('code_postal') }}" 
                               class="form-input @error('code_postal') border-red-500 @enderror" 
                               placeholder="Ex: H3A 1A1" 
                               required>
                        @error('code_postal')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Contact -->
            <div class="mt-8">
                <h4 class="text-lg font-semibold text-white mb-4">Informations de Contact</h4>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
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

                    <div>
                        <label for="email" class="form-label">Email</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               class="form-input @error('email') border-red-500 @enderror" 
                               placeholder="Ex: info@studiosunismtl.ca">
                        @error('email')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="site_web" class="form-label">Site Web</label>
                        <input type="url" 
                               id="site_web" 
                               name="site_web" 
                               value="{{ old('site_web') }}" 
                               class="form-input @error('site_web') border-red-500 @enderror" 
                               placeholder="Ex: https://studiosunismtl.ca">
                        @error('site_web')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-600">
                <a href="{{ route('admin.ecoles.index') }}" class="btn-secondary">
                    Annuler
                </a>
                <button type="submit" class="btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Créer l'École
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-uppercase pour le code
document.getElementById('code').addEventListener('input', function(e) {
    e.target.value = e.target.value.toUpperCase();
});
</script>
@endsection
