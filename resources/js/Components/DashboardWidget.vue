<template>
  <div
    :class="[
      'bg-gray-800 rounded-xl p-6 border border-gray-700 shadow-lg',
      'transition-all duration-300 hover:shadow-xl hover:border-blue-500/50',
      'transform hover:scale-105 cursor-pointer group'
    ]"
    @click="handleClick"
  >
    <!-- Contenu principal -->
    <div class="flex items-center justify-between">
      <!-- Icône et statistiques -->
      <div class="flex items-center space-x-4">
        <!-- Icône -->
        <div
          :class="[
            'w-16 h-16 rounded-xl flex items-center justify-center text-2xl font-bold',
            'transition-all duration-300 group-hover:scale-110',
            iconBackgroundClass
          ]"
        >
          <span v-if="icon">{{ icon }}</span>
          <slot v-else name="icon"></slot>
        </div>
        
        <!-- Informations -->
        <div>
          <h3 class="text-gray-300 text-sm font-medium mb-1">{{ title }}</h3>
          <div class="flex items-baseline space-x-2">
            <span class="text-white text-3xl font-bold">
              {{ formattedValue }}
            </span>
            <span v-if="unit" class="text-gray-400 text-sm">{{ unit }}</span>
          </div>
          
          <!-- Sous-titre ou description -->
          <p v-if="subtitle" class="text-gray-400 text-xs mt-1">
            {{ subtitle }}
          </p>
        </div>
      </div>
      
      <!-- Évolution/Tendance -->
      <div v-if="evolution !== null || trendIcon" class="text-right">
        <!-- Icône de tendance -->
        <div v-if="trendIcon" class="mb-2">
          <span :class="trendIconClass">{{ trendIcon }}</span>
        </div>
        
        <!-- Pourcentage d'évolution -->
        <div v-if="evolution !== null" :class="evolutionClass">
          <span class="text-sm font-semibold">
            {{ evolution > 0 ? '+' : '' }}{{ evolution }}%
          </span>
          <div class="text-xs opacity-75">vs mois dernier</div>
        </div>
      </div>
    </div>
    
    <!-- Barre de progression (optionnelle) -->
    <div v-if="showProgress && maxValue" class="mt-4">
      <div class="flex justify-between items-center mb-2">
        <span class="text-gray-400 text-xs">Progression</span>
        <span class="text-gray-300 text-xs">
          {{ progressPercentage }}%
        </span>
      </div>
      <div class="w-full bg-gray-700 rounded-full h-2">
        <div
          :class="[
            'h-2 rounded-full transition-all duration-500 ease-out',
            progressBarClass
          ]"
          :style="{ width: `${progressPercentage}%` }"
        ></div>
      </div>
    </div>
    
    <!-- Graphique miniature (optionnel) -->
    <div v-if="sparklineData && sparklineData.length" class="mt-4">
      <svg class="w-full h-12" viewBox="0 0 100 24">
        <path
          :d="sparklinePath"
          fill="none"
          :stroke="sparklineColor"
          stroke-width="1.5"
          class="opacity-60"
        />
        <path
          :d="sparklinePath"
          fill="none"
          :stroke="sparklineColor"
          stroke-width="1.5"
          stroke-dasharray="2,2"
          class="opacity-30"
        />
      </svg>
    </div>
    
    <!-- Badge de statut -->
    <div v-if="status" class="mt-3 flex items-center space-x-2">
      <div
        :class="[
          'w-2 h-2 rounded-full',
          statusColor
        ]"
      ></div>
      <span class="text-gray-300 text-xs">{{ status }}</span>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  value: {
    type: [Number, String],
    required: true
  },
  unit: {
    type: String,
    default: ''
  },
  subtitle: {
    type: String,
    default: ''
  },
  icon: {
    type: String,
    default: ''
  },
  iconColor: {
    type: String,
    default: 'blue', // blue, green, red, orange, purple, etc.
  },
  evolution: {
    type: Number,
    default: null
  },
  trendIcon: {
    type: String,
    default: ''
  },
  showProgress: {
    type: Boolean,
    default: false
  },
  maxValue: {
    type: Number,
    default: 100
  },
  sparklineData: {
    type: Array,
    default: () => []
  },
  status: {
    type: String,
    default: ''
  },
  statusType: {
    type: String,
    default: 'info',
    validator: (value) => ['info', 'success', 'warning', 'danger'].includes(value)
  },
  formatter: {
    type: Function,
    default: (value) => value.toString()
  },
  clickable: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['click'])

