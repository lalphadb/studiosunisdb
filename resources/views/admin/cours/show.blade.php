<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.cours.index') }}" class="text-slate-400 hover:text-white">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                    {{ $cours->nom }}
                </h2>
            </div>
            
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.cours.edit', $cours) }}" 
                   class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-edit mr-2"></i>Modifier
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-slate-800 border border-slate-700 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-600 rounded-lg">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-slate-400 text-sm">Inscrits</p>
                            <p class="text-2xl font-bold text-white">{{ $stats['nombre_inscrits'] }}/{{ $cours->capacite_max }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-slate-800 border border-slate-700 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-600 rounded-lg">
                            <i class="fas fa-chair text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-slate-400 text-sm">Places disponibles</p>
                            <p class="text-2xl font-bold text-white">{{ $stats['places_disponibles'] }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-slate-800 border border-slate-700 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-600 rounded-lg">
                            <i class="fas fa-percentage text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-slate-400 text-sm">Taux d'occupation</p>
                            <p class="text-2xl font-bold text-white">{{ $stats['taux_occupation'] }}%</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Informations du cours -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Détails généraux -->
                    <div class="bg-slate-800 border border-slate-700 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4">Informations générales</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-slate-400">Nom du cours</p>
                                <p class="text-white font-medium">{{ $cours->nom }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-slate-400">Type</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($cours->type_cours ?? 'Non défini') }}
                                </span>
                            </div>
                            
                            <div>
                                <p class="text-sm text-slate-400">École</p>
                                <p class="text-white font-medium">{{ $cours->ecole->nom }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-slate-400">Instructeur</p>
                                <p class="text-white font-medium">{{ $cours->instructeur->name ?? 'Non assigné' }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-slate-400">Statut</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($cours->status == 'actif') bg-green-100 text-green-800
                                    @elseif($cours->status == 'complet') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($cours->status) }}
                                </span>
                            </div>
                            
                            <div>
                                <p class="text-sm text-slate-400">Horaire</p>
                                <p class="text-white font-medium">{{ $cours->horaire_formatte }}</p>
                            </div>
                        </div>
                        
                        @if($cours->description)
                            <div class="mt-4">
                                <p class="text-sm text-slate-400">Description</p>
                                <p class="text-white">{{ $cours->description }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Liste des inscrits -->
                    <div class="bg-slate-800 border border-slate-700 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-white">Membres inscrits ({{ $cours->inscriptionsActives->count() }})</h3>
                        </div>
                        
                        @if($cours->inscriptionsActives->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-700">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase">Membre</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase">Inscription</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase">Statut</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-300 uppercase">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-700">
                                        @foreach($cours->inscriptionsActives as $inscription)
                                            <tr>
                                                <td class="px-4 py-3">
                                                    <div>
                                                        <p class="text-sm font-medium text-white">{{ $inscription->membre->nom }} {{ $inscription->membre->prenom }}</p>
                                                        <p class="text-xs text-slate-400">{{ $inscription->membre->email }}</p>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <p class="text-sm text-white">{{ $inscription->date_inscription->format('d/m/Y') }}</p>
                                                    @if($inscription->montant_paye > 0)
                                                        <p class="text-xs text-green-400">${{ number_format($inscription->montant_paye, 2) }} payé</p>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3">
                                                    <span class="inline-flex px-2 py-1 text-xs rounded
                                                        @if($inscription->status == 'active') bg-green-100 text-green-800
                                                        @else bg-yellow-100 text-yellow-800 @endif">
                                                        {{ ucfirst($inscription->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-right">
                                                    <a href="{{ route('admin.membres.show', $inscription->membre) }}" 
                                                       class="text-blue-400 hover:text-blue-300" title="Voir le membre">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-user-slash text-4xl text-slate-600 mb-4"></i>
                                <p class="text-slate-400">Aucun membre inscrit pour le moment</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    
                    <!-- Détails techniques -->
                    <div class="bg-slate-800 border border-slate-700 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4">Paramètres</h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-slate-400">Capacité max</span>
                                <span class="text-white font-medium">{{ $cours->capacite_max }}</span>
                            </div>
                            
                            @if($cours->duree_minutes)
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Durée</span>
                                    <span class="text-white font-medium">{{ $cours->duree_formattee }}</span>
                                </div>
                            @endif
                            
                            @if($cours->age_min || $cours->age_max)
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Âge</span>
                                    <span class="text-white font-medium">
                                        @if($cours->age_min && $cours->age_max)
                                            {{ $cours->age_min }}-{{ $cours->age_max }} ans
                                        @elseif($cours->age_min)
                                            {{ $cours->age_min }}+ ans
                                        @else
                                            -{{ $cours->age_max }} ans
                                        @endif
                                    </span>
                                </div>
                            @endif
                            
                            @if($cours->niveau_requis)
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Niveau</span>
                                    <span class="text-white font-medium">{{ $cours->niveau_requis }}</span>
                                </div>
                            @endif
                            
                            <div class="flex justify-between">
                                <span class="text-slate-400">Prix mensuel</span>
                                <span class="text-white font-medium">${{ number_format($cours->prix_principal, 2) }}</span>
                            </div>
                            
                            @if($cours->prix_session > 0)
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Prix/session</span>
                                    <span class="text-white font-medium">${{ number_format($cours->prix_session, 2) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Inscrire un membre -->
                    @if(!$cours->est_complet && isset($membresDisponibles) && $membresDisponibles->count() > 0)
                        <div class="bg-slate-800 border border-slate-700 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-white mb-4">Inscrire un membre</h3>
                            
                            <form action="{{ route('admin.cours.inscrire-membre', $cours) }}" method="POST" class="space-y-4">
                                @csrf
                                
                                <div>
                                    <select name="membre_id" required class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2">
                                        <option value="">Sélectionner un membre</option>
                                        @foreach($membresDisponibles as $membre)
                                            <option value="{{ $membre->id }}">{{ $membre->nom }} {{ $membre->prenom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <input type="date" name="date_inscription" value="{{ date('Y-m-d') }}" required
                                           class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2">
                                </div>
                                
                                <div>
                                    <textarea name="notes" placeholder="Notes (optionnel)" rows="2"
                                              class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2"></textarea>
                                </div>
                                
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                                    <i class="fas fa-user-plus mr-2"></i>Inscrire
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
