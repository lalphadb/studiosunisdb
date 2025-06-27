@extends('layouts.admin')
@section('title', 'Attribution Ceinture')

@section('content')
<div class="space-y-6">
    <!-- Header avec gradient qui fade vers transparent -->
    <div class="bg-gradient-to-r from-orange-500 via-red-600 to-transparent rounded-xl p-6 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-transparent to-slate-900 opacity-60"></div>
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold flex items-center">
                    🥋 Attribution Individuelle de Ceinture
                </h1>
                <p class="text-orange-100 text-lg">Attribuer une nouvelle ceinture à un membre</p>
            </div>
            <a href="{{ route('admin.ceintures.index') }}" 
               class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                Retour
            </a>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <form method="POST" action="{{ route('admin.ceintures.store') }}" class="space-y-6">
            @csrf
            
            <!-- Sélection membre -->
            <div>
                <label for="user_id" class="block text-sm font-medium text-slate-300 mb-2">
                    Membre <span class="text-red-400">*</span>
                </label>
                <select name="user_id" id="user_id" required
                        class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500 @error('user_id') border-red-500 @enderror">
                    <option value="">Sélectionner un membre</option>
                    @foreach($membres as $membre)
                    <option value="{{ $membre->id }}" 
                            {{ old('user_id', $user?->id) == $membre->id ? 'selected' : '' }}>
                        {{ $membre->name }} - {{ $membre->ecole->nom ?? 'École non assignée' }}
                    </option>
                    @endforeach
                </select>
                @error('user_id')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Sélection ceinture SANS codes couleur -->
            <div>
                <label for="ceinture_id" class="block text-sm font-medium text-slate-300 mb-2">
                    Ceinture à attribuer <span class="text-red-400">*</span>
                </label>
                <select name="ceinture_id" id="ceinture_id" required
                        class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500 @error('ceinture_id') border-red-500 @enderror">
                    <option value="">Sélectionner une ceinture</option>
                    @foreach($ceintures as $ceinture)
                    <option value="{{ $ceinture->id }}" {{ old('ceinture_id') == $ceinture->id ? 'selected' : '' }}>
                        {{ $ceinture->ordre }}. {{ $ceinture->nom }}
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
                       value="{{ old('date_obtention', now()->format('Y-m-d')) }}"
                       max="{{ now()->format('Y-m-d') }}"
                       required
                       class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500 @error('date_obtention') border-red-500 @enderror">
                @error('date_obtention')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-slate-300 mb-2">
                    Notes (optionnel)
                </label>
                <textarea name="notes" 
                          id="notes" 
                          rows="3"
                          placeholder="Commentaires sur l'examen, performances, etc."
                          class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
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
                       {{ old('valide', true) ? 'checked' : '' }}
                       class="w-4 h-4 text-orange-600 bg-slate-700 border-slate-600 rounded focus:ring-orange-500 focus:ring-2">
                <label for="valide" class="ml-2 text-sm text-slate-300">
                    Ceinture validée (cocher si l'attribution est confirmée)
                </label>
            </div>

            <!-- Boutons -->
            <div class="flex items-center justify-between pt-6 border-t border-slate-700">
                <a href="{{ route('admin.ceintures.index') }}" 
                   class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                    Annuler
                </a>
                <button type="submit" 
                        class="bg-orange-600 hover:bg-orange-700 text-white px-8 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Attribuer la Ceinture
                </button>
            </div>
        </form>
    </div>

    <!-- Aperçu visuel des ceintures -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <h3 class="text-lg font-semibold text-white mb-4">🎨 Aperçu des Ceintures</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
            @foreach($ceintures as $ceinture)
            <div class="text-center p-3 bg-slate-700 rounded-lg">
                <div class="w-8 h-8 rounded-full mx-auto mb-2" style="background-color: {{ $ceinture->couleur }}"></div>
                <div class="text-xs text-slate-300">{{ $ceinture->ordre }}. {{ $ceinture->nom }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@if($user)
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Si on arrive depuis la fiche d'un membre, le pré-sélectionner
    const userSelect = document.getElementById('user_id');
    userSelect.value = '{{ $user->id }}';
    userSelect.focus();
});
</script>
@endif
@endsection
