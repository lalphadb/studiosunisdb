<template>
  <Head title="Gestion des Membres" />

  <AuthenticatedLayout>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-slate-900 to-gray-900 text-white">
      <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header avec actions -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
          <div class="mb-4 lg:mb-0">
            <div class="flex items-center space-x-3 mb-2">
              <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl flex items-center justify-center">
                <UsersIcon class="h-7 w-7 text-white" />
              </div>
              <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent">
                  Gestion des Membres
                </h1>
                <p class="text-gray-400 font-medium">Interface professionnelle de gestion des élèves</p>
              </div>
            </div>
          </div>

          <div class="flex items-center space-x-3">
            <Link
              :href="route('membres.create')"
              class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-lg transition-all duration-200 flex items-center space-x-2"
            >
              <UserPlusIcon class="h-5 w-5" />
              <span>Nouveau Membre</span>
            </Link>
          </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <ModernStatsCard
            title="Total Membres"
            :value="stats.total_membres"
            icon-type="heroicon"
            icon-name="users"
            format="number"
            description="Membres inscrits"
          />

          <ModernStatsCard
            title="Membres Actifs"
            :value="stats.membres_actifs"
            icon-type="heroicon" 
            icon-name="check"
            format="number"
            description="Élèves actifs"
          />

          <ModernStatsCard
            title="Nouveaux ce mois"
            :value="stats.nouveaux_mois"
            icon-type="heroicon"
            icon-name="sparkles"
            format="number"
            description="Nouvelles inscriptions"
          />

          <ModernStatsCard
            title="En retard paiement"
            :value="stats.retard_paiement"
            icon-type="heroicon"
            icon-name="exclamation"
            format="number"
            description="Paiements en attente"
          />
        </div>

        <!-- Filtres -->
        <div class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-xl p-6 mb-8">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Recherche</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <MagnifyingGlassIcon class="h-5 w-5 text-gray-400" />
                </div>
                <input
                  v-model="filters.search"
                  type="text"
                  placeholder="Nom ou prénom..."
                  class="w-full pl-10 bg-gray-700/50 border border-gray-600 rounded-lg px-3 py-2 text-white placeholder-gray-400"
                  @input="debounceSearch"
                />
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Statut</label>
              <select
                v-model="filters.statut"
                class="w-full bg-gray-700/50 border border-gray-600 rounded-lg px-3 py-2 text-white"
                @change="applyFilters"
              >
                <option value="">Tous les statuts</option>
                <option value="actif">Actif</option>
                <option value="inactif">Inactif</option>
                <option value="suspendu">Suspendu</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Ceinture</label>
              <select
                v-model="filters.ceinture"
                class="w-full bg-gray-700/50 border border-gray-600 rounded-lg px-3 py-2 text-white"
                @change="applyFilters"
              >
                <option value="">Toutes les ceintures</option>
                <option v-for="ceinture in ceintures" :key="ceinture.id" :value="ceinture.id">
                  {{ ceinture.nom }}
                </option>
              </select>
            </div>

            <div class="flex items-end">
              <button
                @click="resetFilters"
                class="w-full bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded-lg transition-all duration-200 mr-2"
              >
                Réinitialiser
              </button>
              <button
                @click="exportMembres"
                class="w-full bg-green-600 hover:bg-green-700 px-4 py-2 rounded-lg transition-all duration-200"
              >
                Export Excel
              </button>
            </div>
          </div>
        </div>

        <!-- Liste des membres -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
          <div
            v-for="membre in filteredMembres"
            :key="membre.id"
            class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-xl p-6 hover:border-blue-500/30 transition-all duration-300 group"
          >
            <!-- Header du membre -->
            <div class="flex items-start justify-between mb-4">
              <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                  <span class="text-sm font-semibold text-white">
                    {{ membre.prenom[0] }}{{ membre.nom[0] }}
                  </span>
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-white mb-1">{{ membre.prenom }} {{ membre.nom }}</h3>
                  <p class="text-sm text-gray-400">{{ calculerAge(membre.date_naissance) }} ans</p>
                </div>
              </div>

              <div class="flex items-center space-x-2">
                <span
                  :class="{
                    'bg-green-500/20 text-green-300': membre.statut === 'actif',
                    'bg-yellow-500/20 text-yellow-300': membre.statut === 'inactif',
                    'bg-red-500/20 text-red-300': membre.statut === 'suspendu'
                  }"
                  class="px-2 py-1 rounded text-xs font-medium"
                >
                  {{ membre.statut }}
                </span>
              </div>
            </div>

            <!-- Détails du membre -->
            <div class="space-y-3 mb-4">
              <div class="flex items-center text-sm text-gray-300">
                <PhoneIcon class="h-4 w-4 mr-2 text-blue-400" />
                <span>{{ membre.telephone || 'Non renseigné' }}</span>
              </div>

              <div class="flex items-center text-sm text-gray-300">
                <CalendarDaysIcon class="h-4 w-4 mr-2 text-purple-400" />
                <span>Inscrit le {{ formatDate(membre.date_inscription) }}</span>
              </div>

              <div class="flex items-center text-sm text-gray-300">
                <AcademicCapIcon class="h-4 w-4 mr-2 text-yellow-400" />
                <span 
                  class="px-2 py-1 rounded text-xs"
                  :style="`background-color: ${membre.ceinture_actuelle?.couleur_hex}20; color: ${membre.ceinture_actuelle?.couleur_hex}`"
                >
                  {{ membre.ceinture_actuelle?.nom || 'Non définie' }}
                </span>
              </div>

              <div class="flex items-center text-sm text-gray-300">
                <ClockIcon class="h-4 w-4 mr-2 text-green-400" />
                <span>
                  {{ membre.date_derniere_presence ? 
                    `Dernière présence: ${formatDate(membre.date_derniere_presence)}` : 
                    'Aucune présence enregistrée' 
                  }}
                </span>
              </div>
            </div>

            <!-- Progress bar assiduité -->
            <div class="mb-4" v-if="membre.taux_assiduite !== undefined">
              <div class="flex justify-between text-xs text-gray-400 mb-1">
                <span>Assiduité</span>
                <span>{{ Math.round(membre.taux_assiduite || 0) }}%</span>
              </div>
              <ModernProgressBar
                :percentage="membre.taux_assiduite || 0"
                :color="getAssiduitéColor(membre.taux_assiduite || 0)"
                size="sm"
                :glow-effect="true"
                animated
              />
            </div>

            <!-- Actions -->
            <div class="flex flex-wrap gap-2">
              <Link
                :href="route('membres.show', membre.id)"
                class="flex-1 bg-blue-600/20 hover:bg-blue-600/30 text-blue-300 px-3 py-2 rounded-lg text-sm transition-all duration-200 text-center"
              >
                Détails
              </Link>

              <Link
                :href="route('membres.edit', membre.id)"
                class="flex-1 bg-purple-600/20 hover:bg-purple-600/30 text-purple-300 px-3 py-2 rounded-lg text-sm transition-all duration-200 text-center"
              >
                Modifier
              </Link>

              <button
                @click="toggleStatut(membre)"
                :class="{
                  'bg-green-600/20 hover:bg-green-600/30 text-green-300': membre.statut !== 'actif',
                  'bg-red-600/20 hover:bg-red-600/30 text-red-300': membre.statut === 'actif'
                }"
                class="flex-1 px-3 py-2 rounded-lg text-sm transition-all duration-200"
              >
                {{ membre.statut === 'actif' ? 'Désactiver' : 'Activer' }}
              </button>
            </div>
          </div>
        </div>

        <!-- Message si aucun membre -->
        <div v-if="filteredMembres.length === 0" class="text-center py-12">
          <UsersIcon class="h-16 w-16 text-gray-500 mx-auto mb-4" />
          <h3 class="text-lg font-medium text-gray-400 mb-2">Aucun membre trouvé</h3>
          <p class="text-gray-500 mb-4">Commencez par inscrire votre premier élève.</p>
          <Link
            :href="route('membres.create')"
            class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-lg transition-all duration-200 inline-flex items-center space-x-2"
          >
            <UserPlusIcon class="h-5 w-5" />
            <span>Nouveau Membre</span>
          </Link>
        </div>

        <!-- Pagination moderne -->
        <div v-if="membres.links && membres.links.length > 3" class="mt-8 bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-xl p-6">
          <div class="flex justify-between items-center">
            <div class="text-sm text-gray-400">
              Affichage de {{ membres.from || 0 }} à {{ membres.to || 0 }} sur {{ membres.total || 0 }} membres
            </div>
            <div class="flex items-center space-x-2">
              <Link
                v-for="link in membres.links"
                :key="link.label"
                :href="link.url"
                :class="{
                  'bg-blue-600 text-white border-blue-600': link.active,
                  'bg-gray-700/50 text-gray-300 border-gray-600 hover:bg-gray-600/50': !link.active && link.url,
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
  </AuthenticatedLayout>
