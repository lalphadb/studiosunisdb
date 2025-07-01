@extends('layouts.admin')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="space-y-6">
    <!-- Header Module Simple -->
    <div class="gradient-users text-white p-8 rounded-2xl border border-blue-500/20 relative overflow-hidden backdrop-blur-sm studiosdb-fade-in">
        <div class="absolute inset-0 bg-gradient-to-br from-white/3 via-transparent to-white/2"></div>
        
        <div class="relative z-10 flex justify-between items-center">
            <div class="flex items-center space-x-6">
                <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur border border-white/20">
                    <x-admin-icon name="users" size="w-8 h-8" color="text-white" />
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white drop-shadow-sm">Gestion des Utilisateurs</h1>
                    <p class="text-lg text-white/90 font-medium">Administration complète des utilisateurs StudiosDB</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.users.create') }}" 
                   class="bg-white/15 hover:bg-white/25 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 flex items-center space-x-3 backdrop-blur border border-white/20">
                    <x-admin-icon name="plus" size="w-5 h-5" color="text-white" />
                    <span>Nouvel Utilisateur</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards SÉCURISÉES -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="studiosdb-card hover:scale-105 cursor-pointer group">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Total Utilisateurs</p>
                    <p class="text-3xl font-bold text-white group-hover:text-blue-400 transition-colors">
                        {{ isset($users) && method_exists($users, 'total') ? $users->total() : (isset($users) ? $users->count() : 0) }}
                    </p>
                    <p class="text-xs text-slate-500 mt-1">Dans tout le système</p>
                </div>
                <div class="w-14 h-14 bg-blue-500/20 border-blue-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="users" size="w-7 h-7" color="text-blue-400" />
                </div>
            </div>
        </div>
        
        <div class="studiosdb-card">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">En Ligne</p>
                    <p class="text-3xl font-bold text-emerald-400">{{ isset($users) ? $users->count() : 0 }}</p>
                    <p class="text-xs text-slate-500 mt-1">Utilisateurs actifs</p>
                </div>
                <div class="w-14 h-14 bg-emerald-500/20 border-emerald-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="presences" size="w-7 h-7" color="text-emerald-400" />
                </div>
            </div>
        </div>
        
        <div class="studiosdb-card">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Nouveaux</p>
                    <p class="text-3xl font-bold text-violet-400">
                        {{ isset($users) ? $users->filter(function($u) { 
                            return $u->created_at && $u->created_at >= now()->subWeek(); 
                        })->count() : 0 }}
                    </p>
                    <p class="text-xs text-slate-500 mt-1">Cette semaine</p>
                </div>
                <div class="w-14 h-14 bg-violet-500/20 border-violet-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="stats" size="w-7 h-7" color="text-violet-400" />
                </div>
            </div>
        </div>
        
        <div class="studiosdb-card">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Admin École</p>
                    <p class="text-3xl font-bold text-amber-400">
                        {{ isset($users) ? $users->filter(function($u) { 
                            return $u->roles && $u->roles->pluck('name')->contains('admin_ecole'); 
                        })->count() : 0 }}
                    </p>
                    <p class="text-xs text-slate-500 mt-1">Gestionnaires</p>
                </div>
                <div class="w-14 h-14 bg-amber-500/20 border-amber-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="ecoles" size="w-7 h-7" color="text-amber-400" />
                </div>
            </div>
        </div>
    </div>

    <!-- Table moderne SÉCURISÉE -->
    <div class="studiosdb-card">
        <div class="flex items-center justify-between mb-6 pb-6 border-b border-slate-700/30">
            <div class="flex-1 max-w-lg">
                <div class="relative">
                    <input type="text" placeholder="Rechercher un utilisateur..." 
                           class="studiosdb-search w-full pl-12">
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 studiosdb-search-icon">
                        <x-admin-icon name="search" size="w-5 h-5" />
                    </div>
                </div>
            </div>
            
            <div class="flex items-center space-x-3 ml-6">
                <select class="studiosdb-select">
                    <option value="">Tous les statuts</option>
                    <option value="actif">Actif</option>
                    <option value="inactif">Inactif</option>
                </select>
                <button class="studiosdb-btn studiosdb-btn-users">
                    <x-admin-icon name="filter" size="w-4 h-4" />
                    <span class="ml-2">Filtrer</span>
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="studiosdb-table">
                <thead>
                    <tr>
                        <th class="w-4">
                            <input type="checkbox" class="w-4 h-4 text-blue-500 bg-slate-700/50 border-slate-600/50 rounded">
                        </th>
                        <th>Utilisateur</th>
                        <th>École</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Inscription</th>
                        <th class="w-20">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/30">
                    @forelse($users ?? [] as $user)
                        <tr class="hover:bg-slate-700/20 transition-colors">
                            <td>
                                <input type="checkbox" class="w-4 h-4 text-blue-500 bg-slate-700/50 border-slate-600/50 rounded">
                            </td>
                            <td>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full flex items-center justify-center">
                                        <span class="text-white font-medium">{{ substr($user->name ?? 'U', 0, 1) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-white font-medium">{{ $user->name ?? 'Nom indisponible' }}</div>
                                        <div class="text-slate-400 text-sm">{{ $user->email ?? 'Email indisponible' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if(isset($user->ecole) && $user->ecole)
                                    <span class="text-emerald-400">{{ $user->ecole->nom ?? 'École sans nom' }}</span>
                                @else
                                    <span class="text-slate-500">Aucune école</span>
                                @endif
                            </td>
                            <td>
                                @if(isset($user->roles) && $user->roles->count() > 0)
                                    @foreach($user->roles as $role)
                                        <span class="studiosdb-badge studiosdb-badge-users">
                                            {{ ucfirst(str_replace('_', ' ', $role->name ?? 'Rôle')) }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="studiosdb-badge studiosdb-badge-inactive">Aucun rôle</span>
                                @endif
                            </td>
                            <td>
                                <span class="studiosdb-badge studiosdb-badge-active">Actif</span>
                            </td>
                            <td class="text-slate-400">
                                {{ isset($user->created_at) ? $user->created_at->diffForHumans() : 'Date inconnue' }}
                            </td>
                            <td>
                                <x-actions-dropdown :model="$user" module="users" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-empty-state
                                    icon="users"
                                    title="Aucun utilisateur trouvé"
                                    description="Commencez par créer votre premier utilisateur."
                                    action-label="Créer un utilisateur"
                                    :action-route="route('admin.users.create')"
                                    action-color="blue"
                                />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if(isset($users) && method_exists($users, 'links'))
        <div class="flex justify-center">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
