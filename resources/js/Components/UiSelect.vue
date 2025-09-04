<template>
  <div class="relative">
    <label
      v-if="label"
      :for="selectId"
      class="block text-sm font-medium text-slate-300 mb-2"
    >
      {{ label }}
      <span v-if="required" class="text-red-400 ml-1">*</span>
    </label>
    
    <div class="relative">
      <!-- Select field -->
      <select
        :id="selectId"
        ref="select"
        v-model="modelValue"
        :disabled="disabled"
        :required="required"
        :multiple="multiple"
        :class="[
          'ui-select',
          'block w-full rounded-xl',
          'bg-slate-800/50 backdrop-blur-sm',
          'border border-slate-700 focus:border-blue-500',
          'text-white',
          'transition-all duration-200',
          'focus:outline-none focus:ring-2 focus:ring-blue-500/20',
          'appearance-none cursor-pointer',
          disabled && 'opacity-50 cursor-not-allowed',
          error && 'border-red-500 focus:border-red-500 focus:ring-red-500/20',
          sizeClasses
        ]"
        @change="$emit('update:modelValue', $event.target.value)"
      >
        <option v-if="placeholder" value="" disabled :selected="!modelValue">
          {{ placeholder }}
        </option>
        <option
          v-for="option in options"
          :key="option.value"
          :value="option.value"
          :disabled="option.disabled"
        >
          {{ option.label }}
        </option>
      </select>
      
      <!-- Custom arrow icon -->
      <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </div>
    </div>
    
    <!-- Error message -->
    <p v-if="error" class="mt-2 text-sm text-red-400">{{ error }}</p>
    
    <!-- Helper text -->
    <p v-if="helper && !error" class="mt-2 text-sm text-slate-500">{{ helper }}</p>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'

const props = defineProps({
  modelValue: {
    type: [String, Number, Array],
    default: ''
  },
  options: {
    type: Array,
    required: true,
    validator: (options) => {
      return options.every(opt => 
        typeof opt === 'object' && 
        'value' in opt && 
        'label' in opt
      )
    }
  },
  label: String,
  placeholder: {
    type: String,
    default: 'Sélectionner...'
  },
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  },
  error: String,
  helper: String,
  disabled: {
    type: Boolean,
    default: false
  },
  required: {
    type: Boolean,
    default: false
  },
  multiple: {
    type: Boolean,
    default: false
  }
})

defineEmits(['update:modelValue'])

const select = ref(null)

const selectId = computed(() => {
  return `select-${Math.random().toString(36).substring(2, 9)}`
})

const sizeClasses = computed(() => {
  const sizes = {
    sm: 'px-3 py-2 pr-10 text-sm',
    md: 'px-4 py-2.5 pr-10 text-sm',
    lg: 'px-4 py-3 pr-10 text-base'
  }
  return sizes[props.size]
})

// Expose focus method
defineExpose({
  focus: () => select.value?.focus()
})
</script>

<style scoped>
/* Style for option elements in dark mode */
option {
  @apply bg-slate-800 text-white;
}

option:disabled {
  @apply text-slate-500;
}

/* Custom scrollbar for select dropdown */
select::-webkit-scrollbar {
  width: 8px;
}

select::-webkit-scrollbar-track {
  @apply bg-slate-800;
}

select::-webkit-scrollbar-thumb {
  @apply bg-slate-600 rounded;
}

select::-webkit-scrollbar-thumb:hover {
  @apply bg-slate-500;
}
</style>
