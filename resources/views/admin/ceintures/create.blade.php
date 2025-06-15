@extends('layouts.admin')

@section('title', 'Attribuer une Ceinture')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-green-600 to-blue-600 rounded-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">🥋 Attribuer une Ceinture</h1>
                <p class="text-green-100 text-lg">Enregistrer une nouvelle progression</p>
            </div>
            <a href="{{ route('admin.ceintures.index') }}" class="bg-white text-green-600 hover:bg-green-50 px-4 py-2 rounded-lg font-medium transition-colors">
                ← Retour à la liste
            </a>
        </div>
    </div>

    {{-- Formulaire --}}
    <div class="bg-gray-800 rounded-lg shadow-md border border-gray-700">
        <div class="p-6 border-b border-gray-700">
            <h3 class="text-lg font-medium text-white">📝 Attribution de ceinture</h3>
            <p class="text-sm text-gray-400 mt-1">Complétez les informations pour enregistrer la progression</p>
        </div>

        <form method="POST" action="{{ route('admin.ceintures.store') }}" class="p-6 space-y-6">
            @csrf

            {{-- Sélection du membre --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label for="membre_id" class="block text-sm font-medium text-gray-300 mb-2">
                        👤 Membre *
                    </label>
                    <select name="membre_id" id="membre_id" required 
                            class="w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-green-500 focus:ring-green-500 @error('membre_id') border-red-500 @enderror">
                        <option value="">Sélectionner un membre...</option>
                        @foreach($membres as $membre)
                            <option value="{{ $membre->id }}" {{ old('membre_id', $membreSelectionne?->id) == $membre->id ? 'selected' : '' }}>
                                {{ $membre->nom_complet }} 
                                @if($membre->ecole)
                                    - {{ $membre->ecole->nom }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('membre_id')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="ceinture_id" class="block text-sm font-medium text-gray-300 mb-2">
                        🏆 Nouvelle Ceinture *
                    </label>
                    <select name="ceinture_id" id="ceinture_id" required 
                            class="w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-green-500 focus:ring-green-500 @error('ceinture_id') border-red-500 @enderror">
                        <option value="">Sélectionner une ceinture...</option>
                        @foreach($ceintures as $ceinture)
                            <option value="{{ $ceinture->id }}" {{ old('ceinture_id') == $ceinture->id ? 'selected' : '' }}>
                                {{ $ceinture->nom }} (Niveau {{ $ceinture->niveau }})
                            </option>
                        @endforeach
                    </select>
                    @error('ceinture_id')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Date d'obtention --}}
            <div>
                <label for="date_obtention" class="block text-sm font-medium text-gray-300 mb-2">
                    📅 Date d'Obtention *
                </label>
                <input type="date" name="date_obtention" id="date_obtention" required
                       value="{{ old('date_obtention', date('Y-m-d')) }}"
                       max="{{ date('Y-m-d') }}"
                       class="w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-green-500 focus:ring-green-500 @error('date_obtention') border-red-500 @enderror">
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
                          placeholder="Commentaires sur l'attribution, performance, etc..."
                          class="w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-green-500 focus:ring-green-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between pt-6 border-t border-gray-700">
                <a href="{{ route('admin.ceintures.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    ❌ Annuler
                </a>
                
                <button type="submit" 
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    ✅ Attribuer la Ceinture
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
