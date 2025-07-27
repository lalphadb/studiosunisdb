<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-slate-900 to-gray-900 text-white">
    <!-- Background pattern -->
    <div class="absolute inset-0 opacity-50 bg-pattern"></div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header Section -->
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
        <div class="mb-4 lg:mb-0">
          <div class="flex items-center space-x-3 mb-2">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center">
              <AcademicCapIcon class="h-7 w-7 text-white" />
            </div>
            <div>
              <h1 class="text-4xl font-bold bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent">
                StudiosDB v5 Pro
              </h1>
              <p class="text-gray-400 font-medium">École de Karaté - Dashboard Professionnel</p>
            </div>
          </div>
          <div class="flex items-center space-x-2">
            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
            <span class="text-green-400 text-sm font-medium">Système opérationnel</span>
            <span class="text-gray-500 text-xs">• Dernière mise à jour: {{ lastUpdate }}</span>
          </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="flex items-center space-x-3">
          <button 
            @click="refreshData" 
            :disabled="loading"
            class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 px-4 py-2 rounded-lg hover:bg-gray-700/50 transition-all duration-200 flex items-center space-x-2 disabled:opacity-50"
          >
            <ArrowPathIcon class="h-4 w-4" :class="{ 'animate-spin': loading }" />
            <span class="text-sm">Actualiser</span>
          </button>
          
          <button class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg transition-all duration-200 flex items-center space-x-2">
            <PlusIcon class="h-4 w-4" />
            <span class="text-sm">Action Rapide</span>
          </button>
        </div>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <ModernStatsCard
          title="Membres Total"
          :value="stats.total_membres"
          icon-type="heroicon"
          icon-name="users"
          :change="stats.evolution_membres"
          :goal="stats.objectif_membres"
          format="number"
          description="Membres inscrits dans l'école"
        />
        
        <ModernStatsCard
          title="Membres Actifs"
          :value="stats.membres_actifs"
          icon-type="heroicon"
          icon-name="check"
          :change="tauxActivite"
          format="number"
          description="Membres avec activité récente"
        />
        
        <ModernStatsCard
          title="Présences Aujourd'hui"
          :value="stats.presences_aujourd_hui"
          icon-type="heroicon"
          icon-name="calendar"
          format="number"
          description="Présences confirmées aujourd'hui"
        />
        
        <ModernStatsCard
          title="Revenus du Mois"
          :value="stats.revenus_mois"
          icon-type="heroicon"
          icon-name="currency"
          :change="stats.evolution_revenus"
          :goal="stats.objectif_revenus"
          format="currency"
          description="Revenus générés ce mois"
        />
      </div>

      <!-- Action Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <ModernActionCard
          title="Gestion Membres"
          description="Voir, créer et gérer tous les membres"
          @click="navigateToMembres"
          icon-name="user-group"
          color="blue"
          :badge="`${stats.total_membres} membres`"
          badge-type="info"
        />
        
        <ModernActionCard
          title="Gestion Cours"
          description="Créer, planifier et organiser les cours"
          @click="navigateToCours"
          icon-name="academic-cap"
          color="purple"
          :badge="`${stats.cours_actifs || 0} cours actifs`"
          badge-type="info"
        />
        
        <ModernActionCard
          title="Gestion Présences"
          description="Mode tablette pour enregistrer les présences"
          @click="navigateToPresences"
          icon-name="clipboard-document-check"
          color="green"
          badge="Mode Tablette"
          badge-type="success"
        />
        
        <ModernActionCard
          title="Gestion Paiements"
          description="Suivi des paiements et facturations"
          @click="navigateToPaiements"
          icon-name="credit-card"
          color="orange"
          :badge="stats.paiements_en_retard > 0 ? `${stats.paiements_en_retard} en retard` : 'À jour'"
          :badge-type="stats.paiements_en_retard > 0 ? 'warning' : 'success'"
        />
      </div>

      <!-- Goals and Progress Section -->
      <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <!-- Objectif Membres -->
        <div class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-xl p-6 hover:border-blue-500/30 transition-all duration-300">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white flex items-center">
              <UserGroupIcon class="h-5 w-5 text-blue-400 mr-2" />
              Objectif Membres
            </h3>
          </div>
          <ModernProgressBar
            :percentage="progressMembres"
            color="karate"
            size="lg"
            :current="stats.total_membres"
            :total="stats.objectif_membres"
            format="fraction"
            :glow-effect="true"
            :show-stats="true"
            animated
          />
        </div>

        <!-- Taux de Présence -->
        <div class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-xl p-6 hover:border-green-500/30 transition-all duration-300">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white flex items-center">
              <CheckCircleIcon class="h-5 w-5 text-green-400 mr-2" />
              Taux Présence
            </h3>
            <span class="text-sm font-medium px-2 py-1 bg-green-500/20 text-green-300 rounded">
              {{ stats.taux_presence }}% {{ tauxPresenceLabel }}
            </span>
          </div>
          <ModernProgressBar
            :percentage="stats.taux_presence"
            color="green"
            size="lg"
            format="percentage"
            :glow-effect="true"
            animated
          />
        </div>

        <!-- Objectif Revenus -->
        <div class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-xl p-6 hover:border-yellow-500/30 transition-all duration-300">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white flex items-center">
              <CurrencyDollarIcon class="h-5 w-5 text-yellow-400 mr-2" />
              Objectif Revenus
            </h3>
          </div>
          <ModernProgressBar
            :percentage="progressRevenus"
            color="yellow"
            size="lg"
            :current="stats.revenus_mois"
            :total="stats.objectif_revenus"
            format="fraction"
            :glow-effect="true"
            :show-stats="true"
            animated
          />
        </div>

        <!-- Satisfaction -->
        <div class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-xl p-6 hover:border-purple-500/30 transition-all duration-300">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white flex items-center">
              <StarIcon class="h-5 w-5 text-purple-400 mr-2" />
              Satisfaction
            </h3>
            <span class="text-sm font-medium px-2 py-1 bg-purple-500/20 text-purple-300 rounded">
              {{ stats.satisfaction_moyenne }}% {{ satisfactionLabel }}
            </span>
          </div>
          <ModernProgressBar
            :percentage="stats.satisfaction_moyenne"
            color="purple"
            size="lg"
            format="percentage"
            :glow-effect="true"
            animated
          />
        </div>
      </div>

      <!-- Recent Activity & Quick Stats -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Recent Activity -->
        <div class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-xl p-6">
          <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
            <ClockIcon class="h-5 w-5 text-blue-400 mr-2" />
            Activité Récente
          </h3>
          <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-gray-700/30 rounded-lg">
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                <span class="text-sm text-gray-300">Nouveau membre inscrit</span>
              </div>
              <span class="text-xs text-gray-500">Il y a 2h</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-gray-700/30 rounded-lg">
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-blue-400 rounded-full"></div>
                <span class="text-sm text-gray-300">Cours de kata complété</span>
              </div>
              <span class="text-xs text-gray-500">Il y a 4h</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-gray-700/30 rounded-lg">
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-yellow-400 rounded-full"></div>
                <span class="text-sm text-gray-300">Paiement reçu</span>
              </div>
              <span class="text-xs text-gray-500">Hier</span>
            </div>
          </div>
        </div>

        <!-- Quick Stats -->
        <div class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-xl p-6">
          <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
            <ChartBarIcon class="h-5 w-5 text-purple-400 mr-2" />
            Statistiques Rapides
          </h3>
          <div class="grid grid-cols-2 gap-4">
            <div class="text-center p-3 bg-gray-700/30 rounded-lg">
              <div class="text-2xl font-bold text-blue-400">{{ stats.cours_aujourd_hui || 0 }}</div>
              <div class="text-xs text-gray-400">Cours aujourd'hui</div>
            </div>
            <div class="text-center p-3 bg-gray-700/30 rounded-lg">
              <div class="text-2xl font-bold text-green-400">{{ stats.examens_ce_mois || 0 }}</div>
              <div class="text-xs text-gray-400">Examens ce mois</div>
            </div>
            <div class="text-center p-3 bg-gray-700/30 rounded-lg">
              <div class="text-2xl font-bold text-yellow-400">{{ moyenneAge }}</div>
              <div class="text-xs text-gray-400">Âge moyen</div>
            </div>
            <div class="text-center p-3 bg-gray-700/30 rounded-lg">
              <div class="text-2xl font-bold text-purple-400">{{ stats.retention_rate || 95 }}%</div>
              <div class="text-xs text-gray-400">Taux rétention</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="text-center py-6 border-t border-gray-700/50">
        <div class="flex flex-col sm:flex-row items-center justify-center space-y-2 sm:space-y-0 sm:space-x-4 text-sm text-gray-400">
          <div class="flex items-center space-x-2">
            <CalendarDaysIcon class="h-4 w-4" />
            <span>{{ formattedDate }}</span>
          </div>
          <div class="flex items-center space-x-2">
            <ClockIcon class="h-4 w-4" />
            <span>{{ formattedTime }}</span>
          </div>
          <div class="flex items-center space-x-2">
            <ServerIcon class="h-4 w-4" />
            <span>StudiosDB v5.2.0</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, onMounted } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import ModernStatsCard from '@/Components/ModernStatsCard.vue'
