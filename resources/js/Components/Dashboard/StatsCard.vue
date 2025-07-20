<template>
  <div 
    @click="$emit('click')"
    class="group relative overflow-hidden rounded-xl p-6 cursor-pointer hover:scale-105 transition-all duration-300 hover:shadow-xl"
    :class="cardClasses"
  >
    <!-- Background gradient -->
    <div class="absolute inset-0 bg-gradient-to-br" :class="color"></div>
    <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
    
    <!-- Content -->
    <div class="relative z-10">
      <div class="flex items-center justify-between mb-4">
        <div class="text-3xl">{{ icon }}</div>
        <div v-if="changeType" class="flex items-center space-x-1 text-sm">
          <svg v-if="changeType === 'positive'" class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"></path>
          </svg>
          <svg v-else class="w-4 h-4 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 7l-9.2 9.2M7 7v10h10"></path>
          </svg>
          <span :class="changeType === 'positive' ? 'text-green-300' : 'text-red-300'">
            {{ Math.abs(change) }}%
          </span>
        </div>
      </div>
      
      <div>
        <p class="text-3xl font-bold text-white mb-2">{{ value }}</p>
        <p class="text-white/80 text-sm font-medium">{{ title }}</p>
      </div>
    </div>
    
    <!-- Decoration circles -->
    <div class="absolute -top-2 -right-2 w-16 h-16 bg-white/10 rounded-full"></div>
    <div class="absolute -bottom-4 -left-4 w-12 h-12 bg-white/5 rounded-full"></div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  title: String,
  value: [String, Number],
  change: Number,
  changeType: String, // 'positive' | 'negative'
  icon: String,
  color: {
    type: String,
    default: 'from-blue-500 to-blue-600'
  }
})

defineEmits(['click'])

const cardClasses = computed(() => {
  return 'bg-gradient-to-br border border-white/10 shadow-lg'
})
</script>
