<template>
  <div class="min-h-screen bg-gray-900 text-white">
    <div class="container mx-auto py-8 px-4">
      <h1 class="text-3xl font-bold mb-8 text-center">
        🥋 StudiosDB v5 Pro - Dashboard Simple
      </h1>
      
      <div class="bg-gray-800 rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Test de Base</h2>
        <p class="text-green-400">✅ Vue Vue.js chargée avec succès!</p>
        <p class="text-blue-400">📅 {{ new Date().toLocaleString('fr-CA') }}</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-600 p-4 rounded-lg text-center">
          <h3 class="font-bold">Membres Total</h3>
          <p class="text-2xl">{{ stats?.total_membres || 0 }}</p>
        </div>
        
        <div class="bg-green-600 p-4 rounded-lg text-center">
          <h3 class="font-bold">Membres Actifs</h3>
          <p class="text-2xl">{{ stats?.membres_actifs || 0 }}</p>
        </div>
        
        <div class="bg-yellow-600 p-4 rounded-lg text-center">
          <h3 class="font-bold">Cours</h3>
          <p class="text-2xl">{{ stats?.total_cours || 0 }}</p>
        </div>
        
        <div class="bg-purple-600 p-4 rounded-lg text-center">
          <h3 class="font-bold">Revenus</h3>
          <p class="text-2xl">${{ stats?.revenus_mois || 0 }}</p>
        </div>
      </div>

      <div class="bg-gray-800 rounded-lg p-4">
        <h3 class="font-semibold mb-2">Debug Info:</h3>
        <pre class="text-xs bg-gray-700 p-2 rounded overflow-auto">{{ JSON.stringify(stats, null, 2) }}</pre>
      </div>
    </div>
  </div>
</template>

<script setup>
// Props avec valeurs par défaut
const props = defineProps({
  stats: {
    type: Object,
    default: () => ({
      total_membres: 0,
      membres_actifs: 0,
      total_cours: 0,
      revenus_mois: 0
    })
  }
})

// Log pour debug
console.log('DashboardSimple chargé avec stats:', props.stats)
</script>
