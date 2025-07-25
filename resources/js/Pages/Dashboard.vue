<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-gray-900">
    <!-- Header -->
    <header class="bg-gray-800/90 backdrop-blur-sm border-b border-gray-700/50 sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="flex items-center space-x-4">
            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
              <span class="text-white font-bold text-lg">S</span>
            </div>
            <div>
              <h1 class="text-xl font-bold text-white">StudiosDB Pro</h1>
              <p class="text-xs text-gray-400">Gestion de dojo v5.0</p>
            </div>
          </div>
          <div class="flex items-center space-x-4">
            <button class="p-2 text-gray-400 hover:text-white transition-colors rounded-lg hover:bg-gray-700">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 -5v-5a1 1 0 0 0 -1 -1h-6a1 1 0 0 0 -1 1v5l-5 5h5" />
              </svg>
              <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full animate-pulse"></span>
            </button>
            <div class="flex items-center space-x-3 bg-gray-700/50 rounded-lg px-3 py-2">
              <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center">
                <span class="text-white font-semibold text-sm">{{ $page.props.auth.user.name.charAt(0) }}</span>
              </div>
              <div class="hidden md:block">
                <p class="text-white text-sm font-medium">{{ $page.props.auth.user.name }}</p>
                <p class="text-gray-400 text-xs">Administrateur</p>
              </div>
            </div>
            <button @click="logout" class="p-2 text-gray-400 hover:text-red-400 transition-colors rounded-lg hover:bg-gray-700">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </header>

    <!-- Main -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Bienvenue -->
      <div class="mb-8">
        <h2 class="text-3xl font-bold text-white mb-2">Bonjour {{ $page.props.auth.user.name }} 👋</h2>
        <p class="text-gray-400">Voici un aperçu de votre dojo aujourd'hui</p>
      </div>

      <!-- Statistiques -->
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <StatsCard title="Membres Total" :value="stats.total_membres || 250" :change="12" changeType="positive" icon="👥" color="from-blue-500 to-blue-600" @click="navigateTo('membres.index')" />
        <StatsCard title="Cours Actifs" :value="stats.total_cours || 18" :change="3" changeType="positive" icon="📚" color="from-emerald-500 to-emerald-600" @click="navigateTo('cours.index')" />
        <StatsCard title="Présences Aujourd'hui" :value="stats.presences_aujourd_hui || 43" :change="8" changeType="positive" icon="✅" color="from-yellow-500 to-orange-500" @click="navigateTo('presences.index')" />
        <StatsCard title="Revenus du Mois" :value="formatMoney(stats.revenus_mois || 5750)" :change="15" changeType="positive" icon="💰" color="from-purple-500 to-pink-500" @click="navigateTo('paiements.index')" />
      </div>
    </main>
  </div>
</template>

<script setup>
import StatsCard from '@/Components/Dashboard/StatsCard.vue'
import { router } from '@inertiajs/vue3'

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

const navigateTo = (routeName, params = {}) => {
  router.visit(route(routeName, params))
}

const logout = () => {
  router.post(route('logout'))
}

const formatMoney = (amount) => {
  return new Intl.NumberFormat('fr-CA', {
    style: 'currency',
    currency: 'CAD',
    minimumFractionDigits: 0
  }).format(amount)
}
</script>
