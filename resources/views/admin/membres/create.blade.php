@extends('layouts.admin')

@section('title', 'Nouveau Membre')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-white mb-8">Nouveau Membre</h1>
    
    <div class="bg-slate-800 rounded-lg border border-slate-700 p-6">
        <form method="POST" action="{{ route('admin.membres.store') }}">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-white mb-2">Prénom *</label>
                    <input type="text" name="prenom" value="{{ old('prenom') }}" required
                           class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    @error('prenom')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-white mb-2">Nom *</label>
                    <input type="text" name="nom" value="{{ old('nom') }}" required
                           class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    @error('nom')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-white mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    @error('email')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-white mb-2">Téléphone</label>
                    <input type="tel" name="telephone" value="{{ old('telephone') }}"
                           class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    @error('telephone')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-white mb-2">Date de naissance</label>
                    <input type="date" name="date_naissance" value="{{ old('date_naissance') }}"
                           class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    @error('date_naissance')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-white mb-2">École *</label>
                    <select name="ecole_id" required
                            class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner une école</option>
                        @foreach($ecoles as $ecole)
                        <option value="{{ $ecole->id }}" {{ old('ecole_id') == $ecole->id ? 'selected' : '' }}>
                            {{ $ecole->nom }}
                        </option>
                        @endforeach
                    </select>
                    @error('ecole_id')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-white mb-2">Date d'inscription *</label>
                    <input type="date" name="date_inscription" value="{{ old('date_inscription', date('Y-m-d')) }}" required
                           class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    @error('date_inscription')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-white mb-2">Statut *</label>
                    <select name="statut" required
                            class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="actif" {{ old('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="inactif" {{ old('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                        <option value="suspendu" {{ old('statut') == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                    </select>
                    @error('statut')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            
            <div class="flex justify-end mt-8 space-x-4">
                <a href="{{ route('admin.membres.index') }}" 
                   class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-2 rounded-lg transition-colors">
                    Annuler
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                    Créer le membre
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
