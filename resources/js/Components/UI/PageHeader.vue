<template>
  <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
    <div>
      <div class="flex items-center gap-3">
        <slot name="icon" />
        <h1 class="text-3xl font-bold tracking-tight bg-gradient-to-r from-indigo-300 to-purple-300 bg-clip-text text-transparent">
          {{ title }}
        </h1>
      </div>
      <p v-if="description" class="mt-1 text-sm text-slate-400 max-w-2xl">{{ description }}</p>
    </div>
    <div class="flex flex-wrap gap-2">
      <slot name="actions" />
    </div>
  </div>
</template>

<script setup lang="ts">
interface Props { title: string; description?: string }
const props = defineProps<Props>()
</script>
