<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-module-header 
                title="Créer une Ceinture"
                subtitle="Création d'un nouvel élément"
                icon="🥋"
                colors="orange-500,red-600"
                :action="false"
            />

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">🥋</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Création ceinture</h3>
                        <p class="text-gray-600 mb-6">Interface de création simplifiée</p>
                        
                        <div class="flex justify-center space-x-3">
                            <x-secondary-button href="{{ route('admin.ceintures.index') }}">
                                Retour à la liste
                            </x-secondary-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
