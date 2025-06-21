@extends('layouts.admin')

@section('title', 'Mon Profil')

@section('content')
<div class="space-y-6">
    {{-- Header moderne avec gradient --}}
    <div class="relative bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 rounded-lg p-6 text-white overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white opacity-5 rounded-full -ml-16 -mb-16"></div>
        
        <div class="relative flex items-center justify-between">
            <div class="flex items-center space-x-6">
                {{-- Avatar avec initiales --}}
                <div class="w-20 h-20 bg-white bg-opacity-20 rounded-lg flex items-center justify-center text-2xl font-bold backdrop-blur-sm border border-white border-opacity-30">
                    {{ strtoupper(substr($user->name ?: 'U', 0, 1)) }}{{ strtoupper(substr($user->email, 0, 1)) }}
                </div>
                
                <div>
                    <h1 class="text-3xl font-bold mb-2">👤 Mon Profil</h1>
                    <p class="text-blue-100 text-lg">{{ $user->name ?: 'Utilisateur' }} • {{ $user->email }}</p>
                    <div class="flex items-center space-x-4 mt-2">
                        @if($user->hasRole('superadmin'))
                            <span class="bg-purple-500 bg-opacity-50 px-3 py-1 rounded-full text-sm font-medium">
                                👑 Super Administrateur
                            </span>
                        @elseif($user->hasRole('admin'))
                            <span class="bg-green-500 bg-opacity-50 px-3 py-1 rounded-full text-sm font-medium">
                                🛡️ Admin École
                            </span>
                        @elseif($user->hasRole('instructeur'))
                            <span class="bg-orange-500 bg-opacity-50 px-3 py-1 rounded-full text-sm font-medium">
                                🎯 Instructeur
                            </span>
                        @else
                            <span class="bg-gray-500 bg-opacity-50 px-3 py-1 rounded-full text-sm font-medium">
                                🥋 Membre
                            </span>
                        @endif
                        
                        @if($user->ecole)
                            <span class="bg-blue-500 bg-opacity-50 px-3 py-1 rounded-full text-sm font-medium">
                                🏫 {{ $user->ecole->nom }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- Actions Header --}}
            <div class="flex space-x-3">
                <a href="{{ route('admin.dashboard') }}" 
                   class="bg-white text-purple-600 hover:bg-gray-50 px-6 py-3 rounded-lg font-medium transition-all duration-300">
                    ← Retour Dashboard
                </a>
            </div>
        </div>
    </div>

    {{-- Informations du compte --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- Section Informations Profil --}}
        <div class="bg-gray-800 rounded-lg shadow-2xl border border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 p-4">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                    </svg>
                    Informations du Profil
                </h3>
            </div>
            
            <div class="p-6">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Section Sécurité --}}
        <div class="bg-gray-800 rounded-lg shadow-2xl border border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-emerald-500 p-4">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                    </svg>
                    Sécurité du Compte
                </h3>
            </div>
            
            <div class="p-6">
                @include('profile.partials.update-password-form')
            </div>
        </div>
    </div>

    {{-- Informations système --}}
    <div class="bg-gray-800 rounded-lg shadow-2xl border border-gray-700 overflow-hidden">
        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-4">
            <h3 class="text-xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" clip-rule="evenodd"></path>
                </svg>
                Informations Système
            </h3>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gray-900 rounded-lg p-4">
                    <h4 class="font-medium text-white mb-2">🆔 Identifiant</h4>
                    <p class="text-gray-300">ID: {{ $user->id }}</p>
                    <p class="text-gray-400 text-sm">Créé le {{ $user->created_at->format('d/m/Y') }}</p>
                </div>
                
                <div class="bg-gray-900 rounded-lg p-4">
                    <h4 class="font-medium text-white mb-2">🔐 Permissions</h4>
                    <p class="text-gray-300">{{ $user->roles->pluck('name')->join(', ') }}</p>
                    <p class="text-gray-400 text-sm">{{ $user->getAllPermissions()->count() }} permissions</p>
                </div>
                
                <div class="bg-gray-900 rounded-lg p-4">
                    <h4 class="font-medium text-white mb-2">🏫 École Assignée</h4>
                    @if($user->ecole)
                        <p class="text-gray-300">{{ $user->ecole->nom }}</p>
                        <p class="text-gray-400 text-sm">{{ $user->ecole->ville }}</p>
                    @else
                        <p class="text-gray-300">Accès Global</p>
                        <p class="text-gray-400 text-sm">Toutes les écoles</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Zone Dangereuse (si applicable) --}}
    @if($user->hasRole('superadmin'))
    <div class="bg-red-900 bg-opacity-20 border border-red-500 rounded-lg overflow-hidden">
        <div class="bg-gradient-to-r from-red-600 to-red-700 p-4">
            <h3 class="text-xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                ⚠️ Zone Super Administrateur
            </h3>
        </div>
        
        <div class="p-6">
            <div class="bg-red-800 bg-opacity-30 rounded-lg p-4">
                <h4 class="text-red-200 font-bold mb-2">🔧 Actions Avancées</h4>
                <p class="text-red-300 text-sm mb-4">
                    En tant que Super Administrateur, vous avez accès à toutes les fonctionnalités système.
                </p>
                <div class="flex space-x-4">
                    @if(config('telescope.enabled'))
                    <a href="/telescope" target="_blank" 
                       class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        🔭 Telescope
                    </a>
                    @endif
                    <a href="{{ route('admin.logs.index') }}" 
                       class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        📋 Logs Système
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Section Suppression Compte --}}
    <div class="bg-gray-800 rounded-lg shadow-2xl border border-gray-700 overflow-hidden">
        <div class="bg-gradient-to-r from-red-600 to-red-700 p-4">
            <h3 class="text-xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 012 0v4a1 1 0 11-2 0V7zM8 13a1 1 0 112 0 1 1 0 01-2 0z" clip-rule="evenodd"></path>
                </svg>
                Suppression du Compte
            </h3>
        </div>
        
        <div class="p-6">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection
