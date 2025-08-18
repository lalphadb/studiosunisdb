<template>
  <Transition
    enter-active-class="transition ease-out duration-200"
    enter-from-class="opacity-0"
    enter-to-class="opacity-100"
    leave-active-class="transition ease-in duration-150"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-black bg-opacity-75" @click="$emit('close')"></div>

        <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-blue-900 rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="px-6 pt-5 pb-4 bg-gradient-to-br from-blue-900 to-indigo-900 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div :class="[
                'mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10',
                type === 'danger' ? 'bg-red-900/50' : 'bg-blue-800/50'
              ]">
                <ExclamationTriangleIcon 
                  :class="[
                    'h-6 w-6',
                    type === 'danger' ? 'text-red-400' : 'text-blue-400'
                  ]"
                />
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg font-medium leading-6 text-white">
                  {{ title }}
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-blue-200">
                    {{ message }}
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="px-4 py-3 bg-blue-950/60 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              @click="$emit('confirm')"
              :class="[
                'w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm',
                type === 'danger' 
                  ? 'bg-red-600 hover:bg-red-700 focus:ring-red-500' 
                  : 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500'
              ]"
            >
              {{ confirmText }}
            </button>
            <button
              @click="$emit('close')"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-blue-700 shadow-sm px-4 py-2 bg-blue-800 text-base font-medium text-blue-200 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
            >
              Annuler
            </button>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { ExclamationTriangleIcon } from '@heroicons/vue/24/outline'

defineProps({
  show: Boolean,
  title: String,
  message: String,
  confirmText: {
    type: String,
    default: 'Confirmer'
  },
  type: {
    type: String,
    default: 'warning'
  }
})

defineEmits(['close', 'confirm'])
</script>
