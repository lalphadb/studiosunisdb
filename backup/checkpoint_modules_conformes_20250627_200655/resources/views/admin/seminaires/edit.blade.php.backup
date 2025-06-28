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
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('titre')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Type *</label>
                            <select name="type" required
                                    class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                                    class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                            <input type="datetime-local" name="date_debut" 
                                   value="{{ old('date_debut', $seminaire->date_debut->format('Y-m-d\TH:i')) }}" required
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('date_debut')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Date de Fin</label>
                            <input type="datetime-local" name="date_fin" 
                                   value="{{ old('date_fin', $seminaire->date_fin ? $seminaire->date_fin->format('Y-m-d\TH:i') : '') }}"
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('date_fin')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Durée (minutes)</label>
                            <input type="number" name="duree" value="{{ old('duree', $seminaire->duree) }}" min="30" max="480"
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('duree')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Lieu</label>
                            <input type="text" name="lieu" value="{{ old('lieu', $seminaire->lieu) }}"
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
                            <input type="number" name="max_participants" value="{{ old('max_participants', $seminaire->max_participants) }}" min="1"
                                   class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('max_participants')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Coût ($)</label>
                            <input type="number" name="cout" value="{{ old('cout', $seminaire->cout) }}" min="0" step="0.01"
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
                                      class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $seminaire->description) }}</textarea>
                            @error('description')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Objectifs</label>
                            <textarea name="objectifs" rows="3"
                                      class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('objectifs', $seminaire->objectifs) }}</textarea>
                            @error('objectifs')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Prérequis</label>
                            <textarea name="prerequis" rows="2"
                                      class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('prerequis', $seminaire->prerequis) }}</textarea>
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
                                <option value="planifie" {{ old('statut', $seminaire->statut) == 'planifie' ? 'selected' : '' }}>📅 Planifié</option>
                                <option value="ouvert" {{ old('statut', $seminaire->statut) == 'ouvert' ? 'selected' : '' }}>✅ Ouvert aux inscriptions</option>
                                <option value="complet" {{ old('statut', $seminaire->statut) == 'complet' ? 'selected' : '' }}>⚠️ Complet</option>
                                <option value="termine" {{ old('statut', $seminaire->statut) == 'termine' ? 'selected' : '' }}>✅ Terminé</option>
                                <option value="annule" {{ old('statut', $seminaire->statut) == 'annule' ? 'selected' : '' }}>❌ Annulé</option>
                            </select>
                            @error('statut')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Certificat</label>
                            <select name="certificat"
                                    class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="0" {{ old('certificat', $seminaire->certificat) == '0' ? 'selected' : '' }}>Non</option>
                                <option value="1" {{ old('certificat', $seminaire->certificat) == '1' ? 'selected' : '' }}>Oui</option>
                            </select>
                            @error('certificat')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Statistiques (si inscriptions existantes) -->
                @if($seminaire->inscriptions->count() > 0)
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                        📊 Statistiques Actuelles
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-slate-700 rounded-lg p-4">
                            <div class="text-slate-400 text-sm">Inscrits</div>
                            <div class="text-2xl font-bold text-white">{{ $seminaire->inscriptions->count() }}</div>
                        </div>
                        <div class="bg-slate-700 rounded-lg p-4">
                            <div class="text-slate-400 text-sm">Présents</div>
                            <div class="text-2xl font-bold text-green-400">{{ $seminaire->inscriptions->where('statut', 'present')->count() }}</div>
                        </div>
                        <div class="bg-slate-700 rounded-lg p-4">
                            <div class="text-slate-400 text-sm">Absents</div>
                            <div class="text-2xl font-bold text-red-400">{{ $seminaire->inscriptions->where('statut', 'absent')->count() }}</div>
                        </div>
                        <div class="bg-slate-700 rounded-lg p-4">
                            <div class="text-slate-400 text-sm">En attente</div>
                            <div class="text-2xl font-bold text-yellow-400">{{ $seminaire->inscriptions->where('statut', 'inscrit')->count() }}</div>
                        </div>
                    </div>
                    <div class="mt-4 p-4 bg-blue-500/10 border border-blue-500/20 rounded-lg">
                        <p class="text-blue-400 text-sm">
                            ⚠️ <strong>Attention :</strong> Modifier certains paramètres peut affecter les {{ $seminaire->inscriptions->count() }} participants déjà inscrits.
                        </p>
                    </div>
                </div>
                @endif

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
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                            Mettre à Jour
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Actions Dangereuses -->
        @if($seminaire->inscriptions->count() > 0 || $seminaire->statut == 'termine')
        <div class="mt-8 bg-red-900/20 border border-red-500/20 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-red-400 mb-4 flex items-center">
                ⚠️ Actions Avancées
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($seminaire->inscriptions->count() > 0)
                <div>
                    <h4 class="text-white font-medium mb-2">Gestion des Participants</h4>
                    <div class="space-y-2">
                        <button class="w-full bg-yellow-600 hover:bg-yellow-700 text-white py-2 px-4 rounded-lg text-sm transition-colors">
                            📧 Notifier Changements
                        </button>
                        <button class="w-full bg-orange-600 hover:bg-orange-700 text-white py-2 px-4 rounded-lg text-sm transition-colors">
                            📋 Export Participants
                        </button>
                    </div>
                </div>
                @endif

                @if($seminaire->statut == 'termine')
                <div>
                    <h4 class="text-white font-medium mb-2">Séminaire Terminé</h4>
                    <div class="space-y-2">
                        @if($seminaire->certificat)
                        <button class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg text-sm transition-colors">
                            📜 Générer Certificats
                        </button>
                        @endif
                        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-sm transition-colors">
                            📊 Rapport Final
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
