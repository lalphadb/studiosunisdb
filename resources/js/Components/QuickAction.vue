<template>
  <component
    :is="component"
    :href="href"
    :class="[
      'group relative bg-gray-800 hover:bg-gray-700 border border-gray-700 hover:border-blue-500',
      'rounded-xl p-6 transition-all duration-300 transform hover:scale-105 hover:shadow-xl',
      'cursor-pointer text-left overflow-hidden',
      { 'opacity-50 cursor-not-allowed': disabled }
    ]"
    @click="handleClick"
  >
    <!-- Effet de gradient au survol -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-600/20 to-purple-600/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
    
    <!-- Contenu principal -->
    <div class="relative z-10">
      <!-- Icône et badge -->
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center space-x-3">
          <!-- Icône -->
          <div
            :class="[
              'w-12 h-12 rounded-lg flex items-center justify-center text-xl',
              'bg-blue-600 group-hover:bg-blue-500 transition-colors duration-300',
              iconClass
            ]"
          >
            <span v-if="icon">{{ icon }}</span>
            <slot v-else name="icon"></slot>
          </div>
          
          <!-- Badge/Notification -->
          <div
            v-if="badge"
            :class="[
              'px-2 py-1 rounded-full text-xs font-semibold',
              badgeClass
            ]"
          >
            {{ badge }}
          </div>
        </div>
        
        <!-- Flèche -->
        <div class="text-gray-400 group-hover:text-blue-400 transition-colors duration-300 transform group-hover:translate-x-1">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </div>
      </div>
      
      <!-- Titre -->
      <h3 class="text-white font-semibold text-lg mb-2 group-hover:text-blue-100 transition-colors duration-300">
        {{ title }}
      </h3>
      
      <!-- Description -->
      <p v-if="description" class="text-gray-300 text-sm leading-relaxed mb-4">
        {{ description }}
      </p>
      
      <!-- Statistiques/Métriques -->
      <div v-if="metrics && metrics.length" class="flex items-center space-x-4">
        <div
          v-for="metric in metrics"
          :key="metric.label"
          class="flex items-center space-x-2"
        >
          <div class="w-2 h-2 rounded-full bg-blue-400"></div>
          <span class="text-gray-300 text-xs">
            {{ metric.label }}: 
            <span class="text-white font-semibold">{{ metric.value }}</span>
          </span>
        </div>
      </div>
      
      <!-- Statut/État -->
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
    
    <!-- Effet de loading -->
    <div
      v-if="loading"
      class="absolute inset-0 bg-gray-800/50 flex items-center justify-center rounded-xl"
    >
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
    </div>
  </component>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  description: {
    type: String,
    default: ''
  },
  icon: {
    type: String,
    default: ''
  },
  iconClass: {
    type: String,
    default: ''
  },
  href: {
    type: String,
    default: null
  },
  route: {
    type: String,
    default: null
  },
  routeParams: {
    type: Object,
    default: () => ({})
  },
  external: {
    type: Boolean,
    default: false
  },
  target: {
    type: String,
    default: '_self'
  },
  badge: {
    type: [String, Number],
    default: null
  },
  badgeType: {
    type: String,
    default: 'info', // info, success, warning, danger
    validator: (value) => ['info', 'success', 'warning', 'danger'].includes(value)
  },
  metrics: {
    type: Array,
    default: () => []
    // Format: [{ label: 'Label', value: 'Value' }]
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
  disabled: {
    type: Boolean,
    default: false
  },
  loading: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['click'])

// Déterminer le composant à utiliser
const component = computed(() => {
  if (props.disabled || props.loading) return 'div'
  if (props.href && !props.route) {
    return props.external ? 'a' : Link
  }
  if (props.route) return Link
  return 'button'
})

// URL finale
const finalHref = computed(() => {
  if (props.route) {
    return route(props.route, props.routeParams)
  }
  return props.href
})

// Classes pour le badge
const badgeClass = computed(() => {
  const baseClasses = 'px-2 py-1 rounded-full text-xs font-semibold'
  const typeClasses = {
    info: 'bg-blue-600 text-blue-100',
    success: 'bg-green-600 text-green-100',
    warning: 'bg-orange-600 text-orange-100',
    danger: 'bg-red-600 text-red-100'
  }
  return `${baseClasses} ${typeClasses[props.badgeType]}`
})

// Couleur pour le statut
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
const handleClick = (event) => {
  if (props.disabled || props.loading) {
    event.preventDefault()
    return
  }
  
  if (!props.href && !props.route) {
    emit('click', event)
  }
}
</script>

<style scoped>
/* Animation personnalisée pour l'effet hover */
@keyframes pulse-border {
  0%, 100% { 
    border-color: rgb(55, 65, 81); /* gray-700 */
  }
  50% { 
    border-color: rgb(59, 130, 246); /* blue-500 */
  }
}

.group:hover {
  animation: pulse-border 2s ease-in-out infinite;
}

/* Effet de brillance subtile */
.group::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(
    90deg,
    transparent,
    rgba(255, 255, 255, 0.1),
    transparent
  );
  transition: left 0.5s ease;
}

.group:hover::before {
  left: 100%;
}
</style>
