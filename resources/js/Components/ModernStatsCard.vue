<template>
  <div class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-xl p-6 hover:border-blue-500/30 transition-all duration-300 group">
    <div class="flex items-center justify-between mb-4">
      <div class="flex items-center space-x-3">
        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center">
          <component :is="iconComponent" class="h-6 w-6 text-white" v-if="iconComponent" />
          <span v-else class="text-xl">📊</span>
        </div>
        <div>
          <h3 class="text-sm font-medium text-gray-400">{{ title }}</h3>
          <p class="text-2xl font-bold text-white">{{ formattedValue }}</p>
        </div>
      </div>
      
      <div v-if="change !== undefined" class="text-right">
        <div :class="{
          'text-green-400': change >= 0,
          'text-red-400': change < 0
        }" class="text-sm font-medium">
          {{ change >= 0 ? '+' : '' }}{{ change }}%
        </div>
      </div>
    </div>
    
    <div v-if="description" class="text-xs text-gray-500">
      {{ description }}
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import {
  UsersIcon,
  CheckCircleIcon,
  PlusCircleIcon,
  ChartBarIcon,
  CurrencyDollarIcon,
  ClockIcon,
  BellIcon
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
  format: {
    type: String,
    default: 'number' // number, currency, percentage
  },
  iconType: {
    type: String,
    default: 'heroicon'
  },
  iconName: {
    type: String,
    default: 'chart'
  },
  change: {
    type: Number,
    default: undefined
  },
  description: {
    type: String,
    default: ''
  }
})

// Icon mapping
const iconMap = {
  users: UsersIcon,
  check: CheckCircleIcon,
  plus: PlusCircleIcon,
  chart: ChartBarIcon,
  currency: CurrencyDollarIcon,
  clock: ClockIcon,
  bell: BellIcon
}

const iconComponent = computed(() => {
  return iconMap[props.iconName] || ChartBarIcon
})

const formattedValue = computed(() => {
  const value = props.value
  
  switch (props.format) {
    case 'currency':
      return new Intl.NumberFormat('fr-CA', {
        style: 'currency',
        currency: 'CAD'
      }).format(value)
    
    case 'percentage':
      return `${value}%`
    
    case 'number':
    default:
      return new Intl.NumberFormat('fr-CA').format(value)
  }
})
</script>
