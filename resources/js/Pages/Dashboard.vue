<template>
  <AuthenticatedLayout>
    <!-- Background gradient sombre -->
    <div class="fixed inset-0 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 -z-10"></div>
    
    <!-- Pattern overlay -->
    <div class="fixed inset-0 opacity-20 -z-10" style="background-image: url('data:image/svg+xml,%3Csvg width=%2260%22 height=%2260%22 viewBox=%220 0 60 60%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cg fill=%22none%22 fill-rule=%22evenodd%22%3E%3Cg fill=%22%239C92AC%22 fill-opacity=%220.03%22%3E%3Cpath d=%22M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')"></div>
    
    <div class="p-6 max-w-7xl mx-auto">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
          Tableau de bord
        </h1>
        <p class="mt-2 text-slate-400">
          Bienvenue {{ auth.user.name }} · {{ currentDate }}
        </p>
      </div>
      
      <!-- Stats principales -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <!-- Membres actifs -->
        <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 backdrop-blur-xl p-6">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500/20 to-blue-600/20 flex items-center justify-center">
              <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <span class="text-xs px-2 py-1 rounded-full bg-emerald-500/20 text-emerald-400">
              +12%
            </span>
          </div>
          <div class="text-xl sm:text-2xl xl:text-3xl font-bold text-white mb-1 truncate">{{ stats.membres_actifs }}</div>
          <div class="text-sm text-slate-400">Membres actifs</div>
          <div class="text-xs text-slate-500 mt-1">Actifs 30 derniers jours</div>
        </div>
        
        <!-- Cours actifs -->
        <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 backdrop-blur-xl p-6">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500/20 to-purple-600/20 flex items-center justify-center">
              <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
              </svg>
            </div>
            <span class="text-xs px-2 py-1 rounded-full bg-purple-500/20 text-purple-400">
              +5
            </span>
          </div>
          <div class="text-xl sm:text-2xl xl:text-3xl font-bold text-white mb-1 truncate">{{ stats.cours_actifs }}</div>
          <div class="text-sm text-slate-400">Cours actifs</div>
          <div class="text-xs text-slate-500 mt-1">Sessions planifiées</div>
        </div>
        
        <!-- Taux de présence -->
        <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 backdrop-blur-xl p-6">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500/20 to-emerald-600/20 flex items-center justify-center">
              <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <span class="text-xs px-2 py-1 rounded-full bg-emerald-500/20 text-emerald-400">
              +3%
            </span>
          </div>
          <div class="text-xl sm:text-2xl xl:text-3xl font-bold text-white mb-1 truncate">{{ stats.taux_presence }}%</div>
          <div class="text-sm text-slate-400">Taux de présence</div>
          <div class="text-xs text-slate-500 mt-1">Semaine en cours</div>
        </div>
        
        <!-- Paiements en retard -->
        <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 backdrop-blur-xl p-6">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500/20 to-amber-600/20 flex items-center justify-center">
              <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <span class="text-xs px-2 py-1 rounded-full bg-red-500/20 text-red-400">
              -2
            </span>
          </div>
          <div class="text-xl sm:text-2xl xl:text-3xl font-bold text-white mb-1 truncate">{{ stats.paiements_retard }}</div>
          <div class="text-sm text-slate-400">Paiements en retard</div>
          <div class="text-xs text-slate-500 mt-1">À relancer</div>
        </div>
        
        <!-- Revenus mois -->
        <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 backdrop-blur-xl p-6">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500/20 to-indigo-600/20 flex items-center justify-center">
              <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
              </svg>
            </div>
            <span class="text-xs px-2 py-1 rounded-full bg-emerald-500/20 text-emerald-400">
              +18%
            </span>
          </div>
          <div class="text-xl sm:text-2xl xl:text-3xl font-bold text-white mb-1 truncate">{{ formatCurrency(stats.revenus_mois) }}</div>
          <div class="text-sm text-slate-400">Revenus mois</div>
          <div class="text-xs text-slate-500 mt-1">Chiffre d'affaires</div>
        </div>
      </div>
      
      <!-- Graphiques et activité -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Graphique progression -->
        <div class="lg:col-span-2 rounded-2xl border border-slate-700/50 bg-slate-900/60 backdrop-blur-xl p-6">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-white">Progression des inscriptions</h2>
            <select class="px-4 py-2 rounded-lg bg-slate-800/50 border border-slate-700 text-slate-300 text-sm">
              <option>7 derniers jours</option>
              <option>30 derniers jours</option>
              <option>Cette année</option>
            </select>
          </div>
          
          <!-- Graphique simplifié -->
          <div class="h-64 flex items-end justify-between gap-2">
            <div v-for="(value, index) in chartData" :key="index" 
                 class="flex-1 bg-gradient-to-t from-indigo-500/50 to-purple-600/50 rounded-t-lg transition-all hover:from-indigo-500/70 hover:to-purple-600/70"
                 :style="`height: ${value}%`">
              <div class="text-center text-xs text-white mt-2">{{ value }}</div>
            </div>
          </div>
          <div class="flex justify-between mt-2 text-xs text-slate-400">
            <span>Lun</span>
            <span>Mar</span>
            <span>Mer</span>
            <span>Jeu</span>
            <span>Ven</span>
            <span>Sam</span>
            <span>Dim</span>
          </div>
        </div>
        
        <!-- Activité récente -->
        <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 backdrop-blur-xl p-6">
          <h2 class="text-xl font-semibold text-white mb-6">Activité récente</h2>
          
          <div class="space-y-4 max-h-80 overflow-y-auto scrollbar-hide">
            <div v-for="activity in recentActivities" :key="activity.id"
                 class="flex items-start gap-3 p-3 rounded-xl hover:bg-slate-800/30 transition cursor-pointer group">
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
      
      <!-- Actions rapides et Rappels -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Actions rapides -->
        <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 backdrop-blur-xl p-6">
          <h2 class="text-xl font-semibold text-white mb-6">Actions rapides</h2>
          
          <div class="grid grid-cols-2 gap-4">
            <Link href="/membres/create"
                  class="p-4 rounded-xl bg-slate-800/50 hover:bg-slate-700/50 border border-slate-700 transition group cursor-pointer">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500/20 to-blue-600/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                  <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                  </svg>
                </div>
                <div>
                  <div class="text-sm font-medium text-white">Nouveau membre</div>
                  <div class="text-xs text-slate-400">Inscription</div>
                </div>
              </div>
            </Link>
            
            <Link href="/presences"
                  class="p-4 rounded-xl bg-slate-800/50 hover:bg-slate-700/50 border border-slate-700 transition group cursor-pointer">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-emerald-500/20 to-emerald-600/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                  <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                  </svg>
                </div>
                <div>
                  <div class="text-sm font-medium text-white">Présences</div>
                  <div class="text-xs text-slate-400">Prendre présences</div>
                </div>
              </div>
            </Link>
            
            <Link href="/cours"
                  class="p-4 rounded-xl bg-slate-800/50 hover:bg-slate-700/50 border border-slate-700 transition group cursor-pointer">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500/20 to-purple-600/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                  <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
                <div>
                  <div class="text-sm font-medium text-white">Cours</div>
                  <div class="text-xs text-slate-400">Gérer planning</div>
                </div>
              </div>
            </Link>
            
            <a href="/utilisateurs"
               class="p-4 rounded-xl bg-slate-800/50 hover:bg-slate-700/50 border border-slate-700 transition group cursor-pointer">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500/20 to-indigo-600/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                  <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                  </svg>
                </div>
                <div>
                  <div class="text-sm font-medium text-white">Utilisateurs</div>
                  <div class="text-xs text-slate-400">Gérer accès</div>
                </div>
              </div>
            </a>
          </div>
        </div>
        
        <!-- Rappels -->
        <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 backdrop-blur-xl p-6">
          <h2 class="text-xl font-semibold text-white mb-6">Rappels</h2>
          
          <div class="space-y-3">
            <div class="flex items-center gap-3 p-3 rounded-xl bg-amber-500/10 border border-amber-500/30">
              <div class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></div>
              <p class="text-sm text-slate-300">
                <span class="font-medium text-amber-400">{{ stats.paiements_retard }}</span> paiements en retard à relancer
              </p>
            </div>
            
            <div class="flex items-center gap-3 p-3 rounded-xl bg-blue-500/10 border border-blue-500/30">
              <div class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></div>
              <p class="text-sm text-slate-300">
                Vérifier planning examens ceintures
              </p>
            </div>
            
            <div class="flex items-center gap-3 p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30">
              <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
              <p class="text-sm text-slate-300">
                Créer communication info-stage
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { UserPlusIcon, CheckIcon, CurrencyDollarIcon, ExclamationTriangleIcon } from '@heroicons/vue/24/outline'

