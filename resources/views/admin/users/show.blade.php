@extends('layouts.admin')

@section('title', 'Profil de ' . $user->name)

@section('content')
<div class="admin-content">
    {{-- Header principal avec gradient et informations de base --}}
    <div class="bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 rounded-xl p-6 text-white mb-6">
        <div class="flex justify-between items-start">
            <div class="flex items-center space-x-4">
                <!-- Avatar avec initiales -->
                <div class="w-16 h-16 bg-black bg-opacity-30 rounded-xl flex items-center justify-center">
                    <span class="text-2xl font-bold text-white">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
                    <p class="text-blue-100">{{ $user->email }}</p>
                    <!-- Badges de rôles -->
                    <div class="flex space-x-2 mt-2">
                        @if($user->roles->isNotEmpty())
                            @foreach($user->roles as $role)
                                @php
                                    $roleLabels = [
                                        'superadmin' => 'Superadmin',
                                        'admin_ecole' => 'Admin École', 
                                        'admin' => 'Admin',
                                        'instructeur' => 'Instructeur',
                                        'membre' => 'Membre'
                                    ];
                                    $roleColors = [
                                        'superadmin' => 'bg-red-600 text-red-100',
                                        'admin_ecole' => 'bg-orange-600 text-orange-100',
                                        'admin' => 'bg-orange-600 text-orange-100', 
                                        'instructeur' => 'bg-purple-600 text-purple-100',
                                        'membre' => 'bg-green-600 text-green-100'
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded-md {{ $roleColors[$role->name] ?? 'bg-gray-600 text-gray-100' }}">
                                    {{ $roleLabels[$role->name] ?? ucfirst(str_replace('_', ' ', $role->name)) }}
                                </span>
                            @endforeach
                        @endif
                        @if($user->active)
                            <span class="px-2 py-1 text-xs font-medium rounded-md bg-green-600 text-green-100">
                                Actif
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Boutons d'action -->
            <div class="flex space-x-3">
                @can('update', $user)
                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition duration-200 text-sm font-medium flex items-center">
                        <span class="mr-2">✏️</span>Modifier
                    </a>
                @endcan
                <a href="{{ route('admin.users.index') }}" 
                   class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition duration-200 text-sm font-medium flex items-center">
                    <span class="mr-2">←</span>Retour à la liste
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne gauche - Informations principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations Personnelles -->
            <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <span class="mr-2">👤</span>Informations Personnelles
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Colonne 1 -->
                        <div class="space-y-4">
                            <div>
                                <div class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">📧 Email</div>
                                <div class="text-white">{{ $user->email }}</div>
                            </div>
                            
                            @if($user->telephone)
                            <div>
                                <div class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">📞 Téléphone</div>
                                <div class="text-white">{{ $user->telephone }}</div>
                            </div>
                            @endif

                            @if($user->sexe)
                            <div>
                                <div class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">👤 Sexe</div>
                                <div class="text-white">{{ $user->sexe === 'M' ? 'Masculin' : ($user->sexe === 'F' ? 'Féminin' : $user->sexe) }}</div>
                            </div>
                            @endif
                        </div>

                        <!-- Colonne 2 -->
                        <div class="space-y-4">
                            @if($user->date_naissance)
                            <div>
                                <div class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">🎂 Date de naissance</div>
                                <div class="text-white">{{ $user->date_naissance->format('d/m/Y') }}</div>
                                <div class="text-orange-400 text-sm">{{ $user->date_naissance->age }} ans</div>
                            </div>
                            @endif

                            <div>
                                <div class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">📅 Date d'inscription</div>
                                <div class="text-white">{{ $user->date_inscription ? $user->date_inscription->format('d/m/Y') : 'N/A' }}</div>
                                @if($user->date_inscription)
                                    <div class="text-gray-400 text-sm">{{ $user->date_inscription->diffForHumans() }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($user->adresse)
                    <div class="mt-6 pt-6 border-t border-gray-700">
                        <div class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">🏠 Adresse</div>
                        <div class="text-white">{{ $user->adresse }}</div>
                        @if($user->ville || $user->code_postal)
                            <div class="text-gray-300">
                                {{ $user->ville }}{{ $user->ville && $user->code_postal ? ', ' : '' }}{{ $user->code_postal }}
                            </div>
                        @endif
                    </div>
                    @endif

                    @if($user->contact_urgence_nom)
                    <div class="mt-6 pt-6 border-t border-gray-700">
                        <div class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">🚨 Contact d'urgence</div>
                        <div class="text-white">{{ $user->contact_urgence_nom }}</div>
                        @if($user->contact_urgence_telephone)
                            <div class="text-gray-300">{{ $user->contact_urgence_telephone }}</div>
                        @endif
                    </div>
                    @endif

                    @if($user->notes)
                    <div class="mt-6 pt-6 border-t border-gray-700">
                        <div class="bg-blue-900 rounded-lg p-4">
                            <h4 class="font-medium text-blue-100 mb-2">📝 Notes</h4>
                            <p class="text-blue-200">{{ $user->notes }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Famille si applicable --}}
                    @if($user->famillePrincipale || $user->membresFamille->count() > 0)
                    <div class="mt-6 pt-6 border-t border-gray-700">
                        <div class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-3">👨‍👩‍👧‍👦 Famille</div>
                        
                        @if($user->famillePrincipale)
                            <div class="mb-3">
                                <div class="text-gray-300 text-sm">Chef de famille :</div>
                                <div class="text-white">{{ $user->famillePrincipale->name }}</div>
                            </div>
                        @endif

                        @if($user->membresFamille->count() > 0)
                            <div>
                                <div class="text-gray-300 text-sm mb-2">Membres de la famille :</div>
                                @foreach($user->membresFamille as $membre)
                                    <div class="flex justify-between items-center bg-gray-700 rounded p-2 mb-1">
                                        <span class="text-white">{{ $membre->name }}</span>
                                        <span class="text-xs text-gray-400">{{ $membre->roles->first()->name ?? 'Aucun rôle' }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne droite - École et Progression -->
        <div class="space-y-6">
            <!-- École -->
            <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <span class="mr-2">🏫</span>École
                    </h3>
                </div>
                <div class="p-6 text-center">
                    @if($user->ecole)
                        <div class="w-16 h-16 bg-green-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <span class="text-white font-bold text-lg">
                                {{ strtoupper(substr($user->ecole->nom, 0, 3)) }}
                            </span>
                        </div>
                        <h4 class="text-white font-medium">{{ $user->ecole->nom }}</h4>
                        <p class="text-gray-400 text-sm">{{ $user->ecole->ville ?? 'Localisation non définie' }}</p>
                        @if(Route::has('admin.ecoles.show'))
                            <a href="{{ route('admin.ecoles.show', $user->ecole) }}" 
                               class="text-green-400 hover:text-green-300 text-sm mt-2 inline-block">
                                Voir détails de l'école →
                            </a>
                        @endif
                    @else
                        <div class="w-16 h-16 bg-gray-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <span class="text-gray-400 text-lg">❓</span>
                        </div>
                        <p class="text-gray-400">Aucune école assignée</p>
                    @endif
                </div>
            </div>

            <!-- Progression Ceintures -->
            @if($user->ceintureActuelle())
                @php $ceinture_actuelle = $user->ceintureActuelle(); @endphp
                <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-yellow-600 to-orange-600 px-6 py-4">
                        <h3 class="text-lg font-bold text-white flex items-center">
                            <span class="mr-2">🥋</span>Progression Ceintures
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg p-4 text-center">
                            <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center mx-auto mb-3">
                                <span class="text-yellow-600 font-bold text-lg">🥋</span>
                            </div>
                            <h4 class="text-white font-bold">{{ $ceinture_actuelle->ceinture->nom }}</h4>
                            <p class="text-yellow-100 text-sm">Niveau {{ $ceinture_actuelle->ceinture->ordre }}/21</p>
                            <p class="text-yellow-200 text-xs">
                                Obtenue le {{ $ceinture_actuelle->date_obtention->format('d/m/Y') }}
                            </p>
                        </div>

                        <div class="mt-4">
                            <div class="flex justify-between text-sm text-gray-400 mb-2">
                                <span>Progression Globale</span>
                                <span>{{ $ceinture_actuelle->ceinture->ordre }}/21 ({{ round(($ceinture_actuelle->ceinture->ordre / 21) * 100) }}%)</span>
                            </div>
                            <div class="w-full bg-gray-700 rounded-full h-2">
                                <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ round(($ceinture_actuelle->ceinture->ordre / 21) * 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-600 to-gray-700 px-6 py-4">
                        <h3 class="text-lg font-bold text-white flex items-center">
                            <span class="mr-2">🥋</span>Progression Ceintures
                        </h3>
                    </div>
                    <div class="p-6 text-center">
                        <div class="w-16 h-16 bg-gray-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <span class="text-gray-400 text-lg">🥋</span>
                        </div>
                        <p class="text-gray-400">Aucune ceinture attribuée</p>
                        <a href="{{ route('admin.ceintures.create', ['user_id' => $user->id]) }}" 
                           class="text-blue-400 hover:text-blue-300 text-sm mt-2 inline-block">
                            Attribuer première ceinture →
                        </a>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <span class="mr-2">⚡</span>Actions
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    @can('update', $user)
                        <a href="{{ route('admin.users.edit', $user) }}" 
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-medium transition-colors flex items-center justify-center">
                            <span class="mr-2">✏️</span>Modifier Membre
                        </a>
                    @endcan

                    <button class="w-full bg-gray-600 hover:bg-gray-700 text-white py-3 px-4 rounded-lg font-medium transition-colors flex items-center justify-center">
                        <span class="mr-2">📱</span>Générer QR Code
                    </button>

                    @if($user->ecole && Route::has('admin.ecoles.show'))
                        <a href="{{ route('admin.ecoles.show', $user->ecole) }}" 
                           class="w-full bg-gray-600 hover:bg-gray-700 text-white py-3 px-4 rounded-lg font-medium transition-colors flex items-center justify-center">
                            <span class="mr-2">🏫</span>Voir son école
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Section Actions rapides en bas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
        @can('update', $user)
            <a href="{{ route('admin.users.edit', $user) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white p-6 rounded-xl text-center transition-colors block">
                <div class="text-3xl mb-2">✏️</div>
                <h4 class="font-medium">Modifier Profil</h4>
                <p class="text-blue-200 text-sm">Informations personnelles</p>
            </a>
        @endcan

        <a href="{{ route('admin.ceintures.create', ['user_id' => $user->id]) }}" 
           class="bg-orange-600 hover:bg-orange-700 text-white p-6 rounded-xl text-center transition-colors block">
            <div class="text-3xl mb-2">🥋</div>
            <h4 class="font-medium">Attribuer Ceinture</h4>
            <p class="text-orange-200 text-sm">Gestion des grades</p>
        </a>

        <div class="bg-red-600 hover:bg-red-700 text-white p-6 rounded-xl text-center transition-colors cursor-pointer">
            <div class="text-3xl mb-2">🎯</div>
            <h4 class="font-medium">Ajouter Séminaire</h4>
            <p class="text-red-200 text-sm">Inscrire à un séminaire</p>
        </div>

        <div class="bg-gray-600 hover:bg-gray-700 text-white p-6 rounded-xl text-center transition-colors cursor-pointer">
            <div class="text-3xl mb-2">📊</div>
            <h4 class="font-medium">Présences</h4>
            <p class="text-gray-300 text-sm">{{ $user->presences->count() }} présences</p>
        </div>
    </div>
</div>
@endsection
