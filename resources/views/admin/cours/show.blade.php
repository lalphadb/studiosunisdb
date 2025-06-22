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
                            <span class="text-slate-400 text-sm font-medium">Niveau:</span>
                            <div class="mt-1">
                                @php
                                    $niveauClasses = [
                                        'debutant' => 'bg-green-600',
                                        'intermediaire' => 'bg-yellow-600',
                                        'avance' => 'bg-red-600',
                                        'tous_niveaux' => 'bg-blue-600'
                                    ];
                                    $niveauClass = $niveauClasses[$cours->niveau] ?? 'bg-slate-600';
                                @endphp
                                <span class="{{ $niveauClass }} text-white px-3 py-1 rounded-full text-sm font-medium">
                                    {{ ucfirst(str_replace('_', ' ', $cours->niveau)) }}
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <span class="text-slate-400 text-sm font-medium">Instructeur:</span>
                            <div class="text-white font-semibold">{{ $cours->instructeur ?? 'Non assigné' }}</div>
                        </div>
                        
                        <div>
                            <span class="text-slate-400 text-sm font-medium">Durée:</span>
                            <div class="text-white font-semibold">{{ $cours->duree_minutes }} minutes</div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <span class="text-slate-400 text-sm font-medium">Capacité:</span>
                            <div class="text-white font-semibold">{{ $cours->statut_occupation }}</div>
                        </div>
                        
                        <div>
                            <span class="text-slate-400 text-sm font-medium">Prix session:</span>
                            <div class="text-white font-semibold">
                                @if($cours->prix)
                                    ${{ number_format($cours->prix, 2) }}
                                @else
                                    Non défini
                                @endif
                            </div>
                        </div>
                        
                        <div>
                            <span class="text-slate-400 text-sm font-medium">Statut:</span>
                            <div class="mt-1">
                                <span class="{{ $cours->active ? 'bg-green-600' : 'bg-slate-600' }} text-white px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $cours->active ? 'Actif' : 'Inactif' }}
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <span class="text-slate-400 text-sm font-medium">Créé le:</span>
                            <div class="text-white font-semibold">{{ $cours->created_at->format('d/m/Y à H:i') }}</div>
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

            <!-- Horaires (si existants) -->
            @if($cours->horaires->count() > 0)
            <div class="bg-slate-800 border border-slate-700 rounded-lg p-6">
                <h3 class="text-xl font-semibold text-white mb-4 border-b border-slate-700 pb-3">
                    📅 Horaires
                </h3>
                
                <div class="space-y-3">
                    @foreach($cours->horaires as $horaire)
                    <div class="flex items-center justify-between bg-slate-900 rounded-lg p-3">
                        <div>
                            <span class="text-white font-medium">{{ ucfirst($horaire->jour_semaine) }}</span>
                            <span class="text-slate-400 ml-2">{{ $horaire->heure_debut->format('H:i') }} - {{ $horaire->heure_fin->format('H:i') }}</span>
                        </div>
                        <span class="{{ $horaire->active ? 'bg-green-600' : 'bg-slate-600' }} text-white px-2 py-1 rounded text-xs">
                            {{ $horaire->active ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar - Statistiques -->
        <div class="space-y-6">
            <!-- Statistiques -->
            <div class="bg-slate-800 border border-slate-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-white mb-4 border-b border-slate-700 pb-3">
                    📊 Statistiques
                </h3>
                
                <div class="space-y-4">
                    <div class="text-center bg-slate-900 rounded-lg p-4">
                        <div class="text-3xl font-bold text-blue-400">{{ $stats['total_inscriptions'] }}</div>
                        <div class="text-slate-400 text-sm">Inscriptions actives</div>
                    </div>
                    
                    <div class="text-center bg-slate-900 rounded-lg p-4">
                        <div class="text-3xl font-bold text-green-400">{{ $stats['places_disponibles'] }}</div>
                        <div class="text-slate-400 text-sm">Places disponibles</div>
                    </div>
                    
                    <div class="text-center bg-slate-900 rounded-lg p-4">
                        <div class="text-3xl font-bold text-purple-400">${{ number_format($stats['revenus_session'], 2) }}</div>
                        <div class="text-slate-400 text-sm">Revenus session</div>
                    </div>
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

            <!-- Suppression -->
            @if(auth()->user()->hasRole('superadmin'))
            <div class="bg-slate-800 border border-slate-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-red-400 mb-4 border-b border-slate-700 pb-3">
                    ⚠️ Zone dangereuse
                </h3>
                
                <form method="POST" action="{{ route('admin.cours.destroy', $cours) }}" 
                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ? Cette action est irréversible.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg font-medium transition-colors">
                        🗑️ Supprimer le cours
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
