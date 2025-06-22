@extends('layouts.admin')

@section('title', 'Dashboard StudiosUnisDB')

@section('content')
<div class="space-y-6">
    <!-- Header adaptatif selon le rôle -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-6 text-white shadow-xl">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold flex items-center">
                    <span class="mr-3">📊</span>
                    Dashboard StudiosUnisDB
                </h1>
                <p class="text-blue-100 text-lg mt-2">
                    Bienvenue {{ $userInfo['name'] }} - {{ ucfirst($userInfo['role']) }}
                    @if($userInfo['ecole'] !== 'Global') 
                        ({{ $userInfo['ecole'] }})
                    @endif
                </p>
            </div>
            <div class="text-right">
                <div class="text-blue-100 text-sm">{{ now()->format('d/m/Y H:i') }}</div>
                <div class="text-blue-200 text-xs">StudiosUnisDB v4.0-FINAL</div>
            </div>
        </div>
    </div>

    <!-- Métriques adaptées selon le rôle -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Utilisateurs -->
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">
                        @if(auth()->user()->hasRole('superadmin'))
                            Utilisateurs Total
                        @else
                            Membres École
                        @endif
                    </p>
                    <p class="text-3xl font-bold">{{ $stats['total_users'] }}</p>
                    <p class="text-blue-200 text-xs">Actifs</p>
                </div>
                <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-white bg-opacity-20">
                    <span class="text-2xl">👥</span>
                </div>
            </div>
        </div>

        <!-- Écoles -->
        <div class="bg-gradient-to-br from-green-600 to-green-700 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">
                        @if(auth()->user()->hasRole('superadmin'))
                            Écoles Total
                        @else
                            Mon École
                        @endif
                    </p>
                    <p class="text-3xl font-bold">{{ $stats['total_ecoles'] }}</p>
                    <p class="text-green-200 text-xs">
                        @if(auth()->user()->hasRole('superadmin'))
                            Studios Unis QC
                        @else
                            {{ auth()->user()->ecole->nom ?? 'N/A' }}
                        @endif
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-white bg-opacity-20">
                    <span class="text-2xl">🏯</span>
                </div>
            </div>
        </div>

        <!-- Cours -->
        <div class="bg-gradient-to-br from-purple-600 to-purple-700 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Cours Disponibles</p>
                    <p class="text-3xl font-bold">{{ $stats['total_cours'] }}</p>
                    <p class="text-purple-200 text-xs">Programmes</p>
                </div>
                <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-white bg-opacity-20">
                    <span class="text-2xl">📚</span>
                </div>
            </div>
        </div>

        <!-- Revenus -->
        <div class="bg-gradient-to-br from-yellow-600 to-orange-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm">Revenus ce mois</p>
                    <p class="text-3xl font-bold">${{ number_format($stats['paiements_mois'], 2) }}</p>
                    <p class="text-yellow-200 text-xs">{{ date('F Y') }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-white bg-opacity-20">
                    <span class="text-2xl">💰</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 p-4">
            <h3 class="text-lg font-bold text-white flex items-center">
                <span class="mr-2">⚡</span>
                Actions Rapides
            </h3>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <a href="{{ route('admin.users.index') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-lg transition-colors flex items-center space-x-3">
                    <span class="text-2xl">👥</span>
                    <div>
                        <div class="font-medium">Gérer Utilisateurs</div>
                        <div class="text-blue-200 text-sm">{{ $stats['total_users'] }} membres</div>
                    </div>
                </a>
                
                <a href="{{ route('admin.ceintures.index') }}" 
                   class="bg-yellow-600 hover:bg-yellow-700 text-white p-4 rounded-lg transition-colors flex items-center space-x-3">
                    <span class="text-2xl">🥋</span>
                    <div>
                        <div class="font-medium">Gérer Ceintures</div>
                        <div class="text-yellow-200 text-sm">Progressions</div>
                    </div>
                </a>
                
                <a href="{{ route('admin.ecoles.index') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white p-4 rounded-lg transition-colors flex items-center space-x-3">
                    <span class="text-2xl">🏯</span>
                    <div>
                        <div class="font-medium">Gérer Écoles</div>
                        <div class="text-green-200 text-sm">{{ $stats['total_ecoles'] }} studios</div>
                    </div>
                </a>
                
                <div class="bg-purple-600 opacity-50 text-white p-4 rounded-lg flex items-center space-x-3">
                    <span class="text-2xl">📚</span>
                    <div>
                        <div class="font-medium">Cours</div>
                        <div class="text-purple-200 text-sm">Bientôt disponible</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section SuperAdmin -->
    @if(auth()->user()->hasRole('superadmin'))
    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
        <div class="bg-gradient-to-r from-red-600 to-pink-600 p-4">
            <h3 class="text-lg font-bold text-white flex items-center">
                <span class="mr-2">⚙️</span>
                Administration Système
            </h3>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('admin.ecoles.index') }}" 
                   class="bg-gray-700 hover:bg-gray-600 text-white p-4 rounded-lg transition-colors">
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl">🏯</span>
                        <div>
                            <div class="font-medium">Gérer Écoles</div>
                            <div class="text-gray-400 text-sm">{{ $stats['total_ecoles'] }} Studios Unis</div>
                        </div>
                    </div>
                </a>
                
                <a href="/telescope" target="_blank"
                   class="bg-gray-700 hover:bg-gray-600 text-white p-4 rounded-lg transition-colors">
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl">🔍</span>
                        <div>
                            <div class="font-medium">Telescope</div>
                            <div class="text-gray-400 text-sm">Monitoring système</div>
                        </div>
                    </div>
                </a>
                
                <div class="bg-gray-700 p-4 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl">📈</span>
                        <div>
                            <div class="font-medium text-white">Système</div>
                            <div class="text-gray-400 text-sm">Laravel 12.19.3</div>
                            <div class="text-gray-400 text-sm">PHP {{ phpversion() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
