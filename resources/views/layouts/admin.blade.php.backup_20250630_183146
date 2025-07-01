<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - StudiosDB</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-white font-sans">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-800 border-r border-slate-700 flex flex-col">
            <!-- Logo -->
            <div class="p-4 border-b border-slate-700">
                <div class="flex items-center">
                    <div class="h-8 w-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-sm">S</span>
                    </div>
                    <div class="ml-3">
                        <h2 class="text-lg font-bold text-white">StudiosDB</h2>
                        <p class="text-slate-400 text-xs">Administration</p>
                    </div>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-4 py-2 text-white hover:bg-blue-600 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600' : '' }}">
                    <span class="mr-3 text-lg">📊</span>
                    <span>Dashboard</span>
                </a>

                @can('viewAny', App\Models\User::class)
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center px-4 py-2 text-white hover:bg-blue-600 rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-blue-600' : '' }}">
                        <span class="mr-3 text-lg">👤</span>
                        <span>Utilisateurs</span>
                    </a>
                @endcan
                
                @can('viewAny', App\Models\Ecole::class)
                    <a href="{{ route('admin.ecoles.index') }}" 
                       class="flex items-center px-4 py-2 text-white hover:bg-green-600 rounded-lg transition-colors {{ request()->routeIs('admin.ecoles.*') ? 'bg-green-600' : '' }}">
                        <span class="mr-3 text-lg">🏫</span>
                        <span>Écoles</span>
                    </a>
                @endcan
                
                @can('viewAny', App\Models\Cours::class)
                    <a href="{{ route('admin.cours.index') }}" 
                       class="flex items-center px-4 py-2 text-white hover:bg-purple-600 rounded-lg transition-colors {{ request()->routeIs('admin.cours.*') ? 'bg-purple-600' : '' }}">
                        <span class="mr-3 text-lg">📚</span>
                        <span>Cours</span>
                    </a>
                @endcan
                
                @can('viewAny', App\Models\Seminaire::class)
                    <a href="{{ route('admin.seminaires.index') }}" 
                       class="flex items-center px-4 py-2 text-white hover:bg-pink-600 rounded-lg transition-colors {{ request()->routeIs('admin.seminaires.*') ? 'bg-pink-600' : '' }}">
                        <span class="mr-3 text-lg">🎯</span>
                        <span>Séminaires</span>
                    </a>
                @endcan
                
                @can('viewAny', App\Models\Ceinture::class)
                    <a href="{{ route('admin.ceintures.index') }}" 
                       class="flex items-center px-4 py-2 text-white hover:bg-orange-600 rounded-lg transition-colors {{ request()->routeIs('admin.ceintures.*') ? 'bg-orange-600' : '' }}">
                        <span class="mr-3 text-lg">🥋</span>
                        <span>Ceintures</span>
                    </a>
                @endcan
                
                @can('viewAny', App\Models\Paiement::class)
                    <a href="{{ route('admin.paiements.index') }}" 
                       class="flex items-center px-4 py-2 text-white hover:bg-yellow-600 rounded-lg transition-colors {{ request()->routeIs('admin.paiements.*') ? 'bg-yellow-600' : '' }}">
                        <span class="mr-3 text-lg">💰</span>
                        <span>Paiements</span>
                    </a>
                @endcan
                
                @can('viewAny', App\Models\Presence::class)
                    <a href="{{ route('admin.presences.index') }}" 
                       class="flex items-center px-4 py-2 text-white hover:bg-teal-600 rounded-lg transition-colors {{ request()->routeIs('admin.presences.*') ? 'bg-teal-600' : '' }}">
                        <span class="mr-3 text-lg">✅</span>
                        <span>Présences</span>
                    </a>
                @endcan

                <!-- Telescope pour SuperAdmin -->
                @if(auth()->user()->hasRole('superadmin'))
                    <div class="border-t border-slate-700 mt-4 pt-4">
                        <a href="/telescope" target="_blank"
                           class="flex items-center px-4 py-2 text-white hover:bg-red-600 rounded-lg transition-colors">
                            <span class="mr-3 text-lg">🔭</span>
                            <span>Telescope</span>
                            <span class="ml-auto text-xs bg-red-500 px-2 py-1 rounded">DEV</span>
                        </a>
                    </div>
                @endif
            </nav>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-slate-800 border-b border-slate-700 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-semibold text-white">@yield('title', 'Administration')</h1>
                        <p class="text-slate-400 text-sm">
                            {{ auth()->user()->name }} - 
                            @foreach(auth()->user()->roles as $role)
                                <span class="text-blue-400">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</span>
                            @endforeach
                        </p>
                    </div>
                    
                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="flex items-center space-x-3 bg-slate-700 hover:bg-slate-600 px-4 py-2 rounded-lg transition-colors">
                            <div class="h-8 w-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <div class="text-left">
                                <div class="text-white text-sm font-medium">{{ auth()->user()->name }}</div>
                                <div class="text-slate-400 text-xs">{{ auth()->user()->email }}</div>
                            </div>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <!-- Dropdown Menu -->
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
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Profil
                                </a>
                                
                                @if(auth()->user()->hasRole('membre'))
                                    <a href="{{ route('dashboard') }}" 
                                       class="flex items-center px-4 py-2 text-slate-300 hover:bg-slate-600 hover:text-white transition-colors">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                        Dashboard Membre
                                    </a>
                                @endif
                                
                                <div class="border-t border-slate-600 my-2"></div>
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="flex items-center w-full px-4 py-2 text-slate-300 hover:bg-red-600 hover:text-white transition-colors">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Main Content Area -->
            <main class="flex-1 p-6 overflow-auto">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="mb-6 bg-green-900 border border-green-700 text-green-100 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if (session('error'))
                    <div class="mb-6 bg-red-900 border border-red-700 text-red-100 px-4 py-3 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Alpine.js pour les dropdowns -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
