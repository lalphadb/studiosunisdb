<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-module-header 
                title="Gestion des Utilisateurs"
                subtitle="Gestion de vos utilisateurs du réseau"
                icon="👤"
                colors="blue-500,cyan-600"
                :action="true"
                actionText="Nouvel Utilisateur"
                :actionRoute="route('admin.users.create')"
            />

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Votre contenu existant ici -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
