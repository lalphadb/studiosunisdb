<template>
  <div 
    @click="handleClick"
    :class="[
      'action-card group relative overflow-hidden',
      'p-6 rounded-2xl',
      'bg-slate-900/50 backdrop-blur-xl',
      'border border-slate-700/50',
      'transition-all duration-300',
      'hover:border-slate-600/50 hover:shadow-2xl',
      'cursor-pointer transform hover:scale-[1.02]',
      disabled && 'opacity-50 cursor-not-allowed hover:scale-100'
    ]"
  >
    <!-- Background gradient effect -->
    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
      <div :class="`absolute inset-0 bg-gradient-to-br ${color} opacity-10`"></div>
    </div>
    
    <!-- Content -->
    <div class="relative">
      <div class="flex items-start justify-between mb-4">
        <!-- Icon -->
        <div :class="[
          'p-3 rounded-xl',
          `bg-gradient-to-br ${color} opacity-20 group-hover:opacity-30`,
          'transition-all duration-300 group-hover:scale-110'
        ]">
          <component :is="iconComponent" class="w-6 h-6 text-white" />
        </div>
        
        <!-- Status badge -->
        <div v-if="badge" :class="[
          'px-3 py-1 rounded-full text-xs font-medium',
          badgeVariant === 'success' && 'bg-emerald-500/20 text-emerald-400',
          badgeVariant === 'warning' && 'bg-amber-500/20 text-amber-400',
          badgeVariant === 'danger' && 'bg-red-500/20 text-red-400',
          badgeVariant === 'info' && 'bg-blue-500/20 text-blue-400'
        ]">
          {{ badge }}
        </div>
      </div>
      
      <!-- Title and description -->
      <h3 class="text-lg font-semibold text-white mb-2 group-hover:text-blue-400 transition-colors">
        {{ title }}
      </h3>
      <p class="text-sm text-slate-400 mb-4">
        {{ description }}
      </p>
      
      <!-- Stats or additional info -->
      <div v-if="stats" class="flex items-center gap-4">
        <div v-for="stat in stats" :key="stat.label" class="flex items-center gap-2">
          <span class="text-xs text-slate-500">{{ stat.label }}:</span>
          <span class="text-sm font-medium text-white">{{ stat.value }}</span>
        </div>
      </div>
      
      <!-- Action button or link -->
      <div class="mt-4 flex items-center justify-between">
        <span class="text-sm text-blue-400 group-hover:text-blue-300 font-medium">
          {{ actionText }}
        </span>
        <svg class="w-5 h-5 text-blue-400 group-hover:text-blue-300 transform group-hover:translate-x-1 transition-all" 
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </div>
    </div>
    
    <!-- Loading overlay -->
    <div v-if="loading" class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm flex items-center justify-center rounded-2xl">
      <svg class="w-8 h-8 text-blue-500 animate-spin" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
      </svg>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'

// Icônes SVG inline comme composants
const PlusIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
  </svg>`
}

const UsersIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
  </svg>`
}

const CalendarIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
  </svg>`
}

const ClipboardIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
  </svg>`
}

const ChartIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
  </svg>`
}

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  description: {
    type: String,
    required: true
  },
  icon: {
    type: String,
    default: 'plus'
  },
  color: {
    type: String,
    default: 'from-blue-500 to-indigo-600'
  },
  actionText: {
    type: String,
    default: 'Voir plus'
  },
  route: String,
  href: String,
  badge: String,
  badgeVariant: {
    type: String,
    default: 'info',
    validator: (value) => ['success', 'warning', 'danger', 'info'].includes(value)
  },
  stats: Array,
  loading: {
    type: Boolean,
    default: false
  },
  disabled: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['click'])

const iconComponent = computed(() => {
  const icons = {
    'plus': PlusIcon,
    'users': UsersIcon,
    'calendar': CalendarIcon,
    'clipboard': ClipboardIcon,
    'chart': ChartIcon
  }
  return icons[props.icon] || PlusIcon
})

const handleClick = () => {
  if (props.disabled || props.loading) return
  
  if (props.route) {
    router.visit(route(props.route))
  } else if (props.href) {
    window.location.href = props.href
  } else {
    emit('click')
  }
}
</script>