import ModernProgressBar from '@/Components/ModernProgressBar.vue'
import ModernActionCard from '@/Components/ModernActionCard.vue'

import {
  AcademicCapIcon,
  ArrowPathIcon,
  PlusIcon,
  UserGroupIcon,
  CheckCircleIcon,
  CurrencyDollarIcon,
  StarIcon,
  ClockIcon,
  ChartBarIcon,
  CalendarDaysIcon,
  ServerIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
  stats: {
    type: Object,
    required: true
  },
  user: {
    type: Object,
    required: true
  }
})

const loading = ref(false)

// Computed properties
const tauxActivite = computed(() => {
  if (!props.stats.total_membres || props.stats.total_membres === 0) return 0
  return Math.round((props.stats.membres_actifs / props.stats.total_membres) * 100)
})

const progressMembres = computed(() => {
  if (!props.stats.objectif_membres) return 0
  return Math.min((props.stats.total_membres / props.stats.objectif_membres) * 100, 100)
})

const progressRevenus = computed(() => {
  if (!props.stats.objectif_revenus) return 0
  return Math.min((props.stats.revenus_mois / props.stats.objectif_revenus) * 100, 100)
})

const tauxPresenceLabel = computed(() => {
  const taux = props.stats.taux_presence
  if (taux >= 90) return 'Excellent'
  if (taux >= 75) return 'Très bien'
  if (taux >= 60) return 'Bien'
  return 'À améliorer'
})

