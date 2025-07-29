<template>
    <div class="p-8 bg-gray-100 min-h-screen">
        <h1 class="text-4xl font-bold text-green-600 mb-6">✅ Dashboard StudiosDB v5 Pro</h1>

        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-semibold mb-4">Test Réussi !</h2>
            <p>Laravel + Vue 3 + Inertia.js fonctionnent parfaitement.</p>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="bg-blue-100 p-4 rounded">
                <h3 class="font-bold">👥 Membres Actifs</h3>
                <p class="text-2xl">{{ metriques?.membres_actifs || 0 }}</p>
            </div>
            <div class="bg-green-100 p-4 rounded">
                <h3 class="font-bold">📚 Cours</h3>
                <p class="text-2xl">{{ metriques?.cours_actifs || 0 }}</p>
            </div>
        </div>

        <div class="mt-6 bg-yellow-100 p-4 rounded">
            <p><strong>Utilisateur:</strong> {{ user?.name || 'Non défini' }}</p>
            <p><strong>Email:</strong> {{ user?.email || 'N/A' }}</p>
        </div>
    </div>
</template>

<script setup>
defineProps({
    metriques: Object,
    user: Object,
    role: String,
    timestamp: String
})
</script>
