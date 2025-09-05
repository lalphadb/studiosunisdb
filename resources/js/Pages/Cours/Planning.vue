<template>
  <AuthenticatedLayout>
    <Head title="Planning des Cours" />
    
    <div class="min-h-screen max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <PageHeader title="Planning" description="Vue d'ensemble du planning hebdomadaire des cours">
        <template #actions>
          <Link href="/cours"
                class="px-4 py-2 rounded-lg bg-slate-700/50 hover:bg-slate-600/50 text-white text-sm font-medium border border-slate-600 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2H9z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5h2a2 2 0 012 2v6a2 2 0 01-2 2h-6a2 2 0 01-2-2V7a2 2 0 012-2h6z" />
            </svg>
            Vue Liste
          </Link>
          <Link href="/cours/create"
                class="px-4 py-2 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-medium hover:from-indigo-400 hover:to-purple-500">
            Nouveau Cours
          </Link>
        </template>
      </PageHeader>
      
      <!-- Stats du planning -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <StatCard title="Cours Actifs" :value="stats.totalCours" tone="blue" description="Planning cette semaine" />
        <StatCard title="Jours Programmés" :value="stats.joursActifs" tone="green" description="Jours avec cours" />
        <StatCard title="Instructeurs" :value="stats.totalInstructeurs" tone="purple" description="Équipe active" />
        <StatCard title="Conflits Détectés" :value="stats.conflitsDetectes" :tone="stats.conflitsDetectes > 0 ? 'red' : 'green'" description="Superpositions horaires" />
      </div>
      
      <!-- Filtres planning -->
      <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6 mb-6">
        <div class="flex flex-wrap items-center gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-400 mb-2">Instructeur</label>
            <select v-model="filtreInstructeur" class="bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              <option value="">Tous les instructeurs</option>
              <option v-for="inst in instructeurs" :key="inst.id" :value="inst.id">{{ inst.name }}</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-slate-400 mb-2">Niveau</label>
            <select v-model="filtreNiveau" class="bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              <option value="">Tous les niveaux</option>
              <option value="debutant">Débutant</option>
              <option value="intermediaire">Intermédiaire</option>
              <option value="avance">Avancé</option>
              <option value="competition">Compétition</option>
            </select>
          </div>
          
          <div class="flex items-center gap-3">
            <input
              id="cours-actifs"
              v-model="voirSeulementActifs"
              type="checkbox"
              class="w-4 h-4 text-blue-600 bg-slate-900 border-slate-700 rounded focus:ring-blue-500 focus:ring-2"
            />
            <label for="cours-actifs" class="text-sm text-slate-300">
              Cours actifs seulement
            </label>
          </div>
          
          <button @click="resetFilters" class="ml-auto px-4 py-2 bg-slate-800/50 hover:bg-slate-700/50 text-white rounded-xl border border-slate-700 transition-all text-sm">
            Réinitialiser filtres
          </button>
        </div>
      </div>
      
      <!-- Planning hebdomadaire -->
      <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full min-w-[800px]">
            <thead>
              <tr class="bg-slate-900/50 border-b border-slate-700/50">
                <th class="px-4 py-4 text-left text-sm font-semibold text-slate-400 uppercase tracking-wider w-20">
                  Heure
                </th>
                <th v-for="jour in joursSemaine" :key="jour.key"
                    class="px-3 py-4 text-center text-sm font-semibold text-slate-400 uppercase tracking-wider min-w-[120px]">
                  {{ jour.label }}
                  <div class="text-xs text-slate-500 font-normal mt-1">
                    {{ getCoursCountForDay(jour.key) }} cours
                  </div>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="heure in creneauxHoraires" :key="heure"
                  class="border-b border-slate-700/30 hover:bg-slate-800/20">
                <td class="px-4 py-3 text-sm font-medium text-slate-400 border-r border-slate-700/30 bg-slate-900/30">
                  {{ heure }}
                </td>
                <td v-for="jour in joursSemaine" :key="jour.key"
                    class="px-2 py-2 border-r border-slate-700/30 relative align-top">
                  <div class="space-y-1 min-h-[60px]">
                    <div v-for="cours in getCoursForSlot(jour.key, heure)"
                         :key="cours.id"
                         :class="getCoursCardClass(cours)"
                         class="p-2 rounded-lg cursor-pointer transition-all hover:scale-105 hover:shadow-lg group"
                         @click="viewCours(cours)"
                         :title="`${cours.nom} - ${cours.instructeur?.name || 'Sans instructeur'} - ${cours.inscrits_count}/${cours.places_max} inscrits`">
                      <div class="text-xs font-medium text-white truncate">
                        {{ cours.nom }}
                      </div>
                      <div class="text-xs text-white/80 truncate">
                        {{ cours.instructeur?.name || 'Sans instructeur' }}
                      </div>
                      <div class="text-xs text-white/60">
                        {{ cours.inscrits_count || 0 }}/{{ cours.places_max }}
                      </div>
                      
                      <!-- Actions au hover -->
                      <div class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1">
                        <button @click.stop="editCours(cours)"
                                class="p-1 bg-amber-500/80 hover:bg-amber-600 text-white rounded text-xs"
                                title="Modifier">
                          <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                          </svg>
                        </button>
                        <button @click.stop="duplicateCours(cours)"
                                class="p-1 bg-blue-500/80 hover:bg-blue-600 text-white rounded text-xs"
                                title="Dupliquer">
                          <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                          </svg>
                        </button>
                      </div>
                    </div>
                    
                    <!-- Créneaux vides cliquables -->
                    <div v-if="getCoursForSlot(jour.key, heure).length === 0"
                         class="min-h-[60px] border-2 border-dashed border-slate-600/50 rounded-lg flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity cursor-pointer group"
                         @click="createCoursForSlot(jour.key, heure)"
                         :title="`Créer un cours ${jour.label} à ${heure}`">
                      <div class="text-xs text-slate-500 group-hover:text-slate-400 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Ajouter
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      
      <!-- Légende améliorée -->
      <div class="mt-6 bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Légende & Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Niveaux -->
          <div>
            <h4 class="text-sm font-medium text-slate-300 mb-3">Niveaux de cours</h4>
            <div class="grid grid-cols-2 gap-3">
              <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-green-500/80 rounded"></div>
                <span class="text-sm text-slate-300">Débutant</span>
              </div>
              <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-blue-500/80 rounded"></div>
                <span class="text-sm text-slate-300">Intermédiaire</span>
              </div>
              <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-purple-500/80 rounded"></div>
                <span class="text-sm text-slate-300">Avancé</span>
              </div>
              <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-red-500/80 rounded"></div>
                <span class="text-sm text-slate-300">Compétition</span>
              </div>
            </div>
          </div>
          
          <!-- Actions -->
          <div>
            <h4 class="text-sm font-medium text-slate-300 mb-3">Actions disponibles</h4>
            <div class="space-y-2 text-sm text-slate-400">
              <div>• Cliquer sur un cours pour voir les détails</div>
              <div>• Hover sur un cours pour les actions (modifier, dupliquer)</div>
              <div>• Cliquer sur un créneau vide pour créer un nouveau cours</div>
              <div>• Utiliser les filtres pour personnaliser la vue</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import StatCard from '@/Components/UI/StatCard.vue'

