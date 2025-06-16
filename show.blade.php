@extends('layouts.admin')

@section('title', 'Profil Membre')

@section('content')
<div class="space-y-6">
    {{-- Header Moderne avec Gradient --}}
    <div class="relative bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 rounded-lg p-6 text-white overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white opacity-5 rounded-full -ml-16 -mb-16"></div>
        
        <div class="relative flex items-center justify-between">
            <div class="flex items-center space-x-6">
                {{-- Avatar 3D Style --}}
                <div class="relative">
                    <div class="w-20 h-20 bg-white bg-opacity-20 rounded-lg flex items-center justify-center text-2xl font-bold backdrop-blur-sm border border-white border-opacity-30">
                        {{ strtoupper(substr($membre->prenom, 0, 1)) }}{{ strtoupper(substr($membre->nom, 0, 1)) }}
                    </div>
                    {{-- Badge Statut --}}
                    <div class="absolute -bottom-2 -right-2 w-6 h-6 {{ $membre->statut === 'actif' ? 'bg-green-500' : 'bg-red-500' }} rounded-full border-2 border-white flex items-center justify-center">
                        <span class="text-white text-xs">{{ $membre->statut === 'actif' ? '✓' : '!' }}</span>
                    </div>
                </div>
                
                <div>
                    {{-- NOM RÉDUIT --}}
                    <h1 class="text-2xl font-bold mb-2">{{ $membre->prenom }} {{ $membre->nom }}</h1>
                    <div class="flex items-center space-x-4 text-lg">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $membre->ecole->nom ?? 'École non assignée' }}
                        </span>
                        {{-- Ceinture Badge Élégant --}}
                        @php $ceintureActuelle = $membre->getCeintureActuellePourAffichage(); @endphp
                        @if($ceintureActuelle)
                        <span class="flex items-center bg-yellow-500 bg-opacity-30 px-3 py-1 rounded-lg backdrop-blur-sm">
                            <span class="text-xl mr-2">{{ $ceintureActuelle->emoji }}</span>
                            <span class="font-medium">{{ $ceintureActuelle->nom }}</span>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- Actions Floating --}}
            <div class="flex space-x-3">
                <a href="{{ route('admin.membres.edit', $membre) }}" 
                   class="bg-white bg-opacity-20 hover:bg-opacity-30 px-6 py-3 rounded-lg font-medium transition-all duration-300 backdrop-blur-sm border border-white border-opacity-30">
                    ✏️ Modifier
                </a>
                <a href="{{ route('admin.membres.index') }}" 
                   class="bg-white text-purple-600 hover:bg-gray-50 px-6 py-3 rounded-lg font-medium transition-all duration-300">
                    ← Retour
                </a>
            </div>
        </div>
    </div>

    {{-- SECTION PRINCIPALE - CÔTE À CÔTE --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- CARTE INFORMATIONS PERSONNELLES --}}
        <div class="bg-gray-800 rounded-lg shadow-2xl border border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 p-4">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                    </svg>
                    Informations Personnelles
                </h3>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- Colonne Gauche --}}
                    <div class="space-y-4">
                        <div class="group">
                            <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Email</label>
                            <div class="text-white font-medium group-hover:text-blue-400 transition-colors">
                                {{ $membre->email ?? 'Non renseigné' }}
                            </div>
                        </div>
                        
                        <div class="group">
                            <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Téléphone</label>
                            <div class="text-white font-medium group-hover:text-blue-400 transition-colors">
                                {{ $membre->telephone ?? 'Non renseigné' }}
                            </div>
                        </div>
                        
                        <div class="group">
                            <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Date de naissance</label>
                            <div class="text-white font-medium group-hover:text-blue-400 transition-colors">
                                @if($membre->date_naissance)
                                    {{ $membre->date_naissance->format('d/m/Y') }}
                                    <span class="text-sm text-gray-400">({{ $membre->age }} ans)</span>
                                @else
                                    Non renseigné
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    {{-- Colonne Droite --}}
                    <div class="space-y-4">
                        <div class="group">
                            <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Date inscription</label>
                            <div class="text-white font-medium group-hover:text-blue-400 transition-colors">
                                {{ $membre->date_inscription->format('d/m/Y') }}
                                <span class="text-sm text-gray-400">({{ $membre->date_inscription->diffForHumans() }})</span>
                            </div>
                        </div>
                        
                        <div class="group">
                            <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Adresse</label>
                            <div class="text-white font-medium group-hover:text-blue-400 transition-colors">
                                {{ $membre->adresse ?? 'Non renseigné' }}
                            </div>
                        </div>
                        
                        {{-- Contact d'urgence intégré --}}
                        @if($membre->contact_urgence)
                        <div class="group bg-red-500 bg-opacity-10 p-3 rounded-lg border border-red-500 border-opacity-30">
                            <label class="text-xs font-medium text-red-400 uppercase tracking-wider flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Contact d'urgence
                            </label>
                            <div class="text-white font-medium">{{ $membre->contact_urgence }}</div>
                            <div class="text-red-300 text-sm">{{ $membre->telephone_urgence ?? 'Pas de téléphone' }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- CARTE PROGRESSION CEINTURES --}}
        <div class="bg-gray-800 rounded-lg shadow-2xl border border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-4">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    Progression Ceintures
                </h3>
            </div>
            
            <div class="p-6">
                {{-- Ceinture Actuelle - Design Spécial --}}
                <div class="mb-6">
                    <label class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-2 block">Ceinture Actuelle</label>
                    @if($ceintureActuelle)
                        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg p-4 text-white">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-2xl">
                                    {{ $ceintureActuelle->emoji }}
                                </div>
                                <div class="flex-1">
                                    <div class="text-xl font-bold">{{ $ceintureActuelle->nom }}</div>
                                    <div class="text-yellow-100">Niveau {{ $ceintureActuelle->niveau }}</div>
                                    @if($membre->derniereCeinture)
                                    <div class="text-sm text-yellow-200">
                                        Obtenue le {{ $membre->derniereCeinture->date_obtention->format('d/m/Y') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-gray-700 rounded-lg p-4 text-center">
                            <div class="text-4xl mb-2">🥋</div>
                            <div class="text-gray-300">Aucune ceinture attribuée</div>
                        </div>
                    @endif
                </div>

                {{-- Prochaine Ceinture CORRIGÉE --}}
                <div class="mb-6">
                    <label class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-2 block">Prochaine Ceinture</label>
                    @if($ceintureActuelle && $ceintureActuelle->prochaineCeinture())
                        @php $prochaine = $ceintureActuelle->prochaineCeinture(); @endphp
                        <div class="bg-gray-700 rounded-lg p-4 border-2 border-dashed border-gray-600 relative">
                            <div class="absolute top-2 right-2 text-xs bg-blue-500 text-white px-2 py-1 rounded-lg">
                                Objectif
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gray-600 rounded-full flex items-center justify-center text-xl opacity-75">
                                    {{ $prochaine->emoji }}
                                </div>
                                <div>
                                    <div class="text-white font-medium">{{ str_replace('ject', '', $prochaine->nom) }}</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-green-700 rounded-lg p-4 text-center">
                            <div class="text-2xl mb-1">🏆</div>
                            <div class="text-green-200 font-medium">Niveau Maximum Atteint</div>
                            <div class="text-green-300 text-sm">Félicitations !</div>
                        </div>
                    @endif
                </div>

                {{-- Progression Visuelle --}}
                @if($ceintureActuelle)
                <div class="bg-gray-900 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-400">Progression</span>
                        <span class="text-sm text-white">{{ $ceintureActuelle->niveau }}/10</span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-2">
                        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 h-2 rounded-full transition-all duration-500"
                             style="width: {{ ($ceintureActuelle->niveau / 10) * 100 }}%"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- SECTION ACTIONS ET DÉTAILS --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Actions Rapides --}}
        <div class="lg:col-span-2">
            <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path>
                </svg>
                Actions Rapides
            </h3>
            
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('admin.ceintures.create', ['membre_id' => $membre->id]) }}" 
                   class="group bg-gradient-to-br from-orange-500 to-red-500 rounded-lg p-6 text-white hover:scale-105 transition-all duration-300 shadow-2xl border border-orange-400 border-opacity-30">
                    <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">🥋</div>
                    <div class="font-bold text-lg">Attribuer Ceinture</div>
                    <div class="text-orange-100 text-sm">Nouvelle progression</div>
                </a>
                
                <a href="{{ route('admin.seminaires.create', ['membre_id' => $membre->id]) }}" 
                   class="group bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg p-6 text-white hover:scale-105 transition-all duration-300 shadow-2xl border border-purple-400 border-opacity-30">
                    <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">🎓</div>
                    <div class="font-bold text-lg">Voir Séminaires</div>
                    <div class="text-purple-100 text-sm">Formations</div>
                </a>
                
                <button class="group bg-gradient-to-br from-blue-500 to-cyan-500 rounded-lg p-6 text-white transition-all duration-300 shadow-2xl opacity-60 border border-blue-400 border-opacity-30" disabled>
                    <div class="text-3xl mb-2">✅</div>
                    <div class="font-bold text-lg">Prendre Présence</div>
                    <div class="text-blue-100 text-sm">Bientôt disponible</div>
                </button>
                
                <a href="{{ route('admin.membres.edit', $membre) }}" 
                   class="group bg-gradient-to-br from-gray-600 to-gray-700 rounded-lg p-6 text-white hover:scale-105 transition-all duration-300 shadow-2xl border border-gray-500 border-opacity-30">
                    <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">✏️</div>
                    <div class="font-bold text-lg">Modifier Profil</div>
                    <div class="text-gray-300 text-sm">Informations</div>
                </a>
            </div>
        </div>

        {{-- Sidebar Infos --}}
        <div class="space-y-6">
            
            {{-- Activité Compacte --}}
            <div class="bg-gray-800 rounded-lg p-4 border border-gray-700">
                <h4 class="font-bold text-white mb-3 flex items-center">
                    📊 Statistiques
                </h4>
                <div class="grid grid-cols-1 gap-3">
                    <div class="bg-gray-900 rounded-lg p-3 text-center">
                        <div class="text-xl font-bold text-blue-400">0</div>
                        <div class="text-xs text-gray-400">Cours actifs</div>
                    </div>
                    <div class="bg-gray-900 rounded-lg p-3 text-center">
                        <div class="text-xl font-bold text-green-400">0</div>
                        <div class="text-xs text-gray-400">Présences mois</div>
                    </div>
                    <div class="bg-gray-900 rounded-lg p-3 text-center">
                        <div class="text-xl font-bold text-purple-400">N/A</div>
                        <div class="text-xs text-gray-400">Taux présence</div>
                    </div>
                </div>
            </div>

            {{-- Notes si présentes --}}
            @if($membre->notes)
            <div class="bg-gray-800 rounded-lg p-4 border border-gray-700">
                <h4 class="font-bold text-white mb-2 flex items-center">
                    📝 Notes
                </h4>
                <p class="text-gray-300 text-sm leading-relaxed">{{ $membre->notes }}</p>
            </div>
            @endif

            {{-- Informations système --}}
            <div class="bg-gray-800 rounded-lg p-4 border border-gray-700">
                <h4 class="font-bold text-white mb-3">⚙️ Système</h4>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-gray-400">ID Membre</span>
                        <span class="text-white font-mono">#{{ $membre->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Créé</span>
                        <span class="text-white">{{ $membre->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Modifié</span>
                        <span class="text-white">{{ $membre->updated_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Animations personnalisées */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.group:hover .group-hover\:scale-110 {
    animation: float 2s ease-in-out infinite;
}
</style>
@endsection
