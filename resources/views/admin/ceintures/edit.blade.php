@extends('layouts.admin')

@section('title', 'Modifier la Ceinture')

@section('content')
<x-module-header 
   title="Modifier la Ceinture" 
   :breadcrumbs="[
       ['name' => 'Admin', 'url' => route('admin.dashboard')],
       ['name' => 'Ceintures', 'url' => route('admin.ceintures.index')],
       ['name' => 'Modifier', 'url' => null]
   ]"
   color="purple-600" />

<div class="studiosdb-content">
    <x-admin.flash-messages />
    
    <div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden">
        <form method="POST" action="{{ route('admin.ceintures.update', $ceinture) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="nom" value="Nom de la ceinture" />
                    <x-text-input 
                        id="nom" 
                        name="nom" 
                        type="text" 
                        class="mt-1 block w-full studiosdb-input" 
                        :value="old('nom', $ceinture->nom)" 
                        required />
                    <x-input-error class="mt-2" :messages="$errors->get('nom')" />
                </div>

                <div>
                    <x-input-label for="couleur" value="Couleur" />
                    <x-text-input 
                        id="couleur" 
                        name="couleur" 
                        type="text" 
                        class="mt-1 block w-full studiosdb-input" 
                        :value="old('couleur', $ceinture->couleur)" />
                    <x-input-error class="mt-2" :messages="$errors->get('couleur')" />
                </div>

                <div>
                    <x-input-label for="ordre" value="Ordre" />
                    <x-text-input 
                        id="ordre" 
                        name="ordre" 
                        type="number" 
                        class="mt-1 block w-full studiosdb-input" 
                        :value="old('ordre', $ceinture->ordre)" />
                    <x-input-error class="mt-2" :messages="$errors->get('ordre')" />
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('admin.ceintures.index') }}" class="studiosdb-btn-secondary">
                    Annuler
                </a>
                <x-primary-button class="studiosdb-btn-primary">
                    Mettre à jour
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
@endsection