const props = defineProps({
  planning: {
    type: Object,
    default: () => ({})
  },
  instructeurs: {
    type: Array,
    default: () => []
  },
  stats: {
    type: Object,
    default: () => ({
      totalCours: 0,
      joursActifs: 0,
      totalInstructeurs: 0,
      conflitsDetectes: 0
    })
  }
})

const filtreInstructeur = ref('')
const filtreNiveau = ref('')
const voirSeulementActifs = ref(true)

const joursSemaine = [
  { key: 'lundi', label: 'Lundi' },
  { key: 'mardi', label: 'Mardi' },
  { key: 'mercredi', label: 'Mercredi' },
  { key: 'jeudi', label: 'Jeudi' },
  { key: 'vendredi', label: 'Vendredi' },
  { key: 'samedi', label: 'Samedi' },
  { key: 'dimanche', label: 'Dimanche' }
]

const creneauxHoraires = [
  '08:00', '09:00', '10:00', '11:00', '12:00', 
  '13:00', '14:00', '15:00', '16:00', '17:00', 
  '18:00', '19:00', '20:00', '21:00'
]

const coursFiltrés = computed(() => {
  let allCours = []
  
  // Collecter tous les cours du planning
  Object.values(props.planning || {}).forEach(coursList => {
    if (Array.isArray(coursList)) {
      allCours = [...allCours, ...coursList]
    }
  })
  
  return allCours.filter(cours => {
    if (voirSeulementActifs.value && !cours.actif) return false
    if (filtreInstructeur.value && cours.instructeur_id != filtreInstructeur.value) return false
    if (filtreNiveau.value && cours.niveau !== filtreNiveau.value) return false
    return true
  })
})

const getCoursForSlot = (jour, heure) => {
  return coursFiltrés.value.filter(cours => {
    if (cours.jour_semaine !== jour) return false
    
    const heureDebut = cours.heure_debut?.substring(0, 5)
    const heureFin = cours.heure_fin?.substring(0, 5)
    
    return heureDebut <= heure && heure < heureFin
  })
}

const getCoursCountForDay = (jour) => {
  const coursJour = props.planning[jour] || []
  return coursJour.filter(cours => {
    if (voirSeulementActifs.value && !cours.actif) return false
    if (filtreInstructeur.value && cours.instructeur_id != filtreInstructeur.value) return false
    if (filtreNiveau.value && cours.niveau !== filtreNiveau.value) return false
    return true
  }).length
}

const getCoursCardClass = (cours) => {
  const baseClasses = 'min-h-[60px] relative'
  
  const niveauClasses = {
    'debutant': 'bg-green-500/80 hover:bg-green-600/80',
    'intermediaire': 'bg-blue-500/80 hover:bg-blue-600/80', 
    'avance': 'bg-purple-500/80 hover:bg-purple-600/80',
    'competition': 'bg-red-500/80 hover:bg-red-600/80'
  }
  
  return `${baseClasses} ${niveauClasses[cours.niveau] || 'bg-slate-600/80 hover:bg-slate-700/80'}`
}

const resetFilters = () => {
  filtreInstructeur.value = ''
  filtreNiveau.value = ''
  voirSeulementActifs.value = true
}

const viewCours = (cours) => {
  router.get(`/cours/${cours.id}`)
}

const editCours = (cours) => {
  router.get(`/cours/${cours.id}/edit`)
}

const duplicateCours = (cours) => {
  if (confirm(`Dupliquer le cours "${cours.nom}" ?`)) {
    router.post(`/cours/${cours.id}/duplicate`, {}, {
      onSuccess: () => {
        router.reload({ preserveScroll: true })
      }
    })
  }
}

const createCoursForSlot = (jour, heure) => {
  // Redirection vers création avec paramètres pré-remplis
  const params = new URLSearchParams({
    jour_semaine: jour,
    heure_debut: heure,
    heure_fin: getNextHour(heure)
  })
  
  router.get(`/cours/create?${params.toString()}`)
}

const getNextHour = (heure) => {
  const [h, m] = heure.split(':').map(Number)
  const nextHour = h + 1
  return `${nextHour.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}`
}
</script>
