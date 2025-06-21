#!/bin/bash

echo "🎯 CORRECTION CSS STANDARDS LARAVEL 12.19"

# 1. Créer le fichier CSS admin séparé
cat > /home/studiosdb/studiosunisdb/resources/css/admin.css << 'CSS_EOF'
/* StudiosUnisDB Admin CSS - Laravel 12.19 Standards */

:root {
    --primary: #0f172a;
    --secondary: #1e293b; 
    --accent: #3b82f6;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
}

body { 
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
    color: #f1f5f9;
    min-height: 100vh;
    background-attachment: fixed;
}

/* LAYOUT FIXE */
.admin-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    grid-template-rows: auto 1fr;
    grid-template-areas: 
        "sidebar header"
        "sidebar main";
    height: 100vh;
}

.admin-header {
    grid-area: header;
    background: rgba(30, 41, 59, 0.95);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(59, 130, 246, 0.3);
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.admin-sidebar {
    grid-area: sidebar;
    background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
    border-right: 1px solid rgba(59, 130, 246, 0.3);
    padding: 2rem 0;
    overflow-y: auto;
}

.admin-main {
    grid-area: main;
    padding: 2rem;
    overflow-y: auto;
}

/* SIDEBAR STYLES */
.sidebar-logo {
    padding: 0 2rem 2rem;
    border-bottom: 1px solid rgba(75, 85, 99, 0.3);
    margin-bottom: 2rem;
}

.sidebar-nav {
    padding: 0 1rem;
}

.nav-group {
    margin-bottom: 2rem;
}

.nav-group-title {
    color: #94a3b8;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 0 1rem 0.5rem;
}

.nav-item {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    margin: 0.25rem 0;
    border-radius: 0.75rem;
    color: #cbd5e1;
    text-decoration: none;
    transition: all 0.2s ease;
    position: relative;
}

.nav-item:hover {
    background: rgba(59, 130, 246, 0.1);
    color: #e2e8f0;
    transform: translateX(4px);
}

.nav-item.active {
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    color: #ffffff;
    font-weight: 600;
    box-shadow: 0 4px 20px rgba(59, 130, 246, 0.3);
}

.nav-item-icon {
    width: 1.25rem;
    height: 1.25rem;
    margin-right: 0.75rem;
    font-size: 1.25rem;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .admin-layout {
        grid-template-columns: 1fr;
        grid-template-areas: 
            "header"
            "main";
    }
    .admin-sidebar {
        display: none;
    }
}

/* CARDS */
.card-glass {
    background: rgba(30, 41, 59, 0.8);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(75, 85, 99, 0.3);
    border-radius: 1rem;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

/* BUTTONS */
.btn-primary {
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s ease;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 20px rgba(59, 130, 246, 0.4);
}
CSS_EOF

# 2. Modifier vite.config.js pour inclure admin.css
cat > /home/studiosdb/studiosunisdb/vite.config.js << 'VITE_EOF'
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/admin.css',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
});
VITE_EOF

# 3. Créer layout admin PROPRE (sans CSS)
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
                        <span class="nav-item-icon">🏢</span>
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

            {{-- User dropdown --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center space-x-2 p-2 rounded-xl hover:bg-gray-700 hover:bg-opacity-50">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <span class="text-sm font-bold text-white">
                            @if(auth()->user()->hasRole('superadmin'))🔥
                            @elseif(auth()->user()->hasRole('admin'))👑
                            @elseif(auth()->user()->hasRole('instructeur'))🥋
                            @else👤@endif
                        </span>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-400">{{ auth()->user()->roles->pluck('name')->map('ucfirst')->join(', ') }}</p>
                    </div>
                </button>

                <div x-show="open" @click.away="open = false" 
                     class="absolute right-0 mt-2 w-64 bg-gray-800 bg-opacity-95 backdrop-blur-20 rounded-xl shadow-2xl border border-gray-700 py-2 z-50">
                    <div class="px-4 py-3 border-b border-gray-700">
                        <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="py-2">
                        <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700">
                            <span class="mr-3">👤</span>
                            Profil utilisateur
                        </a>
                        @if(auth()->user()->hasRole('superadmin'))
                        <a href="{{ url('/telescope') }}" target="_blank" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700">
                            <span class="mr-3">🔭</span>
                            Telescope
                        </a>
                        @endif
                        <div class="border-t border-gray-700 my-2"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-300 hover:bg-red-900">
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

    {{-- Alpine.js --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
LAYOUT_EOF

echo "✅ CSS séparé créé selon standards Laravel 12.19"
echo "🔧 Compilation assets..."

