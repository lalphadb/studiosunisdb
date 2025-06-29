@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">👥 Inscrire un participant</h1>
                <p class="text-slate-400 mt-1">{{ $seminaire->titre }}</p>
            </div>
            <a href="{{ route('admin.seminaires.show', $seminaire) }}" 
               class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-lg transition-colors">
                ← Retour au séminaire
            </a>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        @if ($errors->any())
            <div class="bg-red-600/10 border border-red-600/20 rounded-lg p-4 mb-6">
                <ul class="text-red-400 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.seminaires.inscription.store', $seminaire) }}" class="space-y-6">
            @csrf
            
            <div>
                <label for="user_id" class="block text-sm font-medium text-slate-300 mb-2">
                    Sélectionner un utilisateur <span class="text-red-400">*</span>
                </label>
                <select name="user_id" id="user_id" required
                        class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-500">
                    <option value="">-- Choisir un utilisateur --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} 
                            @if($user->ecole)
                                ({{ $user->ecole->nom }})
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-4 pt-6 border-t border-slate-700">
                <a href="{{ route('admin.seminaires.show', $seminaire) }}" 
                   class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-2 rounded-lg transition-colors">
                    Annuler
                </a>
                <button type="submit" 
                        class="bg-pink-600 hover:bg-pink-700 text-white px-6 py-2 rounded-lg transition-colors">
                    👥 Inscrire le participant
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
