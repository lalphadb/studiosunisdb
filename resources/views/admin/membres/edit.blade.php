@extends('layouts.admin')

@section('title', 'Modifier Membre')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h1 class="text-3xl font-bold text-white">
                    ✏️ Modifier Membre
                </h1>
                <p class="mt-1 text-sm text-gray-300">
                    Modification des informations de {{ $membre->prenom }} {{ $membre->nom }}
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                <a href="{{ route('admin.membres.show', $membre) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                    👁️ Voir Profil
                </a>
                <a href="{{ route('admin.membres.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                    ← Retour Liste
                </a>
            </div>
        </div>

        <!-- Messages -->
        @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
        @endif

        <!-- Formulaire -->
        <div class="bg-gray-800 rounded-lg shadow-xl border border-gray-700">
            <form action="{{ route('admin.membres.update', $membre) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="px-6 py-4 border-b border-gray-700">
                    <h3 class="text-lg font-medium text-white">Informations du Membre</h3>
                    <p class="text-sm text-gray-400">Modifiez les informations ci-dessous</p>
                </div>

                <div class="p-6 space-y-6">
                    
                    <!-- École d'inscription -->
                    <div>
                        <label for="ecole_id" class="block text-sm font-medium text-gray-300 mb-2">
                            École d'inscription *
                        </label>
                        <select name="ecole_id" id="ecole_id" required
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('ecole_id') border-red-500 @enderror">
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
                            <label for="prenom" class="block text-sm font-medium text-gray-300 mb-2">
                                Prénom *
                            </label>
                            <input type="text" name="prenom" id="prenom" value="{{ old('prenom', $membre->prenom) }}" required
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('prenom') border-red-500 @enderror">
                            @error('prenom')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-300 mb-2">
                                Nom de famille *
                            </label>
                            <input type="text" name="nom" id="nom" value="{{ old('nom', $membre->nom) }}" required
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nom') border-red-500 @enderror">
                            @error('nom')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Date de naissance et Statut -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="date_naissance" class="block text-sm font-medium text-gray-300 mb-2">
                                Date de naissance
                            </label>
                            <input type="date" name="date_naissance" id="date_naissance" 
                                   value="{{ old('date_naissance', $membre->date_naissance ? $membre->date_naissance->format('Y-m-d') : '') }}"
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_naissance') border-red-500 @enderror">
                            @error('date_naissance')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="statut" class="block text-sm font-medium text-gray-300 mb-2">
                                Statut *
                            </label>
                            <select name="statut" id="statut" required
                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('statut') border-red-500 @enderror">
                                <option value="actif" {{ old('statut', $membre->statut) == 'actif' ? 'selected' : '' }}>✅ Actif</option>
                                <option value="inactif" {{ old('statut', $membre->statut) == 'inactif' ? 'selected' : '' }}>❌ Inactif</option>
                                <option value="suspendu" {{ old('statut', $membre->statut) == 'suspendu' ? 'selected' : '' }}>⏸️ Suspendu</option>
                            </select>
                            @error('statut')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Contact -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="telephone" class="block text-sm font-medium text-gray-300 mb-2">
                                Téléphone
                            </label>
                            <input type="text" name="telephone" id="telephone" value="{{ old('telephone', $membre->telephone) }}"
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('telephone') border-red-500 @enderror">
                            @error('telephone')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                                Email
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email', $membre->email) }}"
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Adresse -->
                    <div>
                        <label for="adresse" class="block text-sm font-medium text-gray-300 mb-2">
                            Adresse complète
                        </label>
                        <textarea name="adresse" id="adresse" rows="3"
                                  class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('adresse') border-red-500 @enderror">{{ old('adresse', $membre->adresse) }}</textarea>
                        @error('adresse')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact d'urgence -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="contact_urgence" class="block text-sm font-medium text-gray-300 mb-2">
                                Contact d'urgence
                            </label>
                            <input type="text" name="contact_urgence" id="contact_urgence" value="{{ old('contact_urgence', $membre->contact_urgence) }}"
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('contact_urgence') border-red-500 @enderror">
                            @error('contact_urgence')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="telephone_urgence" class="block text-sm font-medium text-gray-300 mb-2">
                                Téléphone urgence
                            </label>
                            <input type="text" name="telephone_urgence" id="telephone_urgence" value="{{ old('telephone_urgence', $membre->telephone_urgence) }}"
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('telephone_urgence') border-red-500 @enderror">
                            @error('telephone_urgence')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Date d'inscription -->
                    <div>
                        <label for="date_inscription" class="block text-sm font-medium text-gray-300 mb-2">
                            Date d'inscription *
                        </label>
                        <input type="date" name="date_inscription" id="date_inscription" 
                               value="{{ old('date_inscription', $membre->date_inscription ? $membre->date_inscription->format('Y-m-d') : '') }}" required
                               class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_inscription') border-red-500 @enderror">
                        @error('date_inscription')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-300 mb-2">
                            Notes internes
                        </label>
                        <textarea name="notes" id="notes" rows="4"
                                  class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-500 @enderror">{{ old('notes', $membre->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="px-6 py-4 bg-gray-750 border-t border-gray-700 flex justify-end space-x-3 rounded-b-lg">
                    <a href="{{ route('admin.membres.show', $membre) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                        Annuler
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                        💾 Enregistrer Modifications
                    </button>
                </div>
            </form>
        </div>

        <!-- Informations actuelles -->
        <div class="mt-8 bg-gray-800 rounded-lg shadow border border-gray-700 p-6">
            <h3 class="text-lg font-medium text-white mb-4">📊 Informations Actuelles</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="text-gray-400">Membre depuis:</span>
                    <span class="text-white ml-2">{{ $membre->date_inscription ? $membre->date_inscription->format('d/m/Y') : 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-gray-400">Dernière modification:</span>
                    <span class="text-white ml-2">{{ $membre->updated_at ? $membre->updated_at->format('d/m/Y à H:i') : 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-gray-400">Âge:</span>
                    <span class="text-white ml-2">{{ $membre->date_naissance ? $membre->date_naissance->age . ' ans' : 'Non renseigné' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
