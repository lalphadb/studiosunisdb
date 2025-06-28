@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <x-module-header 
        module="dashboard"
        title="Gestion des Logs" 
        subtitle="Administration et monitoring du système"
    />

    <div class="mt-6 space-y-6">
        <!-- Redirection vers Exports -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <h2 class="text-xl font-bold text-white mb-4 flex items-center">
                <span class="text-2xl mr-3">📊</span>
                Exports et Conformité Loi 25
            </h2>
            
            <p class="text-slate-300 mb-4">
                Pour l'export des logs système et la conformité Loi 25, utilisez la section dédiée aux exports.
            </p>
            
            <a href="{{ route('admin.exports.index') }}" 
               class="inline-flex items-center bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg transition-colors">
                <span class="text-xl mr-3">📊</span>
                Accéder aux Exports
            </a>
        </div>

        <!-- Accès Telescope -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <h2 class="text-xl font-bold text-white mb-4 flex items-center">
                <span class="text-2xl mr-3">🔭</span>
                Monitoring en Temps Réel
            </h2>
            
            <div class="grid md:grid-cols-2 gap-4">
                <a href="/telescope" target="_blank"
                   class="flex items-center bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-lg transition-colors">
                    <span class="text-2xl mr-4">🔭</span>
                    <div>
                        <div class="font-semibold">Laravel Telescope</div>
                        <div class="text-sm text-blue-100">Dashboard de monitoring</div>
                    </div>
                </a>
                
                <a href="/telescope/requests" target="_blank"
                   class="flex items-center bg-teal-600 hover:bg-teal-700 text-white p-4 rounded-lg transition-colors">
                    <span class="text-2xl mr-4">📡</span>
                    <div>
                        <div class="font-semibold">Requêtes HTTP</div>
                        <div class="text-sm text-teal-100">Analyse des requêtes</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Informations système -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <h2 class="text-xl font-bold text-white mb-4 flex items-center">
                <span class="text-2xl mr-3">ℹ️</span>
                Informations Système
            </h2>
            
            <div class="grid md:grid-cols-3 gap-4">
                <div class="bg-green-600/10 border border-green-600/30 rounded-lg p-4 text-center">
                    <div class="text-2xl mb-2">✅</div>
                    <h3 class="font-semibold text-green-400 mb-1">Laravel</h3>
                    <p class="text-slate-400 text-sm">Logs actifs</p>
                </div>
                
                <div class="bg-blue-600/10 border border-blue-600/30 rounded-lg p-4 text-center">
                    <div class="text-2xl mb-2">🔭</div>
                    <h3 class="font-semibold text-blue-400 mb-1">Telescope</h3>
                    <p class="text-slate-400 text-sm">Monitoring disponible</p>
                </div>
                
                <div class="bg-purple-600/10 border border-purple-600/30 rounded-lg p-4 text-center">
                    <div class="text-2xl mb-2">📊</div>
                    <h3 class="font-semibold text-purple-400 mb-1">Exports</h3>
                    <p class="text-slate-400 text-sm">Conforme Loi 25</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
