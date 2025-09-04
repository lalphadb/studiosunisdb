<template>
  <Head title="Dashboard — Administration" />
  <div class="p-6 space-y-10">
    <PageHeader title="Tableau de bord" description="Synthèse opérationnelle en temps réel.">
      <template #actions>
        <div class="flex gap-3">
          <button @click="refresh" class="px-4 py-2 rounded-lg bg-slate-800/70 border border-slate-700 hover:bg-slate-700 text-xs font-medium text-slate-200 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v6h6M20 20v-6h-6"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 20A8 8 0 0020 9M15 4A8 8 0 004 15"/></svg>
            Rafraîchir
          </button>
          <button @click="go('/cours/create')" class="px-4 py-2 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-400 hover:to-purple-500 text-white text-xs font-medium transition flex items-center gap-2 shadow">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Nouveau cours
          </button>
        </div>
      </template>
    </PageHeader>

    <!-- KPIs principaux -->
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-5">
      <StatCard title="Membres actifs" :value="stats.membresActifs" :change="12" format="number" tone="blue" description="Actifs 30 derniers jours" />
      <StatCard title="Cours actifs" :value="stats.coursDuJour" :change="5" format="number" tone="purple" description="Sessions planifiées" />
      <StatCard title="Taux de présence" :value="parseInt(stats.tauxPresence)" format="percentage" :change="3" tone="green" description="Semaine en cours" />
      <StatCard title="Paiements en retard" :value="stats.paiementsRetard" :change="-2" format="number" tone="red" description="À relancer" />
      <StatCard title="Revenus mois" :value="stats.revenusMois" tone="amber" description="Chiffre d'affaires" />
    </section>

    <!-- Analytique & Activité -->
    <section class="grid grid-cols-1 xl:grid-cols-3 gap-6">
      <ChartCard class="xl:col-span-2" title="Progression des inscriptions" subtitle="7 derniers jours">
        <template #toolbar>
          <select class="bg-slate-800/70 border border-slate-700 text-xs rounded-lg px-2 py-1 text-slate-300 focus:outline-none">
            <option>7 jours</option>
            <option>30 jours</option>
            <option>12 mois</option>
          </select>
        </template>
      </ChartCard>
      <ActivityCard title="Activité récente" :items="recentActivity" />
    </section>

    <!-- Actions & Rappels -->
    <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 p-6">
        <h3 class="text-sm font-semibold text-slate-200 mb-4">Actions rapides</h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
          <button @click="go('/membres/create')" class="action-btn">Nouveau membre</button>
          <button @click="go('/presences/tablette')" class="action-btn">Présences</button>
          <button @click="go('/cours')" class="action-btn">Cours</button>
          <button @click="go('/utilisateurs')" class="action-btn">Utilisateurs</button>
        </div>
      </div>
      <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 p-6">
        <h3 class="text-sm font-semibold text-slate-200 mb-4">Rappels</h3>
        <ul class="space-y-3 text-xs text-slate-300">
          <li v-for="(r,i) in reminders" :key="i" class="flex items-start gap-2">
            <span class="w-1.5 h-1.5 mt-1.5 rounded-full bg-indigo-400"></span>
            <span>{{ r }}</span>
          </li>
        </ul>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import StatCard from '@/Components/UI/StatCard.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import ChartCard from '@/Components/UI/ChartCard.vue'
import ActivityCard from '@/Components/UI/ActivityCard.vue'

type Stats = { membresActifs: number; coursDuJour: number; tauxPresence: number; paiementsRetard: number }
const props = defineProps<{ role?: string; widgets?: string[]; user?: { id: number; name: string; email: string } }>()

const stats: Stats = { membresActifs: 0, coursDuJour: 0, tauxPresence: 0, paiementsRetard: 0 }

const recentActivity = [
  { label: 'Nouveau membre inscrit', time: 'il y a 5 min', type: 'success' },
  { label: 'Cours de karaté avancé terminé', time: 'il y a 1 h', type: 'info' },
  { label: 'Paiement reçu – 150$', time: 'il y a 2 h', type: 'success' },
  { label: '2 paiements en retard', time: 'il y a 3 h', type: 'warning' }
]

const reminders = [
  'Relancer les paiements en retard',
  'Vérifier planning examens',
  'Créer communication info-stage'
]

function refresh() {
  // TODO: call inertia reload with only stats
  router.reload({ only: ['stats'] })
}

function go(url: string) { router.visit(url) }
</script>

<style scoped>
.action-btn{ @apply px-3 py-3 rounded-xl bg-slate-800/60 border border-slate-700 text-slate-300 text-xs font-medium hover:bg-slate-700/60 transition flex items-center justify-center text-center; }
</style>
