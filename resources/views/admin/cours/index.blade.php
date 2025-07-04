@extends('layouts.admin')

@section('title', 'Gestion des Cours')

@section('content')
<x-module-header 
    title="Cours" 
    :breadcrumbs="[
        ['name' => 'Admin', 'url' => route('admin.dashboard')],
        ['name' => 'Cours', 'url' => null]
    ]"
    color="emerald-600" />

<div class="studiosdb-content">
    <x-admin.flash-messages />

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="studiosdb-card border-l-4 border-emerald-500 bg-green-500/10">
            <div class="studiosdb-card-body">
                <div class="flex items-center">
                    <i class="fas fa-graduation-cap text-emerald-500 text-2xl mr-3"></i>
                    <div>
                        <span class="text-green-400 text-xl mr-3">✓</span>
                        <span class="text-green-400 text-xl font-bold">{{ $stats['cours_actifs'] ?? 0 }}</span>
                        <p class="text-sm text-gray-400">Cours Actifs</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="studiosdb-card border-l-4 border-red-500 bg-red-500/10">
            <div class="studiosdb-card-body">
                <div class="flex items-center">
                    <i class="fas fa-pause-circle text-red-500 text-2xl mr-3"></i>
                    <div>
                        <span class="text-red-400 text-xl mr-3">✗</span>
                        <span class="text-red-400 text-xl font-bold">{{ $stats['cours_inactifs'] ?? 0 }}</span>
                        <p class="text-sm text-gray-400">Cours Inactifs</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="studiosdb-card border-l-4 border-blue-500 bg-blue-500/10">
            <div class="studiosdb-card-body">
                <div class="flex items-center">
                    <i class="fas fa-users text-blue-500 text-2xl mr-3"></i>
                    <div>
                        <span class="text-blue-400 text-xl font-bold">{{ $stats['total_inscrits'] ?? 0 }}</span>
                        <p class="text-sm text-gray-400">Total Inscrits</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="studiosdb-card border-l-4 border-purple-500 bg-purple-500/10">
            <div class="studiosdb-card-body">
                <div class="flex items-center">
                    <i class="fas fa-calendar text-purple-500 text-2xl mr-3"></i>
                    <div>
                        <span class="text-purple-400 text-xl font-bold">{{ $stats['sessions_semaine'] ?? 0 }}</span>
                        <p class="text-sm text-gray-400">Sessions cette semaine</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="studiosdb-card mb-6">
        <div class="studiosdb-card-body">
            <form method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-64">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Rechercher un cours..."
                           class="studiosdb-input">
                </div>
                
                <div>
                    <select name="statut" class="studiosdb-select">
                        <option value="">Tous les statuts</option>
                        <option value="actif" {{ request('statut') === 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="inactif" {{ request('statut') === 'inactif' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>
                
                <button type="submit" class="studiosdb-btn-primary">
                    <i class="fas fa-search mr-2"></i>
                    Rechercher
                </button>
                
                @can('cours.create')
                <a href="{{ route('admin.cours.create') }}" class="studiosdb-btn-success">
                    <i class="fas fa-plus mr-2"></i>
                    Nouveau Cours
                </a>
                @endcan
            </form>
        </div>
    </div>

    <!-- Table des cours -->
    @if($cours->count() > 0)
        <div class="studiosdb-table-container">
            <table class="studiosdb-table">
                <thead>
                    <tr>
                        <th>Nom du Cours</th>
                        <th>Description</th>
                        <th>Statut</th>
                        <th>Inscrits</th>
                        <th>Créé le</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cours as $cour)
                    <tr>
                        <td>
                            <div class="font-medium text-white">{{ $cour->nom }}</div>
                            @if($cour->code)
                                <div class="text-sm text-gray-400">Code: {{ $cour->code }}</div>
                            @endif
                        </td>
                        <td>
                            <div class="text-sm text-gray-300 max-w-md truncate">
                                {{ $cour->description ?? 'Aucune description' }}
                            </div>
                        </td>
                        <td>
                            @if($cour->statut === 'actif')
                                <span class="studiosdb-badge-success">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Actif
                                </span>
                            @else
                                <span class="studiosdb-badge-danger">
                                    <i class="fas fa-pause-circle mr-1"></i>
                                    Inactif
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="text-blue-400 font-medium">
                                {{ $cour->inscriptions_count ?? 0 }}
                            </span>
                        </td>
                        <td class="text-gray-400 text-sm">
                            {{ $cour->created_at->format('d/m/Y') }}
                        </td>
                        <td class="text-center">
                            <div class="flex justify-center space-x-2">
                                @can('cours.view')
                                <a href="{{ route('admin.cours.show', $cour) }}" 
                                   class="text-blue-400 hover:text-blue-300">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endcan
                                
                                @can('cours.edit')
                                <a href="{{ route('admin.cours.edit', $cour) }}" 
                                   class="text-yellow-400 hover:text-yellow-300">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                
                                @can('cours.delete')
                                <form method="POST" action="{{ route('admin.cours.destroy', $cour) }}" 
                                      class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-400 hover:text-red-300"
                                            onclick="return confirm('Confirmer la suppression ?')">
                                        <i class="fas fa-trash"></i>
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

        <!-- Pagination -->
        <div class="mt-6">
            {{ $cours->links() }}
        </div>
    @else
        <x-empty-state 
            icon="fas fa-graduation-cap"
            title="Aucun cours trouvé"
            description="Commencez par créer votre premier cours."
            :action="auth()->user()->can('cours.create') ? ['text' => 'Créer un cours', 'url' => route('admin.cours.create')] : null" />
    @endif
</div>
@endsection
