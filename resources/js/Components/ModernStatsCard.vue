<template>
  <div class="group relative overflow-hidden rounded-xl bg-gradient-to-br from-gray-800/50 to-gray-900/50 backdrop-blur-xl border border-gray-700/50 p-6 transition-all duration-300 hover:scale-105 hover:border-blue-500/50 hover:shadow-2xl hover:shadow-blue-500/20">
    <!-- Glow effect -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-600/10 to-purple-600/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

    <!-- Content -->
    <div class="relative z-10">
      <!-- Header with icon -->
      <div class="flex items-center justify-between mb-4">
        <component :is="iconComponent" v-if="iconComponent" class="h-8 w-8 text-blue-400" />
        <div v-else class="text-2xl">{{ icon }}</div>

        <!-- Trend indicator -->
        <div v-if="change !== undefined" class="flex items-center space-x-1">
          <component :is="trendIcon" class="h-4 w-4" :class="trendColor" />
          <span class="text-sm font-medium" :class="trendColor">
            {{ Math.abs(change) }}%
          </span>
        </div>
      </div>

      <!-- Value -->
      <div class="mb-2">
        <div class="text-3xl font-bold text-white tracking-tight">
          {{ formattedValue }}
        </div>
        <div class="text-sm text-gray-400 font-medium">{{ title }}</div>
      </div>

      <!-- Progress bar if goal is set -->
      <div v-if="goal" class="mt-4">
        <div class="flex justify-between text-xs text-gray-400 mb-1">
          <span>Objectif</span>
          <span>{{ Math.round(progressPercentage) }}%</span>
        </div>
        <div class="w-full bg-gray-700 rounded-full h-2">
          <div
            class="bg-gradient-to-r from-blue-400 to-blue-600 h-2 rounded-full transition-all duration-500 ease-out"
            :style="{ width: Math.min(progressPercentage, 100) + '%' }"
          ></div>
        </div>
      </div>

      <!-- Description -->
      <div v-if="description" class="mt-3 text-xs text-gray-500">
        {{ description }}
      </div>
    </div>

    <!-- Animated border -->
    <div class="absolute inset-0 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">
      <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-blue-600 via-purple-600 to-blue-600 p-[1px] animate-pulse">
        <div class="w-full h-full rounded-xl bg-gray-900"></div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import {
  ChevronUpIcon,
  ChevronDownIcon,
  UsersIcon,
  CurrencyDollarIcon,
  CheckCircleIcon,
  ChartBarIcon,
  CalendarIcon,
  AcademicCapIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  value: {
    type: [Number, String],
    required: true
  },
  icon: {
    type: String,
    default: ''
  },
  iconType: {
    type: String,
    default: 'emoji' // 'emoji' or 'heroicon'
  },
  iconName: {
    type: String,
    default: ''
  },
  change: {
    type: Number,
    default: undefined
  },
  goal: {
    type: Number,
    default: undefined
  },
  description: {
    type: String,
    default: ''
  },
  format: {
    type: String,
    default: 'number' // 'number', 'currency', 'percentage'
  }
})

// Icon mapping
const iconMap = {
  'users': UsersIcon,
  'currency': CurrencyDollarIcon,
  'check': CheckCircleIcon,
  'chart': ChartBarIcon,
  'calendar': CalendarIcon,
  'academic': AcademicCapIcon
}

const iconComponent = computed(() => {
  if (props.iconType === 'heroicon' && props.iconName) {
    return iconMap[props.iconName]
  }
  return null
})

const formattedValue = computed(() => {
  const numValue = typeof props.value === 'string' ? parseFloat(props.value) || 0 : props.value

  switch (props.format) {
    case 'currency':
      return new Intl.NumberFormat('fr-CA', {
        style: 'currency',
        currency: 'CAD'
      }).format(numValue)
    case 'percentage':
      return `${numValue}%`
    default:
      return new Intl.NumberFormat('fr-CA').format(numValue)
  }
})

const trendIcon = computed(() => {
  return props.change >= 0 ? ChevronUpIcon : ChevronDownIcon
})

const trendColor = computed(() => {
  return props.change >= 0 ? 'text-green-400' : 'text-red-400'
})

const progressPercentage = computed(() => {
  if (!props.goal) return 0
  const numValue = typeof props.value === 'string' ? parseFloat(props.value) || 0 : props.value
  return (numValue / props.goal) * 100
})
</script>

<style scoped>
@keyframes glow {
  0%, 100% { opacity: 0.3; }
  50% { opacity: 0.8; }
}

.animate-glow {
  animation: glow 2s ease-in-out infinite;
}
</style>
