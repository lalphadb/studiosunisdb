<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'StudiosUnisDB') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand { font-weight: bold; }
        .card { box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); border: none; }
        .badge { font-size: 0.75em; }
        .btn-sm { font-size: 0.875rem; }
    </style>
</head>
<body>
    <div id="app">
        <!-- Navigation -->
        <nav class="navbar navbar-expand-md navbar-dark bg-primary shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                    🥋 StudiosUnisDB
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    @auth
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">📊 Dashboard</a>
                        </li>
                        
                        @can('view-membres')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.membres.index') }}">👥 Membres</a>
                        </li>
                        @endcan
                        
                        @can('view-cours')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.cours.index') }}">📚 Cours</a>
                        </li>
                        @endcan
                        
                        @can('view-ceintures')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.ceintures.index') }}">🥋 Ceintures</a>
                        </li>
                        @endcan
                        
                        @can('view-presences')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.presences.index') }}">👁️ Présences</a>
                        </li>
                        @endcan
                        
                        @can('view-paiements')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.paiements.index') }}">💰 Paiements</a>
                        </li>
                        @endcan
                        
                        @can('view-ecoles')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.ecoles.index') }}">🏫 Écoles</a>
                        </li>
                        @endcan
                    </ul>
                    @endauth

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    {{ Auth::user()->name }}
                                    @if(Auth::user()->ecole)
                                        <small class="text-light">({{ Auth::user()->ecole->code }})</small>
                                    @endif
                                </a>

                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="{{ route('profile.edit') ?? '#' }}">
                                        👤 Profil
                                    </a>
                                    
                                    @role('superadmin')
                                    <a class="dropdown-item" href="/telescope" target="_blank">
                                        🔭 Telescope
                                    </a>
                                    @endrole
                                    
                                    <div class="dropdown-divider"></div>
                                    
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        🚪 Déconnexion
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
