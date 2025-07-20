<template>
  <div
    :class="color"
    class="rounded-xl p-6 text-white shadow-md hover:scale-[1.02] transition-transform cursor-pointer"
    @click="handleClick"
  >
    <div class="flex items-center justify-between">
      <div>
        <h3 class="text-lg font-medium">{{ title }}</h3>
        <p class="text-3xl font-bold">{{ value }}</p>
      </div>
      <div class="text-4xl">{{ icon }}</div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  title: string
  value: string | number
  icon: string
  color?: string
}>()

const emit = defineEmits(['click'])

function handleClick() {
  emit('click')
}
</script>
