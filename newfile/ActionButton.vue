<template>
  <button 
    @click="$emit('click')"
    :class="color"
    class="group relative overflow-hidden rounded-xl p-4 text-white hover:scale-105 transition-all duration-300 hover:shadow-lg"
  >
    <div class="relative z-10">
      <div class="text-2xl mb-2">{{ icon }}</div>
      <h4 class="font-semibold text-sm">{{ title }}</h4>
      <p class="text-xs opacity-90">{{ subtitle }}</p>
    </div>
    <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
  </button>
</template>

<script setup>
defineProps({
  icon: String,
  title: String,
  subtitle: String,
  color: String
})

defineEmits(['click'])
</script>