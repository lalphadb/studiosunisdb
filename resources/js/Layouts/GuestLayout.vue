<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <!-- Logo -->
        <div class="mb-6">
            <Link href="/">
                <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center">
                    <span class="text-white text-2xl font-bold">SDB</span>
                </div>
            </Link>
        </div>

        <!-- Card -->
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <slot />
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-sm text-gray-600">
            {{ $appName }} • École de Karaté Studiosunis
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>
