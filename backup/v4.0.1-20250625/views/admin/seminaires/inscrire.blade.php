@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Inscrire un participant</h1>
                <a href="{{ route('admin.seminaires.show', $seminaire) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour au séminaire
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ $seminaire->titre }}</h5>
                    <small class="text-muted">
                        {{ \Carbon\Carbon::parse($seminaire->date_debut)->format('d/m/Y') }} à 
                        {{ \Carbon\Carbon::parse($seminaire->heure_debut)->format('H:i') }}
                    </small>
                </div>
                
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.seminaires.inscription.store', $seminaire) }}">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label for="user_id" class="form-label">Sélectionner un utilisateur <span class="text-danger">*</span></label>
                            <select name="user_id" id="user_id" class="form-control" required>
                                <option value="">-- Choisir un utilisateur --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} 
                                        @if($user->ecole)
                                            ({{ $user->ecole->nom }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-user-plus"></i> Inscrire le participant
                            </button>
                            <a href="{{ route('admin.seminaires.show', $seminaire) }}" class="btn btn-secondary">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
