<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StudiosDB - Connexion</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-900 text-white min-h-screen flex items-center justify-center">
    <div class="bg-slate-800 p-8 rounded-xl border border-slate-700 w-full max-w-md">
        <div class="text-center mb-6">
            <div class="text-4xl mb-2">🥋</div>
            <h1 class="text-2xl font-bold">StudiosDB</h1>
            <p class="text-slate-400">Connexion à votre compte</p>
        </div>
        
        @if ($errors->any())
            <div class="bg-red-600/20 border border-red-600 rounded-lg p-4 mb-4">
                <ul class="text-red-300 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('status'))
            <div class="bg-green-600/20 border border-green-600 rounded-lg p-4 mb-4">
                <p class="text-green-300 text-sm">{{ session('status') }}</p>
            </div>
        @endif
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium mb-2">Email</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}"
                       class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                       required autofocus>
            </div>
            
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium mb-2">Mot de passe</label>
                <input type="password" 
                       id="password" 
                       name="password"
                       class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                       required>
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="rounded bg-slate-700 border-slate-600 text-blue-600">
                    <span class="ml-2 text-sm text-slate-300">Se souvenir de moi</span>
                </label>
            </div>
            
            <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-colors">
                Se connecter
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <p class="text-slate-400 text-sm mb-4">Pas encore de compte ?</p>
            <a href="{{ route('register') }}" 
               class="inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors">
                Créer un compte
            </a>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ url('/') }}" class="text-slate-400 hover:text-white text-sm">
                ← Retour à l'accueil
            </a>
        </div>
        </div>
    </div>
</body>
</html>
