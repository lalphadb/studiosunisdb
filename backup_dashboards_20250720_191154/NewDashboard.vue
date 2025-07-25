<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-gray-900">
    
    <!-- Header professionnel -->
    <header class="bg-gray-800/90 backdrop-blur-sm border-b border-gray-700/50 sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <!-- Logo et titre -->
          <div class="flex items-center space-x-4">
            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
              <span class="text-white font-bold text-lg">S</span>
            </div>
            <div>
              <h1 class="text-xl font-bold text-white">StudiosDB Pro</h1>
              <p class="text-xs text-gray-400">Gestion de dojo v5.0</p>
            </div>
          </div>
          
          <!-- Actions utilisateur -->
          <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-3 bg-gray-700/50 rounded-lg px-3 py-2">
              <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center">
                <span class="text-white font-semibold text-sm">{{ userInitial }}</span>
              </div>
              <div class="hidden md:block">
                <p class="text-white text-sm font-medium">{{ $page.props.auth.user.name }}</p>
                <p class="text-gray-400 text-xs">Administrateur</p>
              </div>
            </div>
            
            <Link :href="route('logout')" method="post" class="p-2 text-gray-400 hover:text-red-400 transition-colors rounded-lg hover:bg-gray-700">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
              </svg>
            </Link>
          </div>
        </div>
      </div>
    </header>

    <!-- Navigation moderne -->
    <nav class="bg-gray-800/70 backdrop-blur-sm border-b border-gray-700/30">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex space-x-1">
          <Link :href="route('dashboard')" 
                class="flex items-center space-x-2 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 bg-blue-600 text-white shadow-lg">
            <span class="text-lg">🏠</span>
            <span>Dashboard</span>
          </Link>
          <button class="flex items-center space-x-2 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 text-gray-300 hover:text-white hover:bg-gray-700/50">
            <span class="text-lg">👥</span>
            <span>Membres</span>
          </button>
          <button class="flex items-center space-x-2 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 text-gray-300 hover:text-white hover:bg-gray-700/50">
            <span class="text-lg">✅</span>
            <span>Présences</span>
          </button>
          <button class="flex items-center space-x-2 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 text-gray-300 hover:text-white hover:bg-gray-700/50">
            <span class="text-lg">📚</span>
            <span>Cours</span>
          </button>
          <button class="flex items-center space-x-2 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 text-gray-300 hover:text-white hover:bg-gray-700/50">
            <span class="text-lg">💰</span>
            <span>Finances</span>
          </button>
        </div>
      </div>
    </nav>

    <!-- Contenu principal -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      
      <!-- Message de bienvenue -->
      <div class="mb-8">
        <h2 class="text-3xl font-bold text-white mb-2">
          Bonjour {{ $page.props.auth.user.name }} 👋
        </h2>
        <p class="text-gray-400">Voici un aperçu de votre dojo aujourd'hui</p>
      </div>

      <!-- KPIs Principaux -->
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        
        <!-- Membres -->
        <div class="group relative overflow-hidden rounded-xl p-6 cursor-pointer hover:scale-105 transition-all duration-300 hover:shadow-xl bg-gradient-to-br from-blue-500 to-blue-600 border border-white/10 shadow-lg">
          <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-blue-600"></div>
          <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
          
          <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
              <div class="text-3xl">👥</div>
              <div class="flex items-center space-x-1 text-sm">
                <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"></path>
                </svg>
                <span class="text-green-300">12%</span>
              </div>
            </div>
            
            <div>
              <p class="text-3xl font-bold text-white mb-2">{{ stats.total_membres }}</p>
              <p class="text-white/80 text-sm font-medium">Membres Total</p>
            </div>
          </div>
          
          <div class="absolute -top-2 -right-2 w-16 h-16 bg-white/10 rounded-full"></div>
          <div class="absolute -bottom-4 -left-4 w-12 h-12 bg-white/5 rounded-full"></div>
        </div>

        <!-- Cours -->
        <div class="group relative overflow-hidden rounded-xl p-6 cursor-pointer hover:scale-105 transition-all duration-300 hover:shadow-xl bg-gradient-to-br from-emerald-500 to-emerald-600 border border-white/10 shadow-lg">
          <div class="absolute inset-0 bg-gradient-to-br from-emerald-500 to-emerald-600"></div>
          <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
          
          <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
              <div class="text-3xl">📚</div>
              <div class="flex items-center space-x-1 text-sm">
                <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"></path>
                </svg>
                <span class="text-green-300">3%</span>
              </div>
            </div>
            
            <div>
              <p class="text-3xl font-bold text-white mb-2">{{ stats.total_cours }}</p>
              <p class="text-white/80 text-sm font-medium">Cours Actifs</p>
            </div>
          </div>
          
          <div class="absolute -top-2 -right-2 w-16 h-16 bg-white/10 rounded-full"></div>
          <div class="absolute -bottom-4 -left-4 w-12 h-12 bg-white/5 rounded-full"></div>
        </div>

        <!-- Présences -->
        <div class="group relative overflow-hidden rounded-xl p-6 cursor-pointer hover:scale-105 transition-all duration-300 hover:shadow-xl bg-gradient-to-br from-yellow-500 to-orange-500 border border-white/10 shadow-lg">
          <div class="absolute inset-0 bg-gradient-to-br from-yellow-500 to-orange-500"></div>
          <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
          
          <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
              <div class="text-3xl">✅</div>
              <div class="flex items-center space-x-1 text-sm">
                <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"></path>
                </svg>
                <span class="text-green-300">8%</span>
              </div>
            </div>
            
            <div>
              <p class="text-3xl font-bold text-white mb-2">{{ stats.presences_aujourd_hui }}</p>
              <p class="text-white/80 text-sm font-medium">Présences Aujourd'hui</p>
            </div>
          </div>
          
          <div class="absolute -top-2 -right-2 w-16 h-16 bg-white/10 rounded-full"></div>
          <div class="absolute -bottom-4 -left-4 w-12 h-12 bg-white/5 rounded-full"></div>
        </div>

        <!-- Revenus -->
        <div class="group relative overflow-hidden rounded-xl p-6 cursor-pointer hover:scale-105 transition-all duration-300 hover:shadow-xl bg-gradient-to-br from-purple-500 to-pink-500 border border-white/10 shadow-lg">
          <div class="absolute inset-0 bg-gradient-to-br from-purple-500 to-pink-500"></div>
          <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
          
          <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
              <div class="text-3xl">💰</div>
              <div class="flex items-center space-x-1 text-sm">
                <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"></path>
                </svg>
                <span class="text-green-300">15%</span>
              </div>
            </div>
            
            <div>
              <p class="text-3xl font-bold text-white mb-2">{{ formatMoney(stats.revenus_mois) }}</p>
              <p class="text-white/80 text-sm font-medium">Revenus du Mois</p>
            </div>
          </div>
          
          <div class="absolute -top-2 -right-2 w-16 h-16 bg-white/10 rounded-full"></div>
          <div class="absolute -bottom-4 -left-4 w-12 h-12 bg-white/5 rounded-full"></div>
        </div>

      </div>

      <!-- Actions rapides -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        
        <!-- Actions principales -->
        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6 hover:bg-gray-800/70 transition-all">
          <div class="mb-6">
            <h3 class="text-lg font-semibold text-white">Actions Rapides</h3>
            <p class="text-gray-400 text-sm">Tâches courantes</p>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <button class="bg-gradient-to-r from-blue-500 to-blue-600 group relative overflow-hidden rounded-xl p-4 text-white hover:scale-105 transition-all duration-300 hover:shadow-lg">
              <div class="relative z-10">
                <div class="text-2xl mb-2">👤</div>
                <h4 class="font-semibold text-sm">Nouveau Membre</h4>
                <p class="text-xs opacity-90">Inscription rapide</p>
              </div>
              <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </button>
            <button class="bg-gradient-to-r from-green-500 to-green-600 group relative overflow-hidden rounded-xl p-4 text-white hover:scale-105 transition-all duration-300 hover:shadow-lg">
              <div class="relative z-10">
                <div class="text-2xl mb-2">📋</div>
                <h4 class="font-semibold text-sm">Présences</h4>
                <p class="text-xs opacity-90">Interface tablette</p>
              </div>
              <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </button>
            <button class="bg-gradient-to-r from-yellow-500 to-orange-500 group relative overflow-hidden rounded-xl p-4 text-white hover:scale-105 transition-all duration-300 hover:shadow-lg">
              <div class="relative z-10">
                <div class="text-2xl mb-2">💳</div>
                <h4 class="font-semibold text-sm">Paiements</h4>
                <p class="text-xs opacity-90">Gérer finances</p>
              </div>
              <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </button>
            <button class="bg-gradient-to-r from-purple-500 to-pink-500 group relative overflow-hidden rounded-xl p-4 text-white hover:scale-105 transition-all duration-300 hover:shadow-lg">
              <div class="relative z-10">
                <div class="text-2xl mb-2">📅</div>
                <h4 class="font-semibold text-sm">Planning</h4>
                <p class="text-xs opacity-90">Horaires cours</p>
              </div>
              <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </button>
          </div>
        </div>

        <!-- Notifications -->
        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6 hover:bg-gray-800/70 transition-all">
          <div class="mb-6">
            <h3 class="text-lg font-semibold text-white">Notifications</h3>
            <p class="text-gray-400 text-sm">Alertes importantes</p>
          </div>
          <div class="space-y-3">
            <div class="group flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-700/30 transition-all cursor-pointer">
              <div class="bg-yellow-500/20 text-yellow-400 w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-white font-medium text-sm group-hover:text-blue-400 transition-colors">3 Paiements en retard</p>
                <p class="text-gray-400 text-xs mt-1">Relances à envoyer cette semaine</p>
              </div>
            </div>
            
            <div class="group flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-700/30 transition-all cursor-pointer">
              <div class="bg-blue-500/20 text-blue-400 w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-white font-medium text-sm group-hover:text-blue-400 transition-colors">Nouveau membre inscrit</p>
                <p class="text-gray-400 text-xs mt-1">Marie Dubois - Ceinture blanche</p>
              </div>
            </div>
            
            <div class="group flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-700/30 transition-all cursor-pointer">
              <div class="bg-green-500/20 text-green-400 w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-white font-medium text-sm group-hover:text-blue-400 transition-colors">Objectif atteint</p>
                <p class="text-gray-400 text-xs mt-1">250 membres inscrits ce mois !</p>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Footer stats -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-gray-800/30 backdrop-blur-sm border border-gray-700/30 rounded-lg p-4 hover:bg-gray-700/30 transition-all">
          <div class="text-center">
            <p class="text-2xl font-bold text-white mb-1">87%</p>
            <p class="text-xs text-gray-400 uppercase tracking-wide">Taux de présence</p>
          </div>
        </div>
        <div class="bg-gray-800/30 backdrop-blur-sm border border-gray-700/30 rounded-lg p-4 hover:bg-gray-700/30 transition-all">
          <div class="text-center">
            <p class="text-2xl font-bold text-white mb-1">235/250</p>
            <p class="text-xs text-gray-400 uppercase tracking-wide">Membres actifs</p>
          </div>
        </div>
        <div class="bg-gray-800/30 backdrop-blur-sm border border-gray-700/30 rounded-lg p-4 hover:bg-gray-700/30 transition-all">
          <div class="text-center">
            <p class="text-2xl font-bold text-white mb-1">24</p>
            <p class="text-xs text-gray-400 uppercase tracking-wide">Cours cette semaine</p>
          </div>
        </div>
        <div class="bg-gray-800/30 backdrop-blur-sm border border-gray-700/30 rounded-lg p-4 hover:bg-gray-700/30 transition-all">
          <div class="text-center">
            <p class="text-2xl font-bold text-white mb-1">76%</p>
            <p class="text-xs text-gray-400 uppercase tracking-wide">Revenus objectif</p>
          </div>
        </div>
      </div>

    </main>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  stats: {
    type: Object,
    default: () => ({
      total_membres: 250,
      membres_actifs: 235,
      total_cours: 18,
      presences_aujourd_hui: 43,
      revenus_mois: 5750,
      evolution_revenus: 12.5,
      paiements_en_retard: 3
    })
  }
})

// Computed properties
const userInitial = computed(() => {
  return $page.props.auth.user.name.charAt(0).toUpperCase()
})

// Utilitaires
const formatMoney = (amount) => {
  return new Intl.NumberFormat('fr-CA', {
    style: 'currency',
    currency: 'CAD',
    minimumFractionDigits: 0
  }).format(amount)
}
</script>

<style scoped>
/* Animations personnalisées */
.animate-fade-in {
  animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
