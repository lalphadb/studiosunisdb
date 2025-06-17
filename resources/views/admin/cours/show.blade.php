@extends('layouts.admin')

@section('title', 'Détails du Cours')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-white">📚 {{ $cours->nom }}</h1>
            <p class="text-slate-400 mt-1">Détails du cours de karaté</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.cours.edit', $cours) }}" 
               class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors flex items-center space-x-2">
                <span>✏️</span>
                <span>Modifier</span>
            </a>
            <a href="{{ route('admin.cours.index') }}" 
               class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors flex items-center space-x-2">
                <span>←</span>
                <span>Retour</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-2">
            <div class="bg-slate-800 border border-slate-700 rounded-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-white mb-4 border-b border-slate-700 pb-3">
                    Informations du Cours
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <span class="text-slate-400 text-sm font-medium">École:</span>
                            <div class="text-white font-semibold">{{ $cours->ecole->nom ?? 'N/A' }}</div>
                        </div>
                        
                        <div>
                            <span class="text-slate-400 text-sm font-medium">Type:</span>
                            <div class="mt-1">
                                <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-medium">
                                    {{ ucfirst($cours->type_cours) }}
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <span class="text-slate-400 text-sm font-medium">Niveau requis:</span>
                            <div class="text-white font-semibold">{{ $cours->niveau_requis ?? 'Tous niveaux' }}</div>
                        </div>
                        
                        <div>
                            <span class="text-slate-400 text-sm font-medium">Tranche d'âge:</span>
                            <div class="text-white font-semibold">{{ $cours->age_min }} - {{ $cours->age_max }} ans</div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <span class="text-slate-400 text-sm font-medium">Capacité:</span>
                            <div class="text-white font-semibold">{{ $cours->capacite_max }} places</div>
                        </div>
                        
                        <div>
                            <span class="text-slate-400 text-sm font-medium">Durée:</span>
                            <div class="text-white font-semibold">{{ $cours->duree_minutes }} minutes</div>
                        </div>
                        
                        <div>
                            <span class="text-slate-400 text-sm font-medium">Prix mensuel:</span>
                            <div class="text-white font-semibold">
                                @if($cours->prix_mensuel)
                                    ${{ number_format($cours->prix_mensuel, 2) }}
                                @else
                                    Non défini
                                @endif
                            </div>
                        </div>
                        
                        <div>
                            <span class="text-slate-400 text-sm font-medium">Salle:</span>
                            <div class="text-white font-semibold">{{ $cours->salle ?? 'Non spécifiée' }}</div>
                        </div>
                    </div>
                </div>
                
                @if($cours->description)
                <div class="mt-6 pt-6 border-t border-slate-700">
                    <span class="text-slate-400 text-sm font-medium">Description:</span>
                    <p class="text-white mt-2 leading-relaxed">{{ $cours->description }}</p>
                </div>
                @endif
            </div>

            <!-- Instructeurs -->
            @if($cours->instructeurPrincipal)
            <div class="bg-slate-800 border border-slate-700 rounded-lg p-6">
                <h3 class="text-xl font-semibold text-white mb-4 border-b border-slate-700 pb-3">
                    👨‍🏫 Instructeur(s)
                </h3>
                
                <div class="space-y-3">
                    <div>
                        <span class="text-slate-400 text-sm font-medium">Instructeur principal:</span>
                        <div class="text-white font-semibold">{{ $cours->instructeurPrincipal->name }}</div>
                    </div>
                    
                    @if($cours->instructeurAssistant)
                    <div>
                        <span class="text-slate-400 text-sm font-medium">Instructeur assistant:</span>
                        <div class="text-white font-semibold">{{ $cours->instructeurAssistant->name }}</div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar - Statistiques et Statut -->
        <div class="space-y-6">
            <!-- Statistiques -->
            <div class="bg-slate-800 border border-slate-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-white mb-4 border-b border-slate-700 pb-3">
                    📊 Statistiques
                </h3>
                
                <div class="space-y-4">
                    <div class="text-center bg-slate-900 rounded-lg p-4">
                        <div class="text-3xl font-bold text-blue-400">{{ $stats['total_inscriptions'] ?? 0 }}</div>
                        <div class="text-slate-400 text-sm">Inscriptions actives</div>
                    </div>
                    
                    <div class="text-center bg-slate-900 rounded-lg p-4">
                        <div class="text-3xl font-bold text-green-400">{{ $stats['places_disponibles'] ?? 0 }}</div>
                        <div class="text-slate-400 text-sm">Places disponibles</div>
                    </div>
                    
                    <div class="text-center bg-slate-900 rounded-lg p-4">
                        <div class="text-3xl font-bold text-purple-400">${{ number_format($stats['revenus_mensuels'] ?? 0, 2) }}</div>
                        <div class="text-slate-400 text-sm">Revenus mensuels</div>
                    </div>
                </div>
            </div>

            <!-- Statut -->
            <div class="bg-slate-800 border border-slate-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-white mb-4 border-b border-slate-700 pb-3">
                    🏷️ Statut
                </h3>
                
                <div class="text-center">
                    @php
                        $statusClasses = [
                            'actif' => 'bg-green-600',
                            'inactif' => 'bg-slate-600',
                            'complet' => 'bg-yellow-600',
                            'annule' => 'bg-red-600'
                        ];
                        $statusClass = $statusClasses[$cours->status] ?? 'bg-slate-600';
                    @endphp
                    <span class="{{ $statusClass }} text-white px-6 py-3 rounded-lg text-lg font-semibold">
                        {{ ucfirst($cours->status) }}
                    </span>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-slate-800 border border-slate-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-white mb-4 border-b border-slate-700 pb-3">
                    ⚡ Actions rapides
                </h3>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.cours.edit', $cours) }}" 
                       class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-3 rounded-lg font-medium transition-colors text-center block">
                        ✏️ Modifier le cours
                    </a>
                    
                    <button class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-medium transition-colors opacity-60" disabled>
                        📅 Gérer les horaires
                    </button>
                    
                    <button class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg font-medium transition-colors opacity-60" disabled>
                        👥 Voir les inscriptions
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
