<template>
  <Head title="Gestion des Membres" />

  <AuthenticatedLayout>
    <div class="min-h-screen bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 text-white">
      <div class="relative z-10 w-full px-6 lg:px-12 py-8">

        <!-- Header avec actions -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
          <div class="mb-4 lg:mb-0">
            <div class="flex items-center space-x-3 mb-2">
              <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-700 rounded-xl flex items-center justify-center shadow-lg">
                <UsersIcon class="h-7 w-7 text-white" />
              </div>
              <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-100 to-blue-300 bg-clip-text text-transparent">
                  Gestion des Membres
                </h1>
                <p class="text-blue-200 font-medium">{{ totalMembres }} membres inscrits à l'école</p>
              </div>
            </div>
          </div>

          <div class="flex items-center space-x-3">
            <button
              @click="exportMembres"
              class="bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 px-5 py-3 rounded-lg transition-all duration-200 flex items-center space-x-2 shadow-md"
            >
              <ArrowDownTrayIcon class="h-5 w-5" />
              <span>Exporter</span>
            </button>
            <Link
              :href="route('membres.create')"
              class="bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 px-6 py-3 rounded-lg transition-all duration-200 flex items-center space-x-2 shadow-md"
            >
              <PlusIcon class="h-5 w-5" />
              <span>Nouveau Membre</span>
            </Link>
          </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 w-full">
          <ModernStatsCard
            title="Membres Actifs"
            :value="stats.membres_actifs"
            icon-type="heroicon"
            icon-name="users"
            format="number"
            description="Membres actifs ce mois"
            :trend="stats.trend_actifs"
          />

          <ModernStatsCard
            title="Nouvelles Inscriptions"
            :value="stats.nouvelles_inscriptions"
            icon-type="heroicon"
            icon-name="user-plus"
            format="number"
            description="Inscriptions ce mois"
            :trend="stats.trend_inscriptions"
          />

          <ModernStatsCard
            title="Taux de Présence"
            :value="stats.taux_presence"
            icon-type="heroicon"
            icon-name="chart"
            format="percentage"
            description="Présence moyenne"
            :trend="stats.trend_presence"
          />

          <ModernStatsCard
            title="Examens Prévus"
            :value="stats.examens_prevus"
            icon-type="heroicon"
            icon-name="academic"
            format="number"
            description="Élèves prêts pour examen"
            trend-type="info"
          />
        </div>

        <!-- Barre de recherche et filtres -->
        <div class="bg-blue-900/60 backdrop-blur-xl border border-blue-800/50 rounded-xl p-6 mb-8 shadow-md w-full">
          <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Recherche -->
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-blue-200 mb-2">Rechercher</label>
              <div class="relative">
                <input
                  v-model="filters.search"
                  @input="debouncedSearch"
                  type="text"
                  placeholder="Nom, prénom, email..."
                  class="w-full bg-blue-950/60 border border-blue-800 rounded-lg pl-10 pr-4 py-2 text-white placeholder-blue-400 focus:outline-none focus:border-blue-600 transition-colors"
                />
                <MagnifyingGlassIcon class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-blue-400" />
              </div>
            </div>

            <!-- Filtre Statut -->
            <div>
              <label class="block text-sm font-medium text-blue-200 mb-2">Statut</label>
              <select
                v-model="filters.statut"
                @change="applyFilters"
                class="w-full bg-blue-950/60 border border-blue-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-blue-600 transition-colors"
              >
                <option value="">Tous les statuts</option>
                <option value="actif">Actif</option>
                <option value="inactif">Inactif</option>
                <option value="suspendu">Suspendu</option>
              </select>
            </div>

            <!-- Filtre Ceinture -->
            <div>
              <label class="block text-sm font-medium text-blue-200 mb-2">Ceinture</label>
              <select
                v-model="filters.ceinture"
                @change="applyFilters"
                class="w-full bg-blue-950/60 border border-blue-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-blue-600 transition-colors"
              >
                <option value="">Toutes les ceintures</option>
                <option v-for="ceinture in ceintures" :key="ceinture.id" :value="ceinture.id">
                  {{ ceinture.nom }}
                </option>
              </select>
            </div>

            <!-- Filtre Groupe d'âge -->
            <div>
              <label class="block text-sm font-medium text-blue-200 mb-2">Groupe d'âge</label>
              <select
                v-model="filters.groupe_age"
                @change="applyFilters"
                class="w-full bg-blue-950/60 border border-blue-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-blue-600 transition-colors"
              >
                <option value="">Tous les âges</option>
                <option value="enfant">Enfants (5-11 ans)</option>
                <option value="adolescent">Adolescents (12-17 ans)</option>
                <option value="adulte">Adultes (18+ ans)</option>
              </select>
            </div>
          </div>

          <!-- Tags de filtres actifs -->
          <div v-if="hasActiveFilters" class="mt-4 flex flex-wrap items-center gap-2">
            <span class="text-sm text-blue-300">Filtres actifs:</span>
            <span
              v-for="(value, key) in activeFilters"
              :key="key"
              class="inline-flex items-center gap-1 px-3 py-1 bg-blue-800/50 text-blue-200 rounded-full text-sm"
            >
              {{ filterLabels[key] }}: {{ value }}
              <button
                @click="removeFilter(key)"
                class="ml-1 hover:text-white transition-colors"
              >
                <XMarkIcon class="h-4 w-4" />
              </button>
            </span>
            <button
              @click="resetFilters"
              class="text-sm text-blue-300 hover:text-white transition-colors underline"
            >
              Réinitialiser tout
            </button>
          </div>
        </div>

        <!-- Tableau des membres -->
        <div class="bg-blue-900/60 backdrop-blur-xl border border-blue-800/50 rounded-xl shadow-lg overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="bg-blue-950/60 border-b border-blue-800">
                  <th class="px-6 py-4 text-left text-xs font-medium text-blue-300 uppercase tracking-wider">
                    <button
                      @click="sort('id')"
                      class="flex items-center space-x-1 hover:text-white transition-colors"
                    >
                      <span>#ID</span>
                      <ChevronUpDownIcon class="h-4 w-4" />
                    </button>
                  </th>
                  <th class="px-6 py-4 text-left text-xs font-medium text-blue-300 uppercase tracking-wider">
                    <button
                      @click="sort('nom')"
                      class="flex items-center space-x-1 hover:text-white transition-colors"
                    >
                      <span>Membre</span>
                      <ChevronUpDownIcon class="h-4 w-4" />
                    </button>
                  </th>
                  <th class="px-6 py-4 text-left text-xs font-medium text-blue-300 uppercase tracking-wider">
                    Contact
                  </th>
                  <th class="px-6 py-4 text-left text-xs font-medium text-blue-300 uppercase tracking-wider">
                    Ceinture
                  </th>
                  <th class="px-6 py-4 text-left text-xs font-medium text-blue-300 uppercase tracking-wider">
                    <button
                      @click="sort('date_inscription')"
                      class="flex items-center space-x-1 hover:text-white transition-colors"
                    >
                      <span>Inscription</span>
                      <ChevronUpDownIcon class="h-4 w-4" />
                    </button>
                  </th>
                  <th class="px-6 py-4 text-left text-xs font-medium text-blue-300 uppercase tracking-wider">
                    Statut
                  </th>
                  <th class="px-6 py-4 text-left text-xs font-medium text-blue-300 uppercase tracking-wider">
                    Présences
                  </th>
                  <th class="px-6 py-4 text-right text-xs font-medium text-blue-300 uppercase tracking-wider">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-blue-800/50">
                <tr
                  v-for="membre in membres.data"
                  :key="membre.id"
                  class="hover:bg-blue-800/30 transition-colors"
                >
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-200">
                    #{{ membre.id }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold">
                          {{ membre.prenom[0] }}{{ membre.nom[0] }}
                        </div>
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-white">
                          {{ membre.prenom }} {{ membre.nom }}
                        </div>
                        <div class="text-xs text-blue-300">
                          {{ getAge(membre.date_naissance) }} ans
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-blue-200">{{ membre.email }}</div>
                    <div class="text-xs text-blue-400">{{ membre.telephone }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span
                      :class="getCeintureClass(membre.ceinture_actuelle)"
                      class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                    >
                      {{ membre.ceinture_actuelle?.nom || 'Non définie' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-200">
                    {{ formatDate(membre.date_inscription) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span
                      :class="getStatutClass(membre.statut)"
                      class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                    >
                      {{ membre.statut }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center space-x-2">
                      <div class="text-sm text-blue-200">
                        {{ membre.presences_mois }} ce mois
                      </div>
                      <div class="w-16 bg-blue-950/60 rounded-full h-2">
                        <div
                          :style="{ width: `${getPresencePercentage(membre)}%` }"
                          class="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full"
                        ></div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end space-x-2">
                      <Link
                        :href="route('membres.show', membre.id)"
                        class="text-blue-400 hover:text-blue-300 transition-colors"
                        title="Voir le profil"
                      >
                        <EyeIcon class="h-5 w-5" />
                      </Link>
                      <Link
                        :href="route('membres.edit', membre.id)"
                        class="text-yellow-400 hover:text-yellow-300 transition-colors"
                        title="Modifier"
                      >
                        <PencilIcon class="h-5 w-5" />
                      </Link>
                      <button
                        @click="confirmDelete(membre)"
                        class="text-red-400 hover:text-red-300 transition-colors"
                        title="Supprimer"
                      >
                        <TrashIcon class="h-5 w-5" />
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="bg-blue-950/60 px-6 py-4 border-t border-blue-800">
            <Pagination :links="membres.links" />
          </div>
        </div>

      </div>
    </div>

    <!-- Modal de suppression -->
    <ConfirmModal
      :show="showDeleteModal"
      @close="showDeleteModal = false"
      @confirm="deleteMembre"
      title="Supprimer le membre"
      :message="`Êtes-vous sûr de vouloir supprimer ${membreToDelete?.prenom} ${membreToDelete?.nom} ? Cette action est irréversible.`"
      confirmText="Supprimer"
      type="danger"
    />

  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import ModernStatsCard from '@/Components/ModernStatsCard.vue'
import Pagination from '@/Components/Pagination.vue'
import ConfirmModal from '@/Components/ConfirmModal.vue'
import {
  PlusIcon,
  MagnifyingGlassIcon,
  UsersIcon,
  ArrowDownTrayIcon,
  ChevronUpDownIcon,
  EyeIcon,
  PencilIcon,
  TrashIcon,
  XMarkIcon
} from '@heroicons/vue/24/outline'

// Props
const props = defineProps({
  membres: Object,
  stats: Object,
  ceintures: Array,
  filters: Object
})

// État local
const filters = ref({
  search: props.filters?.search || '',
  statut: props.filters?.statut || '',
  ceinture: props.filters?.ceinture || '',
  groupe_age: props.filters?.groupe_age || ''
})

const sortField = ref('nom')
const sortDirection = ref('asc')
const showDeleteModal = ref(false)
const membreToDelete = ref(null)

// Computed
const totalMembres = computed(() => props.membres?.total || 0)

const hasActiveFilters = computed(() => {
  return Object.values(filters.value).some(val => val !== '')
})

const activeFilters = computed(() => {
  const active = {}
  Object.entries(filters.value).forEach(([key, value]) => {
    if (value !== '') {
      active[key] = value
    }
  })
  return active
})

const filterLabels = {
  search: 'Recherche',
  statut: 'Statut',
  ceinture: 'Ceinture',
  groupe_age: 'Âge'
}

// Méthodes
const debouncedSearch = (() => {
  let timeout
  return () => {
    clearTimeout(timeout)
    timeout = setTimeout(() => {
      applyFilters()
    }, 300)
  }
})()

const applyFilters = () => {
  router.get(route('membres.index'), filters.value, {
    preserveState: true,
    preserveScroll: true
  })
}

const removeFilter = (key) => {
  filters.value[key] = ''
  applyFilters()
}

const resetFilters = () => {
  Object.keys(filters.value).forEach(key => {
    filters.value[key] = ''
  })
  applyFilters()
}

const sort = (field) => {
  if (sortField.value === field) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortField.value = field
    sortDirection.value = 'asc'
  }
  
  router.get(route('membres.index'), {
    ...filters.value,
    sort: sortField.value,
    direction: sortDirection.value
  }, {
    preserveState: true,
    preserveScroll: true
  })
}

const confirmDelete = (membre) => {
  membreToDelete.value = membre
  showDeleteModal.value = true
}

const deleteMembre = () => {
  if (membreToDelete.value) {
    router.delete(route('membres.destroy', membreToDelete.value.id), {
      onSuccess: () => {
        showDeleteModal.value = false
        membreToDelete.value = null
      }
    })
  }
}

const exportMembres = () => {
  window.location.href = route('export.membres', filters.value)
}

const getAge = (dateNaissance) => {
  const today = new Date()
  const birthDate = new Date(dateNaissance)
  let age = today.getFullYear() - birthDate.getFullYear()
  const monthDiff = today.getMonth() - birthDate.getMonth()
  
  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
    age--
  }
  
  return age
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-CA', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const getStatutClass = (statut) => {
  const classes = {
    'actif': 'bg-green-900/50 text-green-300 border border-green-700',
    'inactif': 'bg-gray-900/50 text-gray-300 border border-gray-700',
    'suspendu': 'bg-red-900/50 text-red-300 border border-red-700'
  }
  return classes[statut] || classes['inactif']
}

const getCeintureClass = (ceinture) => {
  if (!ceinture) return 'bg-gray-900/50 text-gray-300'
  
  const couleur = ceinture.nom.toLowerCase()
  const classes = {
    'blanc': 'bg-white/20 text-white border border-white/50',
    'jaune': 'bg-yellow-900/50 text-yellow-300 border border-yellow-700',
    'orange': 'bg-orange-900/50 text-orange-300 border border-orange-700',
    'vert': 'bg-green-900/50 text-green-300 border border-green-700',
    'bleu': 'bg-blue-900/50 text-blue-300 border border-blue-700',
    'marron': 'bg-amber-900/50 text-amber-300 border border-amber-700',
    'noir': 'bg-gray-900 text-gray-100 border border-gray-600'
  }
  
  return classes[couleur] || 'bg-gray-900/50 text-gray-300'
}

const getPresencePercentage = (membre) => {
  const expectedPresences = 8 // Présences attendues par mois
  return Math.min(100, Math.round((membre.presences_mois / expectedPresences) * 100))
}
</script>
