@extends('layouts.admin')

@section('title', 'Attribuer une Ceinture')

@section('content')
<div class="admin-content">
    {{-- Header --}}
    <div class="admin-header">
        <div>
            <h1 class="admin-title">🥋 Attribuer une Ceinture</h1>
            <p class="admin-subtitle">Enregistrer une nouvelle progression</p>
        </div>
        <div class="admin-actions">
            <a href="{{ route('admin.ceintures.index') }}" class="btn btn-secondary">
                ← Retour à la liste
            </a>
        </div>
    </div>

    {{-- Formulaire --}}
    <div class="admin-card">
        <form method="POST" action="{{ route('admin.ceintures.store') }}" class="space-y-6">
            @csrf

            {{-- Sélection du membre --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label for="user_id" class="form-label">
                        👤 Membre *
                    </label>
                    <select name="user_id" id="user_id" required class="form-select @error('user_id') border-red-500 @enderror">
                        <option value="">Sélectionner un membre...</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $userSelectionne?->id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                                @if($user->ecole)
                                    - {{ $user->ecole->nom }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="ceinture_id" class="form-label">
                        🏆 Nouvelle Ceinture *
                    </label>
                    <select name="ceinture_id" id="ceinture_id" required class="form-select @error('ceinture_id') border-red-500 @enderror">
                        <option value="">Sélectionner une ceinture...</option>
                        @foreach($ceintures as $ceinture)
                            <option value="{{ $ceinture->id }}" {{ old('ceinture_id') == $ceinture->id ? 'selected' : '' }}>
                                {{ $ceinture->nom }} (Ordre {{ $ceinture->ordre }})
                            </option>
                        @endforeach
                    </select>
                    @error('ceinture_id')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Date et examinateur --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label for="date_obtention" class="form-label">
                        📅 Date d'Obtention *
                    </label>
                    <input type="date" name="date_obtention" id="date_obtention" required
                           value="{{ old('date_obtention', date('Y-m-d')) }}"
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
                           value="{{ old('examinateur') }}"
                           placeholder="Nom de l'examinateur"
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
                          placeholder="Commentaires sur l'attribution, performance, etc..."
                          class="form-textarea @error('commentaires') border-red-500 @enderror">{{ old('commentaires') }}</textarea>
                @error('commentaires')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Validation --}}
            <div class="flex items-center">
                <input type="checkbox" name="valide" id="valide" value="1" 
                       {{ old('valide', true) ? 'checked' : '' }}
                       class="rounded border-gray-600 text-blue-600 focus:ring-blue-500 bg-gray-700">
                <label for="valide" class="ml-2 text-sm text-gray-300">
                    Ceinture validée (recommandé)
                </label>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between pt-6 border-t border-gray-700">
                <a href="{{ route('admin.ceintures.index') }}" class="btn btn-secondary">
                    ❌ Annuler
                </a>
                
                <button type="submit" class="btn btn-primary">
                    ✅ Attribuer la Ceinture
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
