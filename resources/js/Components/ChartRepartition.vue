<template>
  <div class="bg-gray-800 rounded-xl p-6 shadow-lg">
    <h3 v-if="title" class="text-white text-lg font-semibold mb-4">{{ title }}</h3>
    
    <div class="relative">
      <!-- Graphique en camembert SVG -->
      <svg :width="size" :height="size" class="mx-auto">
        <!-- Cercle de fond -->
        <circle
          :cx="center"
          :cy="center"
          :r="radius"
          fill="none"
          stroke="#374151"
          stroke-width="2"
        />
        
        <!-- Segments du camembert -->
        <path
          v-for="(segment, index) in segments"
          :key="index"
          :d="segment.path"
          :fill="segment.color"
          :stroke="segment.color"
          stroke-width="1"
          class="transition-all duration-300 hover:opacity-80 cursor-pointer"
          @mouseover="hoveredSegment = index"
          @mouseleave="hoveredSegment = null"
        />
        
        <!-- Texte central (optionnel) -->
        <text
          v-if="centerText"
          :x="center"
          :y="center"
          text-anchor="middle"
          dominant-baseline="middle"
          class="fill-white text-sm font-semibold"
        >
          {{ centerText }}
        </text>
      </svg>
      
      <!-- Tooltip au survol -->
      <div
        v-if="hoveredSegment !== null && tooltip"
        class="absolute top-0 left-0 bg-gray-900 text-white px-3 py-2 rounded-lg shadow-lg pointer-events-none z-10 transform -translate-x-1/2 -translate-y-full"
        :style="tooltipPosition"
      >
        <div class="font-semibold">{{ segments[hoveredSegment]?.label }}</div>
        <div class="text-sm text-gray-300">
          {{ segments[hoveredSegment]?.value }} ({{ segments[hoveredSegment]?.percentage }}%)
        </div>
      </div>
    </div>
    
    <!-- Légende -->
    <div v-if="showLegend" class="mt-6 space-y-2">
      <div
        v-for="(item, index) in data"
        :key="index"
        class="flex items-center justify-between text-sm"
        :class="{ 'opacity-50': hoveredSegment !== null && hoveredSegment !== index }"
      >
        <div class="flex items-center space-x-3">
          <div
            class="w-3 h-3 rounded-full"
            :style="{ backgroundColor: getColor(index) }"
          ></div>
          <span class="text-gray-300">{{ item.label }}</span>
        </div>
        <div class="text-white font-semibold">
          {{ formatValue(item.value) }}
          <span class="text-gray-400 text-xs ml-1">
            ({{ getPercentage(item.value) }}%)
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'

const props = defineProps({
  title: {
    type: String,
    default: ''
  },
  data: {
    type: Array,
    required: true,
    // Format: [{ label: 'Label', value: number, color?: string }]
  },
  size: {
    type: Number,
    default: 200
  },
  colors: {
    type: Array,
    default: () => [
      '#3B82F6', // Bleu
      '#10B981', // Vert
      '#F59E0B', // Orange
      '#EF4444', // Rouge
      '#8B5CF6', // Violet
      '#06B6D4', // Cyan
      '#84CC16', // Lime
      '#F97316', // Orange foncé
    ]
  },
  showLegend: {
    type: Boolean,
    default: true
  },
  centerText: {
    type: String,
    default: ''
  },
  tooltip: {
    type: Boolean,
    default: true
  },
  valueFormatter: {
    type: Function,
    default: (value) => value.toString()
  }
})

const hoveredSegment = ref(null)

// Calculs pour le graphique
const center = computed(() => props.size / 2)
const radius = computed(() => (props.size / 2) - 20)
const total = computed(() => props.data.reduce((sum, item) => sum + item.value, 0))

const segments = computed(() => {
  if (props.data.length === 0 || total.value === 0) return []

  let currentAngle = -90 // Commencer en haut

  return props.data.map((item, index) => {
    const percentage = (item.value / total.value) * 100
    const angle = (item.value / total.value) * 360
    
    const startAngle = currentAngle
    const endAngle = currentAngle + angle
    
    // Conversion en radians
    const startAngleRad = (startAngle * Math.PI) / 180
    const endAngleRad = (endAngle * Math.PI) / 180
    
    // Points du segment
    const x1 = center.value + radius.value * Math.cos(startAngleRad)
    const y1 = center.value + radius.value * Math.sin(startAngleRad)
    const x2 = center.value + radius.value * Math.cos(endAngleRad)
    const y2 = center.value + radius.value * Math.sin(endAngleRad)
    
    // Flag pour arc large
    const largeArcFlag = angle > 180 ? 1 : 0
    
    // Path SVG
    const path = [
      `M ${center.value} ${center.value}`,
      `L ${x1} ${y1}`,
      `A ${radius.value} ${radius.value} 0 ${largeArcFlag} 1 ${x2} ${y2}`,
      'Z'
    ].join(' ')
    
    currentAngle += angle
    
    return {
      path,
      color: item.color || getColor(index),
      label: item.label,
      value: item.value,
      percentage: Math.round(percentage * 10) / 10
    }
  })
})

const tooltipPosition = computed(() => {
  // Position simple du tooltip (peut être améliorée)
  return {
    left: '50%',
    top: '20px'
  }
})

// Méthodes utilitaires
const getColor = (index) => {
  return props.colors[index % props.colors.length]
}

const getPercentage = (value) => {
  if (total.value === 0) return 0
  return Math.round((value / total.value) * 1000) / 10
}

const formatValue = (value) => {
  return props.valueFormatter(value)
}
</script>

<style scoped>
/* Animation de transition pour les segments */
path {
  transition: opacity 0.3s ease;
}

/* Amélioration du tooltip */
.tooltip {
  transform: translate(-50%, -100%);
}
</style>
