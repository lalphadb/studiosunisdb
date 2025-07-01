<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-module-header 
                title="Gestion des Exports"
                subtitle="Exportation des données StudiosDB"
                icon="📤"
                colors="indigo-500,blue-600"
                :action="false"
            />

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-gradient-to-r from-blue-500/15 via-blue-600/20 to-cyan-500/15 p-6 rounded-xl text-white">
                            <h3 class="text-lg font-semibold text-slate-100 tracking-tight flex items-center gap-3">📊 Export Utilisateurs</h3>
                            <p class="mb-4 opacity-90">Exporter la liste complète des utilisateurs</p>
                            <x-primary-button href="#" class="bg-white/20 hover:bg-white/30 text-white border-white/20">
                                Télécharger PDF
                            </x-primary-button>
                        </div>
                        
                        <div class="bg-gradient-to-r from-green-500/15 via-green-600/20 to-emerald-500/15 p-6 rounded-xl text-white">
                            <h3 class="text-lg font-semibold text-slate-100 tracking-tight flex items-center gap-3">🏫 Export Écoles</h3>
                            <p class="mb-4 opacity-90">Exporter les données des écoles</p>
                            <x-primary-button href="#" class="bg-white/20 hover:bg-white/30 text-white border-white/20">
                                Télécharger Excel
                            </x-primary-button>
                        </div>
                        
                        <div class="bg-gradient-to-r from-purple-500/15 via-purple-600/20 to-indigo-500/15 p-6 rounded-xl text-white">
                            <h3 class="text-lg font-semibold text-slate-100 tracking-tight flex items-center gap-3">📚 Export Cours</h3>
                            <p class="mb-4 opacity-90">Exporter les données des cours</p>
                            <x-primary-button href="#" class="bg-white/20 hover:bg-white/30 text-white border-white/20">
                                Télécharger CSV
                            </x-primary-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
