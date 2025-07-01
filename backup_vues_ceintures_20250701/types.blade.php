@extends('layouts.admin')
@section('title', 'Types de Ceintures')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-orange-500/15 via-orange-600/20 to-red-500/15 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">🥋 Types de Ceintures</h1>
                <p class="text-orange-100 text-lg">Système de grades Kyu et Dan</p>
            </div>
            <a href="{{ route('admin.ceintures.index') }}" 
               class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-xl font-medium transition duration-200">
                Retour aux Ceintures
            </a>
        </div>
    </div>

    <!-- Types Kyu et Dan -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Ceintures Kyu -->
        <div class="bg-slate-800/40 backdrop-blur-xl/40 backdrop-blur-xl rounded-xl border border-slate-700/30/30/20overflow-hidden">
            <div class="bg-yellow-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-slate-100 tracking-tight flex items-center gap-3">Ceintures Kyu (Grades inférieurs)</h3>
            </div>
            <div class="p-6">
                <p class="text-slate-300 mb-4">Les grades Kyu vont du plus élevé (10e Kyu) au plus bas (1er Kyu), juste avant la ceinture noire.</p>
                <div class="space-y-2">
                    <div class="text-sm text-slate-400">• Ordres 1-10</div>
                    <div class="text-sm text-slate-400">• Couleurs: blanc, jaune, orange, vert, bleu, marron</div>
                    <div class="text-sm text-slate-400">• Débutants à avancés</div>
                </div>
            </div>
        </div>

        <!-- Ceintures Dan -->
        <div class="bg-slate-800/40 backdrop-blur-xl/40 backdrop-blur-xl rounded-xl border border-slate-700/30/30/20overflow-hidden">
            <div class="bg-red-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-slate-100 tracking-tight flex items-center gap-3">Ceintures Dan (Grades supérieurs)</h3>
            </div>
            <div class="p-6">
                <p class="text-slate-300 mb-4">Les grades Dan commencent à la ceinture noire (1er Dan) et montent jusqu'aux plus hauts niveaux.</p>
                <div class="space-y-2">
                    <div class="text-sm text-slate-400">• Ordres 11+</div>
                    <div class="text-sm text-slate-400">• Couleurs: noir, rouge (hauts grades)</div>
                    <div class="text-sm text-slate-400">• Experts et maîtres</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="bg-slate-800/40 backdrop-blur-xl/40 backdrop-blur-xl rounded-xl border border-slate-700/30/30/20p-6">
        <h3 class="text-lg font-semibold text-slate-100 tracking-tight flex items-center gap-3">Actions</h3>
        <div class="flex space-x-4">
            <a href="{{ route('admin.ceintures.create') }}" 
               class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-xl">
                Créer une nouvelle ceinture
            </a>
            <a href="{{ route('admin.ceintures.index') }}" 
               class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-xl">
                Voir toutes les ceintures
            </a>
        </div>
    </div>
</div>
@endsection
