@extends('layouts.admin')

@section('title', 'Dashboard École')

@section('content')
<div class="space-y-6">
    <!-- Header Admin École DISTINCTIF -->
    <div class="bg-gradient-to-r from-emerald-500 via-emerald-600 to-teal-600 rounded-xl p-8 text-white shadow-2xl border-2 border-emerald-400/30 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-white/5 via-transparent to-white/10"></div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <div class="w-16 h-16 bg-emerald-500/30 rounded-2xl flex items-center justify-center backdrop-blur border border-emerald-400/50 shadow-lg">
                        <span class="text-3xl">🏫</span>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white drop-shadow-sm">🏫 ADMIN ÉCOLE</h1>
                        <p class="text-lg text-emerald-100 font-medium">{{ $ecole->nom ?? 'École de Karaté' }}</p>
                        <p class="text-sm text-emerald-200">Gestion complète de votre établissement</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="bg-emerald-500/30 px-6 py-3 rounded-xl border border-emerald-400/50 backdrop-blur">
                        <div class="text-sm text-emerald-100 font-medium">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-emerald-200">Administrateur École</div>
                        <div class="text-xs text-emerald-300 mt-1">{{ now()->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats École -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-slate-800/50 border border-emerald-500/20 rounded-xl p-6 hover:border-emerald-500/40 transition-colors">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Mes Membres</p>
                    <p class="text-3xl font-bold text-emerald-400">{{ $stats['mes_membres'] ?? 0 }}</p>
                    <p class="text-xs text-slate-500 mt-1">{{ $stats['membres_actifs'] ?? 0 }} actifs</p>
                </div>
                <div class="w-14 h-14 bg-emerald-500/20 border-emerald-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="users" size="w-7 h-7" color="text-emerald-400" />
                </div>
            </div>
        </div>
        
        <div class="bg-slate-800/50 border border-blue-500/20 rounded-xl p-6 hover:border-blue-500/40 transition-colors">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Nouveaux ce mois</p>
                    <p class="text-3xl font-bold text-blue-400">{{ $stats['nouveaux_mois'] ?? 0 }}</p>
                    <p class="text-xs text-slate-500 mt-1">{{ now()->format('M Y') }}</p>
                </div>
                <div class="w-14 h-14 bg-blue-500/20 border-blue-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="stats" size="w-7 h-7" color="text-blue-400" />
                </div>
            </div>
        </div>
        
        <div class="bg-slate-800/50 border border-violet-500/20 rounded-xl p-6 hover:border-violet-500/40 transition-colors">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Mes Cours</p>
                    <p class="text-3xl font-bold text-violet-400">{{ $stats['mes_cours'] ?? 0 }}</p>
                    <p class="text-xs text-slate-500 mt-1">{{ $stats['cours_actifs'] ?? 0 }} actifs</p>
                </div>
                <div class="w-14 h-14 bg-violet-500/20 border-violet-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="cours" size="w-7 h-7" color="text-violet-400" />
                </div>
            </div>
        </div>
        
        <div class="bg-slate-800/50 border border-amber-500/20 rounded-xl p-6 hover:border-amber-500/40 transition-colors">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Revenus Mois</p>
                    <p class="text-3xl font-bold text-amber-400">${{ number_format($stats['revenus_mois'] ?? 0, 0) }}</p>
                    <p class="text-xs text-slate-500 mt-1">Estimation</p>
                </div>
                <div class="w-14 h-14 bg-amber-500/20 border-amber-500/30 rounded-2xl flex items-center justify-center border">
                    <span class="text-amber-400 text-lg font-bold">$</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Nouveaux Membres -->
    <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-3">
                <x-admin-icon name="users" size="w-6 h-6" color="text-emerald-400" />
                <h2 class="text-xl font-bold text-white">Nouveaux Membres</h2>
            </div>
            <a href="{{ route('admin.users.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors font-medium">
                <x-admin-icon name="plus" size="w-4 h-4" />
                <span class="ml-2">Ajouter Membre</span>
            </a>
        </div>
        
        <div class="space-y-4">
            @forelse($derniers_membres as $membre)
                <div class="flex items-center justify-between p-4 bg-slate-700/30 rounded-xl border border-slate-600/30">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-medium">{{ substr($membre->name, 0, 2) }}</span>
                        </div>
                        <div>
                            <div class="text-white font-medium">{{ $membre->name }}</div>
                            <div class="text-slate-400 text-sm">{{ $membre->email }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-slate-400 text-sm">{{ $membre->created_at->format('d/m/Y') }}</div>
                        <div class="text-slate-500 text-xs">{{ $membre->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-slate-400">
                    <x-admin-icon name="users" size="w-12 h-12" color="text-slate-500" class="mx-auto mb-4" />
                    <p>Aucun nouveau membre récemment</p>
                    <p class="text-sm mt-2">Les nouveaux membres apparaîtront ici</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Actions Rapides -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Gestion Membres -->
        <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-6 group hover:border-emerald-500/30 transition-colors">
            <div class="text-center">
                <div class="w-16 h-16 bg-emerald-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-emerald-500/30 transition-colors">
                    <x-admin-icon name="users" size="w-8 h-8" color="text-emerald-400" />
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Gestion Membres</h3>
                <p class="text-slate-400 text-sm mb-4">Gérer les membres de votre école</p>
                <div class="space-y-2">
                    <a href="{{ route('admin.users.index') }}" 
                       class="block w-full px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-center font-medium">
                        Voir Tous les Membres
                    </a>
                    <a href="{{ route('admin.users.create') }}" 
                       class="block w-full px-4 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition-colors text-center font-medium">
                        Ajouter un Membre
                    </a>
                </div>
            </div>
        </div>

        <!-- Gestion Cours -->
        <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-6 group hover:border-violet-500/30 transition-colors">
            <div class="text-center">
                <div class="w-16 h-16 bg-violet-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-violet-500/30 transition-colors">
                    <x-admin-icon name="cours" size="w-8 h-8" color="text-violet-400" />
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Gestion Cours</h3>
                <p class="text-slate-400 text-sm mb-4">Organiser les cours et programmes</p>
                <div class="space-y-2">
                    <a href="{{ route('admin.cours.index') }}" 
                       class="block w-full px-4 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 transition-colors text-center font-medium">
                        Voir Mes Cours
                    </a>
                    <a href="{{ route('admin.cours.create') }}" 
                       class="block w-full px-4 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition-colors text-center font-medium">
                        Créer un Cours
                    </a>
                </div>
            </div>
        </div>

        <!-- Actions Rapides -->
        <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-6 group hover:border-orange-500/30 transition-colors">
            <div class="text-center">
                <div class="w-16 h-16 bg-orange-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-orange-500/30 transition-colors">
                    <x-admin-icon name="ceintures" size="w-8 h-8" color="text-orange-400" />
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Actions Rapides</h3>
                <p class="text-slate-400 text-sm mb-4">Outils de gestion rapide</p>
                <div class="space-y-2">
                    <a href="{{ route('admin.ceintures.index') }}" 
                       class="block w-full px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors text-center font-medium">
                        Attribuer Ceinture
                    </a>
                    <a href="{{ route('admin.presences.index') }}" 
                       class="block w-full px-4 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition-colors text-center font-medium">
                        Gérer Présences
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mes Cours -->
    @if($prochains_cours->count() > 0)
    <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-3">
                <x-admin-icon name="cours" size="w-6 h-6" color="text-violet-400" />
                <h2 class="text-xl font-bold text-white">Mes Cours Actifs</h2>
            </div>
            <a href="{{ route('admin.cours.index') }}" 
               class="text-violet-400 hover:text-violet-300 text-sm font-medium">
                Voir tous les cours →
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($prochains_cours as $cours)
                <div class="bg-slate-700/30 border border-slate-600/30 rounded-lg p-4 hover:border-violet-500/30 transition-colors">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-10 h-10 bg-violet-500/20 rounded-lg flex items-center justify-center">
                            <x-admin-icon name="cours" size="w-5 h-5" color="text-violet-400" />
                        </div>
                        <div>
                            <div class="text-white font-medium">{{ $cours->nom }}</div>
                            <div class="text-slate-400 text-sm">{{ $cours->niveau ?? 'Tous niveaux' }}</div>
                        </div>
                    </div>
                    <p class="text-slate-300 text-sm mb-3">{{ Str::limit($cours->description, 60) }}</p>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-400">{{ $cours->created_at->format('d/m/Y') }}</span>
                        <span class="px-2 py-1 bg-violet-500/20 text-violet-300 rounded">Actif</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Message de distinction École -->
    <div class="bg-emerald-500/10 border border-emerald-500/30 rounded-xl p-6">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-emerald-500/20 rounded-xl flex items-center justify-center">
                <span class="text-emerald-400 text-xl">🏫</span>
            </div>
            <div>
                <h4 class="text-lg font-bold text-emerald-400">Interface Administrateur École</h4>
                <p class="text-emerald-300 text-sm">Vous gérez l'école "<strong>{{ $ecole->nom }}</strong>". Cette interface vous donne accès à tous les outils de gestion de votre établissement.</p>
            </div>
        </div>
    </div>
</div>
@endsection
