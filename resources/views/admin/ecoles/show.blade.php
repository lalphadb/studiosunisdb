@extends('layouts.admin')

@section('title', 'Détail École')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h1 class="text-3xl font-bold text-white">
                    🏢 {{ $ecole->nom }}
                </h1>
                <p class="mt-1 text-sm text-gray-300">
                    Détails de l'école - {{ $ecole->ville }}, {{ $ecole->province }}
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                <a href="{{ route('admin.ecoles.edit', $ecole) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                    ✏️ Modifier
                </a>
                <a href="{{ route('admin.ecoles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
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
            <div class="lg:col-span-2">
                <div class="bg-gray-800 rounded-lg shadow border border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-700">
                        <h3 class="text-lg font-medium text-white">Informations de l'École</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Nom et Statut -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Nom de l'école</label>
                                <p class="mt-1 text-sm text-white">{{ $ecole->nom }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Statut</label>
                                <p class="mt-1">
                                    @if($ecole->statut === 'actif')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">✅ Actif</span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">❌ Inactif</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Adresse -->
                        @if($ecole->adresse)
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Adresse</label>
                            <p class="mt-1 text-sm text-white">{{ $ecole->adresse }}</p>
                        </div>
                        @endif

                        <!-- Ville et Province -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Ville</label>
                                <p class="mt-1 text-sm text-white">{{ $ecole->ville ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Province</label>
                                <p class="mt-1 text-sm text-white">{{ $ecole->province ?? 'Québec' }}</p>
                            </div>
                        </div>

                        <!-- Contact -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if($ecole->telephone)
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Téléphone</label>
                                <p class="mt-1 text-sm text-white">{{ $ecole->telephone }}</p>
                            </div>
                            @endif
                            @if($ecole->email)
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Email</label>
                                <p class="mt-1 text-sm text-white">{{ $ecole->email }}</p>
                            </div>
                            @endif
                        </div>

                        <!-- Directeur et Capacité -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if($ecole->directeur)
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Directeur</label>
                                <p class="mt-1 text-sm text-white">{{ $ecole->directeur }}</p>
                            </div>
                            @endif
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Capacité maximale</label>
                                <p class="mt-1 text-sm text-white">{{ $ecole->capacite_max ?? 100 }} membres</p>
                            </div>
                        </div>

                        <!-- Site web -->
                        @if($ecole->site_web)
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Site web</label>
                            <p class="mt-1 text-sm">
                                <a href="{{ $ecole->site_web }}" target="_blank" class="text-blue-400 hover:text-blue-300">
                                    {{ $ecole->site_web }}
                                </a>
                            </p>
                        </div>
                        @endif

                        <!-- Description -->
                        @if($ecole->description)
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Description</label>
                            <p class="mt-1 text-sm text-white">{{ $ecole->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="space-y-6">
                <!-- Membres Actifs -->
                <div class="bg-gray-800 rounded-lg shadow border border-gray-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="text-2xl">👥</div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-300">Membres Actifs</p>
                            <p class="text-2xl font-bold text-white">{{ $ecole->membres()->where('statut', 'actif')->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Cours Actifs -->
                <div class="bg-gray-800 rounded-lg shadow border border-gray-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="text-2xl">📚</div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-300">Cours Actifs</p>
                            <p class="text-2xl font-bold text-white">{{ $ecole->cours()->where('statut', 'actif')->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Capacité Utilisée -->
                <div class="bg-gray-800 rounded-lg shadow border border-gray-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="text-2xl">📊</div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-300">Capacité Utilisée</p>
                            @php
                                $total_membres = $ecole->membres()->count();
                                $capacite = $ecole->capacite_max ?? 100;
                                $pourcentage = $capacite > 0 ? round(($total_membres / $capacite) * 100) : 0;
                            @endphp
                            <p class="text-2xl font-bold text-white">{{ $pourcentage }}%</p>
                            <div class="w-full bg-gray-700 rounded-full h-2 mt-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min($pourcentage, 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="bg-gray-800 rounded-lg shadow border border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-white mb-4">Actions Rapides</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.membres.create') }}?ecole_id={{ $ecole->id }}" class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                            👤 Nouveau Membre
                        </a>
                        <a href="{{ route('admin.cours.create') }}?ecole_id={{ $ecole->id }}" class="block w-full text-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                            📚 Nouveau Cours
                        </a>
                        <a href="{{ route('admin.membres.index') }}?ecole_id={{ $ecole->id }}" class="block w-full text-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">
                            📋 Voir Membres
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations système -->
        <div class="mt-8 bg-gray-800 rounded-lg shadow border border-gray-700 p-6">
            <h3 class="text-lg font-medium text-white mb-4">📋 Informations Système</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="text-gray-400">Créée le:</span>
                    <span class="text-white ml-2">{{ $ecole->created_at ? $ecole->created_at->format('d/m/Y à H:i') : 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-gray-400">Dernière modification:</span>
                    <span class="text-white ml-2">{{ $ecole->updated_at ? $ecole->updated_at->format('d/m/Y à H:i') : 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-gray-400">ID École:</span>
                    <span class="text-white ml-2">#{{ $ecole->id }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
