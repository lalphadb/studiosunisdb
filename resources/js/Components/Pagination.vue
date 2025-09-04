<template>
  <div class="flex items-center justify-between">
    <div class="text-sm text-slate-400">
      <template v-if="from && to && total">
        Affichage de <span class="font-medium text-white">{{ from }}</span> à 
        <span class="font-medium text-white">{{ to }}</span> sur 
        <span class="font-medium text-white">{{ total }}</span> résultats
      </template>
      <template v-else-if="links && links.length > 2">
        {{ links.length - 2 }} page(s) disponible(s)
      </template>
      <template v-else>
        Résultats
      </template>
    </div>
    
    <div v-if="links && links.length > 0" class="flex items-center gap-2">
      <Component
        v-for="(link, index) in links"
        :key="index"
        :is="link.url ? 'Link' : 'span'"
        :href="link.url"
        v-html="link.label"
        class="px-3 py-2 text-sm rounded-lg transition-all"
        :class="{
          'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg': link.active,
          'bg-slate-800/50 hover:bg-slate-700/50 text-slate-300 border border-slate-700': !link.active && link.url,
          'bg-slate-900/30 text-slate-600 cursor-not-allowed': !link.url
        }"
      />
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  links: {
    type: Array,
    default: () => []
  },
  from: {
    type: [Number, null],
    default: null
  },
  to: {
    type: [Number, null], 
    default: null
  },
  total: {
    type: [Number, null],
    default: null
  }
})
</script>
