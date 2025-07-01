<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - StudiosDB</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-900 text-white font-sans">
    <div class="min-h-screen flex">
        <!-- Sidebar avec nouvelles couleurs -->
        <aside class="w-64 bg-slate-800 border-r border-slate-700 flex flex-col">
            <!-- Logo avec couleurs améliorées -->
            <div class="p-4 border-b border-slate-700">
                <div class="flex items-center">
                    <div class="h-8 w-8 bg-gradient-to-r from-admin-blue to-admin-violet rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-sm">S</span>
                    </div>
                    <div class="ml-3">
                        <h2 class="text-lg font-bold text-white">StudiosDB</h2>
                        <p class="text-slate-400 text-xs">Administration</p>
                    </div>
                </div>
            </div>
            
            <!-- Navigation avec icônes SVG -->
            <nav class="flex-1 p-4 space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-4 py-2 text-white hover:bg-admin-blue/20 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-admin-blue/30 border-admin-blue border' : '' }}">
                    <x-admin-icon name="dashboard" size="w-5 h-5" color="text-admin-blue" />
                    <span class="ml-3">Dashboard</span>
                </a>

                @can('viewAny', App\Models\User::class)
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center px-4 py-2 text-white hover:bg-admin-blue/20 rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-admin-blue/30 border-admin-blue border' : '' }}">
                        <x-admin-icon name="users" size="w-5 h-5" color="text-admin-blue" />
                        <span class="ml-3">Utilisateurs</span>
                    </a>
                @endcan
                
                @can('viewAny', App\Models\Ecole::class)
                    <a href="{{ route('admin.ecoles.index') }}" 
                       class="flex items-center px-4 py-2 text-white hover:bg-admin-emerald/20 rounded-lg transition-colors {{ request()->routeIs('admin.ecoles.*') ? 'bg-admin-emerald/30 border-admin-emerald border' : '' }}">
                        <x-admin-icon name="ecoles" size="w-5 h-5" color="text-admin-emerald" />
                        <span class="ml-3">Écoles</span>
                    </a>
                @endcan
                
                @can('viewAny', App\Models\Cours::class)
                    <a href="{{ route('admin.cours.index') }}" 
                       class="flex items-center px-4 py-2 text-white hover:bg-admin-violet/20 rounded-lg transition-colors {{ request()->routeIs('admin.cours.*') ? 'bg-admin-violet/30 border-admin-violet border' : '' }}">
                        <x-admin-icon name="cours" size="w-5 h-5" color="text-admin-violet" />
                        <span class="ml-3">Cours</span>
                    </a>
                @endcan
                
                @can('viewAny', App\Models\Seminaire::class)
                    <a href="{{ route('admin.seminaires.index') }}" 
                       class="flex items-center px-4 py-2 text-white hover:bg-admin-rose/20 rounded-lg transition-colors {{ request()->routeIs('admin.seminaires.*') ? 'bg-admin-rose/30 border-admin-rose border' : '' }}">
                        <x-admin-icon name="seminaires" size="w-5 h-5" color="text-admin-rose" />
                        <span class="ml-3">Séminaires</span>
                    </a>
                @endcan
                
                @can('viewAny', App\Models\Ceinture::class)
                    <a href="{{ route('admin.ceintures.index') }}" 
                       class="flex items-center px-4 py-2 text-white hover:bg-admin-orange/20 rounded-lg transition-colors {{ request()->routeIs('admin.ceintures.*') ? 'bg-admin-orange/30 border-admin-orange border' : '' }}">
                        <x-admin-icon name="ceintures" size="w-5 h-5" color="text-admin-orange" />
                        <span class="ml-3">Ceintures</span>
                    </a>
                @endcan
                
                @can('viewAny', App\Models\Paiement::class)
                    <a href="{{ route('admin.paiements.index') }}" 
                       class="flex items-center px-4 py-2 text-white hover:bg-admin-amber/20 rounded-lg transition-colors {{ request()->routeIs('admin.paiements.*') ? 'bg-admin-amber/30 border-admin-amber border' : '' }}">
                        <x-admin-icon name="paiements" size="w-5 h-5" color="text-admin-amber" />
                        <span class="ml-3">Paiements</span>
                    </a>
                @endcan
                
                @can('viewAny', App\Models\Presence::class)
                    <a href="{{ route('admin.presences.index') }}" 
                       class="flex items-center px-4 py-2 text-white hover:bg-admin-cyan/20 rounded-lg transition-colors {{ request()->routeIs('admin.presences.*') ? 'bg-admin-cyan/30 border-admin-cyan border' : '' }}">
                        <x-admin-icon name="presences" size="w-5 h-5" color="text-admin-cyan" />
                        <span class="ml-3">Présences</span>
                    </a>
                @endcan
            </nav>
            
            <!-- Version avec Dark Mode Toggle -->
            <div class="p-4 border-t border-slate-700 space-y-3">
                <x-dark-mode-toggle />
                <div class="text-xs text-slate-500 text-center">
                    StudiosDB v4.1.10.2
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header amélioré -->
            <header class="bg-slate-800 border-b border-slate-700 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-semibold text-white">@yield('title', 'Administration')</h1>
                        <p class="text-slate-400 text-sm">
                            {{ auth()->user()->name }} - 
                            @foreach(auth()->user()->roles as $role)
                                <span class="text-admin-blue">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</span>
                            @endforeach
                        </p>
                    </div>
                    
                    <!-- User Dropdown avec nouvelles couleurs -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="flex items-center space-x-3 bg-slate-700/50 hover:bg-slate-600/50 px-4 py-2 rounded-lg transition-colors border border-slate-600/30">
                            <div class="h-8 w-8 bg-gradient-to-r from-admin-blue to-admin-violet rounded-full flex items-center justify-center">
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
                        
                        <!-- Menu dropdown identique au original mais avec nouvelles couleurs -->
                        <div x-show="open" @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-slate-700 rounded-lg shadow-lg border border-slate-600 z-50">
                            <!-- Contenu identique à l'original -->
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
                    <div class="mb-6 bg-admin-emerald/10 border border-admin-emerald/20 text-emerald-300 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if (session('error'))
                    <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-300 px-4 py-3 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
