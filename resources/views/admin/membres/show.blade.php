@extends('layouts.admin')

@section('title', 'Profil Membre')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h1 class="text-3xl font-bold text-white">
                    👤 {{ $membre->prenom }} {{ $membre->nom }}
                </h1>
                <p class="mt-1 text-sm text-gray-300">
                    Profil membre - {{ $membre->ecole->nom ?? 'École non assignée' }}
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                <a href="{{ route('admin.membres.edit', $membre) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                    ✏️ Modifier
                </a>
                <a href="{{ route('admin.membres.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                    ← Retour Liste
                </a>
            </div>
        </div>

        <!-- Messages de succès -->
        @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations principales -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Carte membre -->
                <div class="bg-gray-800 rounded-lg shadow border border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-700">
                        <h3 class="text-lg font-medium text-white">Informations Personnelles</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center mb-6">
                            <div class="w-16 h-16 bg-gray-600 rounded-full flex items-center justify-center">
                                <span class="text-xl font-medium text-white">
                                    {{ strtoupper(substr($membre->prenom, 0, 1)) }}{{ strtoupper(substr($membre->nom, 0, 1)) }}
                                </span>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-bold text-white">{{ $membre->prenom }} {{ $membre->nom }}</h3>
                                <p class="text-gray-400">
                                    @switch($membre->statut)
                                        @case('actif')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">✅ Membre Actif</span>
                                            @break
                                        @case('inactif')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">❌ Inactif</span>
                                            @break
                                        @case('suspendu')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">⏸️ Suspendu</span>
                                            @break
                                    @endswitch
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if($membre->date_naissance)
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Date de naissance</label>
                                <p class="mt-1 text-sm text-white">{{ $membre->date_naissance->format('d/m/Y') }} ({{ $membre->date_naissance->age }} ans)</p>
                            </div>
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-300">École</label>
                                <p class="mt-1 text-sm text-white">{{ $membre->ecole->nom ?? 'Non assignée' }}</p>
                                @if($membre->ecole)
                                    <p class="text-sm text-gray-400">{{ $membre->ecole->ville }}, {{ $membre->ecole->province }}</p>
                                @endif
                            </div>

                            @if($membre->telephone)
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Téléphone</label>
                                <p class="mt-1 text-sm text-white">{{ $membre->telephone }}</p>
                            </div>
                            @endif

                            @if($membre->email)
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Email</label>
                                <p class="mt-1 text-sm text-white">{{ $membre->email }}</p>
                            </div>
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Date d'inscription</label>
                                <p class="mt-1 text-sm text-white">{{ $membre->date_inscription ? $membre->date_inscription->format('d/m/Y') : 'N/A' }}</p>
                                @if($membre->date_inscription)
                                    <p class="text-sm text-gray-400">{{ $membre->date_inscription->diffForHumans() }}</p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Ceinture actuelle</label>
                                <p class="mt-1">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-white text-gray-800 border">
                                        🥋 Ceinture Blanche
                                    </span>
                                </p>
                            </div>
                        </div>

                        @if($membre->adresse)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-300">Adresse</label>
                            <p class="mt-1 text-sm text-white">{{ $membre->adresse }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Contact d'urgence -->
                @if($membre->contact_urgence || $membre->telephone_urgence)
                <div class="bg-gray-800 rounded-lg shadow border border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-700">
                        <h3 class="text-lg font-medium text-white">🚨 Contact d'Urgence</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if($membre->contact_urgence)
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Nom du contact</label>
                                <p class="mt-1 text-sm text-white">{{ $membre->contact_urgence }}</p>
                            </div>
                            @endif
                            @if($membre->telephone_urgence)
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Téléphone</label>
                                <p class="mt-1 text-sm text-white">{{ $membre->telephone_urgence }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Notes -->
                @if($membre->notes)
                <div class="bg-gray-800 rounded-lg shadow border border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-700">
                        <h3 class="text-lg font-medium text-white">📝 Notes Internes</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-white">{{ $membre->notes }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Statistiques et actions -->
            <div class="space-y-6">
                
                <!-- Activité -->
                <div class="bg-gray-800 rounded-lg shadow border border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-white mb-4">📊 Activité</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Cours inscrits</span>
                            <span class="text-white font-semibold">0</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Présences ce mois</span>
                            <span class="text-white font-semibold">0</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Taux présence</span>
                            <span class="text-green-400 font-semibold">N/A</span>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="bg-gray-800 rounded-lg shadow border border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-white mb-4">⚡ Actions Rapides</h3>
                    <div class="space-y-3">
                        <button class="block w-full text-center px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition">
                            🥋 Attribuer Ceinture (bientôt)
                        </button>
                        <button class="block w-full text-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                            🎓 Inscrire Séminaire (bientôt)
                        </button>
                        <button class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                            ✅ Prendre Présence (bientôt)
                        </button>
                        <button class="block w-full text-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                            📚 Inscrire à un Cours (bientôt)
                        </button>
                        <a href="{{ route('admin.membres.edit', $membre) }}" 
                           class="block w-full text-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">
                            ✏️ Modifier Profil
                        </a>
                    </div>
                </div>

                <!-- Historique -->
                <div class="bg-gray-800 rounded-lg shadow border border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-white mb-4">📅 Historique</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Création profil</span>
                            <span class="text-white">{{ $membre->created_at ? $membre->created_at->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Dernière modification</span>
                            <span class="text-white">{{ $membre->updated_at ? $membre->updated_at->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Ancienneté</span>
                            <span class="text-white">{{ $membre->date_inscription ? $membre->date_inscription->diffForHumans() : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
