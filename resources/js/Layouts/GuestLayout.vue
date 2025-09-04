<template>
    <div class="min-h-screen flex flex-col items-center justify-center relative bg-gradient-to-br from-gray-900 via-slate-900 to-gray-950 text-slate-200 overflow-hidden">
        <!-- decorative circles -->
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl" />
            <div class="absolute -bottom-40 -right-40 w-[34rem] h-[34rem] bg-purple-600/20 rounded-full blur-3xl" />
        </div>
        <div class="relative w-full max-w-md px-6">
            <div class="flex flex-col items-center mb-8">
                <Link href="/" class="group">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg flex items-center justify-center">
                        <span class="text-white text-2xl font-bold tracking-tight">SDB</span>
                    </div>
                    <p class="mt-4 text-xs uppercase tracking-widest text-slate-400">{{ $appName }}</p>
                </Link>
            </div>
            <div class="relative rounded-2xl border border-slate-700/60 bg-slate-900/70 backdrop-blur-xl shadow-2xl p-8">
                <slot />
            </div>
            <div class="mt-8 text-center text-xs text-slate-500">
                © {{ new Date().getFullYear() }} École Studiosunis • v{{ $appVersion }} • <Link href="/loi-25" class="hover:text-slate-300 transition">Loi 25</Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>
