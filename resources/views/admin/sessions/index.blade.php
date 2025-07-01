@extends('layouts.admin')

@section('title', 'Sessions de cours')

@section('content')
<div class="space-y-6">

    <!-- Header avec votre système StudiosDB -->
    <x-module-header 
        module="cours"
        title="Sessions de cours" 
        subtitle="Gestion des sessions saisonnières"
        :createRoute="route('admin.sessions.create')"
        createText="Nouvelle session"
        createPermission="create-sessions"
    />

    <!-- Messages flash -->
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

    <!-- Table avec vos composants StudiosDB -->
    @if($sessions->count() > 0)
        <x-admin-table 
            :headers="auth()->user()->hasRole('super_admin') ? 
                ['Session', 'École', 'Période', 'Statut', 'Horaires', 'Inscriptions', 'Actions'] : 
                ['Session', 'Période', 'Statut', 'Horaires', 'Inscriptions', 'Actions']"
            :searchable="true"
            :filterable="true"
            :exportable="true">
            
            @foreach($sessions as $session)
            <tr class="hover:bg-slate-700/30 transition-colors">
                <td class="w-4">
                    <input type="checkbox" class="w-4 h-4 text-purple-500 bg-slate-700/50 border-slate-600/50 rounded focus:ring-purple-500">
                </td>
                
                <!-- Session -->
                <td>
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <span class="text-2xl">📅</span>
                        </div>
                        <div>
                            <div class="font-medium text-white">{{ $session->nom }}</div>
                            @if($session->description)
                            <div class="text-sm text-slate-400">{{ Str::limit($session->description, 50) }}</div>
                            @endif
                        </div>
                    </div>
                </td>
                
                @if(auth()->user()->hasRole('super_admin'))
                <!-- École -->
                <td>
                    <div class="text-sm text-white">{{ $session->ecole->nom }}</div>
                    <div class="text-xs text-slate-400">{{ $session->ecole->code_ecole }}</div>
                </td>
                @endif
                
                <!-- Période -->
                <td>
                    <div class="text-sm text-white">
                        {{ $session->date_debut->format('d/m/Y') }} - {{ $session->date_fin->format('d/m/Y') }}
                    </div>
                    <div class="text-xs text-slate-400">
                        {{ $session->date_debut->diffInDays($session->date_fin) }} jours
                        @if($session->date_debut > now())
                            • <span class="text-blue-400">Commence {{ $session->date_debut->diffForHumans() }}</span>
                        @elseif($session->date_fin < now())
                            • <span class="text-gray-400">Terminée {{ $session->date_fin->diffForHumans() }}</span>
                        @else
                            • <span class="text-green-400">En cours</span>
                        @endif
                    </div>
                </td>
                
                <!-- Statut avec vos badges StudiosDB -->
                <td>
                    <div class="flex flex-col space-y-1">
                        @if($session->actif)
                            <span class="studiosdb-badge studiosdb-badge-active">
                                ✅ Active
                            </span>
                        @else
                            <span class="studiosdb-badge studiosdb-badge-inactive">
                                ⏸️ Inactive
                            </span>
                        @endif
                        
                        @if($session->inscriptions_ouvertes ?? false)
                            <span class="studiosdb-badge studiosdb-badge-cours">
                                📝 Inscriptions ouvertes
                            </span>
                        @endif
                    </div>
                </td>
                
                <!-- Horaires -->
                <td>
                    <div class="text-sm text-white">{{ $session->coursHoraires->count() }} horaires</div>
                    <div class="text-xs text-slate-400">
                        {{ $session->coursHoraires->pluck('cours_id')->unique()->count() }} cours
                    </div>
                </td>
                
                <!-- Inscriptions -->
                <td>
                    <div class="text-sm text-white">
                        {{ $session->inscriptionsHistorique->where('statut', 'active')->count() }} inscriptions
                    </div>
                    @if($session->date_limite_inscription)
                    <div class="text-xs text-slate-400">
                        Limite: {{ $session->date_limite_inscription->format('d/m/Y') }}
                    </div>
                    @endif
                </td>
                
                <!-- Actions avec dropdown -->
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
                                @can('view', $session)
                                <a href="{{ route('admin.sessions.show', $session) }}" 
                                   class="flex items-center px-4 py-2 text-sm text-slate-300 hover:bg-slate-700">
                                    <span class="mr-2">👁️</span>
                                    Voir détails
                                </a>
                                @endcan
                                
                                @can('update', $session)
                                <a href="{{ route('admin.sessions.edit', $session) }}" 
                                   class="flex items-center px-4 py-2 text-sm text-slate-300 hover:bg-slate-700">
                                    <span class="mr-2">✏️</span>
                                    Modifier
                                </a>
                                @endcan
                                
                                @can('delete', $session)
                                <div class="border-t border-slate-700 my-1"></div>
                                <form method="POST" action="{{ route('admin.sessions.destroy', $session) }}" 
                                      x-data="{ 
                                          confirm() { 
                                              return window.confirm('Êtes-vous sûr de vouloir supprimer cette session ?') 
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
            <div class="text-6xl mb-6">📅</div>
            <h3 class="text-xl font-medium text-white mb-3">Aucune session créée</h3>
            <p class="text-slate-400 mb-8 max-w-md mx-auto">
                Commencez par créer votre première session saisonnière pour organiser vos cours par périodes.
            </p>
            
            @can('create-sessions')
            <a href="{{ route('admin.sessions.create') }}" 
               class="studiosdb-btn studiosdb-btn-cours studiosdb-btn-lg">
                <span class="mr-2">➕</span>
                Créer la première session
            </a>
            @endcan
        </div>
    @endif

</div>
@endsection
