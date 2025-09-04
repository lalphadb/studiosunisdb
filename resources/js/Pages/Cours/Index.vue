<template>
  <AuthenticatedLayout>
    <Head title="Gestion des Cours" />
    
  <div class="min-h-screen max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 overflow-x-hidden">
      <!-- Messages de feedback -->
      <div v-if="$page.props.flash?.success" class="mb-4 rounded-xl border border-green-500/40 bg-green-500/10 text-green-300 px-4 py-3 text-sm font-medium">
        {{$page.props.flash.success}}
      </div>
      <div v-if="$page.props.errors?.delete" class="mb-4 rounded-xl border border-red-500/40 bg-red-500/10 text-red-300 px-4 py-3 text-sm font-medium">
        {{$page.props.errors.delete}}
      </div>
  <PageHeader title="Cours" description="Planning et gestion des séances de karaté">
        <template #actions>
          <button @click="showCalendarView = !showCalendarView"
                  class="px-4 py-2 rounded-lg bg-slate-700/50 hover:bg-slate-600/50 text-white text-sm font-medium border border-slate-600">
            Vue Calendrier
          </button>
      <div class="flex items-center gap-2 bg-slate-800/40 rounded-lg p-1 border border-slate-700/60" data-testid="archives-toggle">
        <button @click="setArchives(false)" :class="['px-3 py-1.5 rounded-md text-sm font-medium transition', !showingArchives ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow' : 'text-slate-300 hover:text-white']">Actifs</button>
        <button @click="setArchives(true)" :class="['px-3 py-1.5 rounded-md text-sm font-medium transition', showingArchives ? 'bg-gradient-to-r from-pink-500 to-rose-600 text-white shadow' : 'text-slate-300 hover:text-white']">Archives</button>
      </div>
          <Link href="/cours/create"
                class="px-4 py-2 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-medium hover:from-indigo-400 hover:to-purple-500">
            Nouveau Cours
          </Link>
        </template>
      </PageHeader>
      
      <!-- Stats Cards -->
  <!-- Stats: wrap gracefully -->
  <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6">
        <StatCard title="Total Cours" :value="stats.totalCours" tone="blue" description="Tous niveaux" />
        <StatCard title="Cours Actifs" :value="stats.coursActifs" tone="green" description="En cours cette semaine" />
        <StatCard title="Instructeurs" :value="stats.totalInstructeurs" tone="purple" description="Actifs cette saison" />
        <StatCard title="Séances/Semaine" :value="stats.seancesParSemaine" tone="amber" description="Planning hebdomadaire" />
      </div>
      
      <!-- Filtres -->
      <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-4 md:p-6 mb-6">
        <form @submit.prevent="applyFilters" class="space-y-4">
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3 md:gap-4">
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
          <div class="flex flex-wrap gap-3">
            <button type="submit" class="px-4 md:px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl flex items-center gap-2 transition-all font-medium shadow-lg">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
              </svg>
              Filtrer
            </button>
            <button type="button" @click="resetFilters" class="px-4 md:px-5 py-2.5 bg-slate-800/50 hover:bg-slate-700/50 text-white rounded-xl flex items-center gap-2 transition-all border border-slate-700">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
              Réinitialiser
            </button>
            <button @click="exportCours" class="ml-auto px-4 md:px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl flex items-center gap-2 transition-all font-medium shadow-lg">
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
        <div class="overflow-x-auto -mx-4 sm:mx-0">
          <table class="w-full">
            <thead>
              <tr class="bg-slate-900/50 border-b border-slate-700/50">
                <th class="px-4 md:px-6 py-3 md:py-4 text-left text-[10px] md:text-xs font-semibold text-slate-400 uppercase tracking-wider">
                  Cours
                </th>
                <th class="px-4 md:px-6 py-3 md:py-4 text-left text-[10px] md:text-xs font-semibold text-slate-400 uppercase tracking-wider hidden sm:table-cell">
                  Instructeur
                </th>
                <th class="px-4 md:px-6 py-3 md:py-4 text-left text-[10px] md:text-xs font-semibold text-slate-400 uppercase tracking-wider">
                  Horaire
                </th>
                <th class="px-4 md:px-6 py-3 md:py-4 text-left text-[10px] md:text-xs font-semibold text-slate-400 uppercase tracking-wider hidden lg:table-cell">
                  Niveau
                </th>
                <th class="px-4 md:px-6 py-3 md:py-4 text-left text-[10px] md:text-xs font-semibold text-slate-400 uppercase tracking-wider hidden md:table-cell">
                  Inscrits
                </th>
                <th class="px-4 md:px-6 py-3 md:py-4 text-left text-[10px] md:text-xs font-semibold text-slate-400 uppercase tracking-wider hidden sm:table-cell">
                  Statut
                </th>
                <th class="px-4 md:px-6 py-3 md:py-4 text-right text-[10px] md:text-xs font-semibold text-slate-400 uppercase tracking-wider w-20 md:w-24">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/30">
        <tr v-for="cours in filteredCours" 
          :key="cours.id"
          :class="['hover:bg-slate-800/30 transition-all duration-200 group', cours.is_archived ? 'opacity-50 line-through' : '', (highlightedId && highlightedId === cours.id) ? 'ring-2 ring-indigo-400 animate-pulse' : '']">
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap max-w-[160px] md:max-w-none">
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
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap hidden sm:table-cell">
                  <div class="text-sm text-slate-300">{{ cours.instructeur?.name || 'Non assigné' }}</div>
                  <div class="text-xs text-slate-500">{{ cours.instructeur?.email }}</div>
                </td>
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                  <div class="text-sm text-slate-300 capitalize">{{ cours.jour_semaine_display }}</div>
                  <div class="text-xs text-slate-500">{{ cours.heure_debut_format }} - {{ cours.heure_fin_format }}</div>
                </td>
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap hidden lg:table-cell">
                  <span :class="getNiveauBadgeClass(cours.niveau)"
                        class="px-3 py-1 inline-flex text-xs font-medium rounded-full">
                    {{ cours.niveau }}
                  </span>
                </td>
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap hidden md:table-cell">
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
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap hidden sm:table-cell">
                  <div class="flex items-center gap-2">
                    <span v-if="cours.actif && !cours.is_archived" 
                          class="px-3 py-1 inline-flex text-xs font-medium rounded-full bg-green-500/20 text-green-400 border border-green-500/30">
                      Actif
                    </span>
                    <span v-else-if="!cours.actif && !cours.is_archived" 
                          class="px-3 py-1 inline-flex text-xs font-medium rounded-full bg-red-500/20 text-red-400 border border-red-500/30">
                      Inactif
                    </span>
                    <span v-if="showingArchives && cours.is_archived" class="px-3 py-1 inline-flex text-xs font-medium rounded-full bg-slate-500/20 text-slate-300 border border-slate-600/40" data-testid="badge-archive">
                      Archivé
                    </span>
                  </div>
                </td>
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex items-center justify-end gap-1 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-200">
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
          title="Supprimer" data-testid="btn-delete-cours">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                    <button v-if="cours.is_archived" @click="restoreCours(cours)"
                            class="p-1.5 text-emerald-400 hover:text-emerald-300 hover:bg-emerald-500/10 rounded-lg transition-all"
                            title="Restaurer">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v6h6M20 20v-6h-6M5 19A9 9 0 0119 5" />
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-4 md:px-6 py-4 border-t border-slate-700/50 bg-slate-900/30">
          <Pagination 
            :links="cours.links || []" 
            :from="cours.from || null" 
            :to="cours.to || null" 
            :total="cours.total || null" 
          />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
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
  newCoursId: {
    type: [Number, String, null],
    default: null
  },
  showingArchives: {
    type: Boolean,
    default: false
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

// Accès direct aux props de page si besoin d'étendre plus tard (ex: loader, permissions dynamiques)
const page = usePage()

// Highlight newly duplicated course (flash prop newCoursId)
const highlightedId = ref(props.newCoursId)
onMounted(() => {
  if (highlightedId.value) {
    setTimeout(() => { highlightedId.value = null }, 4500)
  }
})

const coursData = computed(() => {
  // Avec la pagination Laravel standard, les données sont dans cours.data
  if (props.cours && props.cours.data) {
    return props.cours.data
  }
  // Fallback pour compatibilité avec ancien format
  if (Array.isArray(props.cours)) {
    return props.cours
  }
  return []
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
    router.post(`/cours/${cours.id}/duplicate`, {}, {
      preserveScroll: true,
      onSuccess: () => {
        // Recharger la page pour voir le nouveau cours
        router.reload({ preserveScroll: true })
      },
      onError: (errors) => {
        console.error('Erreur duplication cours:', errors)
      }
    })
  }
}

const deleteCours = (cours) => {
  // Infos locales
  const inscrits = cours.inscrits_count ?? cours.membres_actifs_count ?? 0
  const isArchived = !!cours.deleted_at || cours.is_archived

  // Première étape: choix action (archive vs delete définitif)
  let wantsForce = confirm(
    `Cours: "${cours.nom}"
\nActive: ${!isArchived ? 'Oui' : 'Déjà archivé'} | Inscriptions actives: ${inscrits}
\nOK = SUPPRESSION DÉFINITIVE${inscrits>0 ? ' (bloquée car inscriptions actives)' : ' (irréversible)'}
Annuler = ${isArchived ? 'Annuler (aucune action)' : 'ARCHIVER (restorable)'}
`
  )

  // Si déjà archivé et l'utilisateur clique Annuler -> rien à faire
  if (isArchived && !wantsForce) return

  // Sécurité: impossible de forcer si inscriptions actives
  if (wantsForce && inscrits > 0) {
    alert('Suppression définitive refusée: des inscriptions actives existent. Le cours sera simplement archivé.')
    wantsForce = false
  }

  // Si suppression définitive demandée, double confirmation
  if (wantsForce) {
    const confirmForce = confirm('Confirmer la suppression DÉFINITIVE ? Cette action est irréversible.')
    if (!confirmForce) wantsForce = false
  }

  const url = wantsForce ? `/cours/${cours.id}?force=1` : `/cours/${cours.id}?archiver=1`
  // Suppression cours (debug logs retirés)
  router.delete(url, {
    preserveScroll: true,
  // headers nettoyés (aucun header debug)
    onSuccess: (page) => {
  // Suppression effectuée (log debug retiré)
      if (props.showingArchives || wantsForce) {
        router.get('/cours?archives=1', {}, { preserveScroll: true, preserveState: false })
      } else {
        router.reload({ preserveScroll: true })
      }
    },
    onError: (errors) => {
      console.error('[Cours] Erreur suppression', errors)
      const msg = errors?.delete || 'Échec de la suppression. Vérifiez les inscriptions ou réessayez.'
      alert(msg)
    }
  })
}

const exportCours = () => {
  window.open('/cours/export')
}

const restoreCours = (cours) => {
  if (!confirm(`Restaurer le cours "${cours.nom}" ?`)) return
  router.post(`/cours/${cours.id}/restore`, {}, {
    preserveScroll: true,
    onSuccess: () => {
      // Recharger la page pour actualiser la pagination
      router.reload({ preserveScroll: true })
    },
    onError: (errors) => {
      console.error('Erreur restauration cours:', errors)
    }
  })
}

const setArchives = (val) => {
  if (val === props.showingArchives) return
  const url = val ? '/cours?archives=1' : '/cours'
  router.get(url, {}, { preserveScroll: true, preserveState: false })
}
</script>
