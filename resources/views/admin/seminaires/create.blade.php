@extends('layouts.admin')

@section('title', 'Nouveau Séminaire')

@section('content')
<div class="space-y-6">
    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700">
        <h1 class="text-2xl font-bold text-white mb-6">Nouveau Séminaire</h1>
        
        <form method="POST" action="{{ route('admin.seminaires.store') }}" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-slate-300 font-medium mb-2">Titre</label>
                    <input type="text" name="titre" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white" required>
                    @error('titre')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-slate-300 font-medium mb-2">Type</label>
                    <select name="type" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white" required>
                        <option value="">Sélectionner un type</option>
                        <option value="technique">Technique</option>
                        <option value="kata">Kata</option>
                        <option value="competition">Compétition</option>
                        <option value="arbitrage">Arbitrage</option>
                    </select>
                    @error('type')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-slate-300 font-medium mb-2">Niveau requis</label>
                    <select name="niveau_requis" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white" required>
                        <option value="">Sélectionner un niveau</option>
                        <option value="debutant">Débutant</option>
                        <option value="intermediaire">Intermédiaire</option>
                        <option value="avance">Avancé</option>
                        <option value="tous_niveaux">Tous niveaux</option>
                    </select>
                    @error('niveau_requis')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-slate-300 font-medium mb-2">Date de début</label>
                    <input type="date" name="date_debut" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white" required>
                    @error('date_debut')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-slate-300 font-medium mb-2">Date de fin</label>
                    <input type="date" name="date_fin" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white" required>
                    @error('date_fin')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-slate-300 font-medium mb-2">Heure de début</label>
                    <input type="time" name="heure_debut" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white" required>
                    @error('heure_debut')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-slate-300 font-medium mb-2">Heure de fin</label>
                    <input type="time" name="heure_fin" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white" required>
                    @error('heure_fin')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-slate-300 font-medium mb-2">Lieu</label>
                    <input type="text" name="lieu" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white" required>
                    @error('lieu')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-slate-300 font-medium mb-2">Instructeur</label>
                    <input type="text" name="instructeur" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white" required>
                    @error('instructeur')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div>
                <label class="block text-slate-300 font-medium mb-2">Description (optionnel)</label>
                <textarea name="description" rows="4" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white" placeholder="Description du séminaire..."></textarea>
                @error('description')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex items-center space-x-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    Créer le séminaire
                </button>
                <a href="{{ route('admin.seminaires.index') }}" class="px-6 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg transition-colors">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
