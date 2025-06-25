@extends('layouts.admin')
@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="space-y-6">
    <!-- Header avec couleur bleue -->
    <div class="bg-gradient-to-r from-blue-500 to-cyan-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Gestion des Utilisateurs
                </h1>
                <p class="text-blue-100 text-lg">Gestion de vos utilisateurs du réseau</p>
            </div>
            <a href="{{ route('admin.users.create') }}" 
               class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nouvel Utilisateur
            </a>
        </div>
    </div>

    <!-- Métriques avec couleur bleue -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-white">{{ $users->total() }}</div>
                    <div class="text-sm text-slate-400">Total Utilisateurs</div>
                </div>
            </div>
        </div>
        
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-white">{{ $users->where('active', true)->count() }}</div>
                    <div class="text-sm text-slate-400">Actifs</div>
                </div>
            </div>
        </div>
        
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0V9a2 2 0 00-2-2H7a2 2 0 00-2 2v12m7-10V9a2 2 0 00-2-2H9a2 2 0 00-2 2v2m4 4h2m-2 4h2"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-white">{{ $users->filter(function($user) { return $user->hasRole('instructeur'); })->count() }}</div>
                    <div class="text-sm text-slate-400">Instructeurs</div>
                </div>
            </div>
        </div>
        
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-500">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-white">{{ $users->filter(function($user) { return $user->hasRole('membre'); })->count() }}</div>
                    <div class="text-sm text-slate-400">Membres</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Vos Utilisateurs avec couleur bleue -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="bg-blue-600 px-6 py-4">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Vos Utilisateurs
            </h3>
        </div>

        <!-- Barre de recherche -->
        <div class="px-6 py-4 border-b border-slate-700">
            <div class="flex items-center space-x-4">
                <div class="flex-1">
                    <input type="text" 
                           placeholder="Rechercher un utilisateur par nom, email..."
                           class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Rechercher
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-slate-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Utilisateur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">École</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Rôle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-700/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center">
                                        <span class="text-white font-bold text-sm">{{ substr($user->name, 0, 2) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-white">{{ $user->name }}</div>
                                    <div class="text-sm text-slate-400">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-white">{{ $user->ecole->nom ?? 'Non assignée' }}</div>
                            <div class="text-sm text-slate-400">{{ $user->ecole->ville ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->roles->isNotEmpty())
                                @foreach($user->roles as $role)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-900 text-blue-300 mr-1">
                                    {{ $roles[$role->name] ?? ucfirst($role->name) }}
                                </span>
                                @endforeach
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-900 text-gray-300">
                                    Aucun rôle
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($user->active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-300">
                                    ✓ Actif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900 text-red-300">
                                    ✗ Inactif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                <!-- Bouton Voir -->
                                <a href="{{ route('admin.users.show', $user) }}" 
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 hover:bg-blue-700 text-white transition-colors duration-200"
                                   title="Voir les détails">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                
                                <!-- Bouton Modifier -->
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-600 hover:bg-yellow-700 text-white transition-colors duration-200"
                                   title="Modifier">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                
                                <!-- Bouton Supprimer -->
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-600 hover:bg-red-700 text-white transition-colors duration-200"
                                            title="Supprimer"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-slate-400">
                                <svg class="w-16 h-16 mx-auto text-slate-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-slate-300">Aucun utilisateur trouvé</h3>
                                <p class="mt-1 text-sm text-slate-500">Commencez par créer un premier utilisateur.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-slate-700">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
