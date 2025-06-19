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
            margin: 0;
            padding: 0;
        }
        .sidebar-bg {
            background: linear-gradient(180deg, #1e293b, #0f172a);
            border-right: 1px solid #334155;
        }
        .header-bg { 
            background: linear-gradient(135deg, #1e293b, #334155); 
            border-bottom: 1px solid #475569;
        }
        .card-bg { 
            background-color: #1e293b !important; 
            border: 1px solid #334155;
        }
        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            margin: 0.125rem 0;
            border-radius: 0.375rem;
            text-decoration: none;
            color: #94a3b8;
            transition: all 0.2s ease;
            font-size: 0.875rem;
        }
        .nav-item:hover {
            background-color: rgba(59, 130, 246, 0.1);
            color: #f1f5f9;
        }
        .nav-item.active {
            background-color: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
            border-left: 3px solid #3b82f6;
        }
        .nav-item .icon {
            margin-right: 0.75rem;
            font-size: 1rem;
            width: 1.25rem;
            text-align: center;
        }
        
        /* Responsive Design */
        @media (max-width: 1024px) {
            .sidebar {
                width: 200px;
            }
            .nav-item {
                padding: 0.5rem 0.75rem;
                font-size: 0.8rem;
            }
            .nav-item .icon {
                margin-right: 0.5rem;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 60px;
            }
            .sidebar-collapsed .nav-text {
                display: none;
            }
            .sidebar-collapsed .nav-item {
                justify-content: center;
                padding: 0.75rem;
            }
            .sidebar-collapsed .icon {
                margin-right: 0;
            }
            .sidebar-collapsed .logo-text {
                display: none;
            }
            .sidebar-collapsed .user-info-text {
                display: none;
            }
        }
        
        .sidebar-toggle {
            display: none;
        }
        
        @media (max-width: 768px) {
            .sidebar-toggle {
                display: block;
            }
        }
    </style>
</head>
<body>
    <div class="flex h-screen">
        <!-- Sidebar Navigation -->
        <div id="sidebar" class="sidebar sidebar-bg shadow-xl transition-all duration-300 ease-in-out" style="width: 240px;">
            <!-- Logo -->
            <div class="p-4 border-b border-slate-600">
                <div class="flex items-center">
                    <span class="text-xl">🥋</span>
                    <div class="ml-2 logo-text">
                        <h1 class="text-lg font-bold text-white">StudiosUnisDB</h1>
                        <p class="text-xs text-blue-300 leading-none">
                            @if(auth()->user()->hasRole('superadmin'))
                                SuperAdmin - Réseau
                            @elseif(auth()->user()->ecole)
                                {{ Str::limit(auth()->user()->ecole->nom, 20) }}
                            @else
                                École non assignée
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 p-3 overflow-y-auto">
                <div class="space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="nav-item {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}"
                       title="Dashboard">
                        <span class="icon">📊</span>
                        <span class="nav-text">Dashboard</span>
                    </a>

                    <!-- Écoles - SEULEMENT SuperAdmin -->
                    @if(auth()->user()->hasRole('superadmin'))
                    <a href="{{ route('admin.ecoles.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.ecoles*') ? 'active' : '' }}"
                       title="Écoles">
                        <span class="icon">🏢</span>
                        <span class="nav-text">Écoles</span>
                    </a>
                    @endif

                    <!-- Membres -->
                    @if(auth()->user()->hasAnyRole(['superadmin', 'admin', 'instructeur']))
                    <a href="{{ route('admin.membres.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.membres*') ? 'active' : '' }}"
                       title="Membres">
                        <span class="icon">👥</span>
                        <span class="nav-text">Membres</span>
                    </a>
                    @endif

                    <!-- Cours -->
                    @if(auth()->user()->hasAnyRole(['superadmin', 'admin', 'instructeur']))
                    <a href="{{ route('admin.cours.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.cours*') ? 'active' : '' }}"
                       title="Cours">
                        <span class="icon">📚</span>
                        <span class="nav-text">Cours</span>
                    </a>
                    @endif

                    <!-- Présences -->
                    @if(auth()->user()->hasAnyRole(['superadmin', 'admin', 'instructeur']))
                    <a href="{{ route('admin.presences.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.presences*') ? 'active' : '' }}"
                       title="Présences">
                        <span class="icon">✅</span>
                        <span class="nav-text">Présences</span>
                    </a>
                    @endif

                    <!-- Ceintures -->
                    @if(auth()->user()->hasAnyRole(['superadmin', 'admin', 'instructeur']))
                    <a href="{{ route('admin.ceintures.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.ceintures*') ? 'active' : '' }}"
                       title="Ceintures">
                        <span class="icon">🥋</span>
                        <span class="nav-text">Ceintures</span>
                    </a>
                    @endif

                    <!-- Séminaires -->
                    @if(auth()->user()->hasAnyRole(['superadmin', 'admin', 'instructeur']))
                    <a href="{{ route('admin.seminaires.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.seminaires*') ? 'active' : '' }}"
                       title="Séminaires">
                        <span class="icon">🎓</span>
                        <span class="nav-text">Séminaires</span>
                    </a>
                    @endif

                    <!-- Paiements -->
                    @if(auth()->user()->hasAnyRole(['superadmin', 'admin']))
                    <a href="{{ route('admin.paiements.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.paiements*') ? 'active' : '' }}"
                       title="Paiements">
                        <span class="icon">💳</span>
                        <span class="nav-text">Paiements</span>
                    </a>
                                        <!-- Logs -->
                    @if(auth()->user()->hasAnyRole(['superadmin', 'admin']))
                    <a href="{{ route('admin.logs.index') }}"
                       class="nav-item {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}"
                       title="Logs">
                        <span class="icon">📋</span>
                        <span class="nav-text">Logs</span>
                    </a>
                    @endif

                @endif

                    <!-- Séparateur -->
                    @if(auth()->user()->hasRole('superadmin'))
                    <div class="border-t border-slate-600 my-3"></div>

                    <!-- Telescope - SEULEMENT SuperAdmin -->
                    <a href="{{ url('/telescope') }}" 
                       class="nav-item"
                       target="_blank"
                       title="Monitoring système">
                        <span class="icon">🔭</span>
                        <span class="nav-text">Telescope</span>
                    </a>
                    @endif
                </div>
            </nav>

            <!-- User Info Bottom -->
            <div class="p-3 border-t border-slate-600">
                <div class="relative">
                    <button onclick="toggleUserDropdown()" class="w-full flex items-center space-x-2 hover:bg-slate-700 p-2 rounded transition-all">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">
                            @if(auth()->user()->hasRole('superadmin'))
                                🔥
                            @elseif(auth()->user()->hasRole('admin'))
                                👑
                            @elseif(auth()->user()->hasRole('instructeur'))
                                🥋
                            @else
                                👤
                            @endif
                        </div>
                        <div class="flex-1 text-left user-info-text min-w-0">
                            <div class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-slate-400 truncate">{{ auth()->user()->roles->pluck('name')->first() }}</div>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0 user-info-text" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    
                    <div id="userDropdown" class="hidden absolute bottom-full mb-2 left-0 w-full card-bg rounded-md shadow-xl py-1 z-50">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-white hover:bg-slate-600">👤 Profil</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-white hover:bg-slate-600">
                                🚪 Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Header -->
            <header class="header-bg shadow-sm flex items-center justify-between px-6 py-4 z-10">
                <div class="flex items-center">
                    <!-- Mobile Sidebar Toggle -->
                    <button id="sidebarToggle" 
                            onclick="toggleSidebar()" 
                            class="sidebar-toggle mr-4 p-2 rounded-md hover:bg-slate-600 lg:hidden">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    
                    <h1 class="text-xl font-bold text-white">
                        @yield('page-title', 'Administration')
                    </h1>
                </div>
                
                <!-- Header Actions -->
                <div class="flex items-center space-x-4">
                    <div class="text-right hidden sm:block">
                        <div class="text-sm font-medium text-white">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-slate-300">
                            @if(auth()->user()->hasRole('superadmin'))
                                SuperAdmin
                            @elseif(auth()->user()->ecole)
                                {{ auth()->user()->ecole->nom }}
                            @else
                                École non assignée
                            @endif
                        </div>
                    </div>
                    
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-lg">
                        @if(auth()->user()->hasRole('superadmin'))
                            🔥
                        @elseif(auth()->user()->hasRole('admin'))
                            👑
                        @elseif(auth()->user()->hasRole('instructeur'))
                            🥋
                        @else
                            👤
                        @endif
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-900 p-4 lg:p-6">
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

                <div class="max-w-full">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile Overlay -->
    <div id="sidebarOverlay" 
         class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden" 
         onclick="toggleSidebar()">
    </div>

    <script>
        function toggleUserDropdown() {
            document.getElementById('userDropdown').classList.toggle('hidden');
        }
        
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (window.innerWidth <= 768) {
                // Mobile: Show/hide sidebar
                if (sidebar.style.transform === 'translateX(-100%)') {
                    sidebar.style.transform = 'translateX(0)';
                    overlay.classList.remove('hidden');
                } else {
                    sidebar.style.transform = 'translateX(-100%)';
                    overlay.classList.add('hidden');
                }
            } else {
                // Desktop: Collapse/expand
                sidebar.classList.toggle('sidebar-collapsed');
                if (sidebar.classList.contains('sidebar-collapsed')) {
                    sidebar.style.width = '60px';
                } else {
                    sidebar.style.width = '240px';
                }
            }
        }
        
        // Auto-collapse sidebar on mobile
        function handleResize() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (window.innerWidth <= 768) {
                sidebar.style.transform = 'translateX(-100%)';
                sidebar.style.position = 'fixed';
                sidebar.style.zIndex = '50';
                overlay.classList.add('hidden');
            } else {
                sidebar.style.transform = 'translateX(0)';
                sidebar.style.position = 'relative';
                sidebar.style.zIndex = 'auto';
                overlay.classList.add('hidden');
                
                if (window.innerWidth <= 1024) {
                    sidebar.classList.add('sidebar-collapsed');
                    sidebar.style.width = '200px';
                } else {
                    sidebar.classList.remove('sidebar-collapsed');
                    sidebar.style.width = '240px';
                }
            }
        }
        
        window.addEventListener('resize', handleResize);
        window.addEventListener('load', handleResize);
        
        window.onclick = function(event) {
            if (!event.target.closest('[onclick="toggleUserDropdown()"]')) {
                document.getElementById('userDropdown').classList.add('hidden');
            }
        }
    </script>
</body>
</html>
