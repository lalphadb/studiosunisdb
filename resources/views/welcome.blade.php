<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StudiosUnisDB - Gestion des Écoles de Karaté</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-white">
    <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-slate-900 to-slate-800">
        <div class="max-w-4xl mx-auto text-center px-6">
            <!-- Logo/Titre -->
            <div class="mb-8">
                <h1 class="text-6xl font-bold text-white mb-4">
                    Studios<span class="text-blue-400">Unis</span>DB
                </h1>
                <p class="text-xl text-slate-300">
                    Système de gestion pour les 22 écoles de karaté du Québec
                </p>
            </div>

            <!-- Fonctionnalités -->
            <div class="grid md:grid-cols-3 gap-8 mb-12">
                <div class="bg-slate-800 p-6 rounded-lg">
                    <div class="text-blue-400 text-4xl mb-4">🏫</div>
                    <h3 class="text-xl font-semibold mb-2">Gestion des Écoles</h3>
                    <p class="text-slate-400">22 Studios Unis du Québec</p>
                </div>
                <div class="bg-slate-800 p-6 rounded-lg">
                    <div class="text-green-400 text-4xl mb-4">👥</div>
                    <h3 class="text-xl font-semibold mb-2">Gestion des Membres</h3>
                    <p class="text-slate-400">Profils complets et inscriptions</p>
                </div>
                <div class="bg-slate-800 p-6 rounded-lg">
                    <div class="text-yellow-400 text-4xl mb-4">🥋</div>
                    <h3 class="text-xl font-semibold mb-2">Cours & Ceintures</h3>
                    <p class="text-slate-400">Planning et progressions</p>
                </div>
            </div>

            <!-- Boutons d'action -->
        	    <div class="space-x-4">
                	@auth
                    	<a href="{{ route('admin.dashboard') }}" 
                       	class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition duration-200">
                        	Accéder au Dashboard
                    	</a>
                	@else
                    	<a href="{{ route('login') }}" 
                      	 class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition duration-200">
                        	Connexion Admin
                    	</a>
                    	<a href="{{ route('register') }}" 
                       class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg transition duration-200">
                        Créer un compte
                    </a>
                @endauth
            </div>
	<!-- Footer -->
                 @include('partials.footer')
