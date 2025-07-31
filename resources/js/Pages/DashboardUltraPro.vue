<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-slate-900 to-gray-900 text-white">
    <!-- Background pattern -->
    <div class="absolute inset-0 opacity-50" style="background-image: url('data:image/svg+xml,%3Csvg width=60 height=60 viewBox=0 0 60 60 xmlns=http://www.w3.org/2000/svg%3E%3Cg fill=none fill-rule=evenodd%3E%3Cg fill=%23ffffff fill-opacity=0.02%3E%3Ccircle cx=30 cy=30 r=2/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <!-- Header Section -->
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
        <div class="mb-4 lg:mb-0">
          <div class="flex items-center space-x-3 mb-2">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
              🥋
            </div>
            <div>
              <h1 class="text-2xl font-bold bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent">
                StudiosDB v5 Pro
              </h1>
              <p class="text-gray-400 text-sm">École de Karaté - Dashboard Ultra</p>
            </div>
          </div>
        </div>

        <!-- User Menu & Actions -->
        <div class="flex items-center space-x-3">
          <span class="text-sm text-gray-300">Bonjour, {{ user?.name || 'Admin' }}</span>
          
          <button
            @click="refreshData"
            :disabled="loading"
            class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 px-3 py-2 rounded-lg hover:bg-gray-700/50 transition-all duration-200 flex items-center space-x-2 disabled:opacity-50"
          >
            <span class="text-lg" :class="{ 'animate-spin': loading }">↻</span>
            <span class="text-sm">Actualiser</span>
          </button>

          <Link 
            :href="route('logout')" 
            method="post" 
            as="button"
            class="bg-red-600 hover:bg-red-700 px-3 py-2 rounded-lg transition-all duration-200 flex items-center space-x-2 text-sm"
          >
            <span>🚪</span>
            <span>Déconnexion</span>
          </Link>
        </div>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-lg p-4 hover:border-blue-500/30 transition-all">
          <div class="text-center">
            <div class="text-2xl font-bold text-blue-400">{{ stats.total_membres }}</div>
            <div class="text-xs text-gray-400">Total Membres</div>
            <div class="text-xs text-green-400 mt-1">+{{ stats.evolution_membres }}%</div>
          </div>
        </div>
        
        <div class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-lg p-4 hover:border-green-500/30 transition-all">
          <div class="text-center">
            <div class="text-2xl font-bold text-green-400">{{ stats.membres_actifs }}</div>
            <div class="text-xs text-gray-400">Membres Actifs</div>
            <div class="text-xs text-blue-400 mt-1">{{ Math.round((stats.membres_actifs / stats.total_membres) * 100) }}% activité</div>
          </div>
        </div>
        
        <div class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-lg p-4 hover:border-purple-500/30 transition-all">
          <div class="text-center">
            <div class="text-2xl font-bold text-purple-400">{{ stats.cours_actifs || 0 }}</div>
            <div class="text-xs text-gray-400">Cours Actifs</div>
            <div class="text-xs text-purple-400 mt-1">{{ stats.cours_aujourd_hui || 0 }} aujourd'hui</div>
          </div>
        </div>
        
        <div class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-lg p-4 hover:border-yellow-500/30 transition-all">
          <div class="text-center">
            <div class="text-2xl font-bold text-yellow-400">${{ stats.revenus_mois }}</div>
            <div class="text-xs text-gray-400">Revenus Mois</div>
            <div class="text-xs text-green-400 mt-1">+{{ stats.evolution_revenus }}%</div>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <button
          @click="navigateToMembres"
          class="bg-blue-600/20 hover:bg-blue-600/30 border border-blue-500/30 hover:border-blue-400 backdrop-blur-sm rounded-lg p-4 transition-all duration-200 group"
        >
          <div class="text-center">
            <div class="text-3xl mb-2">👥</div>
            <div class="text-sm font-medium text-blue-300 group-hover:text-blue-200">Gestion Membres</div>
            <div class="text-xs text-gray-400">{{ stats.total_membres }} inscrits</div>
          </div>
        </button>

        <button
          @click="navigateToCours"
          class="bg-purple-600/20 hover:bg-purple-600/30 border border-purple-500/30 hover:border-purple-400 backdrop-blur-sm rounded-lg p-4 transition-all duration-200 group"
        >
          <div class="text-center">
            <div class="text-3xl mb-2">📚</div>
            <div class="text-sm font-medium text-purple-300 group-hover:text-purple-200">Cours & Planning</div>
            <div class="text-xs text-gray-400">{{ stats.cours_actifs || 0 }} actifs</div>
          </div>
        </button>

        <button
          @click="navigateToPresences"
          class="bg-green-600/20 hover:bg-green-600/30 border border-green-500/30 hover:border-green-400 backdrop-blur-sm rounded-lg p-4 transition-all duration-200 group"
        >
          <div class="text-center">
            <div class="text-3xl mb-2">✅</div>
            <div class="text-sm font-medium text-green-300 group-hover:text-green-200">Présences</div>
            <div class="text-xs text-gray-400">{{ stats.taux_presence }}% présence</div>
          </div>
        </button>

        <button
          @click="navigateToPaiements"
          class="bg-orange-600/20 hover:bg-orange-600/30 border border-orange-500/30 hover:border-orange-400 backdrop-blur-sm rounded-lg p-4 transition-all duration-200 group"
        >
          <div class="text-center">
            <div class="text-3xl mb-2">💳</div>
            <div class="text-sm font-medium text-orange-300 group-hover:text-orange-200">Paiements</div>
            <div class="text-xs text-gray-400">{{ stats.paiements_en_retard || 0 }} en retard</div>
          </div>
        </button>
      </div>

      <!-- Progress Bars Section -->
      <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <!-- Objectif Membres -->
        <div class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-xl p-6 hover:border-blue-500/30 transition-all duration-300">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white flex items-center">
              <span class="text-blue-400 mr-2">👥</span>
              Objectif Membres
            </h3>
          </div>
          <!-- Simple Progress Bar -->
          <div class="mb-2">
            <div class="flex justify-between text-sm text-gray-300">
              <span>{{ stats.total_membres }}</span>
              <span>{{ stats.objectif_membres }}</span>
            </div>
            <div class="w-full bg-gray-700 rounded-full h-3 mt-2">
              <div 
                class="bg-gradient-to-r from-blue-500 to-blue-600 h-3 rounded-full transition-all duration-1000"
                :style="`width: ${Math.min((stats.total_membres / stats.objectif_membres) * 100, 100)}%`"
              ></div>
            </div>
            <div class="text-xs text-gray-400 mt-1 text-center">
              {{ Math.round((stats.total_membres / stats.objectif_membres) * 100) }}% de l'objectif
            </div>
          </div>
        </div>

        <!-- Taux de Présence -->
        <div class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-xl p-6 hover:border-green-500/30 transition-all duration-300">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white flex items-center">
              <span class="text-green-400 mr-2">✅</span>
              Taux Présence
            </h3>
            <span class="text-sm font-medium px-2 py-1 bg-green-500/20 text-green-300 rounded">
              {{ stats.taux_presence }}% {{ getTauxPresenceLabel(stats.taux_presence) }}
            </span>
          </div>
          <div class="w-full bg-gray-700 rounded-full h-3">
            <div 
              class="bg-gradient-to-r from-green-500 to-green-600 h-3 rounded-full transition-all duration-1000"
              :style="`width: ${stats.taux_presence}%`"
            ></div>
          </div>
        </div>

        <!-- Objectif Revenus -->
        <div class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-xl p-6 hover:border-yellow-500/30 transition-all duration-300">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white flex items-center">
              <span class="text-yellow-400 mr-2">💰</span>
              Objectif Revenus
            </h3>
          </div>
          <div class="mb-2">
            <div class="flex justify-between text-sm text-gray-300">
              <span>${{ stats.revenus_mois }}</span>
              <span>${{ stats.objectif_revenus }}</span>
            </div>
            <div class="w-full bg-gray-700 rounded-full h-3 mt-2">
              <div 
                class="bg-gradient-to-r from-yellow-500 to-yellow-600 h-3 rounded-full transition-all duration-1000"
                :style="`width: ${Math.min((stats.revenus_mois / stats.objectif_revenus) * 100, 100)}%`"
              ></div>
            </div>
            <div class="text-xs text-gray-400 mt-1 text-center">
              {{ Math.round((stats.revenus_mois / stats.objectif_revenus) * 100) }}% de l'objectif
            </div>
          </div>
        </div>

        <!-- Satisfaction -->
        <div class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-xl p-6 hover:border-purple-500/30 transition-all duration-300">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white flex items-center">
              <span class="text-purple-400 mr-2">⭐</span>
              Satisfaction
            </h3>
            <span class="text-sm font-medium px-2 py-1 bg-purple-500/20 text-purple-300 rounded">
              {{ stats.satisfaction_moyenne }}% {{ getSatisfactionLabel(stats.satisfaction_moyenne) }}
            </span>
          </div>
          <div class="w-full bg-gray-700 rounded-full h-3">
            <div 
              class="bg-gradient-to-r from-purple-500 to-purple-600 h-3 rounded-full transition-all duration-1000"
              :style="`width: ${stats.satisfaction_moyenne}%`"
            ></div>
          </div>
        </div>
      </div>

      <!-- Recent Activity & Quick Stats -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Recent Activity -->
        <div class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-xl p-6">
          <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
            <span class="text-blue-400 mr-2">🕒</span>
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
            <span class="text-purple-400 mr-2">📊</span>
            Statistiques Rapides
          </h3>
          <div class="grid grid-cols-2 gap-4">
            <div class="text-center p-3 bg-gray-700/30 rounded-lg">
              <div class="text-2xl font-bold text-blue-400">{{ stats.cours_aujourd_hui || 5 }}</div>
              <div class="text-xs text-gray-400">Cours aujourd'hui</div>
            </div>
            <div class="text-center p-3 bg-gray-700/30 rounded-lg">
              <div class="text-2xl font-bold text-green-400">{{ stats.examens_ce_mois || 8 }}</div>
              <div class="text-xs text-gray-400">Examens ce mois</div>
            </div>
            <div class="text-center p-3 bg-gray-700/30 rounded-lg">
              <div class="text-2xl font-bold text-yellow-400">{{ stats.moyenne_age || '24 ans' }}</div>
              <div class="text-xs text-gray-400">Âge moyen</div>
            </div>
            <div class="text-center p-3 bg-gray-700/30 rounded-lg">
              <div class="text-2xl font-bold text-purple-400">{{ stats.retention_rate || 96 }}%</div>
              <div class="text-xs text-gray-400">Taux rétention</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="text-center py-6 border-t border-gray-700/50">
        <div class="flex flex-col sm:flex-row items-center justify-center space-y-2 sm:space-y-0 sm:space-x-4 text-sm text-gray-400">
          <div class="flex items-center space-x-2">
            <span>📅</span>
            <span>{{ formatDate }}</span>
          </div>
          <div class="flex items-center space-x-2">
            <span>🕒</span>
            <span>{{ formatTime }}</span>
          </div>
          <div class="flex items-center space-x-2">
            <span>🖥️</span>
            <span>StudiosDB v5.3.0 Ultra Pro</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, onMounted } from 'vue'
