<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-slate-900 to-gray-900">
    <!-- Background Pattern Clair -->
    <div class="absolute inset-0 opacity-20" 
         style="background-image: url('data:image/svg+xml,%3Csvg width=60 height=60 viewBox=0 0 60 60 xmlns=http://www.w3.org/2000/svg%3E%3Cg fill=none fill-rule=evenodd%3E%3Cg fill=%23818cf8 fill-opacity=0.1%3E%3Ccircle cx=30 cy=30 r=2/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')">
    </div>

  <!-- Header supprimé : géré par le layout -->

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

        <!-- KPI Cards Sombres -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <!-- Total Membres -->
          <div class="bg-gray-800/80 backdrop-blur-xl border border-gray-700/50 rounded-xl p-6 hover:bg-gray-800/90 transition-all duration-300 shadow-md hover:shadow-lg">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                <span class="text-2xl text-white">👥</span>
              </div>
              <div class="flex items-center space-x-1 text-emerald-400 text-sm font-medium">
                <span>↗</span>
                <span>+{{ stats?.evolution_membres || 8.3 }}%</span>
              </div>
            </div>
            <div class="space-y-1">
              <p class="text-2xl font-bold text-white">{{ stats?.total_membres || 42 }}</p>
              <p class="text-sm text-gray-300">Total Membres</p>
              <div class="w-full bg-gray-700 rounded-full h-2">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full transition-all duration-1000"
                     :style="`width: ${Math.min((stats?.total_membres || 42) / (stats?.objectif_membres || 50) * 100, 100)}%`">
                </div>
              </div>
              <p class="text-xs text-gray-400">Objectif: {{ stats?.objectif_membres || 50 }} membres</p>
            </div>
          </div>

          <!-- Membres Actifs -->
          <div class="bg-gray-800/80 backdrop-blur-xl border border-gray-700/50 rounded-xl p-6 hover:bg-gray-800/90 transition-all duration-300 shadow-md hover:shadow-lg">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg flex items-center justify-center">
                <span class="text-2xl text-white">✅</span>
              </div>
              <div class="text-sm text-emerald-400 font-medium">
                {{ Math.round(((stats?.membres_actifs || 38) / (stats?.total_membres || 42)) * 100) }}% actifs
              </div>
            </div>
            <div class="space-y-1">
              <p class="text-2xl font-bold text-white">{{ stats?.membres_actifs || 38 }}</p>
              <p class="text-sm text-gray-300">Membres Actifs</p>
              <div class="w-full bg-gray-700 rounded-full h-2">
                <div class="bg-gradient-to-r from-emerald-400 to-green-600 h-2 rounded-full transition-all duration-1000"
                     :style="`width: ${((stats?.membres_actifs || 38) / (stats?.total_membres || 42)) * 100}%`">
                </div>
              </div>
              <p class="text-xs text-gray-400">Présents cette semaine</p>
            </div>
          </div>

          <!-- Revenus -->
          <div class="bg-gray-800/80 backdrop-blur-xl border border-gray-700/50 rounded-xl p-6 hover:bg-gray-800/90 transition-all duration-300 shadow-md hover:shadow-lg">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-lg flex items-center justify-center">
                <span class="text-2xl text-white">💰</span>
              </div>
              <div class="flex items-center space-x-1 text-emerald-400 text-sm font-medium">
                <span>↗</span>
                <span>+{{ stats?.evolution_revenus || 12.5 }}%</span>
              </div>
            </div>
            <div class="space-y-1">
              <p class="text-2xl font-bold text-white">${{ stats?.revenus_mois || 3250 }}</p>
              <p class="text-sm text-gray-300">Revenus ce Mois</p>
              <div class="w-full bg-gray-700 rounded-full h-2">
                <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 h-2 rounded-full transition-all duration-1000"
                     :style="`width: ${Math.min((stats?.revenus_mois || 3250) / (stats?.objectif_revenus || 4000) * 100, 100)}%`">
                </div>
              </div>
              <p class="text-xs text-gray-400">Objectif: ${{ stats?.objectif_revenus || 4000 }}</p>
            </div>
          </div>

          <!-- Taux Présence -->
          <div class="bg-gray-800/80 backdrop-blur-xl border border-gray-700/50 rounded-xl p-6 hover:bg-gray-800/90 transition-all duration-300 shadow-md hover:shadow-lg">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg flex items-center justify-center">
                <span class="text-2xl text-white">📊</span>
              </div>
              <div class="text-sm font-medium" :class="(stats?.taux_presence || 87) >= 80 ? 'text-emerald-400' : 'text-yellow-400'">
                {{ getTauxPresenceLabel(stats?.taux_presence || 87) }}
              </div>
            </div>
            <div class="space-y-1">
              <p class="text-2xl font-bold text-white">{{ stats?.taux_presence || 87 }}%</p>
              <p class="text-sm text-gray-300">Taux de Présence</p>
              <div class="w-full bg-gray-700 rounded-full h-2">
                <div class="bg-gradient-to-r from-purple-500 to-indigo-600 h-2 rounded-full transition-all duration-1000"
                     :style="`width: ${stats?.taux_presence || 87}%`">
                </div>
              </div>
              <p class="text-xs text-gray-400">7 derniers jours</p>
            </div>
          </div>
        </div>

        <!-- Actions Rapides Sombres -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
          <Link :href="route('membres.index')"
             class="group bg-gray-800/80 hover:bg-blue-900/80 border border-gray-700/50 rounded-xl p-6 transition-all duration-300 transform hover:scale-105 shadow-lg">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="text-lg font-bold text-white mb-2">👥 Gestion Membres</h3>
                <p class="text-blue-200 text-sm mb-3">Inscriptions, profils, ceintures</p>
                <div class="flex items-center space-x-4 text-blue-200 text-sm">
                  <span>{{ stats?.total_membres || 42 }} inscrits</span>
                  <span>•</span>
                  <span>{{ stats?.membres_actifs || 38 }} actifs</span>
                </div>
              </div>
              <div class="text-white/70 group-hover:text-white transition-colors">
                <span class="text-2xl">→</span>
              </div>
            </div>
          </Link>

       <Link :href="route('cours.index')"
         class="group bg-gray-800/80 hover:bg-green-900/80 border border-gray-700/50 rounded-xl p-6 transition-all duration-300 transform hover:scale-105 shadow-lg">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="text-lg font-bold text-white mb-2">📚 Gestion Cours</h3>
                <p class="text-green-200 text-sm mb-3">Horaires, planning, instructeurs</p>
                <div class="flex items-center space-x-4 text-green-200 text-sm">
                  <span>{{ stats?.cours_actifs || 8 }} cours</span>
                  <span>•</span>
                  <span>{{ stats?.cours_aujourd_hui || 4 }} aujourd'hui</span>
                </div>
              </div>
              <div class="text-white/70 group-hover:text-white transition-colors">
                <span class="text-2xl">→</span>
              </div>
            </div>
          </Link>

       <Link :href="route('presences.index')"
         class="group bg-gray-800/80 hover:bg-purple-900/80 border border-gray-700/50 rounded-xl p-6 transition-all duration-300 transform hover:scale-105 shadow-lg">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="text-lg font-bold text-white mb-2">📋 Interface Présences</h3>
                <p class="text-purple-200 text-sm mb-3">Tablette, pointage, rapports</p>
                <div class="flex items-center space-x-4 text-purple-200 text-sm">
                  <span>{{ stats?.presences_aujourd_hui || 15 }} présents</span>
                  <span>•</span>
                  <span>{{ stats?.taux_presence || 87 }}% taux</span>
                </div>
              </div>
              <div class="text-white/70 group-hover:text-white transition-colors">
                <span class="text-2xl">→</span>
              </div>
            </div>
          </Link>
        </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth?.user);
const stats = ref({
  total_membres: 42,
  membres_actifs: 38,
  evolution_membres: 8.3,
  objectif_membres: 50,
  revenus_mois: 3250,
  evolution_revenus: 12.5,
  objectif_revenus: 4000,
  taux_presence: 87,
  cours_actifs: 8,
  cours_aujourd_hui: 4,
  presences_aujourd_hui: 15
});

const getTauxPresenceLabel = (taux) => {
  if (taux >= 90) return 'Excellent';
  if (taux >= 80) return 'Très bon';
  if (taux >= 70) return 'Bon';
  if (taux >= 60) return 'Moyen';
  return 'Faible';
};

onMounted(() => {
  // Charger les stats réelles depuis l'API
  // fetchStats();
});
</script>

<style scoped>
/* Animations personnalisées si nécessaire */
</style>
