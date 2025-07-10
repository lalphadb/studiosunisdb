@extends('layouts.admin')
@section('title', 'Utilisateur - Modifier')
@section('content')
<div class="space-y-6">
    <div class="flex items-center space-x-4">
        <a href="{{ route('admin.users.index') }}" class="text-slate-400 hover:text-white">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-white">Modifier {{ $user->name }}</h1>
    </div>

    <div class="bg-slate-800/50 border border-slate-700/30 rounded-xl p-6">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Nom complet *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    @error('name')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Email *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    @error('email')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Nouveau mot de passe</label>
                    <input type="password" name="password"
                           class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    @error('password')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Confirmer mot de passe</label>
                    <input type="password" name="password_confirmation"
                           class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">École *</label>
                    <select name="ecole_id" required
                            class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        @foreach($ecoles as $ecole)
                            <option value="{{ $ecole->id }}" {{ old('ecole_id', $user->ecole_id) == $ecole->id ? 'selected' : '' }}>
                                {{ $ecole->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('ecole_id')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Rôle *</label>
                    <select name="role" required
                            class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role', $user->roles->first()?->name) == $role->name ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.users.index') }}" 
                   class="px-6 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700">
                    Annuler
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
