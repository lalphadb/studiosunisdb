@extends('layouts.admin')

@section('title', 'Dashboard École')

@section('content')
<div class="space-y-6">
    <!-- Header Dashboard École -->
    <div class="gradient-ecoles text-white p-8 rounded-2xl border border-emerald-500/20 relative overflow-hidden backdrop-blur-sm studiosdb-fade-in">
        <div class="absolute inset-0 bg-gradient-to-br from-white/3 via-transparent to-white/2"></div>
        
        <div class="relative z-10">
            <div class="flex items-center space-x-6 mb-6">
                <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur border border-white/20">
                    <x-admin-icon name="ecoles" size="w-8 h-8" color="text-white" />
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white drop-shadow-sm">{{ auth()->user()->ecole->nom ?? 'École de Karaté' }}</h1>
                    <p class="text-lg text-white/90 font-medium">Dashboard Administrateur École</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="studiosdb-card">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Total Membres</p>
                    <p class="text-3xl font-bold text-emerald-400">{{ auth()->user()->ecole->users->count() ?? 1 }}</p>
                    <p class="text-xs text-slate-500 mt-1">+ actifs</p>
                </div>
                <div class="w-14 h-14 bg-emerald-500/20 border-emerald-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="users" size="w-7 h-7" color="text-emerald-400" />
                </div>
            </div>
        </div>
        
        <div class="studiosdb-card">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Nouveaux ce mois</p>
                    <p class="text-3xl font-bold text-blue-400">{{ auth()->user()->ecole->users->where('created_at', '>=', now()->startOfMonth())->count() ?? 2 }}</p>
                    <p class="text-xs text-slate-500 mt-1">Ce mois</p>
                </div>
                <div class="w-14 h-14 bg-blue-500/20 border-blue-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="stats" size="w-7 h-7" color="text-blue-400" />
                </div>
            </div>
        </div>
        
        <div class="studiosdb-card">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Cours Actifs</p>
                    <p class="text-3xl font-bold text-violet-400">{{ auth()->user()->ecole->cours->where('active', true)->count() ?? 0 }}</p>
                    <p class="text-xs text-slate-500 mt-1">0 actifs</p>
                </div>
                <div class="w-14 h-14 bg-violet-500/20 border-violet-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="cours" size="w-7 h-7" color="text-violet-400" />
                </div>
            </div>
        </div>
        
        <div class="studiosdb-card">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Revenus Mois</p>
                    <p class="text-3xl font-bold text-amber-400">${{ number_format(160, 0) }}</p>
                    <p class="text-xs text-slate-500 mt-1">Ce mois</p>
                </div>
                <div class="w-14 h-14 bg-amber-500/20 border-amber-500/30 rounded-2xl flex items-center justify-center border">
                    <span class="text-amber-400 text-lg font-bold">$</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Nouveaux Membres -->
    <div class="studiosdb-card">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-3">
                <x-admin-icon name="users" size="w-6 h-6" color="text-blue-400" />
                <h2 class="text-xl font-bold text-white">Nouveaux Membres</h2>
            </div>
            <a href="{{ route('admin.users.create') }}" 
               class="studiosdb-btn studiosdb-btn-ecoles">
                + Ajouter Membre
            </a>
        </div>
        
        <!-- Liste des nouveaux membres -->
        <div class="space-y-4">
            @forelse(auth()->user()->ecole->users->take(3) ?? [] as $membre)
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
                    <p>Aucun nouveau membre récemment</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Actions Rapides -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Gestion Membres -->
        <div class="studiosdb-card group hover:bg-emerald-500/5">
            <div class="text-center">
                <div class="w-16 h-16 bg-emerald-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-emerald-500/30 transition-colors">
                    <x-admin-icon name="users" size="w-8 h-8" color="text-emerald-400" />
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Gestion Membres</h3>
                <p class="text-slate-400 text-sm mb-4">Gérer les membres de votre école</p>
                <div class="space-y-2">
                    <a href="{{ route('admin.users.index') }}" 
                       class="studiosdb-btn studiosdb-btn-ecoles w-full">
                        Voir Tous les Membres
                    </a>
                    <a href="{{ route('admin.users.create') }}" 
                       class="studiosdb-btn studiosdb-btn-cancel w-full">
                        Ajouter un Membre
                    </a>
                </div>
            </div>
        </div>

        <!-- Gestion Cours -->
        <div class="studiosdb-card group hover:bg-violet-500/5">
            <div class="text-center">
                <div class="w-16 h-16 bg-violet-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-violet-500/30 transition-colors">
                    <x-admin-icon name="cours" size="w-8 h-8" color="text-violet-400" />
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Gestion Cours</h3>
                <p class="text-slate-400 text-sm mb-4">Organiser les cours et programmes</p>
                <div class="space-y-2">
                    <a href="{{ route('admin.cours.index') }}" 
                       class="studiosdb-btn studiosdb-btn-cours w-full">
                        Voir Mes Cours
                    </a>
                    <a href="{{ route('admin.cours.create') }}" 
                       class="studiosdb-btn studiosdb-btn-cancel w-full">
                        Créer un Cours
                    </a>
                </div>
            </div>
        </div>

        <!-- Actions Rapides -->
        <div class="studiosdb-card group hover:bg-orange-500/5">
            <div class="text-center">
                <div class="w-16 h-16 bg-orange-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-orange-500/30 transition-colors">
                    <x-admin-icon name="ceintures" size="w-8 h-8" color="text-orange-400" />
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Actions Rapides</h3>
                <p class="text-slate-400 text-sm mb-4">Outils de gestion rapide</p>
                <div class="space-y-2">
                    <a href="{{ route('admin.ceintures.index') }}" 
                       class="studiosdb-btn studiosdb-btn-ceintures w-full">
                        Attribuer Ceinture
                    </a>
                    <a href="{{ route('admin.presences.index') }}" 
                       class="studiosdb-btn studiosdb-btn-presences w-full">
                        Gérer Présences
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
