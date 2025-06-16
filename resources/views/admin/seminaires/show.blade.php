@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-slate-900 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Actions -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-white">{{ $seminaire->titre }}</h1>
                <p class="text-slate-400 mt-2">{{ $seminaire->ecole?->nom ?? 'École non définie' }}</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.seminaires.edit', $seminaire) }}" 
                   class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                    ✏️ Modifier
                </a>
                <a href="{{ route('admin.seminaires.index') }}" 
                   class="bg-slate-600 hover:bg-slate-500 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                    ← Retour
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations Principales -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Détails Généraux -->
                <div class="bg-slate-800 rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                        📋 Informations Générales
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Type</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @switch($seminaire->type)
                                    @case('technique') bg-blue-100 text-blue-800 @break
                                    @case('kata') bg-purple-100 text-purple-800 @break
                                    @case('competition') bg-red-100 text-red-800 @break
                                    @case('arbitrage') bg-yellow-100 text-yellow-800 @break
                                    @case('grade') bg-green-100 text-green-800 @break
                                    @default bg-gray-100 text-gray-800
                                @endswitch">
                                @switch($seminaire->type)
                                    @case('technique') 🥋 Technique @break
                                    @case('kata') 🎭 Kata @break
                                    @case('competition') 🏆 Compétition @break
                                    @case('arbitrage') ⚖️ Arbitrage @break
                                    @case('grade') 🎓 Grade @break
                                    @default {{ ucfirst($seminaire->type) }}
                                @endswitch
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Statut</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @switch($seminaire->statut)
                                    @case('planifie') bg-gray-100 text-gray-800 @break
                                    @case('ouvert') bg-green-100 text-green-800 @break
                                    @case('complet') bg-yellow-100 text-yellow-800 @break
                                    @case('termine') bg-blue-100 text-blue-800 @break
                                    @case('annule') bg-red-100 text-red-800 @break
                                @endswitch">
                                @switch($seminaire->statut)
                                    @case('planifie') 📅 Planifié @break
                                    @case('ouvert') ✅ Ouvert @break
                                    @case('complet') ⚠️ Complet @break
                                    @case('termine') ✅ Terminé @break
                                    @case('annule') ❌ Annulé @break
                                @endswitch
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Date & Heure</label>
                            <div class="text-white">
                                <div>📅 {{ $seminaire->date_debut->format('d/m/Y') }}</div>
                                <div class="text-slate-400">🕐 {{ $seminaire->date_debut->format('H:i') }}
                                    @if($seminaire->date_fin)
                                        - {{ $seminaire->date_fin->format('H:i') }}
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Durée</label>
                            <div class="text-white">
                                ⏱️ {{ number_format($seminaire->duree / 60, 1) }}h
                                @if($seminaire->duree % 60 > 0)
                                    {{ $seminaire->duree % 60 }}min
                                @endif
                            </div>
                        </div>

                        @if($seminaire->instructeur)
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Instructeur</label>
                            <div class="text-white">👨‍🏫 {{ $seminaire->instructeur }}</div>
                        </div>
                        @endif

                        @if($seminaire->lieu)
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Lieu</label>
                            <div class="text-white">📍 {{ $seminaire->lieu }}</div>
                        </div>
                        @endif

                        @if($seminaire->cout)
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Coût</label>
                            <div class="text-white">💰 {{ number_format($seminaire->cout, 2) }} $</div>
                        </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Niveau</label>
                            <div class="text-green-400">🥋 Ouvert à tous les niveaux</div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                @if($seminaire->description || $seminaire->objectifs || $seminaire->prerequis)
                <div class="bg-slate-800 rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                        📝 Description & Objectifs
                    </h2>
                    <div class="space-y-4">
                        @if($seminaire->description)
                        <div>
                            <h3 class="text-lg font-medium text-white mb-2">Description</h3>
                            <p class="text-slate-300 leading-relaxed">{{ $seminaire->description }}</p>
                        </div>
                        @endif

                        @if($seminaire->objectifs)
                        <div>
                            <h3 class="text-lg font-medium text-white mb-2">Objectifs</h3>
                            <p class="text-slate-300 leading-relaxed">{{ $seminaire->objectifs }}</p>
                        </div>
                        @endif

                        @if($seminaire->prerequis)
                        <div>
                            <h3 class="text-lg font-medium text-white mb-2">Prérequis</h3>
                            <p class="text-slate-300 leading-relaxed">{{ $seminaire->prerequis }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Liste des Participants -->
                <div class="bg-slate-800 rounded-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-white flex items-center">
                            👥 Participants ({{ $seminaire->inscriptions->count() }})
                        </h2>
                        @if($seminaire->statut == 'ouvert')
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            + Ajouter Participant
                        </button>
                        @endif
                    </div>

                    @if($seminaire->inscriptions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-700">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase">Participant</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase">École</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase">Inscription</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase">Statut</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-700">
                                @foreach($seminaire->inscriptions as $inscription)
                                <tr class="hover:bg-slate-700/50 transition-colors">
                                    <td class="px-4 py-3">
                                        <div class="text-white font-medium">{{ $inscription->membre->prenom }} {{ $inscription->membre->nom }}</div>
                                        <div class="text-slate-400 text-sm">{{ $inscription->membre->email }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            🥋 {{ $inscription->membre->ecole?->nom ?? 'StudiosUnisDB' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-slate-300">
                                        {{ $inscription->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @switch($inscription->statut)
                                                @case('inscrit') bg-blue-100 text-blue-800 @break
                                                @case('present') bg-green-100 text-green-800 @break
                                                @case('absent') bg-red-100 text-red-800 @break
                                                @default bg-gray-100 text-gray-800
                                            @endswitch">
                                            @switch($inscription->statut)
                                                @case('inscrit') 📝 Inscrit @break
                                                @case('present') ✅ Présent @break
                                                @case('absent') ❌ Absent @break
                                                @default {{ ucfirst($inscription->statut) }}
                                            @endswitch
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center space-x-2">
                                            @if($seminaire->statut == 'termine' && $inscription->statut == 'present' && $seminaire->certificat)
                                            <button class="text-blue-400 hover:text-blue-300 text-sm">
                                                📜 Certificat
                                            </button>
                                            @endif
                                            <button class="text-red-400 hover:text-red-300 text-sm">
                                                🗑️
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">👥</div>
                        <div class="text-slate-400">Aucun participant inscrit</div>
                        <div class="text-sm text-slate-500 mt-2">Les inscriptions apparaîtront ici</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Statistiques -->
                <div class="bg-slate-800 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">📊 Statistiques</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400">Inscrits</span>
                            <span class="text-white font-medium">{{ $seminaire->inscriptions->count() }}</span>
                        </div>
                        @if($seminaire->max_participants)
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400">Capacité</span>
                            <span class="text-white font-medium">{{ $seminaire->max_participants }}</span>
                        </div>
                        <div class="w-full bg-slate-700 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" 
                                 style="width: {{ $seminaire->max_participants > 0 ? min(($seminaire->inscriptions->count() / $seminaire->max_participants) * 100, 100) : 0 }}%"></div>
                        </div>
                        @endif
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400">Présents</span>
                            <span class="text-green-400 font-medium">{{ $seminaire->inscriptions->where('statut', 'present')->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400">Absents</span>
                            <span class="text-red-400 font-medium">{{ $seminaire->inscriptions->where('statut', 'absent')->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions Rapides -->
                <div class="bg-slate-800 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">⚡ Actions Rapides</h3>
                    <div class="space-y-3">
                        @if($seminaire->statut == 'ouvert')
                        <button class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors">
                            📝 Prendre Présences
                        </button>
                        @endif
                        
                        @if($seminaire->inscriptions->count() > 0)
                        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors">
                            📧 Envoyer Email
                        </button>
                        <button class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors">
                            📊 Export Participants
                        </button>
                        @endif

                        @if($seminaire->statut == 'termine' && $seminaire->certificat)
                        <button class="w-full bg-yellow-600 hover:bg-yellow-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors">
                            📜 Générer Certificats
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Informations Système -->
                <div class="bg-slate-800 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">ℹ️ Informations</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Créé le</span>
                            <span class="text-white">{{ $seminaire->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Modifié le</span>
                            <span class="text-white">{{ $seminaire->updated_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Certificat</span>
                            <span class="text-white">{{ $seminaire->certificat ? '✅' : '❌' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Ouvert à</span>
                            <span class="text-green-400">🥋 Tous niveaux</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
