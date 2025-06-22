@extends('layouts.admin')

@section('title', 'Profil de ' . $user->name)

@section('content')
<div class="space-y-6">
    <!-- Header avec avatar et badges comme l'original -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-6 text-white shadow-xl">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <!-- Avatar avec initiales -->
                <div class="w-16 h-16 bg-gray-700 rounded-xl flex items-center justify-center text-white text-xl font-bold">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div>
                    <h1 class="text-3xl font-bold">{{ $user->name }}</h1>
                    <p class="text-blue-100">{{ $user->email }}</p>
                    <div class="flex space-x-2 mt-2">
                        @if($user->roles->isNotEmpty())
                            <span class="bg-purple-500 text-white px-3 py-1 rounded-full text-sm font-medium">{{ ucfirst($user->roles->first()->name) }}</span>
                        @endif
                        @if($user->active)
                            <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-medium">Actif</span>
                        @else
                            <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-medium">Inactif</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex space-x-3">
                <button class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-all flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                    </svg>
                    Modifier
                </button>
                <a href="{{ route('admin.users.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-all flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                    </svg>
                    Retour à la liste
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations Personnelles - Colonne principale -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations Personnelles -->
            <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl p-6 text-white">
                <div class="flex items-center mb-4">
                    <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                    </svg>
                    <h3 class="text-lg font-bold">Informations Personnelles</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-blue-200 text-sm font-medium mb-1">Nom complet</label>
                                <p class="text-white font-medium">{{ $user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-blue-200 text-sm font-medium mb-1">Email</label>
                                <p class="text-white">{{ $user->email }}</p>
                            </div>
                            <div>
                                <label class="block text-blue-200 text-sm font-medium mb-1">Téléphone</label>
                                <p class="text-white">{{ $user->telephone ?? 'Non renseigné' }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-blue-200 text-sm font-medium mb-1">Date de naissance</label>
                                <p class="text-white">{{ $user->date_naissance?->format('d/m/Y') ?? 'Non renseignée' }}</p>
                                @if($user->date_naissance)
                                    <p class="text-blue-200 text-sm">{{ $user->date_naissance->diffInYears(now()) }} ans</p>
                                @endif
                            </div>
                            <div>
                                <label class="block text-blue-200 text-sm font-medium mb-1">Sexe</label>
                                <p class="text-white">{{ $user->sexe ?? 'Non renseigné' }}</p>
                            </div>
                            <div>
                                <label class="block text-blue-200 text-sm font-medium mb-1">Date d'inscription</label>
                                <p class="text-white">{{ $user->date_inscription?->format('d/m/Y') ?? $user->created_at?->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Adresse -->
                @if($user->adresse || $user->ville || $user->code_postal)
                <div class="mt-6 pt-6 border-t border-blue-500 border-opacity-30">
                    <label class="block text-blue-200 text-sm font-medium mb-2">Adresse</label>
                    <div class="text-white">
                        @if($user->adresse)
                            <p>{{ $user->adresse }}</p>
                        @endif
                        @if($user->ville || $user->code_postal)
                            <p>{{ $user->ville }}{{ $user->ville && $user->code_postal ? ', ' : '' }}{{ $user->code_postal }}</p>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Contact d'urgence - PETIT ENCADRÉ ROUGE -->
                @if($user->contact_urgence_nom || $user->contact_urgence_telephone)
                <div class="mt-6 pt-6 border-t border-blue-500 border-opacity-30">
                    <div class="bg-red-500 bg-opacity-20 border border-red-400 rounded-lg p-3">
                        <label class="block text-red-200 text-sm font-medium mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            Contact d'urgence
                        </label>
                        <div class="text-red-100 text-sm space-y-1">
                            @if($user->contact_urgence_nom)
                                <p><strong>{{ $user->contact_urgence_nom }}</strong></p>
                            @endif
                            @if($user->contact_urgence_telephone)
                                <p>{{ $user->contact_urgence_telephone }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar droite - École et Actions -->
        <div class="space-y-6">
            <!-- École -->
            <div class="bg-gradient-to-br from-green-600 to-green-700 rounded-xl p-6 text-white text-center">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    @if($user->ecole)
                        <span class="text-2xl font-bold">{{ $user->ecole->code }}</span>
                    @else
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
                        </svg>
                    @endif
                </div>
                @if($user->ecole)
                    <h3 class="text-lg font-bold">{{ $user->ecole->nom }}</h3>
                    <p class="text-green-200 text-sm">{{ $user->ecole->ville }}, {{ $user->ecole->province }}</p>
                    <a href="#" class="inline-block mt-3 text-green-200 hover:text-white text-sm underline">
                        Voir détails de l'école →
                    </a>
                @else
                    <h3 class="text-lg font-bold">Aucune École</h3>
                    <p class="text-green-200 text-sm">Non assigné</p>
                @endif
            </div>

            <!-- Actions -->
            <div class="bg-gradient-to-br from-purple-600 to-purple-700 rounded-xl p-6 text-white">
                <div class="flex items-center mb-4">
                    <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 002 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z"/>
                    </svg>
                    <h3 class="text-lg font-bold">Actions</h3>
                </div>
                
                <div class="space-y-3">
                    <button class="w-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white py-2 px-4 rounded-lg transition-all text-left">
                        Modifier utilisateur
                    </button>
                    <button class="w-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white py-2 px-4 rounded-lg transition-all text-left">
                        Générer QR Code
                    </button>
                    <button class="w-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white py-2 px-4 rounded-lg transition-all text-left">
                        Voir son école
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes administratives -->
    @if($user->notes)
    <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
        <h3 class="text-lg font-bold text-white mb-4 flex items-center">
            <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
            </svg>
            Notes Administratives
        </h3>
        <p class="text-gray-300 bg-gray-700 p-4 rounded">{{ $user->notes }}</p>
    </div>
    @endif
</div>
@endsection
