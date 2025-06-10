@extends('layouts.admin')

@section('title', 'Nouveau Membre')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h1 class="text-3xl font-bold text-white">
                    👤 Nouveau Membre
                </h1>
                <p class="mt-1 text-sm text-gray-300">
                    Ajouter un nouveau membre au réseau Studios Unis
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('admin.membres.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                    ← Retour Liste
                </a>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="bg-gray-800 rounded-lg shadow-xl border border-gray-700">
            <form action="{{ route('admin.membres.store') }}" method="POST">
                @csrf
                
                <div class="px-6 py-4 border-b border-gray-700">
                    <h3 class="text-lg font-medium text-white">Informations du Membre</h3>
                    <p class="text-sm text-gray-400">Remplissez les informations du nouveau membre</p>
                </div>

                <div class="p-6 space-y-6">
                    
                    <!-- École d'inscription -->
                    <div>
                        <label for="ecole_id" class="block text-sm font-medium text-gray-300 mb-2">
                            École d'inscription *
                            @if(isset($ecole_defaut))
                                <span class="text-sm text-gray-400">(Automatiquement sélectionnée)</span>
                            @endif
                        </label>
                        
                        @if(isset($ecole_defaut) && !auth()->user()->hasRole('superadmin'))
                            {{-- Admin d'école : affichage en lecture seule + input hidden --}}
                            <div class="w-full px-3 py-2 bg-gray-600 border border-gray-500 rounded-md text-gray-300">
                                {{ $ecoles->first()->nom }} - {{ $ecoles->first()->ville }}
                            </div>
                            <input type="hidden" name="ecole_id" value="{{ $ecole_defaut }}">
                            <p class="mt-1 text-sm text-gray-400">
                                💡 Votre école est automatiquement sélectionnée.
                            </p>
                        @else
                            {{-- SuperAdmin : dropdown normal --}}
                            <select name="ecole_id" id="ecole_id" required
                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('ecole_id') border-red-500 @enderror">
                                <option value="">Sélectionner une école</option>
                                @foreach($ecoles as $ecole)
                                    <option value="{{ $ecole->id }}" {{ old('ecole_id', $ecole_defaut) == $ecole->id ? 'selected' : '' }}>
                                        {{ $ecole->nom }} - {{ $ecole->ville }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                        
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
                            <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}" required
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('prenom') border-red-500 @enderror">
                            @error('prenom')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-300 mb-2">
                                Nom de famille *
                            </label>
                            <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
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
                            <input type="date" name="date_naissance" id="date_naissance" value="{{ old('date_naissance') }}"
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
                                <option value="actif" {{ old('statut') == 'actif' ? 'selected' : '' }}>✅ Actif</option>
                                <option value="inactif" {{ old('statut') == 'inactif' ? 'selected' : '' }}>❌ Inactif</option>
                                <option value="suspendu" {{ old('statut') == 'suspendu' ? 'selected' : '' }}>⏸️ Suspendu</option>
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
                            <input type="text" name="telephone" id="telephone" value="{{ old('telephone') }}"
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('telephone') border-red-500 @enderror"
                                   placeholder="514-123-4567">
                            @error('telephone')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                                Email
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
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
                                  class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('adresse') border-red-500 @enderror"
                                  placeholder="Adresse complète du membre">{{ old('adresse') }}</textarea>
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
                            <input type="text" name="contact_urgence" id="contact_urgence" value="{{ old('contact_urgence') }}"
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('contact_urgence') border-red-500 @enderror"
                                   placeholder="Nom du contact">
                            @error('contact_urgence')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="telephone_urgence" class="block text-sm font-medium text-gray-300 mb-2">
                                Téléphone urgence
                            </label>
                            <input type="text" name="telephone_urgence" id="telephone_urgence" value="{{ old('telephone_urgence') }}"
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('telephone_urgence') border-red-500 @enderror"
                                   placeholder="514-123-4567">
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
                               value="{{ old('date_inscription', date('Y-m-d')) }}" required
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
                                  class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-500 @enderror"
                                  placeholder="Notes, observations, remarques particulières...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="px-6 py-4 bg-gray-750 border-t border-gray-700 flex justify-end space-x-3 rounded-b-lg">
                    <a href="{{ route('admin.membres.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                        Annuler
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                        👤 Créer Membre
                    </button>
                </div>
            </form>
        </div>

        <!-- Aide -->
        <div class="mt-6 bg-blue-900 bg-opacity-50 border border-blue-700 rounded-lg p-4">
            <div class="flex">
                <div class="text-blue-400 text-xl mr-3">💡</div>
                <div>
                    <h4 class="text-sm font-medium text-blue-300">Conseils</h4>
                    <p class="text-sm text-blue-200 mt-1">
                        • Assurez-vous que les informations de contact sont correctes<br>
                        • Le contact d'urgence est important pour les mineurs<br>
                        • La date d'inscription détermine l'ancienneté du membre
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
