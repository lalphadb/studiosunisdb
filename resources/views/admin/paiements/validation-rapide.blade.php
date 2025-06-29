@extends('layouts.admin')

@section('title', 'Validation Rapide')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-white">🚀 Validation Rapide</h2>
        <p class="text-slate-400">Validation rapide des paiements</p>
    </div>

    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <h3 class="text-lg font-semibold text-white mb-4">🚧 En développement</h3>
        <p class="text-slate-300 mb-4">
            Fonctionnalité de validation rapide en cours de développement.
        </p>
        
        <a href="{{ route('admin.paiements.index') }}" 
           class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-lg">
            ← Retour aux paiements
        </a>
    </div>
</div>
@endsection
