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
            <div class="relative">
              <button class="p-2 text-gray-400 hover:text-white transition-colors rounded-lg hover:bg-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 -5v-5a1 1 0 0 0 -1 -1h-6a1 1 0 0 0 -1 1v5l-5 5h5"></path>
                </svg>
                <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full animate-pulse"></span>
              </button>
            </div>
            
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </header>

    <!-- Navigation moderne -->
    <nav class="bg-gray-800/70 backdrop-blur-sm border-b border-gray-700/30">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex space-x-1">
          <NavLink :href="route('dashboard')" :active="true" icon="🏠">Dashboard</NavLink>
          <NavLink @click="navigateTo('membres.index')" icon="👥">Membres</NavLink>
          <NavLink @click="navigateTo('presences.tablette')" icon="✅">Présences</NavLink>
          <NavLink @click="navigateTo('cours.index')" icon="📚">Cours</NavLink>
          <NavLink @click="navigateTo('paiements.index')" icon="💰">Finances</NavLink>
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
        <StatsCard 
          title="Membres Total"
          :value="stats.total_membres || 250"
          :change="12"
          change-type="positive"
          icon="👥"
          color="from-blue-500 to-blue-600"
          @click="navigateTo('membres.index')"
        />

        <!-- Cours -->
        <StatsCard 
          title="Cours Actifs"
          :value="stats.total_cours || 18"
          :change="3"
          change-type="positive"
          icon="📚"
          color="from-emerald-500 to-emerald-600"
          @click="navigateTo('cours.index')"
        />

        <!-- Présences -->
        <StatsCard 
          title="Présences Aujourd'hui"
          :value="stats.presences_aujourd_hui || 43"
          :change="8"
          change-type="positive"
          icon="✅"
          color="from-yellow-500 to-orange-500"
          @click="navigateTo('presences.index')"
        />

        <!-- Revenus -->
        <StatsCard 
          title="Revenus du Mois"
          :value="formatMoney(stats.revenus_mois || 5750)"
          :change="15"
          change-type="positive"
          icon="💰"
          color="from-purple-500 to-pink-500"
          @click="navigateTo('paiements.index')"
        />

      </div>

      <!-- Section principale avec graphiques -->
      <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-8">
        
        <!-- Graphique évolution -->
        <div class="xl:col-span-2">
          <ChartCard title="Évolution des Présences" subtitle="7 derniers jours">
            <canvas ref="presencesChart" class="w-full h-64"></canvas>
          </ChartCard>
        </div>

        <!-- Progression ceintures -->
        <div>
          <ChartCard title="Répartition Ceintures" subtitle="Par grade">
            <div class="space-y-4">
              <div v-for="ceinture in ceinturesData" :key="ceinture.nom" 
                   class="group hover:bg-gray-700/30 rounded-lg p-3 transition-all cursor-pointer">
                <div class="flex items-center justify-between mb-2">
                  <div class="flex items-center space-x-3">
                    <div class="w-4 h-4 rounded-full" :style="{ backgroundColor: ceinture.couleur }"></div>
                    <span class="text-white font-medium">{{ ceinture.nom }}</span>
                  </div>
                  <span class="text-gray-300 font-semibold">{{ ceinture.count }}</span>
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
          </ChartCard>
        </div>

      </div>

      <!-- Actions rapides améliorées -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        
        <!-- Actions principales -->
        <ActionCard title="Actions Rapides" subtitle="Tâches courantes">
          <div class="grid grid-cols-2 gap-4">
            <ActionButton 
              icon="👤" 
              title="Nouveau Membre" 
              subtitle="Inscription rapide"
              color="bg-gradient-to-r from-blue-500 to-blue-600"
              @click="navigateTo('membres.create')"
            />
            <ActionButton 
              icon="📋" 
              title="Présences" 
              subtitle="Interface tablette"
              color="bg-gradient-to-r from-green-500 to-green-600"
              @click="navigateTo('presences.tablette')"
            />
            <ActionButton 
              icon="💳" 
              title="Paiements" 
              subtitle="Gérer finances"
              color="bg-gradient-to-r from-yellow-500 to-orange-500"
              @click="navigateTo('paiements.index')"
            />
            <ActionButton 
              icon="📅" 
              title="Planning" 
              subtitle="Horaires cours"
              color="bg-gradient-to-r from-purple-500 to-pink-500"
              @click="navigateTo('cours.planning')"
            />
          </div>
        </ActionCard>

        <!-- Notifications et alertes -->
        <ActionCard title="Notifications" subtitle="Alertes importantes">
          <div class="space-y-3">
            <NotificationItem 
              type="warning"
              title="3 Paiements en retard"
              message="Relances à envoyer cette semaine"
              @click="navigateTo('paiements.index')"
            />
            <NotificationItem 
              type="info"
              title="Nouveau membre inscrit"
              message="Marie Dubois - Ceinture blanche"
              @click="navigateTo('membres.index')"
            />
            <NotificationItem 
              type="success"
              title="Objectif atteint"
              message="250 membres inscrits ce mois !"
            />
          </div>
        </ActionCard>

      </div>

      <!-- Footer stats -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <MiniStat label="Taux de présence" value="87%" />
        <MiniStat label="Membres actifs" value="235/250" />
        <MiniStat label="Cours cette semaine" value="24" />
        <MiniStat label="Revenus objectif" value="76%" />
      </div>

    </main>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import Chart from 'chart.js/auto'

// Composants
import StatsCard from '@/Components/Dashboard/StatsCard.vue'
import ChartCard from '@/Components/Dashboard/ChartCard.vue'
import ActionCard from '@/Components/Dashboard/ActionCard.vue'
import ActionButton from '@/Components/Dashboard/ActionButton.vue'
import NotificationItem from '@/Components/Dashboard/NotificationItem.vue'
import NavLink from '@/Components/Dashboard/NavLink.vue'
import MiniStat from '@/Components/Dashboard/MiniStat.vue'

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

// Chart
const presencesChart = ref(null)

onMounted(() => {
  initChart()
})

const initChart = () => {
  const ctx = presencesChart.value.getContext('2d')
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
      datasets: [{
        label: 'Présences',
        data: [42, 38, 45, 41, 48, 35, 29],
        borderColor: 'rgb(59, 130, 246)',
        backgroundColor: 'rgba(59, 130, 246, 0.1)',
        tension: 0.4,
        fill: true
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: false
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: {
            color: 'rgba(255, 255, 255, 0.1)'
          },
          ticks: {
            color: 'rgba(255, 255, 255, 0.7)'
          }
        },
        x: {
          grid: {
            color: 'rgba(255, 255, 255, 0.1)'
          },
          ticks: {
            color: 'rgba(255, 255, 255, 0.7)'
          }
        }
      }
    }
  })
}

// Utilitaires
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

<style scoped>
/* Animations personnalisées */
.animate-fade-in {
  animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-slide-up {
  animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
  from { transform: translateY(10px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}
</style>