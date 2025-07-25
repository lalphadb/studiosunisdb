<template>
  <div class="min-h-screen bg-gray-50">
    <!-- 🎯 Sidebar Navigation -->
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg">
      <div class="flex flex-col h-full">
        <!-- Logo & Header -->
        <div class="flex items-center px-6 py-4 border-b border-gray-200">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
              <span class="text-white font-bold text-lg">🥋</span>
            </div>
            <div>
              <h1 class="text-lg font-bold text-gray-900">StudiosDB</h1>
              <p class="text-xs text-gray-500">v5 Pro</p>
            </div>
          </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-4 py-6 space-y-2">
          <a href="/dashboard" class="flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
            </svg>
            Dashboard
          </a>
          
          <a href="/membres" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Membres
            <span class="ml-auto text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded-full">{{ stats.total_membres }}</span>
          </a>

          <a href="/cours" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4"></path>
            </svg>
            Cours
            <span class="ml-auto text-xs bg-green-100 text-green-600 px-2 py-1 rounded-full">{{ stats.total_cours }}</span>
          </a>

          <a href="/presences" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Présences
            <span class="ml-auto text-xs bg-yellow-100 text-yellow-600 px-2 py-1 rounded-full">{{ stats.presences_aujourd_hui }}</span>
          </a>

          <a href="/paiements" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            </svg>
            Paiements
            <span class="ml-auto text-xs bg-red-100 text-red-600 px-2 py-1 rounded-full">{{ stats.paiements_en_retard }}</span>
          </a>

          <a href="/examens" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            Examens
          </a>

          <a href="/rapports" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Rapports
          </a>
        </nav>

        <!-- User Profile -->
        <div class="px-4 py-4 border-t border-gray-200">
          <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
              <span class="text-gray-600 text-sm font-medium">{{ user?.name?.charAt(0) || 'U' }}</span>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-gray-900 truncate">{{ user?.name || 'Utilisateur' }}</p>
              <p class="text-xs text-gray-500 truncate">{{ user?.email || 'email@exemple.com' }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 📱 Main Content -->
    <div class="pl-64 flex flex-col min-h-screen">
      <!-- Header -->
      <header class="bg-white shadow-sm border-b border-gray-200 relative z-10">
        <div class="px-6 py-4">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-2xl font-bold text-gray-900">Dashboard École de Karaté</h1>
              <p class="text-gray-600">Vue d'ensemble de votre école</p>
            </div>
            <div class="flex items-center space-x-4">
              <div class="text-right">
                <p class="text-sm font-medium text-gray-900">{{ getCurrentDate() }}</p>
                <p class="text-xs text-gray-500">{{ getCurrentTime() }}</p>
              </div>
              <div class="flex items-center space-x-2">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-green-600 text-sm font-medium">En ligne</span>
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- Dashboard Content -->
      <main class="flex-1 p-6 bg-gray-50">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <!-- Membres Total -->
          <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Membres Total</p>
                <p class="text-3xl font-bold text-gray-900">{{ stats.total_membres }}</p>
                <div class="flex items-center mt-2">
                  <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"></path>
                  </svg>
                  <span class="text-green-600 text-sm font-medium">+{{ stats.evolution_membres }}%</span>
                  <span class="text-gray-500 text-sm ml-2">ce mois</span>
                </div>
              </div>
              <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <span class="text-2xl">👥</span>
              </div>
            </div>
          </div>

          <!-- Membres Actifs -->
          <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Membres Actifs</p>
                <p class="text-3xl font-bold text-gray-900">{{ stats.membres_actifs }}</p>
                <div class="flex items-center mt-2">
                  <div class="w-24 bg-gray-200 rounded-full h-2 mr-2">
                    <div 
                      class="bg-green-500 h-2 rounded-full" 
                      :style="{ width: Math.round((stats.membres_actifs / stats.total_membres) * 100) + '%' }"
                    ></div>
                  </div>
                  <span class="text-gray-600 text-sm">{{ Math.round((stats.membres_actifs / stats.total_membres) * 100) }}%</span>
                </div>
              </div>
              <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <span class="text-2xl">⚡</span>
              </div>
            </div>
          </div>

          <!-- Présences -->
          <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Présences Aujourd'hui</p>
                <p class="text-3xl font-bold text-gray-900">{{ stats.presences_aujourd_hui }}</p>
                <div class="flex items-center mt-2">
                  <span class="text-gray-600 text-sm">Taux: {{ stats.taux_presence }}%</span>
                </div>
              </div>
              <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <span class="text-2xl">✅</span>
              </div>
            </div>
          </div>

          <!-- Revenus -->
          <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Revenus Mois</p>
                <p class="text-3xl font-bold text-gray-900">${{ stats.revenus_mois.toLocaleString() }}</p>
                <div class="flex items-center mt-2">
                  <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"></path>
                  </svg>
                  <span class="text-green-600 text-sm font-medium">+{{ stats.evolution_revenus }}%</span>
                </div>
              </div>
              <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <span class="text-2xl">💰</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <a href="/membres/create" class="bg-white border-2 border-dashed border-gray-300 rounded-xl p-6 hover:border-blue-500 hover:bg-blue-50 transition-colors group block">
            <div class="text-center">
              <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-200">
                <span class="text-2xl">👤</span>
              </div>
              <h3 class="font-semibold text-gray-900">Nouveau Membre</h3>
              <p class="text-gray-500 text-sm mt-1">Ajouter un membre</p>
            </div>
          </a>

          <a href="/presences" class="bg-white border-2 border-dashed border-gray-300 rounded-xl p-6 hover:border-green-500 hover:bg-green-50 transition-colors group block">
            <div class="text-center">
              <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:bg-green-200">
                <span class="text-2xl">📋</span>
              </div>
              <h3 class="font-semibold text-gray-900">Prendre Présences</h3>
              <p class="text-gray-500 text-sm mt-1">Mode tablette</p>
            </div>
          </a>

          <a href="/paiements" class="bg-white border-2 border-dashed border-gray-300 rounded-xl p-6 hover:border-yellow-500 hover:bg-yellow-50 transition-colors group block">
            <div class="text-center">
              <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:bg-yellow-200">
                <span class="text-2xl">💳</span>
              </div>
              <h3 class="font-semibold text-gray-900">Paiement</h3>
              <p class="text-gray-500 text-sm mt-1">{{ stats.paiements_en_retard }} en retard</p>
            </div>
          </a>

          <a href="/rapports" class="bg-white border-2 border-dashed border-gray-300 rounded-xl p-6 hover:border-purple-500 hover:bg-purple-50 transition-colors group block">
            <div class="text-center">
              <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:bg-purple-200">
                <span class="text-2xl">📊</span>
              </div>
              <h3 class="font-semibold text-gray-900">Rapport</h3>
              <p class="text-gray-500 text-sm mt-1">Génerer un rapport</p>
            </div>
          </a>
        </div>

        <!-- Charts & Tables -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Évolution des revenus -->
          <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
              <h3 class="text-lg font-semibold text-gray-900">Évolution des Revenus</h3>
              <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                <span class="text-sm text-gray-600">Cette année</span>
              </div>
            </div>
            <div class="h-64 flex items-end justify-between space-x-2">
              <div class="bg-blue-100 rounded-t flex-1 h-20"></div>
              <div class="bg-blue-200 rounded-t flex-1 h-32"></div>
              <div class="bg-blue-300 rounded-t flex-1 h-24"></div>
              <div class="bg-blue-400 rounded-t flex-1 h-40"></div>
              <div class="bg-blue-500 rounded-t flex-1 h-56"></div>
              <div class="bg-blue-600 rounded-t flex-1 h-48"></div>
            </div>
          </div>

          <!-- Répartition des ceintures -->
          <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Répartition des Ceintures</h3>
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                  <div class="w-4 h-4 bg-white border-2 border-gray-400 rounded-full"></div>
                  <span class="text-gray-700">Ceinture Blanche</span>
                </div>
                <span class="font-medium">45</span>
              </div>
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                  <div class="w-4 h-4 bg-yellow-400 rounded-full"></div>
                  <span class="text-gray-700">Ceinture Jaune</span>
                </div>
                <span class="font-medium">38</span>
              </div>
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                  <div class="w-4 h-4 bg-orange-500 rounded-full"></div>
                  <span class="text-gray-700">Ceinture Orange</span>
                </div>
                <span class="font-medium">32</span>
              </div>
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                  <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                  <span class="text-gray-700">Ceinture Verte</span>
                </div>
                <span class="font-medium">28</span>
              </div>
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                  <div class="w-4 h-4 bg-blue-600 rounded-full"></div>
                  <span class="text-gray-700">Ceinture Bleue</span>
                </div>
                <span class="font-medium">24</span>
              </div>
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                  <div class="w-4 h-4 bg-red-600 rounded-full"></div>
                  <span class="text-gray-700">Ceinture Rouge</span>
                </div>
                <span class="font-medium">18</span>
              </div>
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                  <div class="w-4 h-4 bg-gray-900 rounded-full"></div>
                  <span class="text-gray-700">Ceinture Noire</span>
                </div>
                <span class="font-medium">12</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Activity -->
        <div class="mt-8">
          <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-semibold text-gray-900">Activité Récente</h3>
            </div>
            <div class="p-6">
              <div class="space-y-4">
                <div class="flex items-center space-x-4">
                  <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <span class="text-blue-600 text-sm">👤</span>
                  </div>
                  <div class="flex-1">
                    <p class="text-gray-900 font-medium">Marie Dubois a rejoint l'école</p>
                    <p class="text-gray-500 text-sm">Il y a 15 minutes</p>
                  </div>
                </div>
                <div class="flex items-center space-x-4">
                  <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                    <span class="text-green-600 text-sm">✅</span>
                  </div>
                  <div class="flex-1">
                    <p class="text-gray-900 font-medium">Cours de karaté enfants terminé</p>
                    <p class="text-gray-500 text-sm">Il y a 2 heures</p>
                  </div>
                </div>
                <div class="flex items-center space-x-4">
                  <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                    <span class="text-yellow-600 text-sm">💳</span>
                  </div>
                  <div class="flex-1">
                    <p class="text-gray-900 font-medium">Paiement reçu de Jean Martin</p>
                    <p class="text-gray-500 text-sm">Il y a 3 heures</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'

const props = defineProps({
  user: Object,
  stats: Object
})

const getCurrentDate = () => {
  return new Date().toLocaleDateString('fr-FR', { 
    weekday: 'long', 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric' 
  })
}

const getCurrentTime = () => {
  return new Date().toLocaleTimeString('fr-FR', { 
    hour: '2-digit', 
    minute: '2-digit' 
  })
}

onMounted(() => {
  console.log('🚀 Dashboard Moderne chargé', props.stats)
})
</script>
