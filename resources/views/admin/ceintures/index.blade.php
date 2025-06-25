@extends('layouts.admin')
@section('title', 'Gestion des Ceintures')

@section('content')
<div class="space-y-6">
    <!-- Header avec couleur orange -->
    <div class="bg-gradient-to-r from-orange-500 to-red-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z"/>
                    </svg>
                    Gestion des Ceintures
                </h1>
                <p class="text-orange-100 text-lg">Système de progression et grades karaté</p>
            </div>
            <a href="{{ route('admin.ceintures.create') }}" 
               class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nouvelle Ceinture
            </a>
        </div>
    </div>

    <!-- Métriques avec couleur orange -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-600">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-white">{{ $totalCeintures }}</div>
                    <div class="text-sm text-slate-400">Total Ceintures</div>
                </div>
            </div>
        </div>
        
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-white">{{ $totalProgressions }}</div>
                    <div class="text-sm text-slate-400">Progressions</div>
                </div>
            </div>
        </div>
        
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-white">{{ $totalUtilisateursAvecCeinture }}</div>
                    <div class="text-sm text-slate-400">Utilisateurs Gradés</div>
                </div>
            </div>
        </div>
        
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-yellow-600">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-white">{{ $ceinturesActives }}</div>
                    <div class="text-sm text-slate-400">Ceintures Actives</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Vos Ceintures -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="bg-orange-600 px-6 py-4">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z"/>
                </svg>
                Système de Ceintures
            </h3>
        </div>

        <!-- Barre de recherche -->
        <div class="px-6 py-4 border-b border-slate-700">
            <div class="flex items-center space-x-4">
                <div class="flex-1">
                    <input type="text" 
                           placeholder="Rechercher une ceinture..."
                           class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>
                <button class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg font-medium transition duration-200 flex items-center">
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Ordre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Ceinture</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Couleur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Progressions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse($ceintures as $ceinture)
                    <tr class="hover:bg-slate-700/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center">
                                        <span class="text-white font-bold text-sm">{{ $ceinture->ordre }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-white">Niveau {{ $ceinture->ordre }}</div>
                                    <div class="text-sm text-slate-400">{{ $ceinture->ordre <= 10 ? 'Kyu' : 'Dan' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-white">{{ $ceinture->nom }}</div>
                            @if($ceinture->description)
                                <div class="text-sm text-slate-400">{{ Str::limit($ceinture->description, 50) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full mr-2" style="background-color: {{ $ceinture->couleur }}"></div>
                                <span class="text-sm text-white">{{ ucfirst($ceinture->couleur) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $count = $progressions->get($ceinture->id)?->count() ?? 0;
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $count > 0 ? 'bg-green-900 text-green-300' : 'bg-gray-900 text-gray-300' }}">
                                {{ $count }} utilisateur{{ $count > 1 ? 's' : '' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                <!-- Bouton Voir -->
                                <a href="{{ route('admin.ceintures.show', $ceinture) }}" 
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 hover:bg-blue-700 text-white transition-colors duration-200"
                                   title="Voir les détails">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                
                                <!-- Bouton Modifier -->
                                <a href="{{ route('admin.ceintures.edit', $ceinture) }}" 
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-600 hover:bg-yellow-700 text-white transition-colors duration-200"
                                   title="Modifier">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>

                                <!-- Bouton Attribuer -->
                                <a href="{{ route('admin.ceintures.attribuer', $ceinture) }}" 
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-600 hover:bg-green-700 text-white transition-colors duration-200"
                                   title="Attribuer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </a>
                                
                                <!-- Bouton Supprimer -->
                                <form method="POST" action="{{ route('admin.ceintures.destroy', $ceinture) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-600 hover:bg-red-700 text-white transition-colors duration-200"
                                            title="Supprimer"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette ceinture ?')">
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
                                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-slate-300">Aucune ceinture trouvée</h3>
                                <p class="mt-1 text-sm text-slate-500">Commencez par créer votre première ceinture.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($ceintures->hasPages())
        <div class="px-6 py-4 border-t border-slate-700">
            {{ $ceintures->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
