<template>
  <AuthenticatedLayout title="Gestion des Cours - StudiosDB Pro">
    <template #header>
      <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 rounded-2xl shadow-2xl p-8 text-white">
        <h2 class="text-4xl font-bold mb-2 flex items-center">
          <BookOpenIcon class="w-10 h-10 mr-4 text-yellow-300" />
          Gestion des Cours
        </h2>
        <p class="text-xl opacity-90">Interface professionnelle de gestion des cours et horaires</p>
      </div>
    </template>

    <div class="py-8">
      <div class="max-w-full mx-auto sm:px-6 lg:px-8">
        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <div class="bg-white/20 backdrop-blur-md rounded-xl p-6 border border-white/30">
            <div class="flex items-center">
              <div class="p-3 bg-blue-500/20 rounded-lg">
                <BookOpenIcon class="h-8 w-8 text-blue-600" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Cours</p>
                <p class="text-2xl font-bold text-gray-900">{{ stats.total_cours }}</p>
              </div>
            </div>
          </div>

          <div class="bg-white/20 backdrop-blur-md rounded-xl p-6 border border-white/30">
            <div class="flex items-center">
              <div class="p-3 bg-green-500/20 rounded-lg">
                <PlayIcon class="h-8 w-8 text-green-600" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Cours Actifs</p>
                <p class="text-2xl font-bold text-gray-900">{{ stats.cours_actifs }}</p>
              </div>
            </div>
          </div>

          <div class="bg-white/20 backdrop-blur-md rounded-xl p-6 border border-white/30">
            <div class="flex items-center">
              <div class="p-3 bg-purple-500/20 rounded-lg">
                <UserGroupIcon class="h-8 w-8 text-purple-600" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Inscriptions</p>
                <p class="text-2xl font-bold text-gray-900">{{ stats.total_inscriptions }}</p>
              </div>
            </div>
          </div>

          <div class="bg-white/20 backdrop-blur-md rounded-xl p-6 border border-white/30">
            <div class="flex items-center">
              <div class="p-3 bg-yellow-500/20 rounded-lg">
                <CalendarIcon class="h-8 w-8 text-yellow-600" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Sessions</p>
                <p class="text-2xl font-bold text-gray-900">{{ stats.sessions_semaine }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Filtres et actions -->
        <div class="bg-white/20 backdrop-blur-md rounded-xl shadow-xl p-6 mb-8 border border-white/30">
          <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
            <div class="flex flex-wrap items-center space-x-4">
              <!-- Filtre par saison -->
              <select v-model="filters.saison"
                      @change="applyFilters"
                      class="bg-white/10 border border-white/20 rounded-lg px-4 py-2 text-gray-700 focus:ring-2 focus:ring-blue-500">
                <option value="">🌍 Toutes les saisons</option>
                <option value="automne">🍂 Automne</option>
                <option value="hiver">❄️ Hiver</option>
                <option value="printemps">🌸 Printemps</option>
                <option value="ete">☀️ Été</option>
              </select>

              <!-- Filtre par niveau -->
              <select v-model="filters.niveau"
                      @change="applyFilters"
                      class="bg-white/10 border border-white/20 rounded-lg px-4 py-2 text-gray-700 focus:ring-2 focus:ring-blue-500">
                <option value="">🎯 Tous les niveaux</option>
                <option value="debutant">🌱 Débutant</option>
                <option value="intermediaire">⚡ Intermédiaire</option>
                <option value="avance">🔥 Avancé</option>
                <option value="expert">👑 Expert</option>
              </select>

              <!-- Recherche -->
              <div class="relative">
                <MagnifyingGlassIcon class="absolute left-3 top-3 h-4 w-4 text-gray-400" />
                <input v-model="filters.search"
                       @input="applyFilters"
                       type="text"
                       placeholder="Rechercher un cours..."
                       class="bg-white/10 border border-white/20 rounded-lg pl-10 pr-4 py-2 text-gray-700 focus:ring-2 focus:ring-blue-500 w-64">
              </div>
            </div>

            <button @click="openCreateModal"
                    class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 transform hover:scale-105 shadow-lg">
              <PlusIcon class="w-5 h-5 inline mr-2" />
              Nouveau Cours
            </button>
          </div>
        </div>

        <!-- Liste des cours -->
        <div class="bg-white/20 backdrop-blur-md rounded-xl shadow-xl overflow-hidden border border-white/30">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200/20">
              <thead class="bg-gray-900/20">
                <tr>
                  <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                    📚 Cours
                  </th>
                  <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                    🌟 Niveau
                  </th>
                  <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                    🌍 Saison
                  </th>
                  <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                    👥 Inscrits
                  </th>
                  <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                    📅 Horaires
                  </th>
                  <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                    💰 Tarif
                  </th>
                  <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                    ⚙️ Actions
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200/10">
                <tr v-for="cours in filteredCours"
                    :key="cours.id"
                    class="hover:bg-white/10 transition-colors duration-200">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-12 w-12">
                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-400 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                          {{ cours.nom.charAt(0) }}
                        </div>
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">{{ cours.nom }}</div>
                        <div class="text-sm text-gray-500">{{ cours.description }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="getNiveauClasses(cours.niveau)">
                      {{ formatNiveau(cours.niveau) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-2xl">{{ getSaisonEmoji(cours.saison) }}</span>
                    <span class="ml-2 text-sm font-medium text-gray-700">{{ cours.saison }}</span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <span class="text-sm font-medium text-gray-900">{{ cours.inscrits_count }}</span>
                      <span class="text-sm text-gray-500 ml-1">/ {{ cours.capacite_max }}</span>
                      <div class="ml-2 w-16 bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-green-400 to-blue-500 h-2 rounded-full"
                             :style="{ width: (cours.inscrits_count / cours.capacite_max * 100) + '%' }"></div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">
                      <div v-for="horaire in cours.horaires" :key="horaire.id" class="mb-1">
                        {{ formatHoraire(horaire) }}
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">
                      <div class="font-semibold">{{ cours.tarif_mensuel }}$ CAD</div>
                      <div class="text-xs text-gray-500">par mois</div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <button @click="editCours(cours)"
                            class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-100/20 transition-colors">
                      <PencilIcon class="w-4 h-4" />
                    </button>
                    <button @click="manageHoraires(cours)"
                            class="text-purple-600 hover:text-purple-900 p-2 rounded-lg hover:bg-purple-100/20 transition-colors">
                      <CalendarIcon class="w-4 h-4" />
                    </button>
                    <button @click="viewInscriptions(cours)"
                            class="text-green-600 hover:text-green-900 p-2 rounded-lg hover:bg-green-100/20 transition-colors">
                      <UserGroupIcon class="w-4 h-4" />
                    </button>
                    <button @click="deleteCours(cours)"
                            class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-100/20 transition-colors">
                      <TrashIcon class="w-4 h-4" />
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-between items-center">
          <div class="text-sm text-gray-600">
            Affichage de {{ ((pagination.current_page - 1) * pagination.per_page) + 1 }} à
            {{ Math.min(pagination.current_page * pagination.per_page, pagination.total) }}
            sur {{ pagination.total }} cours
          </div>
          <div class="flex space-x-2">
            <button v-for="page in paginationPages"
                    :key="page"
                    @click="changePage(page)"
                    :class="[
                      'px-3 py-2 rounded-lg text-sm font-medium transition-colors',
                      page === pagination.current_page
                        ? 'bg-blue-600 text-white'
                        : 'bg-white/10 text-gray-700 hover:bg-white/20'
                    ]">
              {{ page }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modals -->
    <CreateCoursModal v-if="showCreateModal"
                      @close="showCreateModal = false"
                      @created="handleCoursCreated" />

    <EditCoursModal v-if="showEditModal"
                    :cours="selectedCours"
                    @close="showEditModal = false"
                    @updated="handleCoursUpdated" />

    <HorairesModal v-if="showHorairesModal"
                   :cours="selectedCours"
                   @close="showHorairesModal = false"
                   @updated="handleHorairesUpdated" />
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import CreateCoursModal from '@/Components/Cours/CreateModal.vue'
import EditCoursModal from '@/Components/Cours/EditModal.vue'
import HorairesModal from '@/Components/Cours/HorairesModal.vue'
import {
  BookOpenIcon,
  PlusIcon,
  PencilIcon,
  TrashIcon,
  MagnifyingGlassIcon,
  CalendarIcon,
  UserGroupIcon,
  PlayIcon
} from '@heroicons/vue/24/outline'

// Props
const props = defineProps({
  cours: Array,
  stats: Object,
  pagination: Object
})

// État réactif
const showCreateModal = ref(false)
const showEditModal = ref(false)
const showHorairesModal = ref(false)
const selectedCours = ref(null)

const filters = ref({
  search: '',
  saison: '',
  niveau: ''
})

// Cours filtrés
const filteredCours = computed(() => {
  let result = props.cours || []

  if (filters.value.search) {
    result = result.filter(cours =>
      cours.nom.toLowerCase().includes(filters.value.search.toLowerCase()) ||
      cours.description.toLowerCase().includes(filters.value.search.toLowerCase())
    )
  }

  if (filters.value.saison) {
    result = result.filter(cours => cours.saison === filters.value.saison)
  }

  if (filters.value.niveau) {
    result = result.filter(cours => cours.niveau === filters.value.niveau)
  }

  return result
})

// Pages de pagination
const paginationPages = computed(() => {
  const pages = []
  const current = props.pagination.current_page
  const last = props.pagination.last_page

  for (let i = Math.max(1, current - 2); i <= Math.min(last, current + 2); i++) {
    pages.push(i)
  }

  return pages
})

// Méthodes
const openCreateModal = () => {
  showCreateModal.value = true
}

const editCours = (cours) => {
  selectedCours.value = cours
  showEditModal.value = true
}

const manageHoraires = (cours) => {
  selectedCours.value = cours
  showHorairesModal.value = true
}

const viewInscriptions = (cours) => {
  router.visit(`/cours/${cours.id}/inscriptions`)
}

const deleteCours = (cours) => {
  if (confirm(`Êtes-vous sûr de vouloir supprimer le cours "${cours.nom}" ?`)) {
    router.delete(`/cours/${cours.id}`)
  }
}

const applyFilters = () => {
  router.get('/cours', filters.value, { preserveState: true })
}

const changePage = (page) => {
  router.get('/cours', { ...filters.value, page }, { preserveState: true })
}

const handleCoursCreated = (cours) => {
  showCreateModal.value = false
  router.reload()
}

const handleCoursUpdated = (cours) => {
  showEditModal.value = false
  selectedCours.value = null
  router.reload()
}

const handleHorairesUpdated = () => {
  showHorairesModal.value = false
  selectedCours.value = null
  router.reload()
}

// Utilitaires de formatage
const getNiveauClasses = (niveau) => {
  const classes = {
    'debutant': 'bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium',
    'intermediaire': 'bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-medium',
    'avance': 'bg-orange-100 text-orange-800 px-2 py-1 rounded-full text-xs font-medium',
    'expert': 'bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium'
  }
  return classes[niveau] || 'bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs font-medium'
}

const formatNiveau = (niveau) => {
  const formats = {
    'debutant': '🌱 Débutant',
    'intermediaire': '⚡ Intermédiaire',
    'avance': '🔥 Avancé',
    'expert': '👑 Expert'
  }
  return formats[niveau] || niveau
}

const getSaisonEmoji = (saison) => {
  const emojis = {
    'automne': '🍂',
    'hiver': '❄️',
    'printemps': '🌸',
    'ete': '☀️'
  }
  return emojis[saison] || '🌍'
}

const formatHoraire = (horaire) => {
  const jours = {
    'lundi': 'Lun',
    'mardi': 'Mar',
    'mercredi': 'Mer',
    'jeudi': 'Jeu',
    'vendredi': 'Ven',
    'samedi': 'Sam',
    'dimanche': 'Dim'
  }
  return `${jours[horaire.jour]} ${horaire.heure_debut}-${horaire.heure_fin}`
}
</script>
