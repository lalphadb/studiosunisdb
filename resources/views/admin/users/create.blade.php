<?php
// resources/views/admin/users/create.blade.php
?>
@extends('layouts.admin')

@section('title', 'Créer un Utilisateur')

@section('content')
<div class="space-y-6">
    <!-- Header moderne -->
    <div class="bg-gradient-to-r from-blue-500/20 via-blue-600/25 to-cyan-500/20 text-white p-8 rounded-2xl border border-blue-500/30 relative overflow-hidden backdrop-blur-sm">
        <!-- Effet brillance -->
        <div class="absolute inset-0 bg-gradient-to-br from-white/5 via-transparent to-white/3"></div>
        
        <div class="relative z-10 flex justify-between items-center">
            <div class="flex items-center space-x-6">
                <div class="w-16 h-16 bg-white/15 rounded-2xl flex items-center justify-center backdrop-blur border border-white/20">
                    <span class="text-3xl">👤</span>
                </div>
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-white drop-shadow-sm">Créer un Utilisateur</h1>
                    <p class="text-lg text-white/90 font-medium">Ajout d'un nouvel utilisateur au système</p>
                </div>
            </div>
            <div class="flex items-center">
                <a href="{{ route('admin.users.index') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 flex items-center space-x-3 backdrop-blur border border-white/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Retour à la liste</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Formulaire élégant -->
    <div class="bg-slate-800/50 backdrop-blur-xl rounded-2xl border border-slate-700/50 p-8">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-8">
            @csrf
            
            <!-- Section Informations personnelles -->
            <div class="space-y-6">
                <div class="flex items-center space-x-3 pb-4 border-b border-slate-700/50">
                    <div class="w-8 h-8 bg-blue-500/20 rounded-xl flex items-center justify-center">
                        <span class="text-lg">👤</span>
                    </div>
                    <h3 class="text-xl font-semibold text-white">Informations personnelles</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-300">Nom complet *</label>
                        <input type="text" name="name" required
                               class="w-full bg-slate-700/50 border border-slate-600/50 text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 backdrop-blur transition-all duration-300"
                               placeholder="Ex: Jean Dupont"
                               value="{{ old('name') }}">
                        @error('name')
                            <p class="text-red-400 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-300">Adresse email *</label>
                        <input type="email" name="email" required
                               class="w-full bg-slate-700/50 border border-slate-600/50 text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 backdrop-blur transition-all duration-300"
                               placeholder="Ex: jean.dupont@exemple.com"
                               value="{{ old('email') }}">
                        @error('email')
                            <p class="text-red-400 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-300">Téléphone</label>
                        <input type="tel" name="phone"
                               class="w-full bg-slate-700/50 border border-slate-600/50 text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 backdrop-blur transition-all duration-300"
                               placeholder="Ex: 418-555-0123"
                               value="{{ old('phone') }}">
                        @error('phone')
                            <p class="text-red-400 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-300">Date de naissance</label>
                        <input type="date" name="date_naissance"
                               class="w-full bg-slate-700/50 border border-slate-600/50 text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 backdrop-blur transition-all duration-300"
                               value="{{ old('date_naissance') }}">
                        @error('date_naissance')
                            <p class="text-red-400 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section École et permissions -->
            <div class="space-y-6">
                <div class="flex items-center space-x-3 pb-4 border-b border-slate-700/50">
                    <div class="w-8 h-8 bg-green-500/20 rounded-xl flex items-center justify-center">
                        <span class="text-lg">🏫</span>
                    </div>
                    <h3 class="text-xl font-semibold text-white">École et permissions</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-300">École associée</label>
                        <select name="ecole_id" 
                                class="w-full bg-slate-700/50 border border-slate-600/50 text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 backdrop-blur transition-all duration-300">
                            <option value="">Sélectionner une école</option>
                            @foreach(\App\Models\Ecole::all() as $ecole)
                                <option value="{{ $ecole->id }}" {{ old('ecole_id') == $ecole->id ? 'selected' : '' }}>
                                    {{ $ecole->nom }} ({{ $ecole->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('ecole_id')
                            <p class="text-red-400 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-300">Rôle</label>
                        <select name="role" 
                                class="w-full bg-slate-700/50 border border-slate-600/50 text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 backdrop-blur transition-all duration-300">
                            <option value="membre" {{ old('role') == 'membre' ? 'selected' : '' }}>Membre</option>
                            <option value="instructeur" {{ old('role') == 'instructeur' ? 'selected' : '' }}>Instructeur</option>
                            <option value="admin_ecole" {{ old('role') == 'admin_ecole' ? 'selected' : '' }}>Admin École</option>
                            @if(auth()->user()->hasRole('superadmin'))
                                <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                            @endif
                        </select>
                        @error('role')
                            <p class="text-red-400 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section Sécurité -->
            <div class="space-y-6">
                <div class="flex items-center space-x-3 pb-4 border-b border-slate-700/50">
                    <div class="w-8 h-8 bg-red-500/20 rounded-xl flex items-center justify-center">
                        <span class="text-lg">🔒</span>
                    </div>
                    <h3 class="text-xl font-semibold text-white">Sécurité</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-300">Mot de passe *</label>
                        <input type="password" name="password" required
                               class="w-full bg-slate-700/50 border border-slate-600/50 text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 backdrop-blur transition-all duration-300"
                               placeholder="Minimum 8 caractères">
                        @error('password')
                            <p class="text-red-400 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-300">Confirmer le mot de passe *</label>
                        <input type="password" name="password_confirmation" required
                               class="w-full bg-slate-700/50 border border-slate-600/50 text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 backdrop-blur transition-all duration-300"
                               placeholder="Répéter le mot de passe">
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-slate-700/50">
                <a href="{{ route('admin.users.index') }}" 
                   class="bg-slate-600/80 hover:bg-slate-600 text-white px-6 py-3 rounded-xl transition-all duration-300 font-medium">
                    Annuler
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl transition-all duration-300 font-semibold flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Créer l'utilisateur</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
