@extends('layouts.app')

@section('content')
<!-- Navigation -->
<nav class="bg-slate-800 border-b border-slate-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center space-x-4">
                <span class="text-2xl">🥋</span>
                <h1 class="text-xl font-bold">StudiosDB</h1>
            </div>
            
            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-blue-400 hover:text-blue-300 text-sm">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-slate-300 hover:text-white text-sm">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300 text-sm">Connexion</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded text-sm">S'inscrire</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<div class="bg-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="relative bg-gradient-to-r from-blue-600 via-purple-600 to-slate-800 rounded-2xl p-8 md:p-16 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-transparent to-slate-900/40"></div>
            
            <div class="relative z-10 text-center">
                <h1 class="text-3xl md:text-5xl lg:text-6xl font-bold mb-6">
                    Gestion d'Écoles de Karaté
                </h1>
                <p class="text-lg md:text-xl lg:text-2xl text-white/90 mb-8 max-w-3xl mx-auto">
                    Système complet pour gérer les membres, cours, ceintures et paiements
                </p>
                
                @guest
                    <div class="flex flex-col sm:flex-row gap-4 justify-center max-w-md mx-auto">
                        <a href="{{ route('register') }}" 
                           class="bg-white text-blue-600 hover:bg-gray-100 px-6 py-3 rounded-lg text-lg font-semibold transition-colors">
                            Créer un compte
                        </a>
                        <a href="{{ route('login') }}" 
                           class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg text-lg font-semibold transition-colors border border-white/20">
                            Se connecter
                        </a>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</div>

<!-- Fonctionnalités -->
<div class="py-16 bg-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center mb-12">Fonctionnalités</h2>
        
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-slate-800 p-6 rounded-xl border border-slate-700 hover:border-slate-600 transition-colors">
                <div class="text-3xl mb-4">👤</div>
                <h3 class="text-xl font-bold mb-3">Gestion des Membres</h3>
                <p class="text-slate-300">Inscriptions, profils complets, suivi des progressions</p>
            </div>
            
            <div class="bg-slate-800 p-6 rounded-xl border border-slate-700 hover:border-slate-600 transition-colors">
                <div class="text-3xl mb-4">📚</div>
                <h3 class="text-xl font-bold mb-3">Cours et Séminaires</h3>
                <p class="text-slate-300">Planning, inscriptions, présences automatisées</p>
            </div>
            
            <div class="bg-slate-800 p-6 rounded-xl border border-slate-700 hover:border-slate-600 transition-colors">
                <div class="text-3xl mb-4">🥋</div>
                <h3 class="text-xl font-bold mb-3">Système de Ceintures</h3>
                <p class="text-slate-300">Suivi des progressions, examens, certifications</p>
            </div>
            
            <div class="bg-slate-800 p-6 rounded-xl border border-slate-700 hover:border-slate-600 transition-colors">
                <div class="text-3xl mb-4">💰</div>
                <h3 class="text-xl font-bold mb-3">Gestion Financière</h3>
                <p class="text-slate-300">Paiements, factures, rapports automatisés</p>
            </div>
            
            <div class="bg-slate-800 p-6 rounded-xl border border-slate-700 hover:border-slate-600 transition-colors">
                <div class="text-3xl mb-4">🏫</div>
                <h3 class="text-xl font-bold mb-3">Multi-École</h3>
                <p class="text-slate-300">Gestion de plusieurs dojos, permissions granulaires</p>
            </div>
            
            <div class="bg-slate-800 p-6 rounded-xl border border-slate-700 hover:border-slate-600 transition-colors">
                <div class="text-3xl mb-4">📊</div>
                <h3 class="text-xl font-bold mb-3">Rapports et Analyses</h3>
                <p class="text-slate-300">Statistiques détaillées, tableaux de bord</p>
            </div>
        </div>
    </div>
</div>
@endsection
