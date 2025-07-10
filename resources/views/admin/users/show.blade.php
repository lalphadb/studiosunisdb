@extends('layouts.admin')
@section('title', 'Utilisateur - Détail')
@section('content')
<div class="space-y-6">
    <div class="flex items-center space-x-4">
        <a href="{{ route('admin.users.index') }}" class="text-slate-400 hover:text-white">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-white">{{ $user->name }}</h1>
    </div>
    
    <div class="bg-slate-800/50 border border-slate-700/30 rounded-xl p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-300">Email</label>
                <p class="text-white">{{ $user->email }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300">École</label>
                <p class="text-white">{{ $user->ecole->nom ?? 'Aucune' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300">Rôle</label>
                <p class="text-white">{{ $user->roles->first()->name ?? 'Aucun' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300">Statut</label>
                <p class="text-white">{{ $user->email_verified_at ? 'Actif' : 'Inactif' }}</p>
            </div>
        </div>
        
        <div class="mt-6 flex space-x-4">
            <a href="{{ route('admin.users.edit', $user) }}" 
               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Modifier
            </a>
            <a href="{{ route('admin.users.index') }}" 
               class="px-4 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700">
                Retour
            </a>
        </div>
    </div>
</div>
@endsection
