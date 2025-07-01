<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-module-header 
                title="Inscriptions Séminaire"
                subtitle="Gestion des inscriptions"
                icon="👥"
                colors="pink-500,purple-600"
                :action="false"
            />

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl">
                <div class="p-6">
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">👥</div>
                        <h3 class="text-lg font-semibold text-slate-100 tracking-tight flex items-center gap-3">Inscriptions Séminaire</h3>
                        <p class="text-gray-600 mb-6">Gestion des participants aux séminaires</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-md mx-auto">
                            <x-primary-button href="{{ route('admin.seminaires.index') }}" class="justify-center">
                                📋 Liste des séminaires
                            </x-primary-button>
                            <x-secondary-button href="{{ route('admin.seminaires.create') }}" class="justify-center">
                                ➕ Nouveau séminaire
                            </x-secondary-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
