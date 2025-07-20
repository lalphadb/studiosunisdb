<template>
  <div class="bg-gray-800 p-4 rounded-xl shadow text-white text-center">
    <h4 class="text-sm text-gray-400 uppercase tracking-wide">{{ title }}</h4>
    <p class="text-2xl font-bold mt-1">{{ value }}</p>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  title: string
  value: string | number
}>()
</script>
