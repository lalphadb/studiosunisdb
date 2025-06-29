@extends('layouts.admin')

@section('title', 'Actions de Masse')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-white">⚡ Actions de Masse - Paiements</h2>
        <p class="text-slate-400">Valider plusieurs paiements en une fois</p>
    </div>

    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <h3 class="text-lg font-semibold text-white mb-4">🚧 En construction</h3>
        <p class="text-slate-300 mb-4">
            Cette fonctionnalité permet de traiter plusieurs paiements simultanément.
        </p>
        
        <div class="space-y-4">
            <div class="bg-blue-600/20 border border-blue-600/30 rounded-lg p-4">
                <h4 class="text-blue-300 font-medium mb-2">Fonctionnalités prévues :</h4>
                <ul class="text-blue-200 text-sm space-y-1">
                    <li>• Sélection multiple de paiements</li>
                    <li>• Validation en lot</li>
                    <li>• Ajout de références virements</li>
                    <li>• Changement de statut en masse</li>
                </ul>
            </div>
            
            <div class="flex space-x-4">
                <a href="{{ route('admin.paiements.index') }}" 
                   class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-lg">
                    ← Retour aux paiements
                </a>
                
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg" 
                        onclick="alert('Fonctionnalité en développement')">
                    🚀 Bientôt disponible
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
