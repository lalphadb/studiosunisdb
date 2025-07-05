@extends('layouts.admin')

@section('title', 'Gestion des Utilisateurs')

@section('breadcrumb')
    <span class="text-cyan-400">Utilisateurs</span>
@endsection

@section('content')
<x-module-header 
    module="users"
    title="Gestion des Utilisateurs"
    subtitle="Administration complète des utilisateurs StudiosDB"
    :createRoute="route('admin.users.create')"
    createText="Nouvel Utilisateur"
    createPermission="users.create"
    :breadcrumbs="[
        ['name' => 'Admin', 'url' => route('admin.dashboard')],
        ['name' => 'Utilisateurs', 'url' => null]
    ]">
    
    <!-- Métriques dans le slot -->
    <x-slot name="metrics">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="studiosdb-card bg-slate-800 border border-slate-700 rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-400 text-sm font-medium uppercase">Total Utilisateurs</p>
                        <p class="text-3xl font-bold text-cyan-400">{{ $stats['total'] ?? 0 }}</p>
                        <p class="text-slate-500 text-xs">Dans tout le système</p>
                    </div>
                    <div class="bg-cyan-500 bg-opacity-20 p-3 rounded-lg">
                        <i class="fas fa-users text-cyan-400 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="studiosdb-card bg-slate-800 border border-slate-700 rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-400 text-sm font-medium uppercase">En Ligne</p>
                        <p class="text-3xl font-bold text-green-400">{{ $stats['online'] ?? 0 }}</p>
                        <p class="text-slate-500 text-xs">Utilisateurs actifs</p>
                    </div>
                    <div class="bg-green-500 bg-opacity-20 p-3 rounded-lg">
                        <i class="fas fa-circle text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="studiosdb-card bg-slate-800 border border-slate-700 rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-400 text-sm font-medium uppercase">Nouveaux</p>
                        <p class="text-3xl font-bold text-purple-400">{{ $stats['nouveaux'] ?? 0 }}</p>
                        <p class="text-slate-500 text-xs">Cette semaine</p>
                    </div>
                    <div class="bg-purple-500 bg-opacity-20 p-3 rounded-lg">
                        <i class="fas fa-user-plus text-purple-400 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="studiosdb-card bg-slate-800 border border-slate-700 rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-400 text-sm font-medium uppercase">Admin École</p>
                        <p class="text-3xl font-bold text-yellow-400">{{ $stats['admins'] ?? 0 }}</p>
                        <p class="text-slate-500 text-xs">Gestionnaires</p>
                    </div>
                    <div class="bg-yellow-500 bg-opacity-20 p-3 rounded-lg">
                        <i class="fas fa-crown text-yellow-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>
</x-module-header>

<x-admin.flash-messages />

<!-- Filtres et recherche -->
<div class="studiosdb-card bg-slate-800 border border-slate-700 rounded-xl p-6 mb-6">
    <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}"
                   placeholder="Rechercher un utilisateur..."
                   class="studiosdb-input w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-200 placeholder-slate-400 focus:border-cyan-500 focus:ring-cyan-500">
        </div>
        
        <select name="role" 
                class="studiosdb-input px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-200 focus:border-cyan-500 focus:ring-cyan-500">
            <option value="">Tous les rôles</option>
            <option value="superadmin" {{ request('role') === 'superadmin' ? 'selected' : '' }}>Super Admin</option>
            <option value="admin_ecole" {{ request('role') === 'admin_ecole' ? 'selected' : '' }}>Admin École</option>
            <option value="membre" {{ request('role') === 'membre' ? 'selected' : '' }}>Membre</option>
        </select>
        
        <button type="submit" 
                class="studiosdb-btn-primary bg-cyan-600 hover:bg-cyan-700 text-white px-6 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2">
            <i class="fas fa-search"></i>
            <span>Rechercher</span>
        </button>
        
        @if(request()->hasAny(['search', 'role']))
            <a href="{{ route('admin.users.index') }}" 
               class="studiosdb-btn-secondary bg-slate-600 hover:bg-slate-500 text-slate-200 px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2">
                <i class="fas fa-times"></i>
                <span>Effacer</span>
            </a>
        @endif
    </form>
</div>

<!-- Table des utilisateurs -->
<div class="studiosdb-card bg-slate-800 border border-slate-700 rounded-xl overflow-hidden">
    @if($users->count() > 0)
        <div class="overflow-x-auto">
            <table class="studiosdb-table w-full">
                <thead class="bg-slate-700 border-b border-slate-600">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                            <input type="checkbox" id="select-all" class="rounded border-slate-600 text-cyan-600 focus:ring-cyan-500">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Utilisateur</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">École</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Rôle</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Statut Inscription</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-slate-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @foreach($users as $user)
                        <tr class="hover:bg-slate-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="user-checkbox rounded border-slate-600 text-cyan-600 focus:ring-cyan-500" value="{{ $user->id }}">
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-200">{{ $user->name }}</p>
                                        <p class="text-sm text-slate-400">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->ecole)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-300">
                                        {{ $user->ecole->nom }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-sm">-</span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->hasRole('superadmin'))
                                    <span class="studiosdb-badge-success">Super Admin</span>
                                @elseif($user->hasRole('admin_ecole'))
                                    <span class="studiosdb-badge-warning">Admin École</span>
                                @else
                                    <span class="studiosdb-badge-info">Membre</span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->email_verified_at)
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                                        <span class="text-sm text-green-400">Actif</span>
                                        <span class="text-xs text-slate-500">il y a {{ $user->created_at->diffForHumans() }}</span>
                                    </div>
                                @else
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-red-400 rounded-full animate-pulse"></div>
                                        <span class="text-sm text-red-400">En attente</span>
                                    </div>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <x-module-actions 
                                    :item="$user"
                                    module="users"
                                    :canView="auth()->user()->can('users.view')"
                                    :canEdit="auth()->user()->can('users.edit')"
                                    :canDelete="auth()->user()->can('users.delete') && $user->id !== auth()->id()" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-slate-700 px-6 py-4 border-t border-slate-600">
            {{ $users->appends(request()->query())->links() }}
        </div>
    @else
        <div class="p-6">
            <x-empty-state 
                icon="fa-users"
                title="Aucun utilisateur trouvé"
                description="Commencez par ajouter votre premier utilisateur."
                :action="[
                    'text' => 'Ajouter un utilisateur',
                    'url' => route('admin.users.create'),
                    'icon' => 'fa-plus'
                ]" />
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sélection multiple
    const selectAll = document.getElementById('select-all');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    
    selectAll?.addEventListener('change', function() {
        userCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
});
</script>
@endpush
