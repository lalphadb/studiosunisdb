<template>
  <Head title="Gestion des Cours" />
  
  <AuthenticatedLayout>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-slate-900 to-gray-900 text-white">
      <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header avec actions -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
          <div class="mb-4 lg:mb-0">
            <div class="flex items-center space-x-3 mb-2">
              <div class="w-12 h-12 bg-gradient-to-br from-purple-600 to-indigo-600 rounded-xl flex items-center justify-center">
                <AcademicCapIcon class="h-7 w-7 text-white" />
              </div>
              <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent">
                  Gestion des Cours
                </h1>
                <p class="text-gray-400 font-medium">Planning et organisation des cours de karaté</p>
              </div>
            </div>
          </div>
          
          <div class="flex items-center space-x-3">
            <Link 
              :href="route('cours.create')"
              class="bg-purple-600 hover:bg-purple-700 px-6 py-3 rounded-lg transition-all duration-200 flex items-center space-x-2"
            >
              <PlusIcon class="h-5 w-5" />
              <span>Nouveau Cours</span>
            </Link>
          </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <ModernStatsCard
            title="Cours Actifs"
            :value="stats.cours_actifs"
            icon-type="heroicon"
            icon-name="academic"
            format="number"
            description="Cours en cours d'activité"
          />
          
          <ModernStatsCard
            title="Total Inscrits"
            :value="stats.total_inscrits"
            icon-type="heroicon"
            icon-name="users"
            format="number"
            description="Élèves inscrits aux cours"
          />
          
          <ModernStatsCard
            title="Taux d'Occupation"
            :value="stats.taux_occupation"
            icon-type="heroicon"
            icon-name="chart"
            format="percentage"
            description="Occupation moyenne des cours"
          />
          
          <ModernStatsCard
            title="Revenus Cours"
            :value="stats.revenus_cours"
            icon-type="heroicon"
            icon-name="currency"
            format="currency"
            description="Revenus générés par les cours"
          />
        </div>

        <!-- Filtres -->
        <div class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-xl p-6 mb-8">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Jour de la semaine</label>
              <select 
                v-model="filters.jour" 
                class="w-full bg-gray-700/50 border border-gray-600 rounded-lg px-3 py-2 text-white"
              >
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
            
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Instructeur</label>
              <select 
                v-model="filters.instructeur" 
                class="w-full bg-gray-700/50 border border-gray-600 rounded-lg px-3 py-2 text-white"
              >
                <option value="">Tous les instructeurs</option>
                <option v-for="instructeur in instructeurs" :key="instructeur.id" :value="instructeur.id">
                  {{ instructeur.name }}
                </option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Statut</label>
              <select 
                v-model="filters.statut" 
                class="w-full bg-gray-700/50 border border-gray-600 rounded-lg px-3 py-2 text-white"
              >
                <option value="">Tous</option>
                <option value="actif">Actif</option>
                <option value="inactif">Inactif</option>
                <option value="complet">Complet</option>
              </select>
            </div>
            
            <div class="flex items-end">
              <button 
                @click="resetFilters"
                class="w-full bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded-lg transition-all duration-200"
              >
                Réinitialiser
              </button>
            </div>
          </div>
        </div>

        <!-- Liste des cours -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
          <div 
            v-for="cours in filteredCours" 
            :key="cours.id"
            class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-xl p-6 hover:border-purple-500/30 transition-all duration-300 group"
          >
            <!-- Header du cours -->
            <div class="flex items-start justify-between mb-4">
              <div>
                <h3 class="text-lg font-semibold text-white mb-1">{{ cours.nom }}</h3>
                <p class="text-sm text-gray-400">{{ cours.description }}</p>
              </div>
              
              <div class="flex items-center space-x-2">
                <span 
                  :class="{
                    'bg-green-500/20 text-green-300': cours.actif && cours.places_restantes > 0,
                    'bg-red-500/20 text-red-300': !cours.actif,
                    'bg-yellow-500/20 text-yellow-300': cours.actif && cours.places_restantes === 0
                  }"
                  class="px-2 py-1 rounded text-xs font-medium"
                >
                  {{ getStatutCours(cours) }}
                </span>
              </div>
            </div>

            <!-- Détails du cours -->
            <div class="space-y-3 mb-4">
              <div class="flex items-center text-sm text-gray-300">
                <CalendarDaysIcon class="h-4 w-4 mr-2 text-purple-400" />
                <span>{{ cours.horaire_complet }}</span>
              </div>
              
              <div class="flex items-center text-sm text-gray-300">
                <UserIcon class="h-4 w-4 mr-2 text-blue-400" />
                <span>{{ cours.instructeur?.name || 'Non assigné' }}</span>
              </div>
              
              <div class="flex items-center text-sm text-gray-300">
                <UsersIcon class="h-4 w-4 mr-2 text-green-400" />
                <span>{{ cours.membres_count }}/{{ cours.places_max }} places</span>
              </div>
              
              <div class="flex items-center text-sm text-gray-300">
                <CurrencyDollarIcon class="h-4 w-4 mr-2 text-yellow-400" />
                <span>{{ formatCurrency(cours.prix_mensuel) }}/mois</span>
              </div>
            </div>

            <!-- Progress bar occupation -->
            <div class="mb-4">
              <div class="flex justify-between text-xs text-gray-400 mb-1">
                <span>Occupation</span>
                <span>{{ Math.round((cours.membres_count / cours.places_max) * 100) }}%</span>
              </div>
              <ModernProgressBar
                :percentage="(cours.membres_count / cours.places_max) * 100"
                :color="getProgressColor(cours)"
                size="sm"
                :glow-effect="true"
                animated
              />
            </div>

            <!-- Actions -->
            <div class="flex flex-wrap gap-2">
              <Link 
                :href="route('cours.show', cours.id)"
                class="flex-1 bg-purple-600/20 hover:bg-purple-600/30 text-purple-300 px-3 py-2 rounded-lg text-sm transition-all duration-200 text-center"
              >
                Détails
              </Link>
              
              <Link 
                :href="route('cours.edit', cours.id)"
                class="flex-1 bg-blue-600/20 hover:bg-blue-600/30 text-blue-300 px-3 py-2 rounded-lg text-sm transition-all duration-200 text-center"
              >
                Modifier
              </Link>
              
              <button 
                @click="toggleStatut(cours)"
                :class="{
                  'bg-green-600/20 hover:bg-green-600/30 text-green-300': !cours.actif,
                  'bg-red-600/20 hover:bg-red-600/30 text-red-300': cours.actif
                }"
                class="flex-1 px-3 py-2 rounded-lg text-sm transition-all duration-200"
              >
                {{ cours.actif ? 'Désactiver' : 'Activer' }}
              </button>
            </div>
          </div>
        </div>

        <!-- Message si aucun cours -->
        <div v-if="filteredCours.length === 0" class="text-center py-12">
          <AcademicCapIcon class="h-16 w-16 text-gray-500 mx-auto mb-4" />
          <h3 class="text-lg font-medium text-gray-400 mb-2">Aucun cours trouvé</h3>
          <p class="text-gray-500 mb-4">Commencez par créer votre premier cours.</p>
          <Link 
            :href="route('cours.create')"
            class="bg-purple-600 hover:bg-purple-700 px-6 py-3 rounded-lg transition-all duration-200 inline-flex items-center space-x-2"
          >
            <PlusIcon class="h-5 w-5" />
            <span>Créer un cours</span>
          </Link>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { computed, ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import ModernStatsCard from '@/Components/ModernStatsCard.vue'
import ModernProgressBar from '@/Components/ModernProgressBar.vue'

import {
  AcademicCapIcon,
  PlusIcon,
  CalendarDaysIcon,
  UserIcon,
  UsersIcon,
  CurrencyDollarIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
  cours: {
    type: Array,
    required: true
  },
  instructeurs: {
    type: Array,
    default: () => []
  },
  stats: {
    type: Object,
    default: () => ({
      cours_actifs: 0,
      total_inscrits: 0,
      taux_occupation: 0,
      revenus_cours: 0
    })
  }
})

