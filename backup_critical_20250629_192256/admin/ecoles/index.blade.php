@extends('layouts.admin')
@section('title', 'Gestion des Écoles')

@section('content')
<div class="space-y-6">
    <!-- Header avec x-module-header -->
    <x-module-header 
        module="ecole"
        title="Gestion des Écoles"
        subtitle="Administration des écoles du réseau"
        create-route="{{ route('admin.ecoles.create') }}"
        create-text="Nouvelle École"
        create-permission="create,App\Models\Ecole"
    />

    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <!-- Formulaire de recherche (GET - NO @csrf) -->
        <form method="GET" action="{{ route('admin.ecoles.index') }}">
            <div class="px-6 py-4 border-b border-slate-700">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <input type="text" 
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Rechercher par nom ou code..."
                               class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-600">
                        @error('search')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div>
                        <select name="province" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white">
                            <option value="">Toutes les provinces</option>
                            <option value="QC" {{ request('province') == 'QC' ? 'selected' : '' }}>Québec</option>
                            <option value="ON" {{ request('province') == 'ON' ? 'selected' : '' }}>Ontario</option>
                            <option value="BC" {{ request('province') == 'BC' ? 'selected' : '' }}>Colombie-Britannique</option>
                        </select>
                        @error('province')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                            Rechercher
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-slate-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase">École</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase">Localisation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse($ecoles as $ecole)
                        <tr class="hover:bg-slate-700/50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 bg-green-600 rounded-lg flex items-center justify-center">
                                        <span class="text-xl text-white">🏫</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-white">{{ $ecole->nom }}</div>
                                        <div class="text-sm text-slate-400">{{ $ecole->code }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-white">{{ $ecole->ville }}, {{ $ecole->province }}</div>
                                <div class="text-sm text-slate-400">{{ $ecole->code_postal }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-white">{{ $ecole->telephone }}</div>
                                <div class="text-sm text-slate-400">{{ $ecole->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($ecole->active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-300">
                                        ✓ Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900 text-red-300">
                                        ✗ Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    @can('view', $ecole)
                                        <a href="{{ route('admin.ecoles.show', $ecole) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-600 hover:bg-green-700 text-white transition-colors"
                                           title="Voir">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('update', $ecole)
                                        <a href="{{ route('admin.ecoles.edit', $ecole) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-600 hover:bg-green-700 text-white transition-colors"
                                           title="Modifier">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="text-slate-400">
                                    <span class="text-5xl mb-4 block">🏫</span>
                                    <h3 class="mt-2 text-sm font-medium text-slate-300">Aucune école trouvée</h3>
                                    @can('create', App\Models\Ecole::class)
                                        <a href="{{ route('admin.ecoles.create') }}" 
                                           class="mt-4 inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                                            Créer une école
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
