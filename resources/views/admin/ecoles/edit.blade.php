@extends('layouts.admin')

@section('title', 'Modifier ' . $ecole->nom)

@section('content')
<div class="flex-1 flex flex-col overflow-hidden">
    <!-- Header avec module-header -->
    <x-module-header 
        module="ecoles" 
        title="Modifier {{ $ecole->nom }}" 
        subtitle="🏫 Gestion des informations de l'école"
    />

    <div class="flex-1 overflow-x-hidden overflow-y-auto p-6">
        <div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden">
            <form action="{{ route('admin.ecoles.update', $ecole) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Informations de base -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Nom de l'école *</label>
                        <input type="text" name="nom" value="{{ old('nom', $ecole->nom) }}" 
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-emerald-500" 
                               required>
                        @error('nom')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Code école</label>
                        <input type="text" name="code" value="{{ old('code', $ecole->code) }}" 
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-emerald-500">
                        @error('code')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Adresse -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Adresse</label>
                    <input type="text" name="adresse" value="{{ old('adresse', $ecole->adresse) }}" 
                           class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-emerald-500">
                    @error('adresse')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Ville</label>
                        <input type="text" name="ville" value="{{ old('ville', $ecole->ville) }}" 
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-emerald-500">
                        @error('ville')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Code postal</label>
                        <input type="text" name="code_postal" value="{{ old('code_postal', $ecole->code_postal) }}" 
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-emerald-500">
                        @error('code_postal')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Contact -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Téléphone</label>
                        <input type="text" name="telephone" value="{{ old('telephone', $ecole->telephone) }}" 
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-emerald-500">
                        @error('telephone')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $ecole->email) }}" 
                               class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-emerald-500">
                        @error('email')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Description</label>
                    <textarea name="description" rows="4" 
                              class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:border-emerald-500">{{ old('description', $ecole->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Statut -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="active" value="1" 
                               {{ old('active', $ecole->active) ? 'checked' : '' }}
                               class="rounded border-slate-600 bg-slate-700 text-emerald-500 focus:ring-emerald-500">
                        <span class="ml-2 text-sm text-slate-300">École active</span>
                    </label>
                </div>

                <!-- Actions -->
                <div class="flex justify-between pt-6 border-t border-slate-700">
                    <a href="{{ route('admin.ecoles.index') }}" 
                       class="studiosdb-btn studiosdb-btn-cancel">
                        ← Retour
                    </a>
                    
                    <button type="submit" class="studiosdb-btn studiosdb-btn-ecoles">
                        💾 Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
