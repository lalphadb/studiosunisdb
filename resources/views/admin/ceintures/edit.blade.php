@extends('layouts.admin')

@section('title', 'Modifier Attribution Ceinture')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-yellow-600 to-orange-600 rounded-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">✏️ Modifier Attribution</h1>
                <p class="text-yellow-100 text-lg">{{ $progression->membre->nom_complet }} - {{ $progression->ceinture->nom }}</p>
            </div>
            <a href="{{ route('admin.ceintures.show', $progression) }}" class="bg-white text-yellow-600 hover:bg-yellow-50 px-4 py-2 rounded-lg font-medium transition-colors">
                ← Retour aux détails
            </a>
        </div>
    </div>

    {{-- Formulaire --}}
    <div class="bg-gray-800 rounded-lg shadow-md border border-gray-700">
        <div class="p-6 border-b border-gray-700">
            <h3 class="text-lg font-medium text-white">📝 Modifier l'attribution</h3>
            <p class="text-sm text-gray-400 mt-1">Corrigez les informations de cette progression</p>
        </div>

        <form method="POST" action="{{ route('admin.ceintures.update', $progression) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            {{-- Informations membre (lecture seule) --}}
            <div class="bg-gray-900 rounded-lg p-4">
                <h4 class="font-medium text-white mb-2">👤 Membre</h4>
                <p class="text-gray-300">{{ $progression->membre->nom_complet }} - {{ $progression->membre->ecole->nom ?? 'N/A' }}</p>
            </div>

            {{-- Sélection ceinture --}}
            <div>
                <label for="ceinture_id" class="block text-sm font-medium text-gray-300 mb-2">
                    🏆 Ceinture *
                </label>
                <select name="ceinture_id" id="ceinture_id" required 
                        class="w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-yellow-500 focus:ring-yellow-500 @error('ceinture_id') border-red-500 @enderror">
                    @foreach($ceintures as $ceinture)
                        <option value="{{ $ceinture->id }}" {{ old('ceinture_id', $progression->ceinture_id) == $ceinture->id ? 'selected' : '' }}>
                            {{ $ceinture->nom }} (Niveau {{ $ceinture->niveau }})
                        </option>
                    @endforeach
                </select>
                @error('ceinture_id')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Date d'obtention --}}
            <div>
                <label for="date_obtention" class="block text-sm font-medium text-gray-300 mb-2">
                    📅 Date d'Obtention *
                </label>
                <input type="date" name="date_obtention" id="date_obtention" required
                       value="{{ old('date_obtention', $progression->date_obtention->format('Y-m-d')) }}"
                       max="{{ date('Y-m-d') }}"
                       class="w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-yellow-500 focus:ring-yellow-500 @error('date_obtention') border-red-500 @enderror">
                @error('date_obtention')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Notes --}}
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-300 mb-2">
                    📝 Notes
                </label>
                <textarea name="notes" id="notes" rows="4"
                          placeholder="Commentaires sur l'attribution..."
                          class="w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-yellow-500 focus:ring-yellow-500 @error('notes') border-red-500 @enderror">{{ old('notes', $progression->notes) }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between pt-6 border-t border-gray-700">
                <a href="{{ route('admin.ceintures.show', $progression) }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    ❌ Annuler
                </a>
                
                <button type="submit" 
                        class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    ✅ Mettre à Jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
