@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="admin-content">
    <!-- Header principal -->
    <div class="bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 rounded-xl p-6 text-white shadow-2xl mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-4xl font-bold mb-2">
                    @if(auth()->user()->isSuperAdmin())
                        Dashboard SuperAdmin
                    @elseif(auth()->user()->isAdmin())
                        Dashboard Admin - {{ auth()->user()->ecole?->nom }}
                    @else
                        Tableau de Bord
                    @endif
                </h1>
                <p class="text-blue-100">
                    Bienvenue {{ auth()->user()->name }} - {{ now()->format('d/m/Y H:i') }}
                </p>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="bg-green-500 text-white px-4 py-2 rounded-full text-sm font-medium">
                    🟢 Système Opérationnel
                </span>
            </div>
        </div>
    </div>

    <!-- Métriques principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        @if(auth()->user()->isSuperAdmin())
            <!-- Métriques SuperAdmin -->
            <div class="admin-card p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-500 bg-opacity-20 rounded-full">
                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-400">Total Écoles</h3>
                        <p class="text-2xl font-bold text-white">{{ $metrics['total_ecoles'] ?? 0 }}</p>
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
                        <p class="text-2xl font-bold text-white">{{ $metrics['total_users'] ?? 0 }}</p>
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
                        <p class="text-2xl font-bold text-white">{{ $metrics['total_admins'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="admin-card p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-500 bg-opacity-20 rounded-full">
                        <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-400">Revenus Mois</h3>
                        <p class="text-2xl font-bold text-white">${{ number_format($metrics['revenus_mois'] ?? 0) }}</p>
                    </div>
                </div>
            </div>

        @elseif(auth()->user()->isAdmin())
            <!-- Métriques Admin École -->
            <div class="admin-card p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-500 bg-opacity-20 rounded-full">
                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-400">Utilisateurs École</h3>
                        <p class="text-2xl font-bold text-white">{{ $metrics['users_ecole'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="admin-card p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-500 bg-opacity-20 rounded-full">
                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-400">Membres Actifs</h3>
                        <p class="text-2xl font-bold text-white">{{ $metrics['membres_actifs'] ?? 0 }}</p>
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
                        <p class="text-2xl font-bold text-white">{{ $metrics['instructeurs'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="admin-card p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-500 bg-opacity-20 rounded-full">
                        <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-400">Revenus Mois</h3>
                        <p class="text-2xl font-bold text-white">${{ number_format($metrics['revenus_mois'] ?? 0) }}</p>
                    </div>
                </div>
            </div>

        @else
            <!-- Métriques basiques pour autres rôles -->
            <div class="admin-card p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-500 bg-opacity-20 rounded-full">
                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-400">Mon École</h3>
                        <p class="text-lg font-bold text-white">{{ $metrics['mon_ecole'] ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <div class="admin-card p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-500 bg-opacity-20 rounded-full">
                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-400">Mon Rôle</h3>
                        <p class="text-lg font-bold text-white">{{ ucfirst($metrics['mon_role'] ?? 'N/A') }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Actions rapides -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Navigation modules -->
        <div class="admin-card">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-4">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                    Modules Disponibles
                </h3>
            </div>
            <div class="p-6 space-y-4">
                @can('view-users')
                <a href="{{ route('admin.users.index') }}" class="block p-4 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-white font-medium">Gestion Utilisateurs</h4>
                            <p class="text-gray-400 text-sm">Membres, instructeurs, administrateurs</p>
                        </div>
                    </div>
                </a>
                @endcan

                @can('view-ecoles')
                <a href="{{ route('admin.ecoles.index') }}" class="block p-4 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.84L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.84l-7-3z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-white font-medium">Gestion Écoles</h4>
                            <p class="text-gray-400 text-sm">Studios Unis du Québec</p>
                        </div>
                    </div>
                </a>
                @endcan

                @can('view-ceintures')
                <a href="{{ route('admin.ceintures.index') }}" class="block p-4 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-white font-medium">Gestion Ceintures</h4>
                            <p class="text-gray-400 text-sm">Progression et attribution</p>
                        </div>
                    </div>
                </a>
                @endcan
            </div>
        </div>

        <!-- Activités récentes -->
        <div class="admin-card">
            <div class="bg-gradient-to-r from-orange-600 to-red-600 p-4">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    Activités Récentes
                </h3>
            </div>
            <div class="p-6">
                @if(!empty($recentActivities) && count($recentActivities) > 0)
                    <div class="space-y-4">
                        @foreach($recentActivities as $activity)
                        <div class="flex items-center p-3 bg-gray-700 rounded-lg">
                            <div class="w-2 h-2 bg-blue-400 rounded-full mr-3"></div>
                            <div class="flex-1">
                                <p class="text-white text-sm">{{ $activity['description'] ?? 'Activité' }}</p>
                                <p class="text-gray-400 text-xs">{{ $activity['time'] ?? 'Récemment' }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h4 class="text-gray-300 font-medium">Aucune activité récente</h4>
                        <p class="text-gray-400 text-sm">Les activités apparaîtront ici</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
