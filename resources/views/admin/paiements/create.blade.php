@extends('layouts.admin')

@section('title', 'Nouveau Paiement')

@section('content')
<div class="space-y-6">
    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700">
        <h1 class="text-2xl font-bold text-white mb-6">Nouveau Paiement</h1>
        
        <form method="POST" action="{{ route('admin.paiements.store') }}" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-slate-300 font-medium mb-2">Utilisateur</label>
                    <select name="user_id" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white" required>
                        <option value="">Sélectionner un utilisateur</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-slate-300 font-medium mb-2">Montant</label>
                    <input type="number" step="0.01" name="montant" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white" required>
                    @error('montant')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-slate-300 font-medium mb-2">Motif</label>
                    <select name="motif" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white" required>
                        <option value="">Sélectionner un motif</option>
                        <option value="session_automne">Session Automne</option>
                        <option value="session_hiver">Session Hiver</option>
                        <option value="session_printemps">Session Printemps</option>
                        <option value="session_ete">Session Été</option>
                        <option value="seminaire">Séminaire</option>
                        <option value="examen_ceinture">Examen Ceinture</option>
                        <option value="equipement">Équipement</option>
                        <option value="autre">Autre</option>
                    </select>
                    @error('motif')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div>
                <label class="block text-slate-300 font-medium mb-2">Description (optionnel)</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white" placeholder="Description du paiement..."></textarea>
                @error('description')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex items-center space-x-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    Créer le paiement
                </button>
                <a href="{{ route('admin.paiements.index') }}" class="px-6 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg transition-colors">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
