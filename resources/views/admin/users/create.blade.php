@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>➕ Créer un nouvel utilisateur/membre</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        <!-- Informations de base -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nom complet *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Mot de passe -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Mot de passe *</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           name="password" required>
                                    @error('password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Confirmer mot de passe *</label>
                                    <input type="password" class="form-control" 
                                           name="password_confirmation" required>
                                </div>
                            </div>
                        </div>

                        <!-- École et rôle -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ecole_id">École *</label>
                                    <select class="form-control @error('ecole_id') is-invalid @enderror" name="ecole_id" required>
                                        <option value="">Sélectionner une école</option>
                                        @foreach($ecoles as $ecole)
                                            <option value="{{ $ecole->id }}" {{ old('ecole_id') == $ecole->id ? 'selected' : '' }}>
                                                {{ $ecole->code }} - {{ $ecole->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('ecole_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role">Rôle *</label>
                                    <select class="form-control @error('role') is-invalid @enderror" name="role" required>
                                        <option value="membre" {{ old('role') == 'membre' ? 'selected' : '' }}>Membre</option>
                                        @if(auth()->user()->hasAnyRole(['admin', 'superadmin']))
                                            <option value="instructeur" {{ old('role') == 'instructeur' ? 'selected' : '' }}>Instructeur</option>
                                        @endif
                                        @if(auth()->user()->hasRole('superadmin'))
                                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        @endif
                                    </select>
                                    @error('role')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Informations personnelles -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="telephone">Téléphone</label>
                                    <input type="text" class="form-control" name="telephone" value="{{ old('telephone') }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_naissance">Date de naissance</label>
                                    <input type="date" class="form-control" name="date_naissance" value="{{ old('date_naissance') }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sexe">Sexe</label>
                                    <select class="form-control" name="sexe">
                                        <option value="">Non spécifié</option>
                                        <option value="M" {{ old('sexe') == 'M' ? 'selected' : '' }}>Masculin</option>
                                        <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>Féminin</option>
                                        <option value="Autre" {{ old('sexe') == 'Autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Date d'inscription et statut -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_inscription">Date d'inscription *</label>
                                    <input type="date" class="form-control @error('date_inscription') is-invalid @enderror" 
                                           name="date_inscription" value="{{ old('date_inscription', date('Y-m-d')) }}" required>
                                    @error('date_inscription')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-check mt-4">
                                        <input type="checkbox" class="form-check-input" name="active" value="1" 
                                               {{ old('active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label">Compte actif</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea class="form-control" name="notes" rows="3">{{ old('notes') }}</textarea>
                        </div>

                        <!-- Boutons -->
                        <div class="form-group text-right">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">Créer l'utilisateur</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
