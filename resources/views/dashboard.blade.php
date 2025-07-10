@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-7xl px-4">
    <div class="flex flex-wrap -mx-2 justify-content-center">
        <div class="col-md-12">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="bg-white shadow-md rounded-lg overflow-hidden-header d-flex justify-content-between align-items-center">
                    <h4>{{ __('Dashboard') }}</h4>
                    <div>
                        <span class="badge bg-primary">{{ auth()->user()->getRoleNames()->first() }}</span>
                    </div>
                </div>

                <div class="bg-white shadow-md rounded-lg overflow-hidden-body">
                    @if (session('status'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="flex flex-wrap -mx-2">
                        <!-- Informations utilisateur -->
                        <div class="col-md-4">
                            <div class="bg-white shadow-md rounded-lg overflow-hidden bg-primary text-white mb-4">
                                <div class="bg-white shadow-md rounded-lg overflow-hidden-body">
                                    <h5 class="bg-white shadow-md rounded-lg overflow-hidden-title">Informations utilisateur</h5>
                                    <p><strong>Nom:</strong> {{ auth()->user()->name }}</p>
                                    <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                                    <p><strong>École ID:</strong> {{ auth()->user()->ecole_id }}</p>
                                    <p><strong>Rôles:</strong> {{ auth()->user()->getRoleNames()->implode(', ') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Progression ceinture -->
                        <div class="col-md-4">
                            <div class="bg-white shadow-md rounded-lg overflow-hidden bg-slate-500 text-white mb-4">
                                <div class="bg-white shadow-md rounded-lg overflow-hidden-body">
                                    <h5 class="bg-white shadow-md rounded-lg overflow-hidden-title">Ma ceinture actuelle & Progression</h5>
                                    @php
                                        try {
                                            $derniereCeinture = auth()->user()->userCeintures()
                                                ->with('ceinture')
                                                ->where('valide', true)
                                                ->latest('date_obtention')
                                                ->first();
                                        } catch (\Exception $e) {
                                            $derniereCeinture = null;
                                        }
                                    @endphp
                                    
                                    @if($derniereCeinture)
                                        <p><span class="badge" style="background-color: {{ $derniereCeinture->couleur ?? '#666' }}">{{ $derniereCeinture->nom ?? 'Ceinture' }}</span></p>
                                        <small>Obtenue le {{ $derniereCeinture->pivot->date_obtention ?? 'Date inconnue' }}</small>
                                    @else
                                        <p><span class="badge bg-secondary">Aucune ceinture</span></p>
                                        <small>Commencez votre parcours !</small>
                                    @endif

                                    @php
                                        try {
                                            $prochaineCeinture = null;
                                            // Logique pour la prochaine ceinture si nécessaire
                                        } catch (\Exception $e) {
                                            $prochaineCeinture = null;
                                        }
                                    @endphp
                                    
                                    @if($prochaineCeinture)
                                        <hr>
                                        <h6>Prochaine ceinture</h6>
                                        <p><span class="badge" style="background-color: {{ $prochaineCeinture->couleur }}">{{ $prochaineCeinture->nom }}</span></p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Permissions -->
                        <div class="col-md-4">
                            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                                <div class="bg-white shadow-md rounded-lg overflow-hidden-body">
                                    <h5 class="bg-white shadow-md rounded-lg overflow-hidden-title">Permissions</h5>
                                    <div class="d-flex flex-wrap">
                                        @foreach(auth()->user()->getAllPermissions() as $permission)
                                            <span class="badge bg-secondary me-1 mb-1">{{ $permission->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions rapides -->
                    <div class="flex flex-wrap -mx-2 mt-4">
                        <div class="col-md-12">
                            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                                <div class="bg-white shadow-md rounded-lg overflow-hidden-header">
                                    <h5>Actions rapides</h5>
                                </div>
                                <div class="bg-white shadow-md rounded-lg overflow-hidden-body">
                                    <div class="flex flex-wrap -mx-2">
                                        @can('view-users')
                                        <div class="col-md-3 mb-2">
                                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary w-100">
                                                <i class="fas fa-users"></i> Gestion Utilisateurs
                                            </a>
                                        </div>
                                        @endcan

                                        @can('view-cours')
                                        <div class="col-md-3 mb-2">
                                            <a href="{{ route('admin.cours.index') }}" class="btn btn-outline-success w-100">
                                                <i class="fas fa-book"></i> Gestion Cours
                                            </a>
                                        </div>
                                        @endcan

                                        @can('view-paiements')
                                        <div class="col-md-3 mb-2">
                                            <a href="{{ route('admin.paiements.index') }}" class="btn btn-outline-warning w-100">
                                                <i class="fas fa-credit-bg-white shadow-md rounded-lg overflow-hidden"></i> Paiements
                                            </a>
                                        </div>
                                        @endcan

                                        <div class="col-md-3 mb-2">
                                            <a href="/api/test-auth" target="_blank" class="btn btn-outline-info w-100">
                                                <i class="fas fa-bug"></i> Test Auth API
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
