@extends('layouts.admin')

@section('title', 'Gestion des Membres')

@section('content')
<div class="space-y-6">
    {{-- Header avec gradient moderne --}}
    <div class="relative bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 rounded-xl p-6 text-white overflow-hidden shadow-2xl border border-gray-700">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white opacity-5 rounded-full -ml-16 -mb-16"></div>
        <div class="relative flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold mb-2">👥 Gestion des Membres</h1>
                <p class="text-blue-100 text-lg">
                    @if(auth()->user()->hasRole('superadmin'))
                        Tous les karatékas du réseau Studios Unis
                    @elseif(auth()->user()->ecole)
                        École {{ auth()->user()->ecole->nom }} - {{ $membres->total() ?? 0 }} membres
                    @else
                        Gestion des membres karatékas
                    @endif
                </p>
            </div>
            <div class="flex space-x-3">
                @can('create-membre')
                <a href="{{ route('admin.membres.create') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-4 py-2 rounded-lg font-medium transition-all flex items-center space-x-2">
                    <span>➕</span>
                    <span>Nouveau membre</span>
                </a>
                @endcan
            </div>
        </div>
    </div>

    {{-- Statistiques rapides --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card-modern p-4 text-center">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mx-auto mb-3">
                <span class="text-xl">👥</span>
            </div>
            <p class="text-2xl font-bold text-white">{{ $stats['total'] ?? 0 }}</p>
            <p class="text-gray-400 text-sm">Membres actifs</p>
        </div>
        
        <div class="card-modern p-4 text-center">
            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mx-auto mb-3">
                <span class="text-xl">🥋</span>
            </div>
            <p class="text-2xl font-bold text-white">{{ $stats['instructeurs'] ?? 0 }}</p>
            <p class="text-gray-400 text-sm">Instructeurs</p>
        </div>
        
        <div class="card-modern p-4 text-center">
            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mx-auto mb-3">
                <span class="text-xl">➕</span>
            </div>
            <p class="text-2xl font-bold text-white">{{ $stats['nouveaux'] ?? 0 }}</p>
            <p class="text-gray-400 text-sm">Ce mois</p>
        </div>
        
        <div class="card-modern p-4 text-center">
            <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg flex items-center justify-center mx-auto mb-3">
                <span class="text-xl">🎯</span>
            </div>
            <p class="text-2xl font-bold text-white">{{ number_format($stats['presence_rate'] ?? 0, 1) }}%</p>
            <p class="text-gray-400 text-sm">Présence moy.</p>
        </div>
    </div>

    {{-- Table des membres --}}
    <div class="card-modern overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-4">
            <h3 class="text-xl font-bold text-white flex items-center">
                <span class="text-2xl mr-3">📋</span>
                Liste des membres 
                @if(isset($membres) && $membres->count() > 0)
                    ({{ $membres->count() }} résultats)
                @endif
            </h3>
        </div>
        
        @if(isset($membres) && $membres->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Membre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">École</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Rôle</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-600">
                    @foreach($membres as $membre)
                    <tr class="hover:bg-gray-700 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold">
                                        {{ strtoupper(substr($membre->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="font-medium text-white">{{ $membre->name }}</p>
                                    <p class="text-gray-400 text-sm">
                                        @if($membre->date_naissance)
                                            {{ \Carbon\Carbon::parse($membre->date_naissance)->age }} ans
                                        @endif
                                        @if($membre->sexe)
                                            • {{ $membre->sexe == 'M' ? 'Homme' : ($membre->sexe == 'F' ? 'Femme' : 'Autre') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-white">{{ $membre->email }}</p>
                            @if($membre->telephone)
                                <p class="text-gray-400 text-sm">{{ $membre->telephone }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($membre->ecole)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-600 text-white">
                                    {{ $membre->ecole->nom }}
                                </span>
                            @else
                                <span class="text-gray-400">Non assigné</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @foreach($membre->roles as $role)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs mr-1
                                    @if($role->name == 'superadmin') bg-red-600 text-white
                                    @elseif($role->name == 'admin') bg-orange-600 text-white
                                    @elseif($role->name == 'instructeur') bg-green-600 text-white
                                    @else bg-gray-600 text-white
                                    @endif">
                                    {{ ucfirst($role->name) }}
                                </span>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                @can('view-membres')
                                <a href="{{ route('admin.membres.show', $membre) }}" 
                                   class="text-blue-400 hover:text-blue-300 transition-colors">
                                    👁️
                                </a>
                                @endcan
                                
                                @can('edit-membre')
                                <a href="{{ route('admin.membres.edit', $membre) }}" 
                                   class="text-green-400 hover:text-green-300 transition-colors">
                                    ✏️
                                </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-12 text-center">
            <span class="text-6xl">👥</span>
            <h3 class="text-xl font-medium text-white mt-4">Aucun membre trouvé</h3>
            <p class="text-gray-400 mt-2">Commencez par ajouter des membres à votre école.</p>
            @can('create-membre')
            <a href="{{ route('admin.membres.create') }}" 
               class="inline-flex items-center px-4 py-2 mt-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                <span class="mr-2">➕</span>
                Ajouter le premier membre
            </a>
            @endcan
        </div>
        @endif
    </div>
</div>
@endsection
