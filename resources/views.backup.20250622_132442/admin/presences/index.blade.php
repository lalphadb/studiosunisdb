@extends('layouts.admin')

@section('title', 'Gestion des Présences')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-slate-800 rounded-lg p-6 mb-6 border border-slate-700">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-100">Gestion des Présences 🎯</h1>
                    <p class="text-gray-400 mt-1">Suivi des présences des membres aux cours</p>
                </div>
                <a href="{{ route('admin.presences.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                    <i class="fas fa-plus mr-2"></i>Nouvelle Présence
                </a>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="bg-slate-800 overflow-hidden shadow-xl sm:rounded-lg border border-slate-700">
            <div class="p-6 text-gray-100">
                
                @if(isset($presences) && $presences->count() > 0)
                    <!-- Statistiques -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                        <div class="bg-green-600 rounded-lg p-4 text-center">
                            <i class="fas fa-check-circle text-2xl mb-2"></i>
                            <p class="text-2xl font-bold">{{ $presences->where('statut', 'present')->count() }}</p>
                            <p class="text-sm">Présents</p>
                        </div>
                        <div class="bg-red-600 rounded-lg p-4 text-center">
                            <i class="fas fa-times-circle text-2xl mb-2"></i>
                            <p class="text-2xl font-bold">{{ $presences->where('statut', 'absent')->count() }}</p>
                            <p class="text-sm">Absents</p>
                        </div>
                        <div class="bg-orange-600 rounded-lg p-4 text-center">
                            <i class="fas fa-clock text-2xl mb-2"></i>
                            <p class="text-2xl font-bold">{{ $presences->where('statut', 'retard')->count() }}</p>
                            <p class="text-sm">Retards</p>
                        </div>
                        <div class="bg-blue-600 rounded-lg p-4 text-center">
                            <i class="fas fa-user-check text-2xl mb-2"></i>
                            <p class="text-2xl font-bold">{{ $presences->count() }}</p>
                            <p class="text-sm">Total</p>
                        </div>
                    </div>

                    <!-- Liste des présences -->
                    <div class="space-y-3">
                        @foreach($presences->take(10) as $presence)
                            <div class="bg-slate-700 p-4 rounded-lg flex justify-between items-center">
                                <div>
                                    <p class="font-medium">{{ $presence->membre->prenom }} {{ $presence->membre->nom }}</p>
                                    <p class="text-sm text-gray-400">{{ $presence->cours->nom }} - {{ \Carbon\Carbon::parse($presence->date_presence)->format('d/m/Y') }}</p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-sm font-medium
                                    @if($presence->statut == 'present') bg-green-600 text-white
                                    @elseif($presence->statut == 'absent') bg-red-600 text-white  
                                    @elseif($presence->statut == 'retard') bg-orange-600 text-white
                                    @else bg-blue-600 text-white @endif">
                                    {{ ucfirst($presence->statut) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- État vide -->
                    <div class="text-center py-16">
                        <i class="fas fa-clipboard-list text-6xl text-blue-400 mb-6"></i>
                        <h3 class="text-2xl font-bold mb-4">Module Présences</h3>
                        <p class="text-gray-400 mb-8">Aucune présence enregistrée pour le moment.</p>
                        
                        <div class="flex justify-center space-x-4">
                            <a href="{{ route('admin.presences.create') }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium">
                                <i class="fas fa-plus mr-2"></i>Nouvelle Présence
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
