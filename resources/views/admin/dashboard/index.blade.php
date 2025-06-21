@extends("layouts.admin")

@section('content')
<div class="container">
    <!-- Header Dashboard -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h1 class="mb-2">Dashboard StudiosUnisDB</h1>
                    <p class="mb-0">Bienvenue {{ $userInfo['name'] }} - {{ ucfirst($userInfo['role']) }} 
                        @if($userInfo['ecole'] !== 'Global') 
                            ({{ $userInfo['ecole'] }})
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Utilisateurs</h5>
                    <h2 class="text-primary">{{ $stats['total_users'] }}</h2>
                    <small class="text-muted">Total actifs</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Écoles</h5>
                    <h2 class="text-success">{{ $stats['total_ecoles'] }}</h2>
                    <small class="text-muted">Configurées</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Cours</h5>
                    <h2 class="text-info">{{ $stats['total_cours'] }}</h2>
                    <small class="text-muted">Disponibles</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Revenus</h5>
                    <h2 class="text-warning">${{ number_format($stats['paiements_mois'], 2) }}</h2>
                    <small class="text-muted">Ce mois</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Actions rapides</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @can('view-users')
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary w-100">
                                    👥 Gérer les utilisateurs
                                </a>
                            </div>
                        @endcan
                        
                        @can('create-user')
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.users.create') }}" class="btn btn-outline-success w-100">
                                    ➕ Nouvel utilisateur
                                </a>
                            </div>
                        @endcan
                        
                        @can('view-cours')
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.cours.index') }}" class="btn btn-outline-info w-100">
                                    📚 Gérer les cours
                                </a>
                            </div>
                        @endcan
                        
                        @can('view-paiements')
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.paiements.index') }}" class="btn btn-outline-warning w-100">
                                    💰 Voir les paiements
                                </a>
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations système -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6>Informations système</h6>
                </div>
                <div class="card-body">
                    <small class="text-muted">
                        StudiosUnisDB v4.0 | Laravel {{ app()->version() }} | PHP {{ phpversion() }}
                        | Connecté en tant que: {{ $userInfo['email'] }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
