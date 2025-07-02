@extends('layouts.admin')

@section('title', 'Modifier ' . $user->name)

@section('content')
<div class="flex-1 flex flex-col overflow-hidden">
    <x-module-header 
        module="users" 
        title="Modifier {{ $user->name }}" 
        subtitle="✏️ Modification du profil membre"
    />

    <div class="flex-1 overflow-x-hidden overflow-y-auto p-6">
        <div class="studiosdb-card">
            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Informations de base -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Nom complet</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500">
                        @error('name')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500">
                        @error('email')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Téléphone</label>
                        <input type="text" name="telephone" value="{{ old('telephone', $user->telephone) }}" 
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Date de naissance</label>
                        <input type="date" name="date_naissance" value="{{ old('date_naissance', $user->date_naissance?->format('Y-m-d')) }}" 
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Sexe</label>
                        <select name="sexe" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500">
                            <option value="">Sélectionner...</option>
                            <option value="M" {{ old('sexe', $user->sexe) == 'M' ? 'selected' : '' }}>Masculin</option>
                            <option value="F" {{ old('sexe', $user->sexe) == 'F' ? 'selected' : '' }}>Féminin</option>
                            <option value="Autre" {{ old('sexe', $user->sexe) == 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">École</label>
                        <select name="ecole_id" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500">
                            @foreach(App\Models\Ecole::all() as $ecole)
                                <option value="{{ $ecole->id }}" {{ old('ecole_id', $user->ecole_id) == $ecole->id ? 'selected' : '' }}>
                                    {{ $ecole->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <!-- Adresse -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-white">📍 Adresse</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Adresse complète</label>
                        <textarea name="adresse" rows="3" 
                                  class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500">{{ old('adresse', $user->adresse) }}</textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Ville</label>
                            <input type="text" name="ville" value="{{ old('ville', $user->ville) }}" 
                                   class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Code postal</label>
                            <input type="text" name="code_postal" value="{{ old('code_postal', $user->code_postal) }}" 
                                   class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500">
                        </div>
                    </div>
                </div>
                
                <!-- Contact d'urgence -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-white">🚨 Contact d'urgence</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Nom du contact</label>
                            <input type="text" name="contact_urgence_nom" value="{{ old('contact_urgence_nom', $user->contact_urgence_nom) }}" 
                                   class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Téléphone d'urgence</label>
                            <input type="text" name="contact_urgence_telephone" value="{{ old('contact_urgence_telephone', $user->contact_urgence_telephone) }}" 
                                   class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500">
                        </div>
                    </div>
                </div>
                
                <!-- Informations famille -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-white">👨‍👩‍👧‍👦 Informations Famille</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Nom de famille groupé</label>
                            <input type="text" name="nom_famille" value="{{ old('nom_famille', $user->nom_famille) }}" 
                                   placeholder="ex: Famille Boudreau"
                                   class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500">
                            <div class="text-xs text-slate-400 mt-1">Grouper plusieurs membres sous le même nom</div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Contact principal famille</label>
                            <input type="text" name="contact_principal_famille" value="{{ old('contact_principal_famille', $user->contact_principal_famille) }}" 
                                   placeholder="ex: Papa Jean Boudreau"
                                   class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Téléphone principal famille</label>
                            <input type="text" name="telephone_principal_famille" value="{{ old('telephone_principal_famille', $user->telephone_principal_famille) }}" 
                                   placeholder="Téléphone du contact principal"
                                   class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Notes famille</label>
                            <textarea name="notes_famille" rows="3" 
                                      placeholder="Notes partagées pour toute la famille"
                                      class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500">{{ old('notes_famille', $user->notes_famille) }}</textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Notes -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Notes administratives</label>
                    <textarea name="notes" rows="4" 
                              class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-blue-500">{{ old('notes', $user->notes) }}</textarea>
                </div>
                
                <!-- Statut -->
                <div class="flex items-center">
                    <input type="checkbox" name="active" value="1" {{ old('active', $user->active) ? 'checked' : '' }}
                           class="mr-2 rounded border-slate-600 bg-slate-700 text-blue-600 focus:ring-blue-500">
                    <label class="text-sm font-medium text-slate-300">Membre actif</label>
                </div>
                
                <!-- Boutons -->
                <div class="flex justify-between">
                    <a href="{{ route('admin.users.show', $user) }}" 
                       class="studiosdb-btn studiosdb-btn-cancel">
                        ← Retour au profil
                    </a>
                    
                    <button type="submit" class="studiosdb-btn studiosdb-btn-users">
                        💾 Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
