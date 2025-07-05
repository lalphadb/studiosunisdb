@extends('layouts.admin')

@section('title', 'Profil de ' . $user->name)

@section('content')
<div class="flex-1 flex flex-col overflow-hidden">
    <!-- Header avec module-header -->
    <x-module-header 
        module="users" 
        title="Profil de {{ $user->name }}" 
        subtitle="👤 Membre de {{ $user->ecole->nom ?? 'École non assignée' }}"
    />

    <div class="flex-1 overflow-x-hidden overflow-y-auto p-6 space-y-6">
        
        <!-- Informations personnelles -->
        <div class="studiosdb-card">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <x-admin-icon name="users" size="w-6 h-6" color="text-blue-400" />
                    <h2 class="text-xl font-bold text-white">Informations Personnelles</h2>
                </div>
                @can('update', $user)
                <a href="{{ route('admin.users.edit', $user) }}" 
                   class="studiosdb-btn studiosdb-btn-users">
                    ✏️ Modifier
                </a>
                @endcan
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Colonne gauche -->
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-slate-400">Nom complet</label>
                        <div class="text-white font-medium">{{ $user->name }}</div>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-slate-400">Email</label>
                        <div class="text-white">{{ $user->email }}</div>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-slate-400">Téléphone</label>
                        <div class="text-white">{{ $user->telephone ?? 'Non renseigné' }}</div>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-slate-400">Date de naissance</label>
                        <div class="text-white">
                            @if($user->date_naissance)
                                {{ $user->date_naissance->format('d/m/Y') }} 
                                ({{ $user->date_naissance->age }} ans)
                            @else
                                Non renseignée
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-slate-400">Sexe</label>
                        <div class="text-white">{{ $user->sexe ?? 'Non renseigné' }}</div>
                    </div>
                </div>
                
                <!-- Colonne droite -->
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-slate-400">École</label>
                        <div class="text-white font-medium">{{ $user->ecole->nom ?? 'Non assignée' }}</div>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-slate-400">Date d'inscription</label>
                        <div class="text-white">{{ $user->date_inscription ? \Carbon\Carbon::parse($user->date_inscription)->format('d/m/Y') : $user->created_at->format('d/m/Y') }}</div>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-slate-400">Statut</label>
                        <div>
                            @if($user->active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                    ✅ Actif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                    ❌ Inactif
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    @if($user->adresse)
                    <div>
                        <label class="text-sm font-medium text-slate-400">Adresse</label>
                        <div class="text-white">
                            {{ $user->adresse }}<br>
                            {{ $user->ville }} {{ $user->code_postal }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Contact d'urgence -->
            @if($user->contact_urgence_nom || $user->contact_urgence_telephone)
            <div class="mt-6 pt-6 border-t border-slate-700">
                <h3 class="text-lg font-medium text-white mb-4">🚨 Contact d'urgence</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($user->contact_urgence_nom)
                    <div>
                        <label class="text-sm font-medium text-slate-400">Nom</label>
                        <div class="text-white">{{ $user->contact_urgence_nom }}</div>
                    </div>
                    @endif
                    @if($user->contact_urgence_telephone)
                    <div>
                        <label class="text-sm font-medium text-slate-400">Téléphone</label>
                        <div class="text-white">{{ $user->contact_urgence_telephone }}</div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Ceinture actuelle et progression -->
        <div class="studiosdb-card">
            <div class="flex items-center space-x-3 mb-6">
                <x-admin-icon name="ceintures" size="w-6 h-6" color="text-orange-400" />
                <h2 class="text-xl font-bold text-white">Progression Martial</h2>
            </div>
            
            @php
                $ceintureActuelle = $user->userCeintures()
                    ->with('ceinture')
                    ->where('valide', true)
                    ->latest('date_obtention')
                    ->first();
            @endphp
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Ceinture actuelle -->
                <div class="bg-gradient-to-br from-orange-500/20 to-red-500/20 border border-orange-500/30 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-white mb-4">🥋 Ceinture actuelle</h3>
                    @if($ceintureActuelle)
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-full mr-4 border-2 border-white shadow-md" 
                                 style="background-color: {{ $ceintureActuelle->ceinture->couleur }}"></div>
                            <div>
                                <div class="font-bold text-lg text-white">
                                    {{ $ceintureActuelle->ceinture->nom }}
                                </div>
                                <div class="text-sm text-gray-300">
                                    Obtenue le {{ $ceintureActuelle->date_obtention->format('d/m/Y') }}
                                </div>
                                @if($ceintureActuelle->examinateur)
                                <div class="text-xs text-gray-400">
                                    Par {{ $ceintureActuelle->examinateur }}
                                </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-white rounded-full mr-4 border-2 border-gray-300 shadow-md"></div>
                            <div>
                                <div class="font-bold text-lg text-white">Ceinture Blanche</div>
                                <div class="text-sm text-gray-300">Débutant</div>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Statistiques -->
                <div class="bg-gradient-to-br from-blue-500/20 to-cyan-500/20 border border-blue-500/30 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-white mb-4">📊 Statistiques</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-300">Ceintures obtenues:</span>
                            <span class="text-white font-medium">{{ $user->userCeintures->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-300">Cours inscrits:</span>
                            <span class="text-white font-medium">{{ $user->inscriptionsCours->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-300">Présences:</span>
                            <span class="text-white font-medium">{{ $user->presences->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-300">Ancienneté:</span>
                            <span class="text-white font-medium">{{ $user->created_at->diffInMonths(now()) }} mois</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Famille / Membres liés -->
        @if($user->membresFamille->count() > 0)
        <div class="studiosdb-card">
            <div class="flex items-center space-x-3 mb-6">
                <x-admin-icon name="users" size="w-6 h-6" color="text-blue-400" />
                <h2 class="text-xl font-bold text-white">👨‍👩‍👧‍👦 Famille / Membres liés</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($user->membresFamille as $membre)
                <div class="p-4 bg-blue-500/10 border border-blue-500/30 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-medium text-white">{{ $membre->name }}</div>
                            <div class="text-sm text-gray-300">{{ $membre->email }}</div>
                            @if($membre->telephone)
                            <div class="text-sm text-gray-300">📞 {{ $membre->telephone }}</div>
                            @endif
                            @if($membre->date_naissance)
                            <div class="text-xs text-gray-400">{{ $membre->date_naissance->age }} ans</div>
                            @endif
                        </div>
                        <div class="text-right">
                            <a href="{{ route('admin.users.show', $membre) }}" 
                               class="text-blue-400 hover:text-blue-300 text-sm">
                                Voir profil →
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mt-4 p-3 bg-slate-700/50 rounded border border-slate-600">
                <div class="text-sm text-slate-400 mb-2">🏠 Lien familial détecté par:</div>
                @if($user->nom_famille)
                <div class="text-white">Nom de famille: <strong>{{ $user->nom_famille }}</strong></div>
                @else
                <div class="text-white">Adresse identique: <strong>{{ $user->adresse }}</strong></div>
                @endif
                
                @if($user->contact_principal_famille)
                <div class="text-sm text-slate-300 mt-2">
                    👤 Contact principal: <strong>{{ $user->contact_principal_famille }}</strong>
                    @if($user->telephone_principal_famille)
                        <span class="ml-2">📞 {{ $user->telephone_principal_famille }}</span>
                    @endif
                </div>
                @endif
            </div>
        </div>
        @endif
        
        <!-- Historique des ceintures -->
        @if($user->userCeintures->count() > 0)
        <div class="studiosdb-card">
            <div class="flex items-center space-x-3 mb-6">
                <x-admin-icon name="ceintures" size="w-6 h-6" color="text-orange-400" />
                <h2 class="text-xl font-bold text-white">Historique des Ceintures</h2>
            </div>
            
            <div class="space-y-4">
                @foreach($user->userCeintures()->with('ceinture')->orderBy('date_obtention', 'desc')->get() as $attribution)
                <div class="flex items-center justify-between p-4 bg-slate-700/50 rounded-lg border border-slate-600">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full mr-4 border-2 border-white shadow-md" 
                             style="background-color: {{ $attribution->ceinture->couleur }}"></div>
                        <div>
                            <div class="font-medium text-white">{{ $attribution->ceinture->nom }}</div>
                            <div class="text-sm text-gray-300">
                                Obtenue le {{ $attribution->date_obtention->format('d/m/Y') }}
                            </div>
                            @if($attribution->examinateur)
                            <div class="text-xs text-gray-400">Examinateur: {{ $attribution->examinateur }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        @if($attribution->valide)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                            ✅ Validée
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                            ⏳ En attente
                        </span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Cours actuels -->
        @if($user->inscriptionsCours->count() > 0)
        <div class="studiosdb-card">
            <div class="flex items-center space-x-3 mb-6">
                <x-admin-icon name="cours" size="w-6 h-6" color="text-violet-400" />
                <h2 class="text-xl font-bold text-white">Cours Actuels</h2>
            </div>
            
            <div class="space-y-4">
                @foreach($user->inscriptionsCours()->with('cours')->get() as $inscription)
                <div class="p-4 bg-violet-500/10 border border-violet-500/30 rounded-lg">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="font-medium text-white">{{ $inscription->cours->nom }}</div>
                            <div class="text-sm text-gray-300">{{ $inscription->cours->description }}</div>
                            <div class="text-sm text-gray-300">Niveau: {{ $inscription->cours->niveau ?? 'Tous niveaux' }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-300">Inscrit le</div>
                            <div class="text-white">{{ $inscription->created_at->format('d/m/Y') }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Rôles et permissions -->
        @if($user->roles->count() > 0)
        <div class="studiosdb-card">
            <div class="flex items-center space-x-3 mb-6">
                <x-admin-icon name="users" size="w-6 h-6" color="text-blue-400" />
                <h2 class="text-xl font-bold text-white">Rôles et Permissions</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-white mb-3">🏷️ Rôles assignés</h3>
                    <div class="space-y-2">
                        @foreach($user->roles as $role)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                            {{ $role->name }}
                        </span>
                        @endforeach
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-medium text-white mb-3">🔑 Permissions totales</h3>
                    <div class="text-2xl font-bold text-blue-400">
                        {{ $user->getAllPermissions()->count() }}
                    </div>
                    <div class="text-sm text-gray-300">permissions actives</div>
                </div>
            </div>
        </div>
        @endif

        <!-- Notes administratives -->
        @if($user->notes)
        <div class="studiosdb-card">
            <div class="flex items-center space-x-3 mb-6">
                <x-admin-icon name="users" size="w-6 h-6" color="text-blue-400" />
                <h2 class="text-xl font-bold text-white">📝 Notes Administratives</h2>
            </div>
            
            <div class="p-4 bg-slate-700/50 rounded-lg border border-slate-600">
                <div class="text-gray-300">{{ $user->notes }}</div>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="studiosdb-card">
            <div class="flex items-center space-x-3 mb-6">
                <x-admin-icon name="users" size="w-6 h-6" color="text-blue-400" />
                <h2 class="text-xl font-bold text-white">🚀 Actions</h2>
            </div>
            
            <div class="flex flex-wrap gap-4">
                @can('update', $user)
                <a href="{{ route('admin.users.edit', $user) }}" 
                   class="studiosdb-btn studiosdb-btn-users">
                    ✏️ Modifier le profil
                </a>
                @endcan
                
                @can('viewAny', App\Models\Presence::class)
                <a href="{{ route('admin.presences.index', ['user_id' => $user->id]) }}" 
                   class="studiosdb-btn studiosdb-btn-presences">
                    📋 Voir les présences
                </a>
                @endcan
                
                @can('viewAny', App\Models\Paiement::class)
                <a href="{{ route('admin.paiements.index', ['user_id' => $user->id]) }}" 
                   class="studiosdb-btn studiosdb-btn-paiements">
                    💰 Voir les paiements
                </a>
                @endcan
                
                <a href="{{ route('admin.users.qrcode', $user) }}" 
                   class="studiosdb-btn studiosdb-btn-cancel">
                    📱 QR Code
                </a>
                
                <a href="{{ route('admin.users.index') }}" 
                   class="studiosdb-btn studiosdb-btn-cancel">
                    ← Retour à la liste
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
