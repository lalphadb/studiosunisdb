<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administration') - StudiosUnisDB</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { 
            background-color: #0f172a !important; 
            color: #f1f5f9 !important; 
            min-height: 100vh;
        }
        .nav-bg { 
            background: linear-gradient(135deg, #1e293b, #0f172a); 
            border-bottom: 1px solid #334155;
        }
        .card-bg { 
            background-color: #1e293b !important; 
            border: 1px solid #334155;
        }
        .text-white { color: #f1f5f9 !important; }
        .text-slate-400 { color: #94a3b8 !important; }
        .hover-bg:hover { background-color: rgba(255,255,255,0.1); }
    </style>
</head>
<body>
    <!-- Navigation Compacte et Professionnelle -->
    <nav class="nav-bg text-white shadow-xl">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-14">
                <div class="flex items-center space-x-4">
                    <!-- Logo Compact -->
                    <div class="flex-shrink-0">
                        <h1 class="text-lg font-bold text-white">🥋 StudiosUnisDB</h1>
                        <p class="text-xs text-blue-300 leading-none">
                            @if(auth()->user()->hasRole('superadmin'))
                                SuperAdmin - Réseau Studios Unis
                            @elseif(auth()->user()->ecole)
                                {{ Str::limit(auth()->user()->ecole->nom, 25) }}
                            @else
                                École non assignée
                            @endif
                        </p>
                    </div>
                    
                    <!-- Navigation Compacte -->
                    <div class="hidden lg:flex lg:space-x-2">
                        <!-- Dashboard -->
                        <a href="{{ route('admin.dashboard') }}" 
                           class="px-2 py-1 text-xs rounded hover-bg transition-all {{ request()->routeIs('admin.dashboard*') ? 'bg-white bg-opacity-20' : 'text-gray-300' }}">
                            📊 Dashboard
                        </a>
                        
                        <!-- Écoles - SEULEMENT SuperAdmin -->
                        @if(auth()->user()->hasRole('superadmin'))
                        <a href="{{ route('admin.ecoles.index') }}" 
                           class="px-2 py-1 text-xs rounded hover-bg transition-all {{ request()->routeIs('admin.ecoles*') ? 'bg-white bg-opacity-20' : 'text-gray-300' }}">
                            🏢 Écoles
                        </a>
                        @endif
                        
                        <!-- Membres -->
                        @if(auth()->user()->hasAnyRole(['superadmin', 'admin', 'instructeur']))
                        <a href="{{ route('admin.membres.index') }}" 
                           class="px-2 py-1 text-xs rounded hover-bg transition-all {{ request()->routeIs('admin.membres*') ? 'bg-white bg-opacity-20' : 'text-gray-300' }}">
                            👥 Membres
                        </a>
                        @endif
                        
                        <!-- Cours -->
                        @if(auth()->user()->hasAnyRole(['superadmin', 'admin', 'instructeur']))
                        <a href="{{ route('admin.cours.index') }}" 
                           class="px-2 py-1 text-xs rounded hover-bg transition-all {{ request()->routeIs('admin.cours*') ? 'bg-white bg-opacity-20' : 'text-gray-300' }}">
                            📚 Cours
                        </a>
                        @endif
                        
                        <!-- Présences -->
                        @if(auth()->user()->hasAnyRole(['superadmin', 'admin', 'instructeur']))
                        <a href="{{ route('admin.presences.index') }}" 
                           class="px-2 py-1 text-xs rounded hover-bg transition-all {{ request()->routeIs('admin.presences*') ? 'bg-white bg-opacity-20' : 'text-gray-300' }}">
                            ✅ Présences
                        </a>
                        @endif

                        <!-- Ceintures -->
                        @if(auth()->user()->hasAnyRole(['superadmin', 'admin', 'instructeur']))
                        <a href="{{ route('admin.ceintures.index') }}" 
                           class="px-2 py-1 text-xs rounded hover-bg transition-all {{ request()->routeIs('admin.ceintures*') ? 'bg-white bg-opacity-20' : 'text-gray-300' }}">
                            🥋 Ceintures
                        </a>
                        @endif

                        <!-- Séminaires -->
                        @if(auth()->user()->hasAnyRole(['superadmin', 'admin', 'instructeur']))
                        <a href="{{ route('admin.seminaires.index') }}" 
                           class="px-2 py-1 text-xs rounded hover-bg transition-all {{ request()->routeIs('admin.seminaires*') ? 'bg-white bg-opacity-20' : 'text-gray-300' }}">
                            🎓 Séminaires
                        </a>
                        @endif
                        
                        <!-- Paiements -->
                        @if(auth()->user()->hasAnyRole(['superadmin', 'admin']))
                        <a href="{{ route('admin.paiements.index') }}" 
                           class="px-2 py-1 text-xs rounded hover-bg transition-all {{ request()->routeIs('admin.paiements*') ? 'bg-white bg-opacity-20' : 'text-gray-300' }}">
                            💳 Paiements
                        </a>
                        @endif
                        
                        <!-- Telescope - SEULEMENT SuperAdmin -->
                        @if(auth()->user()->hasRole('superadmin'))
                        <a href="{{ url('/telescope') }}" 
                           class="px-2 py-1 text-xs rounded hover-bg transition-all text-gray-300"
                           target="_blank"
                           title="Monitoring">
                            🔭 Telescope
                        </a>
                        @endif                 
                    </div>
                </div>
                
                <!-- Menu Utilisateur -->
                <div class="flex items-center">
                    <div class="relative">
                        <button onclick="toggleDropdown()" class="flex items-center space-x-2 hover-bg px-2 py-1 rounded text-xs transition-all">
                            <span class="text-white">
                                @if(auth()->user()->hasRole('superadmin'))
                                    🔥 {{ Str::limit(Auth::user()->name, 15) }}
                                @elseif(auth()->user()->hasRole('admin'))
                                    👑 {{ Str::limit(Auth::user()->name, 15) }}
                                @elseif(auth()->user()->hasRole('instructeur'))
                                    🥋 {{ Str::limit(Auth::user()->name, 15) }}
                                @else
                                    👤 {{ Str::limit(Auth::user()->name, 15) }}
                                @endif
                            </span>
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 card-bg rounded-md shadow-xl py-1 z-50">
                            <div class="px-3 py-2 text-xs text-slate-400 border-b border-slate-600">
                                {{ auth()->user()->roles->pluck('name')->join(', ') }}
                                @if(auth()->user()->ecole)
                                    <br>{{ auth()->user()->ecole->nom }}
                                @endif
                            </div>
                            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm text-white hover-bg">👤 Profil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-3 py-2 text-sm text-white hover-bg">
                                    🚪 Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Menu Mobile -->
        <div class="lg:hidden border-t border-slate-600">
            <div class="px-2 py-2 space-y-1">
                <!-- Version mobile des liens -->
                <a href="{{ route('admin.dashboard') }}" class="block px-2 py-1 text-xs text-white">📊 Dashboard</a>
                @if(auth()->user()->hasAnyRole(['superadmin', 'admin', 'instructeur']))
                    <a href="{{ route('admin.membres.index') }}" class="block px-2 py-1 text-xs text-white">👥 Membres</a>
                    <a href="{{ route('admin.cours.index') }}" class="block px-2 py-1 text-xs text-white">📚 Cours</a>
                    <a href="{{ route('admin.presences.index') }}" class="block px-2 py-1 text-xs text-white">✅ Présences</a>
                    <a href="{{ route('admin.ceintures.index') }}" class="block px-2 py-1 text-xs text-white">🥋 Ceintures</a>
                    <a href="{{ route('admin.seminaires.index') }}" class="block px-2 py-1 text-xs text-white">🎓 Séminaires</a>
                @endif
                @if(auth()->user()->hasAnyRole(['superadmin', 'admin']))
                    <a href="{{ route('admin.paiements.index') }}" class="block px-2 py-1 text-xs text-white">💳 Paiements</a>
                @endif
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <main class="max-w-full mx-auto py-4 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 px-4 py-3 rounded-md flex items-center" style="background-color: rgba(34, 197, 94, 0.2); border: 1px solid #16a34a; color: #4ade80;">
                <span class="mr-2">✅</span>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 px-4 py-3 rounded-md flex items-center" style="background-color: rgba(239, 68, 68, 0.2); border: 1px solid #dc2626; color: #f87171;">
                <span class="mr-2">❌</span>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        function toggleDropdown() {
            document.getElementById('userDropdown').classList.toggle('hidden');
        }
        
        window.onclick = function(event) {
            if (!event.target.closest('[onclick="toggleDropdown()"]')) {
                document.getElementById('userDropdown').classList.add('hidden');
            }
        }
    </script>
</body>
</html>
