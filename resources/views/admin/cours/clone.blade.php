@extends('layouts.admin')
@section('title', 'Dupliquer un Cours')

@section('content')
<div class="space-y-6">
    <!-- Header avec x-module-header -->
    <x-module-header 
        module="cours"
        title="Dupliquer un Cours"
        subtitle="Créer plusieurs copies d'un cours existant"
        :create-route="null"
        create-text=""
        create-permission="null"
    />

    <div class="bg-slate-800/40 backdrop-blur-xl/40 backdrop-blur-xl rounded-xl border border-slate-700/30/30/20p-6">
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-slate-100 tracking-tight flex items-center gap-3">Cours source</h3>
            <div class="bg-slate-700 rounded-xl p-4">
                <div class="flex items-center">
                    <div class="h-12 w-12 bg-violet-600 rounded-xl flex items-center justify-center">
                        <span class="text-xl text-white">📚</span>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-white font-medium">{{ $cours->nom }}</h4>
                        <p class="text-slate-400">{{ $cours->ecole->nom }}</p>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.cours.clone', $cours) }}">
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
                           class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-violet-600"
                           required>
                    @error('nombre_copies')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
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
                           class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-violet-600">
                    @error('suffixe')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                    <p class="text-slate-400 text-sm mt-1">Les cours seront nommés: "Nom Original - Suffixe 1", "Nom Original - Suffixe 2", etc.</p>
                </div>
            </div>

            <div class="flex items-center justify-between mt-6">
                <a href="{{ route('admin.cours.index') }}" 
                   class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-2 rounded-xl transition duration-200">
                    Annuler
                </a>
                <button type="submit" 
                        class="bg-violet-600 hover:bg-violet-700 text-white px-6 py-2 rounded-xl transition duration-200">
                    Créer les copies
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
