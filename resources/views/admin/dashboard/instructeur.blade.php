@extends('layouts.admin')

@section('title', 'Dashboard Instructeur')

@section('content')
<div class="min-h-screen bg-slate-900 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-white mb-8">🥋 Dashboard Instructeur - {{ $ecole->nom }}</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-slate-800 rounded-lg p-6">
                <h3 class="text-lg font-medium text-white mb-2">📚 Mes Cours</h3>
                <p class="text-3xl font-bold text-blue-400">{{ $stats['mes_cours'] }}</p>
            </div>
            
            <div class="bg-slate-800 rounded-lg p-6">
                <h3 class="text-lg font-medium text-white mb-2">👥 Mes Élèves</h3>
                <p class="text-3xl font-bold text-green-400">{{ $stats['mes_eleves'] }}</p>
            </div>
            
            <div class="bg-slate-800 rounded-lg p-6">
                <h3 class="text-lg font-medium text-white mb-2">✅ Présences Semaine</h3>
                <p class="text-3xl font-bold text-yellow-400">{{ $stats['presences_semaine'] }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
