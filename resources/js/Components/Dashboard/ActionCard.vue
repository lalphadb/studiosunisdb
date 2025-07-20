<template>
  <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6 hover:bg-gray-800/70 transition-all">
    <div class="mb-6">
      <h3 class="text-lg font-semibold text-white">{{ title }}</h3>
      <p class="text-gray-400 text-sm">{{ subtitle }}</p>
    </div>
    <slot></slot>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  subtitle: String
})
</script>
