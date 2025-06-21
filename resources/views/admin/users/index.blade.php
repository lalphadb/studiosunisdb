@extends('layouts.admin')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="admin-content">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-6 text-white shadow-xl mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2 flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                    Gestion des Utilisateurs
                </h1>
                <p class="text-blue-100">
                    @if(auth()->user()->isSuperAdmin())
                        Gestion globale de tous les utilisateurs
                    @else
                        Gestion des utilisateurs de votre école
                    @endif
                </p>
            </div>
            
            @can('create-user')
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.users.create') }}" class="btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Nouvel Utilisateur
                </a>
            </div>
            @endcan
        </div>
    </div>

    <!-- Métriques -->
    @if(!empty($metrics))
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        @if(auth()->user()->isSuperAdmin())
        <div class="admin-card p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-500 bg-opacity-20 rounded-full">
                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-400">Total Utilisateurs</h3>
                    <p class="text-2xl font-bold text-white">{{ $metrics['total_users'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        @endif

        <div class="admin-card p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-500 bg-opacity-20 rounded-full">
                    <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-400">Administrateurs</h3>
                    <p class="text-2xl font-bold text-white">{{ $metrics['admins'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="admin-card p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-500 bg-opacity-20 rounded-full">
                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-400">Instructeurs</h3>
                    <p class="text-2xl font-bold text-white">{{ $metrics['instructeurs'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="admin-card p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-500 bg-opacity-20 rounded-full">
                    <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-400">Membres</h3>
                    <p class="text-2xl font-bold text-white">{{ $metrics['membres'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filtres et recherche -->
    <div class="admin-card mb-6">
        <div class="bg-gradient-to-r from-gray-600 to-gray-700 p-4">
            <h3 class="text-lg font-bold text-white">Filtres et Recherche</h3>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="form-label">Recherche</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           class="form-input" 
                           placeholder="Nom ou email...">
                </div>

                @if(auth()->user()->isSuperAdmin() && $ecoles->count() > 0)
                <div>
                    <label for="ecole_id" class="form-label">École</label>
                    <select id="ecole_id" name="ecole_id" class="form-select">
                        <option value="">Toutes les écoles</option>
                        @foreach($ecoles as $ecole)
                            <option value="{{ $ecole->id }}" {{ request('ecole_id') == $ecole->id ? 'selected' : '' }}>
                                [{{ $ecole->code }}] {{ $ecole->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div>
                    <label for="role" class="form-label">Rôle</label>
                    <select id="role" name="role" class="form-select">
                        <option value="">Tous les rôles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end space-x-2">
                    <button type="submit" class="btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Filtrer
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des utilisateurs -->
    <div class="admin-card">
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-4">
            <h3 class="text-lg font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                </svg>
                Utilisateurs ({{ $users->total() }})
            </h3>
        </div>

        <div class="p-6">
            @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-600">
                            <th class="text-left py-3 px-4 text-gray-300 font-semibold">Utilisateur</th>
                            <th class="text-left py-3 px-4 text-gray-300 font-semibold">École</th>
                            <th class="text-center py-3 px-4 text-gray-300 font-semibold">Rôle</th>
                            <th class="text-center py-3 px-4 text-gray-300 font-semibold">Statut</th>
                            <th class="text-center py-3 px-4 text-gray-300 font-semibold">Inscription</th>
                            <th class="text-center py-3 px-4 text-gray-300 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-600">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-700 transition-colors">
                            <td class="py-4 px-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-400 rounded-lg flex items-center justify-center">
                                        <span class="text-white font-bold text-sm">{{ substr($user->name, 0, 2) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-white font-medium">{{ $user->name }}</div>
                                        <div class="text-gray-400 text-sm">{{ $user->email }}</div>
                                        @if($user->telephone)
                                        <div class="text-gray-500 text-xs">{{ $user->telephone }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                @if($user->ecole)
                                <div class="text-white">{{ $user->ecole->nom }}</div>
                                <div class="text-gray-400 text-sm">[{{ $user->ecole->code }}] {{ $user->ecole->ville }}</div>
                                @else
                                <span class="text-gray-500">Aucune école</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($user->roles->count() > 0)
                                    @php
                                        $role = $user->roles->first()->name;
                                        $colors = [
                                            'superadmin' => 'bg-red-500',
                                            'admin' => 'bg-purple-500',
                                            'instructeur' => 'bg-green-500',
                                            'membre' => 'bg-blue-500'
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs font-medium text-white {{ $colors[$role] ?? 'bg-gray-500' }}">
                                        {{ ucfirst($role) }}
                                    </span>
                                @else
                                    <span class="text-gray-500">Aucun rôle</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($user->active)
                                    <span class="badge-success">Actif</span>
                                @else
                                    <span class="badge-danger">Inactif</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center">
                                <div class="text-white text-sm">{{ $user->date_inscription ? $user->date_inscription->format('d/m/Y') : '-' }}</div>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin.users.show', $user) }}" 
                                       class="text-blue-400 hover:text-blue-300" title="Voir détails">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    
                                    @can('edit-user')
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="text-yellow-400 hover:text-yellow-300" title="Modifier">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    @endcan

                                    @can('delete-user')
                                    @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" 
                                          class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300" title="Supprimer">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $users->withQueryString()->links() }}
            </div>
            @else
            <div class="text-center py-8">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                </svg>
                <h3 class="text-xl font-medium text-gray-300 mb-2">Aucun utilisateur trouvé</h3>
                <p class="text-gray-400">Aucun utilisateur ne correspond aux critères de recherche.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
