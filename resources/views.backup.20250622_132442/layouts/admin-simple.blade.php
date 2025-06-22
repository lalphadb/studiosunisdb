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
        <!-- Header simple -->
        <header class="bg-gray-800 shadow">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold text-white">StudiosUnisDB Admin</h1>
                        @if(auth()->check() && auth()->user()->ecole)
                            <span class="ml-4 text-sm text-blue-300">{{ auth()->user()->ecole->nom }}</span>
                        @endif
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-300">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-300 hover:text-white">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Navigation simple -->
        <nav class="bg-gray-700">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex space-x-8 py-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-white hover:text-gray-300">Dashboard</a>
                    <a href="{{ route('admin.users.index') }}" class="text-white hover:text-gray-300">Utilisateurs</a>
                    <a href="{{ route('admin.ecoles.index') }}" class="text-white hover:text-gray-300">Écoles</a>
                    <a href="{{ route('admin.ceintures.index') }}" class="text-white hover:text-gray-300">Ceintures</a>
                </div>
            </div>
        </nav>

        <!-- Contenu principal -->
        <main class="py-6">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
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
</body>
</html>
