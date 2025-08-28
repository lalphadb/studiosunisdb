<template>
  <AuthenticatedLayout>
    <Head title="Planning des Cours" />
    
    <div class="min-h-screen max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <PageHeader title="Planning" description="Vue d'ensemble du planning hebdomadaire des cours">
        <template #actions>
          <Link href="/cours"
                class="px-4 py-2 rounded-lg bg-slate-700/50 hover:bg-slate-600/50 text-white text-sm font-medium border border-slate-600">
            ← Retour aux cours
          </Link>
          <Link href="/cours/create"
                class="px-4 py-2 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-medium hover:from-indigo-400 hover:to-purple-500">
            Nouveau Cours
          </Link>
        </template>
      </PageHeader>
      
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
        </div>
      </div>
      
      <!-- Planning hebdomadaire -->
      <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="bg-slate-900/50 border-b border-slate-700/50">
                <th class="px-4 py-4 text-left text-sm font-semibold text-slate-400 uppercase tracking-wider w-20">
                  Heure
                </th>
                <th v-for="jour in joursSemaine" :key="jour.key"
                    class="px-3 py-4 text-center text-sm font-semibold text-slate-400 uppercase tracking-wider">
                  {{ jour.label }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="heure in creneauxHoraires" :key="heure"
                  class="border-b border-slate-700/30 hover:bg-slate-800/20">
                <td class="px-4 py-2 text-sm font-medium text-slate-400 border-r border-slate-700/30">
                  {{ heure }}
                </td>
                <td v-for="jour in joursSemaine" :key="jour.key"
                    class="px-2 py-2 border-r border-slate-700/30 relative">
                  <div class="space-y-1">
                    <div v-for="cours in getCoursForSlot(jour.key, heure)"
                         :key="cours.id"
                         :class="getCoursCardClass(cours)"
                         class="p-2 rounded-lg cursor-pointer transition-all hover:scale-105 hover:shadow-lg">
                      <div class="text-xs font-medium text-white truncate">
                        {{ cours.nom }}
                      </div>
                      <div class="text-xs text-white/80 truncate">
                        {{ cours.instructeur?.name || 'Sans instructeur' }}
                      </div>
                      <div class="text-xs text-white/60">
                        {{ cours.inscrits_count || 0 }}/{{ cours.places_max }}
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      
      <!-- Légende -->
      <div class="mt-6 bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Légende</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
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
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'

const props = defineProps({
  planning: {
    type: Object,
    default: () => ({})
  },
  instructeurs: {
    type: Array,
    default: () => []
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

const getCoursCardClass = (cours) => {
  const baseClasses = 'min-h-[60px]'
  
  const niveauClasses = {
    'debutant': 'bg-green-500/80',
    'intermediaire': 'bg-blue-500/80', 
    'avance': 'bg-purple-500/80',
    'competition': 'bg-red-500/80'
  }
  
  return `${baseClasses} ${niveauClasses[cours.niveau] || 'bg-slate-600/80'}`
}
</script>
