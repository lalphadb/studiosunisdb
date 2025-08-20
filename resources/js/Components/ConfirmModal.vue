<template>
  <TransitionRoot appear :show="show" as="template">
    <Dialog as="div" @close="$emit('close')" class="relative z-50">
      <TransitionChild
        as="template"
        enter="duration-300 ease-out"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="duration-200 ease-in"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-black/80 backdrop-blur-sm" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-gradient-to-b from-slate-800 to-slate-900 p-6 shadow-2xl transition-all border border-slate-700/50">
              <DialogTitle as="h3" class="text-lg font-bold text-white mb-4">
                {{ title }}
              </DialogTitle>
              
              <p class="text-sm text-slate-300 mb-6">
                {{ message }}
              </p>

              <div class="flex justify-end gap-3">
                <button
                  type="button"
                  class="px-4 py-2 bg-slate-800/50 hover:bg-slate-700/50 text-white rounded-xl transition-all border border-slate-700"
                  @click="$emit('close')"
                >
                  Annuler
                </button>
                <button
                  type="button"
                  class="px-4 py-2 rounded-xl text-white transition-all font-medium shadow-lg"
                  :class="danger 
                    ? 'bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800' 
                    : 'bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700'"
                  @click="$emit('confirm')"
                >
                  {{ confirmText || 'Confirmer' }}
                </button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'

defineProps({
  show: Boolean,
  title: String,
  message: String,
  confirmText: String,
  danger: Boolean
})

defineEmits(['close', 'confirm'])
</script>
