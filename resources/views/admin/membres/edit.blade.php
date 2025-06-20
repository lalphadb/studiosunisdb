@extends('layouts.admin')

@section('title', 'Modifier Membre')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête avec style Studios Unis -->
        <div class="relative bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 rounded-lg p-6 text-white overflow-hidden mb-8">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-16 -mt-16"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">✏️ Modifier Membre</h1>
                    <p class="text-blue-100">Modification des informations de {{ $membre->prenom }} {{ $membre->nom }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.membres.show', $membre) }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-4 py-2 rounded-lg font-medium transition-all">
                        👁️ Voir Profil
                    </a>
                    <a href="{{ route('admin.membres.index') }}" class="bg-white text-purple-600 hover:bg-gray-50 px-4 py-2 rounded-lg font-medium transition-all">
                        ← Retour Liste
                    </a>
                </div>
            </div>
        </div>

        <!-- Messages -->
        @if(session('success'))
        <div class="mb-6 bg-green-500 bg-opacity-15 border border-green-500 text-green-300 px-4 py-3 rounded-lg">
            ✅ {{ session('success') }}
        </div>
        @endif

        <!-- Formulaire avec style Studios Unis -->
        <div class="bg-gray-800 rounded-lg shadow-2xl border border-gray-700">
            <form action="{{ route('admin.membres.update', $membre) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="bg-gradient-to-r from-blue-500 to-cyan-500 p-4 rounded-t-lg">
                    <h3 class="text-lg font-bold text-white">📝 Informations du Membre</h3>
                    <p class="text-blue-100 text-sm">Modifiez les informations ci-dessous</p>
                </div>

                <div class="p-6 space-y-6">
                    
                    <!-- École d'inscription -->
                    <div>
                        <label for="ecole_id" class="block text-sm font-bold text-blue-400 mb-2">
                            🏫 École d'inscription *
                        </label>
                        <select name="ecole_id" id="ecole_id" required
                                class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('ecole_id') border-red-500 @enderror">
                            @foreach($ecoles as $ecole)
                                <option value="{{ $ecole->id }}" {{ old('ecole_id', $membre->ecole_id) == $ecole->id ? 'selected' : '' }}>
                                    {{ $ecole->nom }} - {{ $ecole->ville }}
                                </option>
                            @endforeach
                        </select>
                        @error('ecole_id')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nom et Prénom -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="prenom" class="block text-sm font-bold text-green-400 mb-2">
                                👤 Prénom *
                            </label>
                            <input type="text" name="prenom" id="prenom" value="{{ old('prenom', $membre->prenom) }}" required
                                   class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('prenom') border-red-500 @enderror">
                            @error('prenom')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="nom" class="block text-sm font-bold text-green-400 mb-2">
                                👤 Nom de famille *
                            </label>
                            <input type="text" name="nom" id="nom" value="{{ old('nom', $membre->nom) }}" required
                                   class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('nom') border-red-500 @enderror">
                            @error('nom')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Date de naissance et Sexe -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="date_naissance" class="block text-sm font-bold text-purple-400 mb-2">
                                🎂 Date de naissance
                            </label>
                            <input type="date" name="date_naissance" id="date_naissance" 
                                   value="{{ old('date_naissance', $membre->date_naissance ? $membre->date_naissance->format('Y-m-d') : '') }}"
                                   class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('date_naissance') border-red-500 @enderror">
                            @error('date_naissance')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="sexe" class="block text-sm font-bold text-pink-400 mb-2">
                                ⚧️ Sexe
                            </label>
                            <select name="sexe" id="sexe"
                                    class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('sexe') border-red-500 @enderror">
                                <option value="">Sélectionner</option>
                                <option value="M" {{ old('sexe', $membre->sexe) == 'M' ? 'selected' : '' }}>👨 Masculin</option>
                                <option value="F" {{ old('sexe', $membre->sexe) == 'F' ? 'selected' : '' }}>👩 Féminin</option>
                                <option value="Autre" {{ old('sexe', $membre->sexe) == 'Autre' ? 'selected' : '' }}>🏳️‍⚧️ Autre</option>
                            </select>
                            @error('sexe')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Contact -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="telephone" class="block text-sm font-bold text-yellow-400 mb-2">
                                📞 Téléphone
                            </label>
                            <input type="text" name="telephone" id="telephone" value="{{ old('telephone', $membre->telephone) }}"
                                   class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('telephone') border-red-500 @enderror">
                            @error('telephone')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-bold text-cyan-400 mb-2">
                                📧 Email
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email', $membre->email) }}"
                                   class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Adresse complète -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <label for="adresse" class="block text-sm font-bold text-indigo-400 mb-2">
                                🏠 Adresse complète
                            </label>
                            <textarea name="adresse" id="adresse" rows="3"
                                      class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('adresse') border-red-500 @enderror">{{ old('adresse', $membre->adresse) }}</textarea>
                            @error('adresse')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label for="ville" class="block text-sm font-bold text-indigo-400 mb-2">🏙️ Ville</label>
                                <input type="text" name="ville" id="ville" value="{{ old('ville', $membre->ville) }}"
                                       class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('ville') border-red-500 @enderror">
                            </div>
                            <div>
                                <label for="code_postal" class="block text-sm font-bold text-indigo-400 mb-2">📮 Code postal</label>
                                <input type="text" name="code_postal" id="code_postal" value="{{ old('code_postal', $membre->code_postal) }}"
                                       class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('code_postal') border-red-500 @enderror">
                            </div>
                        </div>
                    </div>

                    <!-- Contact d'urgence - SECTION CRITIQUE -->
                    <div class="bg-red-500 bg-opacity-15 border-2 border-red-500 border-opacity-40 rounded-lg p-6">
                        <h4 class="text-lg font-bold text-red-300 mb-4 flex items-center">
                            🚨 Contact d'urgence
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="contact_urgence_nom" class="block text-sm font-bold text-red-400 mb-2">
                                    👤 Nom du contact d'urgence
                                </label>
                                <input type="text" name="contact_urgence_nom" id="contact_urgence_nom" 
                                       value="{{ old('contact_urgence_nom', $membre->contact_urgence_nom) }}"
                                       class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-red-500 focus:border-transparent @error('contact_urgence_nom') border-red-500 @enderror"
                                       placeholder="Nom de la personne à contacter">
                                @error('contact_urgence_nom')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="contact_urgence_telephone" class="block text-sm font-bold text-red-400 mb-2">
                                    📞 Téléphone d'urgence
                                </label>
                                <input type="text" name="contact_urgence_telephone" id="contact_urgence_telephone" 
                                       value="{{ old('contact_urgence_telephone', $membre->contact_urgence_telephone) }}"
                                       class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-red-500 focus:border-transparent @error('contact_urgence_telephone') border-red-500 @enderror"
                                       placeholder="514-123-4567">
                                @error('contact_urgence_telephone')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Date d'inscription et Statut -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="date_inscription" class="block text-sm font-bold text-orange-400 mb-2">
                                📅 Date d'inscription *
                            </label>
                            <input type="date" name="date_inscription" id="date_inscription" 
                                   value="{{ old('date_inscription', $membre->date_inscription ? $membre->date_inscription->format('Y-m-d') : '') }}" required
                                   class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('date_inscription') border-red-500 @enderror">
                            @error('date_inscription')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="active" class="block text-sm font-bold text-emerald-400 mb-2">
                                ✅ Statut du membre *
                            </label>
                            <select name="active" id="active" required
                                    class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('active') border-red-500 @enderror">
                                <option value="1" {{ old('active', $membre->active ? '1' : '0') == '1' ? 'selected' : '' }}>✅ Actif</option>
                                <option value="0" {{ old('active', $membre->active ? '1' : '0') == '0' ? 'selected' : '' }}>❌ Inactif</option>
                            </select>
                            @error('active')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-bold text-gray-400 mb-2">
                            📝 Notes internes
                        </label>
                        <textarea name="notes" id="notes" rows="4"
                                  class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-gray-500 focus:border-transparent @error('notes') border-red-500 @enderror"
                                  placeholder="Notes, observations, remarques particulières...">{{ old('notes', $membre->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions avec style Studios Unis -->
                <div class="px-6 py-4 bg-gray-750 border-t border-gray-700 flex justify-end space-x-3 rounded-b-lg">
                    <a href="{{ route('admin.membres.show', $membre) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition-all">
                        Annuler
                    </a>
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-3 rounded-lg font-medium transition-all">
                        💾 Enregistrer Modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
