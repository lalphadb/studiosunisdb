<template>
  <AuthenticatedLayout :stats="stats">
    <template #header>
      <div class="flex justify-between items-center">
        <div>
          <h2 class="font-semibold text-xl text-white leading-tight">
            Dashboard StudiosDB v5 Pro
          </h2>
          <p class="text-gray-400 text-sm mt-1">École de Karaté - Vue d'ensemble complète</p>
        </div>
        <div class="flex items-center space-x-3">
          <div class="text-right">
            <p class="text-white text-sm font-medium">{{ getCurrentDate() }}</p>
            <p class="text-gray-400 text-xs">Dernière mise à jour: {{ getCurrentTime() }}</p>
          </div>
          <div class="flex items-center space-x-2">
            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
            <span class="text-green-400 text-xs font-medium">En ligne</span>
            <button @click="refreshData" class="ml-2 p-1 rounded-full text-gray-400 hover:text-white hover:bg-gray-700/50 transition-all" title="Actualiser les données">
              <svg class="w-4 h-4" :class="{ 'animate-spin': isRefreshing }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <!-- 📊 Statistiques principales avec animations -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          
          <!-- Membres Total -->
          <div class="group relative overflow-hidden rounded-xl p-6 cursor-pointer hover:scale-105 transition-all duration-300 hover:shadow-xl bg-gradient-to-br from-blue-500 to-blue-600 border border-white/10 shadow-lg">
            <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative z-10">
              <div class="flex items-center justify-between mb-4">
                <div class="text-3xl">👥</div>
                <div class="flex items-center space-x-1 text-sm">
                  <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"></path>
                  </svg>
                  <span class="text-green-300">+{{ stats.evolution_membres }}%</span>
                </div>
              </div>
              <div>
                <p class="text-3xl font-bold text-white mb-2">{{ stats.total_membres }}</p>
                <p class="text-white/80 text-sm font-medium">Membres Total</p>
              </div>
            </div>
            <div class="absolute -top-2 -right-2 w-16 h-16 bg-white/10 rounded-full"></div>
            <div class="absolute -bottom-4 -left-4 w-12 h-12 bg-white/5 rounded-full"></div>
          </div>

          <!-- Membres Actifs -->
          <div class="group relative overflow-hidden rounded-xl p-6 cursor-pointer hover:scale-105 transition-all duration-300 hover:shadow-xl bg-gradient-to-br from-green-500 to-green-600 border border-white/10 shadow-lg">
            <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative z-10">
              <div class="flex items-center justify-between mb-4">
                <div class="text-3xl">⚡</div>
                <div class="text-green-300 text-sm">{{ Math.round((stats.membres_actifs / stats.total_membres) * 100) }}%</div>
              </div>
              <div>
                <p class="text-3xl font-bold text-white mb-2">{{ stats.membres_actifs }}</p>
                <p class="text-white/80 text-sm font-medium">Membres Actifs</p>
              </div>
            </div>
            <div class="absolute -top-2 -right-2 w-16 h-16 bg-white/10 rounded-full"></div>
            <div class="absolute -bottom-4 -left-4 w-12 h-12 bg-white/5 rounded-full"></div>
          </div>

          <!-- Présences -->
          <div class="group relative overflow-hidden rounded-xl p-6 cursor-pointer hover:scale-105 transition-all duration-300 hover:shadow-xl bg-gradient-to-br from-yellow-500 to-orange-500 border border-white/10 shadow-lg">
            <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative z-10">
              <div class="flex items-center justify-between mb-4">
                <div class="text-3xl">✅</div>
                <div class="text-green-300 text-sm">Aujourd'hui</div>
              </div>
              <div>
                <p class="text-3xl font-bold text-white mb-2">{{ stats.presences_aujourd_hui }}</p>
                <p class="text-white/80 text-sm font-medium">Présences</p>
              </div>
            </div>
            <div class="absolute -top-2 -right-2 w-16 h-16 bg-white/10 rounded-full"></div>
            <div class="absolute -bottom-4 -left-4 w-12 h-12 bg-white/5 rounded-full"></div>
          </div>

          <!-- Revenus -->
          <div class="group relative overflow-hidden rounded-xl p-6 cursor-pointer hover:scale-105 transition-all duration-300 hover:shadow-xl bg-gradient-to-br from-purple-500 to-purple-600 border border-white/10 shadow-lg">
            <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative z-10">
              <div class="flex items-center justify-between mb-4">
                <div class="text-3xl">💰</div>
                <div class="flex items-center space-x-1 text-sm">
                  <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"></path>
                  </svg>
                  <span class="text-green-300">+{{ stats.evolution_revenus }}%</span>
                </div>
              </div>
              <div>
                <p class="text-3xl font-bold text-white mb-2">{{ formatMoney(stats.revenus_mois) }}</p>
                <p class="text-white/80 text-sm font-medium">Revenus ce Mois</p>
              </div>
            </div>
            <div class="absolute -top-2 -right-2 w-16 h-16 bg-white/10 rounded-full"></div>
            <div class="absolute -bottom-4 -left-4 w-12 h-12 bg-white/5 rounded-full"></div>
          </div>

        </div>

        <!-- 📈 Section graphiques et actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          
          <!-- Graphique revenus -->
          <div class="lg:col-span-2 bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6 hover:bg-gray-800/70 transition-all">
            <div class="mb-6">
              <div class="flex items-center justify-between">
                <div>
                  <h3 class="text-xl font-bold text-white">Évolution des Revenus</h3>
                  <p class="text-gray-400 text-sm mt-1">6 derniers mois en CAD$</p>
                </div>
                <div class="flex items-center space-x-2">
                  <button class="p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                    </svg>
                  </button>
                </div>
              </div>
            </div>
            
            <!-- Graphique SVG amélioré -->
            <div class="relative h-64">
              <svg class="w-full h-full" viewBox="0 0 600 300">
                <!-- Grille -->
                <defs>
                  <pattern id="grid" width="60" height="30" patternUnits="userSpaceOnUse">
                    <path d="M 60 0 L 0 0 0 30" fill="none" stroke="rgba(75, 85, 99, 0.3)" stroke-width="1"/>
                  </pattern>
                  <linearGradient id="areaGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" style="stop-color:#3B82F6;stop-opacity:0.3" />
                    <stop offset="100%" style="stop-color:#3B82F6;stop-opacity:0" />
                  </linearGradient>
                </defs>
                <rect width="600" height="300" fill="url(#grid)"/>
                
                <!-- Zone de remplissage -->
                <path 
                  :d="revenusAreaPath"
                  fill="url(#areaGradient)"
                />
                
                <!-- Ligne principale -->
                <path 
                  :d="revenusLinePath"
                  fill="none" 
                  stroke="#3B82F6" 
                  stroke-width="3"
                  class="drop-shadow-lg"
                />
                
                <!-- Points de données -->
                <circle 
                  v-for="(point, index) in revenusData" 
                  :key="index"
                  :cx="(index / (revenusData.length - 1)) * 600"
                  :cy="300 - ((point - 4000) / 2000) * 300"
                  r="6"
                  fill="#3B82F6"
                  class="hover:r-8 transition-all cursor-pointer drop-shadow-md"
                >
                  <title>{{ revenusLabels[index] }}: {{ formatMoney(point) }}</title>
                </circle>
              </svg>
              
              <!-- Labels des mois -->
              <div class="flex justify-between mt-4 text-xs text-gray-400">
                <span v-for="label in revenusLabels" :key="label">{{ label }}</span>
              </div>
            </div>
          </div>

          <!-- Actions rapides améliorées -->
          <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6 hover:bg-gray-800/70 transition-all">
            <div class="mb-6">
              <h3 class="text-lg font-semibold text-white">Actions Rapides</h3>
              <p class="text-gray-400 text-sm">Raccourcis essentiels</p>
            </div>
            
            <div class="space-y-3">
              <button @click="navigateTo('membres.create')" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg transition-all duration-200 flex items-center space-x-3 group">
                <span class="text-lg group-hover:scale-110 transition-transform">👤</span>
                <div class="text-left flex-1">
                  <div class="font-medium">Nouveau Membre</div>
                  <div class="text-blue-200 text-xs">Inscrire un élève</div>
                </div>
                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
              </button>
              
              <button @click="navigateTo('presences.tablette')" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg transition-all duration-200 flex items-center space-x-3 group">
                <span class="text-lg group-hover:scale-110 transition-transform">📋</span>
                <div class="text-left flex-1">
                  <div class="font-medium">Prendre Présences</div>
                  <div class="text-green-200 text-xs">Mode tablette</div>
                </div>
                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
              </button>
              
              <button @click="navigateTo('paiements.index')" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white py-3 px-4 rounded-lg transition-all duration-200 flex items-center space-x-3 group">
                <span class="text-lg group-hover:scale-110 transition-transform">💳</span>
                <div class="text-left flex-1">
                  <div class="font-medium">Gérer Paiements</div>
                  <div class="text-yellow-200 text-xs">{{ stats.paiements_en_retard }} en retard</div>
                </div>
                <div class="w-2 h-2 bg-red-400 rounded-full animate-pulse"></div>
              </button>
              
              <button @click="navigateTo('cours.planning')" class="w-full bg-purple-600 hover:bg-purple-700 text-white py-3 px-4 rounded-lg transition-all duration-200 flex items-center space-x-3 group">
                <span class="text-lg group-hover:scale-110 transition-transform">📅</span>
                <div class="text-left flex-1">
                  <div class="font-medium">Planning</div>
                  <div class="text-purple-200 text-xs">{{ stats.total_cours }} cours actifs</div>
                </div>
                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- 🏆 Section détaillée -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          
          <!-- Répartition ceintures avec design moderne -->
          <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6 hover:bg-gray-800/70 transition-all">
            <div class="mb-6">
              <h3 class="text-lg font-semibold text-white">Répartition des Ceintures</h3>
              <p class="text-gray-400 text-sm">Distribution par grade de karaté</p>
            </div>
            
            <div class="space-y-4">
              <div v-for="(ceinture, index) in ceinturesData" :key="ceinture.nom" class="group">
                <div class="flex items-center justify-between mb-2">
                  <div class="flex items-center space-x-3">
                    <div 
                      class="w-5 h-5 rounded-full border-2 border-gray-600 group-hover:scale-110 transition-transform"
                      :style="{ backgroundColor: ceinture.couleur }"
                    ></div>
                    <span class="text-white font-medium">{{ ceinture.nom }}</span>
                    <span class="text-xs text-gray-500">({{ Math.round((ceinture.count / totalCeintures) * 100) }}%)</span>
                  </div>
                  <span class="text-gray-300 text-sm font-semibold">{{ ceinture.count }}</span>
                </div>
                <div class="w-full bg-gray-700 rounded-full h-3 mb-1">
                  <div 
                    class="h-3 rounded-full transition-all duration-1000 ease-out relative overflow-hidden"
                    :style="{ 
                      width: ((ceinture.count / maxCeintures) * 100) + '%',
                      backgroundColor: ceinture.couleur,
                      transitionDelay: (index * 100) + 'ms'
                    }"
                  >
                    <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Activité récente moderne -->
          <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6 hover:bg-gray-800/70 transition-all">
            <div class="mb-6">
              <h3 class="text-lg font-semibold text-white">Activité Récente</h3>
              <p class="text-gray-400 text-sm">Dernières actions importantes</p>
            </div>
            
            <div class="space-y-4">
              <!-- Nouveau membre -->
              <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-700/30 transition-all cursor-pointer group">
                <div class="w-10 h-10 rounded-full bg-green-500/20 text-green-400 flex items-center justify-center flex-shrink-0">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                </div>
                <div class="flex-1">
                  <p class="text-white font-medium text-sm group-hover:text-green-400 transition-colors">Nouveau membre inscrit</p>
                  <p class="text-gray-400 text-xs">Marie Dubois - Ceinture blanche</p>
                  <p class="text-gray-500 text-xs mt-1">Il y a 15 min</p>
                </div>
                <div class="w-2 h-2 bg-green-400 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
              </div>
              
              <!-- Paiement reçu -->
              <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-700/30 transition-all cursor-pointer group">
                <div class="w-10 h-10 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center flex-shrink-0">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                  </svg>
                </div>
                <div class="flex-1">
                  <p class="text-white font-medium text-sm group-hover:text-blue-400 transition-colors">Paiement reçu</p>
                  <p class="text-gray-400 text-xs">Jean Martin - 85$ (Octobre 2025)</p>
                  <p class="text-gray-500 text-xs mt-1">Il y a 32 min</p>
                </div>
              </div>
              
              <!-- Paiement en retard -->
              <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-700/30 transition-all cursor-pointer group">
                <div class="w-10 h-10 rounded-full bg-yellow-500/20 text-yellow-400 flex items-center justify-center flex-shrink-0">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                  </svg>
                </div>
                <div class="flex-1">
                  <p class="text-white font-medium text-sm group-hover:text-yellow-400 transition-colors">Paiements en retard</p>
                  <p class="text-gray-400 text-xs">{{ stats.paiements_en_retard }} membres en souffrance</p>
                  <p class="text-gray-500 text-xs mt-1">Il y a 1h</p>
                </div>
                <div class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></div>
              </div>
              
              <!-- Objectif atteint -->
              <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-700/30 transition-all cursor-pointer group">
                <div class="w-10 h-10 rounded-full bg-purple-500/20 text-purple-400 flex items-center justify-center flex-shrink-0">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                  </svg>
                </div>
                <div class="flex-1">
                  <p class="text-white font-medium text-sm group-hover:text-purple-400 transition-colors">Objectif atteint!</p>
                  <p class="text-gray-400 text-xs">{{ stats.total_membres }} membres inscrits ce mois</p>
                  <p class="text-gray-500 text-xs mt-1">Il y a 2h</p>
                </div>
                <div class="w-2 h-2 bg-purple-400 rounded-full animate-pulse"></div>
              </div>
            </div>
          </div>
        </div>

        <!-- 🎯 Objectifs avec design premium -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
          
          <!-- Objectif Membres -->
          <div class="bg-gradient-to-br from-gray-800/50 to-gray-900/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6 hover:from-gray-700/50 hover:to-gray-800/50 transition-all duration-300">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-white font-semibold flex items-center">
                <span class="mr-2">🎯</span>
                Objectif Membres
              </h3>
              <span class="text-blue-400 text-sm font-bold">{{ getPercentage(stats.total_membres, stats.objectif_membres) }}%</span>
            </div>
            <div class="mb-4">
              <div class="w-full bg-gray-700 rounded-full h-4 overflow-hidden">
                <div 
                  class="bg-gradient-to-r from-blue-500 to-blue-400 h-4 rounded-full transition-all duration-1000 relative"
                  :style="{ width: getPercentage(stats.total_membres, stats.objectif_membres) + '%' }"
                >
                  <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                </div>
              </div>
            </div>
            <p class="text-gray-400 text-sm">{{ stats.total_membres }}/{{ stats.objectif_membres }} membres</p>
            <p class="text-xs text-blue-400 mt-1">+{{ stats.evolution_membres }}% ce mois</p>
          </div>

          <!-- Taux de Présence -->
          <div class="bg-gradient-to-br from-gray-800/50 to-gray-900/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6 hover:from-gray-700/50 hover:to-gray-800/50 transition-all duration-300">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-white font-semibold flex items-center">
                <span class="mr-2">📊</span>
                Taux Présence
              </h3>
              <span class="text-green-400 text-sm font-bold">{{ stats.taux_presence }}%</span>
            </div>
            <div class="mb-4">
              <div class="w-full bg-gray-700 rounded-full h-4 overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-green-400 h-4 rounded-full transition-all duration-1000 relative" :style="{ width: stats.taux_presence + '%' }">
                  <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                </div>
              </div>
            </div>
            <p class="text-gray-400 text-sm">Excellent engagement</p>
            <p class="text-xs text-green-400 mt-1">{{ stats.presences_aujourd_hui }} présents aujourd'hui</p>
          </div>

          <!-- Revenus Objectif -->
          <div class="bg-gradient-to-br from-gray-800/50 to-gray-900/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6 hover:from-gray-700/50 hover:to-gray-800/50 transition-all duration-300">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-white font-semibold flex items-center">
                <span class="mr-2">💎</span>
                Revenus
              </h3>
              <span class="text-yellow-400 text-sm font-bold">{{ getPercentage(stats.revenus_mois, stats.objectif_revenus) }}%</span>
            </div>
            <div class="mb-4">
              <div class="w-full bg-gray-700 rounded-full h-4 overflow-hidden">
                <div 
                  class="bg-gradient-to-r from-yellow-500 to-yellow-400 h-4 rounded-full transition-all duration-1000 relative"
                  :style="{ width: getPercentage(stats.revenus_mois, stats.objectif_revenus) + '%' }"
                >
                  <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                </div>
              </div>
            </div>
            <p class="text-gray-400 text-sm">{{ formatMoney(stats.revenus_mois) }}/{{ formatMoney(stats.objectif_revenus) }}</p>
            <p class="text-xs text-yellow-400 mt-1">+{{ stats.evolution_revenus }}% ce mois</p>
          </div>

          <!-- Satisfaction -->
          <div class="bg-gradient-to-br from-gray-800/50 to-gray-900/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6 hover:from-gray-700/50 hover:to-gray-800/50 transition-all duration-300">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-white font-semibold flex items-center">
                <span class="mr-2">⭐</span>
                Satisfaction
              </h3>
              <span class="text-purple-400 text-sm font-bold">{{ stats.satisfaction_moyenne }}%</span>
            </div>
            <div class="mb-4">
              <div class="w-full bg-gray-700 rounded-full h-4 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-purple-400 h-4 rounded-full transition-all duration-1000 relative" :style="{ width: stats.satisfaction_moyenne + '%' }">
                  <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                </div>
              </div>
            </div>
            <p class="text-gray-400 text-sm">Retours très positifs</p>
            <p class="text-xs text-purple-400 mt-1">Enquête {{ stats.total_membres }} réponses</p>
          </div>
        </div>

      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { computed, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  user: Object,
  stats: Object
})

