@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-slate-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-white">🎓 Séminaires</h1>
                <p class="text-slate-400 mt-2">Gestion des événements et formations spécialisées</p>
            </div>
            <a href="{{ route('admin.seminaires.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                + Nouveau Séminaire
            </a>
        </div>

        <!-- Filtres -->
        <div class="bg-slate-800 rounded-lg p-6 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Type</label>
                    <select name="type" class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-2">
                        <option value="">Tous les types</option>
                        <option value="technique">Technique</option>
                        <option value="kata">Kata</option>
                        <option value="competition">Compétition</option>
                        <option value="arbitrage">Arbitrage</option>
                        <option value="grade">Passage de Grade</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Statut</label>
                    <select name="statut" class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-2">
                        <option value="">Tous les statuts</option>
                        <option value="planifie">Planifié</option>
                        <option value="ouvert">Ouvert</option>
                        <option value="complet">Complet</option>
                        <option value="termine">Terminé</option>
                        <option value="annule">Annulé</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">École</label>
                    <select name="ecole_id" class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-2">
                        <option value="">Toutes les écoles</option>
                        @foreach($ecoles as $ecole)
                        <option value="{{ $ecole->id }}">{{ $ecole->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-slate-600 hover:bg-slate-500 text-white px-6 py-2 rounded-lg">
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-slate-800 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-400 text-sm">Total Séminaires</p>
                        <p class="text-2xl font-bold text-white">{{ $seminaires->count() }}</p>
                    </div>
                    <div class="bg-blue-500/20 p-3 rounded-full">
                        <span class="text-2xl">🎓</span>
                    </div>
                </div>
            </div>
            <div class="bg-slate-800 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-400 text-sm">Ouverts</p>
                        <p class="text-2xl font-bold text-green-400">{{ $seminaires->where('statut', 'ouvert')->count() }}</p>
                    </div>
                    <div class="bg-green-500/20 p-3 rounded-full">
                        <span class="text-2xl">✅</span>
                    </div>
                </div>
            </div>
            <div class="bg-slate-800 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-400 text-sm">Participants</p>
                        <p class="text-2xl font-bold text-purple-400">{{ $totalParticipants }}</p>
                    </div>
                    <div class="bg-purple-500/20 p-3 rounded-full">
                        <span class="text-2xl">👥</span>
                    </div>
                </div>
            </div>
            <div class="bg-slate-800 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-400 text-sm">Ce Mois</p>
                        <p class="text-2xl font-bold text-yellow-400">{{ $seminairesMois }}</p>
                    </div>
                    <div class="bg-yellow-500/20 p-3 rounded-full">
                        <span class="text-2xl">📅</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des Séminaires -->
        <div class="bg-slate-800 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Séminaire</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Participants</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        @forelse($seminaires as $seminaire)
                        <tr class="hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <div>
                                    <div class="text-white font-medium">{{ $seminaire->titre }}</div>
                                    <div class="text-slate-400 text-sm">{{ $seminaire->ecole?->nom ?? 'École non définie' }}</div>   
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @switch($seminaire->type)
                                        @case('technique') bg-blue-100 text-blue-800 @break
                                        @case('kata') bg-purple-100 text-purple-800 @break
                                        @case('competition') bg-red-100 text-red-800 @break
                                        @case('arbitrage') bg-yellow-100 text-yellow-800 @break
                                        @case('grade') bg-green-100 text-green-800 @break
                                        @default bg-gray-100 text-gray-800
                                    @endswitch">
                                    @switch($seminaire->type)
                                        @case('technique') 🥋 Technique @break
                                        @case('kata') 🎭 Kata @break
                                        @case('competition') 🏆 Compétition @break
                                        @case('arbitrage') ⚖️ Arbitrage @break
                                        @case('grade') 🎓 Grade @break
                                        @default {{ ucfirst($seminaire->type) }}
                                    @endswitch
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-300">
                                <div>{{ $seminaire->date_debut->format('d/m/Y') }}</div>
                                <div class="text-sm text-slate-400">{{ $seminaire->date_debut->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-300">
                                <div class="flex items-center">
                                    <span class="text-white font-medium">{{ $seminaire->inscriptions->count() }}</span>
                                    @if($seminaire->max_participants)
                                    <span class="text-slate-400 ml-1">/ {{ $seminaire->max_participants }}</span>
                                    @endif
                                </div>
                                @if($seminaire->max_participants)
                                <div class="w-20 bg-slate-700 rounded-full h-2 mt-1">
                                    <div class="bg-blue-500 h-2 rounded-full" 
                                         style="width: {{ ($seminaire->inscriptions->count() / $seminaire->max_participants) * 100 }}%"></div>
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @switch($seminaire->statut)
                                        @case('planifie') bg-gray-100 text-gray-800 @break
                                        @case('ouvert') bg-green-100 text-green-800 @break
                                        @case('complet') bg-yellow-100 text-yellow-800 @break
                                        @case('termine') bg-blue-100 text-blue-800 @break
                                        @case('annule') bg-red-100 text-red-800 @break
                                    @endswitch">
                                    @switch($seminaire->statut)
                                        @case('planifie') 📅 Planifié @break
                                        @case('ouvert') ✅ Ouvert @break
                                        @case('complet') ⚠️ Complet @break
                                        @case('termine') ✅ Terminé @break
                                        @case('annule') ❌ Annulé @break
                                    @endswitch
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.seminaires.show', $seminaire) }}" 
                                       class="text-blue-400 hover:text-blue-300 transition-colors">
                                        👁️
                                    </a>
                                    <a href="{{ route('admin.seminaires.edit', $seminaire) }}" 
                                       class="text-yellow-400 hover:text-yellow-300 transition-colors">
                                        ✏️
                                    </a>
                                    <form method="POST" action="{{ route('admin.seminaires.destroy', $seminaire) }}" 
                                          class="inline-block" onsubmit="return confirm('Supprimer ce séminaire ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 transition-colors">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <div class="text-6xl mb-4">🎓</div>
                                <div class="text-lg">Aucun séminaire trouvé</div>
                                <div class="text-sm mt-2">Créez votre premier séminaire pour commencer</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($seminaires->hasPages())
        <div class="mt-8">
            {{ $seminaires->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
