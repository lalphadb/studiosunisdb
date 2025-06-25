@extends('layouts.admin')
@section('title', 'Détails du Cours')

@section('content')
<div class="space-y-6">
    <!-- Header avec couleur violette -->
    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">{{ $cours->nom }}</h1>
                <p class="text-purple-100 text-lg">{{ $cours->ecole->nom ?? 'École non assignée' }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.cours.edit', $cours) }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Modifier
                </a>
                <a href="{{ route('admin.cours.index') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations du cours -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
                <div class="bg-purple-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Informations du Cours
                    </h3>
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
                                <div class="text-white">{{ $cours->ecole->nom ?? 'Non assignée' }}</div>
                                @if($cours->ecole)
                                    <div class="text-slate-400 text-sm">{{ $cours->ecole->ville ?? '' }}</div>
                                @endif
                            </div>
                            
                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Niveau</div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $cours->niveau === 'debutant' ? 'bg-green-900 text-green-300' : 
                                       ($cours->niveau === 'intermediaire' ? 'bg-yellow-900 text-yellow-300' : 
                                       ($cours->niveau === 'avance' ? 'bg-red-900 text-red-300' : 'bg-blue-900 text-blue-300')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $cours->niveau)) }}
                                </span>
                            </div>

                            @if($cours->instructeur)
                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Instructeur</div>
                                <div class="text-white">{{ $cours->instructeur }}</div>
                            </div>
                            @endif
                        </div>

                        <!-- Colonne 2 -->
                        <div class="space-y-4">
                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Capacité</div>
                                <div class="text-white">{{ $cours->inscriptions->count() ?? 0 }} / {{ $cours->capacite_max }} personnes</div>
                                <div class="w-full bg-slate-600 rounded-full h-2 mt-2">
                                    <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $cours->capacite_max > 0 ? (($cours->inscriptions->count() ?? 0) / $cours->capacite_max) * 100 : 0 }}%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Durée</div>
                                <div class="text-white">{{ $cours->duree_minutes }} minutes</div>
                            </div>

                            @if($cours->prix)
                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Prix</div>
                                <div class="text-white">${{ $cours->prix }} CAD</div>
                            </div>
                            @endif

                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Statut</div>
                                @if($cours->active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-300">
                                        ✓ Actif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900 text-red-300">
                                        ✗ Inactif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($cours->description)
                    <div class="mt-6 pt-6 border-t border-slate-700">
                        <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Description</div>
                        <p class="text-white">{{ $cours->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Horaires -->
            @if($cours->horaires->count() > 0)
            <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
                <div class="bg-indigo-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Horaires du Cours
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($cours->horaires as $horaire)
                        <div class="flex items-center justify-between p-4 bg-slate-700 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-white font-medium">{{ $horaire->jour_francais }}</div>
                                    <div class="text-slate-300 text-sm">{{ $horaire->heure_debut }} - {{ $horaire->heure_fin }}</div>
                                </div>
                            </div>
                            @if($horaire->active)
                                <span class="px-2 py-1 bg-green-600 text-green-100 rounded text-xs">Actif</span>
                            @else
                                <span class="px-2 py-1 bg-gray-600 text-gray-300 rounded text-xs">Inactif</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Inscriptions -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
                <div class="bg-green-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Inscriptions
                    </h3>
                </div>
                <div class="p-6">
                    @if($cours->inscriptions && $cours->inscriptions->count() > 0)
                        <div class="space-y-3">
                            @foreach($cours->inscriptions->take(5) as $inscription)
                            <div class="flex items-center justify-between py-2 border-b border-slate-700 last:border-b-0">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-white font-bold text-xs">{{ substr($inscription->user->name ?? 'U', 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="text-white text-sm font-medium">{{ $inscription->user->name ?? 'Utilisateur inconnu' }}</div>
                                        <div class="text-slate-400 text-xs">{{ $inscription->statut ?? 'inscrit' }}</div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            
                            @if($cours->inscriptions->count() > 5)
                            <p class="text-slate-400 text-sm text-center pt-2">
                                ... et {{ $cours->inscriptions->count() - 5 }} autres inscriptions
                            </p>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-slate-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <h3 class="text-sm font-medium text-slate-300">Aucune inscription</h3>
                            <p class="text-xs text-slate-500">Ce cours n'a pas encore d'inscriptions.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
                <div class="bg-slate-700 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">Actions Rapides</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.cours.edit', $cours) }}" 
                       class="w-full bg-purple-600 hover:bg-purple-700 text-white p-3 rounded-lg text-center transition-colors block">
                        ✏️ Modifier le Cours
                    </a>
                    
                    <button class="w-full bg-green-600 hover:bg-green-700 text-white p-3 rounded-lg text-center transition-colors">
                        👥 Gérer les Inscriptions
                    </button>
                    
                    <button class="w-full bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-lg text-center transition-colors">
                        📊 Voir les Présences
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Métadonnées -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <h3 class="text-lg font-medium text-white mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Métadonnées
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <span class="text-slate-400">Créé le:</span>
                <span class="text-white ml-2">{{ $cours->created_at->format('d/m/Y à H:i') }}</span>
            </div>
            <div>
                <span class="text-slate-400">Dernière modification:</span>
                <span class="text-white ml-2">{{ $cours->updated_at->format('d/m/Y à H:i') }}</span>
            </div>
            <div>
                <span class="text-slate-400">ID:</span>
                <span class="text-white ml-2">#{{ $cours->id }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
