<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-gray-900">
    
    <!-- Header ultra-moderne -->
    <header class="bg-gray-800/90 backdrop-blur-sm border-b border-gray-700/50 sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          
          <!-- Logo et titre -->
          <div class="flex items-center space-x-4">
            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center shadow-lg">
              <span class="text-white font-bold text-lg">S</span>
            </div>
            <div>
              <h1 class="text-xl font-bold text-white">StudiosDB Pro</h1>
              <p class="text-xs text-gray-400">Gestion de dojo v5.0 - Moderne</p>
            </div>
          </div>
          
          <!-- Actions utilisateur -->
          <div class="flex items-center space-x-4">
            <!-- Notifications -->
            <div class="relative">
              <button class="p-2 text-gray-400 hover:text-white transition-colors rounded-lg hover:bg-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5-5v-5a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v5l-5 5h5"></path>
                </svg>
                <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full animate-pulse"></span>
              </button>
            </div>
            
            <!-- Profil utilisateur -->
            <div class="flex items-center space-x-3 bg-gray-700/50 rounded-lg px-3 py-2">
              <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center">
                <span class="text-white font-semibold text-sm">{{ userInitial }}</span>
              </div>
              <div class="hidden md:block">
                <p class="text-white text-sm font-medium">{{ $page.props.auth.user.name }}</p>
                <p class="text-gray-400 text-xs">Administrateur</p>
              </div>
            </div>
            
            <!-- Déconnexion -->
            <Link :href="route('logout')" method="post" 
                  class="p-2 text-gray-400 hover:text-red-400 transition-colors rounded-lg hover:bg-gray-700">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
              </svg>
            </Link>
          </div>
        </div>
      </div>
    </header>

    <!-- Navigation moderne avec icônes -->
    <nav class="bg-gray-800/70 backdrop-blur-sm border-b border-gray-700/30">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex space-x-1">
          <!-- Dashboard actif -->
          <div class="flex items-center space-x-2 px-4 py-2 rounded-lg text-sm font-medium bg-blue-600 text-white shadow-lg">
            <span class="text-lg">🏠</span>
            <span>Dashboard</span>
            <div class="w-1 h-1 bg-white rounded-full"></div>
          </div>
          
          <!-- Autres liens (simulés pour le design) -->
          <button class="flex items-center space-x-2 px-4 py-2 rounded-lg text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700/50 transition-all">
            <span class="text-lg">👥</span>
            <span>Membres</span>
          </button>
          <button class="flex items-center space-x-2 px-4 py-2 rounded-lg text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700/50 transition-all">
            <span class="text-lg">✅</span>
            <span>Présences</span>
          </button>
          <button class="flex items-center space-x-2 px-4 py-2 rounded-lg text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700/50 transition-all">
            <span class="text-lg">📚</span>
            <span>Cours</span>
          </button>
          <button class="flex items-center space-x-2 px-4 py-2 rounded-lg text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700/50 transition-all">
            <span class="text-lg">💰</span>
            <span>Finances</span>
          </button>
        </div>
      </div>
    </nav>

    <!-- Contenu principal -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      
      <!-- Message de bienvenue moderne -->
      <div class="mb-8">
        <h2 class="text-4xl font-bold text-white mb-2 bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
          Bonjour {{ $page.props.auth.user.name }} 👋
        </h2>
        <p class="text-gray-400 text-lg">Voici un aperçu de votre dojo aujourd'hui - Dashboard Moderne</p>
      </div>

      <!-- KPIs Principaux Ultra-Modernes -->
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        
        <!-- Carte Membres -->
        <div class="group relative overflow-hidden rounded-xl p-6 cursor-pointer hover:scale-105 transition-all duration-300 hover:shadow-2xl">
          <!-- Background gradient animé -->
          <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-blue-600"></div>
          <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
          
          <!-- Contenu -->
          <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
              <div class="text-4xl">👥</div>
              <div class="flex items-center space-x-1 text-sm">
                <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"></path>
                </svg>
                <span class="text-green-300 font-semibold">+12%</span>
              </div>
            </div>
            
            <div>
              <p class="text-4xl font-bold text-white mb-2">{{ stats.total_membres }}</p>
              <p class="text-white/90 text-sm font-medium">Membres Total</p>
              <p class="text-white/70 text-xs mt-1">Croissance ce mois</p>
            </div>
          </div>
          
          <!-- Décorations -->
          <div class="absolute -top-2 -right-2 w-20 h-20 bg-white/10 rounded-full"></div>
          <div class="absolute -bottom-4 -left-4 w-16 h-16 bg-white/5 rounded-full"></div>
        </div>

        <!-- Carte Cours -->
        <div class="group relative overflow-hidden rounded-xl p-6 cursor-pointer hover:scale-105 transition-all duration-300 hover:shadow-2xl">
          <div class="absolute inset-0 bg-gradient-to-br from-emerald-500 to-emerald-600"></div>
          <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
          
          <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
              <div class="text-4xl">📚</div>
              <div class="flex items-center space-x-1 text-sm">
                <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"></path>
                </svg>
                <span class="text-green-300 font-semibold">+3%</span>
              </div>
            </div>
            
            <div>
              <p class="text-4xl font-bold text-white mb-2">{{ stats.total_cours }}</p>
              <p class="text-white/90 text-sm font-medium">Cours Actifs</p>
              <p class="text-white/70 text-xs mt-1">Toutes ceintures</p>
            </div>
          </div>
          
          <div class="absolute -top-2 -right-2 w-20 h-20 bg-white/10 rounded-full"></div>
          <div class="absolute -bottom-4 -left-4 w-16 h-16 bg-white/5 rounded-full"></div>
        </div>

        <!-- Carte Présences -->
        <div class="group relative overflow-hidden rounded-xl p-6 cursor-pointer hover:scale-105 transition-all duration-300 hover:shadow-2xl">
          <div class="absolute inset-0 bg-gradient-to-br from-yellow-500 to-orange-500"></div>
          <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
          
          <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
              <div class="text-4xl">✅</div>
              <div class="flex items-center space-x-1 text-sm">
                <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"></path>
                </svg>
                <span class="text-green-300 font-semibold">+8%</span>
              </div>
            </div>
            
            <div>
              <p class="text-4xl font-bold text-white mb-2">{{ stats.presences_aujourd_hui }}</p>
              <p class="text-white/90 text-sm font-medium">Présences Semaine</p>
              <p class="text-white/70 text-xs mt-1">Excellent taux!</p>
            </div>
          </div>
          
          <div class="absolute -top-2 -right-2 w-20 h-20 bg-white/10 rounded-full"></div>
          <div class="absolute -bottom-4 -left-4 w-16 h-16 bg-white/5 rounded-full"></div>
        </div>

        <!-- Carte Revenus -->
        <div class="group relative overflow-hidden rounded-xl p-6 cursor-pointer hover:scale-105 transition-all duration-300 hover:shadow-2xl">
          <div class="absolute inset-0 bg-gradient-to-br from-purple-500 to-pink-500"></div>
          <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
          
          <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
              <div class="text-4xl">💰</div>
              <div class="flex items-center space-x-1 text-sm">
                <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"></path>
                </svg>
                <span class="text-green-300 font-semibold">+15%</span>
              </div>
            </div>
            
            <div>
              <p class="text-4xl font-bold text-white mb-2">{{ formatMoney(stats.revenus_mois) }}</p>
              <p class="text-white/90 text-sm font-medium">Revenus du Mois</p>
              <p class="text-white/70 text-xs mt-1">Objectif: 76%</p>
            </div>
          </div>
          
          <div class="absolute -top-2 -right-2 w-20 h-20 bg-white/10 rounded-full"></div>
          <div class="absolute -bottom-4 -left-4 w-16 h-16 bg-white/5 rounded-full"></div>
        </div>

      </div>

      <!-- Section Actions Rapides Modernes -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        
        <!-- Actions principales -->
        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6 hover:bg-gray-800/70 transition-all">
          <div class="mb-6">
            <h3 class="text-xl font-bold text-white flex items-center">
              <span class="mr-2">⚡</span>
              Actions Rapides
            </h3>
            <p class="text-gray-400 text-sm">Tâches courantes du dojo</p>
          </div>
          
          <div class="grid grid-cols-2 gap-4">
            <!-- Nouveau Membre -->
            <button class="bg-gradient-to-r from-blue-500 to-blue-600 group relative overflow-hidden rounded-xl p-4 text-white hover:scale-105 transition-all duration-300 hover:shadow-lg">
              <div class="relative z-10">
                <div class="text-3xl mb-3">👤</div>
                <h4 class="font-bold text-sm">Nouveau Membre</h4>
                <p class="text-xs opacity-90">Inscription rapide</p>
              </div>
              <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </button>
            
            <!-- Présences -->
            <button class="bg-gradient-to-r from-green-500 to-green-600 group relative overflow-hidden rounded-xl p-4 text-white hover:scale-105 transition-all duration-300 hover:shadow-lg">
              <div class="relative z-10">
                <div class="text-3xl mb-3">📋</div>
                <h4 class="font-bold text-sm">Présences</h4>
                <p class="text-xs opacity-90">Interface tablette</p>
              </div>
              <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </button>
            
            <!-- Paiements -->
            <button class="bg-gradient-to-r from-yellow-500 to-orange-500 group relative overflow-hidden rounded-xl p-4 text-white hover:scale-105 transition-all duration-300 hover:shadow-lg">
              <div class="relative z-10">
                <div class="text-3xl mb-3">💳</div>
                <h4 class="font-bold text-sm">Paiements</h4>
                <p class="text-xs opacity-90">Gérer finances</p>
              </div>
              <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </button>
            
            <!-- Planning -->
            <button class="bg-gradient-to-r from-purple-500 to-pink-500 group relative overflow-hidden rounded-xl p-4 text-white hover:scale-105 transition-all duration-300 hover:shadow-lg">
              <div class="relative z-10">
                <div class="text-3xl mb-3">📅</div>
                <h4 class="font-bold text-sm">Planning</h4>
                <p class="text-xs opacity-90">Horaires cours</p>
              </div>
              <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </button>
          </div>
        </div>

        <!-- Progression Ceintures -->
        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6 hover:bg-gray-800/70 transition-all">
          <div class="mb-6">
            <h3 class="text-xl font-bold text-white flex items-center">
              <span class="mr-2">🥋</span>
              Répartition Ceintures
            </h3>
            <p class="text-gray-400 text-sm">Distribution par grade</p>
          </div>
          
          <div class="space-y-4">
            <div v-for="ceinture in ceinturesData" :key="ceinture.nom" 
                 class="group hover:bg-gray-700/30 rounded-lg p-3 transition-all cursor-pointer">
              <div class="flex items-center justify-between mb-2">
                <div class="flex items-center space-x-3">
                  <div class="w-4 h-4 rounded-full" :style="{ backgroundColor: ceinture.couleur }"></div>
                  <span class="text-white font-medium">{{ ceinture.nom }}</span>
                </div>
                <span class="text-gray-300 font-bold">{{ ceinture.count }}</span>
              </div>
              <div class="w-full bg-gray-700 rounded-full h-2">
                <div 
                  class="h-2 rounded-full transition-all duration-1000 ease-out"
                  :style="{ 
                    width: `${(ceinture.count / maxCeintures) * 100}%`,
                    backgroundColor: ceinture.couleur 
                  }"
                ></div>
              </div>
              <div class="text-xs text-gray-400 mt-1">
                {{ Math.round((ceinture.count / totalCeintures) * 100) }}% du total
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Notifications modernes -->
      <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6 mb-8">
        <div class="mb-6">
          <h3 class="text-xl font-bold text-white flex items-center">
            <span class="mr-2">🔔</span>
            Notifications Récentes
          </h3>
          <p class="text-gray-400 text-sm">Alertes importantes</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <!-- Notification Warning -->
          <div class="bg-yellow-500/10 border border-yellow-500/20 rounded-lg p-4 hover:bg-yellow-500/20 transition-all">
            <div class="flex items-center space-x-3">
              <div class="w-8 h-8 bg-yellow-500/20 text-yellow-400 rounded-full flex items-center justify-center">
                <span class="text-lg">⚠️</span>
              </div>
              <div>
                <p class="text-yellow-300 font-medium text-sm">3 Paiements en retard</p>
                <p class="text-yellow-400/70 text-xs">Relances à envoyer</p>
              </div>
            </div>
          </div>
          
          <!-- Notification Info -->
          <div class="bg-blue-500/10 border border-blue-500/20 rounded-lg p-4 hover:bg-blue-500/20 transition-all">
            <div class="flex items-center space-x-3">
              <div class="w-8 h-8 bg-blue-500/20 text-blue-400 rounded-full flex items-center justify-center">
                <span class="text-lg">ℹ️</span>
              </div>
              <div>
                <p class="text-blue-300 font-medium text-sm">Nouveau membre</p>
                <p class="text-blue-400/70 text-xs">Marie Dubois inscrite</p>
              </div>
            </div>
          </div>
          
          <!-- Notification Success -->
          <div class="bg-green-500/10 border border-green-500/20 rounded-lg p-4 hover:bg-green-500/20 transition-all">
            <div class="flex items-center space-x-3">
              <div class="w-8 h-8 bg-green-500/20 text-green-400 rounded-full flex items-center justify-center">
                <span class="text-lg">✅</span>
              </div>
              <div>
                <p class="text-green-300 font-medium text-sm">Objectif atteint!</p>
                <p class="text-green-400/70 text-xs">250 membres ce mois</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Stats rapides bas de page -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-gray-800/30 backdrop-blur-sm border border-gray-700/30 rounded-lg p-4 hover:bg-gray-700/30 transition-all text-center">
          <p class="text-3xl font-bold text-white mb-1">87%</p>
          <p class="text-xs text-gray-400 uppercase tracking-wide">Taux présence</p>
        </div>
        <div class="bg-gray-800/30 backdrop-blur-sm border border-gray-700/30 rounded-lg p-4 hover:bg-gray-700/30 transition-all text-center">
          <p class="text-3xl font-bold text-white mb-1">235/250</p>
          <p class="text-xs text-gray-400 uppercase tracking-wide">Membres actifs</p>
        </div>
        <div class="bg-gray-800/30 backdrop-blur-sm border border-gray-700/30 rounded-lg p-4 hover:bg-gray-700/30 transition-all text-center">
          <p class="text-3xl font-bold text-white mb-1">24</p>
          <p class="text-xs text-gray-400 uppercase tracking-wide">Cours semaine</p>
        </div>
        <div class="bg-gray-800/30 backdrop-blur-sm border border-gray-700/30 rounded-lg p-4 hover:bg-gray-700/30 transition-all text-center">
          <p class="text-3xl font-bold text-white mb-1">76%</p>
          <p class="text-xs text-gray-400 uppercase tracking-wide">Objectif revenus</p>
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
      presences_aujourd_hui: 343,
      revenus_mois: 5750
    })
  }
})

// Computed properties
const userInitial = computed(() => {
  return $page.props.auth.user.name.charAt(0).toUpperCase()
})

// Données ceintures
const ceinturesData = computed(() => [
  { nom: 'Blanc', count: 85, couleur: '#F8FAFC' },
  { nom: 'Jaune', count: 72, couleur: '#FEF08A' },
  { nom: 'Orange', count: 45, couleur: '#FDBA74' },
  { nom: 'Vert', count: 28, couleur: '#86EFAC' },
  { nom: 'Bleu', count: 15, couleur: '#93C5FD' },
  { nom: 'Marron', count: 8, couleur: '#D2B48C' },
  { nom: 'Noir', count: 3, couleur: '#374151' }
])

const maxCeintures = computed(() => Math.max(...ceinturesData.value.map(c => c.count)))
const totalCeintures = computed(() => ceinturesData.value.reduce((sum, c) => sum + c.count, 0))

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
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
  animation: fadeIn 0.5s ease-out;
}

/* Gradients animés */
.bg-gradient-to-br {
  background-size: 200% 200%;
  animation: gradient 3s ease infinite;
}

@keyframes gradient {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}
</style>
