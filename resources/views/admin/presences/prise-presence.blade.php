<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-module-header 
                title="Prise de Présence"
                subtitle="Enregistrement des présences"
                icon="✅"
                colors="teal-500,green-600"
                :action="false"
            />

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl">
                <div class="p-6">
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">✅</div>
                        <h3 class="text-lg font-semibold text-slate-100 tracking-tight flex items-center gap-3">Prise de Présence</h3>
                        <p class="text-gray-600 mb-6">Système d'enregistrement des présences</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-md mx-auto">
                            <x-primary-button href="{{ route('admin.presences.index') }}" class="justify-center">
                                📋 Toutes les présences
                            </x-primary-button>
                            <x-secondary-button href="{{ route('admin.presences.create') }}" class="justify-center">
                                ➕ Nouvelle présence
                            </x-secondary-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
