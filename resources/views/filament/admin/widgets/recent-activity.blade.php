<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            🕒 Activité Récente
        </x-slot>

        <div class="space-y-3">
            <div class="flex items-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                        Nouvel utilisateur inscrit
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Il y a 2 heures
                    </p>
                </div>
            </div>

            <div class="flex items-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                        Cours de karaté terminé
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Il y a 3 heures
                    </p>
                </div>
            </div>

            <div class="flex items-center p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                        Paiement reçu - 85,00 $
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Il y a 5 heures
                    </p>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
