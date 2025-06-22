@extends('layouts.admin')

@section('title', 'Inscriptions - ' . $seminaire->nom)

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <div class="flex items-center">
                    <a href="{{ route('admin.seminaires.index') }}" 
                       class="text-gray-400 hover:text-white transition duration-150 mr-4">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-white">
                            👥 Inscriptions - {{ $seminaire->nom }}
                        </h1>
                        <div class="mt-1 flex items-center space-x-4 text-sm text-gray-300">
                            <span>{{ $seminaire->intervenant }}</span>
                            <span>•</span>
                            <span>{{ $seminaire->date_debut->format('d/m/Y H:i') }}</span>
                            <span>•</span>
                            <span>{{ $inscriptions->total() }}/{{ $seminaire->capacite_max }} inscrits</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex space-x-3 md:mt-0 md:ml-4">
                <a href="{{ route('admin.seminaires.inscrire', $seminaire) }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Inscrire un membre
                </a>
                <a href="{{ route('admin.seminaires.show', $seminaire) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-300 bg-slate-700 hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Voir le séminaire
                </a>
            </div>
        </div>

        <!-- Messages -->
        @if(session('success'))
            <div class="rounded-md bg-green-50 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-md bg-red-50 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-4 mb-6">
            <div class="bg-slate-800 border border-slate-700 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-300">Total inscrits</p>
                        <p class="text-lg font-semibold text-white">{{ $inscriptions->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800 border border-slate-700 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-300">Présents</p>
                        <p class="text-lg font-semibold text-white">{{ $inscriptions->where('statut', 'present')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800 border border-slate-700 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-600 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-300">Total reçu</p>
                        <p class="text-lg font-semibold text-white">{{ number_format($inscriptions->sum('montant_paye'), 2) }} $</p>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800 border border-slate-700 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-300">Certificats</p>
                        <p class="text-lg font-semibold text-white">{{ $inscriptions->where('certificat_obtenu', true)->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des inscriptions -->
        <div class="bg-slate-800 border border-slate-700 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-600">
                    <thead class="bg-slate-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Membre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">École</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Inscription</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Paiement</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Certificat</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-slate-800 divide-y divide-slate-600">
                        @forelse($inscriptions as $inscription)
                        <tr class="hover:bg-slate-700 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gray-600 flex items-center justify-center">
                                            <span class="text-sm font-medium text-white">
                                                {{ substr($inscription->membre->nom, 0, 1) }}{{ substr($inscription->membre->prenom, 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-white">
                                            {{ $inscription->membre->nom }} {{ $inscription->membre->prenom }}
                                        </div>
                                        <div class="text-sm text-gray-400">
                                            {{ $inscription->membre->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                {{ $inscription->ecole->nom }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                {{ $inscription->date_inscription->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-white">{{ number_format($inscription->montant_paye, 2) }} $</div>
                                @if($inscription->date_paiement)
                                    <div class="text-xs text-green-400">Payé le {{ $inscription->date_paiement->format('d/m/Y') }}</div>
                                @else
                                    <div class="text-xs text-red-400">Non payé</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($inscription->statut === 'present') bg-green-100 text-green-800
                                    @elseif($inscription->statut === 'inscrit') bg-blue-100 text-blue-800
                                    @elseif($inscription->statut === 'absent') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($inscription->statut) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                @if($inscription->certificat_obtenu)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Obtenu
                                    </span>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <!-- Bouton modifier statut -->
                                    <button onclick="openModal('modal-{{ $inscription->id }}')" 
                                            class="text-yellow-400 hover:text-yellow-300 transition duration-150">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    
                                    <!-- Bouton supprimer -->
                                    <form method="POST" action="{{ route('admin.seminaires.inscriptions.destroy', [$seminaire, $inscription]) }}" 
                                          class="inline" onsubmit="return confirm('Supprimer cette inscription ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 transition duration-150">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal de modification pour chaque inscription -->
                        <div id="modal-{{ $inscription->id }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
                            <div class="flex items-center justify-center min-h-screen">
                                <div class="bg-slate-800 rounded-lg p-6 w-full max-w-md">
                                    <h3 class="text-lg font-medium text-white mb-4">
                                        Modifier - {{ $inscription->membre->nom }}
                                    </h3>
                                    <form method="POST" action="{{ route('admin.seminaires.inscriptions.update', [$seminaire, $inscription]) }}">
                                        @csrf
                                        @method('PATCH')
                                        
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-300">Statut</label>
                                                <select name="statut" class="mt-1 block w-full bg-slate-700 border border-slate-600 rounded-md text-white text-sm">
                                                    <option value="inscrit" {{ $inscription->statut === 'inscrit' ? 'selected' : '' }}>Inscrit</option>
                                                    <option value="present" {{ $inscription->statut === 'present' ? 'selected' : '' }}>Présent</option>
                                                    <option value="absent" {{ $inscription->statut === 'absent' ? 'selected' : '' }}>Absent</option>
                                                    <option value="annule" {{ $inscription->statut === 'annule' ? 'selected' : '' }}>Annulé</option>
                                                </select>
                                            </div>
                                            
                                            <div>
                                                <label class="block text-sm font-medium text-gray-300">Montant payé ($)</label>
                                                <input type="number" name="montant_paye" step="0.01" value="{{ $inscription->montant_paye }}"
                                                       class="mt-1 block w-full bg-slate-700 border border-slate-600 rounded-md text-white text-sm">
                                            </div>
                                            
                                            <div>
                                                <label class="flex items-center">
                                                    <input type="checkbox" name="certificat_obtenu" {{ $inscription->certificat_obtenu ? 'checked' : '' }}
                                                           class="h-4 w-4 text-blue-600 bg-slate-700 border-slate-600 rounded">
                                                    <span class="ml-2 text-sm text-gray-300">Certificat obtenu</span>
                                                </label>
                                            </div>
                                            
                                            <div>
                                                <label class="block text-sm font-medium text-gray-300">Notes</label>
                                                <textarea name="notes_participant" rows="3"
                                                          class="mt-1 block w-full bg-slate-700 border border-slate-600 rounded-md text-white text-sm">{{ $inscription->notes_participant }}</textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="flex justify-end space-x-3 mt-6">
                                            <button type="button" onclick="closeModal('modal-{{ $inscription->id }}')"
                                                    class="px-4 py-2 text-sm font-medium text-gray-300 bg-slate-600 rounded-md hover:bg-slate-500">
                                                Annuler
                                            </button>
                                            <button type="submit"
                                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                                Sauvegarder
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-400">
                                    <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <p class="text-lg font-medium mb-2">Aucune inscription</p>
                                    <p class="text-sm mb-4">Ce séminaire n'a encore aucun participant.</p>
                                    <a href="{{ route('admin.seminaires.inscrire', $seminaire) }}" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        Inscrire le premier membre
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($inscriptions->hasPages())
                <div class="bg-slate-700 px-4 py-3 border-t border-slate-600">
                    {{ $inscriptions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}
</script>
@endsection
