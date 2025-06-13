@extends('layouts.admin')

@section('title', 'Nouveau Cours')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-4xl font-bold text-white mb-2">➕ Nouveau Cours</h1>
        <p class="text-slate-400">Ajouter un cours au réseau Studios Unis</p>
    </div>
    <a href="{{ route('admin.cours.index') }}" class="hover-bg px-6 py-3 rounded-lg font-bold transition-all text-white border border-slate-600">
        ← Retour Liste
    </a>
</div>

<div class="card-bg rounded-xl shadow-xl p-8">
    <form action="{{ route('admin.cours.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nom du cours -->
            <div>
                <label class="block text-sm font-bold text-slate-300 mb-2">Nom du Cours *</label>
                <input type="text" name="nom" value="{{ old('nom') }}" required
                       class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
                       placeholder="Ex: Karaté Débutant">
                @error('nom')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- École -->
            <div>
                <label class="block text-sm font-bold text-slate-300 mb-2">École *</label>
                <select name="ecole_id" required
                        class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    <option value="">Sélectionner une école</option>
                    @foreach($ecoles as $ecole)
                        <option value="{{ $ecole->id }}" {{ old('ecole_id') == $ecole->id ? 'selected' : '' }}>
                            {{ $ecole->nom }}
                        </option>
                    @endforeach
                </select>
                @error('ecole_id')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Instructeur -->
            <div>
                <label class="block text-sm font-bold text-slate-300 mb-2">Instructeur</label>
                <select name="instructeur_id"
                        class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    <option value="">Sélectionner un instructeur</option>
                    @foreach($instructeurs as $instructeur)
                        <option value="{{ $instructeur->id }}" {{ old('instructeur_id') == $instructeur->id ? 'selected' : '' }}>
                            {{ $instructeur->name }}
                        </option>
                    @endforeach
                </select>
                @error('instructeur_id')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Capacité -->
            <div>
                <label class="block text-sm font-bold text-slate-300 mb-2">Capacité Maximum *</label>
                <input type="number" name="capacite_max" value="{{ old('capacite_max', 20) }}" required min="1"
                       class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
                       placeholder="20">
                @error('capacite_max')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Prix -->
            <div>
                <label class="block text-sm font-bold text-slate-300 mb-2">Prix Mensuel ($) *</label>
                <input type="number" name="prix" value="{{ old('prix') }}" required min="0" step="0.01"
                       class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
                       placeholder="80.00">
                @error('prix')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Statut -->
            <div>
                <label class="block text-sm font-bold text-slate-300 mb-2">Statut *</label>
                <select name="statut" required
                        class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    <option value="actif" {{ old('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="inactif" {{ old('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                </select>
                @error('statut')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Description -->
        <div class="mt-6">
            <label class="block text-sm font-bold text-slate-300 mb-2">Description</label>
            <textarea name="description" rows="4"
                      class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
                      placeholder="Décrivez le cours, les objectifs, le niveau requis...">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Dates -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div>
                <label class="block text-sm font-bold text-slate-300 mb-2">Date de Début *</label>
                <input type="date" name="date_debut" value="{{ old('date_debut') }}" required
                       class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                @error('date_debut')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-300 mb-2">Date de Fin</label>
                <input type="date" name="date_fin" value="{{ old('date_fin') }}"
                       class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                @error('date_fin')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Boutons -->
        <div class="flex justify-end space-x-4 mt-8">
            <a href="{{ route('admin.cours.index') }}" 
               class="px-6 py-3 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition font-bold">
                Annuler
            </a>
            <button type="submit" 
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold">
                ✅ Créer le Cours
            </button>
        </div>
    </form>
</div>
@endsection
