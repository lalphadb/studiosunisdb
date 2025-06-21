<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - StudiosUnisDB</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-900 text-white antialiased">
    <div class="min-h-full">
        <!-- Sidebar -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-gray-800 px-6 pb-4">
                <!-- Logo -->
                <div class="flex h-16 shrink-0 items-center">
                    <div class="flex items-center space-x-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-purple-600">
                            <span class="text-xl font-bold text-white">🥋</span>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold text-white">StudiosUnisDB</h1>
                            <p class="text-xs text-blue-300">v4.0-FINAL</p>
                        </div>
                    </div>
                </div>
                
                <!-- Navigation -->
                <nav class="flex flex-1 flex-col">
                    <ul role="list" class="flex flex-1 flex-col gap-y-7">
                        <li>
                            <div class="text-xs font-semibold leading-6 text-gray-400">Principal</div>
                            <ul role="list" class="-mx-2 mt-2 space-y-1">
                                <li>
                                    <a href="{{ route('admin.dashboard') }}" 
                                       class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('admin.dashboard*') ? 'bg-gray-700 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                                        <span class="text-lg">📊</span>
                                        Dashboard
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <li>
                            <div class="text-xs font-semibold leading-6 text-gray-400">Gestion</div>
                            <ul role="list" class="-mx-2 mt-2 space-y-1">
                                @can('view-users')
                                <li>
                                    <a href="{{ route('admin.users.index') }}" 
                                       class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('admin.users*') ? 'bg-gray-700 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                                        <span class="text-lg">👥</span>
                                        Utilisateurs
                                    </a>
                                </li>
                                @endcan
                                
                                @can('view-ecoles')
                                <li>
                                    <a href="{{ route('admin.ecoles.index') }}" 
                                       class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('admin.ecoles*') ? 'bg-gray-700 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                                        <span class="text-lg">🏯</span>
                                        Écoles
                                    </a>
                                </li>
                                @endcan
                                
                                @can('view-ceintures')
                                <li>
                                    <a href="{{ route('admin.ceintures.index') }}" 
                                       class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('admin.ceintures*') ? 'bg-gray-700 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                                        <span class="text-lg">🥋</span>
                                        Ceintures
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        
                        <!-- Modules futurs -->
                        <li>
                            <div class="text-xs font-semibold leading-6 text-gray-400">Modules (Bientôt)</div>
                            <ul role="list" class="-mx-2 mt-2 space-y-1">
                                <li>
                                    <span class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-600">
                                        <span class="text-lg">📚</span>
                                        Cours
                                    </span>
                                </li>
                                <li>
                                    <span class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-600">
                                        <span class="text-lg">💳</span>
                                        Paiements
                                    </span>
                                </li>
                                <li>
                                    <span class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-600">
                                        <span class="text-lg">✅</span>
                                        Présences
                                    </span>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Mobile menu button -->
        <div class="sticky top-0 z-40 flex items-center gap-x-6 bg-gray-800 px-4 py-4 shadow-sm lg:hidden">
            <button type="button" class="-m-2.5 p-2.5 text-gray-400 lg:hidden">
                <span class="sr-only">Open sidebar</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
            <div class="flex-1 text-sm font-semibold leading-6 text-white">StudiosUnisDB Admin</div>
        </div>

        <!-- Main content area -->
        <div class="lg:pl-72">
            <!-- Top header -->
            <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-700 bg-gray-800 px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                    <div class="flex flex-1 items-center">
                        <h1 class="text-lg font-semibold leading-6 text-white">
                            @yield('title', 'Administration')
                        </h1>
                        @if(auth()->user()->ecole)
                        <span class="ml-4 text-sm text-blue-300">{{ auth()->user()->ecole->nom }}</span>
                        @endif
                    </div>
                    
                    <!-- Profile dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button type="button" 
                                class="flex items-center gap-x-4 px-6 py-3 text-sm font-semibold leading-6 text-white hover:bg-gray-700 rounded-lg transition-colors"
                                @click="open = !open">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-purple-600">
                                @if(auth()->user()->hasRole('superadmin'))
                                    <span class="text-sm">⭐</span>
                                @elseif(auth()->user()->hasRole('admin'))
                                    <span class="text-sm">👑</span>
                                @else
                                    <span class="text-sm">👤</span>
                                @endif
                            </div>
                            <div class="hidden lg:flex lg:flex-col lg:items-start">
                                <span class="text-sm font-semibold text-white">{{ auth()->user()->name }}</span>
                                <span class="text-xs text-gray-400">{{ auth()->user()->roles->first()?->name }}</span>
                            </div>
                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Dropdown panel -->
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 z-10 mt-2.5 w-64 origin-top-right rounded-md bg-gray-800 border border-gray-700 py-2 shadow-lg">
                            
                            <div class="px-4 py-3 border-b border-gray-700">
                                <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-400">{{ auth()->user()->email }}</p>
                                @if(auth()->user()->ecole)
                                <p class="text-xs text-blue-300 mt-1">🏯 {{ auth()->user()->ecole->nom }}</p>
                                @endif
                            </div>
                            
                            <div class="py-1">
                                <a href="{{ route('profile.edit') }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                    <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Profil
                                </a>
                                
                                @if(auth()->user()->hasRole('superadmin'))
                                <a href="/telescope" target="_blank"
                                   class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                    <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    Telescope
                                </a>
                                @endif
                                
                                <div class="border-t border-gray-700 my-1"></div>
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="flex w-full items-center px-4 py-2 text-sm text-red-300 hover:bg-red-900 hover:text-red-100">
                                        <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page content -->
            <main class="py-10">
                <div class="px-4 sm:px-6 lg:px-8">
                    <!-- Flash messages -->
                    @if(session('success'))
                    <div class="mb-6 rounded-md bg-green-900 border border-green-700 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-300">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="mb-6 rounded-md bg-red-900 border border-red-700 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-300">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="mb-6 rounded-md bg-red-900 border border-red-700 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm text-red-300">
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Alpine.js pour les dropdowns -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