const page = usePage()
const props = defineProps({
  stats: {
    type: Object,
    default: () => ({
      membres_actifs: 248,
      cours_actifs: 18,
      taux_presence: 92,
      paiements_retard: 7,
      revenus_mois: 12450
    })
  },
  recentActivities: {
    type: Array,
    default: () => []
  }
})

// Auth user
const auth = computed(() => page.props.auth || { user: { name: 'Utilisateur' } })

// Current date
const currentDate = computed(() => {
  return new Date().toLocaleDateString('fr-CA', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
})

// Chart data
const chartData = ref([65, 78, 90, 67, 84, 72, 95])

// Recent activities avec données par défaut
const recentActivities = computed(() => {
  if (props.recentActivities?.length > 0) {
    return props.recentActivities
  }
  
  // Données par défaut si aucune activité
  return [
    {
      id: 1,
      title: 'Nouveau membre inscrit',
      time: 'Il y a 5 minutes',
      icon: UserPlusIcon,
      color: 'bg-green-500/20'
    },
    {
      id: 2,
      title: 'Cours de karaté avancé terminé',
      time: 'Il y a 1 heure',
      icon: CheckIcon,
      color: 'bg-blue-500/20'
    },
    {
      id: 3,
      title: 'Paiement reçu - 150$',
      time: 'Il y a 2 heures',
      icon: CurrencyDollarIcon,
      color: 'bg-amber-500/20'
    },
    {
      id: 4,
      title: '2 paiements en retard',
      time: 'Il y a 3 heures',
      icon: ExclamationTriangleIcon,
      color: 'bg-red-500/20'
    }
  ]
})

// Format currency
function formatCurrency(amount) {
  return new Intl.NumberFormat('fr-CA', {
    style: 'currency',
    currency: 'CAD'
  }).format(amount)
}
</script>

<style scoped>
/* Hide scrollbar but keep functionality */
.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}
</style>
