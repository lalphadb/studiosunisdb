#!/bin/bash

echo "🔧 FIX LAYOUT FINAL - ALPINE.JS + DROPDOWN"

# 1. Nettoyer les contrôleurs cassés
cd /home/studiosdb/studiosunisdb/app/Http/Controllers/Admin/

# Supprimer les fichiers corrompus
rm -f *broken* *.backup *.save

# 2. S'assurer qu'Alpine.js est dans app.js
cat > /home/studiosdb/studiosunisdb/resources/js/app.js << 'JS_EOF'
import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();
JS_EOF

# 3. Layout admin DÉFINITIF avec Alpine.js intégré
cat > /home/studiosdb/studiosunisdb/resources/views/layouts/admin.blade.php << 'LAYOUT_EOF'
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administration') - StudiosUnisDB v3.9.3</title>
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-900 text-gray-100">
    <div class="admin-layout">
        {{-- SIDEBAR --}}
        <aside class="admin-sidebar">
            {{-- Logo --}}
            <div class="sidebar-logo">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <span class="text-xl font-bold text-white">🥋</span>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-white">StudiosUnisDB</h1>
                        <p class="text-xs text-blue-300">v3.9.3-FINAL</p>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="sidebar-nav">
                {{-- Dashboard --}}
                <div class="nav-group">
                    <div class="nav-group-title">Principal</div>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="nav-item {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                        <span class="nav-item-icon">📊</span>
                        Dashboard
                    </a>
                </div>

                {{-- Gestion --}}
                <div class="nav-group">
                    <div class="nav-group-title">Gestion</div>
                    
                    <a href="{{ route('admin.ecoles.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.ecoles*') ? 'active' : '' }}">
                        <span class="nav-item-icon">🏯</span>
                        Écoles
                    </a>

                    <a href="{{ route('admin.membres.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.membres*') ? 'active' : '' }}">
                        <span class="nav-item-icon">👥</span>
                        Membres
                    </a>

                    <a href="{{ route('admin.cours.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.cours*') ? 'active' : '' }}">
                        <span class="nav-item-icon">📚</span>
                        Cours
                    </a>

                    <a href="{{ route('admin.presences.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.presences*') ? 'active' : '' }}">
                        <span class="nav-item-icon">✅</span>
                        Présences
                    </a>
                </div>

                {{-- Progression --}}
                <div class="nav-group">
                    <div class="nav-group-title">Progression</div>
                    
                    <a href="{{ route('admin.ceintures.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.ceintures*') ? 'active' : '' }}">
                        <span class="nav-item-icon">🥋</span>
                        Ceintures
                    </a>

                    <a href="{{ route('admin.seminaires.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.seminaires*') ? 'active' : '' }}">
                        <span class="nav-item-icon">🎓</span>
                        Séminaires
                    </a>
                </div>

                {{-- Finance --}}
                <div class="nav-group">
                    <div class="nav-group-title">Finance</div>
                    
                    <a href="{{ route('admin.paiements.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.paiements*') ? 'active' : '' }}">
                        <span class="nav-item-icon">💳</span>
                        Paiements
                    </a>
                </div>
            </nav>
        </aside>

        {{-- HEADER --}}
        <header class="admin-header">
            <div class="flex items-center space-x-4">
                <h2 class="text-xl font-semibold text-white">
                    @yield('page-title', 'Dashboard')
                </h2>
                @if(auth()->user()->ecole)
                    <span class="text-sm text-blue-300">{{ auth()->user()->ecole->nom }}</span>
                @endif
            </div>

            {{-- User dropdown FONCTIONNEL --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-700 hover:bg-opacity-50 transition-all focus:outline-none">
                    {{-- Icône selon rôle --}}
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white"
                         style="background: linear-gradient(135deg, 
                         @if(auth()->user()->hasRole('superadmin')) #fbbf24, #f59e0b @elseif(auth()->user()->hasRole('admin')) #3b82f6, #1d4ed8 @elseif(auth()->user()->hasRole('instructeur')) #f59e0b, #d97706 @else #6b7280, #4b5563 @endif);">
                        @if(auth()->user()->hasRole('superadmin'))
                            ⭐
                        @elseif(auth()->user()->hasRole('admin'))
                            👑
                        @elseif(auth()->user()->hasRole('instructeur'))
                            🥋
                        @else
                            👤
                        @endif
                    </div>
                    
                    <div class="text-left">
                        <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-400">{{ auth()->user()->roles->pluck('name')->map('ucfirst')->join(', ') }}</p>
                    </div>
                    
                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" 
                         :class="{ 'rotate-180': open }" 
                         fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>

                {{-- DROPDOWN MENU --}}
                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-3 w-64 bg-gray-800 bg-opacity-95 backdrop-blur-sm rounded-xl shadow-2xl border border-gray-700 py-2"
                     style="z-index: 9999;">
                    
                    <div class="px-4 py-3 border-b border-gray-600">
                        <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400">{{ auth()->user()->email }}</p>
                        @foreach(auth()->user()->roles as $role)
                            <span class="inline-block mt-1 px-2 py-1 rounded-full text-xs bg-blue-600 text-white mr-1">
                                {{ ucfirst($role->name) }}
                            </span>
                        @endforeach
                        @if(auth()->user()->ecole)
                            <p class="text-xs text-blue-300 mt-2">🏯 {{ auth()->user()->ecole->nom }}</p>
                        @endif
                    </div>
                    
                    <div class="py-2">
                        <a href="{{ route('profile.edit') }}" 
                           class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                            <span class="mr-3">👤</span>
                            Profil utilisateur
                        </a>
                        
                        @if(auth()->user()->hasRole('superadmin'))
                        <a href="{{ url('/telescope') }}" target="_blank" 
                           class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                            <span class="mr-3">🔭</span>
                            Telescope
                        </a>
                        @endif
                        
                        <div class="border-t border-gray-600 my-2"></div>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="flex items-center w-full px-4 py-2 text-sm text-red-300 hover:bg-red-900 hover:text-red-100 transition-colors">
                                <span class="mr-3">🚪</span>
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- MAIN CONTENT --}}
        <main class="admin-main">
            {{-- Messages flash --}}
            @if(session('success'))
                <div class="mb-6 card-glass p-6 flex items-center space-x-3 border-green-500 bg-green-600 bg-opacity-10">
                    <span class="text-2xl">✅</span>
                    <div>
                        <p class="font-medium text-green-100">Succès</p>
                        <p class="text-green-200">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 card-glass p-6 flex items-center space-x-3 border-red-500 bg-red-600 bg-opacity-10">
                    <span class="text-2xl">❌</span>
                    <div>
                        <p class="font-medium text-red-100">Erreur</p>
                        <p class="text-red-200">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            {{-- Page content --}}
            @yield('content')
        </main>
    </div>
</body>
</html>
LAYOUT_EOF

# 4. Nettoyer et recompiler
npm run build
php artisan optimize:clear

echo "✅ Layout finalisé avec Alpine.js fonctionnel !"

