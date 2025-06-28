<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mon Espace Membre') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Header Membre -->
            <div class="bg-gradient-to-r from-blue-500 to-cyan-600 rounded-xl p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold">🥋 Bienvenue {{ auth()->user()->name }}</h1>
                        <p class="text-blue-100">{{ auth()->user()->ecole->nom ?? 'Votre école de karaté' }}</p>
                    </div>
                    <div class="text-right">
                        <div class="bg-blue-500 bg-opacity-50 px-4 py-2 rounded-lg">
                            <div class="text-sm text-blue-100">👤 Membre</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ma Progression -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        🏆 Ma Progression
                    </h3>
                    
                    @php
                        $derniereCeinture = auth()->user()->userCeintures()
                            ->with('ceinture')
                            ->where('valide', true)
                            ->latest('date_obtention')
                            ->first();
                    @endphp
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Ceinture Actuelle -->
                        <div class="bg-gradient-to-br from-orange-100 to-red-100 dark:from-orange-900 dark:to-red-900 p-4 rounded-lg">
                            <div class="flex items-center">
                                @if($derniereCeinture)
                                <div class="w-10 h-10 rounded-full mr-3" 
                                     style="background-color: {{ $derniereCeinture->ceinture->couleur }}"></div>
                                <div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $derniereCeinture->ceinture->nom }}
                                    </div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        Obtenue le {{ $derniereCeinture->date_obtention->format('d/m/Y') }}
                                    </div>
                                </div>
                                @else
                                <div class="w-10 h-10 bg-white rounded-full mr-3"></div>
                                <div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">Ceinture Blanche</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Débutant</div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Temps Pratique -->
                        <div class="bg-gradient-to-br from-blue-100 to-cyan-100 dark:from-blue-900 dark:to-cyan-900 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                {{ auth()->user()->created_at->diffInMonths(now()) }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Mois de pratique</div>
                        </div>
                        
                        <!-- Cours Suivis -->
                        <div class="bg-gradient-to-br from-green-100 to-teal-100 dark:from-green-900 dark:to-teal-900 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                {{ auth()->user()->inscriptionsCours()->count() }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Cours inscrits</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mes Cours -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        📚 Mes Cours
                    </h3>
                    
                    @php
                        $mesCours = auth()->user()->inscriptionsCours()->with('cours')->get();
                    @endphp
                    
                    @if($mesCours->count() > 0)
                        <div class="space-y-3">
                            @foreach($mesCours as $inscription)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $inscription->cours->nom }}
                                    </div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $inscription->cours->description }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $inscription->cours->niveau ?? 'Tous niveaux' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        Inscrit le {{ $inscription->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <div class="text-4xl mb-2">📝</div>
                            <p>Vous n'êtes inscrit à aucun cours pour le moment.</p>
                            <p class="text-sm">Contactez votre école pour vous inscrire.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Historique Ceintures -->
            @if(auth()->user()->userCeintures()->count() > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        🏅 Mon Historique de Ceintures
                    </h3>
                    
                    <div class="space-y-3">
                        @foreach(auth()->user()->userCeintures()->with('ceinture')->orderBy('date_obtention', 'desc')->get() as $attribution)
                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="w-8 h-8 rounded-full mr-3" 
                                 style="background-color: {{ $attribution->ceinture->couleur }}"></div>
                            <div class="flex-1">
                                <div class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ $attribution->ceinture->nom }}
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    Obtenue le {{ $attribution->date_obtention->format('d/m/Y') }}
                                </div>
                            </div>
                            <div class="text-right">
                                @if($attribution->valide)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                    ✅ Validée
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                    ⏳ En attente
                                </span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
