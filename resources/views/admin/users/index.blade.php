@extends('layouts.admin')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">👥 Gestion des Utilisateurs</h1>
                <p class="text-blue-100">Gérer les membres, instructeurs et administrateurs</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-all">
                ➕ Créer Utilisateur
            </a>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <div class="text-2xl font-bold text-white">{{ $metrics['total_users'] ?? 0 }}</div>
            <div class="text-sm text-slate-400">Total Utilisateurs</div>
        </div>
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <div class="text-2xl font-bold text-green-400">{{ $metrics['admins'] ?? 0 }}</div>
            <div class="text-sm text-slate-400">Administrateurs</div>
        </div>
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <div class="text-2xl font-bold text-blue-400">{{ $metrics['instructeurs'] ?? 0 }}</div>
            <div class="text-sm text-slate-400">Instructeurs</div>
        </div>
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <div class="text-2xl font-bold text-purple-400">{{ $metrics['membres'] ?? 0 }}</div>
            <div class="text-sm text-slate-400">Membres</div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Rechercher</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Nom ou email..." 
                       class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded-md text-white">
            </div>
            
            @if(auth()->user()->hasRole('super-admin'))
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">École</label>
                <select name="ecole_id" class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded-md text-white">
                    <option value="">Toutes les écoles</option>
                    @foreach($ecoles as $ecole)
                        <option value="{{ $ecole->id }}" {{ request('ecole_id') == $ecole->id ? 'selected' : '' }}>
                            {{ $ecole->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif
            
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Rôle</label>
                <select name="role" class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded-md text-white">
                    <option value="">Tous les rôles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    🔍 Filtrer
                </button>
                <a href="{{ route('admin.users.index') }}" class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-lg">
                    ↻ Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Liste des utilisateurs -->
    <div class="bg-slate-800 rounded-xl border border-slate-700">
        <div class="px-6 py-4 border-b border-slate-700">
            <h3 class="text-lg font-bold text-white">📋 Liste des Utilisateurs</h3>
        </div>
        
        <div class="p-6">
            @if($users->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-slate-700">
                                <th class="text-left py-3 px-4 text-slate-200">Utilisateur</th>
                                <th class="text-left py-3 px-4 text-slate-200">École</th>
                                <th class="text-left py-3 px-4 text-slate-200">Rôle</th>
                                <th class="text-left py-3 px-4 text-slate-200">Statut</th>
                                <th class="text-left py-3 px-4 text-slate-200">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr class="border-b border-slate-700 hover:bg-slate-700">
                                <td class="py-3 px-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-white font-medium">{{ $user->name }}</div>
                                            <div class="text-slate-400 text-sm">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-slate-300">
                                    {{ $user->ecole->nom ?? 'Aucune école' }}
                                </td>
                                <td class="py-3 px-4">
                                    @if($user->roles->count() > 0)
                                        <span class="px-2 py-1 rounded text-xs bg-blue-600 text-white">
                                            {{ $user->roles->first()->name }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 rounded text-xs bg-gray-600 text-white">Aucun rôle</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 rounded text-xs {{ $user->active ? 'bg-green-600' : 'bg-red-600' }} text-white">
                                        {{ $user->active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.users.show', $user) }}" class="text-blue-400 hover:text-blue-300">👁️</a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="text-yellow-400 hover:text-yellow-300">✏️</a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-6">
                    {{ $users->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">👥</div>
                    <p class="text-xl font-medium text-white mb-2">Aucun utilisateur trouvé</p>
                    <p class="text-slate-400 mb-6">Commencez par créer le premier utilisateur</p>
                    <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">
                        Créer le premier utilisateur
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
