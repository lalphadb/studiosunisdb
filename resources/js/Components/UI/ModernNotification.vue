<template>
  <Transition
    enter-active-class="transform ease-out duration-300 transition"
    enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
    leave-active-class="transition ease-in duration-100"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div
      v-if="show"
      :class="[notificationClasses, customClass]"
    >
      <!-- Icon -->
      <div v-if="showIcon" class="flex-shrink-0">
        <component
          :is="iconComponent"
          :class="iconClasses"
          aria-hidden="true"
        />
      </div>

      <!-- Content -->
      <div class="flex-1">
        <!-- Title -->
        <p v-if="title" class="text-sm font-medium" :class="titleClasses">
          {{ title }}
        </p>
        
        <!-- Message -->
        <p v-if="message" class="mt-1 text-sm" :class="messageClasses">
          {{ message }}
        </p>
        
        <!-- Custom Content Slot -->
        <div v-if="$slots.default" class="mt-2">
          <slot />
        </div>
        
        <!-- Actions -->
        <div v-if="actions.length > 0" class="mt-3 flex space-x-3">
          <button
            v-for="(action, index) in actions"
            :key="index"
            @click="handleAction(action)"
            :class="actionButtonClasses(action)"
          >
            {{ action.label }}
          </button>
        </div>
      </div>

      <!-- Close Button -->
      <div v-if="closable" class="flex-shrink-0 ml-4">
        <button
          @click="close"
          class="inline-flex rounded-lg p-1.5 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-gray-500 transition-colors"
        >
          <span class="sr-only">Fermer</span>
          <XMarkIcon class="h-5 w-5 text-gray-400" />
        </button>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { computed, onMounted, watch } from 'vue';
import { 
  CheckCircleIcon, 
  XCircleIcon, 
  ExclamationTriangleIcon, 
  InformationCircleIcon,
  XMarkIcon 
} from '@heroicons/vue/24/outline';

const props = defineProps({
  show: {
    type: Boolean,
    default: true
  },
  type: {
    type: String,
    default: 'info',
    validator: (value) => ['success', 'error', 'warning', 'info'].includes(value)
  },
  title: {
    type: String,
    default: ''
  },
  message: {
    type: String,
    default: ''
  },
  closable: {
    type: Boolean,
    default: true
  },
  autoClose: {
    type: Number,
    default: 0 // 0 = no auto close
  },
  showIcon: {
    type: Boolean,
    default: true
  },
  actions: {
    type: Array,
    default: () => []
  },
  position: {
    type: String,
    default: 'relative',
    validator: (value) => ['relative', 'fixed-top', 'fixed-bottom'].includes(value)
  },
  customClass: {
    type: String,
    default: ''
  }
});

const emit = defineEmits(['close', 'action']);

const notificationClasses = computed(() => {
  const base = 'pointer-events-auto w-full max-w-sm overflow-hidden rounded-xl shadow-lg ring-1 backdrop-blur-xl';
  
  const types = {
    success: 'bg-emerald-900/90 ring-emerald-700/50',
    error: 'bg-red-900/90 ring-red-700/50',
    warning: 'bg-amber-900/90 ring-amber-700/50',
    info: 'bg-blue-900/90 ring-blue-700/50'
  };
  
  const positions = {
    'relative': '',
    'fixed-top': 'fixed top-4 right-4 z-50',
    'fixed-bottom': 'fixed bottom-4 right-4 z-50'
  };
  
  return [
    base,
    types[props.type],
    positions[props.position],
    'p-4 flex items-start'
  ].join(' ');
});

const iconComponent = computed(() => {
  const icons = {
    success: CheckCircleIcon,
    error: XCircleIcon,
    warning: ExclamationTriangleIcon,
    info: InformationCircleIcon
  };
  return icons[props.type];
});

const iconClasses = computed(() => {
  const colors = {
    success: 'text-emerald-400',
    error: 'text-red-400',
    warning: 'text-amber-400',
    info: 'text-blue-400'
  };
  return `h-6 w-6 ${colors[props.type]}`;
});

const titleClasses = computed(() => {
  const colors = {
    success: 'text-emerald-100',
    error: 'text-red-100',
    warning: 'text-amber-100',
    info: 'text-blue-100'
  };
  return colors[props.type];
});

const messageClasses = computed(() => {
  const colors = {
    success: 'text-emerald-200',
    error: 'text-red-200',
    warning: 'text-amber-200',
    info: 'text-blue-200'
  };
  return colors[props.type];
});

const actionButtonClasses = (action) => {
  const base = 'text-sm font-medium rounded-lg px-3 py-1.5 transition-colors focus:outline-none focus:ring-2';
  
  if (action.variant === 'primary') {
    const variants = {
      success: 'bg-emerald-600 text-white hover:bg-emerald-700 focus:ring-emerald-500',
      error: 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
      warning: 'bg-amber-600 text-white hover:bg-amber-700 focus:ring-amber-500',
      info: 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500'
    };
    return `${base} ${variants[props.type]}`;
  }
  
  const variants = {
    success: 'text-emerald-300 hover:bg-emerald-800 focus:ring-emerald-500',
    error: 'text-red-300 hover:bg-red-800 focus:ring-red-500',
    warning: 'text-amber-300 hover:bg-amber-800 focus:ring-amber-500',
    info: 'text-blue-300 hover:bg-blue-800 focus:ring-blue-500'
  };
  return `${base} ${variants[props.type]}`;
};

const close = () => {
  emit('close');
};

const handleAction = (action) => {
  emit('action', action);
  if (action.callback) {
    action.callback();
  }
  if (action.closeOnClick !== false) {
    close();
  }
};

// Auto close
let autoCloseTimeout = null;

const startAutoClose = () => {
  if (props.autoClose > 0) {
    autoCloseTimeout = setTimeout(() => {
      close();
    }, props.autoClose);
  }
};

const clearAutoClose = () => {
  if (autoCloseTimeout) {
    clearTimeout(autoCloseTimeout);
    autoCloseTimeout = null;
  }
};

onMounted(() => {
  if (props.show) {
    startAutoClose();
  }
});

watch(() => props.show, (newVal) => {
  if (newVal) {
    startAutoClose();
  } else {
    clearAutoClose();
  }
});
</script>
