@extends('layouts.admin')
@section('title', 'Détails Ceinture')

@section('content')
<div class="space-y-6">
    <!-- Header avec x-module-header -->
    <x-module-header 
        module="ceinture"
        title="Détails Ceintures"
        subtitle="Informations détaillées"
        create-route="{{ route('admin.ceinture.create') }}"
        create-text="Nouveau"
        create-permission="create,App\Models\Ceinture"
    />

<div class="space-y-6">
    <!-- Header avec couleur orange -->
    <div class="bg-gradient-to-r from-orange-500 to-red-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-6">
                <!-- Badge ceinture -->
                <div class="w-16 h-16 rounded-xl flex items-center justify-center" style="background-color: {{ $ceinture->couleur }}">
                    <span class="text-2xl font-bold text-white">{{ $ceinture->ordre }}</span>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">{{ $ceinture->nom }}</h1>
                    <p class="text-orange-100 text-lg">Grade {{ $ceinture->ordre }} - {{ $ceinture->ordre <= 10 ? 'Kyu' : 'Dan' }}</p>
                </div>
            </div>
            <!-- Boutons d'action -->
            <div class="flex space-x-3">
                <a href="{{ route('admin.ceintures.attribuer', $ceinture) }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                    Attribuer
                </a>
                <a href="{{ route('admin.ceintures.edit', $ceinture) }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                    Modifier
                </a>
                <a href="{{ route('admin.ceintures.index') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                    Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne gauche - Informations principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations Ceinture -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
                <div class="bg-orange-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">Informations</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Nom</div>
                                <div class="text-white font-medium">{{ $ceinture->nom }}</div>
                            </div>
                            
                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Ordre</div>
                                <div class="text-white">{{ $ceinture->ordre }}</div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Couleur</div>
                                <div class="flex items-center">
                                    <div class="w-6 h-6 rounded-full mr-2" style="background-color: {{ $ceinture->couleur }}"></div>
                                    <span class="text-white">{{ $ceinture->couleur }}</span>
                                </div>
                            </div>
                            
                            <div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Type</div>
                                <div class="text-white">{{ $ceinture->ordre <= 10 ? 'Kyu (Grade inférieur)' : 'Dan (Grade supérieur)' }}</div>
                            </div>
                        </div>
                    </div>

                    @if($ceinture->description)
                    <div class="mt-6 pt-6 border-t border-slate-700">
                        <div class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Description</div>
                        <div class="text-white">{{ $ceinture->description }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Liste des utilisateurs -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
                <div class="bg-orange-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">Utilisateurs avec cette ceinture</h3>
                </div>
                <div class="overflow-x-auto">
                    @if($progressions->count() > 0)
                        <table class="min-w-full">
                            <thead class="bg-slate-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Utilisateur</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">École</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Statut</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-700">
                                @foreach($progressions as $progression)
                                <tr class="hover:bg-slate-700/50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                                                <span class="text-white font-bold text-sm">{{ substr($progression->user->name, 0, 2) }}</span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-white">{{ $progression->user->name }}</div>
                                                <div class="text-sm text-slate-400">{{ $progression->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-white">{{ $progression->date_obtention->format('d/m/Y') }}</div>
                                        <div class="text-xs text-slate-400">{{ $progression->date_obtention->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-300">
                                        {{ $progression->user->ecole->nom ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($progression->valide)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-300">
                                                ✅ Validée
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-900 text-yellow-300">
                                                ⏳ En attente
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if($progressions->hasPages())
                        <div class="px-6 py-4 border-t border-slate-700">
                            {{ $progressions->links() }}
                        </div>
                        @endif
                    @else
                        <div class="p-6 text-center">
                            <div class="text-slate-400">
                                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-slate-300">Aucun utilisateur</h3>
                                <p class="mt-1 text-sm text-slate-500">Cette ceinture n'a pas encore été attribuée</p>
                                <div class="mt-6">
                                    <a href="{{ route('admin.ceintures.attribuer', $ceinture) }}" 
                                       class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm">
                                        Attribuer cette ceinture
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne droite - Statistiques -->
        <div class="space-y-6">
            <!-- Statistiques -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
                <div class="bg-green-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">📊 Statistiques</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white">{{ $progressions->where('valide', true)->count() }}</div>
                        <div class="text-sm text-slate-400">Utilisateurs validés</div>
                    </div>
                    
                    <div class="border-t border-slate-700 pt-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400">En attente:</span>
                            <span class="text-yellow-400">{{ $progressions->where('valide', false)->count() }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400">Total:</span>
                            <span class="text-white">{{ $progressions->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
                <div class="bg-blue-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">⚡ Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.ceintures.attribuer', $ceinture) }}" 
                       class="w-full bg-green-600 hover:bg-green-700 text-white p-3 rounded-lg text-center block transition-colors">
                        🎯 Attribuer à un utilisateur
                    </a>
                    
                    <a href="{{ route('admin.ceintures.edit', $ceinture) }}" 
                       class="w-full bg-yellow-600 hover:bg-yellow-700 text-white p-3 rounded-lg text-center block transition-colors">
                        ✏️ Modifier cette ceinture
                    </a>
                    
                    @if($progressions->count() == 0)
                    <form method="POST" action="{{ route('admin.ceintures.destroy', $ceinture) }}" 
                          onsubmit="return confirm('Supprimer cette ceinture ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white p-3 rounded-lg transition-colors">
                            🗑️ Supprimer
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
