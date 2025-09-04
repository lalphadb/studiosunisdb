<template>
  <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 p-5 flex flex-col">
    <div class="flex items-start justify-between mb-4">
      <h3 class="text-sm font-semibold text-slate-200">{{ title }}</h3>
      <slot name="toolbar" />
    </div>
    <ul class="space-y-3" v-if="items?.length">
      <li v-for="(item,i) in items" :key="i" class="flex items-start gap-3">
        <div class="w-2 h-2 mt-2 rounded-full" :class="dotClass(item.type)"></div>
        <div class="flex-1 min-w-0">
          <p class="text-xs text-slate-300 leading-snug" v-html="item.label" />
          <p class="text-[10px] uppercase tracking-wide font-medium text-slate-500 mt-1">{{ item.time }}</p>
        </div>
      </li>
    </ul>
    <div v-else class="text-xs text-slate-500 py-6 text-center">Aucune activité récente</div>
  </div>
</template>
<script setup lang="ts">
interface ActivityItem { label: string; time: string; type?: string }
interface Props { title: string; items?: ActivityItem[] }
const props = defineProps<Props>()

function dotClass(type?: string) {
  switch(type) {
    case 'success': return 'bg-emerald-400'
    case 'warning': return 'bg-amber-400'
    case 'danger': return 'bg-rose-400'
    case 'info': return 'bg-indigo-400'
    default: return 'bg-slate-500'
  }
}
</script>
