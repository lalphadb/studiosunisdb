<template>
  <transition
    enter-active-class="transition-all duration-500 ease-out"
    leave-active-class="transition-all duration-300 ease-in"
    enter-from-class="opacity-0 scale-90"
    enter-to-class="opacity-100 scale-100"
    leave-from-class="opacity-100 scale-100"
    leave-to-class="opacity-0 scale-90"
  >
    <span v-if="show" class="tabular-nums font-bold">
      {{ displayValue }}
    </span>
  </transition>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';

const props = defineProps({
  value: {
    type: Number,
    required: true
  },
  duration: {
    type: Number,
    default: 1000
  },
  format: {
    type: String,
    default: 'number' // 'number', 'currency', 'percent'
  },
  decimals: {
    type: Number,
    default: 0
  },
  prefix: {
    type: String,
    default: ''
  },
  suffix: {
    type: String,
    default: ''
  }
});

const currentValue = ref(0);
const show = ref(false);

const displayValue = computed(() => {
  let formatted = currentValue.value;
  
  if (props.format === 'currency') {
    formatted = new Intl.NumberFormat('fr-CA', {
      style: 'currency',
      currency: 'CAD',
      minimumFractionDigits: props.decimals,
      maximumFractionDigits: props.decimals
    }).format(currentValue.value);
  } else if (props.format === 'percent') {
    formatted = `${currentValue.value.toFixed(props.decimals)}%`;
  } else {
    formatted = currentValue.value.toLocaleString('fr-CA', {
      minimumFractionDigits: props.decimals,
      maximumFractionDigits: props.decimals
    });
  }
  
  return `${props.prefix}${formatted}${props.suffix}`;
});

const animateValue = (start, end, duration) => {
  const startTime = performance.now();
  const diff = end - start;
  
  const animate = (currentTime) => {
    const elapsed = currentTime - startTime;
    const progress = Math.min(elapsed / duration, 1);
    
    // Easing function for smooth animation
    const easeOutQuart = 1 - Math.pow(1 - progress, 4);
    
    currentValue.value = start + (diff * easeOutQuart);
    
    if (progress < 1) {
      requestAnimationFrame(animate);
    } else {
      currentValue.value = end;
    }
  };
  
  requestAnimationFrame(animate);
};

watch(() => props.value, (newVal, oldVal) => {
  animateValue(oldVal || 0, newVal, props.duration);
});

onMounted(() => {
  show.value = true;
  animateValue(0, props.value, props.duration);
});
</script>
