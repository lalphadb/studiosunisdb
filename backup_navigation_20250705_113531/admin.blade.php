<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'StudiosDB') }} - @yield('title', 'Administration')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @stack('styles')
    
    <style>
        /* Fix immediate styles */
        .flex { display: flex; }
        .flex-1 { flex: 1 1 0%; }
        .flex-col { flex-direction: column; }
        .h-screen { height: 100vh; }
        .w-64 { width: 16rem; }
        .overflow-hidden { overflow: hidden; }
        .overflow-y-auto { overflow-y: auto; }
        .space-y-2 > * + * { margin-top: 0.5rem; }
        .space-x-3 > * + * { margin-left: 0.75rem; }
        .bg-slate-900 { background-color: rgb(15 23 42); }
        .bg-slate-800 { background-color: rgb(30 41 59); }
        .text-white { color: rgb(255 255 255); }
        .border-slate-700 { border-color: rgb(51 65 85); }
        .p-6 { padding: 1.5rem; }
        .p-4 { padding: 1rem; }
    </style>
</head>
<body class="font-sans antialiased bg-slate-900 text-slate-100">
    <div class="flex h-screen bg-slate-900 overflow-hidden">
        <!-- Sidebar Navigation -->
        <x-admin.navigation />
        
        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-h-screen">
            <!-- Top Header -->
            <header class="bg-slate-800 border-b border-slate-700 px-6 py-4 flex items-center justify-between flex-shrink-0">
                <!-- Breadcrumb -->
                <nav class="text-sm text-slate-400">
                    <span class="text-slate-500">StudiosDB</span>
                    @hasSection('breadcrumb')
                        <span class="mx-2">/</span>
                        @yield('breadcrumb')
                    @endif
                </nav>
                
                <!-- User Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="flex items-center space-x-3 text-slate-300 hover:text-white transition-colors focus:outline-none">
                        <div class="text-right">
                            <div class="text-sm font-medium text-slate-200">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-slate-400">
                                @if(auth()->user()->hasRole('superadmin'))
                                    Super Administrateur
                                @elseif(auth()->user()->hasRole('admin_ecole'))
                                    Administrateur École
                                @else
                                    {{ ucfirst(auth()->user()->getRoleNames()->first()) }}
                                @endif
                            </div>
                        </div>
                        
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="absolute right-0 mt-2 w-48 bg-slate-800 border border-slate-700 rounded-lg shadow-lg z-50"
                         style="display: none;">
                        
                        <div class="py-2">
                            <a href="{{ route('profile.edit') }}" 
                               class="flex items-center px-4 py-2 text-sm text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
                                <i class="fas fa-user-circle mr-3"></i>
                                Mon Profil
                            </a>
                            
                            <a href="{{ route('admin.dashboard') }}" 
                               class="flex items-center px-4 py-2 text-sm text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
                                <i class="fas fa-chart-pie mr-3"></i>
                                Dashboard
                            </a>
                            
                            <div class="border-t border-slate-700 my-1"></div>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full flex items-center px-4 py-2 text-sm text-red-400 hover:bg-red-500 hover:bg-opacity-20 hover:text-red-300 transition-colors">
                                    <i class="fas fa-sign-out-alt mr-3"></i>
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Main Content - BIEN CENTRÉ -->
            <main class="flex-1 overflow-y-auto bg-slate-900">
                <div class="container mx-auto px-6 py-6 max-w-7xl">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>
