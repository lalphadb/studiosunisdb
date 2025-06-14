@extends('layouts.admin')

@section('title', 'Détails Cours')

@section('content')
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold text-white mb-2">📚 {{ $cours->nom }}</h1>
            <p class="text-slate-400">Détails du cours</p>
        </div>
        <div class="flex space-x-3">
                    <a href="{{ route('admin.presences.prise-presence', $cours) }}" 
                       class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                        <i class="fas fa-clipboard-check mr-2"></i>Prise de Présence
                    </a>
            <a href="{{ route('admin.cours.edit', $cours) }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold">
                ✏️ Modifier
            </a>
            <a href="{{ route('admin.cours.index') }}" class="px-6 py-3 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition font-bold">
                ← Retour Liste
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Informations principales -->
    <div class="lg:col-span-2">
        <div class="card-bg rounded-xl shadow-xl p-6">
            <h3 class="text-xl font-bold text-white mb-6">📋 Informations du Cours</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-300 mb-2">Nom du cours</label>
                    <p class="text-white text-lg">{{ $cours->nom }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-slate-300 mb-2">École</label>
                    <p class="text-white text-lg">{{ $cours->ecole->nom ?? 'Non assignée' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-slate-300 mb-2">Instructeur</label>
                    <p class="text-white text-lg">{{ $cours->instructeur->name ?? 'Non assigné' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-slate-300 mb-2">Capacité</label>
                    <p class="text-white text-lg">{{ $cours->capacite_max }} personnes</p>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-slate-300 mb-2">Prix</label>
                    <p class="text-white text-lg">${{ number_format($cours->prix, 2) }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-slate-300 mb-2">Statut</label>
                    <span class="px-3 py-1 rounded-full text-sm font-bold" style="@if($cours->statut === 'actif') background-color: rgba(34, 197, 94, 0.2); border: 1px solid #16a34a; color: #4ade80; @else background-color: rgba(239, 68, 68, 0.2); border: 1px solid #dc2626; color: #f87171; @endif">
                        {{ ucfirst($cours->statut) }}
                    </span>
                </div>
            </div>
            
            @if($cours->description)
            <div class="mt-6">
                <label class="block text-sm font-bold text-slate-300 mb-2">Description</label>
                <p class="text-white">{{ $cours->description }}</p>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Statistiques -->
    <div>
        <div class="card-bg rounded-xl shadow-xl p-6">
            <h3 class="text-xl font-bold text-white mb-6">📊 Statistiques</h3>
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-slate-400">Inscrits</span>
                    <span class="text-white font-bold">0</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Places libres</span>
                    <span class="text-white font-bold">{{ $cours->capacite_max }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Taux occupation</span>
                    <span class="text-white font-bold">0%</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
