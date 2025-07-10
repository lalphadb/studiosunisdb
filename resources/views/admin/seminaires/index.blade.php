@extends('layouts.admin')

@section('title', 'Gestion des Séminaires')

@section('content')
<div class="space-y-6">
    <!-- Header Module -->
    <div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">Gestion des Séminaires</h1>
                <p class="text-slate-300">Administration des séminaires et événements spéciaux</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.seminaires.create') }}" 
                   class="studiosdb-btn studiosdb-btn-primary">
                    <span class="ml-2">Nouveau Séminaire</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Total Séminaires</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-rose-500/20 border-rose-500/30 rounded-2xl flex items-center justify-center border">
                    <span class="text-rose-400 text-2xl">🎯</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Séminaires -->
    <div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <min-w-full divide-y divide-gray-200 class="studiosdb-min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Instructeur</th>
                        <th>Statut</th>
                        <th>Participants</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/30">
                    @forelse($seminaires as $seminaire)
                        <tr class="hover:bg-slate-700/20 transition-colors">
                            <td class="text-white font-medium">{{ $seminaire->titre }}</td>
                            <td class="text-slate-300">{{ $seminaire->type_libelle }}</td>
                            <td class="text-slate-300">{{ $seminaire->duree }}</td>
                            <td class="text-slate-300">{{ $seminaire->instructeur }}</td>
                            <td>
                                <span class="{{ $seminaire->statut_class }}">
                                    {{ $seminaire->statut_libelle }}
                                </span>
                            </td>
                            <td class="text-blue-400">{{ $seminaire->nombre_inscrits }}</td>
                            <td>
                                <a href="{{ route('admin.seminaires.show', $seminaire) }}" class="text-blue-400 hover:text-blue-300 mr-2">Voir</a>
                                <a href="{{ route('admin.seminaires.inscriptions', $seminaire) }}" class="text-emerald-400 hover:text-emerald-300">Inscrits</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-slate-400 py-8">
                                Aucun séminaire trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </min-w-full divide-y divide-gray-200>
        </div>
        
        <!-- Pagination -->
        @if($seminaires->hasPages())
            <div class="mt-6">
                {{ $seminaires->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
