@extends('layouts.admin')

@section('title', 'Détails de la Ceinture')

@section('content')
<x-module-header 
   title="Détails de la Ceinture" 
   :breadcrumbs="[
       ['name' => 'Admin', 'url' => route('admin.dashboard')],
       ['name' => 'Ceintures', 'url' => route('admin.ceintures.index')],
       ['name' => $ceinture->nom, 'url' => null]
   ]"
   color="purple-600" />

<div class="studiosdb-content">
    <x-admin.flash-messages />
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden">
                <h3 class="text-lg font-semibold text-white mb-4">
                    🥋 Informations de la Ceinture
                </h3>
                
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-400">Nom</dt>
                        <dd class="mt-1 text-white font-medium">{{ $ceinture->nom }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-400">Couleur</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-600 text-white">
                                {{ $ceinture->couleur }}
                            </span>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-400">Ordre</dt>
                        <dd class="mt-1 text-purple-400 font-semibold">{{ $ceinture->ordre }}</dd>
                    </div>
                </dl>
            </div>
        </div>
        
        <div class="space-y-6">
            <div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden">
                <h3 class="text-lg font-semibold text-white mb-4">⚡ Actions</h3>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.ceintures.edit', $ceinture) }}" 
                       class="w-full studiosdb-btn-primary">
                        ✏️ Modifier
                    </a>
                    
                    <a href="{{ route('admin.ceintures.index') }}" 
                       class="w-full studiosdb-btn-secondary">
                        ← Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
