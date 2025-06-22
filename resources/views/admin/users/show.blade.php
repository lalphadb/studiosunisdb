@extends('layouts.admin')

@section('title', 'Profil de ' . $user->name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                    <span class="text-2xl font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">{{ $user->name }}</h1>
                    <p class="text-blue-100">{{ $user->email }}</p>
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.users.edit', $user) }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg">
                    ✏️ Modifier
                </a>
                <a href="{{ route('admin.users.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg">
                    ← Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Informations de base -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informations personnelles -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <h3 class="text-lg font-bold text-white mb-4">👤 Informations Personnelles</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-slate-400">Nom complet :</span>
                    <span class="text-white font-medium">{{ $user->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Email :</span>
                    <span class="text-white">{{ $user->email }}</span>
                </div>
                @if($user->telephone)
                <div class="flex justify-between">
                    <span class="text-slate-400">Téléphone :</span>
                    <span class="text-white">{{ $user->telephone }}</span>
                </div>
                @endif
                @if($user->date_naissance)
                <div class="flex justify-between">
                    <span class="text-slate-400">Date de naissance :</span>
                    <span class="text-white">{{ \Carbon\Carbon::parse($user->date_naissance)->format('d/m/Y') }}</span>
                </div>
                @endif
                @if($user->sexe)
                <div class="flex justify-between">
                    <span class="text-slate-400">Sexe :</span>
                    <span class="text-white">{{ $user->sexe }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-slate-400">Statut :</span>
                    <span class="px-2 py-1 rounded text-xs {{ $user->active ? 'bg-green-600' : 'bg-red-600' }} text-white">
                        {{ $user->active ? 'Actif' : 'Inactif' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- École et rôles -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <h3 class="text-lg font-bold text-white mb-4">🏫 École et Rôles</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-slate-400">École :</span>
                    <span class="text-white font-medium">{{ $user->ecole->nom ?? 'Aucune école' }}</span>
                </div>
                @if($user->ecole)
                <div class="flex justify-between">
                    <span class="text-slate-400">Code école :</span>
                    <span class="text-white">{{ $user->ecole->code }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-slate-400">Rôle(s) :</span>
                    <div class="flex flex-wrap gap-1">
                        @if($user->roles->count() > 0)
                            @foreach($user->roles as $role)
                                <span class="px-2 py-1 rounded text-xs bg-blue-600 text-white">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        @else
                            <span class="px-2 py-1 rounded text-xs bg-gray-600 text-white">Aucun rôle</span>
                        @endif
                    </div>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Date d'inscription :</span>
                    <span class="text-white">{{ $user->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Dernière mise à jour :</span>
                    <span class="text-white">{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <h3 class="text-lg font-bold text-white mb-4">⚡ Actions Rapides</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.users.edit', $user) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg text-center">
                ✏️ Modifier Profil
            </a>
            <a href="{{ route('admin.ceintures.create', ['user_id' => $user->id]) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-3 rounded-lg text-center">
                🥋 Attribuer Ceinture
            </a>
            <a href="{{ route('admin.users.qrcode', $user) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg text-center">
                📱 Code QR
            </a>
        </div>
    </div>
</div>
@endsection
