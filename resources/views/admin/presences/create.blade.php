@extends('layouts.admin')

@section('title', 'Nouvelle Présence')

@section('content')
<div class="space-y-6">
    <div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden">
        <h1 class="text-2xl font-bold text-white mb-6">Nouvelle Présence</h1>
        
        <form method="POST" action="{{ route('admin.presences.store') }}" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-slate-300 font-medium mb-2">Utilisateur</label>
                    <select name="user_id" class="studiosdb-input w-full" required>
                        <option value="">Sélectionner un utilisateur</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-slate-300 font-medium mb-2">Cours</label>
                    <select name="cours_id" class="studiosdb-input w-full" required>
                        <option value="">Sélectionner un cours</option>
                        @foreach($cours as $c)
                            <option value="{{ $c->id }}">{{ $c->nom }}</option>
                        @endforeach
                    </select>
                    @error('cours_id')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-slate-300 font-medium mb-2">Date du cours</label>
                    <input type="date" name="date_cours" class="studiosdb-input w-full" required>
                    @error('date_cours')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-slate-300 font-medium mb-2">Statut</label>
                    <select name="present" class="studiosdb-input w-full" required>
                        <option value="1">Présent</option>
                        <option value="0">Absent</option>
                    </select>
                    @error('present')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div>
                <label class="block text-slate-300 font-medium mb-2">Notes (optionnel)</label>
                <textarea name="notes" rows="3" class="studiosdb-input w-full" placeholder="Notes sur la présence..."></textarea>
                @error('notes')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            @if($ecoles->count() > 1)
            <div>
                <label class="block text-slate-300 font-medium mb-2">École</label>
                <select name="ecole_id" class="studiosdb-input w-full" required>
                    @foreach($ecoles as $ecole)
                        <option value="{{ $ecole->id }}">{{ $ecole->nom }}</option>
                    @endforeach
                </select>
                @error('ecole_id')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endif
            
            <div class="flex items-center space-x-4">
                <button type="submit" class="studiosdb-btn studiosdb-btn-primary">
                    Enregistrer la présence
                </button>
                <a href="{{ route('admin.presences.index') }}" class="studiosdb-btn studiosdb-btn-cancel">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
