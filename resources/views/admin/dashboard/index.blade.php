@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold text-white mb-2">📊 Dashboard StudiosUnisDB</h1>
    <p class="text-slate-400 text-lg">Vue d'ensemble du réseau Studios Unis du Québec</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="card-bg p-6 rounded-xl shadow-xl">
        <div class="flex items-center">
            <div class="text-4xl mr-4">🏢</div>
            <div>
                <p class="text-slate-400 font-medium">Total Écoles</p>
                <p class="text-3xl font-bold text-white">{{ $stats['total_ecoles'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    
    <div class="card-bg p-6 rounded-xl shadow-xl">
        <div class="flex items-center">
            <div class="text-4xl mr-4">👥</div>
            <div>
                <p class="text-slate-400 font-medium">Total Membres</p>
                <p class="text-3xl font-bold text-white">{{ $stats['total_membres'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    
    <div class="card-bg p-6 rounded-xl shadow-xl">
        <div class="flex items-center">
            <div class="text-4xl mr-4">✅</div>
            <div>
                <p class="text-slate-400 font-medium">Membres Actifs</p>
                <p class="text-3xl font-bold text-white">{{ $stats['membres_actifs'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    
    <div class="card-bg p-6 rounded-xl shadow-xl">
        <div class="flex items-center">
            <div class="text-4xl mr-4">📚</div>
            <div>
                <p class="text-slate-400 font-medium">Cours Actifs</p>
                <p class="text-3xl font-bold text-white">{{ $stats['total_cours'] ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card-bg rounded-xl shadow-xl p-6">
    <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
        <span class="text-3xl mr-3">👤</span>
        Informations de Session
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <p class="text-slate-400 mb-1">Utilisateur connecté</p>
            <p class="text-white font-bold text-xl">{{ auth()->user()->name }}</p>
        </div>
        <div>
            <p class="text-slate-400 mb-1">Email</p>
            <p class="text-white font-mono text-lg">{{ auth()->user()->email }}</p>
        </div>
        @if(auth()->user()->ecole_id)
        <div>
            <p class="text-slate-400 mb-1">École assignée</p>
            <p class="text-blue-400 font-bold text-lg">ID: {{ auth()->user()->ecole_id }}</p>
        </div>
        @else
        <div>
            <p class="text-slate-400 mb-1">Niveau d'accès</p>
            <p class="text-green-400 font-bold text-lg">🔥 SuperAdmin - Toutes écoles</p>
        </div>
        @endif
        <div>
            <p class="text-slate-400 mb-1">Statut</p>
            <span class="px-4 py-2 rounded-full text-sm font-bold" style="background-color: rgba(34, 197, 94, 0.2); border: 1px solid #16a34a; color: #4ade80;">
                {{ ucfirst(auth()->user()->statut) }}
            </span>
        </div>
    </div>
    
    <div class="mt-8 flex space-x-4">
        <a href="/admin/ecoles" class="hover-bg px-6 py-3 rounded-lg font-bold transition-all text-white border border-slate-600">
            🏢 Gérer Écoles
        </a>
        <a href="/admin/membres" class="hover-bg px-6 py-3 rounded-lg font-bold transition-all text-white border border-slate-600">
            👥 Gérer Membres
        </a>
    </div>
</div>
@endsection
