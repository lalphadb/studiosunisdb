@extends('layouts.admin')

@section('title', 'Gestion des Cours')

@section('breadcrumb')
    <span class="text-violet-400">Cours</span>
@endsection

@section('content')
<x-module-header 
    module="cours"
    title="Gestion des Cours"
    subtitle="Administration des cours et programmes d'enseignement"
    :createRoute="route('admin.cours.create')"
    createText="Nouveau Cours"
    createPermission="cours.create"
    :breadcrumbs="[
        ['name' => 'Admin', 'url' => route('admin.dashboard')],
        ['name' => 'Cours', 'url' => null]
    ]">
    
    <!-- Métriques dans le slot -->
    <x-slot name="metrics">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="studiosdb-card bg-slate-800 border border-slate-700 rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-400 text-sm font-medium uppercase">Cours Actifs</p>
                        <p class="text-3xl font-bold text-green-400">{{ $stats['actifs'] ?? 0 }}</p>
                        <p class="text-slate-500 text-xs">En cours</p>
                    </div>
                    <div class="bg-green-500 bg-opacity-20 p-3 rounded-lg">
                        <i class="fas fa-check-circle text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="studiosdb-card bg-slate-800 border border-slate-700 rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-400 text-sm font-medium uppercase">Cours Inactifs</p>
                        <p class="text-3xl font-bold text-red-400">{{ $stats['inactifs'] ?? 0 }}</p>
                        <p class="text-slate-500 text-xs">Suspendus</p>
                    </div>
                    <div class="bg-red-500 bg-opacity-20 p-3 rounded-lg">
                        <i class="fas fa-times-circle text-red-400 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="studiosdb-card bg-slate-800 border border-slate-700 rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-400 text-sm font-medium uppercase">Total Inscrits</p>
                        <p class="text-3xl font-bold text-blue-400">{{ $stats['inscrits'] ?? 0 }}</p>
                        <p class="text-slate-500 text-xs">Participants</p>
                    </div>
                    <div class="bg-blue-500 bg-opacity-20 p-3 rounded-lg">
                        <i class="fas fa-users text-blue-400 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="studiosdb-card bg-slate-800 border border-slate-700 rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-400 text-sm font-medium uppercase">Sessions cette semaine</p>
                        <p class="text-3xl font-bold text-purple-400">{{ $stats['sessions'] ?? 0 }}</p>
                        <p class="text-slate-500 text-xs">Prévues</p>
                    </div>
                    <div class="bg-purple-500 bg-opacity-20 p-3 rounded-lg">
                        <i class="fas fa-calendar-alt text-purple-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>
</x-module-header>

<x-admin.flash-messages />

<!-- Filtres et recherche -->
<div class="studiosdb-card bg-slate-800 border border-slate-700 rounded-xl p-6 mb-6">
    <form method="GET" action="{{ route('admin.cours.index') }}" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}"
                   placeholder="Rechercher un cours..."
                   class="studiosdb-input w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-200 placeholder-slate-400 focus:border-violet-500 focus:ring-violet-500">
        </div>
        
        <select name="statut" 
                class="studiosdb-input px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-200 focus:border-violet-500 focus:ring-violet-500">
            <option value="">Tous les statuts</option>
            <option value="actif" {{ request('statut') === 'actif' ? 'selected' : '' }}>Actif</option>
            <option value="inactif" {{ request('statut') === 'inactif' ? 'selected' : '' }}>Inactif</option>
        </select>
        
        <button type="submit" 
                class="studiosdb-btn-primary bg-violet-600 hover:bg-violet-700 text-white px-6 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2">
            <i class="fas fa-search"></i>
            <span>Rechercher</span>
        </button>
        
        <a href="{{ route('admin.cours.index') }}" 
           class="studiosdb-btn-secondary bg-slate-600 hover:bg-slate-500 text-slate-200 px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2">
            <i class="fas fa-refresh"></i>
            <span>Nouveau Cours</span>
        </a>
    </form>
</div>

<!-- Table ou empty state -->
@if(isset($cours) && $cours->count() > 0)
    <div class="studiosdb-card bg-slate-800 border border-slate-700 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="studiosdb-table w-full">
                <thead class="bg-slate-700 border-b border-slate-600">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Cours</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Niveau</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Instructeur</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Participants</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-slate-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @foreach($cours as $c)
                        <tr class="hover:bg-slate-700 transition-colors">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-sm font-medium text-slate-200">{{ $c->nom }}</p>
                                    <p class="text-sm text-slate-400">{{ $c->description ?? 'Aucune description' }}</p>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300">
                                    {{ $c->niveau ?? 'Non défini' }}
                                </span>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($c->instructeur)
                                    <div class="flex items-center space-x-2">
                                        <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                            {{ substr($c->instructeur->name, 0, 1) }}
                                        </div>
                                        <span class="text-sm text-slate-300">{{ $c->instructeur->name }}</span>
                                    </div>
                                @else
                                    <span class="text-slate-400 text-sm">Non assigné</span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-slate-300">{{ $c->inscriptions_count ?? 0 }} / {{ $c->limite_participants ?? '∞' }}</span>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($c->actif)
                                    <span class="studiosdb-badge-success">Actif</span>
                                @else
                                    <span class="studiosdb-badge-danger">Inactif</span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <x-module-actions 
                                    :item="$c"
                                    module="cours"
                                    :canView="auth()->user()->can('cours.view')"
                                    :canEdit="auth()->user()->can('cours.edit')"
                                    :canDelete="auth()->user()->can('cours.delete')"
                                    :extraActions="[
                                        [
                                            'url' => route('admin.cours.duplicate', $c),
                                            'icon' => 'fa-copy',
                                            'label' => 'Dupliquer',
                                            'title' => 'Dupliquer ce cours',
                                            'color' => 'indigo'
                                        ]
                                    ]" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if(method_exists($cours, 'links'))
            <div class="bg-slate-700 px-6 py-4 border-t border-slate-600">
                {{ $cours->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@else
    <div class="studiosdb-card bg-slate-800 border border-slate-700 rounded-xl p-6">
        <x-empty-state 
            icon="fa-book-open"
            title="Aucun cours trouvé"
            description="Commencez par créer votre premier cours."
            :action="[
                'text' => 'Créer un cours',
                'url' => route('admin.cours.create'),
                'icon' => 'fa-plus'
            ]" />
    </div>
@endif
@endsection
