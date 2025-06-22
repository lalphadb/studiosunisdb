@extends('layouts.admin')

@section('title', 'Modifier ' . $user->name)

@section('content')
<div class="bg-gradient-to-r from-orange-600 to-red-600 rounded-xl p-6 text-white shadow-xl">
    <h1 class="text-3xl font-bold mb-2">🚧 Module en Développement</h1>
    <p class="text-orange-100">La fonctionnalité de modification des utilisateurs sera disponible dans la prochaine version.</p>
    
    <div class="mt-6 flex space-x-4">
        <a href="{{ route('admin.users.show', $user) }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-all">
            ← Voir le profil
        </a>
        <a href="{{ route('admin.users.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-all">
            ← Liste des utilisateurs
        </a>
    </div>
</div>

<div class="mt-6 bg-blue-900 bg-opacity-50 border border-blue-700 rounded-lg p-4">
    <h3 class="text-blue-300 font-bold">📋 Informations Utilisateur</h3>
    <p class="text-blue-200 mt-2">{{ $user->name }} - {{ $user->email }}</p>
</div>
@endsection
