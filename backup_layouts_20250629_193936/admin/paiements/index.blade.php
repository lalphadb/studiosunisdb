<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-module-header 
                title="Gestion des Paiements"
                subtitle="Gestion des paiements du réseau"
                icon="💰"
                colors="yellow-500,orange-600"
                :action="true"
                actionText="Nouveau Paiement"
                :actionRoute="route('admin.paiements.create')"
            />

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- En-tête du module -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <div class="text-4xl mr-4">💰</div>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">Gestion des Paiements</h2>
                                <p class="text-gray-600">Interface de gestion professionnelle</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-500">
                            StudiosDB v4.1.10.2
                        </div>
                    </div>

                    <!-- Zone de contenu -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Statistiques -->
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-2">Statistiques</h4>
                                <p class="text-3xl font-bold text-blue-600">{{ \App\Models\Paiement::count() ?? 0 }}</p>
                                <p class="text-sm text-gray-600">Total des éléments</p>
                            </div>
                            
                            <!-- Actions -->
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-4">Actions</h4>
                                <div class="space-y-2">
                                    <x-primary-button href="{{ route('admin.paiements.create') }}" size="sm" class="w-full justify-center">
                                        ➕ Créer
                                    </x-primary-button>
                                    <x-secondary-button href="#" size="sm" class="w-full justify-center">
                                        📊 Exporter
                                    </x-secondary-button>
                                </div>
                            </div>
                            
                            <!-- Informations -->
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-2">Module</h4>
                                <p class="text-sm text-gray-600 mb-2">✅ Interface standardisée</p>
                                <p class="text-sm text-gray-600 mb-2">🔒 Sécurisé avec CSRF</p>
                                <p class="text-sm text-gray-600">🎨 Design professionnel</p>
                            </div>
                        </div>
                    </div>

                    <!-- Liste des éléments -->
                    <div class="mt-8">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="text-blue-600 mr-3">ℹ️</div>
                                <div>
                                    <p class="font-medium text-blue-900">Module Gestion des Paiements</p>
                                    <p class="text-sm text-blue-700">Liste et gestion des éléments du module. Interface prête pour intégration des données.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
