<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-900 to-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      
      <!-- Header -->
      <div class="mb-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-2">
          🥋 StudiosDB v5 Pro
        </h1>
        <p class="text-gray-400">École de Karaté - Dashboard Professionnel</p>
        <p class="text-green-400 text-sm mt-2">✅ Système opérationnel</p>
      </div>

      <!-- Statistiques principales -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Membres Total -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-xl hover:scale-105 transition-transform">
          <div class="flex items-center justify-between mb-4">
            <div class="text-3xl">👥</div>
            <div class="text-green-300 text-sm">+{{ stats.evolution_membres }}%</div>
          </div>
          <div>
            <p class="text-3xl font-bold mb-1">{{ stats.total_membres }}</p>
            <p class="text-blue-100 text-sm">Membres Total</p>
          </div>
        </div>

        <!-- Membres Actifs -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-xl hover:scale-105 transition-transform">
          <div class="flex items-center justify-between mb-4">
            <div class="text-3xl">⚡</div>
            <div class="text-green-300 text-sm">{{ Math.round((stats.membres_actifs / stats.total_membres) * 100) }}%</div>
          </div>
          <div>
            <p class="text-3xl font-bold mb-1">{{ stats.membres_actifs }}</p>
            <p class="text-green-100 text-sm">Membres Actifs</p>
          </div>
        </div>

        <!-- Présences -->
        <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl p-6 text-white shadow-xl hover:scale-105 transition-transform">
          <div class="flex items-center justify-between mb-4">
            <div class="text-3xl">✅</div>
            <div class="text-yellow-200 text-sm">Aujourd'hui</div>
          </div>
          <div>
            <p class="text-3xl font-bold mb-1">{{ stats.presences_aujourd_hui }}</p>
            <p class="text-yellow-100 text-sm">Présences</p>
          </div>
        </div>

        <!-- Revenus -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-xl hover:scale-105 transition-transform">
          <div class="flex items-center justify-between mb-4">
            <div class="text-3xl">💰</div>
            <div class="text-green-300 text-sm">+{{ stats.evolution_revenus }}%</div>
          </div>
          <div>
            <p class="text-3xl font-bold mb-1">${{ stats.revenus_mois }}</p>
            <p class="text-purple-100 text-sm">Revenus Mois</p>
          </div>
        </div>

      </div>

      <!-- Actions rapides -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        
        <button class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg">
          <div class="flex items-center space-x-3">
            <span class="text-2xl">👤</span>
            <div class="text-left">
              <div class="font-semibold">Nouveau Membre</div>
              <div class="text-blue-200 text-sm">Inscription rapide</div>
            </div>
          </div>
        </button>

        <button class="bg-gradient-to-r from-green-600 to-green-700 text-white p-4 rounded-lg hover:from-green-700 hover:to-green-800 transition-all shadow-lg">
          <div class="flex items-center space-x-3">
            <span class="text-2xl">📋</span>
            <div class="text-left">
              <div class="font-semibold">Présences</div>
              <div class="text-green-200 text-sm">Mode tablette</div>
            </div>
          </div>
        </button>

        <button class="bg-gradient-to-r from-yellow-600 to-yellow-700 text-white p-4 rounded-lg hover:from-yellow-700 hover:to-yellow-800 transition-all shadow-lg">
          <div class="flex items-center space-x-3">
            <span class="text-2xl">💳</span>
            <div class="text-left">
              <div class="font-semibold">Paiements</div>
              <div class="text-yellow-200 text-sm">{{ stats.paiements_en_retard }} en retard</div>
            </div>
          </div>
        </button>

        <button class="bg-gradient-to-r from-purple-600 to-purple-700 text-white p-4 rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all shadow-lg">
          <div class="flex items-center space-x-3">
            <span class="text-2xl">📅</span>
            <div class="text-left">
              <div class="font-semibold">Planning</div>
              <div class="text-purple-200 text-sm">{{ stats.total_cours }} cours</div>
            </div>
          </div>
        </button>

      </div>

      <!-- Objectifs -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Objectif Membres -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
          <h3 class="text-white font-semibold mb-4 flex items-center">
            <span class="mr-2">🎯</span>
            Objectif Membres
          </h3>
          <div class="mb-4">
            <div class="w-full bg-gray-700 rounded-full h-3">
              <div 
                class="bg-blue-500 h-3 rounded-full transition-all duration-1000"
                :style="{ width: Math.min((stats.total_membres / stats.objectif_membres) * 100, 100) + '%' }"
              ></div>
            </div>
          </div>
          <p class="text-gray-300 text-sm">{{ stats.total_membres }}/{{ stats.objectif_membres }}</p>
        </div>

        <!-- Taux Présence -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
          <h3 class="text-white font-semibold mb-4 flex items-center">
            <span class="mr-2">📊</span>
            Taux Présence
          </h3>
          <div class="mb-4">
            <div class="w-full bg-gray-700 rounded-full h-3">
              <div 
                class="bg-green-500 h-3 rounded-full transition-all duration-1000"
                :style="{ width: stats.taux_presence + '%' }"
              ></div>
            </div>
          </div>
          <p class="text-gray-300 text-sm">{{ stats.taux_presence }}% excellent</p>
        </div>

        <!-- Revenus -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
          <h3 class="text-white font-semibold mb-4 flex items-center">
            <span class="mr-2">💎</span>
            Revenus
          </h3>
          <div class="mb-4">
            <div class="w-full bg-gray-700 rounded-full h-3">
              <div 
                class="bg-yellow-500 h-3 rounded-full transition-all duration-1000"
                :style="{ width: Math.min((stats.revenus_mois / stats.objectif_revenus) * 100, 100) + '%' }"
              ></div>
            </div>
          </div>
          <p class="text-gray-300 text-sm">${{ stats.revenus_mois }}/${{ stats.objectif_revenus }}</p>
        </div>

        <!-- Satisfaction -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
          <h3 class="text-white font-semibold mb-4 flex items-center">
            <span class="mr-2">⭐</span>
            Satisfaction
          </h3>
          <div class="mb-4">
            <div class="w-full bg-gray-700 rounded-full h-3">
              <div 
                class="bg-purple-500 h-3 rounded-full transition-all duration-1000"
                :style="{ width: stats.satisfaction_moyenne + '%' }"
              ></div>
            </div>
          </div>
          <p class="text-gray-300 text-sm">{{ stats.satisfaction_moyenne }}% excellent</p>
        </div>

      </div>

      <!-- Footer -->
      <div class="mt-8 text-center">
        <p class="text-gray-500 text-sm">
          📅 {{ new Date().toLocaleDateString('fr-CA', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
          }) }}
        </p>
        <p class="text-green-400 text-xs mt-1">
          🟢 Système en ligne - Dernière mise à jour: {{ new Date().toLocaleTimeString('fr-CA') }}
        </p>
      </div>

    </div>
  </div>
</template>

<script setup>
// Props simples et sécurisés
const props = defineProps({
  user: Object,
  stats: {
    type: Object,
    default: () => ({
      total_membres: 0,
      membres_actifs: 0,
      total_cours: 0,
      presences_aujourd_hui: 0,
      revenus_mois: 0,
      evolution_revenus: 0,
      evolution_membres: 0,
      paiements_en_retard: 0,
      taux_presence: 0,
      objectif_membres: 300,
      objectif_revenus: 7000,
      satisfaction_moyenne: 0
    })
  }
})

// Debug
console.log('Dashboard Professionnel Sécurisé chargé:', props.stats)
</script>
