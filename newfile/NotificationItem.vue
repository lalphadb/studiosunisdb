<template>
  <div 
    @click="$emit('click')"
    class="group flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-700/30 transition-all cursor-pointer"
  >
    <div :class="iconClasses" class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
      <!-- Icône warning -->
      <svg v-if="type === 'warning'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
      </svg>
      <!-- Icône success -->
      <svg v-else-if="type === 'success'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
      </svg>
      <!-- Icône error -->
      <svg v-else-if="type === 'error'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
      </svg>
      <!-- Icône info (default) -->
      <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
      </svg>
    </div>
    
    <div class="flex-1 min-w-0">
      <p class="text-white font-medium text-sm group-hover:text-blue-400 transition-colors">{{ title }}</p>
      <p class="text-gray-400 text-xs mt-1">{{ message }}</p>
      <p class="text-gray-500 text-xs mt-1">{{ timeAgo }}</p>
    </div>
    
    <div class="flex-shrink-0">
      <svg class="w-4 h-4 text-gray-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
      </svg>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  type: String, // 'warning', 'info', 'success', 'error'
  title: String,
  message: String,
  timeAgo: {
    type: String,
    default: 'Il y a quelques minutes'
  }
})

defineEmits(['click'])

const iconClasses = computed(() => {
  const classes = {
    warning: 'bg-yellow-500/20 text-yellow-400',
    info: 'bg-blue-500/20 text-blue-400',
    success: 'bg-green-500/20 text-green-400',
    error: 'bg-red-500/20 text-red-400'
  }
  return classes[props.type] || classes.info
})
</script>