@extends('layouts.admin')
@section('title', 'Modifier Attribution')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-yellow-500 to-orange-600 rounded-xl p-6 text-white">
        <h1 class="text-3xl font-bold flex items-center">
            <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Modifier Attribution de Ceinture
        </h1>
        <p class="text-yellow-100 text-lg">Modifier l'attribution pour {{ $userCeinture->user->name }}</p>
    </div>

    <!-- Formulaire -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <form method="POST" action="{{ route('admin.ceintures.update', $userCeinture) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Informations membre (lecture seule) -->
            <div class="bg-slate-900 rounded-lg p-4 border border-slate-600">
                <h3 class="text-lg font-medium text-white mb-3">Membre concerné</h3>
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12">
                        <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-yellow-500 to-orange-600 flex items-center justify-center">
                            <span class="text-white font-bold text-sm">{{ substr($userCeinture->user->name, 0, 2) }}</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-lg font-medium text-white">{{ $userCeinture->user->name }}</div>
                        <div class="text-sm text-slate-400">{{ $userCeinture->user->email }}</div>
                        <div class="text-sm text-slate-400">{{ $userCeinture->ecole->nom }}</div>
                    </div>
                </div>
                <input type="hidden" name="user_id" value="{{ $userCeinture->user_id }}">
            </div>

            <!-- Sélection ceinture -->
            <div>
                <label for="ceinture_id" class="block text-sm font-medium text-slate-300 mb-2">
                    Ceinture <span class="text-red-400">*</span>
                </label>
                <select name="ceinture_id" id="ceinture_id" required
                        class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-yellow-500 @error('ceinture_id') border-red-500 @enderror">
                    @foreach($ceintures as $ceinture)
                    <option value="{{ $ceinture->id }}" 
                            {{ old('ceinture_id', $userCeinture->ceinture_id) == $ceinture->id ? 'selected' : '' }}>
                        {{ $ceinture->nom }} ({{ $ceinture->couleur }})
                    </option>
                    @endforeach
                </select>
                @error('ceinture_id')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date d'obtention -->
            <div>
                <label for="date_obtention" class="block text-sm font-medium text-slate-300 mb-2">
                    Date d'obtention <span class="text-red-400">*</span>
                </label>
                <input type="date" 
                       name="date_obtention" 
                       id="date_obtention"
                       value="{{ old('date_obtention', $userCeinture->date_obtention->format('Y-m-d')) }}"
                       max="{{ now()->format('Y-m-d') }}"
                       required
                       class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-yellow-500 @error('date_obtention') border-red-500 @enderror">
                @error('date_obtention')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-slate-300 mb-2">
                    Notes
                </label>
                <textarea name="notes" 
                          id="notes" 
                          rows="3"
                          placeholder="Commentaires sur l'examen, performances, etc."
                          class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 @error('notes') border-red-500 @enderror">{{ old('notes', $userCeinture->notes) }}</textarea>
                @error('notes')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Statut validation -->
            <div class="flex items-center">
                <input type="checkbox" 
                       name="valide" 
                       id="valide" 
                       value="1"
                       {{ old('valide', $userCeinture->valide) ? 'checked' : '' }}
                       class="w-4 h-4 text-yellow-600 bg-slate-700 border-slate-600 rounded focus:ring-yellow-500 focus:ring-2">
                <label for="valide" class="ml-2 text-sm text-slate-300">
                    Ceinture validée
                </label>
            </div>

            <!-- Informations système -->
            <div class="bg-slate-900 rounded-lg p-4 border border-slate-600">
                <h3 class="text-sm font-medium text-slate-400 mb-2">Informations système</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-slate-400">Attribuée par:</span>
                        <span class="text-white">{{ $userCeinture->instructeur->name ?? 'Non renseigné' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400">Date création:</span>
                        <span class="text-white">{{ $userCeinture->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($userCeinture->examen_id)
                    <div>
                        <span class="text-slate-400">Examen groupé:</span>
                        <span class="text-blue-400">{{ $userCeinture->examen_id }}</span>
                    </div>
                    @endif
                    <div>
                        <span class="text-slate-400">Dernière modification:</span>
                        <span class="text-white">{{ $userCeinture->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Boutons -->
            <div class="flex items-center justify-between pt-6 border-t border-slate-700">
                <a href="{{ route('admin.ceintures.index') }}" 
                   class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                    Annuler
                </a>
                <div class="flex space-x-3">
                    <!-- Bouton Supprimer -->
                    <form method="POST" action="{{ route('admin.ceintures.destroy', $userCeinture) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette attribution ?')">
                            Supprimer
                        </button>
                    </form>
                    
                    <!-- Bouton Sauvegarder -->
                    <button type="submit" 
                            class="bg-yellow-600 hover:bg-yellow-700 text-white px-8 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Sauvegarder
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
