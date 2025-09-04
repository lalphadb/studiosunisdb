<template>
  <div class="relative overflow-hidden rounded-2xl p-6 bg-slate-900/50 backdrop-blur-xl border border-slate-700/50 group hover:border-slate-600/50 transition-all duration-300">
    <!-- Background gradient animé -->
    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
      <div :class="`absolute inset-0 bg-gradient-to-br ${color} opacity-10`"></div>
    </div>
    
    <!-- Contenu -->
    <div class="relative">
      <div class="flex items-start justify-between">
        <div>
          <p class="text-sm text-slate-400 font-medium">{{ title }}</p>
          <div class="mt-2 flex items-baseline gap-2">
            <p class="text-3xl font-bold text-white">
              <span v-if="loading" class="inline-block w-20 h-8 bg-slate-700 animate-pulse rounded"></span>
              <span v-else>{{ animatedValue }}</span>
            </p>
            <span v-if="change && !loading" :class="changeClass" class="text-sm font-medium flex items-center gap-1">
              <svg v-if="isPositive" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
              </svg>
              <svg v-else class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
              </svg>
              {{ change }}
            </span>
          </div>
        </div>
        
        <!-- Icône -->
        <div :class="`p-3 rounded-xl bg-gradient-to-br ${color} opacity-20 group-hover:opacity-30 transition-opacity`">
          <component :is="iconComponent" class="w-6 h-6 text-white" />
        </div>
      </div>
      
      <!-- Barre de progression optionnelle -->
      <div v-if="showProgress" class="mt-4">
        <div class="h-1 bg-slate-800 rounded-full overflow-hidden">
          <div 
            :class="`h-full rounded-full bg-gradient-to-r ${color} transition-all duration-1000`"
            :style="`width: ${progressValue}%`"
          ></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted, watch } from 'vue'

// Icônes SVG inline comme composants
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

const ChartIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
  </svg>`
}

const DollarIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
  </svg>`
}

export default {
  components: {
    UsersIcon,
    CalendarIcon,
    ChartIcon,
    DollarIcon
  },
  
  props: {
    title: {
      type: String,
      required: true
    },
    value: {
      type: [String, Number],
      required: true
    },
    change: {
      type: String,
      default: null
    },
    icon: {
      type: String,
      default: 'users'
    },
    color: {
      type: String,
      default: 'from-blue-500 to-blue-600'
    },
    loading: {
      type: Boolean,
      default: false
    },
    showProgress: {
      type: Boolean,
      default: false
    },
    progressValue: {
      type: Number,
      default: 0
    }
  },
  
  setup(props) {
    const animatedValue = ref(props.value)
    
    const isPositive = computed(() => {
      return props.change && props.change.startsWith('+')
    })
    
    const changeClass = computed(() => {
      return isPositive.value ? 'text-emerald-400' : 'text-red-400'
    })
    
    const iconComponent = computed(() => {
      const icons = {
        'users': 'UsersIcon',
        'calendar': 'CalendarIcon',
        'chart': 'ChartIcon',
        'dollar': 'DollarIcon'
      }
      return icons[props.icon] || 'UsersIcon'
    })
    
    // Animation du compteur
    const animateValue = (start, end, duration) => {
      const isNumber = !isNaN(parseInt(end))
      if (!isNumber) {
        animatedValue.value = end
        return
      }
      
      const endValue = parseInt(end)
      const startValue = parseInt(start) || 0
      const range = endValue - startValue
      const increment = range / (duration / 16)
      let current = startValue
      
      const timer = setInterval(() => {
        current += increment
        if ((increment > 0 && current >= endValue) || (increment < 0 && current <= endValue)) {
          animatedValue.value = props.value
          clearInterval(timer)
        } else {
          animatedValue.value = Math.round(current).toString()
          if (props.value.includes('k$')) {
            animatedValue.value = (current / 1000).toFixed(1) + 'k$'
          } else if (props.value.includes('%')) {
            animatedValue.value = Math.round(current) + '%'
          }
        }
      }, 16)
    }
    
    onMounted(() => {
      setTimeout(() => {
        animateValue(0, props.value, 1000)
      }, 100)
    })
    
    watch(() => props.value, (newVal, oldVal) => {
      animateValue(oldVal, newVal, 500)
    })
    
    return {
      animatedValue,
      isPositive,
      changeClass,
      iconComponent
    }
  }
}
</script>
