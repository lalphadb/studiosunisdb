@vite(['resources/css/app.css', 'resources/js/app.js'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administration')</title>
</head>
<body class="bg-slate-900 text-white">
    <div class="flex flex-col h-screen overflow-hidden">
        <!-- Header -->
        <header class="bg-gradient-to-r from-gray-500 via-gray-600 to-transparent p-6 text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-transparent to-slate-900 opacity-60"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <span class="text-2xl font-bold">S</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-semibold">StudiosDB</h1>
                        <p class="text-sm text-slate-400">Administration</p>
                    </div>
                </div>
                <div class="relative">
                    <button type="button" class="flex items-center space-x-2 focus:outline-none" x-data="{ open: false }" @click="open = !open" @click.away="open = false">
                        <span>{{ auth()->user()->name }} - 
                            @foreach(auth()->user()->roles as $role)
                                {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                            @endforeach
                        </span>
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" class="absolute right-0 mt-2 w-48 bg-slate-800 rounded-lg shadow-lg py-2 z-10" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95">
                        <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-slate-300 hover:bg-slate-700">Profil</a>
                        @if(auth()->user()->hasRole('membre'))
                            <a href="{{ route('membre.dashboard') }}" class="block px-4 py-2 text-sm text-slate-300 hover:bg-slate-700">Dashboard Membre</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="block w-full text-left">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-slate-300 hover:bg-slate-700">Déconnexion</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="flex flex-1 overflow-hidden">
            <aside class="w-64 bg-slate-800 p-4 overflow-y-auto hidden md:block">
                <nav>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-2 py-2 text-sm font-medium text-slate-300 hover:bg-slate-700 rounded-lg mb-1">
                        <span class="mr-2">📊</span> Dashboard
                    </a>
                    @can('viewAny', App\Models\User::class)
                        <a href="{{ route('admin.users.index') }}" class="flex items-center px-2 py-2 text-sm font-medium text-slate-300 hover:bg-slate-700 rounded-lg mb-1">
                            <span class="mr-2">👤</span> Utilisateurs
                        </a>
                    @endcan
                    @can('viewAny', App\Models\Ecole::class)
                        <a href="{{ route('admin.ecoles.index') }}" class="flex items-center px-2 py-2 text-sm font-medium text-slate-300 hover:bg-slate-700 rounded-lg mb-1">
                            <span class="mr-2">🏫</span> Écoles
                        </a>
                    @endcan
                    @can('viewAny', App\Models\Cours::class)
                        <a href="{{ route('admin.cours.index') }}" class="flex items-center px-2 py-2 text-sm font-medium text-slate-300 hover:bg-slate-700 rounded-lg mb-1">
                            <span class="mr-2">📚</span> Cours
                        </a>
                    @endcan
                    @can('viewAny', App\Models\Seminaire::class)
                        <a href="{{ route('admin.seminaires.index') }}" class="flex items-center px-2 py-2 text-sm font-medium text-slate-300 hover:bg-slate-700 rounded-lg mb-1">
                            <span class="mr-2">🎯</span> Séminaires
                        </a>
                    @endcan
                    @can('viewAny', App\Models\Ceinture::class)
                        <a href="{{ route('admin.ceintures.index') }}" class="flex items-center px-2 py-2 text-sm font-medium text-slate-300 hover:bg-slate-700 rounded-lg mb-1">
                            <span class="mr-2">🥋</span> Ceintures
                        </a>
                    @endcan
                    @can('viewAny', App\Models\Paiement::class)
                        <a href="{{ route('admin.paiements.index') }}" class="flex items-center px-2 py-2 text-sm font-medium text-slate-300 hover:bg-slate-700 rounded-lg mb-1">
                            <span class="mr-2">💰</span> Paiements
                        </a>
                    @endcan
                    @can('viewAny', App\Models\Presence::class)
                        <a href="{{ route('admin.presences.index') }}" class="flex items-center px-2 py-2 text-sm font-medium text-slate-300 hover:bg-slate-700 rounded-lg mb-1">
                            <span class="mr-2">✅</span> Présences
                        </a>
                    @endcan
                    @if(auth()->user()->hasRole('superadmin'))
                        <a href="{{ route('telescope') }}" class="flex items-center px-2 py-2 text-sm font-medium text-slate-300 hover:bg-slate-700 rounded-lg mb-1">
                            <span class="mr-2">🔭</span> Telescope <span class="text-xs text-yellow-400">DEV</span>
                        </a>
                    @endif
                </nav>
            </aside>
            <main class="flex-1 overflow-y-auto p-4">
                @if (session('success'))
                    <div class="bg-green-600 text-white p-4 rounded-lg mb-4">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="bg-red-600 text-white p-4 rounded-lg mb-4">{{ session('error') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
