@extends('layouts.admin')

@section('title', $ecole->nom)

@section('content')
<div class="flex-1 flex flex-col overflow-hidden">
    <!-- Header avec module-header -->
    <x-module-header 
        module="ecoles" 
        title="{{ $ecole->nom }}" 
        subtitle="🏫 Détails de l'école"
    />

    <div class="flex-1 overflow-x-hidden overflow-y-auto p-6 space-y-6">
        
        <!-- Informations de l'école -->
        <div class="studiosdb-card">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <x-admin-icon name="ecoles" size="w-6 h-6" color="text-emerald-400" />
                    <h2 class="text-xl font-bold text-white">Informations de l'École</h2>
                </div>
                @can('update', $ecole)
                <a href="{{ route('admin.ecoles.edit', $ecole) }}" 
                   class="studiosdb-btn studiosdb-btn-ecoles">
                    ✏️ Modifier
                </a>
                @endcan
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Colonne gauche -->
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-slate-400">Nom de l'école</label>
                        <div class="text-white font-medium">{{ $ecole->nom }}</div>
                    </div>
                    
                    @if($ecole->code)
                    <div>
                        <label class="text-sm font-medium text-slate-400">Code école</label>
                        <div class="text-white">{{ $ecole->code }}</div>
                    </div>
                    @endif
                    
                    @if($ecole->telephone)
                    <div>
                        <label class="text-sm font-medium text-slate-400">Téléphone</label>
                        <div class="text-white">{{ $ecole->telephone }}</div>
                    </div>
                    @endif
                    
                    @if($ecole->email)
                    <div>
                        <label class="text-sm font-medium text-slate-400">Email</label>
                        <div class="text-white">{{ $ecole->email }}</div>
                    </div>
                    @endif
                </div>
                
                <!-- Colonne droite -->
                <div class="space-y-4">
                    @if($ecole->adresse)
                    <div>
                        <label class="text-sm font-medium text-slate-400">Adresse</label>
                        <div class="text-white">
                            {{ $ecole->adresse }}<br>
                            {{ $ecole->ville }} {{ $ecole->code_postal }}
                        </div>
                    </div>
                    @endif
                    
                    <div>
                        <label class="text-sm font-medium text-slate-400">Statut</label>
                        <div>
                            @if($ecole->active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                    ✅ Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                    ❌ Inactive
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            @if($ecole->description)
            <div class="mt-6 pt-6 border-t border-slate-700">
                <label class="text-sm font-medium text-slate-400">Description</label>
                <div class="text-white mt-2">{{ $ecole->description }}</div>
            </div>
            @endif
        </div>

        <!-- Statistiques -->
        <div class="studiosdb-card">
            <div class="flex items-center space-x-3 mb-6">
                <x-admin-icon name="users" size="w-6 h-6" color="text-blue-400" />
                <h2 class="text-xl font-bold text-white">📊 Statistiques</h2>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-blue-500/10 border border-blue-500/30 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-blue-400">{{ $ecole->users->count() }}</div>
                    <div class="text-sm text-slate-300">Membres</div>
                </div>
                
                <div class="bg-violet-500/10 border border-violet-500/30 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-violet-400">{{ $ecole->cours->count() }}</div>
                    <div class="text-sm text-slate-300">Cours</div>
                </div>
                
                <div class="bg-rose-500/10 border border-rose-500/30 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-rose-400">{{ $ecole->seminaires->count() }}</div>
                    <div class="text-sm text-slate-300">Séminaires</div>
                </div>
                
                <div class="bg-emerald-500/10 border border-emerald-500/30 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-emerald-400">{{ $ecole->users->where('active', true)->count() }}</div>
                    <div class="text-sm text-slate-300">Actifs</div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="studiosdb-card">
            <div class="flex items-center space-x-3 mb-6">
                <x-admin-icon name="ecoles" size="w-6 h-6" color="text-emerald-400" />
                <h2 class="text-xl font-bold text-white">🚀 Actions</h2>
            </div>
            
            <div class="flex flex-wrap gap-4">
                @can('update', $ecole)
                <a href="{{ route('admin.ecoles.edit', $ecole) }}" 
                   class="studiosdb-btn studiosdb-btn-ecoles">
                    ✏️ Modifier l'école
                </a>
                @endcan
                
                <a href="{{ route('admin.users.index') }}" 
                   class="studiosdb-btn studiosdb-btn-users">
                    👥 Voir les membres
                </a>
                
                <a href="{{ route('admin.cours.index') }}" 
                   class="studiosdb-btn studiosdb-btn-cours">
                    🥋 Voir les cours
                </a>
                
                @can('viewAny', App\Models\Ecole::class)
                <a href="{{ route('admin.ecoles.index') }}" 
                   class="studiosdb-btn studiosdb-btn-cancel">
                    ← Retour à la liste
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
