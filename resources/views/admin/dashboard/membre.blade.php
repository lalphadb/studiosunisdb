@extends('layouts.admin')

@section('title', 'Dashboard Membre')

@section('content')
<div class="min-h-screen bg-slate-900 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-white mb-8">👤 Bienvenue {{ $user->name }}</h2>
        
        <div class="bg-slate-800 rounded-lg p-6">
            <p class="text-white">Dashboard membre en développement...</p>
        </div>
    </div>
</div>
@endsection
