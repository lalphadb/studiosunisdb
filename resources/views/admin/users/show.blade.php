@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>👤 {{ $user->name }}</h4>
                    <div>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary btn-sm">✏️ Modifier</a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">↩️ Retour</a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="row">
                        <!-- Informations de base -->
                        <div class="col-md-6">
                            <h5>📋 Informations personnelles</h5>
                            <table class="table table-sm">
                                <tr>
                                    <th>Nom complet:</th>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Téléphone:</th>
                                    <td>{{ $user->telephone ?? 'Non renseigné' }}</td>
                                </tr>
                                <tr>
                                    <th>Date de naissance:</th>
                                    <td>{{ $user->date_naissance?->format('d/m/Y') ?? 'Non renseignée' }}</td>
                                </tr>
                                <tr>
                                    <th>Sexe:</th>
                                    <td>{{ $user->sexe ?? 'Non spécifié' }}</td>
                                </tr>
                                <tr>
                                    <th>Date d'inscription:</th>
                                    <td>{{ $user->date_inscription?->format('d/m/Y') ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Informations système -->
                        <div class="col-md-6">
                            <h5>🔐 Informations système</h5>
                            <table class="table table-sm">
                                <tr>
                                    <th>École:</th>
                                    <td>
                                        @if($user->ecole)
                                            <span class="badge badge-info">{{ $user->ecole->code }}</span>
                                            {{ $user->ecole->nom }}
                                        @else
                                            <span class="text-muted">Aucune école assignée</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Rôle(s):</th>
                                    <td>
                                        @foreach($user->getRoleNames() as $role)
                                            <span class="badge badge-secondary">{{ $role }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>Statut:</th>
                                    <td>
                                        @if($user->active)
                                            <span class="badge badge-success">Actif</span>
                                        @else
                                            <span class="badge badge-danger">Inactif</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Email vérifié:</th>
                                    <td>
                                        @if($user->email_verified_at)
                                            <span class="badge badge-success">✅ Vérifié</span>
                                            <br><small>{{ $user->email_verified_at->format('d/m/Y H:i') }}</small>
                                        @else
                                            <span class="badge badge-warning">⏳ Non vérifié</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Compte créé:</th>
                                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Dernière modification:</th>
                                    <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($user->notes)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h5>📝 Notes</h5>
                                <div class="alert alert-light">
                                    {{ $user->notes }}
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Statistiques si membre -->
                    @if($user->isMembre())
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h5>📊 Statistiques du membre</h5>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="card bg-primary text-white">
                                            <div class="card-body text-center">
                                                <h6>{{ $user->ceintures->count() }}</h6>
                                                <small>Ceintures obtenues</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-success text-white">
                                            <div class="card-body text-center">
                                                <h6>{{ $user->presences->count() }}</h6>
                                                <small>Présences totales</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-info text-white">
                                            <div class="card-body text-center">
                                                <h6>{{ $user->paiements->count() }}</h6>
                                                <small>Paiements</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-warning text-white">
                                            <div class="card-body text-center">
                                                <h6>
                                                    @if($user->getCeintureActuelle())
                                                        {{ $user->getCeintureActuelle()->nom }}
                                                    @else
                                                        Aucune
                                                    @endif
                                                </h6>
                                                <small>Ceinture actuelle</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
