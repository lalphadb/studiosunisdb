<template>
  <div class="min-h-screen">
    <!-- Background animé -->
    <div class="fixed inset-0 -z-10">
      <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950"></div>
      <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=%2260%22 height=%2260%22 viewBox=%220 0 60 60%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cg fill=%22none%22 fill-rule=%22evenodd%22%3E%3Cg fill=%22%239C92AC%22 fill-opacity=%220.03%22%3E%3Cpath d=%22M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')"></div>
    </div>

    <!-- Contenu principal -->
    <div class="relative px-6 py-8 max-w-7xl mx-auto">
      
      <!-- Header moderne -->
      <div class="mb-10">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
              Tableau de bord
            </h1>
            <p class="mt-2 text-slate-400">Bienvenue, {{ userName }} • {{ currentDate }}</p>
          </div>
          
          <!-- Quick actions -->
          <div class="flex gap-3">
            <button @click="refreshData" class="p-3 rounded-xl bg-slate-800/50 hover:bg-slate-700/50 text-slate-400 hover:text-white transition-all group">
              <svg class="w-5 h-5 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
              </svg>
            </button>
            <button class="px-6 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-medium transition-all transform hover:scale-105">
              <span>Nouveau cours</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Cartes de statistiques principales -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <StatCard 
          v-for="stat in mainStats" 
          :key="stat.id"
          :title="stat.title"
          :value="stat.value"
          :change="stat.change"
          :icon="stat.icon"
          :color="stat.color"
          :loading="loading"
        />
      </div>

      <!-- Section graphiques et activités -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- Graphique principal -->
        <div class="lg:col-span-2 p-6 rounded-2xl bg-slate-900/50 backdrop-blur-xl border border-slate-700/50">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-white">Progression des inscriptions</h2>
            <select class="px-4 py-2 rounded-lg bg-slate-800/50 border border-slate-700 text-slate-300 text-sm">
              <option>7 derniers jours</option>
              <option>30 derniers jours</option>
              <option>Cette année</option>
            </select>
          </div>
          
          <!-- Graphique SVG moderne -->
          <div class="h-64 relative">
            <svg class="w-full h-full" viewBox="0 0 400 200">
              <!-- Grille de fond -->
              <g opacity="0.1">
                <line v-for="i in 5" :key="i" :x1="0" :y1="i * 40" :x2="400" :y2="i * 40" stroke="white" />
              </g>
              
              <!-- Courbe animée -->
              <defs>
                <linearGradient id="gradient" x1="0%" y1="0%" x2="0%" y2="100%">
                  <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:0.8" />
                  <stop offset="100%" style="stop-color:#3b82f6;stop-opacity:0.1" />
                </linearGradient>
              </defs>
              
              <path 
                :d="chartPath"
                fill="url(#gradient)"
                class="chart-path"
              />
              
              <path 
                :d="chartLine"
                fill="none"
                stroke="#3b82f6"
                stroke-width="3"
                class="chart-line"
              />
              
              <!-- Points interactifs -->
              <g v-for="(point, index) in chartPoints" :key="index">
                <circle 
                  :cx="point.x" 
                  :cy="point.y" 
                  r="5" 
                  fill="#3b82f6"
                  class="cursor-pointer hover:r-7 transition-all"
                  @mouseenter="showTooltip(index)"
                  @mouseleave="hideTooltip"
                />
              </g>
            </svg>
          </div>
        </div>

        <!-- Activité récente -->
        <div class="p-6 rounded-2xl bg-slate-900/50 backdrop-blur-xl border border-slate-700/50">
          <h2 class="text-xl font-semibold text-white mb-6">Activité récente</h2>
          
          <div class="space-y-4 max-h-80 overflow-y-auto scrollbar-hide">
            <div v-for="activity in recentActivities" :key="activity.id" 
                 class="flex items-start gap-3 p-3 rounded-xl hover:bg-slate-800/30 transition-all cursor-pointer group">
              <div :class="`p-2 rounded-lg ${activity.color} group-hover:scale-110 transition-transform`">
                <component :is="activity.icon" class="w-4 h-4 text-white" />
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm text-white font-medium truncate">{{ activity.title }}</p>
                <p class="text-xs text-slate-400">{{ activity.time }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Cours à venir -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Prochains cours -->
        <div class="p-6 rounded-2xl bg-slate-900/50 backdrop-blur-xl border border-slate-700/50">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-white">Prochains cours</h2>
            <button class="text-sm text-blue-400 hover:text-blue-300 transition-colors">Voir tout →</button>
          </div>
          
          <div class="space-y-3">
            <div v-for="cours in upcomingCours" :key="cours.id"
                 class="p-4 rounded-xl bg-slate-800/30 hover:bg-slate-800/50 transition-all cursor-pointer group">
              <div class="flex items-center justify-between">
                <div>
                  <h3 class="font-medium text-white group-hover:text-blue-400 transition-colors">
                    {{ cours.name }}
                  </h3>
                  <div class="flex items-center gap-4 mt-2 text-xs text-slate-400">
                    <span class="flex items-center gap-1">
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      {{ cours.time }}
                    </span>
                    <span class="flex items-center gap-1">
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                      </svg>
                      {{ cours.students }} élèves
                    </span>
                  </div>
                </div>
                <div :class="`px-3 py-1 rounded-full text-xs font-medium ${cours.levelColor}`">
                  {{ cours.level }}
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Performance du mois -->
        <div class="p-6 rounded-2xl bg-slate-900/50 backdrop-blur-xl border border-slate-700/50">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-white">Performance du mois</h2>
            <span class="text-sm text-slate-400">Novembre 2024</span>
          </div>
          
          <div class="space-y-4">
            <div v-for="metric in performanceMetrics" :key="metric.id">
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-slate-300">{{ metric.label }}</span>
                <span class="text-sm font-medium text-white">{{ metric.value }}%</span>
              </div>
              <div class="h-2 bg-slate-800 rounded-full overflow-hidden">
                <div 
                  :class="`h-full rounded-full transition-all duration-1000 ${metric.color}`"
                  :style="`width: ${metric.value}%`"
                ></div>
              </div>
            </div>
          </div>
          
          <!-- Call to action -->
          <div class="mt-6 p-4 rounded-xl bg-gradient-to-r from-blue-600/20 to-purple-600/20 border border-blue-500/30">
            <p class="text-sm text-blue-300 mb-2">Objectif du mois</p>
            <p class="text-white font-medium">Atteindre 50 nouveaux membres</p>
            <div class="mt-3 flex items-center gap-2">
              <div class="flex-1 h-2 bg-slate-700 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-blue-500 to-purple-500 rounded-full" style="width: 76%"></div>
              </div>
              <span class="text-xs text-slate-400">38/50</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { ref, computed, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'

export default {
  layout: AuthenticatedLayout,
  
  setup() {
    const page = usePage()
    const loading = ref(false)
    
    const userName = computed(() => page.props.auth?.user?.name || 'Utilisateur')
    const currentDate = computed(() => {
      return new Date().toLocaleDateString('fr-FR', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
      })
    })
    
    // Statistiques principales
    const mainStats = ref([
      {
        id: 1,
        title: 'Membres actifs',
        value: '248',
        change: '+12%',
        icon: 'users',
        color: 'from-blue-500 to-blue-600'
      },
      {
        id: 2,
        title: 'Cours cette semaine',
        value: '18',
        change: '+5%',
        icon: 'calendar',
        color: 'from-purple-500 to-purple-600'
      },
      {
        id: 3,
        title: 'Taux de présence',
        value: '92%',
        change: '+3%',
        icon: 'chart',
        color: 'from-emerald-500 to-emerald-600'
      },
      {
        id: 4,
        title: 'Revenus du mois',
        value: '12.4k$',
        change: '+18%',
        icon: 'dollar',
        color: 'from-amber-500 to-amber-600'
      }
    ])
    
    // Données du graphique
    const chartPath = ref('M 0 160 Q 50 140 100 120 T 200 100 T 300 80 T 400 60 L 400 200 L 0 200 Z')
    const chartLine = ref('M 0 160 Q 50 140 100 120 T 200 100 T 300 80 T 400 60')
    const chartPoints = ref([
      { x: 0, y: 160 },
      { x: 100, y: 120 },
      { x: 200, y: 100 },
      { x: 300, y: 80 },
      { x: 400, y: 60 }
    ])
    
    // Activités récentes
    const recentActivities = ref([
      {
        id: 1,
        title: 'Nouveau membre inscrit',
        time: 'Il y a 5 minutes',
        icon: 'UserPlusIcon',
        color: 'bg-green-500/20'
      },
      {
        id: 2,
        title: 'Cours de karaté avancé terminé',
        time: 'Il y a 1 heure',
        icon: 'CheckIcon',
        color: 'bg-blue-500/20'
      },
      {
        id: 3,
        title: 'Paiement reçu - 150$',
        time: 'Il y a 2 heures',
        icon: 'CurrencyDollarIcon',
        color: 'bg-amber-500/20'
      },
      {
        id: 4,
        title: 'Examen de ceinture planifié',
        time: 'Il y a 3 heures',
        icon: 'CalendarIcon',
        color: 'bg-purple-500/20'
      }
    ])
    
    // Prochains cours
    const upcomingCours = ref([
      {
        id: 1,
        name: 'Karaté débutant',
        time: '14:00 - 15:30',
        students: 12,
        level: 'Ceinture blanche',
        levelColor: 'bg-slate-600 text-white'
      },
      {
        id: 2,
        name: 'Karaté intermédiaire',
        time: '16:00 - 17:30',
        students: 18,
        level: 'Ceinture verte',
        levelColor: 'bg-green-600 text-white'
      },
      {
        id: 3,
        name: 'Karaté avancé',
        time: '18:00 - 19:30',
        students: 8,
        level: 'Ceinture noire',
        levelColor: 'bg-slate-900 text-white'
      }
    ])
    
    // Métriques de performance
    const performanceMetrics = ref([
      {
        id: 1,
        label: 'Taux de rétention',
        value: 95,
        color: 'bg-gradient-to-r from-emerald-500 to-emerald-600'
      },
      {
        id: 2,
        label: 'Satisfaction des membres',
        value: 88,
        color: 'bg-gradient-to-r from-blue-500 to-blue-600'
      },
      {
        id: 3,
        label: 'Objectifs atteints',
        value: 76,
        color: 'bg-gradient-to-r from-purple-500 to-purple-600'
      },
      {
        id: 4,
        label: 'Croissance mensuelle',
        value: 92,
        color: 'bg-gradient-to-r from-amber-500 to-amber-600'
      }
    ])
    
    const refreshData = () => {
      loading.value = true
      setTimeout(() => {
        loading.value = false
      }, 1000)
    }
    
    const showTooltip = (index) => {
      // Logique pour afficher le tooltip
    }
    
    const hideTooltip = () => {
      // Logique pour cacher le tooltip
    }
    
    onMounted(() => {
      // Animation d'entrée
      setTimeout(() => {
        loading.value = false
      }, 500)
    })
    
    return {
      userName,
      currentDate,
      loading,
      mainStats,
      chartPath,
      chartLine,
      chartPoints,
      recentActivities,
      upcomingCours,
      performanceMetrics,
      refreshData,
      showTooltip,
      hideTooltip
    }
  }
}
</script>

<style scoped>
/* Animations */
@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.chart-path {
  animation: slideUp 1s ease-out;
}

.chart-line {
  stroke-dasharray: 1000;
  stroke-dashoffset: 1000;
  animation: draw 2s ease-out forwards;
}

@keyframes draw {
  to {
    stroke-dashoffset: 0;
  }
}

/* Hide scrollbar but keep functionality */
.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

.scrollbar-hide::-webkit-scrollbar {
  display: none;
}
</style>
