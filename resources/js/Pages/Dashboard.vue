<script setup lang="ts">
defineProps<{
  stats: {
    total_membres: number
    total_cours: number
    total_presences: number
    total_paiements: number
    progression_ceintures: { ceinture: string, count: number }[]
  }
}>()
</script>

<template>
  <div class="p-6 space-y-10 bg-gray-900 text-white min-h-screen">
    
    <!-- Titre -->
    <div class="text-3xl font-semibold">Tableau de bord - St-Émile</div>

    <!-- Widgets statistiques -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <DashboardWidget title="Membres actifs" :value="stats.total_membres" icon="👥" />
      <DashboardWidget title="Cours actifs" :value="stats.total_cours" icon="📆" />
      <DashboardWidget title="Présences (ce mois)" :value="stats.total_presences" icon="✅" />
      <DashboardWidget title="Paiements reçus" :value="`${stats.total_paiements.toLocaleString('fr-CA', { style: 'currency', currency: 'CAD' })}`" icon="💰" />
    </div>

    <!-- Actions rapides -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <QuickAction title="Ajouter un membre" route="/membres/create" icon="➕" />
      <QuickAction title="Feuilles de présence" route="/presences" icon="📝" />
      <QuickAction title="Gérer les cours" route="/cours" icon="📚" />
      <QuickAction title="Paiements à valider" route="/paiements" icon="💳" />
      <QuickAction title="Réinscription session" route="/sessions" icon="🔁" />
      <QuickAction title="Exporter rapports" route="/exports" icon="📤" />
    </div>

    <!-- Progression par ceinture -->
    <div>
      <h2 class="text-xl font-semibold mb-4">Progression des ceintures</h2>
      <div class="space-y-3">
        <ProgressBar
          v-for="item in stats.progression_ceintures"
          :key="item.ceinture"
          :label="item.ceinture"
          :value="item.count"
        />
      </div>
    </div>

  </div>
</template>
