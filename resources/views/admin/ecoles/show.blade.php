@extends('layouts.admin')

@section('title', 'Détails de l\'École')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h1 class="text-3xl font-bold text-white flex items-center">
                    <span class="w-10 h-10 bg-gradient-to-br from-green-400 to-teal-400 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-white font-bold text-sm">{{ $ecole->code ?? 'EC' }}</span>
                    </span>
                    {{ $ecole->nom }}
                </h1>
                <p class="mt-1 text-sm text-gray-300">
                    Détails et statistiques de l'école
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                @can('update', $ecole)
                <a href="{{ route('admin.ecoles.edit', $ecole) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 transition">
                    ✏️ Modifier
                </a>
                @endcan
                <a href="{{ route('admin.ecoles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                    ← Retour Liste
                </a>
            </div>
        </div>

        <!-- Messages -->
        @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
        @endif

        <!-- Métriques de l'école -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-500 bg-opacity-20 rounded-full">
                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-400">Total Utilisateurs</h3>
                        <p class="text-2xl font-bold text-white">{{ $ecole->users()->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-500 bg-opacity-20 rounded-full">
                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-400">Cours Actifs</h3>
                        <p class="text-2xl font-bold text-white">{{ $ecole->cours()->where('active', true)->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-500 bg-opacity-20 rounded-full">
                        <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-400">Administrateurs</h3>
                        <p class="text-2xl font-bold text-white">{{ $ecole->users()->whereHas('roles', function($q) { $q->whereIn('name', ['admin', 'superadmin']); })->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-500 bg-opacity-20 rounded-full">
                        <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-400">Statut</h3>
                        <p class="text-lg font-bold {{ $ecole->active ? 'text-green-400' : 'text-red-400' }}">
                            {{ $ecole->active ? 'Actif' : 'Inactif' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Informations de l'école -->
            <div class="bg-gray-800 rounded-lg border border-gray-700">
                <div class="px-6 py-4 border-b border-gray-700">
                    <h3 class="text-lg font-medium text-white">Informations de l'École</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm text-gray-400">Code:</span>
                            <p class="text-white font-medium">{{ $ecole->code ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-400">Statut:</span>
                            <p class="text-white font-medium">
                                @if($ecole->active)
                                    <span class="text-green-400">✅ Actif</span>
                                @else
                                    <span class="text-red-400">❌ Inactif</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div>
                        <span class="text-sm text-gray-400">Adresse:</span>
                        <p class="text-white">{{ $ecole->adresse ?? 'N/A' }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm text-gray-400">Ville:</span>
                            <p class="text-white">{{ $ecole->ville ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-400">Province:</span>
                            <p class="text-white">{{ $ecole->province ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm text-gray-400">Code postal:</span>
                            <p class="text-white">{{ $ecole->code_postal ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-400">Téléphone:</span>
                            <p class="text-white">{{ $ecole->telephone ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm text-gray-400">Email:</span>
                            <p class="text-white">{{ $ecole->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-400">Site web:</span>
                            <p class="text-white">
                                @if($ecole->site_web)
                                    <a href="{{ $ecole->site_web }}" target="_blank" class="text-blue-400 hover:text-blue-300">{{ $ecole->site_web }}</a>
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($ecole->directeur)
                    <div>
                        <span class="text-sm text-gray-400">Directeur:</span>
                        <p class="text-white">{{ $ecole->directeur }}</p>
                    </div>
                    @endif

                    @if($ecole->capacite_max)
                    <div>
                        <span class="text-sm text-gray-400">Capacité maximale:</span>
                        <p class="text-white">{{ $ecole->capacite_max }} personnes</p>
                    </div>
                    @endif

                    @if($ecole->description)
                    <div>
                        <span class="text-sm text-gray-400">Description:</span>
                        <p class="text-white">{{ $ecole->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Utilisateurs de l'école -->
            <div class="bg-gray-800 rounded-lg border border-gray-700">
                <div class="px-6 py-4 border-b border-gray-700">
                    <h3 class="text-lg font-medium text-white">Utilisateurs de l'École</h3>
                </div>
                <div class="p-6">
                    @if($ecole->users->count() > 0)
                        <div class="space-y-4">
                            @foreach($ecole->users->take(10) as $user)
                            <div class="flex items-center justify-between py-3 border-b border-gray-700 last:border-b-0">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-400 rounded-full flex items-center justify-center">
                                        <span class="text-white font-bold text-sm">{{ substr($user->name, 0, 2) }}</span>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-white font-medium">{{ $user->name }}</p>
                                        <p class="text-gray-400 text-sm">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if($user->roles->count() > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $user->roles->first()->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                            
                            @if($ecole->users->count() > 10)
                            <p class="text-gray-400 text-sm text-center pt-4">
                                ... et {{ $ecole->users->count() - 10 }} autres utilisateurs
                            </p>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                            <h3 class="text-xl font-medium text-gray-300 mb-2">Aucun utilisateur</h3>
                            <p class="text-gray-400">Cette école n'a pas encore d'utilisateurs inscrits.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Métadonnées -->
        <div class="mt-8 bg-gray-800 rounded-lg border border-gray-700 p-6">
            <h3 class="text-lg font-medium text-white mb-4">📊 Métadonnées</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="text-gray-400">Créée le:</span>
                    <span class="text-white ml-2">{{ $ecole->created_at ? $ecole->created_at->format('d/m/Y à H:i') : 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-gray-400">Dernière modification:</span>
                    <span class="text-white ml-2">{{ $ecole->updated_at ? $ecole->updated_at->format('d/m/Y à H:i') : 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-gray-400">ID:</span>
                    <span class="text-white ml-2">#{{ $ecole->id }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
