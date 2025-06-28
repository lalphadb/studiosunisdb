@extends('layouts.admin')

@section('title', 'Créer un Utilisateur')

@section('content')
<div class="admin-content">
   {{-- DEBUG TEMPORAIRE --}}
   @if(isset($debugInfo))
   <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6">
       <h4 class="font-bold">DEBUG INFO:</h4>
       <pre>{{ json_encode($debugInfo, JSON_PRETTY_PRINT) }}</pre>
   </div>
   @endif

   {{-- Header uniforme --}}
   <x-module-header 
       title="Créer un Membre"
       subtitle="Ajouter un nouveau karatéka au système"
       icon="👤➕"
       gradient="from-blue-600 to-purple-600"
       :create-route="null"
       create-text="" />

   <!-- Formulaire -->
   <div class="bg-gray-800 rounded-xl border border-gray-700">
       <form method="POST" action="{{ route('admin.users.store') }}" class="p-6">
           @csrf
           
           <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-6">
               <!-- Colonne Gauche - Informations de base -->
               <div class="space-y-6">
                   <h4 class="text-lg font-medium text-white border-b border-gray-700 pb-2">👤 Informations Personnelles</h4>
                   
                   <div>
                       <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                           Nom complet <span class="text-red-400">*</span>
                       </label>
                       <input type="text" id="name" name="name" value="{{ old('name') }}" required
                              class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                       @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                   </div>

                   <div>
                       <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                           Email <span class="text-red-400">*</span>
                       </label>
                       <input type="email" id="email" name="email" value="{{ old('email') }}" required
                              class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                       @error('email') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                   </div>

                   <div>
                       <label for="telephone" class="block text-sm font-medium text-gray-300 mb-2">Téléphone</label>
                       <input type="tel" id="telephone" name="telephone" value="{{ old('telephone') }}"
                              placeholder="418-262-6609"
                              class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                       @error('telephone') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                   </div>

                   <div>
                       <label for="date_naissance" class="block text-sm font-medium text-gray-300 mb-2">Date de naissance</label>
                       <input type="date" id="date_naissance" name="date_naissance" value="{{ old('date_naissance') }}"
                              class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                       @error('date_naissance') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                   </div>

                   <div>
                       <label for="sexe" class="block text-sm font-medium text-gray-300 mb-2">Sexe</label>
                       <select id="sexe" name="sexe"
                               class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                           <option value="">Non spécifié</option>
                           <option value="M" {{ old('sexe') == 'M' ? 'selected' : '' }}>Masculin</option>
                           <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>Féminin</option>
                           <option value="Autre" {{ old('sexe') == 'Autre' ? 'selected' : '' }}>Autre</option>
                       </select>
                   </div>

                   <div>
                       <label for="adresse" class="block text-sm font-medium text-gray-300 mb-2">Adresse</label>
                       <textarea id="adresse" name="adresse" rows="2"
                                 placeholder="618 rue du Bouleau-Blanc"
                                 class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">{{ old('adresse') }}</textarea>
                       @error('adresse') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                   </div>

                   <div class="grid grid-cols-2 gap-4">
                       <div>
                           <label for="ville" class="block text-sm font-medium text-gray-300 mb-2">Ville</label>
                           <input type="text" id="ville" name="ville" value="{{ old('ville') }}"
                                  placeholder="Quebec"
                                  class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                       </div>
                       <div>
                           <label for="code_postal" class="block text-sm font-medium text-gray-300 mb-2">Code postal</label>
                           <input type="text" id="code_postal" name="code_postal" value="{{ old('code_postal') }}"
                                  placeholder="G3G1V8"
                                  class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                       </div>
                   </div>
               </div>

               <!-- Colonne Droite - Système et Contact d'urgence -->
               <div class="space-y-6">
                   <h4 class="text-lg font-medium text-white border-b border-gray-700 pb-2">🏫 Informations Système</h4>
                   
                   <div>
                       <label for="ecole_id" class="block text-sm font-medium text-gray-300 mb-2">
                           École <span class="text-red-400">*</span>
                       </label>
                       <select id="ecole_id" name="ecole_id" required
                               class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                           <option value="">Sélectionner une école</option>
                           @foreach($ecoles as $ecole)
                               <option value="{{ $ecole->id }}" {{ old('ecole_id') == $ecole->id ? 'selected' : '' }}>
                                   {{ $ecole->nom }}
                               </option>
                           @endforeach
                       </select>
                       @error('ecole_id') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                   </div>

                   <!-- Champ Famille -->
                   <div>
                       <label for="famille_principale_id" class="block text-sm font-medium text-gray-300 mb-2">
                           Chef de famille (optionnel)
                       </label>
                       <select id="famille_principale_id" name="famille_principale_id"
                               class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                           <option value="">Aucun - Membre indépendant</option>
                           @foreach($chefsDesFamilles ?? [] as $chef)
                               <option value="{{ $chef->id }}" {{ old('famille_principale_id') == $chef->id ? 'selected' : '' }}>
                                   {{ $chef->name }} ({{ $chef->email }})
                               </option>
                           @endforeach
                       </select>
                       <p class="text-xs text-gray-400 mt-1">Si ce membre fait partie d'une famille, sélectionner le chef de famille</p>
                       @error('famille_principale_id') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                   </div>

                   <div>
                       <label for="role" class="block text-sm font-medium text-gray-300 mb-2">
                           Rôle <span class="text-red-400">*</span>
                       </label>
                       <select id="role" name="role" required
                               class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                           <option value="">Sélectionner un rôle</option>
                           @if(isset($availableRoles) && count($availableRoles) > 0)
                               @foreach($availableRoles as $roleValue => $roleLabel)
                                   <option value="{{ $roleValue }}" {{ old('role') == $roleValue ? 'selected' : '' }}>
                                       {{ $roleLabel }}
                                   </option>
                               @endforeach
                           @else
                               <option value="">Aucun rôle disponible</option>
                           @endif
                       </select>
                       @error('role') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                   </div>

                   <div>
                       <label for="date_inscription" class="block text-sm font-medium text-gray-300 mb-2">Date d'inscription</label>
                       <input type="date" id="date_inscription" name="date_inscription" value="{{ old('date_inscription', date('Y-m-d')) }}"
                              class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                   </div>

                   <div>
                       <label class="flex items-center">
                           <input type="checkbox" name="active" value="1" {{ old('active', true) ? 'checked' : '' }}
                                  class="rounded border-gray-600 text-blue-600 focus:ring-blue-500 bg-gray-900">
                           <span class="ml-2 text-gray-300">Utilisateur actif</span>
                       </label>
                   </div>

                   <div class="border-t border-gray-600 pt-6">
                       <h4 class="text-lg font-medium text-white mb-4">🚨 Contact d'urgence</h4>
                       
                       <div class="space-y-4">
                           <div>
                               <label for="contact_urgence_nom" class="block text-sm font-medium text-gray-300 mb-2">
                                   Nom du contact d'urgence
                               </label>
                               <input type="text" id="contact_urgence_nom" name="contact_urgence_nom" value="{{ old('contact_urgence_nom') }}"
                                      placeholder="Isabelle Lanteigne"
                                      class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                           </div>

                           <div>
                               <label for="contact_urgence_telephone" class="block text-sm font-medium text-gray-300 mb-2">
                                   Téléphone d'urgence
                               </label>
                               <input type="tel" id="contact_urgence_telephone" name="contact_urgence_telephone" value="{{ old('contact_urgence_telephone') }}"
                                      placeholder="418-998-8234"
                                      class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                           </div>
                       </div>
                   </div>

                   <div class="border-t border-gray-600 pt-6">
                       <h4 class="text-lg font-medium text-white mb-4">🔒 Accès au système</h4>
                       
                       <div class="space-y-4">
                           <div>
                               <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                                   Mot de passe <span class="text-red-400">*</span>
                               </label>
                               <input type="password" id="password" name="password" required
                                      class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                               @error('password') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                           </div>

                           <div>
                               <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">
                                   Confirmer le mot de passe <span class="text-red-400">*</span>
                               </label>
                               <input type="password" id="password_confirmation" name="password_confirmation" required
                                      class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">
                           </div>
                       </div>
                   </div>

                   <div>
                       <label for="notes" class="block text-sm font-medium text-gray-300 mb-2">Notes</label>
                       <textarea id="notes" name="notes" rows="3"
                                 placeholder="Notes spéciales, conditions médicales, etc."
                                 class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
                   </div>
               </div>
           </div>
           
           <!-- Actions -->
           <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-600">
               <a href="{{ route('admin.users.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition-colors">
                   ❌ Annuler
               </a>
               <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors">
                   ✅ Créer le membre
               </button>
           </div>
       </form>
   </div>
</div>
@endsection
