/studiosdb/studiosunisdb/studiosdb_v5_pro/resources/js/Components/Dashboard/NavLink.vue
<template>
  <component 
    :is="href ? Link : 'button'"
    :href="href"
    @click="href ? null : $emit('click')"
    :class="[
      'flex items-center space-x-2 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200',
      active 
        ? 'bg-blue-600 text-white shadow-lg' 
        : 'text-gray-300 hover:text-white hover:bg-gray-700/50'
    ]"
  >
    <span class="text-lg">{{ icon }}</span>
    <span>{{ $slots.default()[0].children }}</span>
    <div v-if="active" class="w-1 h-1 bg-white rounded-full"></div>
  </component>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  href: String,
  active: {
    type: Boolean,
    default: false
  },
  icon: String
})

defineEmits(['click'])
</script>