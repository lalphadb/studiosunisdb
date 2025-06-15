@extends('layouts.admin')

@section('title', 'Détails Progression Ceinture')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">🏆 Progression de Ceinture</h1>
                <p class="text-blue-100 text-lg">{{ $progression->membre->nom_complet }} - {{ $progression->ceinture->nom }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.ceintures.edit', $progression) }}" class="bg-yellow-500 hover:bg-yellow-600 px-4 py-2 rounded-lg font-medium transition-colors">
                    ✏️ Modifier
                </a>
                <a href="{{ route('admin.ceintures.index') }}" class="bg-white text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-lg font-medium transition-colors">
                    ← Retour
                </a>
            </div>
        </div>
    </div>

    {{-- Détails de la progression --}}
    <div class="bg-gray-800 rounded-lg shadow-md border border-gray-700">
        <div class="p-6 border-b border-gray-700">
            <h3 class="text-lg font-medium text-white">📋 Détails de l'Attribution</h3>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Informations membre --}}
                <div class="bg-gray-900 rounded-lg p-4">
                    <h4 class="font-medium text-white mb-4">👤 Informations Membre</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Nom complet :</span>
                            <span class="text-white font-medium">{{ $progression->membre->nom_complet }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">École :</span>
                            <span class="text-white">{{ $progression->membre->ecole->nom ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Email :</span>
                            <span class="text-white">{{ $progression->membre->email ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Statut :</span>
                            <span class="px-2 py-1 rounded-full text-xs {{ $progression->membre->statut === 'actif' ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300' }}">
                                {{ ucfirst($progression->membre->statut) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Informations ceinture --}}
                <div class="bg-gray-900 rounded-lg p-4">
                    <h4 class="font-medium text-white mb-4">🥋 Informations Ceinture</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Ceinture :</span>
                            <span class="px-2 py-1 rounded-full text-xs font-medium border {{ $progression->ceinture->couleur_badge ?? 'bg-white text-gray-800 border-gray-300' }}">
                                {{ $progression->ceinture->nom }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Niveau :</span>
                            <span class="text-white font-medium">{{ $progression->ceinture->niveau }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Date d'obtention :</span>
                            <span class="text-white">{{ $progression->date_obtention->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Il y a :</span>
                            <span class="text-gray-300">{{ $progression->date_obtention->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            @if($progression->notes)
            <div class="mt-6 bg-gray-900 rounded-lg p-4">
                <h4 class="font-medium text-white mb-2">📝 Notes</h4>
                <p class="text-gray-300 text-sm">{{ $progression->notes }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Historique des progressions --}}
    <div class="bg-gray-800 rounded-lg shadow-md border border-gray-700">
        <div class="p-6 border-b border-gray-700">
            <h3 class="text-lg font-medium text-white">📈 Historique des Progressions</h3>
            <p class="text-sm text-gray-400 mt-1">Toutes les ceintures obtenues par {{ $progression->membre->prenom }}</p>
        </div>

        <div class="p-6">
            @if($historique->count() > 0)
                <div class="space-y-4">
                    @foreach($historique as $index => $item)
                    <div class="flex items-center p-4 {{ $item->id === $progression->id ? 'bg-blue-900 border border-blue-600' : 'bg-gray-900' }} rounded-lg">
                        <div class="flex-shrink-0 w-12 h-12 {{ $item->id === $progression->id ? 'bg-blue-600' : 'bg-gray-600' }} rounded-full flex items-center justify-center text-white font-bold">
                            {{ $historique->count() - $index }}
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-white font-medium">{{ $item->ceinture->nom }}</h4>
                                    <p class="text-gray-400 text-sm">{{ $item->date_obtention->format('d/m/Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $item->ceinture->couleur_badge ?? 'bg-white text-gray-800 border-gray-300' }}">
                                        Niveau {{ $item->ceinture->niveau }}
                                    </span>
                                    @if($item->id === $progression->id)
                                        <div class="text-xs text-blue-400 mt-1">← Actuelle</div>
                                    @endif
                                </div>
                            </div>
                            @if($item->notes)
                                <p class="text-gray-500 text-sm mt-2">{{ Str::limit($item->notes, 100) }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-6xl mb-4">🥋</div>
                    <p class="text-gray-400">Aucun historique de progression</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Actions --}}
    <div class="bg-gray-800 rounded-lg shadow-md border border-gray-700 p-6">
        <h3 class="text-lg font-medium text-white mb-4">⚡ Actions Rapides</h3>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('admin.ceintures.edit', $progression) }}" 
               class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                ✏️ Modifier cette Attribution
            </a>
            
            <a href="{{ route('admin.ceintures.create', ['membre_id' => $progression->membre->id]) }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                🥋 Attribuer Nouvelle Ceinture
            </a>
            
            <a href="{{ route('admin.membres.show', $progression->membre) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                👤 Voir Profil Membre
            </a>

            @if(auth()->user()->hasRole('superadmin'))
            <form method="POST" action="{{ route('admin.ceintures.destroy', $progression) }}" 
                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette attribution ?')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    🗑️ Supprimer
                </button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection
