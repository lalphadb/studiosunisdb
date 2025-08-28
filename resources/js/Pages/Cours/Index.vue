<template>
  <AuthenticatedLayout>
    <Head title="Gestion des Cours" />
    
    <div class="min-h-screen max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <PageHeader title="Cours" description="Planning et gestion des séances de karaté">
        <template #actions>
          <button @click="showCalendarView = !showCalendarView"
                  class="px-4 py-2 rounded-lg bg-slate-700/50 hover:bg-slate-600/50 text-white text-sm font-medium border border-slate-600">
            Vue Calendrier
          </button>
          <Link href="/cours/create"
                class="px-4 py-2 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-medium hover:from-indigo-400 hover:to-purple-500">
            Nouveau Cours
          </Link>
        </template>
      </PageHeader>
      
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <StatCard title="Total Cours" :value="stats.totalCours" tone="blue" description="Tous niveaux" />
        <StatCard title="Cours Actifs" :value="stats.coursActifs" tone="green" description="En cours cette semaine" />
        <StatCard title="Instructeurs" :value="stats.totalInstructeurs" tone="purple" description="Actifs cette saison" />
        <StatCard title="Séances/Semaine" :value="stats.seancesParSemaine" tone="amber" description="Planning hebdomadaire" />
      </div>
      
      <!-- Filtres -->
      <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6 mb-6">
        <form @submit.prevent="applyFilters" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <!-- Recherche -->
            <div class="lg:col-span-2">
              <label class="block text-sm font-medium text-slate-400 mb-2">Recherche</label>
              <div class="relative">
                <input
                  v-model="searchTerm"
                  type="text"
                  class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-slate-500"
                  placeholder="Nom du cours..."
                  @input="debouncedSearch"
                />
                <svg class="absolute left-3 top-3 w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
            </div>
            
            <!-- Niveau -->
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">Niveau</label>
              <select v-model="filters.niveau" class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Tous les niveaux</option>
                <option value="debutant">Débutant</option>
                <option value="intermediaire">Intermédiaire</option>
                <option value="avance">Avancé</option>
                <option value="competition">Compétition</option>
              </select>
            </div>
            
            <!-- Instructeur -->
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">Instructeur</label>
              <select v-model="filters.instructeur" class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Tous</option>
                <option v-for="inst in instructeurs" :key="inst.id" :value="inst.id">{{ inst.name }}</option>
              </select>
            </div>
            
            <!-- Jour -->
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">Jour</label>
              <select v-model="filters.jour" class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Tous les jours</option>
                <option value="lundi">Lundi</option>
                <option value="mardi">Mardi</option>
                <option value="mercredi">Mercredi</option>
                <option value="jeudi">Jeudi</option>
                <option value="vendredi">Vendredi</option>
                <option value="samedi">Samedi</option>
                <option value="dimanche">Dimanche</option>
              </select>
            </div>
            
            <!-- Tri -->
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">Tri par</label>
              <select v-model="filters.sort" class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="jour_semaine">Jour</option>
                <option value="nom">Nom</option>
                <option value="niveau">Niveau</option>
                <option value="heure_debut">Heure</option>
              </select>
            </div>
          </div>
          
          <!-- Actions -->
          <div class="flex gap-3">
            <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl flex items-center gap-2 transition-all font-medium shadow-lg">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
              </svg>
              Filtrer
            </button>
            <button type="button" @click="resetFilters" class="px-5 py-2.5 bg-slate-800/50 hover:bg-slate-700/50 text-white rounded-xl flex items-center gap-2 transition-all border border-slate-700">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
              Réinitialiser
            </button>
            <button @click="exportCours" class="ml-auto px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl flex items-center gap-2 transition-all font-medium shadow-lg">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
              </svg>
              Exporter
            </button>
          </div>
        </form>
      </div>
      
      <!-- Table -->
      <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 overflow-hidden mb-6">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="bg-slate-900/50 border-b border-slate-700/50">
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                  Cours
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                  Instructeur
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                  Horaire
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                  Niveau
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                  Inscrits
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                  Statut
                </th>
                <th class="px-6 py-4 text-right text-xs font-semibold text-slate-400 uppercase tracking-wider w-24">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/30">
              <tr v-for="cours in filteredCours" 
                  :key="cours.id"
                  class="hover:bg-slate-800/30 transition-all duration-200 group">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8">
                      <div class="h-8 w-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-semibold shadow-lg text-xs">
                        {{ cours.nom[0] }}
                      </div>
                    </div>
                    <div class="ml-3">
                      <div class="text-sm font-medium text-white">{{ cours.nom }}</div>
                      <div class="text-xs text-slate-400">{{ cours.age_min }}-{{ cours.age_max }} ans</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-slate-300">{{ cours.instructeur?.name || 'Non assigné' }}</div>
                  <div class="text-xs text-slate-500">{{ cours.instructeur?.email }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-slate-300 capitalize">{{ cours.jour_semaine_display }}</div>
                  <div class="text-xs text-slate-500">{{ cours.heure_debut_format }} - {{ cours.heure_fin_format }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="getNiveauBadgeClass(cours.niveau)"
                        class="px-3 py-1 inline-flex text-xs font-medium rounded-full">
                    {{ cours.niveau }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="text-sm text-slate-300">
                      {{ cours.inscrits_count || 0 }}/{{ cours.places_max }}
                    </div>
                    <div class="ml-3 w-16 bg-slate-700 rounded-full h-2">
                      <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2 rounded-full transition-all" 
                           :style="`width: ${Math.min(((cours.inscrits_count || 0) / cours.places_max) * 100, 100)}%`"></div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span v-if="cours.actif" 
                        class="px-3 py-1 inline-flex text-xs font-medium rounded-full bg-green-500/20 text-green-400 border border-green-500/30">
                    Actif
                  </span>
                  <span v-else 
                        class="px-3 py-1 inline-flex text-xs font-medium rounded-full bg-red-500/20 text-red-400 border border-red-500/30">
                    Inactif
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <button @click="viewCours(cours)"
                            class="p-1.5 text-blue-400 hover:text-blue-300 hover:bg-blue-500/10 rounded-lg transition-all"
                            title="Voir">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    </button>
                    <button @click="editCours(cours)"
                            class="p-1.5 text-amber-400 hover:text-amber-300 hover:bg-amber-500/10 rounded-lg transition-all"
                            title="Modifier">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </button>
                    <button @click="duplicateCours(cours)"
                            class="p-1.5 text-purple-400 hover:text-purple-300 hover:bg-purple-500/10 rounded-lg transition-all"
                            title="Dupliquer">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                      </svg>
                    </button>
                    <button @click="deleteCours(cours)"
                            class="p-1.5 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition-all"
                            title="Supprimer">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-slate-700/50 bg-slate-900/30">
          <Pagination :links="cours.links || []" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import StatCard from '@/Components/UI/StatCard.vue'
import { debounce } from 'lodash'

const props = defineProps({
  cours: {
    type: [Array, Object],
    default: () => ({ data: [], links: [] })
  },
  instructeurs: {
    type: Array,
    default: () => []
  },
  stats: {
    type: Object,
    default: () => ({
      totalCours: 0,
      coursActifs: 0,
      totalInstructeurs: 0,
      seancesParSemaine: 0
    })
  }
})

const searchTerm = ref('')
const showCalendarView = ref(false)
const filters = ref({
  niveau: '',
  instructeur: '',
  jour: '',
  sort: 'jour_semaine'
})

const coursData = computed(() => {
  if (Array.isArray(props.cours)) {
    return props.cours
  }
  return props.cours.data || []
})

const filteredCours = computed(() => {
  let result = [...coursData.value]
  
  if (searchTerm.value) {
    const search = searchTerm.value.toLowerCase()
    result = result.filter(c => 
      c.nom?.toLowerCase().includes(search) ||
      c.instructeur?.name?.toLowerCase().includes(search)
    )
  }
  
  if (filters.value.niveau) {
    result = result.filter(c => c.niveau === filters.value.niveau)
  }
  
  if (filters.value.instructeur) {
    result = result.filter(c => c.instructeur?.id == filters.value.instructeur)
  }
  
  if (filters.value.jour) {
    result = result.filter(c => c.jour_semaine === filters.value.jour)
  }
  
  return result
})

const getNiveauBadgeClass = (niveau) => {
  const classes = {
    'debutant': 'bg-green-500/20 text-green-400 border border-green-500/30',
    'intermediaire': 'bg-blue-500/20 text-blue-400 border border-blue-500/30',
    'avance': 'bg-purple-500/20 text-purple-400 border border-purple-500/30',
    'competition': 'bg-red-500/20 text-red-400 border border-red-500/30'
  }
  return classes[niveau] || 'bg-slate-500/20 text-slate-400 border border-slate-500/30'
}

const debouncedSearch = debounce(() => {
  applyFilters()
}, 300)

const applyFilters = () => {
  // Appliquer filtres côté client pour simplicité
}

const resetFilters = () => {
  searchTerm.value = ''
  filters.value = {
    niveau: '',
    instructeur: '',
    jour: '',
    sort: 'jour_semaine'
  }
}

const viewCours = (cours) => {
  router.get(`/cours/${cours.id}`)
}

const editCours = (cours) => {
  router.get(`/cours/${cours.id}/edit`)
}

const duplicateCours = (cours) => {
  if (confirm(`Voulez-vous dupliquer le cours "${cours.nom}" ?`)) {
    router.post(`/cours/${cours.id}/duplicate`)
  }
}

const deleteCours = (cours) => {
  if (confirm(`Êtes-vous sûr de vouloir supprimer le cours "${cours.nom}" ?`)) {
    router.delete(`/cours/${cours.id}`)
  }
}

const exportCours = () => {
  window.open('/cours/export')
}
</script>
