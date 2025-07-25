<<<<<<< Updated upstream
<<<<<<< Updated upstream
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

=======
><template>
  <div class="min-h-screen bg-gray-900 text-white">
    <!-- Header -->
    <header class="bg-gray-800 border-b border-gray-700 px-6 py-4 flex items-center justify-between">
      <div class="flex items-center space-x-4">
        <div class="text-2xl font-bold text-blue-400">StudiosDB Pro</div>
        <span class="text-sm text-gray-400">Gestion de dojo — v5.1</span>
      </div>
      <div class="flex items-center space-x-3">
        <span class="text-sm text-gray-400 hidden md:inline">Bienvenue {{ $page.props.auth.user.name }}</span>
        <button @click="logout" class="text-red-400 hover:text-white transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
        </button>
      </div>
    </header>

    <!-- Navigation -->
    <nav class="bg-gray-800 border-b border-gray-700 px-6 py-2 flex space-x-4 text-sm">
      <NavLink :href="route('dashboard')" icon="🏠" :active="true">Dashboard</NavLink>
      <NavLink @click="navigate('membres.index')" icon="👥">Membres</NavLink>
      <NavLink @click="navigate('cours.index')" icon="📚">Cours</NavLink>
      <NavLink @click="navigate('presences.index')" icon="✅">Présences</NavLink>
      <NavLink @click="navigate('paiements.index')" icon="💳">Paiements</NavLink>
    </nav>

    <!-- Main -->
    <main class="px-6 py-8 max-w-7xl mx-auto space-y-12">
      <!-- Statistiques principales -->
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <StatsCard title="Membres" :value="stats.total_membres" icon="👥" :change="8" changeType="positive" color="from-indigo-500 to-indigo-700" />
        <StatsCard title="Cours" :value="stats.total_cours" icon="📚" :change="4" changeType="positive" color="from-emerald-500 to-emerald-700" />
        <StatsCard title="Présences" :value="stats.total_presences" icon="✅" :change="6" changeType="positive" color="from-yellow-400 to-yellow-600" />
        <StatsCard title="Paiements" :value="formatMoney(stats.total_paiements)" icon="💳" :change="15" changeType="positive" color="from-pink-500 to-pink-700" />
      </div>

      <!-- Graphiques et Progression -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Graphique -->
        <ChartCard title="Évolution des Présences" subtitle="7 derniers jours">
          <canvas ref="chartRef" class="w-full h-64"></canvas>
        </ChartCard>

        <!-- Répartition ceintures -->
        <ChartCard title="Répartition Ceintures" subtitle="Par grade">
          <div v-if="stats.progression_ceintures.length" class="space-y-4">
            <div v-for="item in stats.progression_ceintures" :key="item.ceinture" class="text-sm">
              <div class="flex justify-between mb-1">
                <span class="font-medium">{{ item.ceinture }}</span>
                <span>{{ item.count }}</span>
              </div>
              <ProgressBar :value="item.count" />
            </div>
          </div>
          <div v-else class="text-gray-400 italic">Aucune donnée de progression disponible.</div>
        </ChartCard>
      </div>

=======
><template>
  <div class="min-h-screen bg-gray-900 text-white">
    <!-- Header -->
    <header class="bg-gray-800 border-b border-gray-700 px-6 py-4 flex items-center justify-between">
      <div class="flex items-center space-x-4">
        <div class="text-2xl font-bold text-blue-400">StudiosDB Pro</div>
        <span class="text-sm text-gray-400">Gestion de dojo — v5.1</span>
      </div>
      <div class="flex items-center space-x-3">
        <span class="text-sm text-gray-400 hidden md:inline">Bienvenue {{ $page.props.auth.user.name }}</span>
        <button @click="logout" class="text-red-400 hover:text-white transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
        </button>
      </div>
    </header>

    <!-- Navigation -->
    <nav class="bg-gray-800 border-b border-gray-700 px-6 py-2 flex space-x-4 text-sm">
      <NavLink :href="route('dashboard')" icon="🏠" :active="true">Dashboard</NavLink>
      <NavLink @click="navigate('membres.index')" icon="👥">Membres</NavLink>
      <NavLink @click="navigate('cours.index')" icon="📚">Cours</NavLink>
      <NavLink @click="navigate('presences.index')" icon="✅">Présences</NavLink>
      <NavLink @click="navigate('paiements.index')" icon="💳">Paiements</NavLink>
    </nav>

    <!-- Main -->
    <main class="px-6 py-8 max-w-7xl mx-auto space-y-12">
      <!-- Statistiques principales -->
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <StatsCard title="Membres" :value="stats.total_membres" icon="👥" :change="8" changeType="positive" color="from-indigo-500 to-indigo-700" />
        <StatsCard title="Cours" :value="stats.total_cours" icon="📚" :change="4" changeType="positive" color="from-emerald-500 to-emerald-700" />
        <StatsCard title="Présences" :value="stats.total_presences" icon="✅" :change="6" changeType="positive" color="from-yellow-400 to-yellow-600" />
        <StatsCard title="Paiements" :value="formatMoney(stats.total_paiements)" icon="💳" :change="15" changeType="positive" color="from-pink-500 to-pink-700" />
      </div>

      <!-- Graphiques et Progression -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Graphique -->
        <ChartCard title="Évolution des Présences" subtitle="7 derniers jours">
          <canvas ref="chartRef" class="w-full h-64"></canvas>
        </ChartCard>

        <!-- Répartition ceintures -->
        <ChartCard title="Répartition Ceintures" subtitle="Par grade">
          <div v-if="stats.progression_ceintures.length" class="space-y-4">
            <div v-for="item in stats.progression_ceintures" :key="item.ceinture" class="text-sm">
              <div class="flex justify-between mb-1">
                <span class="font-medium">{{ item.ceinture }}</span>
                <span>{{ item.count }}</span>
              </div>
              <ProgressBar :value="item.count" />
            </div>
          </div>
          <div v-else class="text-gray-400 italic">Aucune donnée de progression disponible.</div>
        </ChartCard>
      </div>

