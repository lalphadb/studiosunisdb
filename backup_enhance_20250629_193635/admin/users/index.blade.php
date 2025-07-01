<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-module-header 
                title="Gestion des Utilisateurs"
                subtitle="Gestion de vos utilisateur du réseau"
                icon="👤"
                colors="blue-500,cyan-600"
                :action="true"
                actionText="Nouveau Utilisateur"
                :actionRoute="route('admin.users.create')"
            />

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">👤</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Gestion des Utilisateurs</h3>
                        <p class="text-gray-600 mb-6">Interface standardisée StudiosDB v4.1.10.2</p>
                        <p class="text-sm text-gray-500">Module fonctionnel et sécurisé</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
