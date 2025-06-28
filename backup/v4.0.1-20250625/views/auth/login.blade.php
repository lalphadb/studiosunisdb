<!DOCTYPE html>
<html lang="fr" class="h-full bg-gray-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion - StudiosUnisDB</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-900 text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="text-center mb-6">
            <div class="flex justify-center mb-4">
                <div class="flex h-16 w-16 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 shadow-lg">
                    <span class="text-2xl font-bold text-white">🥋</span>
                </div>
            </div>
            <h2 class="text-3xl font-bold text-white">StudiosUnisDB</h2>
            <p class="text-sm text-slate-300 mt-1">Connexion au système de gestion</p>
            <p class="text-xs text-blue-300 font-medium">22 Studios Unis du Québec</p>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-slate-800 shadow-xl overflow-hidden sm:rounded-lg border border-slate-700">
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-400">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block font-medium text-sm text-slate-300">Adresse courriel</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" 
                           class="block mt-1 w-full bg-slate-700 border-slate-600 text-white placeholder-slate-400 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm px-3 py-2"
                           required autofocus placeholder="votre@email.com">
                    @error('email')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="block font-medium text-sm text-slate-300">Mot de passe</label>
                    <input id="password" type="password" name="password" 
                           class="block mt-1 w-full bg-slate-700 border-slate-600 text-white placeholder-slate-400 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm px-3 py-2"
                           required placeholder="••••••••">
                    @error('password')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-slate-600 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-slate-700">
                        <span class="ml-2 text-sm text-slate-300">Se souvenir de moi</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-blue-400 hover:text-blue-300">Mot de passe oublié?</a>
                    @endif
                </div>

                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013 3v1"/>
                    </svg>
                    SE CONNECTER
                </button>
            </form>

            <div class="text-center mt-6 pt-4 border-t border-slate-600">
                <p class="text-xs text-slate-500">StudiosUnisDB v4.0-FINAL &copy; {{ date('Y') }}</p>
                <p class="text-xs text-slate-600 mt-1">Système de gestion pour écoles de karaté</p>
            </div>
        </div>
    </div>
</body>
</html>
