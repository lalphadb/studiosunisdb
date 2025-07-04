<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'StudiosDB Admin')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background-color: #0f172a !important;
            color: #ffffff !important;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-slate-900">
        <!-- Sidebar Navigation -->
        @include('partials.admin-navigation')

        <!-- Main Content -->
        <main class="ml-64 min-h-screen">
            <div class="p-6">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts additionnels -->
    <script>
        // Auto-scroll to top
        window.scrollTo(0, 0);
        
        // Ensure full height
        document.documentElement.style.height = '100%';
        document.body.style.height = '100%';
    </script>
</body>
</html>
