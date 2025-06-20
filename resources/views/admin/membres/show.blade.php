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
                {{-- Avatar 3D Style avec Age --}}
                <div class="relative">
                    <div class="w-20 h-20 bg-white bg-opacity-20 rounded-lg flex items-center justify-center text-2xl font-bold backdrop-blur-sm border border-white border-opacity-30">
                        {{ $membre->initiales }}
                    </div>
                    {{-- Badge Statut --}}
                    <div class="absolute -bottom-2 -right-2 w-6 h-6 {{ $membre->active ? 'bg-green-500' : 'bg-red-500' }} rounded-full border-2 border-white flex items-center justify-center">
                        <span class="text-white text-xs">{{ $membre->active ? '✓' : '!' }}</span>
                    </div>
                    {{-- Badge Age --}}
                    @if($membre->age)
                    <div class="absolute -top-2 -left-2 bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                        {{ $membre->age }} ans
                    </div>
                    @endif
                </div>
                
                <div>
                    <h1 class="text-2xl font-bold mb-2">{{ $membre->nom_complet }}</h1>
                    <div class="flex items-center space-x-4 text-lg">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $membre->ecole->nom ?? 'École non assignée' }}
                        </span>
                        {{-- Ceinture Badge --}}
                        @php $ceintureActuelle = $membre->getCeintureActuellePourAffichage(); @endphp
                        @if($ceintureActuelle)
                        <div class="flex items-center bg-black bg-opacity-40 px-4 py-2 rounded-lg backdrop-blur-sm border border-white border-opacity-40">
                            <div class="relative w-10 h-6 rounded border-2 border-white overflow-hidden mr-3" style="background: {{ $ceintureActuelle->couleur }}">
                                {{-- Rayures pour ceintures avancées --}}
                                @if(strpos($ceintureActuelle->nom, 'I') !== false || strpos($ceintureActuelle->nom, 'Dan') !== false)
                                    <div class="absolute w-full h-0.5 bg-yellow-400 top-1/2 transform -translate-y-1/2"></div>
                                @endif
                            </div>
                            <span class="font-bold text-white text-sm drop-shadow-lg">{{ $ceintureActuelle->nom }}</span>
                        </div>
                        @else
                        <div class="flex items-center bg-gray-700 bg-opacity-60 px-4 py-2 rounded-lg backdrop-blur-sm border border-gray-500">
                            <div class="w-10 h-6 bg-gray-400 rounded mr-3 border-2 border-gray-300"></div>
                            <span class="font-bold text-white text-sm">Aucune ceinture</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- Actions Header --}}
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

    {{-- SECTION PRINCIPALE --}}
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
                <div class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="group">
                                <label class="text-xs font-medium text-blue-400 uppercase tracking-wider mb-1 block">📧 Email</label>
                                <div class="text-white font-medium text-lg">{{ $membre->email ?? 'Non renseigné' }}</div>
                            </div>
                            
                            <div class="group">
                                <label class="text-xs font-medium text-green-400 uppercase tracking-wider mb-1 block">📞 Téléphone</label>
                                <div class="text-white font-medium text-lg">{{ $membre->telephone ?? 'Non renseigné' }}</div>
                            </div>
                            
                            <div class="group">
                                <label class="text-xs font-medium text-purple-400 uppercase tracking-wider mb-1 block">🎂 Date de naissance</label>
                                <div class="text-white font-medium text-lg">
                                    @if($membre->date_naissance)
                                        {{ $membre->date_naissance->format('d/m/Y') }}
                                        <div class="text-purple-300 text-sm font-bold">🎯 {{ $membre->age }} ans</div>
                                    @else
                                        Non renseigné
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="group">
                                <label class="text-xs font-medium text-yellow-400 uppercase tracking-wider mb-1 block">📅 Date inscription</label>
                                <div class="text-white font-medium text-lg">
                                    {{ $membre->date_inscription->format('d/m/Y') }}
                                    <div class="text-yellow-300 text-sm">{{ $membre->date_inscription->diffForHumans() }}</div>
                                </div>
                            </div>
                            
                            <div class="group">
                                <label class="text-xs font-medium text-pink-400 uppercase tracking-wider mb-1 block">🏠 Adresse</label>
                                <div class="text-white font-medium text-lg">
                                    {{ $membre->adresse ?? 'Non renseigné' }}
                                    @if($membre->ville || $membre->code_postal)
                                        <div class="text-pink-300 text-sm">{{ $membre->ville }} {{ $membre->code_postal }}</div>
                                    @endif
                                </div>
                            </div>
                            
                            @if($membre->sexe)
                            <div class="group">
                                <label class="text-xs font-medium text-indigo-400 uppercase tracking-wider mb-1 block">⚧️ Sexe</label>
                                <div class="text-white font-medium text-lg">
                                    @if($membre->sexe == 'M') 👨 Masculin
                                    @elseif($membre->sexe == 'F') 👩 Féminin  
                                    @else 🏳️‍⚧️ Autre
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Contact d'urgence --}}
                    @if($membre->contact_urgence_nom || $membre->contact_urgence_telephone)
                    <div class="bg-red-500 bg-opacity-15 p-4 rounded-lg border-2 border-red-500 border-opacity-40">
                        <label class="text-sm font-bold text-red-300 uppercase tracking-wider mb-3 block flex items-center">
                            🚨 Contact d'urgence
                        </label>
                        @if($membre->contact_urgence_nom)
                            <div class="text-white font-bold text-xl mb-2">👤 {{ $membre->contact_urgence_nom }}</div>
                        @endif
                        @if($membre->contact_urgence_telephone)
                            <div class="text-red-200 font-medium text-lg">📞 {{ $membre->contact_urgence_telephone }}</div>
                        @endif
                    </div>
                    @else
                    <div class="bg-gray-700 bg-opacity-50 p-4 rounded-lg border border-gray-600">
                        <div class="text-gray-400 text-center">⚠️ Aucun contact d'urgence renseigné</div>
                    </div>
                    @endif

                    {{-- Notes --}}
                    @if($membre->notes)
                    <div class="bg-blue-500 bg-opacity-15 p-4 rounded-lg border border-blue-500 border-opacity-40">
                        <label class="text-sm font-bold text-blue-300 uppercase tracking-wider mb-2 block">📝 Notes</label>
                        <div class="text-white text-base leading-relaxed">{{ $membre->notes }}</div>
                    </div>
                    @endif
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
                <div class="mb-6">
                    <label class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-3 block">🏆 Ceinture Actuelle</label>
                    @if($ceintureActuelle)
                        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg p-6 text-white">
                            <div class="flex items-center space-x-6">
                                <div class="w-20 h-12 bg-white bg-opacity-30 rounded-lg flex items-center justify-center border-2 border-white">
                                    <div class="relative w-16 h-8 rounded border border-gray-300 overflow-hidden" style="background: {{ $ceintureActuelle->couleur }}"></div>
                                </div>
                                <div class="flex-1">
                                    <div class="text-2xl font-bold mb-1">{{ $ceintureActuelle->nom }}</div>
                                    <div class="text-yellow-100 text-lg font-medium">🎯 Niveau {{ $ceintureActuelle->ordre }}/21</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-gray-700 rounded-lg p-6 text-center border-2 border-dashed border-gray-500">
                            <div class="text-5xl mb-3">🥋</div>
                            <div class="text-gray-300 text-xl font-medium">Aucune ceinture attribuée</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Actions rapides --}}
    <div class="bg-gray-800 rounded-lg shadow-2xl border border-gray-700 overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-emerald-500 p-4">
            <h3 class="text-xl font-bold text-white">⚡ Actions Rapides</h3>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.membres.edit', $membre) }}" 
                   class="group bg-gradient-to-br from-blue-500 to-cyan-500 rounded-lg p-6 text-white hover:scale-105 transition-all duration-300 shadow-lg">
                    <div class="text-4xl mb-3">✏️</div>
                    <div class="font-bold text-xl">Modifier Profil</div>
                    <div class="text-blue-100 text-sm">Informations personnelles</div>
                </a>
                
                <a href="{{ route('admin.ceintures.create') }}?membre_id={{ $membre->id }}" 
                   class="group bg-gradient-to-br from-orange-500 to-red-500 rounded-lg p-6 text-white hover:scale-105 transition-all duration-300 shadow-lg">
                    <div class="text-4xl mb-3">🥋</div>
                    <div class="font-bold text-xl">Attribuer Ceinture</div>
                    <div class="text-orange-100 text-sm">Gestion des grades</div>
                </a>
                
                <a href="{{ route('admin.presences.index') }}?membre_id={{ $membre->id }}" 
                   class="group bg-gradient-to-br from-green-500 to-emerald-500 rounded-lg p-6 text-white hover:scale-105 transition-all duration-300 shadow-lg">
                    <div class="text-4xl mb-3">📊</div>
                    <div class="font-bold text-xl">Présences</div>
                    <div class="text-green-100 text-sm">Historique</div>
                </a>
                
                <a href="{{ route('admin.paiements.index') }}?membre_id={{ $membre->id }}"
                   class="group bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg p-6 text-white hover:scale-105 transition-all duration-300 shadow-lg">
                    <div class="text-4xl mb-3">💳</div>
                    <div class="font-bold text-xl">Paiements</div>
                    <div class="text-purple-100 text-sm">Facturation</div>
                </a>
            </div>
        </div>
    </div>

    {{-- Zone Administration (SuperAdmin ET Admin) --}}
    @if(auth()->user()->hasAnyRole(['superadmin', 'admin']))
    <div class="bg-red-900 bg-opacity-20 border border-red-500 rounded-lg overflow-hidden">
        <div class="bg-gradient-to-r from-red-600 to-red-700 p-4">
            <h3 class="text-xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                ⚠️ Zone Dangereuse - Administration
            </h3>
        </div>
        
        <div class="p-6">
            <div class="bg-red-800 bg-opacity-30 rounded-lg p-4 mb-4">
                <h4 class="text-red-200 font-bold mb-2">⚠️ Suppression Définitive</h4>
                <p class="text-red-300 text-sm">
                    La suppression de <strong>{{ $membre->nom_complet }}</strong> est une action <strong>irréversible</strong> qui supprimera :
                </p>
                <ul class="text-red-300 text-sm mt-2 ml-4 list-disc">
                    <li>Toutes ses progressions de ceintures</li>
                    <li>Toutes ses présences aux cours</li>
                    <li>Tous ses paiements et factures</li>
                    <li>Toutes ses inscriptions aux cours et séminaires</li>
                </ul>
            </div>
            
            <form method="POST" action="{{ route('admin.membres.destroy', $membre) }}" 
                  onsubmit="return confirm('⚠️ SUPPRESSION DÉFINITIVE !\n\nVous êtes sur le point de supprimer {{ $membre->nom_complet }}.\n\nCeci supprimera TOUTES ses données :\n• Progressions de ceintures\n• Présences\n• Paiements\n• Inscriptions\n\nCette action est IRRÉVERSIBLE !\n\nÊtes-vous absolument sûr ?')" 
                  class="flex items-center justify-between">
                @csrf
                @method('DELETE')
                
                <div class="text-red-300 text-sm">
                    @if(auth()->user()->hasRole('superadmin'))
                        <strong>Accès Super-Administrateur</strong>
                    @else
                        <strong>Accès Administrateur d'École</strong>
                    @endif
                </div>
                <button type="submit" 
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-bold transition-colors border-2 border-red-500 hover:border-red-400">
                    🗑️ Supprimer Définitivement
                </button>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection
