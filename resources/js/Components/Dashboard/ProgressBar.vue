<template>
  <div class="bg-white rounded-lg shadow p-4">
    <div class="flex items-center justify-between mb-2">
      <span class="text-sm font-medium text-gray-700">{{ label }}</span>
      <span class="text-sm text-gray-500">{{ value }}%</span>
    </div>
    <div class="w-full bg-gray-200 rounded-full h-2">
      <div 
        class="bg-blue-600 h-2 rounded-full transition-all duration-300"
        :style="`width: ${value}%`"
      ></div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  label: String,
  value: Number
})
</script>
