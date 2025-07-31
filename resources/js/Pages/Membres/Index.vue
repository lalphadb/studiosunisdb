<template>
    <Head title="Gestion des Membres - StudiosDB v5 Pro" />
    <AuthenticatedLayout>
        <template #toolbar>
            <div class="flex justify-end items-center w-full">
                <button
                    @click="toggleTheme"
                    class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-xl shadow transition-all duration-200 flex items-center space-x-2"
                >
                    <span v-if="theme === 'dark'">🌙 Sombre</span>
                    <span v-else>☀️ Clair</span>
                </button>
            </div>
        </template>
        <template #header>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-semibold text-2xl text-white leading-tight">
                        Gestion des Membres
                    </h2>
                    <p class="text-sm text-gray-400 mt-1">
                        {{ membres?.total || 0 }} membres inscrits • {{ membresActifs }} actifs
                    </p>
                </div>
                <Link
                :href="'/membres/create'"
                class="bg-blue-600 hover:bg-blue-500 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center space-x-2 shadow-lg hover:shadow-xl"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    <span>Nouveau Membre</span>
                </Link>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

                <!-- Statistiques rapides - Pattern Dashboard -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-700/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-400 text-sm font-medium">Total Membres</p>
                                <p class="text-white text-3xl font-bold">{{ membres?.total || 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-600/20 rounded-xl flex items-center justify-center">
                                <span class="text-2xl">👥</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-700/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-400 text-sm font-medium">Membres Actifs</p>
                                <p class="text-white text-3xl font-bold">{{ membresActifs }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-600/20 rounded-xl flex items-center justify-center">
                                <span class="text-2xl">✅</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-700/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-400 text-sm font-medium">Nouveaux ce mois</p>
                                <p class="text-white text-3xl font-bold">{{ nouveauxMembres }}</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-600/20 rounded-xl flex items-center justify-center">
                                <span class="text-2xl">🆕</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-700/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-400 text-sm font-medium">En retard paiement</p>
                                <p class="text-white text-3xl font-bold">{{ membresEnRetard }}</p>
                            </div>
                            <div class="w-12 h-12 bg-orange-600/20 rounded-xl flex items-center justify-center">
                                <span class="text-2xl">⚠️</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtres modernes - Pattern Dashboard -->
                <div class="bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-700/50">
                    <h3 class="text-lg font-semibold text-white mb-4">Filtres et Recherche</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="search-input" class="block text-sm font-medium text-gray-300 mb-2">Recherche</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input
                                    id="search-input"
                                    v-model="filters.search"
                                    type="text"
                                    placeholder="Nom ou prénom..."
                                    class="block w-full pl-10 pr-3 py-3 bg-gray-700 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    @input="debounceSearch"
                                />
                            </div>
                        </div>
                        
                        <div>
                            <label for="statut-select" class="block text-sm font-medium text-gray-300 mb-2">Statut</label>
                            <select
                                id="statut-select"
                                v-model="filters.statut"
                                class="block w-full py-3 px-4 bg-gray-700 border border-gray-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                @change="applyFilters"
                            >
                                <option value="">Tous les statuts</option>
                                <option value="actif">Actif</option>
                                <option value="inactif">Inactif</option>
                                <option value="suspendu">Suspendu</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="ceinture-select" class="block text-sm font-medium text-gray-300 mb-2">Ceinture</label>
                            <select
                                id="ceinture-select"
                                v-model="filters.ceinture"
                                class="block w-full py-3 px-4 bg-gray-700 border border-gray-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                @change="applyFilters"
                            >
                                <option value="">Toutes les ceintures</option>
                                <option
                                    v-for="ceinture in (ceintures || [])"
                                    :key="ceinture.id"
                                    :value="ceinture.id"
                                >
                                    {{ ceinture.nom }}
                                </option>
                            </select>
                        </div>
                        
                        <div class="flex items-end">
                            <button
                                @click="exportMembres"
                                class="w-full bg-green-600 hover:bg-green-500 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Export Excel</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Liste des membres - Style moderne Dashboard -->
                <div class="bg-gray-800 rounded-2xl shadow-lg border border-gray-700/50 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-700/50">
                        <h3 class="text-lg font-semibold text-white">Liste des Membres</h3>
                        <p class="text-sm text-gray-400 mt-1">
                            Affichage de {{ membres?.from || 0 }} à {{ membres?.to || 0 }} sur {{ membres?.total || 0 }} membres
                        </p>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table v-if="membres?.data && membres.data.length" class="min-w-full divide-y divide-gray-700/50">
                            <thead class="bg-gray-900/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">
                                        Membre
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">
                                        Âge
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">
                                        Ceinture
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">
                                        Statut
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">
                                        Dernière présence
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-800 divide-y divide-gray-700/30">
                                <tr v-for="membre in membres.data" :key="membre.id" class="hover:bg-gray-700/30 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12">
                                                <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-lg">
                                                    <span class="text-sm font-semibold text-white">
                                                        {{ membre.prenom[0] }}{{ membre.nom[0] }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-white">
                                                    {{ membre.prenom }} {{ membre.nom }}
                                                </div>
                                                <div class="text-sm text-gray-400">
                                                    {{ membre.telephone }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-white">{{ calculerAge(membre.date_naissance) }} ans</div>
                                        <div class="text-sm text-gray-400">{{ formatDate(membre.date_naissance) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-semibold border"
                                            :style="`background-color: ${membre.ceinture_actuelle?.couleur_hex}20; color: ${membre.ceinture_actuelle?.couleur_hex}; border-color: ${membre.ceinture_actuelle?.couleur_hex}40`"
                                        >
                                            🥋 {{ membre.ceinture_actuelle?.nom || 'Non définie' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            :class="{
                                                'bg-green-600/20 text-green-400 border-green-600/40': membre.statut === 'actif',
                                                'bg-yellow-600/20 text-yellow-400 border-yellow-600/40': membre.statut === 'inactif',
                                                'bg-red-600/20 text-red-400 border-red-600/40': membre.statut === 'suspendu'
                                            }"
                                            class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-semibold border"
                                        >
                                            <span class="w-2 h-2 rounded-full mr-2"
                                                :class="{
                                                    'bg-green-400': membre.statut === 'actif',
                                                    'bg-yellow-400': membre.statut === 'inactif',
                                                    'bg-red-400': membre.statut === 'suspendu'
                                                }"
                                            ></span>
                                            {{ membre.statut }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-white">
                                            {{ formatDate(membre.date_derniere_presence) }}
                                        </div>
                                        <div class="text-sm text-gray-400">
                                            {{ membre.date_derniere_presence ? `Il y a ${joursDepuis(membre.date_derniere_presence)} jours` : 'Aucune présence' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-3">
                                            <Link
                                                :href="`/membres/${membre.id}`"
                                                class="inline-flex items-center px-3 py-2 text-xs font-medium rounded-lg text-blue-400 bg-blue-600/20 border border-blue-600/40 hover:bg-blue-600/30 transition-all duration-200"
                                            >
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Voir
                                            </Link>
                                            <Link
                                                :href="`/membres/${membre.id}/edit`"
                                                class="inline-flex items-center px-3 py-2 text-xs font-medium rounded-lg text-purple-400 bg-purple-600/20 border border-purple-600/40 hover:bg-purple-600/30 transition-all duration-200"
                                            >
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Modifier
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div v-else class="p-8 text-center text-gray-400">
                            Aucun membre trouvé ou erreur de chargement.
                        </div>
                    </div>

                    <!-- Pagination moderne - Pattern Dashboard -->
                    <div class="bg-gray-900/50 px-6 py-4 border-t border-gray-700/50">
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-400">
                                Affichage de {{ membres?.from || 0 }} à {{ membres?.to || 0 }} sur {{ membres?.total || 0 }} membres
                            </div>
                            <div class="flex items-center space-x-2">
                                <Link
                                    v-for="link in (membres?.links || [])"
                                    :key="link.label"
                                    :href="link.url"
                                    :class="{
                                        'bg-blue-600 text-white border-blue-600': link.active,
                                        'bg-gray-700 text-gray-300 border-gray-600 hover:bg-gray-600': !link.active && link.url,
                                        'bg-gray-800 text-gray-500 border-gray-700 cursor-not-allowed': !link.url
                                    }"
                                    class="px-4 py-2 text-sm font-medium border rounded-lg transition-all duration-200"
                                >
                                    {{ link.label.replace(/<[^>]*>?/gm, '') }}
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { reactive, computed, ref, onMounted } from 'vue'
// Gestion du thème (sombre/clair)
const theme = ref('dark')

onMounted(() => {
    const savedTheme = localStorage.getItem('theme')
    if (savedTheme === 'light' || savedTheme === 'dark') {
        theme.value = savedTheme
    }
    applyTheme()
})

const toggleTheme = () => {
    theme.value = theme.value === 'dark' ? 'light' : 'dark'
    localStorage.setItem('theme', theme.value)
    applyTheme()
}

const applyTheme = () => {
    if (theme.value === 'dark') {
        document.documentElement.classList.add('dark')
        document.documentElement.classList.remove('light')
    } else {
        document.documentElement.classList.add('light')
        document.documentElement.classList.remove('dark')
    }
}
import { Link, router, Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    membres: {
        type: Object,
        required: true
    },
    ceintures: {
        type: Array,
        default: () => []
    },
    filters: {
        type: Object,
        default: () => ({})
    },
    stats: {
        type: Object,
        default: () => ({})
    }
})

const filters = reactive({
    search: props.filters?.search || '',
    statut: props.filters?.statut || '',
    ceinture: props.filters?.ceinture || ''
})

// Statistiques calculées (Pattern Dashboard)
const membresActifs = computed(() => {
    return props.stats.membres_actifs || (props.membres.data || []).filter(m => m.statut === 'actif').length
})

const nouveauxMembres = computed(() => {
    return props.stats.nouveaux_membres_mois || 0
})

const membresEnRetard = computed(() => {
    return props.stats.membres_retard_paiement || 0
})

let searchTimeout = null

const debounceSearch = () => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(applyFilters, 300)
}

const applyFilters = () => {
    router.get('/membres', filters, {
        preserveState: true,
        replace: true
    })
}

const calculerAge = (dateNaissance) => {
    return Math.floor((new Date() - new Date(dateNaissance)) / (365.25 * 24 * 60 * 60 * 1000))
}

const formatDate = (date) => {
    if (!date) return 'Jamais'
    return new Date(date).toLocaleDateString('fr-CA')
}

const joursDepuis = (date) => {
    if (!date) return 0
    return Math.floor((new Date() - new Date(date)) / (24 * 60 * 60 * 1000))
}

const exportMembres = () => {
    window.open('/export/membres', '_blank')
}
</script>
