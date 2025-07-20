<template>
  <AuthenticatedLayout page-title="Dashboard" :stats="stats">
    <div class="p-6 space-y-8">
      
      <!-- Cards de statistiques principales -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <DashboardWidget
          title="Membres"
          :value="stats.total_membres"
          icon="👥"
          icon-color="blue"
          :evolution="12"
          trend-icon="📈"
          subtitle="Total inscrits"
          :sparkline-data="[45, 52, 48, 61, 55, 67]"
          @click="navigateTo('membres.index')"
          clickable
        />
        
        <DashboardWidget
          title="Cours"
          :value="stats.total_cours"
          icon="📚"
          icon-color="green"
          subtitle="Cours actifs"
          :show-progress="true"
          :max-value="25"
          @click="navigateTo('cours.index')"
          clickable
        />
        
        <DashboardWidget
          title="Présences"
          :value="stats.presences_aujourd_hui"
          icon="✅"
          icon-color="purple"
          subtitle="Aujourd'hui"
          status="En temps réel"
          status-type="success"
          @click="navigateTo('presences.index')"
          clickable
        />
        
        <DashboardWidget
          title="Revenus"
          :value="formatMoney(stats.revenus_mois)"
          icon="💰"
          icon-color="orange"
          :evolution="stats.evolution_revenus"
          trend-icon="📊"
          subtitle="Ce mois-ci"
          :formatter="(value) => value"
          @click="navigateTo('paiements.index')"
          clickable
        />
      </div>

      <!-- Section principale avec graphiques -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Progression des ceintures -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
          <h2 class="text-white text-xl font-semibold mb-6">Progression des ceintures</h2>
          <div class="space-y-4">
            <ProgressBar
              v-for="ceinture in progression_ceintures"
              :key="ceinture.nom"
              :label="ceinture.nom"
              :value="ceinture.count"
              :max="Math.max(...progression_ceintures.map(c => c.count))"
              :color="ceinture.couleur_hex"
              :formatter="(value) => `${value} élèves`"
              animated
            />
          </div>
        </div>

        <!-- Graphique des performances -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
          <h2 class="text-white text-xl font-semibold mb-6">Performances des élèves</h2>
          <div class="h-64 flex items-center justify-center">
            <!-- Graphique linéaire simple -->
            <svg class="w-full h-full" viewBox="0 0 400 200">
              <!-- Grille de fond -->
              <defs>
                <pattern id="grid" width="40" height="20" patternUnits="userSpaceOnUse">
                  <path d="M 40 0 L 0 0 0 20" fill="none" stroke="#374151" stroke-width="0.5"/>
                </pattern>
              </defs>
              <rect width="100%" height="100%" fill="url(#grid)" />
              
              <!-- Ligne de performance -->
              <path
                :d="performancePath"
                fill="none"
                stroke="#3B82F6"
                stroke-width="3"
                stroke-linecap="round"
                class="drop-shadow-lg"
              />
              
              <!-- Points de données -->
              <circle
                v-for="(point, index) in performancePoints"
                :key="index"
                :cx="point.x"
                :cy="point.y"
                r="4"
                fill="#3B82F6"
                stroke="#1E40AF"
                stroke-width="2"
                class="drop-shadow-sm"
              />
              
              <!-- Labels des mois -->
              <text
                v-for="(month, index) in ['Aoû', 'Sep', 'Oct', 'Nov', 'Déc', 'Jan']"
                :key="month"
                :x="(index * 66) + 20"
                y="190"
                text-anchor="middle"
                class="fill-gray-400 text-xs"
              >
                {{ month }}
              </text>
            </svg>
          </div>
        </div>

      </div>

      <!-- Section avec métriques circulaires et actions rapides -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Métriques de capacité -->
        <div class="lg:col-span-1 space-y-6">
          
          <!-- Taux de présence -->
          <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
            <div class="text-center">
              <div class="relative inline-flex items-center justify-center">
                <svg class="w-24 h-24 transform -rotate-90">
                  <circle
                    cx="48"
                    cy="48"
                    r="40"
                    stroke="#374151"
                    stroke-width="8"
                    fill="transparent"
                  />
                  <circle
                    cx="48"
                    cy="48"
                    r="40"
                    stroke="#3B82F6"
                    stroke-width="8"
                    fill="transparent"
                    stroke-dasharray="251.2"
                    :stroke-dashoffset="251.2 - (251.2 * taux_presence / 100)"
                    stroke-linecap="round"
                    class="transition-all duration-700 ease-out"
                  />
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                  <span class="text-white text-2xl font-bold">{{ taux_presence }}%</span>
                </div>
              </div>
              <p class="text-gray-300 text-sm mt-2">Présences</p>
            </div>
          </div>

          <!-- Taux d'occupation -->
          <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
            <div class="text-center">
              <div class="relative inline-flex items-center justify-center">
                <svg class="w-24 h-24 transform -rotate-90">
                  <circle
                    cx="48"
                    cy="48"
                    r="40"
                    stroke="#374151"
                    stroke-width="8"
                    fill="transparent"
                  />
                  <circle
                    cx="48"
                    cy="48"
                    r="40"
                    stroke="#10B981"
                    stroke-width="8"
                    fill="transparent"
                    stroke-dasharray="251.2"
                    :stroke-dashoffset="251.2 - (251.2 * taux_occupation / 100)"
                    stroke-linecap="round"
                    class="transition-all duration-700 ease-out"
                  />
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                  <span class="text-white text-2xl font-bold">{{ taux_occupation }}%</span>
                </div>
              </div>
              <p class="text-gray-300 text-sm mt-2">Capacité</p>
            </div>
          </div>

        </div>

        <!-- Actions rapides -->
        <div class="lg:col-span-2">
          <h2 class="text-white text-xl font-semibold mb-6">Actions rapides</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            
            <QuickAction
              title="Nouveau Membre"
              description="Inscrire un nouvel élève"
              icon="👤"
              route="membres.create"
              :metrics="[{ label: 'Actifs', value: stats.membres_actifs }]"
              status="Disponible"
              status-type="success"
            />
            
            <QuickAction
              title="Prendre Présences"
              description="Interface tablette tactile"
              icon="📋"
              route="presences.tablette"
              badge="Nouveau"
              badge-type="info"
              :metrics="[{ label: 'Aujourd\'hui', value: stats.presences_aujourd_hui }]"
            />
            
            <QuickAction
              title="Gérer Paiements"
              description="Factures et relances"
              icon="💳"
              route="paiements.index"
              :badge="stats.paiements_en_retard > 0 ? stats.paiements_en_retard : null"
              badge-type="warning"
              status="Attention requise"
              status-type="warning"
            />
            
            <QuickAction
              title="Planning Cours"
              description="Horaires et instructeurs"
              icon="📅"
              route="cours.planning"
              :metrics="[{ label: 'Cours', value: stats.total_cours }]"
              status="À jour"
              status-type="success"
            />
            
          </div>
        </div>

      </div>

      <!-- Répartition par ceintures (camembert) -->
      <div v-if="progression_ceintures && progression_ceintures.length" class="bg-gray-800 rounded-xl p-6 border border-gray-700">
        <ChartRepartition
          title="Répartition par ceintures"
          :data="ceinturesChartData"
          :size="300"
          center-text="Total"
          :value-formatter="(value) => `${value} élèves`"
        />
      </div>

    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import DashboardWidget from '@/Components/DashboardWidget.vue'
