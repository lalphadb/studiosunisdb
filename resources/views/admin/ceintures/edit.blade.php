@extends('layouts.admin')

@section('title', 'Modifier Attribution Ceinture')

@section('content')
<div class="admin-content">
    {{-- Header --}}
    <div class="admin-header">
        <div>
            <h1 class="admin-title">✏️ Modifier Attribution</h1>
            <p class="admin-subtitle">{{ $progression->user->name }} - {{ $progression->ceinture->nom }}</p>
        </div>
        <div class="admin-actions">
            <a href="{{ route('admin.ceintures.show', $progression) }}" class="btn btn-secondary">
                ← Retour aux détails
            </a>
        </div>
    </div>

    {{-- Formulaire --}}
    <div class="admin-card">
        <form method="POST" action="{{ route('admin.ceintures.update', $progression) }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Informations membre (lecture seule) --}}
            <div class="bg-gray-900 rounded-lg p-4">
                <h4 class="font-medium text-white mb-2">👤 Membre</h4>
                <p class="text-gray-300">{{ $progression->user->name }} - {{ $progression->user->ecole->nom ?? 'N/A' }}</p>
            </div>

            {{-- Sélection ceinture --}}
            <div>
                <label for="ceinture_id" class="form-label">
                    🏆 Ceinture *
                </label>
                <select name="ceinture_id" id="ceinture_id" required class="form-select @error('ceinture_id') border-red-500 @enderror">
                    @foreach($ceintures as $ceinture)
                        <option value="{{ $ceinture->id }}" {{ old('ceinture_id', $progression->ceinture_id) == $ceinture->id ? 'selected' : '' }}>
                            {{ $ceinture->nom }} (Ordre {{ $ceinture->ordre }})
                        </option>
                    @endforeach
                </select>
                @error('ceinture_id')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Date et examinateur --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label for="date_obtention" class="form-label">
                        📅 Date d'Obtention *
                    </label>
                    <input type="date" name="date_obtention" id="date_obtention" required
                           value="{{ old('date_obtention', $progression->date_obtention->format('Y-m-d')) }}"
                           max="{{ date('Y-m-d') }}"
                           class="form-input @error('date_obtention') border-red-500 @enderror">
                    @error('date_obtention')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="examinateur" class="form-label">
                        👨‍🏫 Examinateur
                    </label>
                    <input type="text" name="examinateur" id="examinateur" 
                           value="{{ old('examinateur', $progression->examinateur) }}"
                           class="form-input @error('examinateur') border-red-500 @enderror">
                    @error('examinateur')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Commentaires --}}
            <div>
                <label for="commentaires" class="form-label">
                    📝 Commentaires
                </label>
                <textarea name="commentaires" id="commentaires" rows="4"
                          placeholder="Commentaires sur l'attribution..."
                          class="form-textarea @error('commentaires') border-red-500 @enderror">{{ old('commentaires', $progression->commentaires) }}</textarea>
                @error('commentaires')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Validation --}}
            <div class="flex items-center">
                <input type="checkbox" name="valide" id="valide" value="1" 
                       {{ old('valide', $progression->valide) ? 'checked' : '' }}
                       class="rounded border-gray-600 text-blue-600 focus:ring-blue-500 bg-gray-700">
                <label for="valide" class="ml-2 text-sm text-gray-300">
                    Ceinture validée
                </label>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between pt-6 border-t border-gray-700">
                <a href="{{ route('admin.ceintures.show', $progression) }}" class="btn btn-secondary">
                    ❌ Annuler
                </a>
                
                <button type="submit" class="btn btn-primary">
                    ✅ Mettre à Jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
