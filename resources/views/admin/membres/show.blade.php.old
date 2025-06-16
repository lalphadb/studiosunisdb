@extends('layouts.admin')

@section('title', 'Profil Membre')

@section('content')
<div class="space-y-6">
    {{-- Section Progression Ceintures --}}
    <div class="bg-gray-800 rounded-lg shadow-md border border-gray-700">
        <div class="p-6 border-b border-gray-700">
            <h3 class="text-lg font-medium text-white">🥋 Progression Ceintures</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Ceinture Actuelle --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Ceinture actuelle</label>
                    @php
                        $ceintureActuelle = $membre->getCeintureActuellePourAffichage();
                    @endphp
                    
                    @if($ceintureActuelle)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                              style="background-color: {{ $ceintureActuelle->couleur }}20; 
                                     color: {{ $ceintureActuelle->couleur }}; 
                                     border: 1px solid {{ $ceintureActuelle->couleur }}40;">
                            {{ $ceintureActuelle->emoji }} {{ $ceintureActuelle->nom }}
                        </span>
                        <div class="text-xs text-gray-400 mt-1">
                            Niveau {{ $ceintureActuelle->niveau }}
                            @if($membre->derniereCeinture)
                                - Obtenue le {{ $membre->derniereCeinture->date_obtention->format('d/m/Y') }}
                            @endif
                        </div>
                    @else
                        <span class="text-gray-400">Aucune ceinture attribuée</span>
                    @endif
                </div>

                {{-- Prochaine Ceinture --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Prochaine ceinture</label>
                    @if($ceintureActuelle && $ceintureActuelle->prochaineCeinture())
                        @php $prochaine = $ceintureActuelle->prochaineCeinture(); @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border border-gray-500 text-gray-300">
                            {{ $prochaine->emoji }} {{ $prochaine->nom }} (Niveau {{ $prochaine->niveau }})
                        </span>
                    @else
                        <span class="text-gray-400">Niveau maximum atteint</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Header --}}
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">👤 {{ $membre->prenom }} {{ $membre->nom }}</h1>
                <p class="text-blue-100 text-lg">Profil membre - {{ $membre->ecole->nom ?? 'École non assignée' }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.membres.edit', $membre) }}" class="bg-yellow-500 hover:bg-yellow-600 px-4 py-2 rounded-lg font-medium transition-colors">
                    ✏️ Modifier
                </a>
                <a href="{{ route('admin.membres.index') }}" class="bg-white text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-lg font-medium transition-colors">
                    ← Retour Liste
                </a>
            </div>
        </div>
    </div>

    {{-- Informations Personnelles --}}
    <div class="bg-gray-800 rounded-lg shadow-md border border-gray-700">
        <div class="p-6 border-b border-gray-700">
            <h3 class="text-lg font-medium text-white">👤 Informations Personnelles</h3>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Nom complet :</span>
                        <span class="text-white font-medium">{{ $membre->prenom }} {{ $membre->nom }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">École :</span>
                        <span class="text-white">{{ $membre->ecole->nom ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Email :</span>
                        <span class="text-white">{{ $membre->email ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Téléphone :</span>
                        <span class="text-white">{{ $membre->telephone ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Date de naissance :</span>
                        <span class="text-white">
                            @if($membre->date_naissance)
                                {{ $membre->date_naissance->format('d/m/Y') }} ({{ $membre->age }} ans)
                            @else
                                N/A
                            @endif
                        </span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Date d'inscription :</span>
                        <span class="text-white">{{ $membre->date_inscription->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Statut :</span>
                        <span class="px-2 py-1 rounded-full text-xs {{ $membre->statut === 'actif' ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300' }}">
                            {{ ucfirst($membre->statut) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Adresse :</span>
                        <span class="text-white">{{ $membre->adresse ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Contact d'Urgence --}}
    @if($membre->contact_urgence || $membre->telephone_urgence)
    <div class="bg-gray-800 rounded-lg shadow-md border border-gray-700">
        <div class="p-6 border-b border-gray-700">
            <h3 class="text-lg font-medium text-white">🚨 Contact d'Urgence</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="flex justify-between">
                    <span class="text-gray-400">Nom du contact :</span>
                    <span class="text-white">{{ $membre->contact_urgence ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Téléphone :</span>
                    <span class="text-white">{{ $membre->telephone_urgence ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Activité --}}
    <div class="bg-gray-800 rounded-lg shadow-md border border-gray-700">
        <div class="p-6 border-b border-gray-700">
            <h3 class="text-lg font-medium text-white">📊 Activité</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-white">0</div>
                    <div class="text-sm text-gray-400">Cours inscrits</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-white">0</div>
                    <div class="text-sm text-gray-400">Présences ce mois</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-white">N/A</div>
                    <div class="text-sm text-gray-400">Taux présence</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Actions Rapides --}}
    <div class="bg-gray-800 rounded-lg shadow-md border border-gray-700">
        <div class="p-6 border-b border-gray-700">
            <h3 class="text-lg font-medium text-white">⚡ Actions Rapides</h3>
        </div>
        <div class="p-6">
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('admin.ceintures.create', ['membre_id' => $membre->id]) }}" 
                   class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    🥋 Attribuer Ceinture
                </a>
                
                <a href="{{ route('admin.seminaires.create', ['membre_id' => $membre->id]) }}" 
                   class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    🎓 Voir Séminaires
                </a>
                
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors" 
                        disabled>
                    ✅ Prendre Présence (bientôt)
                </button>
                
                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors" 
                        disabled>
                    📚 Inscrire à un Cours (bientôt)
                </button>
                
                <a href="{{ route('admin.membres.edit', $membre) }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    ✏️ Modifier Profil
                </a>
            </div>
        </div>
    </div>

    {{-- Notes Internes --}}
    @if($membre->notes)
    <div class="bg-gray-800 rounded-lg shadow-md border border-gray-700">
        <div class="p-6 border-b border-gray-700">
            <h3 class="text-lg font-medium text-white">📝 Notes Internes</h3>
        </div>
        <div class="p-6">
            <p class="text-gray-300">{{ $membre->notes }}</p>
        </div>
    </div>
    @endif

    {{-- Historique --}}
    <div class="bg-gray-800 rounded-lg shadow-md border border-gray-700">
        <div class="p-6 border-b border-gray-700">
            <h3 class="text-lg font-medium text-white">📈 Historique</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex items-center p-3 bg-gray-900 rounded-lg">
                    <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white text-sm font-bold mr-4">
                        ✓
                    </div>
                    <div>
                        <div class="text-white font-medium">Création profil</div>
                        <div class="text-gray-400 text-sm">{{ $membre->created_at->format('d/m/Y à H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
