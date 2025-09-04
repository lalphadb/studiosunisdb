<template>
  <div :class="[
    'ui-card',
    'relative overflow-hidden',
    'bg-slate-900/50 backdrop-blur-xl',
    'border border-slate-700/50',
    'transition-all duration-300',
    variantClasses,
    hoverEffect && 'hover:border-slate-600/50 hover:shadow-2xl',
    className
  ]">
    <!-- Gradient overlay optionnel -->
    <div v-if="gradient" class="absolute inset-0 opacity-5">
      <div :class="`absolute inset-0 bg-gradient-to-br ${gradientColors}`"></div>
    </div>
    
    <!-- Header -->
    <div v-if="title || $slots.header" class="relative px-6 py-4 border-b border-slate-800/50">
      <slot name="header">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-semibold text-white">{{ title }}</h3>
          <div v-if="$slots.actions" class="flex items-center gap-2">
            <slot name="actions" />
          </div>
        </div>
        <p v-if="description" class="mt-1 text-sm text-slate-400">{{ description }}</p>
      </slot>
    </div>
    
    <!-- Body -->
    <div :class="[
      'relative',
      !noPadding && 'p-6',
      bodyClass
    ]">
      <slot />
    </div>
    
    <!-- Footer -->
    <div v-if="$slots.footer" class="relative px-6 py-4 border-t border-slate-800/50 bg-slate-900/30">
      <slot name="footer" />
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  title: String,
  description: String,
  variant: {
    type: String,
    default: 'default',
    validator: (value) => ['default', 'elevated', 'bordered', 'glass'].includes(value)
  },
  gradient: {
    type: Boolean,
    default: false
  },
  gradientColors: {
    type: String,
    default: 'from-blue-600 to-purple-600'
  },
  hoverEffect: {
    type: Boolean,
    default: true
  },
  noPadding: {
    type: Boolean,
    default: false
  },
  className: {
    type: String,
    default: ''
  },
  bodyClass: {
    type: String,
    default: ''
  }
})

const variantClasses = computed(() => {
  const variants = {
    default: 'rounded-2xl',
    elevated: 'rounded-2xl shadow-xl',
    bordered: 'rounded-2xl border-2',
    glass: 'rounded-2xl bg-white/5 backdrop-blur-2xl'
  }
  return variants[props.variant]
})
</script>
