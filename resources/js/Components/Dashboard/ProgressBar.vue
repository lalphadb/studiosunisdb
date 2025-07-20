<template>
  <div class="w-full bg-gray-700 rounded-full h-2">
    <div
      class="bg-blue-500 h-2 rounded-full transition-all"
      :style="{ width: percentage + '%' }"
    ></div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  value: number
  max?: number
  color?: string
}>()

const percentage = computed(() => {
  const max = props.max ?? 100
  const val = props.value ?? 0
  return Math.min(Math.round((val / max) * 100), 100)
})
</script>
