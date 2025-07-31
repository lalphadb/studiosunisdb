<template>
  <div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center">
      <div class="flex-shrink-0">
        <div :class="iconClass" class="p-3 rounded-full">
          <span class="text-2xl">{{ icon }}</span>
        </div>
      </div>
      <div class="ml-4">
        <h3 class="text-lg font-semibold text-gray-900">{{ title }}</h3>
        <p class="text-3xl font-bold text-gray-700">{{ value }}</p>
        <p class="text-sm text-gray-500">{{ subtitle }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  value: [String, Number],
  subtitle: String,
  icon: String,
  iconClass: String
})
</script>
