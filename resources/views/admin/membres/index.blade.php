@extends('layouts.admin')

@section('title', 'Gestion des Membres')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-4xl font-bold text-white mb-2">👥 Gestion des Membres</h1>
        <p class="text-slate-400">Membres du réseau Studios Unis</p>
    </div>
    <a href="{{ route('admin.membres.create') }}" class="hover-bg px-6 py-3 rounded-lg font-bold transition-all text-white border border-blue-500" style="background-color: rgba(59, 130, 246, 0.2);">
        ➕ Nouveau Membre
    </a>
</div>

<div class="card-bg rounded-xl shadow-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead style="background: linear-gradient(135deg, #334155, #1e293b); border-bottom: 1px solid #475569;">
                <tr>
                    <th class="text-left px-6 py-4 font-bold text-white">Membre</th>
                    <th class="text-left px-6 py-4 font-bold text-white">École</th>
                    <th class="text-left px-6 py-4 font-bold text-white">Contact</th>
                    <th class="text-left px-6 py-4 font-bold text-white">Statut</th>
                    <th class="text-center px-6 py-4 font-bold text-white">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($membres as $membre)
                <tr class="border-b border-slate-600 hover-bg transition-all">
                    <td class="px-6 py-4">
                        <div>
                            <div class="font-bold text-white text-lg">{{ $membre->prenom }} {{ $membre->nom }}</div>
                            <div class="text-slate-400">Inscrit le {{ $membre->date_inscription->format('d/m/Y') }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-white">{{ $membre->ecole->nom ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        <div class="text-white">{{ $membre->email ?: 'Pas d\'email' }}</div>
                        <div class="text-slate-400">{{ $membre->telephone ?: 'Pas de tél.' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-sm font-bold" style="@if($membre->statut === 'actif') background-color: rgba(34, 197, 94, 0.2); border: 1px solid #16a34a; color: #4ade80; @elseif($membre->statut === 'suspendu') background-color: rgba(245, 158, 11, 0.2); border: 1px solid #f59e0b; color: #fbbf24; @else background-color: rgba(239, 68, 68, 0.2); border: 1px solid #dc2626; color: #f87171; @endif">
                            {{ ucfirst($membre->statut) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center space-x-3">
                            <a href="{{ route('admin.membres.show', $membre) }}" class="text-blue-400 hover:text-blue-300 transition-colors text-lg" title="Voir">👁️</a>
                            <a href="{{ route('admin.membres.edit', $membre) }}" class="text-green-400 hover:text-green-300 transition-colors text-lg" title="Modifier">✏️</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                        <div class="text-6xl mb-4">👥</div>
                        <p class="text-xl">Aucun membre trouvé.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($membres->hasPages())
    <div class="px-6 py-4" style="border-top: 1px solid #475569;">
        {{ $membres->links() }}
    </div>
    @endif
</div>
@endsection
