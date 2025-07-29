<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-slate-900 to-gray-900 text-white">
    <!-- Background pattern -->
    <div class="absolute inset-0 opacity-50 bg-pattern"></div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header avec navigation -->
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
        <div class="mb-4 lg:mb-0">
          <nav class="flex items-center space-x-2 text-sm text-gray-400 mb-4">
            <button @click="router.visit('/dashboard')" class="hover:text-white transition-colors">
              🏠 Dashboard
            </button>
            <span>/</span>
            <span class="text-white">👥 Gestion des Membres</span>
          </nav>
          
          <div class="flex items-center space-x-3 mb-2">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center">
              <UserGroupIcon class="h-7 w-7 text-white" />
            </div>
            <div>
              <h1 class="text-4xl font-bold bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent">
                Gestion des Membres
              </h1>
              <p class="text-gray-400 font-medium">Système complet de gestion des membres et familles</p>
            </div>
          </div>
        </div>
        
        <!-- Actions rapides -->
        <div class="flex items-center space-x-3">
          <button 
            @click="exportMembers"
            class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 px-4 py-2 rounded-lg hover:bg-gray-700/50 transition-all duration-200 flex items-center space-x-2"
          >
            <DocumentArrowDownIcon class="h-4 w-4" />
            <span class="text-sm">Export</span>
          </button>
          
          <button 
            @click="openCreateModal"
            class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded-lg transition-all duration-200 flex items-center space-x-2"
          >
            <UserPlusIcon class="h-4 w-4" />
            <span class="text-sm font-medium">Nouveau Membre</span>
          </button>
        </div>
      </div>

      <!-- Statistiques rapides -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <ModernStatsCard
          title="Total Membres"
          :value="stats.total_membres"
          icon-name="users"
          :change="stats.evolution_membres"
          format="number"
          color="blue"
        />
        
        <ModernStatsCard
          title="Membres Actifs"
          :value="stats.membres_actifs"
          icon-name="check-circle"
          format="number"
          color="green"
        />
        
        <ModernStatsCard
          title="Nouvelles Inscriptions"
          :value="stats.nouvelles_inscriptions"
          icon-name="user-plus"
          format="number"
          color="purple"
        />
        
        <ModernStatsCard
          title="Familles"
          :value="stats.total_familles"
          icon-name="home"
          format="number"
          color="orange"
        />
      </div>

      <!-- Filtres avancés -->
      <div class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-xl p-6 mb-8">
        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
          <AdjustmentsHorizontalIcon class="h-5 w-5 text-blue-400 mr-2" />
          Filtres et Recherche
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          <!-- Recherche -->
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Recherche</label>
            <div class="relative">
              <MagnifyingGlassIcon class="h-5 w-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
              <input
                v-model="filters.search"
                type="text"
                placeholder="Nom, prénom, email..."
                class="w-full pl-10 pr-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                @input="debounceSearch"
              />
            </div>
          </div>

          <!-- Statut -->
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Statut</label>
            <select
              v-model="filters.statut"
              class="w-full px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
              @change="applyFilters"
            >
              <option value="">Tous les statuts</option>
              <option value="actif">✅ Actif</option>
              <option value="inactif">⏸️ Inactif</option>
              <option value="suspendu">⛔ Suspendu</option>
              <option value="diplome">🎓 Diplômé</option>
            </select>
          </div>

          <!-- Ceinture -->
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Ceinture</label>
            <select
              v-model="filters.ceinture"
              class="w-full px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
              @change="applyFilters"
            >
              <option value="">Toutes les ceintures</option>
              <option v-for="ceinture in ceintures" :key="ceinture.id" :value="ceinture.id">
                {{ ceinture.emoji }} {{ ceinture.nom }}
              </option>
            </select>
          </div>

          <!-- Famille -->
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Vue</label>
            <select
              v-model="filters.vue"
              class="w-full px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
              @change="applyFilters"
            >
              <option value="tous">👥 Tous les membres</option>
              <option value="familles">🏠 Grouper par familles</option>
              <option value="adultes">👨 Adultes seulement</option>
              <option value="enfants">👶 Enfants seulement</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Liste des membres -->
      <div class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-xl overflow-hidden">
        <div class="p-6 border-b border-gray-700/50">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-white flex items-center">
              <UsersIcon class="h-5 w-5 text-blue-400 mr-2" />
              Liste des Membres ({{ paginatedMembers.total }})
            </h3>
            
            <div class="flex items-center space-x-2">
              <!-- Tri -->
              <select
                v-model="sortBy"
                class="px-3 py-2 bg-gray-700/50 border border-gray-600 rounded-lg text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                @change="applySort"
              >
                <option value="nom">📝 Trier par nom</option>
                <option value="prenom">📝 Trier par prénom</option>
                <option value="date_inscription">📅 Date d'inscription</option>
                <option value="derniere_presence">⏰ Dernière présence</option>
                <option value="ceinture">🥋 Niveau de ceinture</option>
              </select>
              
              <!-- Pagination -->
              <div class="flex items-center space-x-2 text-sm text-gray-400">
                <span>{{ paginatedMembers.from }}-{{ paginatedMembers.to }} sur {{ paginatedMembers.total }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Table responsive -->
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-700/30">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Membre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Statut</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Ceinture</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Famille</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Dernière Présence</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-700/50">
              <tr 
                v-for="membre in paginatedMembers.data" 
                :key="membre.id"
                class="hover:bg-gray-700/20 transition-colors duration-200"
              >
                <!-- Informations membre -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12">
                      <img 
                        :src="membre.photo || '/img/default-avatar.png'" 
                        :alt="membre.nom"
                        class="h-12 w-12 rounded-full object-cover border-2 border-gray-600"
                      />
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-white">
                        {{ membre.prenom }} {{ membre.nom }}
                      </div>
                      <div class="text-sm text-gray-400">
                        {{ membre.email }}
                      </div>
                      <div class="text-xs text-gray-500">
                        🎂 {{ formatAge(membre.date_naissance) }} ans
                      </div>
                    </div>
                  </div>
                </td>

                <!-- Statut -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="getStatutClass(membre.statut)" class="px-2 py-1 rounded-full text-xs font-medium">
                    {{ getStatutLabel(membre.statut) }}
                  </span>
                </td>

                <!-- Ceinture -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <span class="text-lg mr-2">{{ membre.ceinture_actuelle?.emoji }}</span>
                    <span class="text-sm text-white">{{ membre.ceinture_actuelle?.nom }}</span>
                  </div>
                </td>

                <!-- Famille -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <div v-if="membre.liens_familiaux?.length > 0" class="text-sm">
                    <div v-for="lien in membre.liens_familiaux.slice(0, 2)" :key="lien.id" class="text-gray-400">
                      {{ lien.type_relation }} de {{ lien.membre_lie.prenom }}
                    </div>
                    <div v-if="membre.liens_familiaux.length > 2" class="text-xs text-blue-400">
                      +{{ membre.liens_familiaux.length - 2 }} autres
                    </div>
                  </div>
                  <span v-else class="text-sm text-gray-500">Aucun lien</span>
                </td>

                <!-- Dernière présence -->
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                  {{ membre.derniere_presence ? formatDate(membre.derniere_presence) : 'Jamais' }}
                </td>

                <!-- Actions -->
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex items-center space-x-2">
                    <button
                      @click="viewMember(membre)"
                      class="text-blue-400 hover:text-blue-300 transition-colors p-2 rounded-lg hover:bg-blue-500/10"
                      title="Voir le profil"
                    >
                      <EyeIcon class="h-4 w-4" />
                    </button>
                    
                    <button
                      @click="editMember(membre)"
                      class="text-yellow-400 hover:text-yellow-300 transition-colors p-2 rounded-lg hover:bg-yellow-500/10"
                      title="Modifier"
                    >
                      <PencilIcon class="h-4 w-4" />
                    </button>
                    
                    <button
                      @click="manageFamilyLinks(membre)"
                      class="text-green-400 hover:text-green-300 transition-colors p-2 rounded-lg hover:bg-green-500/10"
                      title="Gérer les liens familiaux"
                    >
                      <LinkIcon class="h-4 w-4" />
                    </button>
                    
                    <button
                      @click="deleteMember(membre)"
                      class="text-red-400 hover:text-red-300 transition-colors p-2 rounded-lg hover:bg-red-500/10"
                      title="Supprimer"
                    >
                      <TrashIcon class="h-4 w-4" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-700/20 px-6 py-3 flex items-center justify-between border-t border-gray-700/50">
          <div class="flex items-center space-x-2">
            <select
              v-model="perPage"
              @change="changePerPage"
              class="px-3 py-1 bg-gray-700/50 border border-gray-600 rounded text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="10">10 par page</option>
              <option value="25">25 par page</option>
              <option value="50">50 par page</option>
              <option value="100">100 par page</option>
            </select>
          </div>
          
          <div class="flex items-center space-x-2">
            <button
              @click="previousPage"
              :disabled="!paginatedMembers.prev_page_url"
              class="px-3 py-2 bg-gray-700/50 text-white rounded-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-600/50 transition-colors"
            >
              ← Précédent
            </button>
            
            <span class="text-sm text-gray-400">
              Page {{ paginatedMembers.current_page }} sur {{ paginatedMembers.last_page }}
            </span>
            
            <button
              @click="nextPage"
              :disabled="!paginatedMembers.next_page_url"
              class="px-3 py-2 bg-gray-700/50 text-white rounded-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-600/50 transition-colors"
            >
              Suivant →
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modals -->
    <MemberCreateModal
      v-if="showCreateModal"
      :ceintures="ceintures"
      @close="showCreateModal = false"
      @created="onMemberCreated"
    />
    
    <MemberEditModal
      v-if="showEditModal && selectedMember"
      :member="selectedMember"
      :ceintures="ceintures"
      @close="showEditModal = false"
      @updated="onMemberUpdated"
    />
    
    <FamilyLinksModal
      v-if="showFamilyModal && selectedMember"
      :member="selectedMember"
      @close="showFamilyModal = false"
      @updated="onFamilyLinksUpdated"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import { 
  UserGroupIcon, UsersIcon, UserPlusIcon, MagnifyingGlassIcon,
  AdjustmentsHorizontalIcon, DocumentArrowDownIcon, EyeIcon,
  PencilIcon, LinkIcon, TrashIcon
} from '@heroicons/vue/24/outline'
import ModernStatsCard from '@/Components/ModernStatsCard.vue'
import MemberCreateModal from '@/Components/Members/CreateModal.vue'
import MemberEditModal from '@/Components/Members/EditModal.vue'
import FamilyLinksModal from '@/Components/Members/FamilyLinksModal.vue'

// Props
const props = defineProps({
  membres: Object,
  ceintures: Array,
  stats: Object
})

// State
const loading = ref(false)
const showCreateModal = ref(false)
const showEditModal = ref(false)
const showFamilyModal = ref(false)
const selectedMember = ref(null)

// Filtres et recherche
const filters = ref({
  search: '',
  statut: '',
  ceinture: '',
  vue: 'tous'
})

const sortBy = ref('nom')
const perPage = ref(25)
const currentPage = ref(1)

// Computed
const paginatedMembers = computed(() => {
  return props.membres || { data: [], total: 0, current_page: 1, last_page: 1 }
})

// Methods
const debounceSearch = debounce(() => {
  applyFilters()
}, 300)

const applyFilters = () => {
  router.visit('/membres', {
    data: {
      search: filters.value.search,
      statut: filters.value.statut,
      ceinture: filters.value.ceinture,
      vue: filters.value.vue,
      sort: sortBy.value,
      per_page: perPage.value,
      page: 1
    },
    preserveState: true,
    preserveScroll: true
  })
}

const applySort = () => {
  applyFilters()
}

const changePerPage = () => {
  applyFilters()
}

const nextPage = () => {
  if (paginatedMembers.value.next_page_url) {
    currentPage.value++
    applyFilters()
  }
}

const previousPage = () => {
  if (paginatedMembers.value.prev_page_url && currentPage.value > 1) {
    currentPage.value--
    applyFilters()
  }
}

// Actions
const openCreateModal = () => {
  showCreateModal.value = true
}

const viewMember = (member) => {
  router.visit(`/membres/${member.id}`)
}

const editMember = (member) => {
  selectedMember.value = member
  showEditModal.value = true
}

const manageFamilyLinks = (member) => {
  selectedMember.value = member
  showFamilyModal.value = true
}

const deleteMember = (member) => {
  if (confirm(`Êtes-vous sûr de vouloir supprimer ${member.prenom} ${member.nom} ?`)) {
    router.delete(`/membres/${member.id}`, {
      onSuccess: () => {
        // Notification de succès
      }
    })
  }
}

const exportMembers = () => {
  window.open('/membres/export', '_blank')
}

// Event handlers
const onMemberCreated = () => {
  showCreateModal.value = false
  router.reload({ only: ['membres', 'stats'] })
}

const onMemberUpdated = (member) => {
  showEditModal.value = false
  selectedMember.value = null
  router.reload({ only: ['membres', 'stats'] })
}

const onFamilyLinksUpdated = () => {
  showFamilyModal.value = false
  selectedMember.value = null
  router.reload({ only: ['membres'] })
}

// Utility functions
const formatAge = (dateNaissance) => {
  if (!dateNaissance) return 'N/A'
  const today = new Date()
  const birth = new Date(dateNaissance)
  let age = today.getFullYear() - birth.getFullYear()
  const monthDiff = today.getMonth() - birth.getMonth()
  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
    age--
  }
  return age
}

const formatDate = (date) => {
  if (!date) return 'Jamais'
  return new Date(date).toLocaleDateString('fr-CA', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const getStatutClass = (statut) => {
  const classes = {
    'actif': 'bg-green-500/20 text-green-300',
    'inactif': 'bg-gray-500/20 text-gray-300',
    'suspendu': 'bg-red-500/20 text-red-300',
    'diplome': 'bg-purple-500/20 text-purple-300'
  }
  return classes[statut] || 'bg-gray-500/20 text-gray-300'
}

const getStatutLabel = (statut) => {
  const labels = {
    'actif': '✅ Actif',
    'inactif': '⏸️ Inactif',
    'suspendu': '⛔ Suspendu',
    'diplome': '🎓 Diplômé'
  }
  return labels[statut] || statut
}

// Debounce function
function debounce(func, wait) {
  let timeout
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout)
      func(...args)
    }
    clearTimeout(timeout)
    timeout = setTimeout(later, wait)
  }
}
</script>

<style scoped>
.bg-pattern {
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.02'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.backdrop-blur-xl {
  backdrop-filter: blur(12px);
}
</style>
