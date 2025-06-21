@extends('layouts.admin')

@section('title', 'Utilisateurs')

@section('content')
<div class="admin-content">
    {{-- Header --}}
    <div class="admin-header">
        <div>
            <h1 class="admin-title">Utilisateurs</h1>
            <p class="admin-subtitle">Gestion des utilisateurs du système</p>
        </div>
        @can('create-user')
            <div class="admin-actions">
                <a href="{{ route('admin.users.create') }}" 
                   class="btn btn-primary">
                    <span>➕</span>
                    Ajouter un utilisateur
                </a>
            </div>
        @endcan
    </div>

    {{-- Contenu --}}
    <div class="admin-card">
        @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>École</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td class="font-medium text-white">{{ $user->name }}</td>
                                <td class="text-gray-300">{{ $user->email }}</td>
                                <td class="text-gray-300">{{ $user->ecole->nom ?? 'Global' }}</td>
                                <td>
                                    @foreach($user->roles as $role)
                                        <span class="badge badge-{{ $role->name }}">{{ $role->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <span class="badge {{ $user->active ? 'badge-success' : 'badge-danger' }}">
                                        {{ $user->active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex space-x-2">
                                        @can('view', $user)
                                            <a href="{{ route('admin.users.show', $user) }}" 
                                               class="btn btn-sm btn-info">Voir</a>
                                        @endcan
                                        @can('update', $user)
                                            <a href="{{ route('admin.users.edit', $user) }}" 
                                               class="btn btn-sm btn-warning">Modifier</a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-gray-400 text-lg mb-4">👥</div>
                <p class="text-gray-400">Aucun utilisateur trouvé.</p>
            </div>
        @endif
    </div>
</div>
@endsection
