<template>
  <button 
    @click="$emit('click')"
    class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-xl p-6 border border-slate-700/50 hover:from-slate-800/70 hover:to-slate-900/70 transition-all duration-300 group cursor-pointer text-left w-full"
  >
    <!-- Effet de lumière décoratif -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-600/5 via-transparent to-purple-600/5 pointer-events-none"></div>
    
    <div class="relative flex items-center justify-between">
      <div>
        <h3 class="text-lg font-semibold text-white group-hover:text-blue-400 transition-colors">
          {{ title }}
        </h3>
        <p class="mt-1 text-sm text-slate-400">
          {{ description }}
        </p>
      </div>
      <div class="p-3 bg-gradient-to-br from-blue-500/20 to-purple-600/20 rounded-xl group-hover:from-blue-500/30 group-hover:to-purple-600/30 transition-all">
        <svg class="w-6 h-6 text-blue-400 transform group-hover:translate-x-1 transition-transform" 
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M17 8l4 4m0 0l-4 4m4-4H3" />
        </svg>
      </div>
    </div>
  </button>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true
  },
  description: {
    type: String,
    required: true
  }
})

defineEmits(['click'])
</script>
