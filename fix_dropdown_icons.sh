#!/bin/bash

echo "🔧 CORRECTION DROPDOWN + ICÔNES MODERNES"

# Ajouter le CSS manquant pour le dropdown et les nouvelles icônes
cat >> /home/studiosdb/studiosunisdb/resources/css/admin.css << 'CSS_ADD'

/* FIX DROPDOWN Z-INDEX */
.user-dropdown {
    position: relative;
    z-index: 9999 !important;
}

.user-dropdown-menu {
    position: absolute;
    right: 0;
    top: 100%;
    margin-top: 0.5rem;
    width: 16rem;
    background: rgba(31, 41, 55, 0.98);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(75, 85, 99, 0.6);
    border-radius: 1rem;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
    padding: 0.5rem 0;
    z-index: 10000 !important;
    transform: translateY(-10px);
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.user-dropdown-menu.show {
    transform: translateY(0);
    opacity: 1;
    visibility: visible;
}

.user-dropdown-menu::before {
    content: '';
    position: absolute;
    top: -6px;
    right: 20px;
    width: 12px;
    height: 12px;
    background: rgba(31, 41, 55, 0.98);
    border: 1px solid rgba(75, 85, 99, 0.6);
    border-bottom: none;
    border-right: none;
    transform: rotate(45deg);
}

/* ICÔNES RÔLES MODERNES */
.role-icon {
    width: 2rem;
    height: 2rem;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.role-icon::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(45deg, rgba(255,255,255,0.1), transparent);
    border-radius: inherit;
    z-index: 1;
}

.role-icon-superadmin {
    background: linear-gradient(135deg, #fbbf24, #f59e0b, #d97706);
    box-shadow: 0 4px 20px rgba(251, 191, 36, 0.4);
}

.role-icon-admin {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8, #1e40af);
    box-shadow: 0 4px 20px rgba(59, 130, 246, 0.4);
}

.role-icon-instructeur {
    background: linear-gradient(135deg, #f59e0b, #d97706, #b45309);
    box-shadow: 0 4px 20px rgba(245, 158, 11, 0.4);
}

.role-icon-membre {
    background: linear-gradient(135deg, #6b7280, #4b5563, #374151);
    box-shadow: 0 4px 20px rgba(107, 114, 128, 0.4);
}

.role-icon svg {
    width: 1.25rem;
    height: 1.25rem;
    fill: currentColor;
    position: relative;
    z-index: 2;
}

/* ANIMATION GLOW */
@keyframes glow {
    0%, 100% { box-shadow: 0 4px 20px rgba(251, 191, 36, 0.4); }
    50% { box-shadow: 0 6px 30px rgba(251, 191, 36, 0.6); }
}

.role-icon-superadmin {
    animation: glow 2s ease-in-out infinite;
}

CSS_ADD

# Créer le layout corrigé avec les nouvelles icônes
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

<body>
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
                        <p class="text-xs text-blue-300">v3.9.3-DEV-FINAL</p>
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
                    
                    @can('view-ecoles')
                    <a href="{{ route('admin.ecoles.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.ecoles*') ? 'active' : '' }}">
                        <span class="nav-item-icon">🏯</span>
                        Écoles
                    </a>
                    @endcan

                    @can('view-membres')
                    <a href="{{ route('admin.membres.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.membres*') ? 'active' : '' }}">
                        <span class="nav-item-icon">👥</span>
                        Membres
                    </a>
                    @endcan

                    @can('view-cours')
                    <a href="{{ route('admin.cours.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.cours*') ? 'active' : '' }}">
                        <span class="nav-item-icon">📚</span>
                        Cours
                    </a>
                    @endcan

                    @can('view-presences')
                    <a href="{{ route('admin.presences.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.presences*') ? 'active' : '' }}">
                        <span class="nav-item-icon">✅</span>
                        Présences
                    </a>
                    @endcan
                </div>

                {{-- Progression --}}
                <div class="nav-group">
                    <div class="nav-group-title">Progression</div>
                    
                    @can('view-ceintures')
                    <a href="{{ route('admin.ceintures.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.ceintures*') ? 'active' : '' }}">
                        <span class="nav-item-icon">🥋</span>
                        Ceintures
                    </a>
                    @endcan

                    @can('view-seminaires')
                    <a href="{{ route('admin.seminaires.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.seminaires*') ? 'active' : '' }}">
                        <span class="nav-item-icon">🎓</span>
                        Séminaires
                    </a>
                    @endcan
                </div>

                {{-- Finance --}}
                <div class="nav-group">
                    <div class="nav-group-title">Finance</div>
                    
                    @can('view-paiements')
                    <a href="{{ route('admin.paiements.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.paiements*') ? 'active' : '' }}">
                        <span class="nav-item-icon">💳</span>
                        Paiements
                    </a>
                    @endcan
                </div>

                {{-- Actions rapides --}}
                <div class="nav-group">
                    <div class="nav-group-title">Actions rapides</div>
                    
                    @can('create-membre')
                    <a href="{{ route('admin.membres.create') }}" class="nav-item">
                        <span class="nav-item-icon">➕</span>
                        Nouveau membre
                    </a>
                    @endcan

                    @can('create-presence')
                    <a href="{{ route('admin.presences.create') }}" class="nav-item">
                        <span class="nav-item-icon">📱</span>
                        Scanner QR
                    </a>
                    @endcan
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

            {{-- User dropdown CORRIGÉ --}}
            <div class="user-dropdown" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center space-x-3 p-2 rounded-xl hover:bg-gray-700 hover:bg-opacity-50 transition-all">
                    {{-- NOUVELLES ICÔNES RÔLES --}}
                    @if(auth()->user()->hasRole('superadmin'))
                        <div class="role-icon role-icon-superadmin">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                    @elseif(auth()->user()->hasRole('admin'))
                        <div class="role-icon role-icon-admin">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M5 16L3 14l5.5-5.5L10 10l4-4 6 6v7H5v-3zm2.5-2.5L9 12l4-4 6 6v5H7v-1.5l.5-1.5z"/>
                            </svg>
                        </div>
                    @elseif(auth()->user()->hasRole('instructeur'))
                        <div class="role-icon role-icon-instructeur">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 5.5V6.5L17.85 7.15L12 12L6.15 7.15L9 6.5V5.5L3 7V9L12 14L21 9Z"/>
                            </svg>
                        </div>
                    @else
                        <div class="role-icon role-icon-membre">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>
                    @endif
                    
                    <div class="text-left">
                        <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-400">{{ auth()->user()->roles->pluck('name')->map('ucfirst')->join(', ') }}</p>
                    </div>
                    
                    <svg class="w-4 h-4 text-gray-400 transition-transform" :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>

                {{-- DROPDOWN MENU CORRIGÉ --}}
                <div class="user-dropdown-menu" :class="{ 'show': open }" @click.away="open = false">
                    <div class="px-4 py-3 border-b border-gray-600">
                        <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400">{{ auth()->user()->email }}</p>
                        <div class="flex items-center space-x-2 mt-2">
                            @foreach(auth()->user()->roles as $role)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-600 text-white">
                                    {{ ucfirst($role->name) }}
                                </span>
                            @endforeach
                        </div>
                        @if(auth()->user()->ecole)
                            <p class="text-xs text-blue-300 mt-1">🏯 {{ auth()->user()->ecole->nom }}</p>
                        @endif
                    </div>
                    
                    <div class="py-2">
                        <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                            <span class="mr-3">👤</span>
                            Profil utilisateur
                        </a>
                        
                        @if(auth()->user()->hasRole('superadmin'))
                        <a href="{{ url('/telescope') }}" target="_blank" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                            <span class="mr-3">🔭</span>
                            Telescope (Debug)
                        </a>
                        @endif
                        
                        <div class="border-t border-gray-600 my-2"></div>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-300 hover:bg-red-900 hover:text-red-100 transition-colors">
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

            @if($errors->any())
                <div class="mb-6 card-glass p-6 border-red-500 bg-red-600 bg-opacity-10">
                    <div class="flex items-center space-x-3 mb-3">
                        <span class="text-2xl">⚠️</span>
                        <p class="font-medium text-red-100">Erreurs de validation</p>
                    </div>
                    <ul class="list-disc list-inside text-red-200">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Page content --}}
            @yield('content')
        </main>
    </div>

    {{-- Alpine.js --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
LAYOUT_EOF

echo "✅ Dropdown et icônes modernes corrigés !"

