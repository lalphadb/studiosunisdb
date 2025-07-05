@extends('layouts.admin')

@section('title', 'Créer un cours')

@section('content')
<div class="space-y-6">
    <!-- Header avec x-module-header -->
    <x-module-header 
        module="cours"
        title="Créer un nouveau cours" 
        subtitle="Définissez les informations de base qui serviront de modèle pour créer des horaires spécifiques"
    />


    <!-- Formulaire -->
    <form method="POST" action="{{ route('admin.cours.store') }}">
        @csrf
        @include('admin.cours.form')
    </form>
</div>
@endsection
