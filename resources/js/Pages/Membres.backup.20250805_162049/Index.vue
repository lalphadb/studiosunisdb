<template>
    <AuthenticatedLayout>
        <Head title="Gestion des Membres - StudiosDB v5" />
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <!-- Header avec gradient identique à Cours -->
                <div class="bg-gradient-to-r from-blue-500 via-purple-500 to-purple-600 rounded-xl shadow-lg mb-8">
                    <div class="px-8 py-12">
                        <div class="flex items-center space-x-4 text-white">
                            <div class="p-3 bg-white/20 rounded-lg">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold">👥 Gestion des Membres</h1>
                                <p class="text-blue-100 mt-2">Interface professionnelle de gestion des membres et inscriptions</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KPI Cards - Style identique à Cours -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Membres -->
                    <div class="bg-gray-800 rounded-xl p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="flex items-center space-x-3 mb-2">
                                    <div class="p-2 bg-blue-600 rounded-lg">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-2xl font-bold">{{ stats.total_membres || 0 }}</p>
                                <p class="text-gray-400 text-sm">Total Membres</p>
                            </div>
                        </div>
                    </div>

                    <!-- Membres Actifs -->
                    <div class="bg-gray-800 rounded-xl p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="flex items-center space-x-3 mb-2">
                                    <div class="p-2 bg-green-600 rounded-lg">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-2xl font-bold">{{ stats.membres_actifs || 0 }}</p>
                                <p class="text-gray-400 text-sm">Membres Actifs</p>
                            </div>
                        </div>
                    </div>

                    <!-- Nouveaux ce mois -->
                    <div class="bg-gray-800 rounded-xl p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="flex items-center space-x-3 mb-2">
                                    <div class="p-2 bg-purple-600 rounded-lg">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-2xl font-bold">{{ stats.nouveaux_mois || 0 }}</p>
                                <p class="text-gray-400 text-sm">Nouveaux ce mois</p>
                            </div>
                        </div>
                    </div>

                    <!-- Taux d'activité -->
                    <div class="bg-gray-800 rounded-xl p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="flex items-center space-x-3 mb-2">
                                    <div class="p-2 bg-yellow-600 rounded-lg">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"/>
                                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"/>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-2xl font-bold">{{ stats.taux_activite || 0 }}%</p>
                                <p class="text-gray-400 text-sm">Taux d'activité</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtres et Actions - Style identique à Cours -->
                <div class="bg-gray-800 rounded-xl shadow-lg p-6 mb-8">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                        
                        <!-- Filtres -->
                        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
                            <!-- Filtre Tous les statuts -->
                            <div class="flex items-center space-x-2">
                                <button 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg flex items-center space-x-2 hover:bg-blue-700 transition"
                                    :class="{ 'bg-blue-700': !filters.statut }"
                                    @click="resetFilter('statut')"
                                >
                                    <span>🌐</span>
                                    <span>Tous les statuts</span>
                                </button>
                                
                                <button 
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg flex items-center space-x-2 hover:bg-red-700 transition"
                                    :class="{ 'bg-red-700': filters.statut === 'actif' }"
                                    @click="setFilter('statut', 'actif')"
                                >
                                    <span>🔴</span>
                                    <span>Actifs seulement</span>
                                </button>
                            </div>

                            <!-- Recherche -->
                            <div class="relative">
                                <input
                                    v-model="searchQuery"
                                    @input="handleSearch"
                                    type="text"
                                    placeholder="Rechercher un membre..."
                                    class="w-64 px-4 py-2 bg-gray-700 text-white placeholder-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                                <svg class="absolute right-3 top-2.5 w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Bouton Nouveau Membre - Style identique -->
                        <Link 
                            :href="route('membres.create')" 
                            class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg flex items-center space-x-2 transition transform hover:scale-105"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
                            </svg>
                            <span>Nouveau Membre</span>
                        </Link>
                    </div>
                </div>

                <!-- Liste des Membres -->
                <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-white mb-6">Liste des Membres</h3>
                        
                        <div v-if="membres.data && membres.data.length > 0" class="space-y-4">
                            <div 
                                v-for="membre in membres.data" 
                                :key="membre.id"
                                class="bg-gray-700 rounded-lg p-4 hover:bg-gray-600 transition cursor-pointer"
                                @click="showMembre(membre.id)"
                            >
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <!-- Avatar -->
                                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ getInitials(membre.prenom, membre.nom) }}
                                        </div>
                                        
                                        <!-- Infos membre -->
                                        <div>
                                            <h4 class="text-white font-medium">{{ membre.prenom }} {{ membre.nom }}</h4>
                                            <div class="flex items-center space-x-4 text-sm text-gray-400">
                                                <span>📅 {{ formatDate(membre.date_inscription) }}</span>
                                                <span 
                                                    class="px-2 py-1 rounded text-xs font-medium"
                                                    :class="getStatutClass(membre.statut)"
                                                >
                                                    {{ membre.statut }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Actions -->
                                    <div class="flex items-center space-x-2">
                                        <Link 
                                            :href="route('membres.edit', membre.id)"
                                            class="p-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition"
                                            @click.stop
                                        >
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                            </svg>
                                        </Link>
                                        
                                        <button 
                                            @click.stop="deleteMembre(membre.id)"
                                            class="p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition"
                                        >
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 112 0v4a1 1 0 11-2 0V9zm4 0a1 1 0 112 0v4a1 1 0 11-2 0V9z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- État vide -->
                        <div v-else class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                                </svg>
                            </div>
                            <h3 class="text-white text-lg font-medium mb-2">Aucun membre trouvé</h3>
                            <p class="text-gray-400 mb-6">Commencez par ajouter votre premier membre</p>
                            <Link 
                                :href="route('membres.create')" 
                                class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg inline-flex items-center space-x-2 transition"
                            >
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
                                </svg>
                                <span>Ajouter un membre</span>
                            </Link>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div v-if="membres.links && membres.links.length > 3" class="bg-gray-700 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-400">
                                Affichage de {{ membres.from || 0 }} à {{ membres.to || 0 }} sur {{ membres.total || 0 }} membres
                            </div>
                            <div class="flex items-center space-x-2">
                                <Link 
                                    v-for="link in membres.links" 
                                    :key="link.label"
                                    :href="link.url" 
                                    :class="[
                                        'px-3 py-2 rounded text-sm font-medium transition',
                                        link.active 
                                            ? 'bg-blue-600 text-white' 
                                            : 'bg-gray-600 text-gray-300 hover:bg-gray-500'
                                    ]"
                                    v-html="link.label"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

