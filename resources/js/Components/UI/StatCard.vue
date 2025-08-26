<template>
  <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-800/60 to-slate-900/60 backdrop-blur-xl p-5 border border-slate-700/50 hover:border-slate-600 transition-colors">
    <!-- subtle glow background -->
    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none" :style="glowStyle"></div>

    <div class="relative flex items-start justify-between">
      <div>
        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">{{ title }}</p>
        <div class="mt-2 flex items-end space-x-2">
          <p class="text-3xl font-bold text-white leading-none">{{ formattedValue }}</p>
          <span v-if="change !== undefined" :class="changeClasses" class="text-xs font-semibold inline-flex items-center px-1.5 py-0.5 rounded">
            <svg v-if="numericChange !== 0" :class="arrowClasses" class="w-3.5 h-3.5 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.75.75v9.638l3.22-3.22a.75.75 0 111.06 1.061l-4.5 4.5a.75.75 0 01-1.06 0l-4.5-4.5a.75.75 0 111.06-1.06l3.22 3.219V3.75A.75.75 0 0110 3z" clip-rule="evenodd"/></svg>
            <span>{{ changeDisplay }}</span>
          </span>
        </div>
        <p v-if="description" class="mt-2 text-xs text-slate-400 line-clamp-2">{{ description }}</p>
      </div>
      <div class="flex-shrink-0 w-11 h-11 rounded-xl flex items-center justify-center text-slate-200" :class="iconWrapperClasses">
        <slot name="icon">
          <component :is="resolvedIcon" v-if="resolvedIcon" class="w-6 h-6" />
          <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h7v7H3V3zm0 11h7v7H3v-7zm11-11h7v7h-7V3zm7 11h-7v7h7v-7z"/>
          </svg>
        </slot>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  title: string
  value: number | string
  change?: number | string
  format?: 'number' | 'percentage' | 'currency'
  currency?: string
  description?: string
  icon?: any
  tone?: 'blue' | 'purple' | 'green' | 'amber' | 'red' | 'slate'
}

const props = withDefaults(defineProps<Props>(), {
  format: 'number',
  currency: 'CAD',
  tone: 'slate'
})

const numericChange = computed(() => {
  if (props.change === undefined) return 0
  if (typeof props.change === 'string') {
    const cleaned = props.change.replace(/[%+\s]/g, '')
    const n = parseFloat(cleaned)
    return isNaN(n) ? 0 : n
  }
  return props.change
})

const changeDisplay = computed(() => {
  if (props.change === undefined) return ''
  if (props.format === 'percentage' && typeof props.change === 'number') return `${props.change > 0 ? '+' : ''}${props.change}%`
  if (typeof props.change === 'number') return `${props.change > 0 ? '+' : ''}${props.change}`
  return props.change
})

const formattedValue = computed(() => {
  if (props.format === 'currency') {
    const n = typeof props.value === 'number' ? props.value : parseFloat(props.value)
    if (!isNaN(n)) return new Intl.NumberFormat('fr-CA', { style: 'currency', currency: props.currency }).format(n)
    return props.value
  }
  if (props.format === 'percentage') {
    const n = typeof props.value === 'number' ? props.value : parseFloat(props.value)
    return isNaN(n) ? props.value : `${n}%`
  }
  return props.value
})

const positive = computed(() => numericChange.value > 0)
const negative = computed(() => numericChange.value < 0)

const changeClasses = computed(() => {
  if (numericChange.value === 0) return 'bg-slate-600/40 text-slate-300'
  return positive.value
    ? 'bg-emerald-500/15 text-emerald-300 ring-1 ring-inset ring-emerald-500/30'
    : 'bg-rose-500/15 text-rose-300 ring-1 ring-inset ring-rose-500/30'
})

const arrowClasses = computed(() => ({
  'rotate-180': negative.value,
  'text-emerald-400': positive.value,
  'text-rose-400': negative.value
}))

const toneMap: Record<string, { ring: string; glow: string; iconBg: string }> = {
  blue: { ring: 'ring-blue-500/30', glow: 'from-blue-500/10 to-indigo-500/10', iconBg: 'bg-blue-500/20 text-blue-300' },
  purple: { ring: 'ring-purple-500/30', glow: 'from-purple-500/10 to-fuchsia-500/10', iconBg: 'bg-purple-500/20 text-purple-300' },
  green: { ring: 'ring-emerald-500/30', glow: 'from-emerald-500/10 to-teal-500/10', iconBg: 'bg-emerald-500/20 text-emerald-300' },
  amber: { ring: 'ring-amber-500/30', glow: 'from-amber-500/10 to-orange-500/10', iconBg: 'bg-amber-500/20 text-amber-300' },
  red: { ring: 'ring-rose-500/30', glow: 'from-rose-500/10 to-orange-500/10', iconBg: 'bg-rose-500/20 text-rose-300' },
  slate: { ring: 'ring-slate-500/20', glow: 'from-slate-500/10 to-slate-700/10', iconBg: 'bg-slate-500/20 text-slate-300' }
}

const resolved = computed(() => toneMap[props.tone] || toneMap.slate)

const glowStyle = computed(() => ({
  background: `radial-gradient(circle at 30% 20%, rgba(255,255,255,0.06), transparent 60%), linear-gradient(to bottom right, ${resolved.value.glow})`
}))

const iconWrapperClasses = computed(() => resolved.value.iconBg)

const resolvedIcon = computed(() => props.icon)
</script>

<style scoped>
</style>