</template>

<script setup>
import { computed, ref, reactive } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import ModernStatsCard from '@/Components/ModernStatsCard.vue'
import ModernProgressBar from '@/Components/ModernProgressBar.vue'

import {
  UsersIcon,
  UserPlusIcon,
  MagnifyingGlassIcon,
  PhoneIcon,
  CalendarDaysIcon,
  AcademicCapIcon,
  ClockIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
  membres: {
    type: Object,
    required: true
  },
  ceintures: {
    type: Array,
    default: () => []
  },
  stats: {
    type: Object,
    default: () => ({
      total_membres: 0,
      membres_actifs: 0,
      nouveaux_mois: 0,
      retard_paiement: 0
    })
  },
  filters: {
    type: Object,
    default: () => ({})
  }
})

// Filtres réactifs
const filters = reactive({
  search: props.filters.search || '',
  statut: props.filters.statut || '',
  ceinture: props.filters.ceinture || ''
})

// Membres filtrés
const filteredMembres = computed(() => {
  if (!props.membres.data) return []
  
  return props.membres.data.filter(membre => {
    if (filters.search) {
      const searchTerm = filters.search.toLowerCase()
      const nomComplet = `${membre.prenom} ${membre.nom}`.toLowerCase()
      if (!nomComplet.includes(searchTerm)) return false
    }
    
    if (filters.statut && membre.statut !== filters.statut) return false
    
    if (filters.ceinture && membre.ceinture_actuelle_id !== parseInt(filters.ceinture)) return false
    
    return true
  })
})

// Debounce pour la recherche
let searchTimeout = null
const debounceSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(applyFilters, 300)
}

// Appliquer les filtres
const applyFilters = () => {
  router.get(route('membres.index'), filters, {
    preserveState: true,
    replace: true
  })
}

// Réinitialiser les filtres
const resetFilters = () => {
  filters.search = ''
  filters.statut = ''
  filters.ceinture = ''
  applyFilters()
}

// Méthodes utilitaires
const calculerAge = (dateNaissance) => {
  if (!dateNaissance) return 'N/A'
  return Math.floor((new Date() - new Date(dateNaissance)) / (365.25 * 24 * 60 * 60 * 1000))
}

const formatDate = (date) => {
  if (!date) return 'Non définie'
  return new Date(date).toLocaleDateString('fr-CA')
}

const getAssiduitéColor = (taux) => {
  if (taux >= 80) return 'green'
  if (taux >= 60) return 'yellow' 
  return 'red'
}

const toggleStatut = (membre) => {
  // Implementation à ajouter - appel API pour changer le statut
  console.log('Toggle statut membre:', membre.id)
}

const exportMembres = () => {
  window.open('/export/membres', '_blank')
}
</script>
