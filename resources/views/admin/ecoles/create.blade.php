@extends('layouts.admin')

@section('title', 'Nouvelle École')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.ecoles.index') }}" class="text-slate-600 hover:text-slate-900 mr-4">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-3xl font-bold text-slate-900">Nouvelle École</h1>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
        <form method="POST" action="{{ route('admin.ecoles.store') }}">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informations générales -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Informations générales</h3>
                </div>
                
                <div>
                    <label for="nom" class="block text-sm font-medium text-slate-700 mb-2">Nom de l'école *</label>
                    <input type="text" name="nom" id="nom" value="{{ old('nom') }}" 
                           class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nom') border-red-500 @enderror">
                    @error('nom')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="directeur" class="block text-sm font-medium text-slate-700 mb-2">Directeur *</label>
                    <input type="text" name="directeur" id="directeur" value="{{ old('directeur') }}" 
                           class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('directeur') border-red-500 @enderror">
                    @error('directeur')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="adresse" class="block text-sm font-medium text-slate-700 mb-2">Adresse complète *</label>
                    <input type="text" name="adresse" id="adresse" value="{{ old('adresse') }}" 
                           class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('adresse') border-red-500 @enderror">
                    @error('adresse')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="ville" class="block text-sm font-medium text-slate-700 mb-2">Ville *</label>
                    <input type="text" name="ville" id="ville" value="{{ old('ville') }}" 
                           class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('ville') border-red-500 @enderror">
                    @error('ville')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="province" class="block text-sm font-medium text-slate-700 mb-2">Province *</label>
                    <select name="province" id="province" class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('province') border-red-500 @enderror">
                        <option value="Quebec" {{ old('province') == 'Quebec' ? 'selected' : '' }}>Québec</option>
                        <option value="Ontario" {{ old('province') == 'Ontario' ? 'selected' : '' }}>Ontario</option>
                    </select>
                    @error('province')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="code_postal" class="block text-sm font-medium text-slate-700 mb-2">Code postal *</label>
                    <input type="text" name="code_postal" id="code_postal" value="{{ old('code_postal') }}" 
                           placeholder="A1A 1A1"
                           class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('code_postal') border-red-500 @enderror">
                    @error('code_postal')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact -->
                <div class="md:col-span-2 mt-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Contact</h3>
                </div>

                <div>
                    <label for="telephone" class="block text-sm font-medium text-slate-700 mb-2">Téléphone *</label>
                    <input type="tel" name="telephone" id="telephone" value="{{ old('telephone') }}" 
                           placeholder="514-123-4567"
                           class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('telephone') border-red-500 @enderror">
                    @error('telephone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email *</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" 
                           class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="site_web" class="block text-sm font-medium text-slate-700 mb-2">Site web</label>
                    <input type="url" name="site_web" id="site_web" value="{{ old('site_web') }}" 
                           placeholder="https://exemple.com"
                           class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('site_web') border-red-500 @enderror">
                    @error('site_web')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Configuration -->
                <div class="md:col-span-2 mt-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Configuration</h3>
                </div>

                <div>
                    <label for="capacite_max" class="block text-sm font-medium text-slate-700 mb-2">Capacité maximale *</label>
                    <input type="number" name="capacite_max" id="capacite_max" value="{{ old('capacite_max', 100) }}" 
                           min="10" max="500"
                           class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('capacite_max') border-red-500 @enderror">
                    @error('capacite_max')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="statut" class="block text-sm font-medium text-slate-700 mb-2">Statut *</label>
                    <select name="statut" id="statut" class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('statut') border-red-500 @enderror">
                        <option value="actif" {{ old('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="inactif" {{ old('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                    </select>
                    @error('statut')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="4" 
                              class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end mt-8 space-x-4">
                <a href="{{ route('admin.ecoles.index') }}" class="bg-slate-300 hover:bg-slate-400 text-slate-700 px-6 py-2 rounded-lg transition-colors">
                    Annuler
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                    Créer l'école
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
