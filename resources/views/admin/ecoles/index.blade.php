@extends('layouts.admin')

@section('title', 'Gestion des Écoles')

@section('content')
<div class="admin-content">
    <!-- Header avec gradient et actions -->
    <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-xl p-6 text-white shadow-xl mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2 flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.84L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.84l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                    </svg>
                    Studios Unis du Québec
                </h1>
                <p class="text-green-100">
                    @if(auth()->user()->isSuperAdmin())
                        Gestion de l'ensemble des {{ $ecoles->count() }} Studios Unis
                    @else
                        Gestion de votre école
                    @endif
                </p>
            </div>
            
            @can('create-ecole')
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.ecoles.create') }}" class="btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Nouvelle École
                </a>
            </div>
            @endcan
        </div>
    </div>

    <!-- Métriques avec design uniforme -->
    @if(auth()->user()->isSuperAdmin())
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="admin-card p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-500 bg-opacity-20 rounded-full">
                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-400">Total Écoles</h3>
                    <p class="text-2xl font-bold text-white">{{ $metrics['total_ecoles'] }}</p>
                </div>
            </div>
        </div>

        <div class="admin-card p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-500 bg-opacity-20 rounded-full">
                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-400">Total Utilisateurs</h3>
                    <p class="text-2xl font-bold text-white">{{ $metrics['total_users'] }}</p>
                </div>
            </div>
        </div>

        <div class="admin-card p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-500 bg-opacity-20 rounded-full">
                    <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-400">Administrateurs</h3>
                    <p class="text-2xl font-bold text-white">{{ $metrics['total_admins'] }}</p>
                </div>
            </div>
        </div>

        <div class="admin-card p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-500 bg-opacity-20 rounded-full">
                    <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-400">Écoles Actives</h3>
                    <p class="text-2xl font-bold text-white">{{ $metrics['ecoles_actives'] }}</p>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Métriques pour Admin d'école -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="admin-card p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-500 bg-opacity-20 rounded-full">
                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-400">Utilisateurs</h3>
                    <p class="text-2xl font-bold text-white">{{ $metrics['users_ecole'] }}</p>
                </div>
            </div>
        </div>

        <div class="admin-card p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-500 bg-opacity-20 rounded-full">
                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-400">Cours Actifs</h3>
                    <p class="text-2xl font-bold text-white">{{ $metrics['cours_actifs'] }}</p>
                </div>
            </div>
        </div>

        <div class="admin-card p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-500 bg-opacity-20 rounded-full">
                    <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-400">Instructeurs</h3>
                    <p class="text-2xl font-bold text-white">{{ $metrics['instructeurs'] }}</p>
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
                    <h3 class="text-sm font-medium text-gray-400">Membres Actifs</h3>
                    <p class="text-2xl font-bold text-white">{{ $metrics['membres_actifs'] }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Liste des écoles -->
    <div class="admin-card">
        <div class="bg-gradient-to-r from-green-600 to-teal-600 p-4">
            <h3 class="text-lg font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                @if(auth()->user()->isSuperAdmin())
                    Liste des Écoles ({{ $ecoles->count() }})
                @else
                    Votre École
                @endif
            </h3>
        </div>

        <div class="p-6">
            @if($ecoles->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-600">
                            <th class="text-left py-3 px-4 text-gray-300 font-semibold">École</th>
                            <th class="text-left py-3 px-4 text-gray-300 font-semibold">Localisation</th>
                            <th class="text-center py-3 px-4 text-gray-300 font-semibold">Utilisateurs</th>
                            <th class="text-center py-3 px-4 text-gray-300 font-semibold">Cours</th>
                            <th class="text-center py-3 px-4 text-gray-300 font-semibold">Statut</th>
                            <th class="text-center py-3 px-4 text-gray-300 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-600">
                        @foreach($ecoles as $ecole)
                        <tr class="hover:bg-gray-700 transition-colors">
                            <td class="py-4 px-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-teal-400 rounded-lg flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">{{ $ecole->code }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-white font-medium">{{ $ecole->nom }}</div>
                                        <div class="text-gray-400 text-sm">Code: {{ $ecole->code }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="text-white">{{ $ecole->ville }}</div>
                                <div class="text-gray-400 text-sm">{{ $ecole->province }}</div>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <div class="text-white font-semibold">{{ $ecole->users_count }}</div>
                                <div class="text-gray-400 text-sm">utilisateurs</div>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <div class="text-white font-semibold">{{ $ecole->cours_count }}</div>
                                <div class="text-gray-400 text-sm">cours</div>
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($ecole->active)
                                    <span class="badge-success">Actif</span>
                                @else
                                    <span class="badge-danger">Inactif</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin.ecoles.show', $ecole) }}" 
                                       class="text-blue-400 hover:text-blue-300" title="Voir détails">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    
                                    @can('edit-ecole')
                                    <a href="{{ route('admin.ecoles.edit', $ecole) }}" 
                                       class="text-yellow-400 hover:text-yellow-300" title="Modifier">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    @endcan

                                    @can('delete-ecole')
                                    <form method="POST" action="{{ route('admin.ecoles.destroy', $ecole) }}" 
                                          class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette école ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300" title="Supprimer">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
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
            @else
            <div class="text-center py-8">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <h3 class="text-xl font-medium text-gray-300 mb-2">Aucune école trouvée</h3>
                <p class="text-gray-400">Il n'y a actuellement aucune école configurée.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
