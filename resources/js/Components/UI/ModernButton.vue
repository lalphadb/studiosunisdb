<template>
  <button
    :type="type"
    :disabled="disabled || loading"
    :class="[buttonClasses, customClass]"
    @click="handleClick"
  >
    <!-- Loading Spinner -->
    <svg
      v-if="loading"
      class="animate-spin -ml-1 mr-2 h-4 w-4"
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      viewBox="0 0 24 24"
    >
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>

    <!-- Icon avant le texte -->
    <component 
      v-if="icon && !iconRight && !loading" 
      :is="icon" 
      :class="iconClasses"
    />

    <!-- Contenu du bouton -->
    <span :class="{ 'opacity-0': loading && hideTextOnLoading }">
      <slot />
    </span>

    <!-- Icon après le texte -->
    <component 
      v-if="icon && iconRight && !loading" 
      :is="icon" 
      :class="iconClasses"
    />
  </button>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  type: {
    type: String,
    default: 'button'
  },
  variant: {
    type: String,
    default: 'primary',
    validator: (value) => ['primary', 'secondary', 'success', 'danger', 'warning', 'ghost', 'link'].includes(value)
  },
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['xs', 'sm', 'md', 'lg', 'xl'].includes(value)
  },
  disabled: {
    type: Boolean,
    default: false
  },
  loading: {
    type: Boolean,
    default: false
  },
  hideTextOnLoading: {
    type: Boolean,
    default: false
  },
  icon: {
    type: Object,
    default: null
  },
  iconRight: {
    type: Boolean,
    default: false
  },
  fullWidth: {
    type: Boolean,
    default: false
  },
  rounded: {
    type: String,
    default: 'lg',
    validator: (value) => ['none', 'sm', 'md', 'lg', 'xl', '2xl', 'full'].includes(value)
  },
  customClass: {
    type: String,
    default: ''
  }
});

const emit = defineEmits(['click']);

const buttonClasses = computed(() => {
  const base = 'inline-flex items-center justify-center font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
  
  // Variants
  const variants = {
    primary: 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white hover:from-blue-700 hover:to-indigo-700 focus:ring-blue-500 shadow-lg hover:shadow-xl',
    secondary: 'bg-gray-800 text-gray-200 border border-gray-700 hover:bg-gray-700 hover:border-gray-600 focus:ring-gray-500',
    success: 'bg-gradient-to-r from-emerald-600 to-green-600 text-white hover:from-emerald-700 hover:to-green-700 focus:ring-emerald-500 shadow-lg hover:shadow-xl',
    danger: 'bg-gradient-to-r from-red-600 to-rose-600 text-white hover:from-red-700 hover:to-rose-700 focus:ring-red-500 shadow-lg hover:shadow-xl',
    warning: 'bg-gradient-to-r from-amber-500 to-yellow-500 text-white hover:from-amber-600 hover:to-yellow-600 focus:ring-amber-500 shadow-lg hover:shadow-xl',
    ghost: 'text-gray-300 hover:bg-gray-800 hover:text-white focus:ring-gray-500',
    link: 'text-blue-400 hover:text-blue-300 underline-offset-4 hover:underline focus:ring-blue-500'
  };
  
  // Sizes
  const sizes = {
    xs: 'text-xs px-2.5 py-1.5',
    sm: 'text-sm px-3 py-2',
    md: 'text-sm px-4 py-2.5',
    lg: 'text-base px-5 py-3',
    xl: 'text-base px-6 py-3.5'
  };
  
  // Rounded
  const roundedMap = {
    none: 'rounded-none',
    sm: 'rounded-sm',
    md: 'rounded-md',
    lg: 'rounded-lg',
    xl: 'rounded-xl',
    '2xl': 'rounded-2xl',
    full: 'rounded-full'
  };
  
  return [
    base,
    variants[props.variant],
    sizes[props.size],
    roundedMap[props.rounded],
    props.fullWidth ? 'w-full' : '',
    props.loading ? 'cursor-wait' : ''
  ].filter(Boolean).join(' ');
});

const iconClasses = computed(() => {
  const sizes = {
    xs: 'h-3 w-3',
    sm: 'h-4 w-4',
    md: 'h-4 w-4',
    lg: 'h-5 w-5',
    xl: 'h-5 w-5'
  };
  
  return [
    sizes[props.size],
    props.iconRight ? 'ml-2' : 'mr-2'
  ].join(' ');
});

const handleClick = (event) => {
  if (!props.disabled && !props.loading) {
    emit('click', event);
  }
};
</script>
