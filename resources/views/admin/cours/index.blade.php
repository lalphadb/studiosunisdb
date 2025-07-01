@extends('layouts.admin')

@section('title', 'Cours')

@section('content')
<div class="space-y-6">

    <!-- Header avec votre système StudiosDB -->
    <x-module-header 
        module="cours"
        title="Cours" 
        subtitle="Gestion des cours et programmes"
        :createRoute="route('admin.cours.create')"
        createText="Nouveau cours"
        createPermission="create-cours"
    />

    <!-- Messages flash avec vos styles -->
    @if(session('success'))
        <div class="studiosdb-card border-l-4 border-green-500 bg-green-500/10">
            <div class="flex">
                <span class="text-green-400 text-xl mr-3">✅</span>
                <p class="text-sm font-medium text-green-300">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="studiosdb-card border-l-4 border-red-500 bg-red-500/10">
            <div class="flex">
                <span class="text-red-400 text-xl mr-3">❌</span>
                <p class="text-sm font-medium text-red-300">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Table avec vos styles StudiosDB -->
    @if($cours->count() > 0)
        <x-admin-table 
            :headers="auth()->user()->hasRole('super_admin') ? 
                ['Cours', 'École', 'Niveau', 'Statut', 'Horaires', 'Actions'] : 
                ['Cours', 'Niveau', 'Statut', 'Horaires', 'Actions']"
            :searchable="true"
            :filterable="true"
            :exportable="true">
            
            @foreach($cours as $cour)
            <tr class="hover:bg-slate-700/30 transition-colors">
                <td class="w-4">
                    <input type="checkbox" class="w-4 h-4 text-purple-500 bg-slate-700/50 border-slate-600/50 rounded focus:ring-purple-500">
                </td>
                
                <!-- Cours -->
                <td>
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <span class="text-2xl">📚</span>
                        </div>
                        <div>
                            <div class="font-medium text-white">{{ $cour->nom }}</div>
                            @if($cour->description)
                            <div class="text-sm text-slate-400">{{ Str::limit($cour->description, 50) }}</div>
                            @endif
                        </div>
                    </div>
                </td>
                
                @if(auth()->user()->hasRole('super_admin'))
                <!-- École -->
                <td>
                    <div class="text-sm text-white">{{ $cour->ecole->nom }}</div>
                    <div class="text-xs text-slate-400">{{ $cour->ecole->code_ecole }}</div>
                </td>
                @endif
                
                <!-- Niveau -->
                <td>
                    <div class="text-sm text-white">{{ $cour->niveau ?? 'Non défini' }}</div>
                    @if($cour->instructeur_defaut)
                    <div class="text-xs text-slate-400">{{ $cour->instructeur_defaut }}</div>
                    @endif
                </td>
                
                <!-- Statut avec vos badges StudiosDB -->
                <td>
                    @if($cour->active)
                        <span class="studiosdb-badge studiosdb-badge-active">
                            ✅ Actif
                        </span>
                    @else
                        <span class="studiosdb-badge studiosdb-badge-inactive">
                            ⏸️ Inactif
                        </span>
                    @endif
                </td>
                
                <!-- Horaires -->
                <td>
                    <div class="text-sm text-white">{{ $cour->cours_horaires_count ?? 0 }} horaires</div>
                    <div class="text-xs text-slate-400">
                        @if($cour->capacite_max_defaut)
                        Max: {{ $cour->capacite_max_defaut }} places
                        @endif
                    </div>
                </td>
                
                <!-- Actions -->
                <td>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="studiosdb-btn studiosdb-btn-cours text-xs px-3 py-1">
                            Actions
                            <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition
                             class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-slate-800 ring-1 ring-black ring-opacity-5 z-10">
                            <div class="py-1">
                                @can('view', $cour)
                                <a href="{{ route('admin.cours.show', $cour) }}" 
                                   class="flex items-center px-4 py-2 text-sm text-slate-300 hover:bg-slate-700">
                                    <span class="mr-2">👁️</span>
                                    Voir détails
                                </a>
                                @endcan
                                
                                @can('update', $cour)
                                <a href="{{ route('admin.cours.edit', $cour) }}" 
                                   class="flex items-center px-4 py-2 text-sm text-slate-300 hover:bg-slate-700">
                                    <span class="mr-2">✏️</span>
                                    Modifier
                                </a>
                                @endcan
                                
                                @can('delete', $cour)
                                <div class="border-t border-slate-700 my-1"></div>
                                <form method="POST" action="{{ route('admin.cours.destroy', $cour) }}" 
                                      x-data="{ 
                                          confirm() { 
                                              return window.confirm('Êtes-vous sûr de vouloir supprimer ce cours ?') 
                                          } 
                                      }"
                                      @submit.prevent="confirm() && $el.submit()">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-400 hover:bg-red-900/20">
                                        <span class="mr-2">🗑️</span>
                                        Supprimer
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </x-admin-table>
    @else
        <!-- État vide avec vos styles StudiosDB -->
        <div class="studiosdb-card text-center py-16">
            <div class="text-6xl mb-6">📚</div>
            <h3 class="text-xl font-medium text-white mb-3">Aucun cours créé</h3>
            <p class="text-slate-400 mb-8 max-w-md mx-auto">
                Commencez par créer vos premiers cours pour organiser votre enseignement.
            </p>
            
            @can('create-cours')
            <a href="{{ route('admin.cours.create') }}" 
               class="studiosdb-btn studiosdb-btn-cours studiosdb-btn-lg">
                <span class="mr-2">➕</span>
                Créer le premier cours
            </a>
            @endcan
        </div>
    @endif

</div>
@endsection
