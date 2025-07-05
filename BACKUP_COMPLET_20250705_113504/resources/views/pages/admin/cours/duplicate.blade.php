@extends('layouts.admin')
@section('title', 'Dupliquer un Cours')

@section('content')
<div class="space-y-6">
    <!-- Header avec x-module-header -->
    <x-module-header 
        module="cours"
        title="Dupliquer un Cours"
        subtitle="Créer plusieurs copies d'un cours existant"
    />

    <div class="studiosdb-card">
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                <span class="text-2xl mr-3">📚</span>
                Cours source
            </h3>
            <div class="bg-slate-700/50 rounded-xl p-4">
                <div class="flex items-center">
                    <div class="h-12 w-12 bg-violet-600 rounded-xl flex items-center justify-center">
                        <span class="text-xl text-white">📚</span>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-white font-medium">{{ $cours->nom }}</h4>
                        <p class="text-slate-400">{{ $cours->ecole->nom }}</p>
                        @if($cours->description)
                        <p class="text-slate-500 text-sm mt-1">{{ Str::limit($cours->description, 100) }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.cours.duplicate', $cours) }}">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label for="nombre_copies" class="block text-sm font-medium text-white mb-2">
                        Nombre de copies à créer
                    </label>
                    <input type="number" 
                           id="nombre_copies" 
                           name="nombre_copies"
                           value="{{ old('nombre_copies', 1) }}"
                           min="1" 
                           max="10"
                           class="studiosdb-input @error('nombre_copies') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                           required>
                    @error('nombre_copies')
                        <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                    @enderror
                    <p class="text-slate-400 text-sm mt-1">Entre 1 et 10 copies maximum</p>
                </div>

                <div>
                    <label for="suffixe" class="block text-sm font-medium text-white mb-2">
                        Suffixe pour les noms (optionnel)
                    </label>
                    <input type="text" 
                           id="suffixe" 
                           name="suffixe"
                           value="{{ old('suffixe') }}"
                           placeholder="Ex: Groupe, Session, etc."
                           class="studiosdb-input @error('suffixe') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                    @error('suffixe')
                        <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                    @enderror
                    <p class="text-slate-400 text-sm mt-1">Les cours seront nommés: "{{ $cours->nom }} - Suffixe 1", "{{ $cours->nom }} - Suffixe 2", etc.</p>
                </div>
            </div>

            <div class="flex items-center justify-between mt-6">
                <a href="{{ route('admin.cours.index') }}" 
                   class="studiosdb-btn studiosdb-btn-cancel">
                    ← Retour à la liste
                </a>
                <button type="submit" 
                        class="studiosdb-btn studiosdb-btn-cours studiosdb-btn-lg">
                    <span class="mr-2">📋</span>
                    Créer les copies
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
