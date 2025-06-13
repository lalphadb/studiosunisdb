@extends('layouts.admin')

@section('title', 'Cours - ' . ($cours->nom ?? 'Détails'))

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold text-white mb-2">📚 Détails du Cours</h1>
    <p class="text-slate-400">Informations complètes</p>
</div>

<div class="card-bg rounded-xl shadow-xl p-8 text-center">
    <div class="text-8xl mb-4">🚧</div>
    <h2 class="text-2xl font-bold text-white mb-4">Page en Développement</h2>
    <p class="text-slate-400">Les détails des cours seront disponibles prochainement.</p>
    <div class="mt-6">
        <a href="{{ route('admin.cours.index') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            ← Retour aux Cours
        </a>
    </div>
</div>
@endsection
