<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>@yield('title', 'StudiosDB - Espace Membre')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-white font-sans antialiased">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation Top Membre -->
        <nav class="bg-slate-800 border-b border-slate-700 px-6 py-4" role="navigation" aria-label="Navigation membre">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2" aria-label="Accueil StudiosDB">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-sm">S</span>
                        </div>
                        <div>
                            <span class="text-xl font-bold text-white">StudiosDB</span>
                            <span class="text-xs text-slate-400 block">v4.1.10.4</span>
                        </div>
                    </a>
                    <span class="text-slate-400" aria-hidden="true">|</span>
                    <span class="text-blue-400 font-medium">
                        <span aria-hidden="true">🥋</span> Espace Membre
                    </span>
                </div>
                
                <div class="flex items-center space-x-6">
                    <!-- Menu membre simple -->
                    <a href="{{ route('dashboard') }}" 
                       class="text-slate-300 hover:text-white flex items-center space-x-2 {{ request()->routeIs('dashboard') ? 'text-blue-400' : '' }}"
                       aria-label="Mon tableau de bord">
                        <span aria-hidden="true">📊</span>
                        <span class="hidden sm:inline">Mon Dashboard</span>
                    </a>
                    
                    <!-- User info et actions -->
                    <div class="flex items-center space-x-4 border-l border-slate-600 pl-4">
                        <div class="text-right hidden md:block">
                            <div class="text-sm font-medium text-white">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-slate-400">{{ auth()->user()->ecole->nom ?? 'École non assignée' }}</div>
                        </div>
                        
                        @can('view-admin')
                        <a href="/admin" 
                           class="bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded text-sm font-medium transition-colors"
                           aria-label="Accéder à l'administration">
                            <span aria-hidden="true">⚙️</span> <span class="hidden sm:inline">Administration</span>
                        </a>
                        @endcan
                        
                        <a href="{{ route('membre.profil') }}" 
                           class="text-slate-300 hover:text-white"
                           aria-label="Mon profil">
                            <span aria-hidden="true">👤</span> <span class="hidden sm:inline">Profil</span>
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="text-slate-300 hover:text-red-400 transition-colors"
                                    aria-label="Se déconnecter">
                                <span aria-hidden="true">🚪</span> <span class="hidden sm:inline">Déconnexion</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Contenu principal -->
        <main class="flex-1 bg-slate-900" role="main">
            <!-- Mode Maintenance -->
            @if(app()->isDownForMaintenance())
                <div class="bg-amber-500/20 border-b border-amber-500/30 text-amber-300 px-6 py-3" role="alert">
                    <div class="flex items-center justify-center">
                        <span class="text-xl mr-3" aria-hidden="true">🚧</span>
                        <span>Maintenance en cours - Accès limité</span>
                    </div>
                </div>
            @endif

            {{-- MESSAGES FLASH POUR MEMBRES --}}
            <div class="px-6 pt-6">
                {{-- Flash messages avancés --}}
                @if(session('flash_message'))
                    @php
                        $flash = session('flash_message');
                        $type = $flash['type'] ?? 'info';
                        $message = $flash['message'] ?? '';
                        $title = $flash['title'] ?? '';
                    @endphp
                    <div class="mb-6 p-4 rounded-lg border-l-4 
                                @if($type === 'success') bg-green-900/20 border-green-500 text-green-300 @endif
                                @if($type === 'error') bg-red-900/20 border-red-500 text-red-300 @endif
                                @if($type === 'warning') bg-yellow-900/20 border-yellow-500 text-yellow-300 @endif
                                @if($type === 'info') bg-blue-900/20 border-blue-500 text-blue-300 @endif">
                        <div class="flex items-start space-x-3">
                            <span class="text-xl">
                                @if($type === 'success') ✅ @endif
                                @if($type === 'error') ❌ @endif
                                @if($type === 'warning') ⚠️ @endif
                                @if($type === 'info') ℹ️ @endif
                            </span>
                            <div>
                                @if($title)<h3 class="font-medium mb-1">{{ $title }}</h3>@endif
                                <p class="text-sm">{{ $message }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Messages simples (fallback) --}}
                @foreach(['success', 'error', 'warning', 'info'] as $type)
                    @if(session($type))
                        <div class="mb-6 p-4 rounded-lg border-l-4 
                                    @if($type === 'success') bg-green-900/20 border-green-500 text-green-300 @endif
                                    @if($type === 'error') bg-red-900/20 border-red-500 text-red-300 @endif
                                    @if($type === 'warning') bg-yellow-900/20 border-yellow-500 text-yellow-300 @endif
                                    @if($type === 'info') bg-blue-900/20 border-blue-500 text-blue-300 @endif">
                            <div class="flex items-center space-x-3">
                                <span class="text-xl">
                                    @if($type === 'success') ✅ @endif
                                    @if($type === 'error') ❌ @endif
                                    @if($type === 'warning') ⚠️ @endif
                                    @if($type === 'info') ℹ️ @endif
                                </span>
                                <span class="text-sm font-medium">{{ session($type) }}</span>
                            </div>
                        </div>
                    @endif
                @endforeach

                {{-- Erreurs de validation --}}
                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-lg border-l-4 bg-red-900/20 border-red-500 text-red-300">
                        <div class="flex items-start space-x-3">
                            <span class="text-xl">❌</span>
                            <div>
                                <h3 class="font-medium mb-2">Il y a des erreurs dans votre formulaire</h3>
                                <ul class="text-sm space-y-1 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            @yield('content')
        </main>
        
        @include('partials.footer')
    </div>
</body>
</html>
