<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-module-header 
                title="Gestion des Paiements"
                subtitle="Gestion de vos paiements du réseau"
                icon="💰"
                colors="yellow-500,orange-600"
                :action="true"
                actionText="Nouveau Paiement"
                :actionRoute="route('admin.paiements.create')"
            />

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6"><!-- Contenu existant à intégrer --></div>
            </div>
        </div>
    </div>
</x-app-layout>
            </div>
        </div>
    </div>
</x-app-layout>
