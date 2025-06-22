@extends('layouts.admin')

@section('title', 'Présences')

@section('content')
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                {{ __('Détails de la Présence') }} 👁️
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('admin.presences.edit', $presence) }}" 
                   class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                    <i class="fas fa-edit mr-2"></i>Modifier
                </a>
                <a href="{{ route('admin.presences.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                    <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Informations principales -->
            <div class="bg-slate-800 overflow-hidden shadow-xl sm:rounded-lg border border-slate-700 mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-100 mb-6 border-b border-slate-700 pb-3">
                        <i class="fas fa-info-circle mr-2 text-blue-400"></i>Informations de la présence
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Date -->
                        <div class="bg-slate-700 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt text-2xl text-blue-400 mr-4"></i>
                                <div>
                                    <p class="text-sm text-gray-400">Date de présence</p>
                                    <p class="text-lg font-semibold text-white">
                                        {{ \Carbon\Carbon::parse($presence->date_presence)->format('d/m/Y') }}
                                    </p>
                                    <p class="text-sm text-gray-400">
                                        {{ \Carbon\Carbon::parse($presence->date_presence)->translatedFormat('l') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Statut -->
                        <div class="bg-slate-700 rounded-lg p-4">
                            <div class="flex items-center">
                                @switch($presence->statut)
                                    @case('present')
                                        <i class="fas fa-check-circle text-2xl text-green-400 mr-4"></i>
                                        @break
                                    @case('absent')
                                        <i class="fas fa-times-circle text-2xl text-red-400 mr-4"></i>
                                        @break
                                    @case('retard')
                                        <i class="fas fa-clock text-2xl text-orange-400 mr-4"></i>
                                        @break
                                    @case('excuse')
                                        <i class="fas fa-user-check text-2xl text-blue-400 mr-4"></i>
                                        @break
                                @endswitch
                                <div>
                                    <p class="text-sm text-gray-400">Statut</p>
                                    <p class="text-lg font-semibold text-white capitalize">
                                        {{ $presence->statut }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if($presence->heure_arrivee || $presence->heure_depart)
                            <!-- Heure d'arrivée -->
                            @if($presence->heure_arrivee)
                                <div class="bg-slate-700 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-sign-in-alt text-2xl text-green-400 mr-4"></i>
                                        <div>
                                            <p class="text-sm text-gray-400">Heure d'arrivée</p>
                                            <p class="text-lg font-semibold text-white">
                                                {{ \Carbon\Carbon::parse($presence->heure_arrivee)->format('H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Heure de départ -->
                            @if($presence->heure_depart)
                                <div class="bg-slate-700 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-sign-out-alt text-2xl text-red-400 mr-4"></i>
                                        <div>
                                            <p class="text-sm text-gray-400">Heure de départ</p>
                                            <p class="text-lg font-semibold text-white">
                                                {{ \Carbon\Carbon::parse($presence->heure_depart)->format('H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informations du cours -->
            <div class="bg-slate-800 overflow-hidden shadow-xl sm:rounded-lg border border-slate-700 mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-100 mb-6 border-b border-slate-700 pb-3">
                        <i class="fas fa-graduation-cap mr-2 text-yellow-400"></i>Cours concerné
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-slate-700 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-book text-2xl text-yellow-400 mr-4"></i>
                                <div>
                                    <p class="text-sm text-gray-400">Nom du cours</p>
                                    <p class="text-lg font-semibold text-white">{{ $presence->cours->nom }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-700 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-school text-2xl text-purple-400 mr-4"></i>
                                <div>
                                    <p class="text-sm text-gray-400">École</p>
                                    <p class="text-lg font-semibold text-white">{{ $presence->cours->ecole->nom }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-700 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-chalkboard-teacher text-2xl text-indigo-400 mr-4"></i>
                                <div>
                                    <p class="text-sm text-gray-400">Instructeur</p>
                                    <p class="text-lg font-semibold text-white">
                                        {{ $presence->cours->instructeur ?? 'Non défini' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations du membre -->
            <div class="bg-slate-800 overflow-hidden shadow-xl sm:rounded-lg border border-slate-700 mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-100 mb-6 border-b border-slate-700 pb-3">
                        <i class="fas fa-user mr-2 text-green-400"></i>Membre concerné
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-slate-700 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-id-card text-2xl text-green-400 mr-4"></i>
                                <div>
                                    <p class="text-sm text-gray-400">Nom complet</p>
                                    <p class="text-lg font-semibold text-white">
                                        {{ $presence->membre->prenom }} {{ $presence->membre->nom }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-700 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-2xl text-blue-400 mr-4"></i>
                                <div>
                                    <p class="text-sm text-gray-400">Email</p>
                                    <p class="text-lg font-semibold text-white">{{ $presence->membre->email }}</p>
                                </div>
                            </div>
                        </div>

                        @if($presence->membre->telephone)
                            <div class="bg-slate-700 rounded-lg p-4">
                                <div class="flex items-center">
                                    <i class="fas fa-phone text-2xl text-indigo-400 mr-4"></i>
                                    <div>
                                        <p class="text-sm text-gray-400">Téléphone</p>
                                        <p class="text-lg font-semibold text-white">{{ $presence->membre->telephone }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($presence->notes)
                <div class="bg-slate-800 overflow-hidden shadow-xl sm:rounded-lg border border-slate-700 mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-100 mb-4 border-b border-slate-700 pb-3">
                            <i class="fas fa-sticky-note mr-2 text-yellow-400"></i>Notes
                        </h3>
                        <div class="bg-slate-700 rounded-lg p-4">
                            <p class="text-gray-300 leading-relaxed">{{ $presence->notes }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="bg-slate-800 overflow-hidden shadow-xl sm:rounded-lg border border-slate-700">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-100 mb-4 border-b border-slate-700 pb-3">
                        <i class="fas fa-cogs mr-2 text-gray-400"></i>Actions
                    </h3>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.presences.edit', $presence) }}" 
                           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition duration-150 ease-in-out">
                            <i class="fas fa-edit mr-2"></i>Modifier cette présence
                        </a>
                        
                        <a href="{{ route('admin.presences.create', ['cours_id' => $presence->cours_id]) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-150 ease-in-out">
                            <i class="fas fa-plus mr-2"></i>Ajouter une présence pour ce cours
                        </a>
                        
                        <form method="POST" action="{{ route('admin.presences.destroy', $presence) }}" 
                              class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette présence ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-150 ease-in-out">
                                <i class="fas fa-trash mr-2"></i>Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
