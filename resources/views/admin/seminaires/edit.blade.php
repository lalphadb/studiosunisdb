@extends('layouts.admin')

@section('title', 'Modifier Séminaire')

@section('content')
<div class="space-y-6">
    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700">
        <h1 class="text-2xl font-bold text-white mb-6">Modifier le Séminaire</h1>
        
        <form method="POST" action="{{ route('admin.seminaires.update', $seminaire) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-slate-300 font-medium mb-2">Titre</label>
                    <input type="text" name="titre" value="{{ $seminaire->titre }}" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white" required>
                    @error('titre')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-slate-300 font-medium mb-2">Type</label>
                    <select name="type" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white" required>
                        <option value="technique" {{ $seminaire->type === 'technique' ? 'selected' : '' }}>Technique</option>
                        <option value="kata" {{ $seminaire->type === 'kata' ? 'selected' : '' }}>Kata</option>
                        <option value="competition" {{ $seminaire->type === 'competition' ? 'selected' : '' }}>Compétition</option>
                        <option value="arbitrage" {{ $seminaire->type === 'arbitrage' ? 'selected' : '' }}>Arbitrage</option>
                    </select>
                    @error('type')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-slate-300 font-medium mb-2">Date de début</label>
                    <input type="date" name="date_debut" value="{{ $seminaire->date_debut ? $seminaire->date_debut->format('Y-m-d') : '' }}" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white" required>
                    @error('date_debut')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-slate-300 font-medium mb-2">Date de fin</label>
                    <input type="date" name="date_fin" value="{{ $seminaire->date_fin ? $seminaire->date_fin->format('Y-m-d') : '' }}" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white" required>
                    @error('date_fin')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div>
                <label class="block text-slate-300 font-medium mb-2">Description</label>
                <textarea name="description" rows="4" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white">{{ $seminaire->description }}</textarea>
                @error('description')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex items-center space-x-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    Mettre à jour
                </button>
                <a href="{{ route('admin.seminaires.show', $seminaire) }}" class="px-6 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg transition-colors">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
