@extends('layouts.admin')

@section('title', 'Attribuer Ceinture')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Attribuer {{ $ceinture->nom }}
                </h1>
                <p class="text-green-100 text-lg">Attribuer cette ceinture à un utilisateur</p>
            </div>
            <a href="{{ route('admin.ceintures.show', $ceinture) }}" 
               class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour
            </a>
        </div>
    </div>

    {{-- Formulaire --}}
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <form method="POST" action="{{ route('admin.ceintures.attribuer', $ceinture) }}" class="space-y-6">
            @csrf

            {{-- Sélection utilisateur --}}
            <div>
                <label for="user_id" class="block text-sm font-medium text-slate-300 mb-2">
                    Sélectionner l'Utilisateur *
                </label>
                <select name="user_id" id="user_id" required
                        class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('user_id') border-red-500 @enderror">
                    <option value="">-- Choisir un utilisateur --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                            @if($user->ecole)
                                - {{ $user->ecole->nom }}
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Date d'obtention --}}
                <div>
                    <label for="date_obtention" class="block text-sm font-medium text-slate-300 mb-2">
                        Date d'Obtention *
                    </label>
                    <input type="date" name="date_obtention" id="date_obtention" required
                           value="{{ old('date_obtention', date('Y-m-d')) }}"
                           max="{{ date('Y-m-d') }}"
                           class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('date_obtention') border-red-500 @enderror">
                    @error('date_obtention')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Examinateur --}}
                <div>
                    <label for="examinateur" class="block text-sm font-medium text-slate-300 mb-2">
                        Examinateur
                    </label>
                    <input type="text" name="examinateur" id="examinateur"
                           value="{{ old('examinateur') }}"
                           placeholder="Nom de l'examinateur"
                           class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('examinateur') border-red-500 @enderror">
                    @error('examinateur')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Commentaires --}}
            <div>
                <label for="commentaires" class="block text-sm font-medium text-slate-300 mb-2">
                    Commentaires
                </label>
                <textarea name="commentaires" id="commentaires" rows="4"
                          placeholder="Commentaires sur l'attribution, performance, etc..."
                          class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('commentaires') border-red-500 @enderror">{{ old('commentaires') }}</textarea>
                @error('commentaires')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Validation --}}
            <div class="flex items-center">
                <input type="checkbox" name="valide" id="valide" value="1" 
                       {{ old('valide', true) ? 'checked' : '' }}
                       class="rounded border-slate-600 text-green-600 focus:ring-green-500 bg-slate-700">
                <label for="valide" class="ml-2 text-sm text-slate-300">
                    Ceinture validée (recommandé)
                </label>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between pt-6 border-t border-slate-700">
                <a href="{{ route('admin.ceintures.show', $ceinture) }}" 
                   class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-2 rounded-lg font-medium transition duration-200">
                    Annuler
                </a>
                
                <button type="submit" 
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition duration-200">
                    Attribuer la Ceinture
                </button>
            </div>
        </form>
    </div>

    {{-- Historique récent --}}
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Attributions Récentes</h3>
        
        @php
            $recent = \App\Models\UserCeinture::with(['user', 'ceinture'])
                ->latest('created_at')
                ->limit(5)
                ->get();
        @endphp
        
        @if($recent->count() > 0)
            <div class="space-y-3">
                @foreach($recent as $attribution)
                    <div class="flex items-center justify-between p-3 bg-slate-700 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3" style="background-color: {{ $attribution->ceinture->couleur }}">
                                <span class="text-white text-xs font-bold">{{ $attribution->ceinture->ordre }}</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-white">{{ $attribution->user->name }}</div>
                                <div class="text-xs text-slate-400">{{ $attribution->ceinture->nom }}</div>
                            </div>
                        </div>
                        <div class="text-xs text-slate-400">
                            {{ $attribution->created_at->diffForHumans() }}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-slate-400 text-center py-4">Aucune attribution récente</p>
        @endif
    </div>
</div>
@endsection
