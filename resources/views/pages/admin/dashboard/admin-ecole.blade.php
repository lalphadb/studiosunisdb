@extends('layouts.admin')

@section('title', 'Dashboard Admin École')

@section('breadcrumb')
    <span class="text-emerald-400">Dashboard École</span>
@endsection

@section('content')
<!-- Header avec gradient -->
<div class="bg-gradient-to-r from-blue-500 via-purple-600 to-slate-800 rounded-xl p-6 mb-6 text-white relative overflow-hidden shadow-lg">
    <div class="relative z-10 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl p-3">
                <i class="fas fa-chart-pie text-2xl text-white"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-white">Admin École</h1>
                <p class="text-white text-opacity-90 text-base font-medium">Gestion complète de votre établissement</p>
            </div>
        </div>
    </div>
</div>

<x-admin.flash-messages />

<!-- Métriques principales -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Mes Membres -->
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:border-cyan-500 transition-all duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-sm font-medium uppercase tracking-wide">Mes Membres</p>
                <p class="text-3xl font-bold text-cyan-400">{{ $stats['membres'] ?? 0 }}</p>
                <p class="text-slate-500 text-xs mt-1">0 actifs</p>
            </div>
            <div class="bg-cyan-500 bg-opacity-20 p-3 rounded-lg">
                <i class="fas fa-users text-cyan-400 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Nouveaux ce mois -->
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:border-blue-500 transition-all duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-sm font-medium uppercase tracking-wide">Nouveaux ce mois</p>
                <p class="text-3xl font-bold text-blue-400">{{ $stats['nouveaux'] ?? 0 }}</p>
                <p class="text-slate-500 text-xs mt-1">Jul 2025</p>
            </div>
            <div class="bg-blue-500 bg-opacity-20 p-3 rounded-lg">
                <i class="fas fa-user-plus text-blue-400 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Mes Cours -->
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:border-purple-500 transition-all duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-sm font-medium uppercase tracking-wide">Mes Cours</p>
                <p class="text-3xl font-bold text-purple-400">{{ $stats['cours'] ?? 0 }}</p>
                <p class="text-slate-500 text-xs mt-1">0 actifs</p>
            </div>
            <div class="bg-purple-500 bg-opacity-20 p-3 rounded-lg">
                <i class="fas fa-book-open text-purple-400 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Revenus Mois -->
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:border-yellow-500 transition-all duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-sm font-medium uppercase tracking-wide">Revenus Mois</p>
                <p class="text-3xl font-bold text-yellow-400">${{ $stats['revenus'] ?? 0 }}</p>
                <p class="text-slate-500 text-xs mt-1">Estimation</p>
            </div>
            <div class="bg-yellow-500 bg-opacity-20 p-3 rounded-lg">
                <i class="fas fa-dollar-sign text-yellow-400 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Section nouveaux membres -->
<div class="bg-slate-800 border border-slate-700 rounded-xl p-6">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            <div class="bg-cyan-500 bg-opacity-20 p-2 rounded-lg">
                <i class="fas fa-users text-cyan-400"></i>
            </div>
            <h3 class="text-xl font-semibold text-slate-200">Nouveaux Membres</h3>
        </div>
        
        <a href="{{ route('admin.users.create') }}" 
           class="bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2">
            <i class="fas fa-plus text-sm"></i>
            <span>Ajouter Membre</span>
        </a>
    </div>
    
    <!-- Empty state pour nouveaux membres -->
    <div class="text-center py-12">
        <div class="mx-auto w-16 h-16 bg-slate-700 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-users text-slate-400 text-2xl"></i>
        </div>
        <h3 class="text-lg font-semibold text-slate-300 mb-2">Aucun nouveau membre récemment</h3>
        <p class="text-slate-500 mb-6 max-w-md mx-auto">Les nouveaux membres apparaîtront ici</p>
    </div>
</div>
@endsection