// Props
const props = defineProps({
    membres: Object,
    stats: Object,
    filters: Object,
    error: String
})

// State
const searchQuery = ref(props.filters?.search || '')

// Methods
const handleSearch = () => {
    router.get(route('membres.index'), {
        search: searchQuery.value,
        statut: props.filters?.statut
    }, {
        preserveState: true,
        replace: true
    })
}

const setFilter = (key, value) => {
    router.get(route('membres.index'), {
        ...props.filters,
        [key]: value
    }, {
        preserveState: true,
        replace: true
    })
}

const resetFilter = (key) => {
    const newFilters = { ...props.filters }
    delete newFilters[key]
    
    router.get(route('membres.index'), newFilters, {
        preserveState: true,
        replace: true
    })
}

const showMembre = (id) => {
    router.visit(route('membres.show', id))
}

const deleteMembre = (id) => {
    if (confirm('Êtes-vous sûr de vouloir désactiver ce membre ?')) {
        router.delete(route('membres.destroy', id))
    }
}

const getInitials = (prenom, nom) => {
    return (prenom?.charAt(0) || '') + (nom?.charAt(0) || '')
}

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('fr-CA')
}

const getStatutClass = (statut) => {
    const classes = {
        'actif': 'bg-green-600 text-green-100',
        'inactif': 'bg-gray-600 text-gray-100',
        'suspendu': 'bg-red-600 text-red-100'
    }
    return classes[statut] || 'bg-gray-600 text-gray-100'
}
</script>