import { router, Link } from '@inertiajs/vue3'

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
const formatDate = computed(() => {
  return new Date().toLocaleDateString('fr-CA', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
})

const formatTime = computed(() => {
  return new Date().toLocaleTimeString('fr-CA', {
    hour: '2-digit',
    minute: '2-digit'
  })
})

// Helper methods
const getTauxPresenceLabel = (taux) => {
  if (taux >= 90) return 'Excellent'
  if (taux >= 75) return 'Très bien'
  if (taux >= 60) return 'Bien'
  return 'À améliorer'
}

const getSatisfactionLabel = (satisfaction) => {
  if (satisfaction >= 95) return 'Exceptionnel'
  if (satisfaction >= 90) return 'Excellent'
  if (satisfaction >= 80) return 'Très bien'
  if (satisfaction >= 70) return 'Bien'
  return 'À améliorer'
}

// Methods
const refreshData = async () => {
  loading.value = true
  try {
    await new Promise(resolve => setTimeout(resolve, 1000))
    // In real app: await router.reload({ only: ['stats'] })
    location.reload()
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

/* Glassmorphism effect */
.backdrop-blur-xl {
  backdrop-filter: blur(12px);
}

/* Progress bar animations */
.bg-gradient-to-r {
  transition: width 2s ease-in-out;
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
