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
        <!-- SIDEBAR FIXE -->
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
                            <ul role="list" class="-mx-2 space-y-1">
                                <li>
                                    <a href="{{ route('admin.dashboard') }}" 
                                       class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:text-white hover:bg-gray-700' }}">
                                        <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                        </svg>
                                        Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.users.index') }}" 
                                       class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('admin.users.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:text-white hover:bg-gray-700' }}">
                                        <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                        </svg>
                                        Utilisateurs
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.ecoles.index') }}" 
                                       class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('admin.ecoles.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:text-white hover:bg-gray-700' }}">
                                        <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                                        </svg>
                                        Écoles
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.ceintures.index') }}" 
                                       class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('admin.ceintures.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:text-white hover:bg-gray-700' }}">
                                        <span class="h-6 w-6 shrink-0 text-lg">🎗️</span>
                                        Ceintures
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- CONTENU PRINCIPAL - AVEC MARGE POUR LE SIDEBAR -->
        <div class="lg:pl-72">
            <!-- Top bar -->
            <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-700 bg-gray-800 px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                    <div class="flex flex-1 items-center">
                        <h1 class="text-lg font-semibold leading-6 text-white">
                            @yield('title', 'Administration')
                        </h1>
                        @if(auth()->check() && auth()->user()->ecole)
                            <span class="ml-4 text-sm text-blue-300">{{ auth()->user()->ecole->nom }}</span>
                        @endif
                    </div>
                    
                    <!-- Profile dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button type="button" 
                                @click="open = !open"
                                class="flex items-center gap-x-4 px-6 py-3 text-sm font-semibold leading-6 text-white hover:bg-gray-700 rounded-lg" 
                                id="user-menu-button">
                            <img class="h-8 w-8 rounded-full bg-gray-700" 
                                 src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=6366f1&color=ffffff" 
                                 alt="">
                            <span aria-hidden="true">{{ auth()->user()->name }}</span>
                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" 
                             @click.outside="open = false"
                             x-transition
                             class="absolute right-0 z-10 mt-2.5 w-32 origin-top-right rounded-md bg-gray-800 py-2 shadow-lg ring-1 ring-gray-700">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="block w-full px-3 py-1 text-left text-sm leading-6 text-white hover:bg-gray-700">
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

                    @if(session('info'))
                        <div class="mb-4 bg-blue-900 border border-blue-700 text-blue-100 px-4 py-3 rounded">
                            {{ session('info') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>
