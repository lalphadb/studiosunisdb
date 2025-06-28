@extends('layouts.admin')

@section('title', 'Créer un Utilisateur')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">👤 Créer un Nouvel Utilisateur</h1>
                <p class="text-blue-100">Ajouter un nouveau membre au système</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg">
                ← Retour à la liste
            </a>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="bg-slate-800 rounded-xl border border-slate-700">
        <form method="POST" action="{{ route('admin.users.store') }}" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-6">
                <!-- Colonne Gauche -->
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-300 mb-2">
                            Nom complet <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded-md text-white focus:ring-2 focus:ring-blue-500"
                               placeholder="Ex: Jean Dupont">
                        @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-300 mb-2">
                            Email <span class="text-red-400">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded-md text-white focus:ring-2 focus:ring-blue-500"
                               placeholder="jean.dupont@email.com">
                        @error('email') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="telephone" class="block text-sm font-medium text-slate-300 mb-2">Téléphone</label>
                        <input type="tel" id="telephone" name="telephone" value="{{ old('telephone') }}"
                               class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded-md text-white focus:ring-2 focus:ring-blue-500"
                               placeholder="(514) 123-4567">
                        @error('telephone') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-300 mb-2">
                            Mot de passe <span class="text-red-400">*</span>
                        </label>
                        <input type="password" id="password" name="password" required
                               class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded-md text-white focus:ring-2 focus:ring-blue-500"
                               placeholder="Minimum 8 caractères">
                        @error('password') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-2">
                            Confirmer mot de passe <span class="text-red-400">*</span>
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded-md text-white focus:ring-2 focus:ring-blue-500"
                               placeholder="Répéter le mot de passe">
                    </div>
                </div>

                <!-- Colonne Droite -->
                <div class="space-y-6">
                    <div>
                        <label for="ecole_id" class="block text-sm font-medium text-slate-300 mb-2">
                            École <span class="text-red-400">*</span>
                        </label>
                        <select id="ecole_id" name="ecole_id" required
                                class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                            <option value="">Sélectionner une école</option>
                            @foreach($ecoles as $ecole)
                                <option value="{{ $ecole->id }}" {{ old('ecole_id') == $ecole->id ? 'selected' : '' }}>
                                    {{ $ecole->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('ecole_id') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-slate-300 mb-2">
                            Rôle <span class="text-red-400">*</span>
                        </label>
                        <select id="role" name="role" required
                                class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                            <option value="">Sélectionner un rôle</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="date_naissance" class="block text-sm font-medium text-slate-300 mb-2">Date de naissance</label>
                        <input type="date" id="date_naissance" name="date_naissance" value="{{ old('date_naissance') }}"
                               class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                        @error('date_naissance') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="sexe" class="block text-sm font-medium text-slate-300 mb-2">Sexe</label>
                        <select id="sexe" name="sexe"
                                class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                            <option value="">Non spécifié</option>
                            <option value="M" {{ old('sexe') == 'M' ? 'selected' : '' }}>Masculin</option>
                            <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>Féminin</option>
                            <option value="Autre" {{ old('sexe') == 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="active" value="1" {{ old('active', true) ? 'checked' : '' }}
                                   class="rounded border-slate-600 text-blue-600 focus:ring-blue-500 bg-slate-900">
                            <span class="ml-2 text-slate-300">Utilisateur actif</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex justify-between items-center mt-8 pt-6 border-t border-slate-600">
                <a href="{{ route('admin.users.index') }}" class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg">
                    ❌ Annuler
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    ✅ Créer l'Utilisateur
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
