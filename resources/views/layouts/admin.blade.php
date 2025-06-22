<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - StudiosUnisDB</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-900 text-white antialiased">
    <div class="min-h-full">
        <!-- SIDEBAR BEAUTIFUL -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-slate-800 px-6 pb-4">
                <!-- Logo Header -->
                <div class="flex h-16 shrink-0 items-center">
                    <div class="flex items-center space-x-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-blue-600">
                            <span class="text-xl font-bold text-white">🥋</span>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold text-white">StudiosUnisDB</h1>
                            <p class="text-xs text-purple-300">v3.9.3-FINAL</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex flex-1 flex-col">
                    <ul role="list" class="flex flex-1 flex-col gap-y-7">
                        <li>
                            <!-- PRINCIPAL -->
                            <div class="text-xs font-semibold leading-6 text-slate-400 uppercase tracking-wider">Principal</div>
                            <ul role="list" class="-mx-2 mt-2 space-y-1">
                                <li>
                                    <a href="{{ route('admin.dashboard') }}" 
                                       class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('admin.dashboard') ? 'bg-slate-700 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}">
                                        <span class="h-6 w-6 shrink-0 text-lg">📊</span>
                                        Dashboard
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <!-- GESTION -->
                            <div class="text-xs font-semibold leading-6 text-slate-400 uppercase tracking-wider">Gestion</div>
                            <ul role="list" class="-mx-2 mt-2 space-y-1">
                                <li>
                                    <a href="{{ route('admin.ecoles.index') }}" 
                                       class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('admin.ecoles.*') ? 'bg-slate-700 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}">
                                        <span class="h-6 w-6 shrink-0 text-lg">🏫</span>
                                        Écoles
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.users.index') }}" 
                                       class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('admin.users.*') ? 'bg-slate-700 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}">
                                        <span class="h-6 w-6 shrink-0 text-lg">👥</span>
                                        Membres
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.cours.index') }}" 
                                       class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('admin.cours.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}">
                                        <span class="h-6 w-6 shrink-0 text-lg">📚</span>
                                        Cours
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.presences.index') }}" 
                                       class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('admin.presences.*') ? 'bg-slate-700 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}">
                                        <span class="h-6 w-6 shrink-0 text-lg">✅</span>
                                        Présences
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <!-- PROGRESSION -->
                            <div class="text-xs font-semibold leading-6 text-slate-400 uppercase tracking-wider">Progression</div>
                            <ul role="list" class="-mx-2 mt-2 space-y-1">
                                <li>
                                    <a href="{{ route('admin.ceintures.index') }}" 
                                       class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('admin.ceintures.*') ? 'bg-slate-700 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}">
                                        <span class="h-6 w-6 shrink-0 text-lg">🥋</span>
                                        Ceintures
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.seminaires.index') }}" 
                                       class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('admin.seminaires.*') ? 'bg-slate-700 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}">
                                        <span class="h-6 w-6 shrink-0 text-lg">🎯</span>
                                        Séminaires
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <!-- FINANCES -->
                            <div class="text-xs font-semibold leading-6 text-slate-400 uppercase tracking-wider">Finances</div>
                            <ul role="list" class="-mx-2 mt-2 space-y-1">
                                <li>
                                    <a href="{{ route('admin.paiements.index') }}" 
                                       class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('admin.paiements.*') ? 'bg-slate-700 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}">
                                        <span class="h-6 w-6 shrink-0 text-lg">💰</span>
                                        Paiements
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- CONTENU PRINCIPAL -->
        <div class="lg:pl-72">
            <!-- Top bar avec dropdown -->
            <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-slate-700 bg-slate-800 px-4 shadow-sm">
                <div class="flex flex-1 gap-x-4 self-stretch">
                    <div class="flex flex-1 items-center">
                        <h1 class="text-lg font-semibold leading-6 text-white">
                            @yield('title', 'Cours')
                        </h1>
                    </div>
                    
                    <!-- Profile dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button type="button" 
                                @click="open = !open"
                                class="flex items-center gap-x-4 px-6 py-3 text-sm font-semibold leading-6 text-white hover:bg-slate-700 rounded-lg">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-yellow-500 to-orange-600">
                                <span class="text-white font-bold text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-slate-400">{{ auth()->user()->getRoleNames()->first() ?? 'superadmin' }}</div>
                            </div>
                            <svg class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" 
                             @click.outside="open = false"
                             x-transition
                             class="absolute right-0 z-10 mt-2.5 w-56 origin-top-right rounded-md bg-slate-800 py-2 shadow-lg ring-1 ring-slate-700">
                            
                            <div class="px-4 py-2 border-b border-slate-700">
                                <div class="text-sm font-medium text-white">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-slate-400">{{ auth()->user()->email }}</div>
                            </div>
                            
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-slate-300 hover:bg-slate-700 hover:text-white">
                                <span class="mr-3">👤</span>
                                Accès compte système
                            </a>
                            
                            <a href="/telescope" target="_blank" class="flex items-center px-4 py-2 text-sm text-slate-300 hover:bg-slate-700 hover:text-white">
                                <span class="mr-3">🔭</span>
                                Telescope - Monitoring Système
                            </a>
                            
                            <div class="border-t border-slate-700 my-1"></div>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-slate-300 hover:bg-slate-700 hover:text-white">
                                    <span class="mr-3">🚪</span>
                                    Se déconnecter
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page content -->
            <main class="py-10">
                <div class="px-4 sm:px-6 lg:px-8">
                    @if(session('success'))
                        <div class="mb-4 bg-green-900 border border-green-700 text-green-100 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 bg-red-900 border border-red-700 text-red-100 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>