>>>>>>> Stashed changes
      <!-- Actions rapides -->
      <ActionCard title="Actions Rapides" subtitle="Gagnez du temps">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <ActionButton title="Nouveau Membre" subtitle="Ajoutez un élève" icon="👤" color="bg-indigo-600" @click="navigate('membres.create')" />
          <ActionButton title="Présences" subtitle="Mode tablette" icon="📋" color="bg-emerald-600" @click="navigate('presences.tablette')" />
          <ActionButton title="Paiement" subtitle="Enregistrer un paiement" icon="💰" color="bg-yellow-500" @click="navigate('paiements.create')" />
          <ActionButton title="Cours" subtitle="Nouveau cours" icon="📆" color="bg-pink-500" @click="navigate('cours.create')" />
        </div>
      </ActionCard>
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
    </main>
  </div>
</template>

<<<<<<< Updated upstream
<<<<<<< Updated upstream
<script setup>
import { computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
=======
=======
>>>>>>> Stashed changes
<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import Chart from 'chart.js/auto'
import StatsCard from '@/Components/Dashboard/StatsCard.vue'
import ChartCard from '@/Components/Dashboard/ChartCard.vue'
import ProgressBar from '@/Components/Dashboard/ProgressBar.vue'
import ActionCard from '@/Components/Dashboard/ActionCard.vue'
import ActionButton from '@/Components/Dashboard/ActionButton.vue'
import NavLink from '@/Components/Dashboard/NavLink.vue'
<<<<<<< Updated upstream
>>>>>>> Stashed changes

const props = defineProps<{
  stats: {
<<<<<<< Updated upstream
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
=======
=======

const props = defineProps<{
  stats: {
>>>>>>> Stashed changes
    total_membres: number
    total_cours: number
    total_presences: number
    total_paiements: number
    progression_ceintures: { ceinture: string; count: number }[]
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
  }
}>()

const chartRef = ref()

onMounted(() => {
  const ctx = chartRef.value.getContext('2d')
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
      datasets: [
        {
          label: 'Présences',
          data: [32, 38, 41, 43, 48, 35, 29],
          fill: true,
          tension: 0.4,
          borderColor: '#60a5fa',
          backgroundColor: 'rgba(96, 165, 250, 0.1)',
        },
      ],
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: {
        x: { ticks: { color: '#9ca3af' }, grid: { color: 'rgba(255,255,255,0.05)' } },
        y: { beginAtZero: true, ticks: { color: '#9ca3af' }, grid: { color: 'rgba(255,255,255,0.05)' } }
      }
    }
  })
})

<<<<<<< Updated upstream
<<<<<<< Updated upstream
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

=======
const formatMoney = (amount: number) =>
  new Intl.NumberFormat('fr-CA', { style: 'currency', currency: 'CAD', minimumFractionDigits: 0 }).format(amount)

const navigate = (routeName: string) => {
  router.visit(route(routeName))
}

>>>>>>> Stashed changes
=======
const formatMoney = (amount: number) =>
  new Intl.NumberFormat('fr-CA', { style: 'currency', currency: 'CAD', minimumFractionDigits: 0 }).format(amount)

const navigate = (routeName: string) => {
  router.visit(route(routeName))
}

>>>>>>> Stashed changes
const logout = () => {
  router.post(route('logout'))
}
</script>

