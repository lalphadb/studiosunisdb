@extends('layouts.admin')

@section('title', 'Nouveau Séminaire')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h1 class="text-3xl font-bold text-white">
                    🥋 Nouveau Séminaire
                </h1>
                <p class="mt-1 text-sm text-gray-300">
                    Créer un nouveau séminaire avec un grand maître
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('admin.seminaires.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-300 bg-slate-700 hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                    </svg>
                    Retour
                </a>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="bg-slate-800 border border-slate-700 rounded-lg overflow-hidden">
            <form method="POST" action="{{ route('admin.seminaires.store') }}" class="p-6">
                @csrf

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Section: Informations de base -->
                    <div class="sm:col-span-2">
                        <h3 class="text-lg font-medium text-white mb-4 border-b border-slate-600 pb-2">
                            📋 Informations de base
                        </h3>
                    </div>

                    <!-- Nom du séminaire -->
                    <div class="sm:col-span-2">
                        <label for="nom" class="block text-sm font-medium text-gray-300">
                            Nom du séminaire <span class="text-red-400">*</span>
                        </label>
                        <input type="text" 
                               name="nom" 
                               id="nom" 
                               value="{{ old('nom') }}"
                               required
                               class="mt-1 block w-full bg-slate-700 border border-slate-600 rounded-md text-white text-sm focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ex: Stage Technique Avancée">
                        @error('nom')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Intervenant -->
                    <div>
                        <label for="intervenant" class="block text-sm font-medium text-gray-300">
                            Grand Maître / Intervenant <span class="text-red-400">*</span>
                        </label>
                        <input type="text" 
                               name="intervenant" 
                               id="intervenant" 
                               value="{{ old('intervenant') }}"
                               required
                               class="mt-1 block w-full bg-slate-700 border border-slate-600 rounded-md text-white text-sm focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ex: Sensei Takeshi Yamamoto">
                        @error('intervenant')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type de séminaire -->
                    <div>
                        <label for="type_seminaire" class="block text-sm font-medium text-gray-300">
                            Type de séminaire <span class="text-red-400">*</span>
                        </label>
                        <select name="type_seminaire" 
                                id="type_seminaire" 
                                required
                                class="mt-1 block w-full bg-slate-700 border border-slate-600 rounded-md text-white text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Sélectionner un type</option>
                            <option value="technique" {{ old('type_seminaire') === 'technique' ? 'selected' : '' }}>Technique</option>
                            <option value="kata" {{ old('type_seminaire') === 'kata' ? 'selected' : '' }}>Kata</option>
                            <option value="competition" {{ old('type_seminaire') === 'competition' ? 'selected' : '' }}>Compétition</option>
                            <option value="arbitrage" {{ old('type_seminaire') === 'arbitrage' ? 'selected' : '' }}>Arbitrage</option>
                            <option value="self_defense" {{ old('type_seminaire') === 'self_defense' ? 'selected' : '' }}>Self-Défense</option>
                            <option value="armes" {{ old('type_seminaire') === 'armes' ? 'selected' : '' }}>Armes traditionnelles</option>
                            <option value="meditation" {{ old('type_seminaire') === 'meditation' ? 'selected' : '' }}>Méditation</option>
                            <option value="histoire" {{ old('type_seminaire') === 'histoire' ? 'selected' : '' }}>Histoire du karaté</option>
                            <option value="autre" {{ old('type_seminaire') === 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('type_seminaire')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Niveau cible -->
                    <div>
                        <label for="niveau_cible" class="block text-sm font-medium text-gray-300">
                            Niveau cible
                        </label>
                        <input type="text" 
                               name="niveau_cible" 
                               id="niveau_cible" 
                               value="{{ old('niveau_cible') }}"
                               class="mt-1 block w-full bg-slate-700 border border-slate-600 rounded-md text-white text-sm focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ex: Ceinture bleue et plus">
                        @error('niveau_cible')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prix -->
                    <div>
                        <label for="prix" class="block text-sm font-medium text-gray-300">
                            Prix ($) <span class="text-red-400">*</span>
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="number" 
                                   name="prix" 
                                   id="prix" 
                                   step="0.01"
                                   min="0"
                                   value="{{ old('prix') }}"
                                   required
                                   class="block w-full bg-slate-700 border border-slate-600 rounded-md text-white text-sm focus:ring-blue-500 focus:border-blue-500 pr-12"
                                   placeholder="75.00">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-400 sm:text-sm">$</span>
                            </div>
                        </div>
                        @error('prix')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Section: Dates et lieu -->
                    <div class="sm:col-span-2 mt-6">
                        <h3 class="text-lg font-medium text-white mb-4 border-b border-slate-600 pb-2">
                            📅 Dates et lieu
                        </h3>
                    </div>

                    <!-- Date début -->
                    <div>
                        <label for="date_debut" class="block text-sm font-medium text-gray-300">
                            Date et heure de début <span class="text-red-400">*</span>
                        </label>
                        <input type="datetime-local" 
                               name="date_debut" 
                               id="date_debut" 
                               value="{{ old('date_debut') }}"
                               required
                               class="mt-1 block w-full bg-slate-700 border border-slate-600 rounded-md text-white text-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('date_debut')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date fin -->
                    <div>
                        <label for="date_fin" class="block text-sm font-medium text-gray-300">
                            Date et heure de fin <span class="text-red-400">*</span>
                        </label>
                        <input type="datetime-local" 
                               name="date_fin" 
                               id="date_fin" 
                               value="{{ old('date_fin') }}"
                               required
                               class="mt-1 block w-full bg-slate-700 border border-slate-600 rounded-md text-white text-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('date_fin')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Lieu -->
                    <div>
                        <label for="lieu" class="block text-sm font-medium text-gray-300">
                            Lieu <span class="text-red-400">*</span>
                        </label>
                        <input type="text" 
                               name="lieu" 
                               id="lieu" 
                               value="{{ old('lieu') }}"
                               required
                               class="mt-1 block w-full bg-slate-700 border border-slate-600 rounded-md text-white text-sm focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ex: Studio Unis Centre-Ville">
                        @error('lieu')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Capacité -->
                    <div>
                        <label for="capacite_max" class="block text-sm font-medium text-gray-300">
                            Capacité maximale <span class="text-red-400">*</span>
                        </label>
                        <input type="number" 
                               name="capacite_max" 
                               id="capacite_max" 
                               min="1"
                               max="200"
                               value="{{ old('capacite_max', 50) }}"
                               required
                               class="mt-1 block w-full bg-slate-700 border border-slate-600 rounded-md text-white text-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('capacite_max')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="sm:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-300">
                            Description
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4"
                                  class="mt-1 block w-full bg-slate-700 border border-slate-600 rounded-md text-white text-sm focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Décrivez le contenu du séminaire...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prérequis -->
                    <div class="sm:col-span-2">
                        <label for="pre_requis" class="block text-sm font-medium text-gray-300">
                            Prérequis
                        </label>
                        <textarea name="pre_requis" 
                                  id="pre_requis" 
                                  rows="3"
                                  class="mt-1 block w-full bg-slate-700 border border-slate-600 rounded-md text-white text-sm focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Conditions requises pour participer...">{{ old('pre_requis') }}</textarea>
                        @error('pre_requis')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Matériel requis -->
                    <div class="sm:col-span-2">
                        <label for="materiel_requis" class="block text-sm font-medium text-gray-300">
                            Matériel requis
                        </label>
                        <textarea name="materiel_requis" 
                                  id="materiel_requis" 
                                  rows="3"
                                  class="mt-1 block w-full bg-slate-700 border border-slate-600 rounded-md text-white text-sm focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Équipement à apporter...">{{ old('materiel_requis') }}</textarea>
                        @error('materiel_requis')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Options -->
                    <div class="sm:col-span-2 mt-6">
                        <h3 class="text-lg font-medium text-white mb-4 border-b border-slate-600 pb-2">
                            ⚙️ Options
                        </h3>
                    </div>

                    <!-- Ouvert à toutes les écoles -->
                    <div class="sm:col-span-2">
                        <div class="flex items-center">
                            <input id="ouvert_toutes_ecoles" 
                                   name="ouvert_toutes_ecoles" 
                                   type="checkbox" 
                                   {{ old('ouvert_toutes_ecoles', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 bg-slate-700 border-slate-600 rounded focus:ring-blue-500">
                            <label for="ouvert_toutes_ecoles" class="ml-2 text-sm font-medium text-gray-300">
                                Ouvert à toutes les écoles Studios Unis
                            </label>
                        </div>
                        <p class="mt-1 text-sm text-gray-400">Si décoché, seuls les membres de certaines écoles pourront s'inscrire</p>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex items-center justify-end mt-8 pt-6 border-t border-slate-600 space-x-4">
                    <a href="{{ route('admin.seminaires.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-300 bg-slate-700 hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Créer le séminaire
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
