<script setup lang="ts">
import InputLabel from '@/Components/InputLabel.vue';
import FormError from './FormError.vue';

defineProps<{
    label?: string;
    id?: string;
    required?: boolean;
    error?: string | string[] | null;
    hint?: string;
    class?: string;
}>();
</script>

<template>
    <div :class="['space-y-2', $attrs.class]">
        <!-- Label -->
        <InputLabel 
            v-if="label" 
            :for="id" 
            :value="label" 
            :class="{ 'after:content-[\'*\'] after:ml-1 after:text-red-500': required }"
        />
        
        <!-- Champ principal (slot par défaut) -->
        <div class="relative">
            <slot />
        </div>
        
        <!-- Texte d'aide -->
        <p v-if="hint && !error" class="text-sm text-gray-600">
            {{ hint }}
        </p>
        
        <!-- Erreurs -->
        <FormError :message="error" />
    </div>
</template>
