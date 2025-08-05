<template>
    <Head title="[MODULE] - StudiosDB v5 Pro" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-semibold text-2xl text-white leading-tight">
                        Gestion des [MODULE]
                    </h2>
                    <p class="text-sm text-gray-400 mt-1">
                        {{ stats.total_[module] }} [module] • {{ stats.[module]_actifs }} actifs
                    </p>
                </div>
                <Link
                    :href="route('[module].create')"
                    class="bg-blue-600 hover:bg-blue-500 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center space-x-2 shadow-lg hover:shadow-xl"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Nouveau [MODULE]</span>
                </Link>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

                <!-- KPI CARDS -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-700/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-400 text-sm font-medium">Total [MODULE]</p>
                                <p class="text-white text-3xl font-bold">{{ stats.total_[module] }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-600/20 rounded-xl flex items-center justify-center">
                                <span class="text-2xl">[ICON]</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Répéter pour 3 autres KPI -->
                </div>

                <!-- FILTRES -->
                <div class="bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-700/50">
                    <h3 class="text-lg font-semibold text-white mb-4">Filtres et Recherche</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Recherche</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input
                                    v-model="filters.search"
                                    type="text"
                                    placeholder="Rechercher..."
                                    class="block w-full pl-10 pr-3 py-3 bg-gray-700 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    @input="debounceSearch"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CONTENU PRINCIPAL -->
                <div class="bg-gray-800 rounded-2xl shadow-lg border border-gray-700/50 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-700/50">
                        <h3 class="text-lg font-semibold text-white">Liste des [MODULE]</h3>
                    </div>
                    
                    <!-- TABLEAU MODERNE -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-700/50">
                            <thead class="bg-gray-900/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">
                                        [COLONNE]
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-800 divide-y divide-gray-700/30">
                                <tr class="hover:bg-gray-700/30 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-white">
                                        [CONTENU]
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { reactive, computed } from 'vue'
import { Link, router, Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    [module]: Object,
    stats: Object,
    filters: Object
})

const filters = reactive({
    search: props.filters.search || '',
    // Ajouter autres filtres
})

// Méthodes standard
let searchTimeout = null
const debounceSearch = () => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(applyFilters, 300)
}

const applyFilters = () => {
    router.get(route('[module].index'), filters, {
        preserveState: true,
        replace: true
    })
}
</script>
