<template>
  <div class="progress-container">
    <!-- Label -->
    <div v-if="label" class="flex justify-between items-center mb-2">
      <span class="text-sm font-medium text-gray-300">{{ label }}</span>
      <span class="text-sm font-bold text-white">{{ displayValue }}</span>
    </div>

    <!-- Progress bar -->
    <div class="relative">
      <!-- Background -->
      <div
        class="w-full rounded-full overflow-hidden"
        :class="sizeClass"
        :style="{ backgroundColor: backgroundColor }"
      >
        <!-- Progress fill -->
        <div
          class="h-full transition-all duration-700 ease-out relative overflow-hidden rounded-full"
          :style="{
            width: animatedPercentage + '%',
            background: gradientStyle
          }"
        >
          <!-- Shine effect -->
          <div
            v-if="animated"
            class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent transform -skew-x-12 animate-shine"
          ></div>
        </div>
      </div>

      <!-- Glow effect -->
      <div
        v-if="glowEffect"
        class="absolute inset-0 rounded-full blur-sm opacity-30"
        :style="{
          background: gradientStyle,
          width: animatedPercentage + '%'
        }"
      ></div>
    </div>

    <!-- Additional info -->
    <div v-if="showStats" class="flex justify-between text-xs text-gray-400 mt-1">
      <span>{{ current }} / {{ total }}</span>
      <span>{{ Math.round(percentage) }}% complété</span>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'

const props = defineProps({
  percentage: {
    type: Number,
    required: true,
    validator: (value) => value >= 0 && value <= 100
  },
  label: {
    type: String,
    default: ''
  },
  color: {
    type: String,
    default: 'blue'
  },
  backgroundColor: {
    type: String,
    default: '#374151' // gray-700
  },
  size: {
    type: String,
    default: 'md', // 'sm', 'md', 'lg'
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  },
  animated: {
    type: Boolean,
    default: true
  },
  glowEffect: {
    type: Boolean,
    default: false
  },
  showStats: {
    type: Boolean,
    default: false
  },
  current: {
    type: Number,
    default: 0
  },
  total: {
    type: Number,
    default: 100
  },
  format: {
    type: String,
    default: 'percentage' // 'percentage', 'fraction', 'number'
  }
})

const animatedPercentage = ref(0)

// Color presets
const colorPresets = {
  blue: 'linear-gradient(90deg, #3B82F6, #1D4ED8)',
  green: 'linear-gradient(90deg, #10B981, #059669)',
  purple: 'linear-gradient(90deg, #8B5CF6, #7C3AED)',
  red: 'linear-gradient(90deg, #EF4444, #DC2626)',
  yellow: 'linear-gradient(90deg, #F59E0B, #D97706)',
  indigo: 'linear-gradient(90deg, #6366F1, #4F46E5)',
  pink: 'linear-gradient(90deg, #EC4899, #DB2777)',
  karate: 'linear-gradient(90deg, #1E3A8A, #3B82F6, #10B981)' // Thème karaté
}

const sizeClass = computed(() => {
  const sizes = {
    sm: 'h-2',
    md: 'h-3',
    lg: 'h-4'
  }
  return sizes[props.size]
})

const gradientStyle = computed(() => {
  return colorPresets[props.color] || props.color
})

const displayValue = computed(() => {
  switch (props.format) {
    case 'fraction':
      return `${props.current}/${props.total}`
    case 'number':
      return props.current.toString()
    default:
      return `${Math.round(props.percentage)}%`
  }
})

// Animation
const animateProgress = () => {
  const targetPercentage = Math.min(Math.max(props.percentage, 0), 100)
  const duration = 1000 // 1 second
  const steps = 60
  const increment = targetPercentage / steps
  let currentStep = 0

  const animate = () => {
    if (currentStep <= steps) {
      animatedPercentage.value = Math.min(currentStep * increment, targetPercentage)
      currentStep++
      requestAnimationFrame(animate)
    }
  }

  animate()
}

onMounted(() => {
  if (props.animated) {
    setTimeout(animateProgress, 100)
  } else {
    animatedPercentage.value = props.percentage
  }
})

watch(() => props.percentage, () => {
  if (props.animated) {
    animateProgress()
  } else {
    animatedPercentage.value = props.percentage
  }
})
</script>

<style scoped>
@keyframes shine {
  0% { transform: translateX(-100%) skewX(-12deg); }
  100% { transform: translateX(300%) skewX(-12deg); }
}

.animate-shine {
  animation: shine 2s ease-in-out infinite;
}
</style>
