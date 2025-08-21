<template>
  <Link :href="href" 
        class="group bg-blue-900/60 hover:bg-blue-800/60 backdrop-blur-xl border border-blue-800/50 rounded-xl p-6 transition-all duration-300 transform hover:scale-105 hover:shadow-xl hover:shadow-blue-500/10">
    <div class="flex items-center justify-between">
      <div>
        <div class="flex items-center gap-3 mb-3">
          <div :class="`w-12 h-12 bg-gradient-to-br ${getColorClasses(color).gradient} rounded-lg flex items-center justify-center`">
            <span class="text-2xl text-white">{{ icon }}</span>
          </div>
          <h3 class="text-lg font-bold text-white">{{ title }}</h3>
        </div>
        <p class="text-blue-200 text-sm mb-3">{{ description }}</p>
        <div class="flex items-center gap-4 text-blue-300 text-sm">
          <span v-for="(value, key) in stats" :key="key">
            {{ value }} {{ key }}
          </span>
        </div>
      </div>
      <div class="text-blue-400 group-hover:text-white transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M13 7l5 5m0 0l-5 5m5-5H6"/>
        </svg>
      </div>
    </div>
  </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  title: {
    type: String,
    required: true
  },
  description: {
    type: String,
    default: ''
  },
  href: {
    type: String,
    required: true
  },
  icon: {
    type: String,
    default: '📋'
  },
  color: {
    type: String,
    default: 'blue',
    validator: (value) => ['blue', 'green', 'purple', 'amber', 'red'].includes(value)
  },
  stats: {
    type: Object,
    default: () => ({})
  }
});

const getColorClasses = (color) => {
  const colors = {
    blue: {
      gradient: 'from-blue-600 to-indigo-700',
      hover: 'hover:bg-blue-800/60'
    },
    green: {
      gradient: 'from-green-600 to-emerald-700',
      hover: 'hover:bg-green-800/60'
    },
    purple: {
      gradient: 'from-purple-600 to-indigo-700',
      hover: 'hover:bg-purple-800/60'
    },
    amber: {
      gradient: 'from-amber-600 to-orange-700',
      hover: 'hover:bg-amber-800/60'
    },
    red: {
      gradient: 'from-red-600 to-pink-700',
      hover: 'hover:bg-red-800/60'
    }
  };
  return colors[color] || colors.blue;
};
</script>
