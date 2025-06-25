@extends('layouts.admin')

@section('title', 'Accès refusé')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="text-center">
        <div class="text-9xl font-bold text-red-600 mb-4">403</div>
        <h1 class="text-4xl font-bold text-white mb-4">Accès refusé</h1>
        <p class="text-slate-400 mb-8">Vous n'avez pas les permissions nécessaires pour accéder à cette page.</p>
        <a href="{{ route('admin.dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition duration-200">
            ← Retour au Dashboard
        </a>
    </div>
</div>
@endsection
