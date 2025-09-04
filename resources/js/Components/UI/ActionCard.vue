<template>
  <div
    class="group relative overflow-hidden rounded-2xl border border-slate-700/50 bg-slate-900/60 p-5 hover:border-indigo-500/50 transition cursor-pointer flex flex-col"
    @click="handleClick"
  >
    <div class="flex items-start justify-between mb-2">
      <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-slate-700/50 text-slate-300 group-hover:bg-indigo-600/30 group-hover:text-indigo-200 transition">
        <slot name="icon">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/></svg>
        </slot>
      </div>
    </div>
    <h3 class="text-sm font-semibold text-slate-200 mb-1">{{ title }}</h3>
    <p class="text-xs text-slate-400 line-clamp-2 flex-1">{{ description }}</p>
    <div class="mt-3 text-xs font-medium text-indigo-300 group-hover:text-indigo-200 inline-flex items-center gap-1">
      <span>{{ ctaLabel }}</span>
      <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
    </div>
  </div>
</template>
<script setup lang="ts">
import { router } from '@inertiajs/vue3'
interface Props { title: string; description?: string; to?: string; cta?: string }
const props = withDefaults(defineProps<Props>(), { description: '', cta: 'Ouvrir' })
const emit = defineEmits<{ (e:'click'): void }>()

function handleClick() {
  if (props.to) router.visit(props.to)
  emit('click')
}

const ctaLabel = props.cta
</script>
<style scoped>
</style>
