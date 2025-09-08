<template>
    <svg class="ml-1 w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
        <path v-if="!isActive" 
            fill-rule="evenodd" 
            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" 
            clip-rule="evenodd" />
        <path v-else-if="direction === 'asc'" 
            fill-rule="evenodd" 
            d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" 
            clip-rule="evenodd" />
        <path v-else 
            fill-rule="evenodd" 
            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" 
            clip-rule="evenodd" />
    </svg>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    field: String,
    current: String,
    direction: String,
});

const isActive = computed(() => props.field === props.current);
</script>
