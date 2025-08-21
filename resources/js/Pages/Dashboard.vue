<template>
  <AuthenticatedLayout>
    <Head title="Dashboard" />
    <div class="p-6 space-y-6">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
        Mon tableau de bord
      </h1>

      <div v-if="role === 'instructeur'" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <ModernStatsCard label="Cours aujourd’hui" :value="4" />
        <ModernStatsCard label="Taux de présence" :value="'92%'" />
        <ModernActionCard title="Prise de présences" description="Ouvrir la tablette" @click="go('/presences/tablette')" />
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <ModernStatsCard label="Mon horaire" :value="2" />
        <ModernStatsCard label="Mes paiements" :value="'À jour'" />
        <ModernActionCard title="Voir mes cours" description="Parcourir mes cours" @click="go('/cours')" />
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import ModernStatsCard from '@/Components/ModernStatsCard.vue'
import ModernActionCard from '@/Components/ModernActionCard.vue'

const props = defineProps<{ role?: string; widgets?: string[]; user?: { id: number; name: string; email: string } }>()
const role = props.role ?? 'membre'
function go(url: string) { router.visit(url) }
</script>
