@extends('layouts.admin')

@section('title', 'Attribuer Ceinture')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-green-600 to-blue-600 rounded-lg p-6 text-white">
        <h1 class="text-3xl font-bold">🎯 Attribuer Ceinture</h1>
        <p class="text-green-100">Donner une nouvelle ceinture à un membre</p>
    </div>

    {{-- Formulaire simple --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('admin.ceintures.attribuer.store') }}">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Étape 1 : Choisir le membre --}}
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-blue-800 mb-3">1️⃣ Choisir le Membre</h3>
                    <select name="membre_id" required class="w-full p-2 border rounded">
                        <option value="">-- Sélectionner --</option>
                        @php
                            $user = auth()->user();
                            if ($user->hasRole('superadmin')) {
                                $membres = \App\Models\Membre::with(['ecole', 'ceintureActuelle.ceinture'])->get();
                            } else {
                                $membres = \App\Models\Membre::where('ecole_id', $user->ecole_id)
                                    ->with(['ecole', 'ceintureActuelle.ceinture'])->get();
                            }
                        @endphp
                        
                        @foreach($membres as $membre)
                            <option value="{{ $membre->id }}">
                                {{ $membre->prenom }} {{ $membre->nom }} 
                                @if($membre->ceintureActuelle)
                                    - Actuel: {{ $membre->ceintureActuelle->ceinture->nom }}
                                @else
                                    - Aucune ceinture
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Étape 2 : Choisir la ceinture --}}
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-yellow-800 mb-3">2️⃣ Nouvelle Ceinture</h3>
                    <select name="ceinture_id" required class="w-full p-2 border rounded">
                        <option value="">-- Sélectionner --</option>
                        @foreach(\App\Models\Ceinture::orderBy('ordre')->get() as $ceinture)
                            <option value="{{ $ceinture->id }}">
                                <span style="color: {{ $ceinture->couleur }}">●</span>
                                {{ $ceinture->nom }} ({{ $ceinture->ordre }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Étape 3 : Date et validation --}}
                <div class="bg-green-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-green-800 mb-3">3️⃣ Détails</h3>
                    <input type="date" name="date_obtention" required 
                           value="{{ date('Y-m-d') }}" 
                           class="w-full p-2 border rounded mb-2">
                    <input type="text" name="examinateur" 
                           placeholder="Examinateur" 
                           class="w-full p-2 border rounded mb-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="valide" value="1" checked class="mr-2">
                        Ceinture validée
                    </label>
                </div>
            </div>

            <div class="mt-6 text-center">
                <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-lg text-lg font-semibold hover:bg-green-700">
                    🥋 ATTRIBUER LA CEINTURE
                </button>
            </div>
        </form>
    </div>

    {{-- Historique récent --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">📋 Dernières Attributions</h2>
        
        @php
            $recent = \App\Models\MembreCeinture::with(['membre', 'ceinture'])
                ->latest('created_at')
                ->limit(5)
                ->get();
        @endphp
        
        @if($recent->count() > 0)
            @foreach($recent as $attribution)
                <div class="flex items-center justify-between p-3 border-b">
                    <div>
                        <strong>{{ $attribution->membre->prenom }} {{ $attribution->membre->nom }}</strong>
                        a reçu la ceinture 
                        <span style="color: {{ $attribution->ceinture->couleur }}">●</span>
                        <strong>{{ $attribution->ceinture->nom }}</strong>
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ $attribution->created_at->diffForHumans() }}
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-gray-500">Aucune attribution récente</p>
        @endif
    </div>
</div>
@endsection
