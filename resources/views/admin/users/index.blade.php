@extends('layouts.admin')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="admin-content">
    {{-- Header uniforme --}}
    <x-module-header 
        title="Gestion des Membres"
        subtitle="Tous les karatékas du réseau Studios Unis"
        icon="👥"
        gradient="from-blue-500 to-purple-600"
        :create-route="route('admin.users.create')"
        create-text="Nouveau Membre" />

    {{-- Métriques --}}
    @php
        $metrics = [
            [
                'label' => 'Total membres',
                'value' => $users->total(),
                'icon' => '👥',
                'color' => '#3b82f6',
                'subtitle' => 'Tous les membres'
            ],
            [
                'label' => 'Membres Actifs',
                'value' => $users->where('active', true)->count(),
                'icon' => '✅',
                'color' => '#10b981',
                'subtitle' => 'Actifs'
            ],
            [
                'label' => 'Instructeurs',
                'value' => $users->filter(function($user) { return $user->hasRole('instructeur'); })->count(),
                'icon' => '🥋',
                'color' => '#8b5cf6',
                'subtitle' => 'Enseignants'
            ],
            [
                'label' => 'Administrateurs',
                'value' => $users->filter(function($user) { return $user->hasAnyRole(['admin', 'admin_ecole', 'superadmin']); })->count(),
                'icon' => '⚙️',
                'color' => '#f59e0b',
                'subtitle' => 'Gestion'
            ]
        ];
    @endphp
    
    <x-metric-cards :metrics="$metrics" />

    {{-- Section Liste avec header violet comme le module cours --}}
    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
        {{-- Header section comme module cours --}}
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 p-4">
            <h3 class="text-lg font-bold text-white flex items-center">
                <span class="mr-2">📋</span>
                Liste des Membres
            </h3>
        </div>
        
        {{-- Contenu liste --}}
        <div class="p-6">
            @if($users->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Membre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Contact</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">École</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Rôle</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            @foreach($users as $user)
                            <tr class="hover:bg-gray-700">
                                {{-- Membre --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br {{ $loop->index % 3 == 0 ? 'from-blue-500 to-purple-600' : ($loop->index % 3 == 1 ? 'from-purple-500 to-pink-600' : 'from-green-500 to-blue-600') }} rounded-full flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-white">
                                                {{ $user->name }}
                                            </div>
                                            @if($user->date_naissance)
                                                <div class="text-sm text-gray-400">
                                                    {{ $user->date_naissance->age }} ans
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Contact --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-white">
                                        {{ $user->email }}
                                    </div>
                                    @if($user->telephone)
                                        <div class="text-xs text-gray-400">
                                            {{ $user->telephone }}
                                        </div>
                                    @endif
                                </td>

                                {{-- École --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->ecole)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                            🏫 {{ $user->ecole->nom }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-sm">Non assigné</span>
                                    @endif
                                </td>

                                {{-- Rôle --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->roles->isNotEmpty())
                                        @foreach($user->roles as $role)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $role->name === 'superadmin' ? 'bg-red-100 text-red-800' : 
                                                   ($role->name === 'admin' || $role->name === 'admin_ecole' ? 'bg-orange-100 text-orange-800' : 
                                                    ($role->name === 'instructeur' ? 'bg-purple-100 text-purple-800' : 
                                                     'bg-gray-100 text-gray-800')) }}">
                                                {{ $roles[$role->name] ?? ucfirst(str_replace('_', ' ', $role->name)) }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Aucun rôle
                                        </span>
                                    @endif
                                </td>

                                {{-- Statut --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            ✅ Actif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            ❌ Inactif
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions - CORRECTION COMPLÈTE --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <!-- Bouton Voir - toujours visible -->
                                        <a href="{{ route('admin.users.show', $user) }}" 
                                           class="inline-flex items-center justify-center w-9 h-9 bg-blue-600 hover:bg-blue-700 rounded-lg text-white transition duration-150 group" 
                                           title="Voir détails">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </a>
                                        
                                        @can('update', $user)
                                            <!-- Bouton Modifier -->
                                            <a href="{{ route('admin.users.edit', $user) }}" 
                                               class="inline-flex items-center justify-center w-9 h-9 bg-yellow-600 hover:bg-yellow-700 rounded-lg text-white transition duration-150 group" 
                                               title="Modifier">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                                </svg>
                                            </a>
                                        @endcan

                                        @can('delete', $user)
                                            <!-- Bouton Supprimer -->
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" 
                                                  class="inline"
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer {{ $user->name }} ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center justify-center w-9 h-9 bg-red-600 hover:bg-red-700 rounded-lg text-white transition duration-150 group" 
                                                        title="Supprimer">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 012 0v4a1 1 0 11-2 0V7zM12 7a1 1 0 10-2 0v4a1 1 0 102 0V7z" clip-rule="evenodd"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endcan
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
                    <p class="text-xl font-medium text-white mb-2">Aucun membre trouvé</p>
                    <p class="text-gray-400 mb-6">Commencez par créer votre premier membre</p>
                    <a href="{{ route('admin.users.create') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        Créer le premier membre
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
