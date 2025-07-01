<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - StudiosDB v4.1.10.2</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-900 text-white font-sans antialiased">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-800 border-r border-slate-700 flex flex-col">
            <!-- Logo -->
            <div class="p-4 border-b border-slate-700">
                <div class="flex items-center">
                    <div class="h-8 w-8 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-sm">S</span>
                    </div>
                    <div class="ml-3">
                        <h2 class="text-lg font-bold text-white">StudiosDB</h2>
                        <p class="text-slate-400 text-xs">v4.1.10.2</p>
                    </div>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-6 overflow-y-auto">
                
                {{-- 📊 MODULES PRINCIPAUX --}}
                <div>
                    <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Vue d'ensemble</h3>
                    <div class="space-y-1">
                        {{-- Dashboard adaptatif selon le rôle --}}
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center px-4 py-3 text-white hover:bg-blue-500/20 rounded-lg transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-500/30 border-l-4 border-blue-500' : '' }}">
                            <x-admin-icon name="dashboard" size="w-5 h-5" color="text-blue-400" />
                            <span class="ml-3 font-medium">Dashboard</span>
                        </a>
                    </div>
                </div>

                {{-- 🎓 MODULES MÉTIER --}}
                <div>
                    <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Formation</h3>
                    <div class="space-y-1">
                        <a href="{{ route('admin.cours.index') }}" 
                           class="flex items-center px-4 py-3 text-white hover:bg-violet-500/20 rounded-lg transition-all duration-300 {{ request()->routeIs('admin.cours.*') ? 'bg-violet-500/30 border-l-4 border-violet-500' : '' }}">
                            <x-admin-icon name="cours" size="w-5 h-5" color="text-violet-400" />
                            <span class="ml-3 font-medium">Cours</span>
                        </a>
                        
                        <a href="{{ route('admin.sessions.index') }}" 
                           class="flex items-center px-4 py-2.5 text-white hover:bg-violet-500/20 rounded-lg transition-all duration-300 {{ request()->routeIs('admin.sessions.*') ? 'bg-violet-500/30 border-l-4 border-violet-500' : '' }} ml-2">
                            <x-admin-icon name="cours" size="w-4 h-4" color="text-violet-300" />
                            <span class="ml-3 font-medium text-sm">Sessions</span>
                        </a>
                        
                        <a href="{{ route('admin.seminaires.index') }}" 
                           class="flex items-center px-4 py-3 text-white hover:bg-rose-500/20 rounded-lg transition-all duration-300 {{ request()->routeIs('admin.seminaires.*') ? 'bg-rose-500/30 border-l-4 border-rose-500' : '' }}">
                            <x-admin-icon name="seminaires" size="w-5 h-5" color="text-rose-400" />
                            <span class="ml-3 font-medium">Séminaires</span>
                        </a>
                        
                        <a href="{{ route('admin.ceintures.index') }}" 
                           class="flex items-center px-4 py-3 text-white hover:bg-orange-500/20 rounded-lg transition-all duration-300 {{ request()->routeIs('admin.ceintures.*') ? 'bg-orange-500/30 border-l-4 border-orange-500' : '' }}">
                            <x-admin-icon name="ceintures" size="w-5 h-5" color="text-orange-400" />
                            <span class="ml-3 font-medium">Ceintures</span>
                        </a>
                    </div>
                </div>

                {{-- 👥 MODULES GESTION --}}
                <div>
                    <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Gestion quotidienne</h3>
                    <div class="space-y-1">
                        <a href="{{ route('admin.users.index') }}" 
                           class="flex items-center px-4 py-3 text-white hover:bg-blue-500/20 rounded-lg transition-all duration-300 {{ request()->routeIs('admin.users.*') ? 'bg-blue-500/30 border-l-4 border-blue-500' : '' }}">
                            <x-admin-icon name="users" size="w-5 h-5" color="text-blue-400" />
                            <span class="ml-3 font-medium">Utilisateurs</span>
                        </a>
                        
                        <a href="{{ route('admin.presences.index') }}" 
                           class="flex items-center px-4 py-3 text-white hover:bg-cyan-500/20 rounded-lg transition-all duration-300 {{ request()->routeIs('admin.presences.*') ? 'bg-cyan-500/30 border-l-4 border-cyan-500' : '' }}">
                            <x-admin-icon name="presences" size="w-5 h-5" color="text-cyan-400" />
                            <span class="ml-3 font-medium">Présences</span>
                        </a>
                        
                        <a href="{{ route('admin.paiements.index') }}" 
                           class="flex items-center px-4 py-3 text-white hover:bg-amber-500/20 rounded-lg transition-all duration-300 {{ request()->routeIs('admin.paiements.*') ? 'bg-amber-500/30 border-l-4 border-amber-500' : '' }}">
                            <x-admin-icon name="paiements" size="w-5 h-5" color="text-amber-400" />
                            <span class="ml-3 font-medium">Paiements</span>
                        </a>
                    </div>
                </div>

                {{-- ⚙️ MODULES ADMIN --}}
                <div>
                    <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Administration</h3>
                    <div class="space-y-1">
                        <a href="{{ route('admin.ecoles.index') }}" 
                           class="flex items-center px-4 py-3 text-white hover:bg-emerald-500/20 rounded-lg transition-all duration-300 {{ request()->routeIs('admin.ecoles.*') ? 'bg-emerald-500/30 border-l-4 border-emerald-500' : '' }}">
                            <x-admin-icon name="ecoles" size="w-5 h-5" color="text-emerald-400" />
                            <span class="ml-3 font-medium">Écoles</span>
                        </a>
                    </div>
                </div>
            </nav>
            
            <!-- Footer sidebar -->
            <div class="p-4 border-t border-slate-700">
                <x-dark-mode-toggle />
                <div class="text-xs text-slate-500 text-center mt-3">
                    StudiosDB v4.1.10.2
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-slate-800 border-b border-slate-700 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-semibold text-white">@yield('title', 'Administration')</h1>
                        @auth
                            <p class="text-slate-400 text-sm">
                                {{ auth()->user()->name }} - 
                                @foreach(auth()->user()->roles as $role)
                                    <span class="text-blue-400">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</span>
                                @endforeach
                            </p>
                        @endauth
                    </div>
                    
                    <!-- User Dropdown -->
                    @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="flex items-center space-x-3 bg-slate-700/50 hover:bg-slate-600/50 px-4 py-2 rounded-lg transition-colors border border-slate-600/30">
                            <div class="h-8 w-8 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <div class="text-left">
                                <div class="text-white text-sm font-medium">{{ auth()->user()->name }}</div>
                                <div class="text-slate-400 text-xs">{{ auth()->user()->email }}</div>
                            </div>
                            <x-admin-icon name="chevron-down" size="w-4 h-4" color="text-slate-400" />
                        </button>
                        
                        <div x-show="open" @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-slate-700 rounded-lg shadow-lg border border-slate-600 z-50">
                            <div class="py-2">
                                <a href="{{ route('profile.edit') }}" 
                                   class="flex items-center px-4 py-2 text-slate-300 hover:bg-slate-600 hover:text-white transition-colors">
                                    <x-admin-icon name="settings" size="w-4 h-4" />
                                    <span class="ml-3">Profil</span>
                                </a>
                                
                                <div class="border-t border-slate-600 my-2"></div>
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="flex items-center w-full px-4 py-2 text-slate-300 hover:bg-red-600 hover:text-white transition-colors">
                                        <x-admin-icon name="logout" size="w-4 h-4" />
                                        <span class="ml-3">Déconnexion</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endauth
                </div>
            </header>
            
            <!-- Main Content Area -->
            <main class="flex-1 p-6 overflow-auto bg-slate-900">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 px-4 py-3 rounded-lg studiosdb-fade-in">
                        <div class="flex items-center">
                            <x-admin-icon name="presences" size="w-5 h-5" color="text-emerald-400" />
                            <span class="ml-3">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif
                
                @if (session('error'))
                    <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-300 px-4 py-3 rounded-lg studiosdb-fade-in">
                        <div class="flex items-center">
                            <x-admin-icon name="close" size="w-5 h-5" color="text-red-400" />
                            <span class="ml-3">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>
