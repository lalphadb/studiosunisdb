@extends('layouts.admin')

@section('title', 'Créer un Cours')

@section('content')
<x-module-header 
    title="Créer un Cours" 
    module="cours"
    :breadcrumb="[
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Cours', 'url' => route('admin.cours.index')],
        ['label' => 'Créer']
    ]" />

<div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden max-w-4xl mx-auto">
    <form action="{{ route('admin.cours.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        @include('admin.cours.partials.form')
        
        <div class="studiosdb-form-actions">
            <button type="submit" class="studiosdb-btn studiosdb-btn-primary">
                <x-admin-icon name="save" /> Créer le cours
            </button>
            <a href="{{ route('admin.cours.index') }}" class="studiosdb-btn studiosdb-btn-secondary">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