const satisfactionLabel = computed(() => {
  const satisfaction = props.stats.satisfaction_moyenne
  if (satisfaction >= 95) return 'Exceptionnel'
  if (satisfaction >= 90) return 'Excellent'
  if (satisfaction >= 80) return 'Très bien'
  if (satisfaction >= 70) return 'Bien'
  return 'À améliorer'
})

const moyenneAge = computed(() => {
  return props.stats.moyenne_age || '25 ans'
})

const formattedDate = computed(() => {
  return new Date().toLocaleDateString('fr-CA', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
})

const formattedTime = computed(() => {
  return new Date().toLocaleTimeString('fr-CA', {
    hour: '2-digit',
    minute: '2-digit'
  })
})

const lastUpdate = computed(() => {
  return new Date().toLocaleTimeString('fr-CA', {
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit'
  })
})

// Methods
const refreshData = async () => {
  loading.value = true
  try {
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1000))
    // In real app: await router.reload({ only: ['stats'] })
  } catch (error) {
    console.error('Erreur lors du rafraîchissement:', error)
  } finally {
    loading.value = false
  }
}

// Navigation methods
const navigateToMembres = () => {
  router.visit('/membres')
}

const navigateToCours = () => {
  router.visit('/cours')
}

const navigateToPresences = () => {
  router.visit('/presences')
}

const navigateToPaiements = () => {
  router.visit('/paiements')
}

onMounted(() => {
  // Auto-refresh every 5 minutes
  setInterval(() => {
    if (!loading.value) {
      refreshData()
    }
  }, 300000)
})
</script>

<style scoped>
/* Custom animations */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.fade-in-up {
  animation: fadeInUp 0.6s ease-out;
}

/* Background pattern */
.bg-pattern {
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.02'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

/* Glassmorphism effect */
.backdrop-blur-xl {
  backdrop-filter: blur(12px);
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: #374151;
}

::-webkit-scrollbar-thumb {
  background: #6B7280;
  border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
  background: #9CA3AF;
}
</style>
