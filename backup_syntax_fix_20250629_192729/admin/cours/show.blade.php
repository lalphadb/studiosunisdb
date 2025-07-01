@extends('layouts.admin')
@section('title', 'Détails du cours')

@section('content')
<div class="space-y-6">
    <!-- Header avec gradient PURPLE pour cours (comme User/Ceinture) -->
    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-6">
                <!-- Icône cours (comme avatar User ou badge Ceinture) -->
                <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center">
                    <span class="text-2xl font-bold text-white">📚</span>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">{{ $cours->nom }}</h1>
                    <p class="text-purple-100 text-lg">{{ $cours->ecole->nom }}</p>
                    <!-- Badges (comme User) -->
                    <div class="flex space-x-2 mt-3">
                        @if($cours->niveau)
                            <span class="px-3 py-1 text-sm font-medium rounded-md bg-purple-800 text-purple-100">
                                {{ ucfirst($cours->niveau) }}
                            </span>
                        @endif
                        @if($cours->active)
                            <span class="px-3 py-1 text-sm font-medium rounded-md bg-green-600 text-green-100">
                                Actif
                            </span>
                        @else
                            <span class="px-3 py-1 text-sm font-medium rounded-md bg-red-600 text-red-100">
                                Inactif
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Boutons d'action (comme User/Ceinture) -->
            <div class="flex space-x-3">
                @can('update', $cours)
                <a href="{{ route('admin.cours.edit', $cours->id) }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                    Modifier
                </a>
                @endcan
                <a href="{{ route('admin.cours.index') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                    Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Grid Layout (exactement comme User/Ceinture) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne gauche - Informations principales (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations du Cours -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
                <div class="bg-purple-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">Informations</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Colonne 1 -->
                        <div class="space-y-4">
                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Nom du cours</div>
                                <div class="text-white font-medium">{{ $cours->nom }}</div>
                            </div>

                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">École</div>
                                <div class="text-white">{{ $cours->ecole->nom }}</div>
                            </div>
                            
                            @if($cours->instructeur)
                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Instructeur</div>
                                <div class="text-white">{{ $cours->instructeur }}</div>
                            </div>
                            @endif

                            @if($cours->niveau)
                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Niveau</div>
                                <div class="text-white">{{ ucfirst($cours->niveau) }}</div>
                            </div>
                            @endif
                        </div>

                        <!-- Colonne 2 -->
                        <div class="space-y-4">
                            @if($cours->prix)
                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Prix</div>
                                <div class="text-white">{{ number_format($cours->prix, 2) }} $</div>
                            </div>
                            @endif

                            @if($cours->capacite_max)
                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Capacité maximale</div>
                                <div class="text-white">{{ $cours->capacite_max }} participants</div>
                            </div>
                            @endif

                            @if($cours->duree_minutes)
                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Durée</div>
                                <div class="text-white">{{ $cours->duree_minutes }} minutes</div>
                            </div>
                            @endif

                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Statut</div>
                                <div class="text-white">{{ $cours->active ? 'Actif' : 'Inactif' }}</div>
                            </div>
                        </div>
                    </div>

                    @if($cours->description)
                    <div class="mt-6 pt-6 border-t border-slate-700">
                        <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Description</div>
                        <div class="text-white">{{ $cours->description }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne droite - Sidebar (1/3) exactement comme User/Ceinture -->
        <div class="space-y-6">
            <!-- École -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
                <div class="bg-green-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3z"/>
                        </svg>
                        École
                    </h3>
                </div>
                <div class="p-6 text-center">
                    <div class="w-16 h-16 bg-green-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-white font-bold">
                            {{ $cours->ecole->code ?? strtoupper(substr($cours->ecole->nom, 0, 3)) }}
                        </span>
                    </div>
                    <h4 class="text-white font-medium">{{ $cours->ecole->nom }}</h4>
                    <p class="text-slate-400">{{ $cours->ecole->ville ?? 'Localisation non définie' }}</p>
                    @can('view', $cours->ecole)
                        <a href="{{ route('admin.ecoles.show', $cours->ecole) }}" 
                           class="text-green-400 hover:text-green-300 mt-2 inline-block text-sm">
                            Voir détails de l'école →
                        </a>
                    @endcan
                </div>
            </div>

            <!-- Statistiques -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
                <div class="bg-blue-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">📊 Statistiques</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white">0</div>
                        <div class="text-sm text-slate-400">Inscriptions</div>
                    </div>
                    
                    <div class="border-t border-slate-700 pt-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400">Actives:</span>
                            <span class="text-green-400">0</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400">En attente:</span>
                            <span class="text-yellow-400">0</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides (comme Ceinture) -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
                <div class="bg-purple-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">⚡ Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    @can('update', $cours)
                    <a href="{{ route('admin.cours.edit', $cours->id) }}" 
                       class="w-full bg-yellow-600 hover:bg-yellow-700 text-white p-3 rounded-lg text-center block transition-colors">
                        ✏️ Modifier ce cours
                    </a>
                    @endcan
                    
                    @can('create', App\Models\Cours::class)
                    <a href="{{ route('admin.cours.clone.form', $cours->id) }}" 
                       class="w-full bg-indigo-600 hover:bg-indigo-700 text-white p-3 rounded-lg text-center block transition-colors">
                        📋 Dupliquer ce cours
                    </a>
                    @endcan
                    
                    <a href="{{ route('admin.cours.index') }}" 
                       class="w-full bg-gray-600 hover:bg-gray-700 text-white p-3 rounded-lg text-center block transition-colors">
                        📋 Voir tous les cours
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
