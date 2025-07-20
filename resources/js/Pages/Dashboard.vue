<template>
  <div class="min-h-screen bg-gray-900">
    <!-- Header simple -->
    <header class="bg-gray-800 shadow">
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
          <h1 class="text-3xl font-bold text-white">Dashboard StudiosDB v5</h1>
          <div class="flex items-center space-x-4">
            <span class="text-white">{{ $page.props.auth.user.name }}</span>
            <form @submit.prevent="logout" class="inline">
              <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                Déconnexion
              </button>
            </form>
          </div>
        </div>
      </div>
    </header>

    <!-- Navigation simple -->
    <nav class="bg-gray-800 border-b border-gray-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex space-x-8">
          <Link :href="route('dashboard')" class="text-white hover:text-gray-300 px-3 py-2 text-sm font-medium">
            🏠 Dashboard
          </Link>
          <button @click="navigateTo('membres.index')" class="text-gray-300 hover:text-white px-3 py-2 text-sm font-medium">
            👥 Membres
          </button>
          <button @click="navigateTo('presences.tablette')" class="text-gray-300 hover:text-white px-3 py-2 text-sm font-medium">
            ✅ Présences
          </button>
          <button @click="navigateTo('cours.index')" class="text-gray-300 hover:text-white px-3 py-2 text-sm font-medium">
            📚 Cours
          </button>
          <button @click="navigateTo('paiements.index')" class="text-gray-300 hover:text-white px-3 py-2 text-sm font-medium">
            💰 Finances
          </button>
        </div>
      </div>
    </nav>

    <!-- Contenu principal -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      
      <!-- Métriques principales -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Membres -->
        <div class="bg-gray-800 overflow-hidden shadow rounded-lg cursor-pointer hover:bg-gray-700 transition-colors" @click="navigateTo('membres.index')">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <span class="text-4xl">👥</span>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-400 truncate">Membres</dt>
                  <dd class="text-3xl font-bold text-white">{{ stats.total_membres || 250 }}</dd>
                </dl>
                <div class="text-sm text-green-400">+12% ce mois</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Cours -->
        <div class="bg-gray-800 overflow-hidden shadow rounded-lg cursor-pointer hover:bg-gray-700 transition-colors" @click="navigateTo('cours.index')">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <span class="text-4xl">📚</span>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-400 truncate">Cours actifs</dt>
                  <dd class="text-3xl font-bold text-white">{{ stats.total_cours || 18 }}</dd>
                </dl>
                <div class="text-sm text-gray-400">Toutes ceintures</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Présences -->
        <div class="bg-gray-800 overflow-hidden shadow rounded-lg cursor-pointer hover:bg-gray-700 transition-colors" @click="navigateTo('presences.index')">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <span class="text-4xl">✅</span>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-400 truncate">Présences</dt>
                  <dd class="text-3xl font-bold text-white">{{ stats.presences_aujourd_hui || 343 }}</dd>
                </dl>
                <div class="text-sm text-gray-400">Cette semaine</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Revenus -->
        <div class="bg-gray-800 overflow-hidden shadow rounded-lg cursor-pointer hover:bg-gray-700 transition-colors" @click="navigateTo('paiements.index')">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <span class="text-4xl">💰</span>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-400 truncate">Revenus</dt>
                  <dd class="text-3xl font-bold text-white">{{ formatMoney(stats.revenus_mois || 5750) }}</dd>
                </dl>
                <div class="text-sm text-green-400">+15% ce mois</div>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Actions rapides -->
      <div class="bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <h3 class="text-lg leading-6 font-medium text-white mb-4">Actions rapides</h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <button @click="navigateTo('membres.create')" class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-lg text-center transition-colors">
              <div class="text-2xl mb-2">👤</div>
              <div class="font-medium">Nouveau Membre</div>
              <div class="text-sm opacity-75">Inscription rapide</div>
            </button>

            <button @click="navigateTo('presences.tablette')" class="bg-green-600 hover:bg-green-700 text-white p-4 rounded-lg text-center transition-colors">
              <div class="text-2xl mb-2">📋</div>
              <div class="font-medium">Prendre Présences</div>
              <div class="text-sm opacity-75">Interface tablette</div>
            </button>

            <button @click="navigateTo('paiements.index')" class="bg-yellow-600 hover:bg-yellow-700 text-white p-4 rounded-lg text-center transition-colors">
              <div class="text-2xl mb-2">💳</div>
              <div class="font-medium">Gérer Paiements</div>
              <div class="text-sm opacity-75">Factures et relances</div>
            </button>

            <button @click="navigateTo('cours.planning')" class="bg-purple-600 hover:bg-purple-700 text-white p-4 rounded-lg text-center transition-colors">
              <div class="text-2xl mb-2">📅</div>
              <div class="font-medium">Planning Cours</div>
              <div class="text-sm opacity-75">Horaires et salles</div>
            </button>

          </div>
        </div>
      </div>

      <!-- Progression ceintures -->
      <div class="mt-8 bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <h3 class="text-lg leading-6 font-medium text-white mb-4">Répartition par ceintures</h3>
          <div class="space-y-3">
            <div v-for="ceinture in ceinturesData" :key="ceinture.nom" class="flex items-center">
              <div class="w-16 text-sm text-gray-300">{{ ceinture.nom }}</div>
              <div class="flex-1 mx-4">
                <div class="bg-gray-700 rounded-full h-4">
                  <div 
                    class="h-4 rounded-full transition-all duration-500"
                    :style="{ 
                      width: `${(ceinture.count / maxCeintures) * 100}%`,
                      backgroundColor: ceinture.couleur 
                    }"
                  ></div>
                </div>
              </div>
              <div class="w-12 text-right text-sm text-gray-300">{{ ceinture.count }}</div>
            </div>
          </div>
        </div>
      </div>

    </main>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
  stats: {
    type: Object,
    default: () => ({
      total_membres: 250,
      membres_actifs: 235,
      total_cours: 18,
      presences_aujourd_hui: 343,
      revenus_mois: 5750,
      evolution_revenus: 12.5,
      paiements_en_retard: 3
    })
  }
})

const ceinturesData = computed(() => [
  { nom: 'Blanc', count: 85, couleur: '#F8FAFC' },
  { nom: 'Jaune', count: 72, couleur: '#FEF08A' },
  { nom: 'Orange', count: 45, couleur: '#FDBA74' },
  { nom: 'Vert', count: 28, couleur: '#86EFAC' },
  { nom: 'Bleu', count: 15, couleur: '#93C5FD' },
  { nom: 'Marron', count: 8, couleur: '#D2B48C' },
  { nom: 'Noir', count: 3, couleur: '#374151' }
])

const maxCeintures = computed(() => {
  return Math.max(...ceinturesData.value.map(c => c.count))
})

const formatMoney = (amount) => {
  return new Intl.NumberFormat('fr-CA', {
    style: 'currency',
    currency: 'CAD',
    minimumFractionDigits: 0
  }).format(amount)
}

const navigateTo = (routeName, params = {}) => {
  router.visit(route(routeName, params))
}

const logout = () => {
  router.post(route('logout'))
}
</script>
