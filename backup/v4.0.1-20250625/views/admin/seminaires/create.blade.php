@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-slate-900 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-white">🎓 Nouveau Séminaire</h1>
                <p class="text-slate-400 mt-2">Créer un nouvel événement ou formation spécialisée</p>
            </div>
            <a href="{{ route('admin.seminaires.index') }}" 
               class="bg-slate-600 hover:bg-slate-500 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                ← Retour
            </a>
        </div>

        <!-- Formulaire -->
        <div class="bg-slate-800 rounded-lg p-8">
            <form method="POST" action="{{ route('admin.seminaires.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Informations Générales -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                        📋 Informations Générales
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Titre du Séminaire *</label>
                            <input type="text" name="titre" value="{{ old('titre') }}" required
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('titre')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Type *</label>
                            <select name="type" required
                                    class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Choisir un type</option>
                                <option value="technique" {{ old('type') == 'technique' ? 'selected' : '' }}>🥋 Technique</option>
                                <option value="kata" {{ old('type') == 'kata' ? 'selected' : '' }}>🎭 Kata</option>
                                <option value="competition" {{ old('type') == 'competition' ? 'selected' : '' }}>🏆 Compétition</option>
                                <option value="arbitrage" {{ old('type') == 'arbitrage' ? 'selected' : '' }}>⚖️ Arbitrage</option>
                                <option value="grade" {{ old('type') == 'grade' ? 'selected' : '' }}>🎓 Passage de Grade</option>
                            </select>
                            @error('type')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">École *</label>
                            <select name="ecole_id" required
                                    class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Choisir une école</option>
                                @foreach($ecoles as $ecole)
                                <option value="{{ $ecole->id }}" {{ old('ecole_id') == (isset($ecole) ? $ecole->id : null) ? 'selected' : '' }}>
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
                            <input type="text" name="instructeur" value="{{ old('instructeur') }}"
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('instructeur')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Planification -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                        📅 Planification
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Date de Début *</label>
                            <input type="datetime-local" name="date_debut" value="{{ old('date_debut') }}" required
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('date_debut')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Date de Fin</label>
                            <input type="datetime-local" name="date_fin" value="{{ old('date_fin') }}"
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('date_fin')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Durée (minutes)</label>
                            <input type="number" name="duree" value="{{ old('duree', 120) }}" min="30" max="480"
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('duree')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Lieu</label>
                            <input type="text" name="lieu" value="{{ old('lieu') }}"
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('lieu')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Participants & Coûts -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                        👥 Participants & Coûts
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Max Participants</label>
                            <input type="number" name="max_participants" value="{{ old('max_participants') }}" min="1"
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('max_participants')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Coût ($)</label>
                            <input type="number" name="cout" value="{{ old('cout') }}" min="0" step="0.01"
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('cout')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Niveau</label>
                            <div class="bg-slate-700 border border-slate-600 text-green-400 rounded-lg px-4 py-3 flex items-center">
                                🥋 Ouvert à tous les niveaux
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                        📝 Description & Objectifs
                    </h2>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Description</label>
                            <textarea name="description" rows="4"
                                      class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description') }}</textarea>
                            @error('description')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Objectifs</label>
                            <textarea name="objectifs" rows="3"
                                      class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('objectifs') }}</textarea>
                            @error('objectifs')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Prérequis</label>
                            <textarea name="prerequis" rows="2"
                                      class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('prerequis') }}</textarea>
                            @error('prerequis')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Paramètres -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                        ⚙️ Paramètres
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Statut</label>
                            <select name="statut"
                                    class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="planifie" {{ old('statut') == 'planifie' ? 'selected' : '' }}>📅 Planifié</option>
                                <option value="ouvert" {{ old('statut') == 'ouvert' ? 'selected' : '' }}>✅ Ouvert aux inscriptions</option>
                            </select>
                            @error('statut')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Certificat</label>
                            <select name="certificat"
                                    class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="0" {{ old('certificat') == '0' ? 'selected' : '' }}>Non</option>
                                <option value="1" {{ old('certificat') == '1' ? 'selected' : '' }}>Oui</option>
                            </select>
                            @error('certificat')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.seminaires.index') }}" 
                       class="bg-slate-600 hover:bg-slate-500 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        Créer le Séminaire
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
