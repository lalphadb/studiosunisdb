@extends('layouts.admin')

@section('title', 'Gestion des Écoles')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-4xl font-bold text-white mb-2">🏢 Gestion des Écoles</h1>
        <p class="text-slate-400">Réseau Studios Unis du Québec</p>
    </div>
</div>

<div class="card-bg rounded-xl shadow-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead style="background: linear-gradient(135deg, #334155, #1e293b); border-bottom: 1px solid #475569;">
                <tr>
                    <th class="text-left px-6 py-4 font-bold text-white">École</th>
                    <th class="text-left px-6 py-4 font-bold text-white">Localisation</th>
                    <th class="text-left px-6 py-4 font-bold text-white">Directeur</th>
                    <th class="text-left px-6 py-4 font-bold text-white">Membres</th>
                    <th class="text-left px-6 py-4 font-bold text-white">Statut</th>
                    <th class="text-center px-6 py-4 font-bold text-white">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ecoles as $ecole)
                <tr class="border-b border-slate-600 hover-bg transition-all">
                    <td class="px-6 py-4">
                        <div>
                            <div class="font-bold text-white text-lg">{{ $ecole->nom }}</div>
                            <div class="text-slate-400">{{ $ecole->email }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-white">{{ $ecole->ville }}</div>
                        <div class="text-slate-400">{{ $ecole->code_postal }}</div>
                    </td>
                    <td class="px-6 py-4 text-white">{{ $ecole->directeur }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-sm font-bold" style="background-color: rgba(59, 130, 246, 0.2); border: 1px solid #3b82f6; color: #60a5fa;">
                            {{ $ecole->membres_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-sm font-bold" style="{{ $ecole->statut === 'actif' ? 'background-color: rgba(34, 197, 94, 0.2); border: 1px solid #16a34a; color: #4ade80;' : 'background-color: rgba(239, 68, 68, 0.2); border: 1px solid #dc2626; color: #f87171;' }}">
                            {{ ucfirst($ecole->statut) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center space-x-3">
                            <a href="{{ route('admin.ecoles.show', $ecole) }}" class="text-blue-400 hover:text-blue-300 transition-colors text-lg" title="Voir">👁️</a>
                            <a href="{{ route('admin.ecoles.edit', $ecole) }}" class="text-green-400 hover:text-green-300 transition-colors text-lg" title="Modifier">✏️</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                        <div class="text-6xl mb-4">🏢</div>
                        <p class="text-xl">Aucune école trouvée.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($ecoles->hasPages())
    <div class="px-6 py-4" style="border-top: 1px solid #475569;">
        {{ $ecoles->links() }}
    </div>
    @endif
</div>
@endsection
