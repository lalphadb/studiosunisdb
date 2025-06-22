@extends('layouts.admin')

@section('title', 'Assigner Ceintures')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-yellow-600 to-orange-600 rounded-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">🥋 Assigner Ceintures</h1>
                <p class="text-yellow-100 text-lg">Attribuer des ceintures aux membres</p>
            </div>
            <div class="text-right">
                <a href="{{ route('admin.ceintures.index') }}" class="bg-yellow-500 bg-opacity-50 px-4 py-2 rounded-lg text-white hover:bg-opacity-70 transition">
                    ← Retour aux ceintures
                </a>
            </div>
        </div>
    </div>

    {{-- Formulaire d'assignation --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-6">Nouvelle Assignation</h2>
        
        <form method="POST" action="{{ route('admin.ceintures.assign.store') }}">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Sélection du membre --}}
                <div>
                    <label for="membre_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Membre <span class="text-red-500">*</span>
                    </label>
                    <select name="membre_id" id="membre_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Sélectionner un membre --</option>
                        @foreach($membres as $membre)
                            <option value="{{ $membre->id }}" {{ old('membre_id') == $membre->id ? 'selected' : '' }}>
                                {{ $membre->prenom }} {{ $membre->nom }} 
                                @if($membre->ecole)
                                    ({{ $membre->ecole->nom }})
                                @endif
                                @if($membre->ceintureActuelle)
                                    - Ceinture actuelle: {{ $membre->ceintureActuelle->ceinture->nom ?? 'Aucune' }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('membre_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Sélection de la ceinture --}}
                <div>
                    <label for="ceinture_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Ceinture <span class="text-red-500">*</span>
                    </label>
                    <select name="ceinture_id" id="ceinture_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Sélectionner une ceinture --</option>
                        @foreach($ceintures as $ceinture)
                            <option value="{{ $ceinture->id }}" {{ old('ceinture_id') == $ceinture->id ? 'selected' : '' }}>
                                <span style="color: {{ $ceinture->couleur }}">●</span>
                                {{ $ceinture->nom }} (Ordre {{ $ceinture->ordre }})
                            </option>
                        @endforeach
                    </select>
                    @error('ceinture_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Date d'obtention --}}
                <div>
                    <label for="date_obtention" class="block text-sm font-medium text-gray-700 mb-2">
                        Date d'obtention <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="date_obtention" id="date_obtention" required 
                           value="{{ old('date_obtention', date('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('date_obtention')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Examinateur --}}
                <div>
                    <label for="examinateur" class="block text-sm font-medium text-gray-700 mb-2">
                        Examinateur
                    </label>
                    <input type="text" name="examinateur" id="examinateur" 
                           value="{{ old('examinateur') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('examinateur')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Commentaires --}}
            <div class="mt-6">
                <label for="commentaires" class="block text-sm font-medium text-gray-700 mb-2">
                    Commentaires
                </label>
                <textarea name="commentaires" id="commentaires" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('commentaires') }}</textarea>
                @error('commentaires')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Validation --}}
            <div class="mt-6">
                <label class="flex items-center">
                    <input type="checkbox" name="valide" value="1" {{ old('valide') ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Ceinture validée</span>
                </label>
            </div>

            {{-- Boutons --}}
            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('admin.ceintures.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Annuler
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Assigner la ceinture
                </button>
            </div>
        </form>
    </div>

    {{-- Liste des assignations récentes --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Assignations Récentes</h2>
        
        @php
            $recentAssignations = \App\Models\MembreCeinture::with(['membre.ecole', 'ceinture'])
                ->latest('created_at')
                ->limit(10)
                ->get();
        @endphp
        
        @if($recentAssignations->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Membre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ceinture</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentAssignations as $assignation)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $assignation }} {{ $assignation->user->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $assignation->user->ecole->nom ?? 'École inconnue' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span style="color: {{ $assignation->ceinture->couleur }}">●</span>
                                    {{ $assignation->ceinture->nom }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($assignation->date_obtention)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($assignation->valide)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Validée
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            En attente
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center py-4">Aucune assignation récente</p>
        @endif
    </div>
</div>
@endsection