// State pour le rafraîchissement
const isRefreshing = ref(false)

// Données pour les ceintures
const ceinturesData = [
  { nom: 'Blanc', count: 85, couleur: '#F8FAFC' },
  { nom: 'Jaune', count: 72, couleur: '#FEF08A' },
  { nom: 'Orange', count: 45, couleur: '#FDBA74' },
  { nom: 'Vert', count: 28, couleur: '#86EFAC' },
  { nom: 'Bleu', count: 15, couleur: '#93C5FD' },
  { nom: 'Marron', count: 8, couleur: '#D2B48C' },
  { nom: 'Noir', count: 3, couleur: '#374151' }
]

// Données pour le graphique
const revenusData = [4200, 4800, 5100, 5400, 5650, 5850]
const revenusLabels = ['Mai', 'Juin', 'Juillet', 'Août', 'Sept', 'Oct']

// Computed
const maxCeintures = Math.max(...ceinturesData.map(c => c.count))
const totalCeintures = ceinturesData.reduce((sum, c) => sum + c.count, 0)

const revenusLinePath = computed(() => {
  const minValue = Math.min(...revenusData)
  const maxValue = Math.max(...revenusData)
  const range = maxValue - minValue || 1
  
  let path = ''
  revenusData.forEach((value, index) => {
    const x = (index / (revenusData.length - 1)) * 600
    const y = 300 - ((value - minValue) / range) * 300
    
    if (index === 0) {
      path += `M ${x} ${y}`
    } else {
      path += ` L ${x} ${y}`
    }
  })
  
  return path
})

