<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-module-header 
                title="Détails Paiement"
                subtitle="Détails de l'élément"
                icon="💰"
                colors="yellow-500,orange-600"
                :action="false"
            />

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Contenu détaillé -->
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">💰</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Vue détaillée</h3>
                        <p class="text-gray-600 mb-6">Interface standardisée StudiosDB v4.1.10.2</p>
                        
                        <!-- Actions -->
                        <div class="flex justify-center space-x-3">
                            <x-secondary-button href="{{ route('admin.paiements.index') }}">
                                Retour à la liste
                            </x-secondary-button>
                            {{-- 
                            <x-primary-button href="{{ route('admin.paiements.edit', $item) }}">
                                Modifier
                            </x-primary-button>
                            --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
