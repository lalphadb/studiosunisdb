<template>
  <div class="flex items-center justify-between">
    <div class="flex-1 flex justify-between sm:hidden">
      <Link
        v-if="links.prev"
        :href="links.prev"
        class="relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-blue-800 hover:bg-blue-700"
      >
        Précédent
      </Link>
      <Link
        v-if="links.next"
        :href="links.next"
        class="ml-3 relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-blue-800 hover:bg-blue-700"
      >
        Suivant
      </Link>
    </div>
    
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-blue-300">
          Affichage de
          <span class="font-medium text-white">{{ from }}</span>
          à
          <span class="font-medium text-white">{{ to }}</span>
          sur
          <span class="font-medium text-white">{{ total }}</span>
          résultats
        </p>
      </div>
      
      <div>
        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
          <Link
            v-for="(link, index) in links"
            :key="index"
            :href="link.url"
            :class="[
              link.active
                ? 'z-10 bg-blue-600 border-blue-600 text-white'
                : 'bg-blue-900/60 border-blue-800 text-blue-300 hover:bg-blue-800',
              'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
              index === 0 ? 'rounded-l-md' : '',
              index === links.length - 1 ? 'rounded-r-md' : ''
            ]"
            v-html="link.label"
          />
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  links: Array,
  from: Number,
  to: Number,
  total: Number
})
</script>
