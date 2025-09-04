<script setup lang="ts">
defineProps<{
    message?: string | string[] | null;
    class?: string;
}>();
</script>

<template>
    <div v-if="message" :class="['text-sm text-red-600', $attrs.class]">
        <template v-if="Array.isArray(message)">
            <p v-for="(msg, index) in message" :key="index" class="mb-1 last:mb-0">
                {{ msg }}
            </p>
        </template>
        <p v-else class="mb-0">
            {{ message }}
        </p>
    </div>
</template>
