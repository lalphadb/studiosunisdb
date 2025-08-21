<template>
  <div :class="[cardClasses, customClass]">
    <!-- Gradient Background Effect -->
    <div class="absolute inset-0 opacity-10" :class="gradientClasses"></div>
    
    <!-- Content -->
    <div class="relative">
      <!-- Header -->
      <div class="flex items-center justify-between mb-4">
        <!-- Icon Container -->
        <div :class="iconContainerClasses">
          <component
            v-if="icon"
            :is="icon"
            class="w-6 h-6"
            :class="iconColorClasses"
          />
          <span v-else-if="emoji" class="text-2xl">{{ emoji }}</span>
        </div>
        
        <!-- Trend Indicator -->
        <div v-if="trend" class="flex items-center space-x-1 text-sm font-medium" :class="trendClasses">
          <component :is="trendIcon" class="w-4 h-4" />
          <span>{{ formattedTrend }}</span>
        </div>
      </div>
      
      <!-- Stats -->
      <div class="space-y-1">
        <!-- Main Value -->
        <div class="flex items-baseline space-x-2">
          <AnimatedNumber
            v-if="animatedValue"
            :value="value"
            :format="format"
            :decimals="decimals"
            :prefix="prefix"
            :suffix="suffix"
            class="text-2xl font-bold text-white"
          />
          <p v-else class="text-2xl font-bold text-white">
            {{ formattedValue }}
          </p>
          
          <!-- Secondary Value -->
          <span v-if="secondaryValue" class="text-sm text-gray-400">
            {{ secondaryValue }}
          </span>
        </div>
        
        <!-- Label -->
        <p class="text-sm text-gray-300">{{ label }}</p>
        
        <!-- Progress Bar -->
        <div v-if="showProgress" class="mt-3">
          <div class="w-full bg-gray-700 rounded-full h-2">
            <div 
              class="h-2 rounded-full transition-all duration-1000"
              :class="progressBarClasses"
              :style="`width: ${progressPercentage}%`"
            ></div>
          </div>
          <p v-if="progressLabel" class="text-xs text-gray-400 mt-1">
            {{ progressLabel }}
          </p>
        </div>
        
        <!-- Footer Slot -->
        <div v-if="$slots.footer" class="mt-3 pt-3 border-t border-gray-700">
          <slot name="footer" />
        </div>
      </div>
      
      <!-- Action Link -->
      <Link
        v-if="actionLink"
        :href="actionLink.href"
        class="mt-4 inline-flex items-center text-sm font-medium transition-colors"
        :class="actionLinkClasses"
      >
        {{ actionLink.label }}
        <ArrowRightIcon class="ml-1 w-4 h-4" />
      </Link>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { 
  ArrowUpIcon, 
  ArrowDownIcon, 
  ArrowRightIcon,
  MinusIcon 
} from '@heroicons/vue/24/outline';
import AnimatedNumber from './AnimatedNumber.vue';

const props = defineProps({
  label: {
    type: String,
    required: true
  },
  value: {
    type: [Number, String],
    required: true
  },
  secondaryValue: {
    type: String,
    default: ''
  },
  icon: {
    type: Object,
    default: null
  },
  emoji: {
    type: String,
    default: ''
  },
  variant: {
    type: String,
    default: 'default',
    validator: (value) => ['default', 'primary', 'success', 'warning', 'danger', 'info'].includes(value)
  },
  trend: {
    type: Number,
    default: null
  },
  trendSuffix: {
    type: String,
    default: '%'
  },
  showProgress: {
    type: Boolean,
    default: false
  },
  progress: {
    type: Number,
    default: 0
  },
  progressMax: {
    type: Number,
    default: 100
  },
  progressLabel: {
    type: String,
    default: ''
  },
  actionLink: {
    type: Object,
    default: null
  },
  format: {
    type: String,
    default: 'number'
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
  },
  animatedValue: {
    type: Boolean,
    default: true
  },
  customClass: {
    type: String,
    default: ''
  }
});

