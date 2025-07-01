<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-module-header 
                title="Actions de Masse"
                subtitle="Traitement groupé des paiements"
                icon="⚡"
                colors="yellow-500,orange-600"
                :action="false"
            />

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">⚡</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Actions de Masse</h3>
                        <p class="text-gray-600 mb-6">Traitement de plusieurs paiements simultanément</p>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 max-w-md mx-auto mb-6">
                            <h4 class="font-medium text-blue-900 mb-2">Fonctionnalités disponibles</h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• Validation en lot</li>
                                <li>• Changement de statut</li>
                                <li>• Export groupé</li>
                                <li>• Notifications automatiques</li>
                            </ul>
                        </div>
                        
                        <x-primary-button href="{{ route('admin.paiements.index') }}" class="justify-center">
                            💰 Retour aux paiements
                        </x-primary-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
