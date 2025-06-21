@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Utilisateurs</h1>
        @can('create-user')
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                Ajouter un utilisateur
            </a>
        @endcan
    </div>

    <div class="card">
        <div class="card-body">
            @if($users->count() > 0)
                <div class="table-responsive">
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
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->ecole->nom ?? 'Global' }}</td>
                                    <td>
                                        @foreach($user->roles as $role)
                                            <span class="badge bg-info">{{ $role->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $user->active ? 'success' : 'danger' }}">
                                            {{ $user->active ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </td>
                                    <td>
                                        @can('view', $user)
                                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-primary">Voir</a>
                                        @endcan
                                        @can('update', $user)
                                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-warning">Modifier</a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{ $users->links() }}
            @else
                <p class="text-center text-muted">Aucun utilisateur trouvé.</p>
            @endif
        </div>
    </div>
</div>
@endsection
