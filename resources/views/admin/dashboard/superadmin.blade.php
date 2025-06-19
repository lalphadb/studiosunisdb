@extends('layouts.admin')

@section('title', 'Dashboard SuperAdmin')

@section('content')
<div class="min-h-screen bg-slate-900 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-white mb-8">🔥 Dashboard SuperAdmin</h2>
        
        <!-- Statistiques globales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-slate-800 rounded-lg p-6">
                <h3 class="text-lg font-medium text-white mb-2">🏢 Écoles Actives</h3>
                <p class="text-3xl font-bold text-blue-400">{{ $stats['total_ecoles'] }}</p>
            </div>
            
            <div class="bg-slate-800 rounded-lg p-6">
                <h3 class="text-lg font-medium text-white mb-2">👥 Membres Total</h3>
                <p class="text-3xl font-bold text-green-400">{{ $stats['total_membres'] }}</p>
            </div>
            
            <div class="bg-slate-800 rounded-lg p-6">
                <h3 class="text-lg font-medium text-white mb-2">📚 Cours Actifs</h3>
                <p class="text-3xl font-bold text-yellow-400">{{ $stats['total_cours'] }}</p>
            </div>
            
            <div class="bg-slate-800 rounded-lg p-6">
                <h3 class="text-lg font-medium text-white mb-2">🥋 Instructeurs</h3>
                <p class="text-3xl font-bold text-purple-400">{{ $stats['total_instructeurs'] }}</p>
            </div>
        </div>
        
        <!-- Activité récente -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-slate-800 rounded-lg p-6">
                <h3 class="text-lg font-medium text-white mb-2">Nouveaux Membres</h3>
                <p class="text-2xl font-bold text-green-400">{{ $stats['nouveaux_membres_mois'] }}</p>
                <p class="text-sm text-slate-400">Ce mois</p>
            </div>
            
            <div class="bg-slate-800 rounded-lg p-6">
                <h3 class="text-lg font-medium text-white mb-2">Présences</h3>
                <p class="text-2xl font-bold text-blue-400">{{ $stats['presences_semaine'] }}</p>
                <p class="text-sm text-slate-400">Cette semaine</p>
            </div>
            
            <div class="bg-slate-800 rounded-lg p-6">
                <h3 class="text-lg font-medium text-white mb-2">Revenus</h3>
                <p class="text-2xl font-bold text-green-400">${{ number_format($stats['paiements_mois'], 2) }}</p>
                <p class="text-sm text-slate-400">Ce mois</p>
            </div>
            
            <div class="bg-slate-800 rounded-lg p-6">
                <h3 class="text-lg font-medium text-white mb-2">Séminaires</h3>
                <p class="text-2xl font-bold text-purple-400">{{ $stats['seminaires_a_venir'] }}</p>
                <p class="text-sm text-slate-400">À venir</p>
            </div>
        </div>
    </div>
</div>
@endsection
