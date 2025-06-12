<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'StudiosUnisDB') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="bg-slate-900 text-white antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-slate-800 shadow-xl overflow-hidden sm:rounded-lg border border-slate-700">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
