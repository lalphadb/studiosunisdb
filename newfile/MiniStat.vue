<template>
  <div class="bg-gray-800/30 backdrop-blur-sm border border-gray-700/30 rounded-lg p-4 hover:bg-gray-700/30 transition-all">
    <div class="text-center">
      <p class="text-2xl font-bold text-white mb-1">{{ value }}</p>
      <p class="text-xs text-gray-400 uppercase tracking-wide">{{ label }}</p>
    </div>
  </div>
</template>

<script setup>
defineProps({
  label: String,
  value: String
})
</script><template>
  <div class="bg-gray-800/30 backdrop-blur-sm border border-gray-700/30 rounded-lg p-4 hover:bg-gray-700/30 transition-all">
    <div class="text-center">
      <p class="text-2xl font-bold text-white mb-1">{{ value }}</p>
      <p class="text-xs text-gray-400 uppercase tracking-wide">{{ label }}</p>
    </div>
  </div>
</template>

<script setup>
defineProps({
  label: String,
  value: String
})
</script>