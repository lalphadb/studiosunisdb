@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-slate-900 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-white">✏️ Modifier Séminaire</h1>
                <p class="text-slate-400 mt-2">{{ $seminaire->titre }}</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.seminaires.show', $seminaire) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                    👁️ Voir
                </a>
                <a href="{{ route('admin.seminaires.index') }}" 
                   class="bg-slate-600 hover:bg-slate-500 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                    ← Retour
                </a>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="bg-slate-800 rounded-lg p-8">
            <form method="POST" action="{{ route('admin.seminaires.update', $seminaire) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Informations Générales -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                        📋 Informations Générales
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Titre du Séminaire *</label>
                            <input type="text" name="titre" value="{{ old('titre', $seminaire->titre) }}" required
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                            @error('titre')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Type *</label>
                            <select name="type" required
                                    class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                                <option value="technique" {{ old('type', $seminaire->type) == 'technique' ? 'selected' : '' }}>🥋 Technique</option>
                                <option value="kata" {{ old('type', $seminaire->type) == 'kata' ? 'selected' : '' }}>🎭 Kata</option>
                                <option value="competition" {{ old('type', $seminaire->type) == 'competition' ? 'selected' : '' }}>🏆 Compétition</option>
                                <option value="arbitrage" {{ old('type', $seminaire->type) == 'arbitrage' ? 'selected' : '' }}>⚖️ Arbitrage</option>
                                <option value="grade" {{ old('type', $seminaire->type) == 'grade' ? 'selected' : '' }}>🎓 Passage de Grade</option>
                            </select>
                            @error('type')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">École *</label>
                            <select name="ecole_id" required
                                    class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                                @foreach($ecoles as $ecole)
                                <option value="{{ $ecole->id }}" {{ old('ecole_id', $seminaire->ecole_id) == $ecole->id ? 'selected' : '' }}>
                                    {{ $ecole->nom }}
                                </option>
                                @endforeach
                            </select>
                            @error('ecole_id')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Instructeur Principal</label>
                            <input type="text" name="instructeur" value="{{ old('instructeur', $seminaire->instructeur) }}"
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                            @error('instructeur')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        @if($seminaire->inscriptions->count() > 0)
                        <div class="text-sm text-slate-400">
                            {{ $seminaire->inscriptions->count() }} participant(s) inscrit(s)
                        </div>
                        @endif
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.seminaires.show', $seminaire) }}" 
                           class="bg-slate-600 hover:bg-slate-500 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="bg-pink-600 hover:bg-pink-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                            Mettre à Jour
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
