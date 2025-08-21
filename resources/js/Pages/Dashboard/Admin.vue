<template>
  <Head title="Dashboard — Administration" />
  <div class="p-6 space-y-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
      Tableau de bord — Administration
    </h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
      <ModernStatsCard label="Membres actifs" :value="stats.membresActifs" />
      <ModernStatsCard label="Cours aujourd’hui" :value="stats.coursDuJour" />
      <ModernStatsCard label="Taux de présence" :value="`${stats.tauxPresence}%`" />
      <ModernStatsCard label="Paiements en retard" :value="stats.paiementsRetard" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
      <ModernActionCard
        title="Créer un membre"
        description="Ajouter un nouveau membre dans l’école."
        @click="go('/membres/create')"
      />
      <ModernActionCard
        title="Prise de présences"
        description="Ouvrir la tablette de pointage pour les sessions d’aujourd’hui."
        @click="go('/presences/tablette')"
      />
      <ModernActionCard
        title="Gestion des cours"
        description="Voir et modifier les cours et horaires."
        @click="go('/cours')"
      />
      <ModernActionCard
        title="Utilisateurs"
        description="Gérer les utilisateurs de l’école."
        @click="go('/utilisateurs')"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import ModernStatsCard from '@/Components/ModernStatsCard.vue'
import ModernActionCard from '@/Components/ModernActionCard.vue'

type Stats = { membresActifs: number; coursDuJour: number; tauxPresence: number; paiementsRetard: number }
const props = defineProps<{ role?: string; widgets?: string[]; user?: { id: number; name: string; email: string } }>()

const stats: Stats = { membresActifs: 0, coursDuJour: 0, tauxPresence: 0, paiementsRetard: 0 }

function go(url: string) { router.visit(url) }
</script>
