@extends('layouts.admin')

@section('title', $user->name)

@section('content')
<div class="admin-content">
    <!-- Header utilisateur -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-6 text-white shadow-xl mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center mb-4 lg:mb-0">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                    <span class="text-2xl font-bold">{{ substr($user->name, 0, 2) }}</span>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">{{ $user->name }}</h1>
                    <p class="text-blue-100">{{ $user->email }}</p>
                    <div class="flex items-center mt-2 space-x-2">
                        @if($user->roles->count() > 0)
                            @foreach($user->roles as $role)
                                @php
                                    $roleColors = [
                                        'superadmin' => 'bg-red-500',
                                        'admin' => 'bg-purple-500',
                                        'instructeur' => 'bg-green-500',
                                        'membre' => 'bg-blue-500'
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs font-medium text-white {{ $roleColors[$role->name] ?? 'bg-gray-500' }}">
                                    {{ ucfirst($role->name) }}
                                </span>
                            @endforeach
                        @endif
                        
                        @if($user->active)
                            <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs">Actif</span>
                        @else
                            <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs">Inactif</span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="flex space-x-3">
                @can('edit-user')
                <a href="{{ route('admin.users.edit', $user) }}" class="btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Modifier
                </a>
                @endcan
                
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour à la liste
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations personnelles -->
            <div class="admin-card">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                        Informations Personnelles
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Nom complet</label>
                            <p class="text-white">{{ $user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Email</label>
                            <p class="text-white">{{ $user->email }}</p>
                        </div>
                        @if($user->telephone)
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Téléphone</label>
                            <p class="text-white">{{ $user->telephone }}</p>
                        </div>
                        @endif
                        @if($user->date_naissance)
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Date de naissance</label>
                            <p class="text-white">{{ $user->date_naissance->format('d/m/Y') }}</p>
                            <p class="text-gray-400 text-sm">{{ $user->age ?? 'N/A' }} ans</p>
                        </div>
                        @endif
                        @if($user->sexe)
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Sexe</label>
                            <p class="text-white">{{ $user->sexe }}</p>
                        </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Date d'inscription</label>
                            <p class="text-white">{{ $user->date_inscription ? $user->date_inscription->format('d/m/Y') : 'N/A' }}</p>
                        </div>
                    </div>
                    
                    @if($user->adresse)
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Adresse</label>
                        <p class="text-white">{{ $user->adresse }}</p>
                        @if($user->ville || $user->code_postal)
                        <p class="text-gray-400">{{ $user->ville }} {{ $user->code_postal }}</p>
                        @endif
                    </div>
                    @endif
                    
                    @if($user->contact_urgence_nom)
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Contact d'urgence</label>
                        <p class="text-white">{{ $user->contact_urgence_nom }}</p>
                        @if($user->contact_urgence_telephone)
                        <p class="text-gray-400">{{ $user->contact_urgence_telephone }}</p>
                        @endif
                    </div>
                    @endif
                    
                    @if($user->notes)
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Notes</label>
                        <p class="text-gray-300">{{ $user->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Progression ceintures -->
            @if($user->membre_ceintures && $user->membre_ceintures->count() > 0)
            <div class="admin-card">
                <div class="bg-gradient-to-r from-yellow-600 to-orange-600 p-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Progression Ceintures ({{ $user->membre_ceintures->count() }})
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach($user->membre_ceintures->sortByDesc('date_obtention') as $progression)
                        <div class="flex items-center justify-between p-3 bg-gray-700 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full mr-3" style="background-color: {{ $progression->ceinture->couleur ?? '#666' }}"></div>
                                <div>
                                    <p class="text-white font-medium">{{ $progression->ceinture->nom ?? 'Ceinture inconnue' }}</p>
                                    <p class="text-gray-400 text-sm">{{ $progression->date_obtention->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                @if($progression->valide)
                                    <span class="badge-success">Validé</span>
                                @else
                                    <span class="badge-warning">En attente</span>
                                @endif
                                @if($progression->examinateur)
                                <p class="text-gray-400 text-xs mt-1">{{ $progression->examinateur }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Présences récentes -->
            @if($user->presences && $user->presences->count() > 0)
            <div class="admin-card">
                <div class="bg-gradient-to-r from-green-600 to-teal-600 p-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Présences Récentes ({{ $user->presences->count() }})
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-2">
                        @foreach($user->presences->take(5) as $presence)
                        <div class="flex items-center justify-between p-2 bg-gray-700 rounded">
                            <div>
                                <p class="text-white text-sm">{{ $presence->cours->nom ?? 'Cours supprimé' }}</p>
                                <p class="text-gray-400 text-xs">{{ $presence->date_cours->format('d/m/Y') }}</p>
                            </div>
                            @if($presence->present)
                                <span class="badge-success">Présent</span>
                            @else
                                <span class="badge-danger">Absent</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar droite -->
        <div class="space-y-6">
            <!-- École d'appartenance -->
            <div class="admin-card">
                <div class="bg-gradient-to-r from-green-600 to-teal-600 p-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.84L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.84l-7-3z"/>
                        </svg>
                        École
                    </h3>
                </div>
                <div class="p-6">
                    @if($user->ecole)
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-teal-400 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <span class="text-white font-bold text-lg">{{ $user->ecole->code }}</span>
                        </div>
                        <h4 class="text-white font-medium text-lg">{{ $user->ecole->nom }}</h4>
                        <p class="text-gray-400">{{ $user->ecole->ville }}, {{ $user->ecole->province }}</p>
                        @if($user->ecole->telephone)
                        <p class="text-gray-400 text-sm mt-2">{{ $user->ecole->telephone }}</p>
                        @endif
                        <div class="mt-4">
                            <a href="{{ route('admin.ecoles.show', $user->ecole) }}" class="text-blue-400 hover:text-blue-300 text-sm">
                                Voir détails de l'école →
                            </a>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <p class="text-gray-400">Aucune école assignée</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="admin-card">
                <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                        Actions
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    @can('edit-user')
                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="block w-full text-center btn-primary">
                        Modifier utilisateur
                    </a>
                    @endcan
                    
                    <button onclick="alert('QR Code à implémenter')" 
                            class="block w-full text-center btn-secondary">
                        Générer QR Code
                    </button>
                    
                    @if($user->ecole)
                    <a href="{{ route('admin.ecoles.show', $user->ecole) }}" 
                       class="block w-full text-center btn-secondary">
                        Voir son école
                    </a>
                    @endif
                    
                    @can('delete-user')
                    @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')" 
                          class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="block w-full text-center btn-danger">
                            Supprimer utilisateur
                        </button>
                    </form>
                    @endif
                    @endcan
                </div>
            </div>

            <!-- Statistiques -->
            <div class="admin-card">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                        </svg>
                        Statistiques
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-white">{{ $user->membre_ceintures->count() }}</div>
                        <div class="text-gray-400 text-sm">Ceintures obtenues</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-white">{{ $user->presences->where('present', true)->count() }}</div>
                        <div class="text-gray-400 text-sm">Présences</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-white">{{ $user->inscriptions_cours->count() }}</div>
                        <div class="text-gray-400 text-sm">Cours inscrits</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
