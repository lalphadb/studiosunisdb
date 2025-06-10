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
    <!-- Navigation Dark -->
    <nav class="nav-bg text-white shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-8">
                    <div class="flex-shrink-0">
                        <h1 class="text-xl font-bold text-white">🥋 StudiosUnisDB</h1>
                        <p class="text-xs text-blue-300">Réseau Studios Unis</p>
                    </div>
                    <div class="hidden md:flex md:space-x-4">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="px-3 py-2 rounded-md hover-bg transition-all {{ request()->routeIs('admin.dashboard*') ? 'bg-white bg-opacity-20' : 'text-gray-300' }}">
                            📊 Dashboard
                        </a>
                        <a href="{{ route('admin.ecoles.index') }}" 
                           class="px-3 py-2 rounded-md hover-bg transition-all {{ request()->routeIs('admin.ecoles*') ? 'bg-white bg-opacity-20' : 'text-gray-300' }}">
                            🏢 Écoles
                        </a>
                        <a href="{{ route('admin.membres.index') }}" 
                           class="px-3 py-2 rounded-md hover-bg transition-all {{ request()->routeIs('admin.membres*') ? 'bg-white bg-opacity-20' : 'text-gray-300' }}">
                            👥 Membres
                        </a>
                        <span class="px-3 py-2 rounded-md text-gray-500 cursor-not-allowed">
                            📚 Cours (bientôt)
                        </span>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="relative">
                        <button onclick="toggleDropdown()" class="flex items-center space-x-2 hover-bg px-3 py-2 rounded-md transition-all">
                            <span class="text-white">👤 {{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 card-bg rounded-md shadow-xl py-1 z-50">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-white hover-bg">👤 Profil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-white hover-bg">
                                    🚪 Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
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
