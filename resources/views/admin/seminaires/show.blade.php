@extends('layouts.admin')

@section('title', 'Détails Séminaire')

@section('content')
<div class="space-y-6">
    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-white">{{ $seminaire->titre }}</h1>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.seminaires.edit', $seminaire) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    Modifier
                </a>
                <a href="{{ route('admin.seminaires.index') }}" class="px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg transition-colors">
                    Retour à la liste
                </a>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-slate-400 text-sm font-medium mb-1">Type</label>
                    <p class="text-white">{{ $seminaire->type_libelle ?? $seminaire->type }}</p>
                </div>
                
                <div>
                    <label class="block text-slate-400 text-sm font-medium mb-1">Niveau requis</label>
                    <p class="text-white">{{ $seminaire->niveau_libelle ?? $seminaire->niveau_requis }}</p>
                </div>
                
                <div>
                    <label class="block text-slate-400 text-sm font-medium mb-1">Instructeur</label>
                    <p class="text-white">{{ $seminaire->instructeur }}</p>
                </div>
                
                <div>
                    <label class="block text-slate-400 text-sm font-medium mb-1">Lieu</label>
                    <p class="text-white">{{ $seminaire->lieu }}</p>
                </div>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-slate-400 text-sm font-medium mb-1">Période</label>
                    <p class="text-white">{{ $seminaire->duree ?? 'Non définie' }}</p>
                </div>
                
                <div>
                    <label class="block text-slate-400 text-sm font-medium mb-1">Statut</label>
                    <span class="inline-block px-3 py-1 rounded-full text-sm {{ $seminaire->statut_class ?? 'text-slate-400' }}">
                        {{ $seminaire->statut_libelle ?? $seminaire->statut }}
                    </span>
                </div>
                
                <div>
                    <label class="block text-slate-400 text-sm font-medium mb-1">Participants inscrits</label>
                    <p class="text-blue-400 text-xl font-bold">{{ $seminaire->nombre_inscrits ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        @if($seminaire->description)
        <div class="mt-6 pt-6 border-t border-slate-700">
            <label class="block text-slate-400 text-sm font-medium mb-2">Description</label>
            <p class="text-white leading-relaxed">{{ $seminaire->description }}</p>
        </div>
        @endif
        
        <div class="mt-6 pt-6 border-t border-slate-700">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.seminaires.inscriptions', $seminaire) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors">
                    Voir les inscriptions
                </a>
                <form method="POST" action="{{ route('admin.seminaires.destroy', $seminaire) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce séminaire ?')">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