const revenusAreaPath = computed(() => {
  const linePath = revenusLinePath.value
  const lastX = revenusData.length > 1 ? 600 : 0
  
  return `${linePath} L ${lastX} 300 L 0 300 Z`
})

// Utilitaires
const formatMoney = (amount) => {
  return new Intl.NumberFormat('fr-CA', {
    style: 'currency',
    currency: 'CAD',
    minimumFractionDigits: 0
  }).format(amount)
}

const getCurrentDate = () => {
  return new Intl.DateTimeFormat('fr-CA', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  }).format(new Date())
}

const getCurrentTime = () => {
  return new Intl.DateTimeFormat('fr-CA', {
    hour: '2-digit',
    minute: '2-digit'
  }).format(new Date())
}

const getPercentage = (current, target) => {
  return Math.min(Math.round((current / target) * 100), 100)
}

const navigateTo = (routeName, params = {}) => {
  try {
    router.visit(route(routeName, params))
  } catch (error) {
    console.warn(`Route ${routeName} non trouvée`, error)
  }
}

const refreshData = async () => {
  isRefreshing.value = true
  
  try {
    router.reload({ 
      only: ['stats'],
      onFinish: () => {
        isRefreshing.value = false
      }
    })
  } catch (error) {
    console.error('Erreur lors du rafraîchissement:', error)
    isRefreshing.value = false
  }
}
</script>