const cardClasses = computed(() => {
  return [
    'relative overflow-hidden bg-gray-800/80 backdrop-blur-xl',
    'border border-gray-700/50 rounded-xl p-6',
    'hover:bg-gray-800/90 transition-all duration-300',
    'shadow-md hover:shadow-lg'
  ].join(' ');
});

const variantColors = {
  default: {
    gradient: 'bg-gradient-to-br from-gray-600 to-gray-700',
    icon: 'text-gray-400',
    iconBg: 'bg-gradient-to-br from-gray-600 to-gray-700',
    progress: 'bg-gradient-to-r from-gray-500 to-gray-600',
    action: 'text-gray-400 hover:text-gray-300'
  },
  primary: {
    gradient: 'bg-gradient-to-br from-blue-600 to-indigo-600',
    icon: 'text-white',
    iconBg: 'bg-gradient-to-br from-blue-600 to-indigo-600',
    progress: 'bg-gradient-to-r from-blue-500 to-indigo-600',
    action: 'text-blue-400 hover:text-blue-300'
  },
  success: {
    gradient: 'bg-gradient-to-br from-emerald-500 to-green-600',
    icon: 'text-white',
    iconBg: 'bg-gradient-to-br from-emerald-500 to-green-600',
    progress: 'bg-gradient-to-r from-emerald-400 to-green-600',
    action: 'text-emerald-400 hover:text-emerald-300'
  },
  warning: {
    gradient: 'bg-gradient-to-br from-yellow-400 to-amber-600',
    icon: 'text-white',
    iconBg: 'bg-gradient-to-br from-yellow-400 to-amber-600',
    progress: 'bg-gradient-to-r from-yellow-400 to-amber-600',
    action: 'text-yellow-400 hover:text-yellow-300'
  },
  danger: {
    gradient: 'bg-gradient-to-br from-red-500 to-rose-600',
    icon: 'text-white',
    iconBg: 'bg-gradient-to-br from-red-500 to-rose-600',
    progress: 'bg-gradient-to-r from-red-400 to-rose-600',
    action: 'text-red-400 hover:text-red-300'
  },
  info: {
    gradient: 'bg-gradient-to-br from-purple-500 to-indigo-600',
    icon: 'text-white',
    iconBg: 'bg-gradient-to-br from-purple-500 to-indigo-600',
    progress: 'bg-gradient-to-r from-purple-400 to-indigo-600',
    action: 'text-purple-400 hover:text-purple-300'
  }
};

const gradientClasses = computed(() => variantColors[props.variant].gradient);

const iconContainerClasses = computed(() => {
  return [
    'w-12 h-12 rounded-lg flex items-center justify-center',
    variantColors[props.variant].iconBg
  ].join(' ');
});

const iconColorClasses = computed(() => variantColors[props.variant].icon);

const progressBarClasses = computed(() => variantColors[props.variant].progress);

const actionLinkClasses = computed(() => variantColors[props.variant].action);

const trendIcon = computed(() => {
  if (props.trend > 0) return ArrowUpIcon;
  if (props.trend < 0) return ArrowDownIcon;
  return MinusIcon;
});

const trendClasses = computed(() => {
  if (props.trend > 0) return 'text-emerald-400';
  if (props.trend < 0) return 'text-red-400';
  return 'text-gray-400';
});

const formattedTrend = computed(() => {
  const sign = props.trend > 0 ? '+' : '';
  return `${sign}${Math.abs(props.trend)}${props.trendSuffix}`;
});

const formattedValue = computed(() => {
  if (typeof props.value === 'string') return props.value;
  
  if (props.format === 'currency') {
    return new Intl.NumberFormat('fr-CA', {
      style: 'currency',
      currency: 'CAD',
      minimumFractionDigits: props.decimals,
      maximumFractionDigits: props.decimals
    }).format(props.value);
  }
  
  if (props.format === 'percent') {
    return `${props.value.toFixed(props.decimals)}%`;
  }
  
  return `${props.prefix}${props.value.toLocaleString('fr-CA', {
    minimumFractionDigits: props.decimals,
    maximumFractionDigits: props.decimals
  })}${props.suffix}`;
});

const progressPercentage = computed(() => {
  return Math.min((props.progress / props.progressMax) * 100, 100);
});
</script>
