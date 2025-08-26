<template>
  <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 p-5 flex flex-col">
    <div class="flex items-start justify-between mb-4">
      <div>
        <h3 class="text-sm font-semibold text-slate-200">{{ title }}</h3>
        <p v-if="subtitle" class="text-xs text-slate-400 mt-0.5">{{ subtitle }}</p>
      </div>
      <slot name="toolbar" />
    </div>
    <div class="min-h-[180px] flex-1 flex items-center justify-center">
      <slot>
        <p class="text-xs text-slate-500">Graphique à intégrer…</p>
      </slot>
    </div>
  </div>
</template>
<script setup lang="ts">
interface Props { title: string; subtitle?: string }
const props = defineProps<Props>()
</script>
