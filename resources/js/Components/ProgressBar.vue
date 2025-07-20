<template>
  <div class="space-y-2">
    <!-- En-tête avec label et valeur -->
    <div class="flex items-center justify-between">
      <div class="flex items-center space-x-3">
        <!-- Indicateur de couleur -->
        <div
          v-if="color"
          class="w-4 h-4 rounded-full border-2 border-gray-600"
          :style="{ backgroundColor: color }"
        ></div>
        
        <!-- Label -->
        <span class="text-gray-300 text-sm font-medium">{{ label }}</span>
        
        <!-- Badge optionnel -->
        <span
          v-if="badge"
          class="px-2 py-1 bg-gray-700 text-gray-300 text-xs rounded-full"
        >
          {{ badge }}
        </span>
      </div>
      
      <!-- Valeur et pourcentage -->
      <div class="flex items-center space-x-2 text-right">
        <span class="text-white font-semibold text-sm">{{ formattedValue }}</span>
        <span v-if="showPercentage" class="text-gray-400 text-xs">
          ({{ percentage }}%)
        </span>
      </div>
    </div>
    
    <!-- Barre de progression -->
    <div class="relative">
      <!-- Barre de fond -->
      <div
        :class="[
          'w-full rounded-full overflow-hidden',
          backgroundClass
        ]"
        :style="{ height: `${height}px` }"
      >
        <!-- Barre de progression -->
        <div
          :class="[
            'h-full rounded-full transition-all duration-700 ease-out relative overflow-hidden',
            progressClass
          ]"
          :style="{
            width: `${percentage}%`,
            backgroundColor: color || undefined
          }"
        >
          <!-- Effet de brillance animé -->
          <div
            v-if="animated"
            class="absolute inset-0 opacity-30"
            :style="{
              background: `linear-gradient(90deg, transparent, ${color ? lightenColor(color) : '#ffffff'}, transparent)`,
              animation: 'shine 2s ease-in-out infinite'
            }"
          ></div>
          
          <!-- Segments (pour effet strié) -->
          <div
            v-if="striped"
            class="absolute inset-0 opacity-20"
            style="background-image: repeating-linear-gradient(
              45deg,
              transparent,
              transparent 4px,
              rgba(255,255,255,0.3) 4px,
              rgba(255,255,255,0.3) 8px
            )"
          ></div>
        </div>
      </div>
      
      <!-- Indicateurs de paliers (optionnel) -->
      <div v-if="milestones && milestones.length" class="absolute inset-0 pointer-events-none">
        <div
          v-for="milestone in milestones"
          :key="milestone.value"
          class="absolute top-0 bottom-0 w-0.5 bg-gray-500"
          :style="{ left: `${(milestone.value / max) * 100}%` }"
          :title="milestone.label"
        >
          <!-- Petit triangle indicateur -->
          <div class="absolute -top-1 -left-1 w-2 h-2 bg-gray-500 rotate-45"></div>
        </div>
      </div>
    </div>
    
    <!-- Informations additionnelles -->
    <div v-if="subtitle || showMinMax" class="flex justify-between items-center text-xs text-gray-400">
      <span>{{ subtitle || (showMinMax ? min : '') }}</span>
      <span v-if="showMinMax">{{ max }}</span>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  label: {
    type: String,
    required: true
  },
  value: {
    type: Number,
    required: true
  },
  max: {
    type: Number,
    default: 100
  },
  min: {
    type: Number,
    default: 0
  },
  color: {
    type: String,
    default: '#3B82F6' // Bleu par défaut
  },
  height: {
    type: Number,
    default: 8
  },
  variant: {
    type: String,
    default: 'default', // default, success, warning, danger
    validator: (value) => ['default', 'success', 'warning', 'danger'].includes(value)
  },
  showPercentage: {
    type: Boolean,
    default: true
  },
  showMinMax: {
    type: Boolean,
    default: false
  },
  animated: {
    type: Boolean,
    default: true
  },
  striped: {
    type: Boolean,
    default: false
  },
  formatter: {
    type: Function,
    default: (value) => value.toString()
  },
  badge: {
    type: String,
    default: ''
  },
  subtitle: {
    type: String,
    default: ''
  },
  milestones: {
    type: Array,
    default: () => []
    // Format: [{ value: number, label: string }]
  }
})

// Pourcentage calculé
const percentage = computed(() => {
  const range = props.max - props.min
  if (range <= 0) return 0
  
  const normalizedValue = Math.max(props.min, Math.min(props.max, props.value))
  return Math.round(((normalizedValue - props.min) / range) * 100)
})

// Valeur formatée
const formattedValue = computed(() => {
  return props.formatter(props.value)
})

// Classes pour le fond
const backgroundClass = computed(() => {
  return 'bg-gray-700'
})

// Classes pour la barre de progression
const progressClass = computed(() => {
  if (props.color) return '' // Couleur personnalisée via style
  
  const variantClasses = {
    default: 'bg-blue-500',
    success: 'bg-green-500',
    warning: 'bg-orange-500',
    danger: 'bg-red-500'
  }
  
  return variantClasses[props.variant] || variantClasses.default
})

// Fonction pour éclaircir une couleur
const lightenColor = (color) => {
  // Simple lightening - in production, you might want a more robust solution
  if (color.startsWith('#')) {
    const hex = color.slice(1)
    const rgb = parseInt(hex, 16)
    const r = Math.min(255, ((rgb >> 16) & 255) + 40)
    const g = Math.min(255, ((rgb >> 8) & 255) + 40)
    const b = Math.min(255, (rgb & 255) + 40)
    return `rgb(${r}, ${g}, ${b})`
  }
  return 'rgba(255, 255, 255, 0.6)'
}
</script>

<style scoped>
/* Animation de brillance */
@keyframes shine {
  0% {
    transform: translateX(-100%);
  }
  50% {
    transform: translateX(0%);
  }
  100% {
    transform: translateX(100%);
  }
}

/* Transition fluide pour la largeur */
.progress-bar {
  transition: width 0.7s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Effet de pulsation pour les barres animées */
@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.8;
  }
}

.animate-pulse {
  animation: pulse 2s ease-in-out infinite;
}
</style>
