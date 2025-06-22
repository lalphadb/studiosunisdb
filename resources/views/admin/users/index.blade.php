@extends('layouts.admin')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="space-y-6">
    <!-- Header avec gradient -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-6 text-white shadow-xl">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2 flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Gestion des Utilisateurs
                </h1>
                <p class="text-blue-100">
                    @if(auth()->user()->hasRole('superadmin'))
                        Gestion globale de tous les utilisateurs
                    @else
                        Gestion des utilisateurs de votre école
                    @endif
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.create') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-all flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Nouvel Utilisateur
                </a>
            </div>
        </div>
    </div>

    <!-- Métriques en ligne -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Total Utilisateurs -->
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl p-4 text-white">
            <div class="flex items-center">
                <div class="p-2 bg-white bg-opacity-20 rounded-lg mr-3">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-blue-100 text-sm">Total Utilisateurs</p>
                    <p class="text-2xl font-bold">{{ $users->total() }}</p>
                </div>
            </div>
        </div>

        <!-- Administrateurs -->
        <div class="bg-gradient-to-br from-purple-600 to-purple-700 rounded-xl p-4 text-white">
            <div class="flex items-center">
                <div class="p-2 bg-white bg-opacity-20 rounded-lg mr-3">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-purple-100 text-sm">Administrateurs</p>
                    <p class="text-2xl font-bold">{{ $metrics['admins'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Instructeurs -->
        <div class="bg-gradient-to-br from-green-600 to-green-700 rounded-xl p-4 text-white">
            <div class="flex items-center">
                <div class="p-2 bg-white bg-opacity-20 rounded-lg mr-3">
                    <span class="text-xl">👨‍🏫</span>
                </div>
                <div>
                    <p class="text-green-100 text-sm">Instructeurs</p>
                    <p class="text-2xl font-bold">{{ $metrics['instructeurs'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Membres -->
        <div class="bg-gradient-to-br from-yellow-600 to-yellow-700 rounded-xl p-4 text-white">
            <div class="flex items-center">
                <div class="p-2 bg-white bg-opacity-20 rounded-lg mr-3">
                    <span class="text-xl">👥</span>
                </div>
                <div>
                    <p class="text-yellow-100 text-sm">Membres</p>
                    <p class="text-2xl font-bold">{{ $metrics['membres'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et Recherche -->
    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-700 to-gray-600 p-4">
            <h3 class="text-lg font-bold text-white">🔍 Filtres et Recherche</h3>
        </div>
        
        <div class="p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Recherche -->
                <div>
                    <label class="block text-gray-300 text-sm font-medium mb-2">Recherche</label>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Nom ou email..." 
                           class="w-full bg-gray-700 border-gray-600 text-white rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- École (SuperAdmin seulement) -->
                @if(auth()->user()->hasRole('superadmin') && $ecoles->isNotEmpty())
                <div>
                    <label class="block text-gray-300 text-sm font-medium mb-2">École</label>
                    <select name="ecole_id" class="w-full bg-gray-700 border-gray-600 text-white rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Toutes les écoles</option>
                        @foreach($ecoles as $ecole)
                            <option value="{{ $ecole->id }}" {{ request('ecole_id') == $ecole->id ? 'selected' : '' }}>
                                [{{ $ecole->code }}] {{ $ecole->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <!-- Rôle -->
                <div>
                    <label class="block text-gray-300 text-sm font-medium mb-2">Rôle</label>
                    <select name="role" class="w-full bg-gray-700 border-gray-600 text-white rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Tous les rôles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end space-x-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        🔍 Filtrer
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                        🔄 Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des utilisateurs -->
    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-4">
            <h3 class="text-lg font-bold text-white">
                👥 Utilisateurs ({{ $users->total() }})
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Utilisateur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">École</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Rôle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Inscription</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-700 transition-colors">
                            <!-- Utilisateur -->
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gray-600 rounded-lg flex items-center justify-center text-white font-bold mr-3">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="text-white font-medium">{{ $user->name }}</div>
                                        <div class="text-gray-400 text-sm">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- École -->
                            <td class="px-6 py-4">
                                @if($user->ecole)
                                    <span class="bg-green-900 text-green-300 px-2 py-1 rounded text-sm">
                                        {{ $user->ecole->code }}
                                    </span>
                                @else
                                    <span class="text-gray-500 text-sm">Aucune école</span>
                                @endif
                            </td>

                            <!-- Rôle -->
                            <td class="px-6 py-4">
                                @if($user->roles->isNotEmpty())
                                    <span class="bg-blue-900 text-blue-300 px-2 py-1 rounded text-sm">
                                        {{ ucfirst($user->roles->first()->name) }}
                                    </span>
                                @else
                                    <span class="text-gray-500 text-sm">Aucun rôle</span>
                                @endif
                            </td>

                            <!-- Statut -->
                            <td class="px-6 py-4">
                                @if($user->active)
                                    <span class="bg-green-900 text-green-300 px-2 py-1 rounded text-sm font-medium">Actif</span>
                                @else
                                    <span class="bg-red-900 text-red-300 px-2 py-1 rounded text-sm font-medium">Inactif</span>
                                @endif
                            </td>

                            <!-- Inscription -->
                            <td class="px-6 py-4 text-gray-400 text-sm">
                                {{ $user->date_inscription?->format('d/m/Y') ?? $user->created_at?->format('d/m/Y') }}
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.users.show', $user) }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                        👁️ Voir
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                        ✏️ Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                                Aucun utilisateur trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="bg-gray-700 px-6 py-3">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
