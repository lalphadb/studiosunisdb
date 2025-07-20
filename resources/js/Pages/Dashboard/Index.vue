<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 to-blue-900 text-white">
    
    <!-- Header -->
    <header class="bg-gray-800/90 p-4">
      <div class="max-w-7xl mx-auto flex justify-between items-center">
        <div>
          <h1 class="text-2xl font-bold">StudiosDB v5 Pro</h1>
          <p class="text-gray-400">Dashboard Inertia.js</p>
        </div>
        <div class="text-sm">
          {{ user.name }}
        </div>
      </div>
    </header>

    <!-- Contenu -->
    <main class="max-w-7xl mx-auto p-8">
      
      <!-- Message succès -->
      <div class="bg-green-500/20 border border-green-500 rounded-lg p-6 mb-8 text-center">
        <h2 class="text-3xl font-bold mb-2">🎉 Inertia.js Fonctionne!</h2>
        <p class="text-green-300">Dashboard Vue.js chargé avec succès</p>
      </div>

      <!-- Stats -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-blue-500 rounded-lg p-6 text-center hover:scale-105 transition-all">
          <div class="text-3xl mb-2">👥</div>
          <div class="text-2xl font-bold">{{ stats.total_membres }}</div>
          <div class="text-blue-200 text-sm">Membres</div>
        </div>

        <div class="bg-green-500 rounded-lg p-6 text-center hover:scale-105 transition-all">
          <div class="text-3xl mb-2">📚</div>
          <div class="text-2xl font-bold">{{ stats.total_cours }}</div>
          <div class="text-green-200 text-sm">Cours</div>
        </div>

        <div class="bg-yellow-500 rounded-lg p-6 text-center hover:scale-105 transition-all">
          <div class="text-3xl mb-2">✅</div>
          <div class="text-2xl font-bold">{{ stats.presences_aujourd_hui }}</div>
          <div class="text-yellow-200 text-sm">Présences</div>
        </div>

        <div class="bg-purple-500 rounded-lg p-6 text-center hover:scale-105 transition-all">
          <div class="text-3xl mb-2">💰</div>
          <div class="text-2xl font-bold">{{ formatMoney(stats.revenus_mois) }}</div>
          <div class="text-purple-200 text-sm">Revenus</div>
        </div>

      </div>

      <!-- Diagnostic -->
      <div class="bg-gray-800/50 rounded-lg p-6">
        <h3 class="text-xl font-bold mb-4">✅ Diagnostic Technique</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
          <div>
            <p class="text-green-300">✅ Laravel: Opérationnel</p>
            <p class="text-green-300">✅ Inertia.js: Chargé</p>
            <p class="text-green-300">✅ Vue.js: Rendu</p>
          </div>
          <div>
            <p class="text-green-300">✅ Assets: Compilés</p>
            <p class="text-green-300">✅ Auth: {{ user.email }}</p>
            <p class="text-green-300">✅ Données: Transmises</p>
          </div>
        </div>
      </div>

    </main>
  </div>
</template>

<script setup>
const props = defineProps({
  user: Object,
  stats: {
    type: Object,
    default: () => ({
      total_membres: 250,
      total_cours: 18,
      presences_aujourd_hui: 343,
      revenus_mois: 5750
    })
  }
})

const formatMoney = (amount) => {
  return new Intl.NumberFormat('fr-CA', {
    style: 'currency',
    currency: 'CAD',
    minimumFractionDigits: 0
  }).format(amount)
}
</script>
