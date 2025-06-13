@extends('layouts.admin')

@section('title', 'École - ' . $ecole->nom)

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-white">{{ $ecole->nom }}</h1>
            <p class="text-slate-300 mt-1 text-lg">{{ $ecole->ville }}, {{ $ecole->province }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.ecoles.index') }}" class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-lg transition-colors font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Retour Liste
            </a>
            @can('edit-ecole')
            <a href="{{ route('admin.ecoles.edit', $ecole) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors font-medium">
                <i class="fas fa-edit mr-2"></i>Modifier
            </a>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Détails école -->
            <div class="bg-slate-800 rounded-lg shadow-lg border border-slate-700 p-6">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-school mr-3 text-blue-400"></i>
                    Informations de l'École
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-2">Nom de l'École</label>
                        <p class="text-white text-lg font-medium">{{ $ecole->nom }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-2">Directeur</label>
                        <p class="text-white text-lg">{{ $ecole->directeur }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-300 mb-2">Adresse Complète</label>
                        <p class="text-white text-lg">{{ $ecole->adresse }}</p>
                        <p class="text-slate-300 text-base">{{ $ecole->ville }}, {{ $ecole->province }} {{ $ecole->code_postal }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-2">Téléphone</label>
                        <p class="text-white text-lg font-mono">{{ $ecole->telephone }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-2">Email</label>
                        <p class="text-blue-300 text-lg">{{ $ecole->email }}</p>
                    </div>
                    @if($ecole->site_web)
                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-2">Site Web</label>
                        <a href="{{ $ecole->site_web }}" target="_blank" class="text-blue-400 hover:text-blue-300 text-lg underline">{{ $ecole->site_web }}</a>
                    </div>
                    @endif
                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-2">Capacité Maximale</label>
                        <p class="text-white text-lg"><span class="font-bold">{{ $ecole->capacite_max }}</span> personnes</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-2">Statut</label>
                        <span class="px-3 py-2 rounded-full text-base font-bold 
                            {{ $ecole->statut === 'actif' ? 'bg-green-600 text-white' : 'bg-red-600 text-white' }}">
                            {{ ucfirst($ecole->statut) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Membres récents -->
            <div class="bg-slate-800 rounded-lg shadow-lg border border-slate-700 p-6">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-users mr-3 text-green-400"></i>
                    Membres Récents
                </h3>
                @if($ecole->membres->count() > 0)
                <div class="space-y-4">
                    @foreach($ecole->membres->take(5) as $membre)
                    <div class="flex items-center justify-between p-4 bg-slate-700 rounded-lg border border-slate-600">
                        <div>
                            <p class="font-bold text-white text-lg">{{ $membre->prenom }} {{ $membre->nom }}</p>
                            <p class="text-slate-300">{{ $membre->email }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-sm font-bold 
                            {{ $membre->statut === 'actif' ? 'bg-green-600 text-white' : 'bg-red-600 text-white' }}">
                            {{ ucfirst($membre->statut) }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <i class="fas fa-user-plus text-4xl text-slate-500 mb-4"></i>
                    <p class="text-slate-400 text-lg">Aucun membre inscrit</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Statistiques -->
        <div class="space-y-6">
            <!-- KPIs -->
            <div class="bg-slate-800 rounded-lg shadow-lg border border-slate-700 p-6">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-chart-bar mr-3 text-purple-400"></i>
                    Statistiques
                </h3>
                <div class="space-y-6">
                    <div class="bg-slate-700 p-4 rounded-lg border border-slate-600">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-300 font-medium">Membres actifs</span>
                            <span class="text-3xl font-bold text-blue-400">{{ $stats['membres_actifs'] ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="bg-slate-700 p-4 rounded-lg border border-slate-600">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-300 font-medium">Cours actifs</span>
                            <span class="text-3xl font-bold text-green-400">{{ $stats['cours_actifs'] ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="bg-slate-700 p-4 rounded-lg border border-slate-600">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-300 font-medium">Revenus mois</span>
                            <span class="text-3xl font-bold text-purple-400">${{ number_format($stats['revenus_mois'] ?? 0, 2) }}</span>
                        </div>
                    </div>
                    <div class="bg-slate-700 p-4 rounded-lg border border-slate-600">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-300 font-medium">Taux présence</span>
                            <span class="text-3xl font-bold text-orange-400">{{ $stats['taux_presence'] ?? 0 }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-slate-800 rounded-lg shadow-lg border border-slate-700 p-6">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-bolt mr-3 text-yellow-400"></i>
                    Actions Rapides
                </h3>
                <div class="space-y-4">
                    <a href="#" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg transition-colors block text-center font-bold text-lg">
                        <i class="fas fa-users mr-3"></i>Gérer Membres
                    </a>
                    <a href="#" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg transition-colors block text-center font-bold text-lg">
                        <i class="fas fa-calendar mr-3"></i>Voir Cours
                    </a>
                    <a href="#" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg transition-colors block text-center font-bold text-lg">
                        <i class="fas fa-chart-bar mr-3"></i>Rapports
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
