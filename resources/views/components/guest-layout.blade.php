<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>StudiosUnisDB - Connexion</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-900">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-white">🥋 StudiosUnisDB</h1>
                <p class="text-gray-400">Administration Studios Unis du Québec</p>
            </div>
            <div class="w-full sm:max-w-md px-6 py-4 bg-gray-800 shadow-lg overflow-hidden sm:rounded-lg border border-gray-700">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