import ProgressBar from '@/Components/ProgressBar.vue'
import QuickAction from '@/Components/QuickAction.vue'
import ChartRepartition from '@/Components/ChartRepartition.vue'

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
  },
  progression_ceintures: {
    type: Array,
    default: () => [
      { nom: 'Blanc', count: 85, couleur_hex: '#F3F4F6' },
      { nom: 'Jaune', count: 72, couleur_hex: '#FEF3C7' },
      { nom: 'Orange', count: 45, couleur_hex: '#FED7AA' },
      { nom: 'Vert', count: 28, couleur_hex: '#D1FAE5' },
      { nom: 'Bleu', count: 15, couleur_hex: '#DBEAFE' },
      { nom: 'Marron', count: 8, couleur_hex: '#D2B48C' },
      { nom: 'Noir', count: 3, couleur_hex: '#111827' }
    ]
  },
  evolution_presences: {
    type: Array,
    default: () => [
      { mois: 'Aoû', presences: 280 },
      { mois: 'Sep', presences: 320 },
      { mois: 'Oct', presences: 298 },
      { mois: 'Nov', presences: 350 },
      { mois: 'Déc', presences: 315 },
      { mois: 'Jan', presences: 343 }
    ]
  },
  taux_occupation: {
    type: Number,
    default: 68
  },
  taux_presence: {
    type: Number,
    default: 80
  },
  role: {
    type: String,
    default: 'admin'
  }
})

// Computed properties
const ceinturesChartData = computed(() => {
  return props.progression_ceintures.map(ceinture => ({
    label: ceinture.nom,
    value: ceinture.count,
    color: ceinture.couleur_hex
  }))
})

const performancePoints = computed(() => {
  return props.evolution_presences.map((item, index) => ({
    x: (index * 66) + 20,
    y: 160 - (item.presences / 400 * 140) // Normaliser les valeurs
  }))
})

const performancePath = computed(() => {
  const points = performancePoints.value
  if (points.length === 0) return ''
  
  let path = `M ${points[0].x} ${points[0].y}`
  
  for (let i = 1; i < points.length; i++) {
    const prev = points[i - 1]
    const curr = points[i]
    const cp1x = prev.x + (curr.x - prev.x) / 3
    const cp1y = prev.y
    const cp2x = curr.x - (curr.x - prev.x) / 3
    const cp2y = curr.y
    
    path += ` C ${cp1x} ${cp1y}, ${cp2x} ${cp2y}, ${curr.x} ${curr.y}`
  }
  
  return path
})

// Méthodes utilitaires
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
</script>

<style scoped>
/* Animations personnalisées */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.fade-in-up {
  animation: fadeInUp 0.6s ease-out;
}

/* Effet de lueur pour les graphiques */
.glow-blue {
  filter: drop-shadow(0 0 8px rgba(59, 130, 246, 0.4));
}

.glow-green {
  filter: drop-shadow(0 0 8px rgba(16, 185, 129, 0.4));
}

/* Transition fluide pour les cercles de progression */
circle {
  transition: stroke-dashoffset 0.7s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Hover effects pour les cards */
.dashboard-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
}
</style>
