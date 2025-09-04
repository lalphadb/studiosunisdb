<template>
  <div class="relative">
    <label
      v-if="label"
      :for="inputId"
      class="block text-sm font-medium text-slate-300 mb-2"
    >
      {{ label }}
      <span v-if="required" class="text-red-400 ml-1">*</span>
    </label>
    
    <div class="relative">
      <!-- Icon prefix -->
      <div v-if="icon || $slots.icon" class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <slot name="icon">
          <component :is="icon" class="h-5 w-5 text-slate-400" />
        </slot>
      </div>
      
      <!-- Input field -->
      <input
        :id="inputId"
        ref="input"
        v-model="modelValue"
        :type="type"
        :placeholder="placeholder"
        :disabled="disabled"
        :readonly="readonly"
        :required="required"
        :class="[
          'ui-input',
          'block w-full rounded-xl',
          'bg-slate-800/50 backdrop-blur-sm',
          'border border-slate-700 focus:border-blue-500',
          'text-white placeholder-slate-400',
          'transition-all duration-200',
          'focus:outline-none focus:ring-2 focus:ring-blue-500/20',
          disabled && 'opacity-50 cursor-not-allowed',
          error && 'border-red-500 focus:border-red-500 focus:ring-red-500/20',
          (icon || $slots.icon) && 'pl-10',
          suffix && 'pr-10',
          sizeClasses
        ]"
        @input="$emit('update:modelValue', $event.target.value)"
        @blur="$emit('blur', $event)"
        @focus="$emit('focus', $event)"
      >
      
      <!-- Suffix -->
      <div v-if="suffix" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
        <span class="text-slate-400 text-sm">{{ suffix }}</span>
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
    type: [String, Number],
    default: ''
  },
  label: String,
  placeholder: String,
  type: {
    type: String,
    default: 'text'
  },
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  },
  icon: Object,
  suffix: String,
  error: String,
  helper: String,
  disabled: {
    type: Boolean,
    default: false
  },
  readonly: {
    type: Boolean,
    default: false
  },
  required: {
    type: Boolean,
    default: false
  }
})

defineEmits(['update:modelValue', 'blur', 'focus'])

const input = ref(null)

const inputId = computed(() => {
  return `input-${Math.random().toString(36).substring(2, 9)}`
})

const sizeClasses = computed(() => {
  const sizes = {
    sm: 'px-3 py-2 text-sm',
    md: 'px-4 py-2.5 text-sm',
    lg: 'px-4 py-3 text-base'
  }
  return sizes[props.size]
})

// Expose focus method
defineExpose({
  focus: () => input.value?.focus()
})
</script>
