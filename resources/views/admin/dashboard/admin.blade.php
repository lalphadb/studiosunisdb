@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="min-h-screen bg-slate-900 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-white mb-8">👑 Dashboard Admin - {{ $ecole->nom }}</h2>
        
        <!-- Statistiques école -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-slate-800 rounded-lg p-6">
                <h3 class="text-lg font-medium text-white mb-2">👥 Membres</h3>
                <p class="text-3xl font-bold text-green-400">{{ $stats['total_membres'] }}</p>
            </div>
            
            <div class="bg-slate-800 rounded-lg p-6">
                <h3 class="text-lg font-medium text-white mb-2">📚 Cours</h3>
                <p class="text-3xl font-bold text-blue-400">{{ $stats['total_cours'] }}</p>
            </div>
            
            <div class="bg-slate-800 rounded-lg p-6">
                <h3 class="text-lg font-medium text-white mb-2">💰 Revenus Mois</h3>
                <p class="text-3xl font-bold text-green-400">${{ number_format($stats['paiements_mois'], 2) }}</p>
            </div>
            
            <div class="bg-slate-800 rounded-lg p-6">
                <h3 class="text-lg font-medium text-white mb-2">🎓 Séminaires</h3>
                <p class="text-3xl font-bold text-purple-400">{{ $stats['seminaires_a_venir'] }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
