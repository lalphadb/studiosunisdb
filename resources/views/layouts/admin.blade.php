<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - StudiosDB Enterprise</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        window.StudiosDB = window.StudiosDB || {
            version: '4.1.10.2',
            user: @json(auth()->user() ?? null),
            ecole: @json(auth()->user()?->ecole ?? null),
            role: '{{ auth()->user()?->roles?->first()?->name ?? "membre" }}',
            csrf: '{{ csrf_token() }}',
            baseUrl: '{{ url("/") }}'
        };
    </script>
</head>
<body class="bg-slate-900 text-white">
    @auth
        <div class="flex min-h-screen">
            @include('partials.admin-navigation')
            <div class="flex-1 flex flex-col">
                <header class="bg-slate-800 border-b border-slate-700 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-white">
                                @yield('title', 'Dashboard Admin')
                            </h2>
                            <p class="text-slate-400 text-sm">
                                @yield('subtitle', 'StudiosDB Enterprise v4.1.10.2')
                            </p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <p class="text-white text-sm font-medium">{{ auth()->user()->name }}</p>
                                <p class="text-slate-400 text-xs">
                                    {{ auth()->user()->roles?->first()?->name ?? 'membre' }}
                                </p>
                            </div>
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-bold">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </header>
                <main class="flex-1 p-6 overflow-y-auto">
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-emerald-500/20 border border-emerald-500/30 rounded-lg">
                            <p class="text-emerald-200">{{ session('success') }}</p>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-500/20 border border-red-500/30 rounded-lg">
                            <p class="text-red-200">{{ session('error') }}</p>
                        </div>
                    @endif
                    @yield('content')
                </main>
            </div>
        </div>
    @else
        <div class="min-h-screen flex items-center justify-center bg-slate-900">
            <div class="bg-red-500/20 border border-red-500/30 rounded-lg p-6 max-w-md">
                <p class="text-red-200 text-center">
                    Vous devez être connecté pour accéder à cette page.
                </p>
                <div class="mt-4 text-center">
                    <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        Se connecter
                    </a>
                </div>
            </div>
        </div>
    @endauth
</body>
</html>
