@extends('layouts.admin')

@section('title', 'Créer une École')

@section('content')
<div class="space-y-6">
    <x-module-header
        module="ecoles"
        title="Créer une École"
        subtitle="Création d'un nouvel élément"
        create-route="{{ route('admin.ecoles.index') }}"
        create-text="Retour à la liste"
        create-permission="viewAny,App\Models\Ecole"
    />

    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <form method="POST" action="{{ route('admin.ecoles.store') }}" class="space-y-6" aria-label="Formulaire de création d'école">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="nom" class="block text-sm font-medium text-slate-300 mb-2">Nom de l'école *</label>
                    <input type="text" name="nom" id="nom" required
                           class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500"
                           placeholder="Studio de Karaté ..."
                           aria-required="true">
                    @error('nom')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="code" class="block text-sm font-medium text-slate-300 mb-2">Code école *</label>
                    <input type="text" name="code" id="code" required
                           class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500"
                           placeholder="STU001"
                           aria-required="true">
                    @error('code')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="ville" class="block text-sm font-medium text-slate-300 mb-2">Ville</label>
                    <input type="text" name="ville" id="ville"
                           class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500"
                           placeholder="Montréal">
                    @error('ville')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="telephone" class="block text-sm font-medium text-slate-300 mb-2">Téléphone</label>
                    <input type="tel" name="telephone" id="telephone"
                           class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500"
                           placeholder="(514) 123-4567">
                    @error('telephone')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300 mb-2">Email</label>
                    <input type="email" name="email" id="email"
                           class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500"
                           placeholder="info@studio.com">
                    @error('email')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label for="adresse" class="block text-sm font-medium text-slate-300 mb-2">Adresse</label>
                    <textarea name="adresse" id="adresse" rows="3"
                              class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500"
                              placeholder="123 Rue Exemple, Ville, Province, Code Postal"></textarea>
                    @error('adresse')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="flex justify-end space-x-3 pt-6 border-t border-slate-700">
                <a href="{{ route('admin.ecoles.index') }}"
                   class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-2 rounded-lg transition-colors"
                   aria-label="Annuler et retourner à la liste">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors"
                        aria-label="Enregistrer la nouvelle école">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
