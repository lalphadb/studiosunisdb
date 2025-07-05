@extends('layouts.admin')

@section('title', 'Modifier le cours')

@section('content')
<div class="space-y-6">
    <!-- Header avec x-module-header -->
    <x-module-header 
        module="cours"
        title="Modifier le cours : {{ $cours->nom }}" 
        subtitle="Modifiez les informations de base. Les horaires existants conserveront leurs paramètres spécifiques"
    />

        <div class="studiosdb-card border-l-4 border-red-500 bg-red-500/10">
            <div class="flex">
                <span class="text-red-400 text-xl mr-3">❌</span>
                <div>
                    <h3 class="text-sm font-medium text-red-200 mb-2">Erreurs de validation :</h3>
                    <ul class="list-disc list-inside space-y-1 text-sm text-red-300">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Formulaire -->
    <form method="POST" action="{{ route('admin.cours.update', $cours) }}">
        @csrf
        @method('PUT')
        @include('admin.cours.form')
    </form>
</div>
@endsection
