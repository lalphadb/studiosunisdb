@extends('layouts.admin')

@section('title', 'Détails du Paiement')

@section('content')
<div class="min-h-screen bg-slate-900 py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold leading-7 text-white sm:text-3xl">
                        💳 Paiement {{ $paiement->reference_interne }}
                    </h2>
                    <p class="mt-1 text-sm text-slate-400">
                        Détails du paiement
                    </p>
                </div>
                <a href="{{ route('admin.paiements.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-slate-600 rounded-md text-sm font-medium text-slate-300 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour à la liste
                </a>
            </div>
        </div>

        <!-- Contenu du paiement -->
        <div class="bg-slate-800 rounded-lg shadow-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informations membre -->
                <div>
                    <h3 class="text-lg font-medium text-white mb-4">👤 Membre</h3>
                    <div class="space-y-2">
                        <p class="text-slate-300"><strong>Nom:</strong> {{ $paiement->membre->nom }} {{ $paiement->membre->prenom }}</p>
                        <p class="text-slate-300"><strong>Email:</strong> {{ $paiement->membre->email }}</p>
                        <p class="text-slate-300"><strong>École:</strong> {{ $paiement->ecole->nom }}</p>
                    </div>
                </div>

                <!-- Informations paiement -->
                <div>
                    <h3 class="text-lg font-medium text-white mb-4">💰 Paiement</h3>
                    <div class="space-y-2">
                        <p class="text-slate-300"><strong>Motif:</strong> {{ $paiement->session_text }}</p>
                        <p class="text-slate-300"><strong>Montant:</strong> ${{ number_format($paiement->montant, 2) }}</p>
                        <p class="text-slate-300"><strong>Statut:</strong> 
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $paiement->statut_badge }}">
                                {{ $paiement->statut_text }}
                            </span>
                        </p>
                        <p class="text-slate-300"><strong>Créé le:</strong> {{ $paiement->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
            </div>

            @if($paiement->description)
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-white mb-2">📝 Description</h3>
                    <p class="text-slate-300">{{ $paiement->description }}</p>
                </div>
            @endif

            <!-- Actions -->
            <div class="mt-8 flex space-x-4">
                @if($paiement->statut === 'en_attente')
                    <form action="{{ route('admin.paiements.marquer-recu', $paiement) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Marquer comme reçu
                        </button>
                    </form>
                @endif

                @if($paiement->statut === 'recu')
                    <form action="{{ route('admin.paiements.valider', $paiement) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            Valider le paiement
                        </button>
                    </form>
                @endif

                @if(!in_array($paiement->statut, ['valide', 'rembourse']))
                    <a href="{{ route('admin.paiements.edit', $paiement) }}" 
                       class="px-4 py-2 border border-slate-600 text-slate-300 rounded-md hover:bg-slate-700">
                        Modifier
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
