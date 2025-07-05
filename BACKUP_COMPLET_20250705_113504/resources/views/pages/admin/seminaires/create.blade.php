@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Header du module avec les bonnes couleurs séminaires -->
    <x-module-header 
        module="seminaires"
        title="Nouveau Séminaire" 
        subtitle="Organiser un nouveau séminaire inter-écoles"
        :createRoute="route('admin.seminaires.index')"
        createText="← Retour"
    />

    <!-- Formulaire sophistiqué -->
    <div class="bg-slate-800/40 backdrop-blur-xl/40 backdrop-blur-xl rounded-xl border border-slate-700/30/30/20p-6 mt-6">
        <form action="{{ route('admin.seminaires.store') }}" method="POST" class="space-y-8">
            @csrf

            <!-- Section 1: Informations Générales -->
            <div class="bg-slate-700 rounded-xl p-6 border border-slate-600">
                <h3 class="text-lg font-semibold text-slate-100 tracking-tight flex items-center gap-3">
                    🎯 Informations Générales
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="titre" class="block text-sm font-medium text-slate-300 mb-2">
                            Titre du séminaire <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="titre" name="titre" value="{{ old('titre') }}" required
                               class="w-full bg-slate-600 border border-slate-500 text-white rounded-xl px-3 py-2 focus:ring-2 focus:ring-pink-500"
                               placeholder="Ex: Séminaire Kata Avancé, Formation Arbitrage...">
                        @error('titre')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-slate-300 mb-2">
                            Type <span class="text-red-400">*</span>
                        </label>
                        <select id="type" name="type" required
                                class="w-full bg-slate-600 border border-slate-500 text-white rounded-xl px-3 py-2 focus:ring-2 focus:ring-pink-500">
                            <option value="">Sélectionner un type</option>
                            <option value="technique" {{ old('type') == 'technique' ? 'selected' : '' }}>🥋 Technique</option>
                            <option value="kata" {{ old('type') == 'kata' ? 'selected' : '' }}>🎭 Kata</option>
                            <option value="competition" {{ old('type') == 'competition' ? 'selected' : '' }}>🏆 Compétition</option>
                            <option value="arbitrage" {{ old('type') == 'arbitrage' ? 'selected' : '' }}>⚖️ Arbitrage</option>
                            <option value="grade" {{ old('type') == 'grade' ? 'selected' : '' }}>🎓 Passage de Grade</option>
                            <option value="formation" {{ old('type') == 'formation' ? 'selected' : '' }}>📚 Formation</option>
                        </select>
                        @error('type')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-slate-300 mb-2">
                        Description détaillée
                    </label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full bg-slate-600 border border-slate-500 text-white rounded-xl px-3 py-2 focus:ring-2 focus:ring-pink-500"
                              placeholder="Décrivez le contenu, les objectifs et les prérequis du séminaire...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Section 2: École (Multi-tenant) -->
            @if(auth()->user()->hasRole('superadmin'))
            <div class="bg-slate-700 rounded-xl p-6 border border-slate-600">
                <h3 class="text-lg font-semibold text-slate-100 tracking-tight flex items-center gap-3">
                    🏫 École Organisatrice
                </h3>
                <div>
                    <label for="ecole_id" class="block text-sm font-medium text-slate-300 mb-2">
                        École organisatrice <span class="text-red-400">*</span>
                    </label>
                    <select id="ecole_id" name="ecole_id" required
                            class="w-full bg-slate-600 border border-slate-500 text-white rounded-xl px-3 py-2 focus:ring-2 focus:ring-pink-500">
                        <option value="">Sélectionner une école</option>
                        @foreach($ecoles as $ecole)
                            <option value="{{ $ecole->id }}" {{ old('ecole_id') == $ecole->id ? 'selected' : '' }}>
                                {{ $ecole->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('ecole_id')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            @endif

            <!-- Section 3: Planification -->
            <div class="bg-slate-700 rounded-xl p-6 border border-slate-600">
                <h3 class="text-lg font-semibold text-slate-100 tracking-tight flex items-center gap-3">
                    📅 Planification
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date_debut" class="block text-sm font-medium text-slate-300 mb-2">
                            Date et heure de début <span class="text-red-400">*</span>
                        </label>
                        <input type="datetime-local" id="date_debut" name="date_debut" value="{{ old('date_debut') }}" required
                               class="w-full bg-slate-600 border border-slate-500 text-white rounded-xl px-3 py-2 focus:ring-2 focus:ring-pink-500">
                        @error('date_debut')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date_fin" class="block text-sm font-medium text-slate-300 mb-2">
                            Date et heure de fin
                        </label>
                        <input type="datetime-local" id="date_fin" name="date_fin" value="{{ old('date_fin') }}"
                               class="w-full bg-slate-600 border border-slate-500 text-white rounded-xl px-3 py-2 focus:ring-2 focus:ring-pink-500">
                        @error('date_fin')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="lieu" class="block text-sm font-medium text-slate-300 mb-2">
                            Lieu <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="lieu" name="lieu" value="{{ old('lieu') }}" required
                               class="w-full bg-slate-600 border border-slate-500 text-white rounded-xl px-3 py-2 focus:ring-2 focus:ring-pink-500"
                               placeholder="Adresse complète ou nom du lieu">
                        @error('lieu')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="instructeur" class="block text-sm font-medium text-slate-300 mb-2">
                            Instructeur principal <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="instructeur" name="instructeur" value="{{ old('instructeur') }}" required
                               class="w-full bg-slate-600 border border-slate-500 text-white rounded-xl px-3 py-2 focus:ring-2 focus:ring-pink-500"
                               placeholder="Nom de l'instructeur principal">
                        @error('instructeur')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 4: Paramètres -->
            <div class="bg-slate-700 rounded-xl p-6 border border-slate-600">
                <h3 class="text-lg font-semibold text-slate-100 tracking-tight flex items-center gap-3">
                    ⚙️ Paramètres du Séminaire
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="niveau_requis" class="block text-sm font-medium text-slate-300 mb-2">
                            Niveau requis <span class="text-red-400">*</span>
                        </label>
                        <select id="niveau_requis" name="niveau_requis" required
                                class="w-full bg-slate-600 border border-slate-500 text-white rounded-xl px-3 py-2 focus:ring-2 focus:ring-pink-500">
                            <option value="tous_niveaux" {{ old('niveau_requis', 'tous_niveaux') == 'tous_niveaux' ? 'selected' : '' }}>🥋 Tous niveaux</option>
                            <option value="debutant" {{ old('niveau_requis') == 'debutant' ? 'selected' : '' }}>🟢 Débutant</option>
                            <option value="intermediaire" {{ old('niveau_requis') == 'intermediaire' ? 'selected' : '' }}>🟡 Intermédiaire</option>
                            <option value="avance" {{ old('niveau_requis') == 'avance' ? 'selected' : '' }}>🔴 Avancé</option>
                        </select>
                        @error('niveau_requis')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="max_participants" class="block text-sm font-medium text-slate-300 mb-2">
                            Max participants
                        </label>
                        <input type="number" id="max_participants" name="max_participants" value="{{ old('max_participants') }}" min="1"
                               class="w-full bg-slate-600 border border-slate-500 text-white rounded-xl px-3 py-2 focus:ring-2 focus:ring-pink-500">
                        @error('max_participants')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cout" class="block text-sm font-medium text-slate-300 mb-2">
                            Coût ($)
                        </label>
                        <input type="number" id="cout" name="cout" value="{{ old('cout') }}" min="0" step="0.01"
                               class="w-full bg-slate-600 border border-slate-500 text-white rounded-xl px-3 py-2 focus:ring-2 focus:ring-pink-500">
                        @error('cout')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="statut" class="block text-sm font-medium text-slate-300 mb-2">
                            Statut <span class="text-red-400">*</span>
                        </label>
                        <select id="statut" name="statut" required
                                class="w-full bg-slate-600 border border-slate-500 text-white rounded-xl px-3 py-2 focus:ring-2 focus:ring-pink-500">
                            <option value="planifie" {{ old('statut', 'planifie') == 'planifie' ? 'selected' : '' }}>Planifié</option>
                            <option value="ouvert" {{ old('statut') == 'ouvert' ? 'selected' : '' }}>Ouvert aux inscriptions</option>
                        </select>
                        @error('statut')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center space-x-6 pt-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="inscription_ouverte" value="1" {{ old('inscription_ouverte') ? 'checked' : '' }}
                                   class="w-4 h-4 text-pink-600 bg-slate-700 border-slate-600 rounded focus:ring-pink-500">
                            <span class="ml-2 text-sm text-slate-300">Inscriptions ouvertes</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="certificat" value="1" {{ old('certificat') ? 'checked' : '' }}
                                   class="w-4 h-4 text-pink-600 bg-slate-700 border-slate-600 rounded focus:ring-pink-500">
                            <span class="ml-2 text-sm text-slate-300">Avec certificat</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-slate-700/30/30/20>
                <a href="{{ route('admin.seminaires.index') }}" 
                   class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-2 rounded-xl transition-colors">
                    Annuler
                </a>
                <button type="submit" 
                        class="bg-pink-600 hover:bg-pink-700 text-white px-6 py-2 rounded-xl transition-colors">
                    🎯 Créer le séminaire
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
