@extends('layouts.admin')

@section('title', 'Créer une Ceinture')

@section('content')
<div class="space-y-6">
    <!-- Header Création -->
    <div class="gradient-ceintures text-white p-8 rounded-2xl border border-orange-500/20 relative overflow-hidden backdrop-blur-sm studiosdb-fade-in">
        <div class="absolute inset-0 bg-gradient-to-br from-white/3 via-transparent to-white/2"></div>
        
        <div class="relative z-10 flex justify-between items-center">
            <div class="flex items-center space-x-6">
                <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur border border-white/20">
                    <x-admin-icon name="ceintures" size="w-8 h-8" color="text-white" />
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white drop-shadow-sm">Créer une Ceinture</h1>
                    <p class="text-lg text-white/90 font-medium">Création d'un nouveau niveau</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.ceintures.index') }}" 
                   class="bg-white/10 hover:bg-white/20 text-white px-5 py-3 rounded-xl transition-all duration-300 font-medium backdrop-blur border border-white/20 flex items-center space-x-2">
                    <x-admin-icon name="chevron-left" size="w-4 h-4" color="text-white" />
                    <span>Retour à la liste</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Formulaire moderne -->
    <div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden">
        <form method="POST" action="{{ route('admin.ceintures.store') }}" class="space-y-8">
            @csrf
            
            <!-- Informations de base -->
            <div>
                <h2 class="text-xl font-bold text-white mb-6 flex items-center">
                    <x-admin-icon name="ceintures" size="w-6 h-6" color="text-orange-400" />
                    <span class="ml-3">Informations de base</span>
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom -->
                    <div>
                        <label for="nom" class="block text-sm font-medium text-slate-300 mb-2">
                            Nom de la ceinture *
                        </label>
                        <input type="text" 
                               name="nom" 
                               id="nom"
                               value="{{ old('nom') }}"
                               class="studiosdb-search w-full" 
                               placeholder="Ex: Ceinture Blanche"
                               required>
                        @error('nom')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Couleur -->
                    <div>
                        <label for="couleur" class="block text-sm font-medium text-slate-300 mb-2">
                            Couleur *
                        </label>
                        <div class="flex space-x-3">
                            <input type="color" 
                                   name="couleur" 
                                   id="couleur"
                                   value="{{ old('couleur', '#FFFFFF') }}"
                                   class="w-16 h-12 rounded-lg border-2 border-slate-600/50 cursor-pointer">
                            <input type="text" 
                                   name="couleur_hex" 
                                   id="couleur_hex"
                                   value="{{ old('couleur', '#FFFFFF') }}"
                                   class="studiosdb-search flex-1" 
                                   placeholder="#FFFFFF"
                                   pattern="^#[0-9A-Fa-f]{6}$">
                        </div>
                        @error('couleur')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Niveau -->
                    <div>
                        <label for="niveau" class="block text-sm font-medium text-slate-300 mb-2">
                            Niveau
                        </label>
                        <select name="niveau" id="niveau" class="studiosdb-select w-full">
                            <option value="debutant" {{ old('niveau') == 'debutant' ? 'selected' : '' }}>Débutant</option>
                            <option value="intermediaire" {{ old('niveau') == 'intermediaire' ? 'selected' : '' }}>Intermédiaire</option>
                            <option value="avance" {{ old('niveau') == 'avance' ? 'selected' : '' }}>Avancé</option>
                            <option value="expert" {{ old('niveau') == 'expert' ? 'selected' : '' }}>Expert</option>
                            <option value="maitre" {{ old('niveau') == 'maitre' ? 'selected' : '' }}>Maître</option>
                        </select>
                        @error('niveau')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ordre -->
                    <div>
                        <label for="ordre" class="block text-sm font-medium text-slate-300 mb-2">
                            Ordre de progression *
                        </label>
                        <input type="number" 
                               name="ordre" 
                               id="ordre"
                               value="{{ old('ordre', 1) }}"
                               min="1"
                               max="100"
                               class="studiosdb-search w-full" 
                               placeholder="1">
                        <p class="text-slate-500 text-xs mt-1">Plus le nombre est élevé, plus la ceinture est avancée</p>
                        @error('ordre')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div>
                <h2 class="text-xl font-bold text-white mb-6 flex items-center">
                    <x-admin-icon name="edit" size="w-6 h-6" color="text-blue-400" />
                    <span class="ml-3">Description et prérequis</span>
                </h2>
                
                <div class="space-y-6">
                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-300 mb-2">
                            Description
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4"
                                  class="studiosdb-search w-full resize-vertical" 
                                  placeholder="Description de la ceinture, compétences requises, objectifs...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prérequis -->
                    <div>
                        <label for="prerequis" class="block text-sm font-medium text-slate-300 mb-2">
                            Prérequis
                        </label>
                        <textarea name="prerequis" 
                                  id="prerequis" 
                                  rows="3"
                                  class="studiosdb-search w-full resize-vertical" 
                                  placeholder="Compétences ou ceintures prérequises...">{{ old('prerequis') }}</textarea>
                        @error('prerequis')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Configuration avancée -->
            <div>
                <h2 class="text-xl font-bold text-white mb-6 flex items-center">
                    <x-admin-icon name="settings" size="w-6 h-6" color="text-violet-400" />
                    <span class="ml-3">Configuration avancée</span>
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Durée minimum -->
                    <div>
                        <label for="duree_minimum_mois" class="block text-sm font-medium text-slate-300 mb-2">
                            Durée minimum (mois)
                        </label>
                        <input type="number" 
                               name="duree_minimum_mois" 
                               id="duree_minimum_mois"
                               value="{{ old('duree_minimum_mois', 3) }}"
                               min="0"
                               max="60"
                               class="studiosdb-search w-full" 
                               placeholder="3">
                        <p class="text-slate-500 text-xs mt-1">Temps minimum avant passage ceinture suivante</p>
                        @error('duree_minimum_mois')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prix examen -->
                    <div>
                        <label for="prix_examen" class="block text-sm font-medium text-slate-300 mb-2">
                            Prix examen ($)
                        </label>
                        <input type="number" 
                               name="prix_examen" 
                               id="prix_examen"
                               value="{{ old('prix_examen', 0) }}"
                               min="0"
                               step="0.01"
                               class="studiosdb-search w-full" 
                               placeholder="0.00">
                        @error('prix_examen')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- École (si super admin) -->
                    @if(auth()->user()->hasRole('super_admin'))
                        <div>
                            <label for="ecole_id" class="block text-sm font-medium text-slate-300 mb-2">
                                École
                            </label>
                            <select name="ecole_id" id="ecole_id" class="studiosdb-select w-full">
                                <option value="">Toutes les écoles</option>
                                @foreach(\App\Models\Ecole::all() as $ecole)
                                    <option value="{{ $ecole->id }}" {{ old('ecole_id') == $ecole->id ? 'selected' : '' }}>
                                        {{ $ecole->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ecole_id')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <!-- Active -->
                    <div>
                        <label class="flex items-center space-x-3">
                            <input type="hidden" name="active" value="0">
                            <input type="checkbox" 
                                   name="active" 
                                   value="1"
                                   {{ old('active', true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-orange-500 bg-slate-700/50 border-slate-600/50 rounded focus:ring-orange-500">
                            <span class="text-slate-300 font-medium">Ceinture active</span>
                        </label>
                        <p class="text-slate-500 text-xs mt-1">Les ceintures inactives ne peuvent pas être attribuées</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-between items-center pt-6 border-t border-slate-700/30">
                <a href="{{ route('admin.ceintures.index') }}" 
                   class="studiosdb-btn studiosdb-btn-cancel">
                    <x-admin-icon name="chevron-left" size="w-4 h-4" />
                    <span class="ml-2">Annuler</span>
                </a>
                
                <div class="flex space-x-3">
                    <button type="submit" 
                            name="action" 
                            value="save_and_continue"
                            class="studiosdb-btn studiosdb-btn-ceintures">
                        <x-admin-icon name="plus" size="w-4 h-4" />
                        <span class="ml-2">Créer et Continuer</span>
                    </button>
                    
                    <button type="submit" 
                            class="studiosdb-btn studiosdb-btn-ceintures">
                        <x-admin-icon name="presences" size="w-4 h-4" />
                        <span class="ml-2">Créer la Ceinture</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Synchronisation couleur picker et input hex
document.getElementById('couleur').addEventListener('change', function() {
    document.getElementById('couleur_hex').value = this.value.toUpperCase();
});

document.getElementById('couleur_hex').addEventListener('input', function() {
    if (this.value.match(/^#[0-9A-Fa-f]{6}$/)) {
        document.getElementById('couleur').value = this.value;
    }
});
</script>
@endsection
