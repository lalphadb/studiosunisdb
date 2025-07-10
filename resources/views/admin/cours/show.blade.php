@extends('layouts.admin')

@section('title', 'Détails du Cours')

@section('content')
<x-module-header 
   title="Détails du Cours" 
   :breadcrumbs="[
       ['name' => 'Admin', 'url' => route('admin.dashboard')],
       ['name' => 'Cours', 'url' => route('admin.cours.index')],
       ['name' => $cours->nom, 'url' => null]
   ]"
   color="emerald-600" />

<div class="studiosdb-content">
    <x-admin.flash-messages />
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-2">
            <div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden">
                <h3 class="text-lg font-semibold text-white mb-4">
                    📚 Informations du Cours
                </h3>
                
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-400">Nom</dt>
                        <dd class="mt-1 text-white font-medium">{{ $cours->nom }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-400">Type</dt>
                        <dd class="mt-1">
                            <span class="studiosdb-badge-{{ $cours->type == 'enfant' ? 'blue' : ($cours->type == 'adulte' ? 'green' : 'purple') }}">
                                {{ ucfirst($cours->type) }}
                            </span>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-400">Niveau</dt>
                        <dd class="mt-1">
                            <span class="studiosdb-badge-{{ $cours->niveau == 'debutant' ? 'yellow' : ($cours->niveau == 'intermediaire' ? 'orange' : 'red') }}">
                                {{ ucfirst($cours->niveau) }}
                            </span>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-400">Prix mensuel</dt>
                        <dd class="mt-1 text-emerald-400 font-semibold">
                            {{ $cours->prix ? number_format($cours->prix, 2) . ' €' : 'Non défini' }}
                        </dd>
                    </div>
                    
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-400">Description</dt>
                        <dd class="mt-1 text-gray-300">
                            {{ $cours->description ?: 'Aucune description' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
        
        <!-- Actions et statistiques -->
        <div class="space-y-6">
            <!-- Actions -->
            <div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden">
                <h3 class="text-lg font-semibold text-white mb-4">
                    ⚡ Actions
                </h3>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.cours.edit', $cours) }}" 
                       class="w-full studiosdb-btn-primary">
                        ✏️ Modifier
                    </a>
                    
                    <a href="{{ route('admin.cours.index') }}" 
                       class="w-full studiosdb-btn-secondary">
                        ← Retour à la liste
                    </a>
                    
                    @can('delete', $cours)
                    <form method="POST" action="{{ route('admin.cours.destroy', $cours) }}" 
                          class="w-full" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full studiosdb-btn-danger">
                            🗑️ Supprimer
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
            
            <!-- Statistiques -->
            <div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden">
                <h3 class="text-lg font-semibold text-white mb-4">
                    📊 Statistiques
                </h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Créé le</span>
                        <span class="text-white">{{ $cours->created_at->format('d/m/Y') }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-400">Modifié le</span>
                        <span class="text-white">{{ $cours->updated_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
