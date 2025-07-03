@extends('layouts.admin')

@section('title', 'Modifier le cours')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                📚 Modifier le cours : {{ $cours->nom }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Modifiez les informations de base. Les horaires existants conserveront leurs paramètres spécifiques.
            </p>
        </div>

        <!-- Messages -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <strong>Erreurs de validation :</strong>
                <ul class="mt-2 ml-4 list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulaire -->
        <form method="POST" action="{{ route('admin.cours.update', $cours) }}">
            @csrf
            @method('PUT')
            @include('admin.cours.form')
        </form>
    </div>
</div>
@endsection
