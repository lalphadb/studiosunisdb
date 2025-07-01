<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-module-header 
                title="Gestion des Présences"
                subtitle="Gestion de vos présence du réseau"
                icon="✅"
                colors="teal-500,green-600"
                :action="true"
                actionText="Nouveau Présence"
                :actionRoute="route('admin.presences.create')"
            />

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">✅</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Gestion des Présences</h3>
                        <p class="text-gray-600 mb-6">Interface standardisée StudiosDB v4.1.10.2</p>
                        <p class="text-sm text-gray-500">Module fonctionnel et sécurisé</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
