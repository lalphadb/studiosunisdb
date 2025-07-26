<template>
  <component 
    :is="linkComponent"
    :href="href"
    :class="cardClasses"
    @click="handleClick"
  >
    <!-- Background gradient -->
    <div class="absolute inset-0 bg-gradient-to-br opacity-0 group-hover:opacity-100 transition-opacity duration-300" :style="{ background: hoverGradient }"></div>
    
    <!-- Content -->
    <div class="relative z-10 flex flex-col items-center justify-center h-full p-6">
      <!-- Icon -->
      <div class="mb-4">
        <component :is="iconComponent" v-if="iconComponent" class="h-12 w-12 text-white" />
        <div v-else class="text-4xl">{{ icon }}</div>
      </div>

      <!-- Title -->
      <h3 class="text-lg font-bold text-white mb-2 text-center">{{ title }}</h3>
      
      <!-- Description -->
      <p class="text-sm text-gray-300 text-center mb-4">{{ description }}</p>
      
      <!-- Badge/Status -->
      <div v-if="badge" class="px-3 py-1 rounded-full text-xs font-medium" :class="badgeClasses">
        {{ badge }}
      </div>

      <!-- Loading state -->
      <div v-if="loading" class="absolute inset-0 bg-gray-900/50 flex items-center justify-center">
        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-white"></div>
      </div>
    </div>

    <!-- Animated border on hover -->
    <div class="absolute inset-0 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">
      <div class="absolute inset-[1px] rounded-xl bg-gradient-to-br from-white/10 to-transparent"></div>
    </div>
  </component>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { 
  UserPlusIcon,
  ClipboardDocumentCheckIcon,
  CreditCardIcon,
  CalendarDaysIcon,
  ChartBarIcon,
  CogIcon,
  AcademicCapIcon,
  ShieldCheckIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  description: {
    type: String,
    required: true
  },
  href: {
    type: String,
    default: '#'
  },
  icon: {
    type: String,
    default: ''
  },
  iconName: {
    type: String,
    default: ''
  },
  color: {
    type: String,
    default: 'blue'
  },
  badge: {
    type: String,
    default: ''
  },
  badgeType: {
    type: String,
    default: 'info' // 'info', 'success', 'warning', 'danger'
  },
  disabled: {
    type: Boolean,
    default: false
  },
  loading: {
    type: Boolean,
    default: false
  },
  external: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['click'])

// Icon mapping
const iconMap = {
  'user-plus': UserPlusIcon,
  'clipboard': ClipboardDocumentCheckIcon,
  'credit-card': CreditCardIcon,
  'calendar': CalendarDaysIcon,
  'chart': ChartBarIcon,
  'cog': CogIcon,
  'academic': AcademicCapIcon,
  'shield': ShieldCheckIcon
}

const colorThemes = {
  blue: {
    base: 'from-blue-600 to-blue-700',
    hover: 'linear-gradient(135deg, #3B82F6, #1D4ED8)',
    hoverGradient: 'linear-gradient(135deg, rgba(59, 130, 246, 0.3), rgba(29, 78, 216, 0.3))'
  },
  green: {
    base: 'from-green-600 to-green-700',
    hover: 'linear-gradient(135deg, #10B981, #059669)',
    hoverGradient: 'linear-gradient(135deg, rgba(16, 185, 129, 0.3), rgba(5, 150, 105, 0.3))'
  },
  purple: {
    base: 'from-purple-600 to-purple-700',
    hover: 'linear-gradient(135deg, #8B5CF6, #7C3AED)',
    hoverGradient: 'linear-gradient(135deg, rgba(139, 92, 246, 0.3), rgba(124, 58, 237, 0.3))'
  },
  orange: {
    base: 'from-orange-600 to-orange-700',
    hover: 'linear-gradient(135deg, #F59E0B, #D97706)',
    hoverGradient: 'linear-gradient(135deg, rgba(245, 158, 11, 0.3), rgba(217, 119, 6, 0.3))'
  },
  karate: {
    base: 'from-indigo-600 to-blue-600',
    hover: 'linear-gradient(135deg, #4F46E5, #3B82F6)',
    hoverGradient: 'linear-gradient(135deg, rgba(79, 70, 229, 0.3), rgba(59, 130, 246, 0.3))'
  }
}

const iconComponent = computed(() => {
  return props.iconName ? iconMap[props.iconName] : null
})

const linkComponent = computed(() => {
  return props.external ? 'a' : Link
})

const cardClasses = computed(() => {
  const theme = colorThemes[props.color] || colorThemes.blue
  const baseClasses = [
    'group relative overflow-hidden rounded-xl',
    'bg-gradient-to-br backdrop-blur-lg',
    'border border-gray-700/50',
    'min-h-[200px] transition-all duration-300',
    'hover:scale-105 hover:shadow-2xl hover:border-white/20',
    'focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-900',
    theme.base
  ]

  if (props.disabled) {
    baseClasses.push('opacity-50 cursor-not-allowed')
  } else {
    baseClasses.push('cursor-pointer hover:shadow-lg')
  }

  return baseClasses.join(' ')
})

const badgeClasses = computed(() => {
  const types = {
    info: 'bg-blue-500/20 text-blue-300 border border-blue-500/30',
    success: 'bg-green-500/20 text-green-300 border border-green-500/30',
    warning: 'bg-yellow-500/20 text-yellow-300 border border-yellow-500/30',
    danger: 'bg-red-500/20 text-red-300 border border-red-500/30'
  }
  return types[props.badgeType] || types.info
})

const hoverGradient = computed(() => {
  const theme = colorThemes[props.color] || colorThemes.blue
  return theme.hoverGradient
})

const handleClick = (event) => {
  if (props.disabled || props.loading) {
    event.preventDefault()
    return
  }
  emit('click', event)
}
</script>

<style scoped>
/* Smooth animations */
.group:hover .relative {
  transform: translateY(-2px);
  transition: transform 0.3s ease;
}

/* Glow effect on hover */
.group:hover {
  box-shadow: 
    0 20px 25px -5px rgba(0, 0, 0, 0.1),
    0 10px 10px -5px rgba(0, 0, 0, 0.04),
    0 0 20px rgba(59, 130, 246, 0.4);
}
</style>
