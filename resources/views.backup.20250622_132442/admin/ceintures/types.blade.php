@extends('layouts.admin')

@section('title', 'Types de Ceintures')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-yellow-600 to-orange-600 rounded-lg p-6 text-white">
        <h1 class="text-3xl font-bold">🥋 Types de Ceintures</h1>
        <p class="text-yellow-100">Gestion des 21 ceintures du système</p>
    </div>

    {{-- Liste des types --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Ceintures Disponibles</h2>
        
        @php
            $ceintures = \App\Models\Ceinture::orderBy('ordre')->get();
        @endphp
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($ceintures as $ceinture)
                <div class="border rounded-lg p-4 flex items-center space-x-4">
                    <div class="w-8 h-8 rounded-full" style="background-color: {{ $ceinture->couleur }}"></div>
                    <div class="flex-1">
                        <div class="font-semibold">{{ $ceinture->nom }}</div>
                        <div class="text-sm text-gray-500">Ordre: {{ $ceinture->ordre }}</div>
                    </div>
                    <div class="text-sm text-gray-600">
                        {{ $ceinture->membresCeintures()->count() }} attributions
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