// Valeur formatée
const formattedValue = computed(() => {
  return props.formatter(props.value)
})

// Classes pour l'icône
const iconBackgroundClass = computed(() => {
  const colorClasses = {
    blue: 'bg-blue-600 text-blue-100',
    green: 'bg-green-600 text-green-100',
    red: 'bg-red-600 text-red-100',
    orange: 'bg-orange-600 text-orange-100',
    purple: 'bg-purple-600 text-purple-100',
    yellow: 'bg-yellow-600 text-yellow-100',
    pink: 'bg-pink-600 text-pink-100',
    indigo: 'bg-indigo-600 text-indigo-100',
  }
  return colorClasses[props.iconColor] || colorClasses.blue
})

// Classes pour l'évolution
const evolutionClass = computed(() => {
  if (props.evolution === null) return ''
  
  const baseClass = 'text-right'
  if (props.evolution > 0) {
    return `${baseClass} text-green-400`
  } else if (props.evolution < 0) {
    return `${baseClass} text-red-400`
  } else {
    return `${baseClass} text-gray-400`
  }
})

// Classes pour l'icône de tendance
const trendIconClass = computed(() => {
  const baseClass = 'text-2xl'
  if (props.evolution !== null) {
    if (props.evolution > 0) {
      return `${baseClass} text-green-400`
    } else if (props.evolution < 0) {
      return `${baseClass} text-red-400`
    }
  }
  return `${baseClass} text-gray-400`
})

// Pourcentage de progression
const progressPercentage = computed(() => {
  if (!props.maxValue) return 0
  const numValue = typeof props.value === 'string' ? parseFloat(props.value) : props.value
  return Math.min(Math.round((numValue / props.maxValue) * 100), 100)
})

// Classes pour la barre de progression
const progressBarClass = computed(() => {
  const percentage = progressPercentage.value
  if (percentage >= 80) {
    return 'bg-green-500'
  } else if (percentage >= 60) {
    return 'bg-blue-500'
  } else if (percentage >= 40) {
    return 'bg-yellow-500'
  } else {
    return 'bg-red-500'
  }
})

// Couleur du sparkline
const sparklineColor = computed(() => {
  const colors = {
    blue: '#3B82F6',
    green: '#10B981',
    red: '#EF4444',
    orange: '#F59E0B',
    purple: '#8B5CF6',
    yellow: '#F59E0B',
    pink: '#EC4899',
    indigo: '#6366F1',
  }
  return colors[props.iconColor] || colors.blue
})

// Path SVG pour le sparkline
const sparklinePath = computed(() => {
  if (!props.sparklineData.length) return ''
  
  const points = props.sparklineData.map((value, index) => {
    const x = (index / (props.sparklineData.length - 1)) * 100
    const maxVal = Math.max(...props.sparklineData)
    const minVal = Math.min(...props.sparklineData)
    const range = maxVal - minVal || 1
    const y = 20 - ((value - minVal) / range) * 16 // 16 = hauteur utile (24 - 4 de marge)
    
    return `${index === 0 ? 'M' : 'L'} ${x} ${y}`
  })
  
  return points.join(' ')
})

// Couleur du statut
const statusColor = computed(() => {
  const colors = {
    info: 'bg-blue-400',
    success: 'bg-green-400',
    warning: 'bg-orange-400',
    danger: 'bg-red-400'
  }
  return colors[props.statusType]
})

// Gestionnaire de clic
const handleClick = () => {
  if (props.clickable) {
    emit('click')
  }
}
</script>

<style scoped>
/* Animation de pulsation pour les éléments actifs */
@keyframes pulse-glow {
  0%, 100% {
    box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4);
  }
  50% {
    box-shadow: 0 0 20px 5px rgba(59, 130, 246, 0.2);
  }
}

.group:hover {
  animation: pulse-glow 2s ease-in-out infinite;
}
</style>
