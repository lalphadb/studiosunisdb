@extends('layouts.admin')
@section('title', 'Écoles - Liste')
@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-white">Gestion des Écoles</h1>
    
    <div class="bg-slate-800/50 border border-slate-700/30 rounded-xl p-6">
        <p class="text-slate-300">Module Écoles - Interface en développement</p>
        <p class="text-slate-400 text-sm mt-2">{{ $ecoles->count() }} école(s) trouvée(s)</p>
    </div>
</div>
@endsection