// Filtres réactifs
const filters = ref({
  jour: '',
  instructeur: '',
  statut: ''
})

// Cours filtrés
const filteredCours = computed(() => {
  return props.cours.filter(cours => {
    if (filters.value.jour && cours.jour_semaine !== filters.value.jour) return false
    if (filters.value.instructeur && cours.instructeur_id !== parseInt(filters.value.instructeur)) return false
    if (filters.value.statut) {
      const statut = getStatutCours(cours)
      if (filters.value.statut === 'actif' && statut !== 'Actif') return false
      if (filters.value.statut === 'inactif' && statut !== 'Inactif') return false
      if (filters.value.statut === 'complet' && statut !== 'Complet') return false
    }
    return true
  })
})

// Méthodes utilitaires
const getStatutCours = (cours) => {
  if (!cours.actif) return 'Inactif'
  if (cours.places_restantes === 0) return 'Complet'
  return 'Actif'
}

const getProgressColor = (cours) => {
  const occupation = (cours.membres_count / cours.places_max) * 100
  if (occupation >= 90) return 'red'
  if (occupation >= 70) return 'yellow'
  return 'green'
}

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('fr-CA', {
    style: 'currency',
    currency: 'CAD'
  }).format(amount)
}

const resetFilters = () => {
  filters.value = {
    jour: '',
    instructeur: '',
    statut: ''
  }
}

const toggleStatut = (cours) => {
  // Implementation à ajouter - appel API pour changer le statut
  console.log('Toggle statut cours:', cours.id)
}
</script>
