<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-module-header 
                title="Gestion des Cours"
                subtitle="Gestion de vos cours du réseau"
                icon="📚"
                colors="purple-500,indigo-600"
                :action="true"
                actionText="Nouveau Cours"
                :actionRoute="route('admin.cours.create')"
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
