@extends('layouts.admin')

@section('title', 'Inscriptions Séminaire')

@section('content')
<div class="space-y-6">
    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white">Inscriptions - {{ $seminaire->titre }}</h1>
                <p class="text-slate-300">Gestion des participants au séminaire</p>
            </div>
            <a href="{{ route('admin.seminaires.show', $seminaire) }}" class="px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg transition-colors">
                Retour au séminaire
            </a>
        </div>
        
        <div class="text-center py-8">
            <div class="w-16 h-16 bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-slate-400 text-2xl">👥</span>
            </div>
            <h3 class="text-xl text-white mb-2">Aucune inscription pour le moment</h3>
            <p class="text-slate-400">Les inscriptions apparaîtront ici une fois que des utilisateurs s'inscriront au séminaire.</p>
        </div>
    </div>
</div>
@endsection
