<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-module-header 
                title="Validation Rapide"
                subtitle="Validation express des paiements"
                icon="🚀"
                colors="yellow-500,orange-600"
                :action="false"
            />

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl">
                <div class="p-6">
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">🚀</div>
                        <h3 class="text-lg font-semibold text-slate-100 tracking-tight flex items-center gap-3">Validation Rapide</h3>
                        <p class="text-gray-600 mb-6">Interface de validation express pour les paiements</p>
                        
                        <div class="bg-green-50 border border-green-200 rounded-xl p-6 max-w-md mx-auto mb-6">
                            <h4 class="font-medium text-green-900 mb-2">Validation en un clic</h4>
                            <p class="text-sm text-green-700">Validez rapidement les paiements en attente</p>
                        </div>
                        
                        <x-primary-button href="{{ route('admin.paiements.index') }}" class="justify-center">
                            💰 Gérer les paiements
                        </x-primary-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
