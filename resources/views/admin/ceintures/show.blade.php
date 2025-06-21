@extends('layouts.admin')

@section('title', 'Détails Progression Ceinture')

@section('content')
<div class="admin-content">
    {{-- Header --}}
    <div class="admin-header">
        <div>
            <h1 class="admin-title">🏆 Progression de Ceinture</h1>
            <p class="admin-subtitle">{{ $progression->user->name }} - {{ $progression->ceinture->nom }}</p>
        </div>
        <div class="admin-actions">
            @can('edit-ceinture')
                <a href="{{ route('admin.ceintures.edit', $progression) }}" class="btn btn-warning">
                    ✏️ Modifier
                </a>
            @endcan
            <a href="{{ route('admin.ceintures.index') }}" class="btn btn-secondary">
                ← Retour
            </a>
        </div>
    </div>

    {{-- Détails de la progression --}}
    <div class="admin-card mb-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Informations membre --}}
            <div class="bg-gray-900 rounded-lg p-6">
                <h4 class="font-medium text-white mb-4">👤 Informations Membre</h4>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Nom :</span>
                        <span class="text-white font-medium">{{ $progression->user->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Email :</span>
                        <span class="text-white">{{ $progression->user->email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">École :</span>
                        <span class="text-white">{{ $progression->user->ecole->nom ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Statut :</span>
                        <span class="badge {{ $progression->user->active ? 'badge-success' : 'badge-danger' }}">
                            {{ $progression->user->active ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Informations ceinture --}}
            <div class="bg-gray-900 rounded-lg p-6">
                <h4 class="font-medium text-white mb-4">🥋 Informations Ceinture</h4>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Ceinture :</span>
                        <span class="badge badge-ceinture">{{ $progression->ceinture->nom }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Ordre :</span>
                        <span class="text-white">{{ $progression->ceinture->ordre }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Date d'obtention :</span>
                        <span class="text-white">{{ $progression->date_obtention->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Il y a :</span>
                        <span class="text-gray-300">{{ $progression->date_obtention->diffForHumans() }}</span>
                    </div>
                    @if($progression->examinateur)
                    <div class="flex justify-between">
                        <span class="text-gray-400">Examinateur :</span>
                        <span class="text-white">{{ $progression->examinateur }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-400">Statut :</span>
                        <span class="badge {{ $progression->valide ? 'badge-success' : 'badge-warning' }}">
                            {{ $progression->valide ? '✅ Validée' : '⏳ En attente' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Commentaires --}}
        @if($progression->commentaires)
        <div class="mt-6 bg-gray-900 rounded-lg p-6">
            <h4 class="font-medium text-white mb-3">📝 Commentaires</h4>
            <p class="text-gray-300">{{ $progression->commentaires }}</p>
        </div>
        @endif
    </div>

    {{-- Historique des progressions --}}
    <div class="admin-card">
        <div class="border-b border-gray-700 pb-4 mb-6">
            <h3 class="text-lg font-medium text-white">📈 Historique des Progressions</h3>
            <p class="text-sm text-gray-400 mt-1">Toutes les ceintures obtenues par {{ $progression->user->name }}</p>
        </div>

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
                                @if($item->examinateur)
                                    <p class="text-gray-500 text-xs">Examinateur: {{ $item->examinateur }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <span class="badge badge-ceinture">
                                    Ordre {{ $item->ceinture->ordre }}
                                </span>
                                @if($item->id === $progression->id)
                                    <div class="text-xs text-blue-400 mt-1">← Actuelle</div>
                                @endif
                            </div>
                        </div>
                        @if($item->commentaires)
                            <p class="text-gray-500 text-sm mt-2">{{ Str::limit($item->commentaires, 100) }}</p>
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

    {{-- Actions --}}
    <div class="admin-card mt-6">
        <h3 class="text-lg font-medium text-white mb-4">⚡ Actions Rapides</h3>
        <div class="flex flex-wrap gap-4">
            @can('edit-ceinture')
                <a href="{{ route('admin.ceintures.edit', $progression) }}" class="btn btn-warning">
                    ✏️ Modifier cette Attribution
                </a>
            @endcan
            
            @can('assign-ceintures')
                <a href="{{ route('admin.ceintures.create', ['user_id' => $progression->user_id]) }}" class="btn btn-success">
                    🥋 Attribuer Nouvelle Ceinture
                </a>
            @endcan
            
            <a href="{{ route('admin.users.show', $progression->user) }}" class="btn btn-info">
                👤 Voir Profil Membre
            </a>

            @can('delete-ceinture')
            <form method="POST" action="{{ route('admin.ceintures.destroy', $progression) }}" 
                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette attribution ?')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    🗑️ Supprimer
                </button>
            </form>
            @endcan
        </div>
    </div>
</div>
@endsection
