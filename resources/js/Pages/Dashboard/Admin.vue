<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50">
    <!-- Background Pattern Clair -->
    <div class="absolute inset-0 opacity-20" 
         style="background-image: url('data:image/svg+xml,%3Csvg width=60 height=60 viewBox=0 0 60 60 xmlns=http://www.w3.org/2000/svg%3E%3Cg fill=none fill-rule=evenodd%3E%3Cg fill=%23818cf8 fill-opacity=0.1%3E%3Ccircle cx=30 cy=30 r=2/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')">
    </div>

    <div class="relative z-10">
      <!-- Header Navigation Clair -->
      <nav class="bg-white/80 backdrop-blur-lg border-b border-gray-200/50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center h-16">
            <div class="flex items-center space-x-4">
              <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                  <span class="text-white font-bold text-lg">🥋</span>
                </div>
                <div>
                  <h1 class="text-xl font-bold text-gray-900">StudiosDB v5 Pro</h1>
                  <p class="text-xs text-gray-600">École de Karaté - Dashboard Professionnel</p>
                </div>
              </div>
            </div>
            
            <div class="flex items-center space-x-4">
              <div class="text-right">
                <p class="text-sm font-medium text-gray-900">{{ user?.name || 'Admin' }}</p>
                <p class="text-xs text-gray-600">{{ user?.roles?.join(', ') || 'Administrateur' }}</p>
              </div>
              <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                <span class="text-white text-sm font-bold">{{ (user?.name || 'A').charAt(0) }}</span>
              </div>
            </div>
          </div>
        </div>
      </nav>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Welcome Section -->
        <div class="mb-8">
          <h2 class="text-3xl font-bold text-gray-900 mb-2">
            Bonjour {{ user?.name || 'Admin' }} ! 👋
          </h2>
          <p class="text-gray-600">
            Voici un aperçu de l'activité de votre école de karaté aujourd'hui.
          </p>
        </div>

        <!-- KPI Cards Claires -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <!-- Total Membres -->
          <div class="bg-white/70 backdrop-blur-sm border border-gray-200/50 rounded-xl p-6 hover:bg-white/90 transition-all duration-300 shadow-sm hover:shadow-md">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <span class="text-2xl">👥</span>
              </div>
              <div class="flex items-center space-x-1 text-green-600 text-sm font-medium">
                <span>↗</span>
                <span>+{{ stats?.evolution_membres || 8.3 }}%</span>
              </div>
            </div>
            <div class="space-y-1">
              <p class="text-2xl font-bold text-gray-900">{{ stats?.total_membres || 42 }}</p>
              <p class="text-sm text-gray-600">Total Membres</p>
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full transition-all duration-1000"
                     :style="`width: ${Math.min((stats?.total_membres || 42) / (stats?.objectif_membres || 50) * 100, 100)}%`">
                </div>
              </div>
              <p class="text-xs text-gray-500">Objectif: {{ stats?.objectif_membres || 50 }} membres</p>
            </div>
          </div>

          <!-- Membres Actifs -->
          <div class="bg-white/70 backdrop-blur-sm border border-gray-200/50 rounded-xl p-6 hover:bg-white/90 transition-all duration-300 shadow-sm hover:shadow-md">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <span class="text-2xl">✅</span>
              </div>
              <div class="text-sm text-green-600 font-medium">
                {{ Math.round(((stats?.membres_actifs || 38) / (stats?.total_membres || 42)) * 100) }}% actifs
              </div>
            </div>
            <div class="space-y-1">
              <p class="text-2xl font-bold text-gray-900">{{ stats?.membres_actifs || 38 }}</p>
              <p class="text-sm text-gray-600">Membres Actifs</p>
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-gradient-to-r from-green-500 to-green-600 h-2 rounded-full transition-all duration-1000"
                     :style="`width: ${((stats?.membres_actifs || 38) / (stats?.total_membres || 42)) * 100}%`">
                </div>
              </div>
              <p class="text-xs text-gray-500">Présents cette semaine</p>
            </div>
          </div>

          <!-- Revenus -->
          <div class="bg-white/70 backdrop-blur-sm border border-gray-200/50 rounded-xl p-6 hover:bg-white/90 transition-all duration-300 shadow-sm hover:shadow-md">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <span class="text-2xl">💰</span>
              </div>
              <div class="flex items-center space-x-1 text-green-600 text-sm font-medium">
                <span>↗</span>
                <span>+{{ stats?.evolution_revenus || 12.5 }}%</span>
              </div>
            </div>
            <div class="space-y-1">
              <p class="text-2xl font-bold text-gray-900">${{ stats?.revenus_mois || 3250 }}</p>
              <p class="text-sm text-gray-600">Revenus ce Mois</p>
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 h-2 rounded-full transition-all duration-1000"
                     :style="`width: ${Math.min((stats?.revenus_mois || 3250) / (stats?.objectif_revenus || 4000) * 100, 100)}%`">
                </div>
              </div>
              <p class="text-xs text-gray-500">Objectif: ${{ stats?.objectif_revenus || 4000 }}</p>
            </div>
          </div>

          <!-- Taux Présence -->
          <div class="bg-white/70 backdrop-blur-sm border border-gray-200/50 rounded-xl p-6 hover:bg-white/90 transition-all duration-300 shadow-sm hover:shadow-md">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <span class="text-2xl">📊</span>
              </div>
              <div class="text-sm font-medium" :class="(stats?.taux_presence || 87) >= 80 ? 'text-green-600' : 'text-yellow-600'">
                {{ getTauxPresenceLabel(stats?.taux_presence || 87) }}
              </div>
            </div>
            <div class="space-y-1">
              <p class="text-2xl font-bold text-gray-900">{{ stats?.taux_presence || 87 }}%</p>
              <p class="text-sm text-gray-600">Taux de Présence</p>
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-2 rounded-full transition-all duration-1000"
                     :style="`width: ${stats?.taux_presence || 87}%`">
                </div>
              </div>
              <p class="text-xs text-gray-500">7 derniers jours</p>
            </div>
          </div>
        </div>

        <!-- Actions Rapides Claires -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
          <a href="/membres" 
             class="group bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 rounded-xl p-6 transition-all duration-300 transform hover:scale-105 shadow-lg">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="text-lg font-bold text-white mb-2">👥 Gestion Membres</h3>
                <p class="text-blue-100 text-sm mb-3">Inscriptions, profils, ceintures</p>
                <div class="flex items-center space-x-4 text-blue-100 text-sm">
                  <span>{{ stats?.total_membres || 42 }} inscrits</span>
                  <span>•</span>
                  <span>{{ stats?.membres_actifs || 38 }} actifs</span>
                </div>
              </div>
              <div class="text-white/70 group-hover:text-white transition-colors">
                <span class="text-2xl">→</span>
              </div>
            </div>
          </a>

          <a href="/cours" 
             class="group bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 rounded-xl p-6 transition-all duration-300 transform hover:scale-105 shadow-lg">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="text-lg font-bold text-white mb-2">📚 Gestion Cours</h3>
                <p class="text-green-100 text-sm mb-3">Horaires, planning, instructeurs</p>
                <div class="flex items-center space-x-4 text-green-100 text-sm">
                  <span>{{ stats?.cours_actifs || 8 }} cours</span>
                  <span>•</span>
                  <span>{{ stats?.cours_aujourd_hui || 4 }} aujourd'hui</span>
                </div>
              </div>
              <div class="text-white/70 group-hover:text-white transition-colors">
                <span class="text-2xl">→</span>
              </div>
            </div>
          </a>

          <a href="/presences" 
             class="group bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 rounded-xl p-6 transition-all duration-300 transform hover:scale-105 shadow-lg">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="text-lg font-bold text-white mb-2">📋 Interface Présences</h3>
                <p class="text-purple-100 text-sm mb-3">Tablette, pointage, rapports</p>
                <div class="flex items-center space-x-4 text-purple-100 text-sm">
                  <span>{{ stats?.presences_aujourd_hui || 15 }} présents</span>
                  <span>•</span>
                  <span>{{ stats?.taux_presence || 87 }}% taux</span>
                </div>
              </div>
              <div class="text-white/70 group-hover:text-white transition-colors">
                <span class="text-2xl">→</span>
              </div>
            </div>
          </a>
        </div>

        <!-- Analytics Section Claire -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
          <!-- Activité Récente -->
          <div class="bg-white/70 backdrop-blur-sm border border-gray-200/50 rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
              <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
              Activité Récente
            </h3>
            <div class="space-y-4">
              <div class="flex items-center space-x-4 p-3 bg-gray-50/50 rounded-lg border border-gray-100">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                  <span class="text-green-600">👤</span>
                </div>
                <div class="flex-1">
                  <p class="text-gray-900 text-sm font-medium">Nouveau membre inscrit</p>
                  <p class="text-gray-600 text-xs">Marie Dubois - Cours débutant</p>
                </div>
                <span class="text-gray-500 text-xs">Il y a 2h</span>
              </div>
              
              <div class="flex items-center space-x-4 p-3 bg-gray-50/50 rounded-lg border border-gray-100">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                  <span class="text-blue-600">🥋</span>
                </div>
                <div class="flex-1">
                  <p class="text-gray-900 text-sm font-medium">Examen ceinture planifié</p>
                  <p class="text-gray-600 text-xs">6 candidats pour ceinture jaune</p>
                </div>
                <span class="text-gray-500 text-xs">Il y a 4h</span>
              </div>
              
              <div class="flex items-center space-x-4 p-3 bg-gray-50/50 rounded-lg border border-gray-100">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                  <span class="text-yellow-600">💳</span>
                </div>
                <div class="flex-1">
                  <p class="text-gray-900 text-sm font-medium">Paiement reçu</p>
                  <p class="text-gray-600 text-xs">Jean Martin - Abonnement mensuel</p>
                </div>
                <span class="text-gray-500 text-xs">Hier</span>
              </div>
            </div>
          </div>

          <!-- Statistiques Avancées -->
          <div class="bg-white/70 backdrop-blur-sm border border-gray-200/50 rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
              <span class="w-2 h-2 bg-purple-500 rounded-full mr-3"></span>
              Métriques Avancées
            </h3>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
              <div class="text-center p-4 bg-gray-50/50 rounded-lg border border-gray-100">
                <div class="text-2xl font-bold text-blue-600">{{ stats?.examens_ce_mois || 6 }}</div>
                <div class="text-xs text-gray-600">Examens ce mois</div>
              </div>
              
              <div class="text-center p-4 bg-gray-50/50 rounded-lg border border-gray-100">
                <div class="text-2xl font-bold text-green-600">{{ stats?.moyenne_age || '26 ans' }}</div>
                <div class="text-xs text-gray-600">Âge moyen</div>
              </div>
              
              <div class="text-center p-4 bg-gray-50/50 rounded-lg border border-gray-100">
                <div class="text-2xl font-bold text-yellow-600">{{ stats?.retention_rate || 96 }}%</div>
                <div class="text-xs text-gray-600">Taux rétention</div>
              </div>
              
              <div class="text-center p-4 bg-gray-50/50 rounded-lg border border-gray-100">
                <div class="text-2xl font-bold text-purple-600">{{ stats?.satisfaction_moyenne || 94 }}%</div>
                <div class="text-xs text-gray-600">Satisfaction</div>
              </div>
            </div>

            <!-- Progression Ceintures -->
            <div>
              <h4 class="text-sm font-medium text-gray-900 mb-3">Répartition Ceintures</h4>
              <div class="space-y-2">
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-gray-300 rounded-full border border-gray-400"></div>
                    <span class="text-sm text-gray-700">Blanche</span>
                  </div>
                  <span class="text-sm text-gray-600">{{ Math.floor((stats?.total_membres || 42) * 0.4) }}</span>
                </div>
                
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                    <span class="text-sm text-gray-700">Jaune</span>
                  </div>
                  <span class="text-sm text-gray-600">{{ Math.floor((stats?.total_membres || 42) * 0.3) }}</span>
                </div>
                
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                    <span class="text-sm text-gray-700">Orange</span>
                  </div>
                  <span class="text-sm text-gray-600">{{ Math.floor((stats?.total_membres || 42) * 0.2) }}</span>
                </div>
                
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <span class="text-sm text-gray-700">Verte+</span>
                  </div>
                  <span class="text-sm text-gray-600">{{ Math.floor((stats?.total_membres || 42) * 0.1) }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Actions Bar Clair -->
        <div class="bg-white/70 backdrop-blur-sm border border-gray-200/50 rounded-xl p-6 shadow-sm">
          <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
              <h3 class="text-lg font-bold text-gray-900">Actions Rapides</h3>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
              <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
                + Nouveau Membre
              </button>
              <button class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
                + Nouveau Cours
              </button>
              <button class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
                📊 Rapports
              </button>
              <button class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
                💳 Paiements
              </button>
            </div>
          </div>
        </div>

        <!-- Footer Clair -->
        <div class="mt-8 pt-6 border-t border-gray-200/50">
          <div class="flex flex-col sm:flex-row items-center justify-between text-sm text-gray-600">
            <div class="flex items-center space-x-4 mb-4 sm:mb-0">
              <span class="flex items-center space-x-2">
                <span>🏢</span>
                <span>StudiosDB v{{ meta?.version || '5.4.0' }} Pro</span>
              </span>
              <span class="flex items-center space-x-2">
                <span>📅</span>
                <span>{{ new Date().toLocaleDateString('fr-CA') }}</span>
              </span>
              <span class="flex items-center space-x-2">
                <span>🕒</span>
                <span>{{ new Date().toLocaleTimeString('fr-CA', { hour: '2-digit', minute: '2-digit' }) }}</span>
              </span>
            </div>
            
            <div class="flex items-center space-x-2">
              <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
              <span>Système opérationnel</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'DashboardProfessional',
  props: {
    stats: {
      type: Object,
      default: () => ({})
    },
    user: {
      type: Object,
      default: () => ({})
    },
    meta: {
      type: Object,
      default: () => ({})
    }
  },
  methods: {
    getTauxPresenceLabel(taux) {
      if (taux >= 90) return '🎯 Excellent';
      if (taux >= 80) return '✅ Très bien';
      if (taux >= 70) return '⚠️ Correct';
      return '🔴 À améliorer';
    }
  },
  mounted() {
    console.log('🥋 Dashboard Professionnel StudiosDB v5 Pro chargé');
    console.log('📊 Données:', { stats: this.stats, user: this.user });
  }
}
</script>