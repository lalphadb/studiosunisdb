@extends('layouts.admin')

@section('title', 'Gestion des Présences')

@section('content')
<div class="space-y-6">
    <!-- Header Module -->
    <div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">Gestion des Présences</h1>
                <p class="text-slate-300">Administration complète des présences StudiosDB</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.presences.create') }}" 
                   class="studiosdb-btn studiosdb-btn-primary">
                    <span class="ml-2">Nouvelle Présence</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Total Présences</p>
                    <p class="text-3xl font-bold text-white">{{ $presences->total() }}</p>
                </div>
                <div class="w-14 h-14 bg-cyan-500/20 border-cyan-500/30 rounded-2xl flex items-center justify-center border">
                    <span class="text-cyan-400 text-2xl">👥</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Présences -->
    <div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <min-w-full divide-y divide-gray-200 class="studiosdb-min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Cours</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/30">
                    @forelse($presences as $presence)
                        <tr class="hover:bg-slate-700/20 transition-colors">
                            <td class="text-white">{{ $presence->user->name ?? 'N/A' }}</td>
                            <td class="text-slate-300">{{ $presence->cours->nom ?? 'N/A' }}</td>
                            <td class="text-slate-300">{{ $presence->date_cours->format('d/m/Y') }}</td>
                            <td>
                                @if($presence->present)
                                    <span class="text-emerald-400">Présent</span>
                                @else
                                    <span class="text-red-400">Absent</span>
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.presences.destroy', $presence) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-slate-400 py-8">
                                Aucune présence trouvée
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </min-w-full divide-y divide-gray-200>
        </div>
        
        <!-- Pagination -->
        @if($presences->hasPages())
            <div class="mt-6">
                {{ $presences->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
