@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-500 via-cyan-600 to-transparent rounded-xl p-6 text-white relative overflow-hidden mb-6">
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-transparent to-slate-900 opacity-60"></div>
        
        <div class="relative z-10 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <span class="text-3xl">👤</span>
                <div>
                    <h1 class="text-2xl font-bold">Profil Utilisateur</h1>
                    <p class="text-white/80">{{ $user->name }}</p>
                </div>
            </div>
            
            <div class="flex space-x-2">
                @can('update', $user)
                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors">
                        Modifier
                    </a>
                @endcan
                
                <a href="{{ route('admin.users.index') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors">
                    Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations personnelles -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
                <h2 class="text-xl font-bold text-white mb-4 flex items-center">
                    <span class="text-2xl mr-2">📋</span>
                    Informations personnelles
                </h2>
                
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1">Nom complet</label>
                        <p class="text-white">{{ $user->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1">Email</label>
                        <p class="text-white">{{ $user->email }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1">Téléphone</label>
                        <p class="text-white">{{ $user->telephone ?: 'Non renseigné' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1">Date de naissance</label>
                        <p class="text-white">
                            @if($user->date_naissance)
                                {{ $user->date_naissance->format('d/m/Y') }}
                                <span class="text-slate-400 text-sm">
                                    ({{ $user->date_naissance->age }} ans)
                                </span>
                            @else
                                Non renseignée
                            @endif
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1">Sexe</label>
                        <p class="text-white">{{ $user->sexe ?: 'Non renseigné' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1">Date d'inscription</label>
                        <p class="text-white">
                            @if($user->date_inscription)
                                {{ $user->date_inscription->format('d/m/Y') }}
                            @else
                                {{ $user->created_at->format('d/m/Y') }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Adresse -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
                <h2 class="text-xl font-bold text-white mb-4 flex items-center">
                    <span class="text-2xl mr-2">🏠</span>
                    Adresse
                </h2>
                
                <div class="space-y-2">
                    <p class="text-white">{{ $user->adresse ?: 'Non renseignée' }}</p>
                    <p class="text-white">
                        {{ $user->ville ?: 'Ville non renseignée' }}
                        @if($user->code_postal)
                            {{ $user->code_postal }}
                        @endif
                    </p>
                </div>
            </div>

            <!-- Contact d'urgence -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
                <h2 class="text-xl font-bold text-white mb-4 flex items-center">
                    <span class="text-2xl mr-2">🚨</span>
                    Contact d'urgence
                </h2>
                
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1">Nom</label>
                        <p class="text-white">{{ $user->contact_urgence_nom ?: 'Non renseigné' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1">Téléphone</label>
                        <p class="text-white">{{ $user->contact_urgence_telephone ?: 'Non renseigné' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Statut -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                    <span class="text-xl mr-2">⚡</span>
                    Statut
                </h3>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Actif</span>
                        <span class="px-2 py-1 rounded text-xs {{ $user->active ? 'bg-green-600 text-white' : 'bg-red-600 text-white' }}">
                            {{ $user->active ? 'Oui' : 'Non' }}
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Email vérifié</span>
                        <span class="px-2 py-1 rounded text-xs {{ $user->email_verified_at ? 'bg-green-600 text-white' : 'bg-orange-600 text-white' }}">
                            {{ $user->email_verified_at ? 'Oui' : 'Non' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- École -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                    <span class="text-xl mr-2">🏫</span>
                    École
                </h3>
                
                @if($user->ecole)
                    <div class="space-y-2">
                        <p class="text-white font-medium">{{ $user->ecole->nom }}</p>
                        <p class="text-slate-400 text-sm">{{ $user->ecole->ville }}</p>
                        <p class="text-slate-400 text-sm">Code: {{ $user->ecole->code }}</p>
                    </div>
                @else
                    <p class="text-slate-400">Aucune école assignée</p>
                @endif
            </div>

            <!-- Ceinture actuelle -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                    <span class="text-xl mr-2">🥋</span>
                    Ceinture actuelle
                </h3>
                
                @if($user->ceinture_actuelle)
                    <div class="flex items-center space-x-3">
                        <div class="w-4 h-8 rounded" style="background-color: {{ $user->ceinture_actuelle->couleur }}"></div>
                        <div>
                            <p class="text-white font-medium">{{ $user->ceinture_actuelle->nom }}</p>
                            <p class="text-slate-400 text-sm">Niveau {{ $user->ceinture_actuelle->ordre }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-slate-400">Aucune ceinture assignée</p>
                @endif
            </div>

            <!-- Rôles -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                    <span class="text-xl mr-2">🎭</span>
                    Rôles
                </h3>
                
                @if($user->roles->count() > 0)
                    <div class="space-y-2">
                        @foreach($user->roles as $role)
                            <span class="inline-block bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                {{ $role->name }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-slate-400">Aucun rôle assigné</p>
                @endif
            </div>
        </div>
    </div>

    @if($user->notes)
        <!-- Notes -->
        <div class="mt-6 bg-slate-800 rounded-xl border border-slate-700 p-6">
            <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                <span class="text-xl mr-2">📝</span>
                Notes
            </h3>
            <p class="text-slate-300">{{ $user->notes }}</p>
        </div>
    @endif
</div>
@endsection
