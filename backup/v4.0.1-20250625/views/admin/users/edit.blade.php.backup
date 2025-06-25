@extends('layouts.admin')

@section('title', 'Modifier ' . $user->name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-yellow-600 to-orange-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">✏️ Modifier Utilisateur</h1>
                <p class="text-yellow-100">{{ $user->name }} - {{ $user->email }}</p>
            </div>
            <a href="{{ route('admin.users.show', $user) }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg">
                ← Retour au profil
            </a>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="bg-slate-800 rounded-xl border border-slate-700">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-6">
                <!-- Colonne Gauche -->
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-300 mb-2">
                            Nom complet <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                        @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-300 mb-2">
                            Email <span class="text-red-400">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                        @error('email') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="telephone" class="block text-sm font-medium text-slate-300 mb-2">Téléphone</label>
                        <input type="tel" id="telephone" name="telephone" value="{{ old('telephone', $user->telephone) }}"
                               class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                        @error('telephone') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-300 mb-2">
                            Nouveau mot de passe <span class="text-slate-500">(laisser vide pour conserver)</span>
                        </label>
                        <input type="password" id="password" name="password"
                               class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                        @error('password') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-2">
                            Confirmer nouveau mot de passe
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
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
                                <option value="{{ $ecole->id }}" {{ old('ecole_id', $user->ecole_id) == $ecole->id ? 'selected' : '' }}>
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
                                <option value="{{ $role->name }}" {{ old('role', $user->roles->first()->name ?? '') == $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="date_naissance" class="block text-sm font-medium text-slate-300 mb-2">Date de naissance</label>
                        <input type="date" id="date_naissance" name="date_naissance" value="{{ old('date_naissance', $user->date_naissance) }}"
                               class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                        @error('date_naissance') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="sexe" class="block text-sm font-medium text-slate-300 mb-2">Sexe</label>
                        <select id="sexe" name="sexe"
                                class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                            <option value="">Non spécifié</option>
                            <option value="M" {{ old('sexe', $user->sexe) == 'M' ? 'selected' : '' }}>Masculin</option>
                            <option value="F" {{ old('sexe', $user->sexe) == 'F' ? 'selected' : '' }}>Féminin</option>
                            <option value="Autre" {{ old('sexe', $user->sexe) == 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="active" value="1" {{ old('active', $user->active) ? 'checked' : '' }}
                                   class="rounded border-slate-600 text-blue-600 focus:ring-blue-500 bg-slate-900">
                            <span class="ml-2 text-slate-300">Utilisateur actif</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex justify-between items-center mt-8 pt-6 border-t border-slate-600">
                <a href="{{ route('admin.users.show', $user) }}" class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg">
                    ❌ Annuler
                </a>
                <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg">
                    ✅ Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
