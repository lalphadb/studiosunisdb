@extends('layouts.admin')

@section('title', 'Gestion des Séminaires')

@section('content')
<div class="space-y-6">
    <!-- Header Module Séminaires -->
    <div class="gradient-seminaires text-white p-8 rounded-2xl border border-rose-500/20 relative overflow-hidden backdrop-blur-sm studiosdb-fade-in">
        <div class="absolute inset-0 bg-gradient-to-br from-white/3 via-transparent to-white/2"></div>
        
        <div class="relative z-10 flex justify-between items-center">
            <div class="flex items-center space-x-6">
                <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur border border-white/20">
                    <x-admin-icon name="seminaires" size="w-8 h-8" color="text-white" />
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white drop-shadow-sm">Gestion des Séminaires</h1>
                    <p class="text-lg text-white/90 font-medium">Administration des séminaires et événements spéciaux</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.seminaires.create') }}" 
                   class="bg-white/15 hover:bg-white/25 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 flex items-center space-x-3 backdrop-blur border border-white/20">
                    <x-admin-icon name="plus" size="w-5 h-5" color="text-white" />
                    <span>Nouveau Séminaire</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards SÉCURISÉES -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="studiosdb-card hover:scale-105 cursor-pointer group">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Total Séminaires</p>
                    <p class="text-3xl font-bold text-white group-hover:text-rose-400 transition-colors">
                        {{ isset($seminaires) ? (method_exists($seminaires, 'total') ? $seminaires->total() : $seminaires->count()) : 0 }}
                    </p>
                    <p class="text-xs text-slate-500 mt-1">Tous événements</p>
                </div>
                <div class="w-14 h-14 bg-rose-500/20 border-rose-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="seminaires" size="w-7 h-7" color="text-rose-400" />
                </div>
            </div>
        </div>
        
        <div class="studiosdb-card">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">À Venir</p>
                    <p class="text-3xl font-bold text-emerald-400">0</p>
                    <p class="text-xs text-slate-500 mt-1">Prochainement</p>
                </div>
                <div class="w-14 h-14 bg-emerald-500/20 border-emerald-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="presences" size="w-7 h-7" color="text-emerald-400" />
                </div>
            </div>
        </div>
        
        <div class="studiosdb-card">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Participants</p>
                    <p class="text-3xl font-bold text-blue-400">0</p>
                    <p class="text-xs text-slate-500 mt-1">Total inscrits</p>
                </div>
                <div class="w-14 h-14 bg-blue-500/20 border-blue-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="users" size="w-7 h-7" color="text-blue-400" />
                </div>
            </div>
        </div>
        
        <div class="studiosdb-card">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">Ce Mois</p>
                    <p class="text-3xl font-bold text-violet-400">0</p>
                    <p class="text-xs text-slate-500 mt-1">Nouveaux</p>
                </div>
                <div class="w-14 h-14 bg-violet-500/20 border-violet-500/30 rounded-2xl flex items-center justify-center border">
                    <x-admin-icon name="stats" size="w-7 h-7" color="text-violet-400" />
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="studiosdb-card">
        <div class="overflow-x-auto">
            <table class="studiosdb-table">
                <thead>
                    <tr>
                        <th>Séminaire</th>
                        <th>Date</th>
                        <th>Participants</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($seminaires ?? [] as $seminaire)
                        <tr class="hover:bg-slate-700/20 transition-colors">
                            <td>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-rose-500 to-pink-500 rounded-full flex items-center justify-center">
                                        <span class="text-white font-medium">{{ substr($seminaire->nom ?? 'S', 0, 1) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-white font-medium">{{ $seminaire->nom ?? 'Séminaire' }}</div>
                                        <div class="text-slate-400 text-sm">{{ Str::limit($seminaire->description ?? 'Pas de description', 50) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-slate-300">
                                {{ isset($seminaire->date_debut) ? $seminaire->date_debut->format('d/m/Y') : 'Non définie' }}
                            </td>
                            <td>
                                <span class="text-blue-400 font-medium">0</span>
                            </td>
                            <td>
                                <span class="studiosdb-badge studiosdb-badge-pending">À venir</span>
                            </td>
                            <td>
                                <x-actions-dropdown :model="$seminaire" module="seminaires" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <x-empty-state
                                    icon="seminaires"
                                    title="Aucun séminaire trouvé"
                                    description="Créez votre premier séminaire pour organiser des événements spéciaux."
                                    action-label="Créer un séminaire"
                                    :action-route="route('admin.seminaires.create')"
                                    action-color="rose"
                                />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
