@extends('layouts.admin')

@section('title', 'Gestion des Membres')

@section('content')
<div class="space-y-6">
    {{-- Header moderne avec gradient --}}
    <div class="relative bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 rounded-lg p-6 text-white overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white opacity-5 rounded-full -ml-16 -mb-16"></div>
        
        <div class="relative flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold mb-2">👥 Gestion des Membres</h1>
                <p class="text-blue-100 text-lg">Membres du réseau Studios Unis</p>
            </div>
            <a href="{{ route('admin.membres.create') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-6 py-3 rounded-lg font-medium transition-all duration-300 backdrop-blur-sm border border-white border-opacity-30">
                ➕ Nouveau Membre
            </a>
        </div>
    </div>

    {{-- Liste des membres avec style Studios Unis --}}
    <div class="bg-gray-800 rounded-lg shadow-2xl border border-gray-700 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-cyan-500 p-4">
            <h3 class="text-xl font-bold text-white">📋 Liste des Membres ({{ $membres->total() }} membres)</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-900">
                    <tr>
                        <th class="text-left px-6 py-4 font-bold text-blue-400 uppercase tracking-wider">Membre</th>
                        <th class="text-left px-6 py-4 font-bold text-green-400 uppercase tracking-wider">École</th>
                        <th class="text-left px-6 py-4 font-bold text-yellow-400 uppercase tracking-wider">Contact</th>
                        <th class="text-left px-6 py-4 font-bold text-purple-400 uppercase tracking-wider">Statut</th>
                        <th class="text-center px-6 py-4 font-bold text-pink-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($membres as $membre)
                    <tr class="hover:bg-gray-700 transition-colors">
                        {{-- Membre --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-4">
                                {{-- Avatar avec initiales --}}
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-500 rounded-lg flex items-center justify-center text-white font-bold">
                                    {{ $membre->initiales }}
                                </div>
                                <div>
                                    <div class="font-bold text-white text-lg">{{ $membre->nom_complet }}</div>
                                    <div class="text-gray-400 text-sm">
                                        📅 Inscrit le {{ $membre->date_inscription->format('d/m/Y') }}
                                        @if($membre->age)
                                            • {{ $membre->age }} ans
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- École --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-white font-medium">{{ $membre->ecole->nom ?? 'N/A' }}</div>
                            @if($membre->ecole)
                                <div class="text-gray-400 text-sm">{{ $membre->ecole->ville ?? '' }}</div>
                            @endif
                        </td>

                        {{-- Contact --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="space-y-1">
                                <div class="text-white text-sm">
                                    📧 {{ $membre->email ?: 'Pas d\'email' }}
                                </div>
                                <div class="text-gray-400 text-sm">
                                    📞 {{ $membre->telephone ?: 'Pas de téléphone' }}
                                </div>
                            </div>
                        </td>

                        {{-- Statut --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($membre->active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-500 bg-opacity-20 border border-green-500 text-green-400">
                                    ✅ Actif
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-red-500 bg-opacity-20 border border-red-500 text-red-400">
                                    ❌ Inactif
                                </span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex justify-center space-x-3">
                                {{-- Voir --}}
                                <a href="{{ route('admin.membres.show', $membre) }}" 
                                   class="text-blue-400 hover:text-blue-300 transition-colors text-xl hover:scale-110 transform duration-200" 
                                   title="Voir le profil">
                                    👁️
                                </a>
                                
                                {{-- Modifier --}}
                                @can('edit-membre')
                                <a href="{{ route('admin.membres.edit', $membre) }}" 
                                   class="text-green-400 hover:text-green-300 transition-colors text-xl hover:scale-110 transform duration-200" 
                                   title="Modifier">
                                    ✏️
                                </a>
                                @endcan
                                
                                {{-- Attribuer Ceinture --}}
                                @can('assign-ceintures')
                                <a href="{{ route('admin.ceintures.create', ['membre_id' => $membre->id]) }}" 
                                   class="text-yellow-400 hover:text-yellow-300 transition-colors text-xl hover:scale-110 transform duration-200" 
                                   title="Attribuer ceinture">
                                    🥋
                                </a>
                                @endcan
                                
                                {{-- Supprimer (SuperAdmin seulement) --}}
                                @if(auth()->user()->hasRole('superadmin'))
                                <form method="POST" action="{{ route('admin.membres.destroy', $membre) }}" 
                                      onsubmit="return confirm('⚠️ ATTENTION !\n\nSupprimer {{ $membre->nom_complet }} supprimera définitivement :\n• Toutes ses progressions de ceintures\n• Toutes ses présences\n• Tous ses paiements\n• Toutes ses inscriptions\n\nCette action est IRRÉVERSIBLE !\n\nTapez OUI en majuscules pour confirmer.')" 
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-500 hover:text-red-400 transition-colors text-xl hover:scale-110 transform duration-200" 
                                            title="Supprimer définitivement">
                                        🗑️
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-6xl mb-4">👥</div>
                            <p class="text-xl font-medium text-white">Aucun membre trouvé</p>
                            <p class="text-gray-400 mt-2">Commencez par créer votre premier membre</p>
                            <a href="{{ route('admin.membres.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                ➕ Créer un membre
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($membres->hasPages())
        <div class="px-6 py-4 border-t border-gray-700">
            {{ $membres->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
