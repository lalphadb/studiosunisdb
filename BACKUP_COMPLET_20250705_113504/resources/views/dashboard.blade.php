@extends('layouts.membre')

@section('title', 'Mon Espace Membre')

@section('content')
<div class="p-6 space-y-6">
    <!-- Header Membre avec bonnes couleurs -->
    <div class="bg-gradient-to-r from-blue-500 to-cyan-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">🥋 Bienvenue {{ auth()->user()->name }}</h1>
                <p class="text-blue-100">{{ auth()->user()->ecole->nom ?? 'Votre école de karaté' }}</p>
            </div>
            <div class="text-right">
                <div class="bg-blue-500 bg-opacity-50 px-4 py-2 rounded-lg">
                    <div class="text-sm text-blue-100">👤 Membre</div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 1: MA CEINTURE ACTUELLE & PROGRESSION -->
    <div class="bg-slate-800 rounded-xl p-6 border border-slate-700">
        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
            🥋 <span class="ml-2">Ma Ceinture Actuelle & Progression</span>
        </h3>
        
        @php
            $derniereCeinture = auth()->user()->userCeintures()
                ->with('ceinture')
                ->where('valide', true)
                ->latest('date_obtention')
                ->first();
            
            // Prochaine ceinture
            $prochaineCeinture = null;
            if($derniereCeinture) {
                $prochaineCeinture = App\Models\Ceinture::where('ordre', '>', $derniereCeinture->ceinture->ordre)
                    ->orderBy('ordre', 'asc')
                    ->first();
            } else {
                $prochaineCeinture = App\Models\Ceinture::where('ordre', 2)->first(); // Ceinture jaune
            }
        @endphp
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Ceinture Actuelle -->
            <div class="bg-gradient-to-br from-orange-500/20 to-red-500/20 border border-orange-500/30 p-6 rounded-lg">
                <div class="text-center">
                    @if($derniereCeinture)
                    <div class="w-16 h-16 rounded-full mx-auto mb-4 border-4 border-white shadow-lg" 
                         style="background-color: {{ $derniereCeinture->ceinture->couleur }}"></div>
                    <div class="font-bold text-xl text-white">
                        {{ $derniereCeinture->ceinture->nom }}
                    </div>
                    <div class="text-sm text-gray-300 mt-2">
                        📅 Obtenue le {{ $derniereCeinture->date_obtention->format('d/m/Y') }}
                    </div>
                    @if($derniereCeinture->examinateur)
                    <div class="text-xs text-gray-400 mt-1">
                        👨‍🏫 {{ $derniereCeinture->examinateur }}
                    </div>
                    @endif
                    @else
                    <div class="w-16 h-16 bg-white rounded-full mx-auto mb-4 border-4 border-gray-300 shadow-lg"></div>
                    <div class="font-bold text-xl text-white">Ceinture Blanche</div>
                    <div class="text-sm text-gray-300 mt-2">Débutant - Bienvenue!</div>
                    @endif
                </div>
            </div>
            
            <!-- Prochaine Ceinture -->
            @if($prochaineCeinture)
            <div class="bg-gradient-to-br from-green-500/20 to-emerald-500/20 border border-green-500/30 p-6 rounded-lg">
                <div class="text-center">
                    <div class="text-sm text-gray-300 mb-3">🎯 Prochaine ceinture</div>
                    <div class="w-16 h-16 rounded-full mx-auto mb-4 border-4 border-white shadow-lg" 
                         style="background-color: {{ $prochaineCeinture->couleur }}"></div>
                    <div class="font-bold text-lg text-white">
                        {{ $prochaineCeinture->nom }}
                    </div>
                    <div class="text-xs text-gray-400 mt-2">
                        Objectif à atteindre
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Temps Pratique -->
            <div class="bg-gradient-to-br from-blue-500/20 to-cyan-500/20 border border-blue-500/30 p-6 rounded-lg">
                <div class="text-center">
                    <div class="text-sm text-gray-300 mb-3">⏱️ Expérience</div>
                    <div class="text-4xl font-bold text-white">
                        {{ auth()->user()->created_at->diffInMonths(now()) }}
                    </div>
                    <div class="text-sm text-gray-300">Mois de pratique</div>
                    <div class="text-xs text-gray-400 mt-2">
                        Depuis le {{ auth()->user()->created_at->format('d/m/Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 2: MES COURS ET PLAGES HORAIRES -->
    <div class="bg-slate-800 rounded-xl p-6 border border-slate-700">
        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
            📚 <span class="ml-2">Mes Cours et Plages Horaires</span>
        </h3>
        
        @php
            $mesCours = auth()->user()->inscriptionsCours()->with(['cours'])->get();
            $sessionsOuvertes = App\Models\SessionCours::where('ecole_id', auth()->user()->ecole_id)
                ->where('inscriptions_ouvertes', true)
                ->where('actif', true)
                ->get();
        @endphp
        
        @if($mesCours->count() > 0)
            <div class="mb-6">
                <h4 class="font-medium text-white mb-3">📅 Mes cours actuels</h4>
                <div class="space-y-4">
                    @foreach($mesCours as $inscription)
                    <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="font-bold text-lg text-white">
                                    {{ $inscription->cours->nom }}
                                </div>
                                <div class="text-sm text-gray-300">
                                    {{ $inscription->cours->description }}
                                </div>
                                <div class="text-sm text-gray-300">
                                    📈 Niveau: {{ $inscription->cours->niveau ?? 'Tous niveaux' }}
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-500/20 text-blue-300 border border-blue-500/30">
                                    Inscrit le {{ $inscription->created_at->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Sessions ouvertes pour inscription -->
        @if($sessionsOuvertes->count() > 0)
        <div class="mb-6">
            <h4 class="font-medium text-white mb-3">🆕 Inscriptions ouvertes - Choisir vos plages horaires</h4>
            <div class="bg-green-500/10 border border-green-500/30 rounded-lg p-4">
                <div class="text-sm text-green-300 mb-3">
                    ✅ Les inscriptions sont ouvertes! Vous pouvez choisir vos créneaux horaires.
                </div>
                @foreach($sessionsOuvertes as $session)
                <div class="mb-4 last:mb-0 p-3 bg-slate-700 rounded border border-slate-600">
                    <div class="font-medium text-white">
                        📚 {{ $session->nom }}
                    </div>
                    <div class="text-sm text-gray-300">
                        📅 Du {{ \Carbon\Carbon::parse($session->date_debut)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($session->date_fin)->format('d/m/Y') }}
                    </div>
                    <button class="mt-2 px-4 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition-colors">
                        🕐 Voir les créneaux horaires disponibles
                    </button>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($mesCours->count() == 0 && $sessionsOuvertes->count() == 0)
            <div class="text-center py-8 text-gray-400">
                <div class="text-4xl mb-2">📝</div>
                <p>Vous n'êtes inscrit à aucun cours pour le moment.</p>
                <p class="text-sm">Les inscriptions ne sont pas encore ouvertes ou contactez votre école.</p>
            </div>
        @endif
    </div>

    <!-- SECTION 3: MES SÉMINAIRES -->
    <div class="bg-slate-800 rounded-xl p-6 border border-slate-700">
        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
            🎯 <span class="ml-2">Mes Séminaires et Progression</span>
        </h3>
        
        @php
            $mesSeminaires = auth()->user()->inscriptionsSeminaires()->with('seminaire')->get();
            $seminairesDisponibles = App\Models\Seminaire::where('ecole_id', auth()->user()->ecole_id)
                ->where('date_debut', '>', now())
                ->get();
        @endphp
        
        @if($mesSeminaires->count() > 0 || $seminairesDisponibles->count() > 0)
            <!-- Mes séminaires inscrits -->
            @if($mesSeminaires->count() > 0)
            <div class="mb-6">
                <h4 class="font-medium text-white mb-3">📋 Mes séminaires inscrits</h4>
                <div class="space-y-3">
                    @foreach($mesSeminaires as $inscription)
                    <div class="bg-purple-500/10 border border-purple-500/30 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="font-bold text-lg text-white">
                                    {{ $inscription->seminaire->nom }}
                                </div>
                                <div class="text-sm text-gray-300">
                                    📅 {{ \Carbon\Carbon::parse($inscription->seminaire->date_debut)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($inscription->seminaire->date_fin)->format('d/m/Y') }}
                                </div>
                                <div class="text-sm text-gray-300">
                                    📍 {{ $inscription->seminaire->lieu ?? 'Lieu à confirmer' }}
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-500/20 text-purple-300 border border-purple-500/30">
                                ✅ Inscrit
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Séminaires disponibles -->
            @if($seminairesDisponibles->count() > 0)
            <div class="mb-6">
                <h4 class="font-medium text-white mb-3">🆕 Séminaires disponibles</h4>
                <div class="space-y-3">
                    @foreach($seminairesDisponibles as $seminaire)
                    <div class="bg-orange-500/10 border border-orange-500/30 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="font-bold text-lg text-white">
                                    {{ $seminaire->nom }}
                                </div>
                                <div class="text-sm text-gray-300">
                                    📅 {{ \Carbon\Carbon::parse($seminaire->date_debut)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($seminaire->date_fin)->format('d/m/Y') }}
                                </div>
                                <div class="text-sm text-gray-300">
                                    💰 Prix: {{ $seminaire->prix ?? 'Gratuit' }}
                                </div>
                            </div>
                            <button class="px-4 py-2 bg-orange-600 text-white text-sm rounded hover:bg-orange-700 transition-colors">
                                📝 S'inscrire
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        @else
            <div class="text-center py-8 text-gray-400">
                <div class="text-4xl mb-2">🎯</div>
                <p>Aucun séminaire disponible pour le moment.</p>
                <p class="text-sm">Consultez régulièrement pour les nouveaux séminaires.</p>
            </div>
        @endif
    </div>

    <!-- SECTION 4: ACTIONS RAPIDES - CORRECTION ICI -->
    <div class="bg-slate-800 rounded-xl p-6 border border-slate-700">
        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
            🚀 <span class="ml-2">Actions Rapides</span>
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Changer mot de passe - LIEN CORRIGÉ -->
            <a href="{{ route('membre.profil') }}" class="block p-4 bg-blue-500/10 border border-blue-500/30 rounded-lg hover:bg-blue-500/20 transition-colors">
                <div class="flex items-center">
                    <div class="text-blue-400 mr-3 text-2xl">🔐</div>
                    <div>
                        <h4 class="font-medium text-white">Changer mot de passe</h4>
                        <p class="text-sm text-gray-300">Sécurité de votre compte</p>
                    </div>
                </div>
            </a>

            <!-- Mon profil - LIEN CORRIGÉ -->
            <a href="{{ route('membre.profil') }}" class="block p-4 bg-green-500/10 border border-green-500/30 rounded-lg hover:bg-green-500/20 transition-colors">
                <div class="flex items-center">
                    <div class="text-green-400 mr-3 text-2xl">👤</div>
                    <div>
                        <h4 class="font-medium text-white">Mon profil</h4>
                        <p class="text-sm text-gray-300">Modifier mes informations</p>
                    </div>
                </div>
            </a>

            @can('view-admin')
            <!-- Administration -->
            <a href="/admin" class="block p-4 bg-purple-500/10 border border-purple-500/30 rounded-lg hover:bg-purple-500/20 transition-colors">
                <div class="flex items-center">
                    <div class="text-purple-400 mr-3 text-2xl">⚙️</div>
                    <div>
                        <h4 class="font-medium text-white">Administration</h4>
                        <p class="text-sm text-gray-300">Gérer l'école</p>
                    </div>
                </div>
            </a>
            @endcan
        </div>
    </div>

    <!-- SECTION FAMILLE -->
    @if(auth()->user()->famillePrincipale || auth()->user()->membresFamille->count() > 0)
    <div class="bg-slate-800 rounded-xl p-6 border border-slate-700">
        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
            👨‍👩‍👧‍👦 <span class="ml-2">Ma Famille</span>
        </h3>
        
        @if(auth()->user()->estChefFamille())
            <div class="mb-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-500/20 text-yellow-300 border border-yellow-500/30">
                    👑 Chef de famille
                </span>
            </div>
            
            <div class="space-y-3">
                @foreach(auth()->user()->membresFamille as $membre)
                <div class="flex items-center justify-between p-3 bg-slate-700 rounded">
                    <div>
                        <div class="font-medium text-white">{{ $membre->name }}</div>
                        <div class="text-sm text-gray-300">{{ $membre->date_naissance ? $membre->date_naissance->age . ' ans' : 'Age non renseigné' }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-blue-400">{{ $membre->inscriptionsCours->count() }} cours</div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="p-4 bg-blue-500/10 border border-blue-500/30 rounded-lg">
                <div class="text-blue-300 mb-2">👑 Chef de famille:</div>
                <div class="font-medium text-white">{{ auth()->user()->famillePrincipale->name }}</div>
                <div class="text-sm text-gray-300">{{ auth()->user()->famillePrincipale->email }}</div>
            </div>
            
            @if(auth()->user()->famillePrincipale->membresFamille->count() > 1)
            <div class="mt-4">
                <div class="text-sm text-slate-400 mb-2">Autres membres de la famille:</div>
                <div class="space-y-2">
                    @foreach(auth()->user()->famillePrincipale->membresFamille as $membre)
                        @if($membre->id !== auth()->user()->id)
                        <div class="flex items-center justify-between p-2 bg-slate-700 rounded text-sm">
                            <span class="text-white">{{ $membre->name }}</span>
                            <span class="text-gray-400">{{ $membre->date_naissance ? $membre->date_naissance->age . ' ans' : '' }}</span>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif
        @endif
    </div>
    @endif
    
    <!-- SECTION 5: STATISTIQUES PERSONNELLES -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Cours inscrits -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-lg text-white text-center">
            <div class="text-3xl font-bold">{{ auth()->user()->inscriptionsCours()->count() }}</div>
            <div class="text-sm text-blue-100 mt-1">Cours inscrits</div>
            <div class="text-xs text-blue-200 mt-2">📚 Formations</div>
        </div>

        <!-- Séminaires -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-6 rounded-lg text-white text-center">
            <div class="text-3xl font-bold">{{ auth()->user()->inscriptionsSeminaires()->count() }}</div>
            <div class="text-sm text-purple-100 mt-1">Séminaires suivis</div>
            <div class="text-xs text-purple-200 mt-2">🎯 Spécialisations</div>
        </div>

        <!-- Présences -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 p-6 rounded-lg text-white text-center">
            <div class="text-3xl font-bold">{{ auth()->user()->presences()->count() }}</div>
            <div class="text-sm text-green-100 mt-1">Présences</div>
            <div class="text-xs text-green-200 mt-2">✅ Assiduité</div>
        </div>

        <!-- Ceintures obtenues -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-6 rounded-lg text-white text-center">
            <div class="text-3xl font-bold">{{ auth()->user()->userCeintures()->count() }}</div>
            <div class="text-sm text-orange-100 mt-1">Ceintures obtenues</div>
            <div class="text-xs text-orange-200 mt-2">🥋 Progression</div>
        </div>
    </div>
</div>
@endsection
