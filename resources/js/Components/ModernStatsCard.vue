<template>
  <div class="bg-gradient-to-br from-blue-900/60 to-indigo-900/60 backdrop-blur-xl border border-blue-800/50 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
    <div class="flex items-center justify-between mb-4">
      <div class="w-12 h-12 bg-gradient-to-br from-blue-500/20 to-indigo-600/20 rounded-lg flex items-center justify-center">
        <component :is="iconComponent" class="h-6 w-6 text-blue-300" />
      </div>
      <div v-if="trend" class="flex items-center space-x-1">
        <ArrowTrendingUpIcon v-if="trendIsPositive" class="h-4 w-4 text-green-400" />
        <ArrowTrendingDownIcon v-else class="h-4 w-4 text-red-400" />
        <span :class="trendClass" class="text-sm font-medium">
          {{ trend }}
        </span>
      </div>
    </div>
    
    <div>
      <p class="text-blue-300 text-sm font-medium mb-1">{{ title }}</p>
      <p class="text-3xl font-bold text-white">{{ formattedValue }}</p>
      <p v-if="description" class="text-blue-400 text-xs mt-1">{{ description }}</p>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import {
  UsersIcon,
  UserPlusIcon,
  ChartBarIcon,
  AcademicCapIcon,
  CurrencyDollarIcon,
  ArrowTrendingUpIcon,
  ArrowTrendingDownIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
  title: String,
  value: [Number, String],
  iconType: String,
  iconName: String,
  format: {
    type: String,
    default: 'number'
  },
  description: String,
  trend: String,
  trendType: {
    type: String,
    default: 'auto'
  }
})

const iconMap = {
  users: UsersIcon,
  'user-plus': UserPlusIcon,
  chart: ChartBarIcon,
  academic: AcademicCapIcon,
  currency: CurrencyDollarIcon
}

const iconComponent = computed(() => {
  return iconMap[props.iconName] || UsersIcon
})

const formattedValue = computed(() => {
  if (props.format === 'currency') {
    return new Intl.NumberFormat('fr-CA', {
      style: 'currency',
      currency: 'CAD'
    }).format(props.value)
  } else if (props.format === 'percentage') {
    return `${props.value}%`
  } else {
    return new Intl.NumberFormat('fr-CA').format(props.value)
  }
})

const trendIsPositive = computed(() => {
  if (!props.trend) return false
  return props.trend.startsWith('+')
})

const trendClass = computed(() => {
  if (props.trendType === 'info') return 'text-blue-400'
  return trendIsPositive.value ? 'text-green-400' : 'text-red-400'
})
</script>
