@extends('layouts.admin')

@section('title', 'Modifier le Cours')

@section('content')
<x-module-header 
   title="Modifier le Cours" 
   :breadcrumbs="[
       ['name' => 'Admin', 'url' => route('admin.dashboard')],
       ['name' => 'Cours', 'url' => route('admin.cours.index')],
       ['name' => 'Modifier', 'url' => null]
   ]"
   color="emerald-600" />

<div class="studiosdb-content">
    <x-admin.flash-messages />
    
    <div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden">
        <form method="POST" action="{{ route('admin.cours.update', $cours) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nom du cours -->
                <div>
                    <x-input-label for="nom" value="Nom du cours" />
                    <x-text-input 
                        id="nom" 
                        name="nom" 
                        type="text" 
                        class="mt-1 block w-full studiosdb-input" 
                        :value="old('nom', $cours->nom)" 
                        required 
                        autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('nom')" />
                </div>

                <!-- Type -->
                <div>
                    <x-input-label for="type" value="Type de cours" />
                    <select id="type" name="type" class="mt-1 block w-full studiosdb-input" required>
                        <option value="">Sélectionner un type</option>
                        <option value="enfant" {{ old('type', $cours->type) == 'enfant' ? 'selected' : '' }}>Enfant</option>
                        <option value="adulte" {{ old('type', $cours->type) == 'adulte' ? 'selected' : '' }}>Adulte</option>
                        <option value="mixte" {{ old('type', $cours->type) == 'mixte' ? 'selected' : '' }}>Mixte</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('type')" />
                </div>

                <!-- Niveau -->
                <div>
                    <x-input-label for="niveau" value="Niveau" />
                    <select id="niveau" name="niveau" class="mt-1 block w-full studiosdb-input" required>
                        <option value="">Sélectionner un niveau</option>
                        <option value="debutant" {{ old('niveau', $cours->niveau) == 'debutant' ? 'selected' : '' }}>Débutant</option>
                        <option value="intermediaire" {{ old('niveau', $cours->niveau) == 'intermediaire' ? 'selected' : '' }}>Intermédiaire</option>
                        <option value="avance" {{ old('niveau', $cours->niveau) == 'avance' ? 'selected' : '' }}>Avancé</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('niveau')" />
                </div>

                <!-- Prix -->
                <div>
                    <x-input-label for="prix" value="Prix mensuel (€)" />
                    <x-text-input 
                        id="prix" 
                        name="prix" 
                        type="number" 
                        step="0.01"
                        class="mt-1 block w-full studiosdb-input" 
                        :value="old('prix', $cours->prix)" />
                    <x-input-error class="mt-2" :messages="$errors->get('prix')" />
                </div>
            </div>

            <!-- Description -->
            <div>
                <x-input-label for="description" value="Description" />
                <textarea 
                    id="description" 
                    name="description" 
                    rows="4" 
                    class="mt-1 block w-full studiosdb-input"
                    placeholder="Description du cours...">{{ old('description', $cours->description) }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('description')" />
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('admin.cours.index') }}" class="studiosdb-btn-secondary">
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
