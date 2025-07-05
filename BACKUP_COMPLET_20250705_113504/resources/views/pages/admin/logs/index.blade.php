<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-module-header 
                title="Gestion des Logs"
                subtitle="Consultation des logs système"
                icon="📝"
                colors="gray-500,slate-600"
                :action="true"
                actionText="Actualiser"
                :actionRoute="route('admin.logs.index')"
            />

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl">
                            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl">
                <div class="p-6"><!-- Contenu existant à intégrer --></div>
            </div>
        </div>
    </div>
</x-app-layout>
            </div>
        </div>
    </div>
</x-app-layout>
