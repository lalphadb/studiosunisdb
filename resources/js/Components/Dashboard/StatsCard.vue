<template>
  <div class="bg-blue-900/60 backdrop-blur-xl border border-blue-800/50 rounded-xl p-6 hover:bg-blue-800/60 transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/10">
    <div class="flex items-center justify-between mb-4">
      <div :class="`w-12 h-12 bg-gradient-to-br ${getColorClasses(color).gradient} rounded-lg flex items-center justify-center`">
        <span class="text-2xl text-white">{{ icon }}</span>
      </div>
      <div v-if="evolution" class="flex items-center gap-1 text-sm font-medium"
           :class="evolution > 0 ? 'text-green-400' : 'text-red-400'">
        <span>{{ evolution > 0 ? '↗' : '↘' }}</span>
        <span>{{ Math.abs(evolution) }}%</span>
      </div>
    </div>
    <div class="space-y-1 min-w-0">
      <p class="text-xl xl:text-2xl font-bold text-white truncate">{{ value }}</p>
      <p class="text-sm text-blue-200 truncate">{{ title }}</p>
      <p v-if="subtext" class="text-xs text-blue-400 truncate">{{ subtext }}</p>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true
  },
  value: {
    type: [String, Number],
    required: true
  },
  icon: {
    type: String,
    default: '📊'
  },
  evolution: {
    type: Number,
    default: null
  },
  subtext: {
    type: String,
    default: ''
  },
  subtitle: {
    type: String,
    default: ''
  },
  color: {
    type: String,
    default: 'blue'
  },
  iconClass: {
    type: String,
    default: ''
  }
});

const getColorClasses = (color) => {
  const colors = {
    blue: { gradient: 'from-blue-600 to-indigo-700' },
    green: { gradient: 'from-green-600 to-emerald-700' },
    purple: { gradient: 'from-purple-600 to-indigo-700' },
    amber: { gradient: 'from-amber-600 to-orange-700' },
    red: { gradient: 'from-red-600 to-pink-700' }
  };
  return colors[color] || colors.blue;
};
</script>
