<template>
  <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6 hover:bg-gray-800/70 transition-all">
    <!-- Header -->
    <div class="mb-6">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="text-xl font-bold text-white">{{ title }}</h3>
          <p class="text-gray-400 text-sm mt-1">{{ subtitle }}</p>
        </div>
        <div class="flex items-center space-x-2">
          <!-- Menu options -->
          <button class="p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
            </svg>
          </button>
        </div>
      </div>
    </div>
    
    <!-- Chart content -->
    <div class="relative">
      <slot></slot>
    </div>
    
    <!-- Footer stats (optional) -->
    <div v-if="footerStats" class="mt-6 pt-4 border-t border-gray-700/50">
      <div class="grid grid-cols-3 gap-4">
        <div v-for="stat in footerStats" :key="stat.label" class="text-center">
          <p class="text-white font-semibold">{{ stat.value }}</p>
          <p class="text-gray-400 text-xs">{{ stat.label }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  subtitle: String,
  footerStats: {
    type: Array,
    default: null
  }
})
</script>
