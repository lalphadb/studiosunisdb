<script setup>
import { Head, Link } from '@inertiajs/vue3'
const tiles = [
  { label: 'Membres', value: '→', href: '/members' },
  { label: 'Utilisateurs', value: '→', href: '/admin/users' },
]
</script>

<template>
  <Head title="Dashboard" />
  <div class="min-h-screen px-6 py-10 bg-gray-950 text-gray-100">
    <h1 class="text-2xl font-semibold mb-6">Dashboard</h1>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <Link v-for="t in tiles" :key="t.label" :href="t.href"
            class="group rounded-2xl p-5 bg-gray-900/60 ring-1 ring-white/10 hover:bg-gray-900">
        <div class="text-sm text-gray-400">{{ t.label }}</div>
        <div class="mt-3 text-3xl font-bold">{{ t.value }}</div>
      </Link>
    </div>
  </div>
</template>
