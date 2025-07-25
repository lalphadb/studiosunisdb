<template>
  <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <!-- Header avec icône et titre -->
    <div class="flex items-center justify-between mb-4">
      <div class="flex items-center space-x-3">
        <div :class="[
          'p-3 rounded-lg',
          `bg-gradient-to-r ${gradientColor}`
        ]">
          <component :is="iconComponent" class="h-6 w-6 text-white" />
        </div>
        <div>
          <h3 class="text-sm font-medium text-gray-600">{{ title }}</h3>
          <p class="text-2xl font-bold text-gray-900">{{ value }}</p>
        </div>
      </div>
      
      <!-- Badge de changement -->
      <div v-if="change" :class="[
        'px-2 py-1 rounded-full text-xs font-medium',
        changeType === 'positive' 
          ? 'bg-green-100 text-green-800' 
          : changeType === 'negative' 
            ? 'bg-red-100 text-red-800' 
            : 'bg-gray-100 text-gray-800'
      ]">
        {{ change }}
      </div>
    </div>
    
    <!-- Description optionnelle -->
    <p v-if="description" class="text-sm text-gray-500">
      {{ description }}
    </p>
    
    <!-- Mini graphique ou progression -->
    <div v-if="showProgress" class="mt-4">
      <div class="flex justify-between text-xs text-gray-500 mb-1">
        <span>Progression</span>
        <span>{{ progressPercent }}%</span>
      </div>
      <div class="w-full bg-gray-200 rounded-full h-2">
        <div 
          :class="`bg-gradient-to-r ${gradientColor} rounded-full h-2 transition-all duration-500`"
          :style="{ width: `${progressPercent}%` }"
        ></div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import type { Component } from 'vue'

// Props avec types TypeScript stricts
interface Props {
  title: string
  value: string | number
  description?: string
  change?: string
  changeType?: 'positive' | 'negative' | 'neutral'
  icon: string
  color?: string
  showProgress?: boolean
  progressPercent?: number
}

const props = withDefaults(defineProps<Props>(), {
  changeType: 'neutral',
  color: 'from-blue-500 to-blue-600',
  showProgress: false,
  progressPercent: 0
})

// Computed pour l'icône dynamique
const iconComponent = computed((): Component => {
  // Map des icônes disponibles
  const iconMap: Record<string, Component> = {
    'users': () => import('@heroicons/vue/24/outline/UsersIcon'),
    'academic-cap': () => import('@heroicons/vue/24/outline/AcademicCapIcon'),
    'calendar': () => import('@heroicons/vue/24/outline/CalendarIcon'),
    'currency-dollar': () => import('@heroicons/vue/24/outline/CurrencyDollarIcon'),
    'chart-bar': () => import('@heroicons/vue/24/outline/ChartBarIcon'),
    'trophy': () => import('@heroicons/vue/24/outline/TrophyIcon'),
    'clock': () => import('@heroicons/vue/24/outline/ClockIcon'),
    'check-circle': () => import('@heroicons/vue/24/outline/CheckCircleIcon')
  }
  
  return iconMap[props.icon] || iconMap['chart-bar']
})

// Computed pour le gradient de couleur sécurisé
const gradientColor = computed((): string => {
  if (!props.color) return 'from-blue-500 to-blue-600'
  
  // Validation sécurisée des couleurs Tailwind
  const validGradients = [
    'from-blue-500 to-blue-600',
    'from-green-500 to-green-600', 
    'from-yellow-500 to-yellow-600',
    'from-red-500 to-red-600',
    'from-purple-500 to-purple-600',
    'from-indigo-500 to-indigo-600',
    'from-pink-500 to-pink-600',
    'from-gray-500 to-gray-600'
  ]
  
  return validGradients.includes(props.color) ? props.color : 'from-blue-500 to-blue-600'
})
</script>

<style scoped>
/* Animations personnalisées */
.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 300ms;
}

/* Hover effects */
.bg-white:hover {
  transform: translateY(-1px);
  box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}
</style>
