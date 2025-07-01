<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-module-header 
                title="Gestion des Ceintures"
                subtitle="Gestion de vos ceintures du réseau"
                icon="🥋"
                colors="orange-500,red-600"
                :action="true"
                actionText="Nouvelle Ceinture"
                :actionRoute="route('admin.ceintures.create')"
            />

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6"><!-- Contenu existant à intégrer --></div>
            </div>
        </div>
    </div>
</x-app-layout>
