#!/usr/bin/env bash
set -euo pipefail

say(){ echo "— $*"; }

[ -f artisan ] || { echo "❌ Lance depuis la racine (artisan introuvable)"; exit 1; }

# 1) Trouver le bon Dashboard
candidates=(
  "resources/js/Pages/Dashboard.vue"
  "resources/js/Pages/Dashboard/Index.vue"
  "resources/js/Pages/Dashboard/Default.vue"
)
DASH=""
for f in "${candidates[@]}"; do
  if [ -f "$f" ]; then DASH="$f"; break; fi
done

if [ -z "$DASH" ]; then
  echo "❌ Fichier Dashboard introuvable."
  echo "   Vérifie avec: ls resources/js/Pages -R | grep -i 'Dashboard'"
  exit 1
fi

say "Dashboard détecté: $DASH"

# 2) Sauvegarde
ts="$(date +%Y%m%d_%H%M%S)"
cp -v "$DASH" "$DASH.backup.$ts"

# 3) Remplacement (FULL WIDTH, grid 12, responsive, a11y)
cat > "$DASH" <<'VUE'
<template>
  <Head title="Tableau de bord" />

  <div class="w-full max-w-none px-4 sm:px-6 lg:px-8 py-6">
    <!-- En-tête -->
    <div class="mb-6">
      <h1 class="text-3xl font-bold tracking-tight text-white dark:text-white">
        Tableau de bord
      </h1>
      <p class="mt-1 text-sm text-blue-100/80 dark:text-blue-100/80">
        Bienvenue <span class="font-medium">{{ userName }}</span> — École de Karaté
      </p>
    </div>

    <!-- Stats (grid 12 col, full width) -->
    <section aria-labelledby="stats" class="grid grid-cols-12 gap-6 mb-8">
      <div class="col-span-12 md:col-span-6 lg:col-span-3">
        <div class="rounded-2xl bg-gradient-to-br from-blue-600 to-blue-700 text-white p-5 shadow-lg ring-1 ring-white/10">
          <div class="flex items-start justify-between">
            <div class="text-sm/5 opacity-90">Membres actifs</div>
            <span class="inline-flex items-center rounded-full bg-white/15 px-2 py-0.5 text-xs/5">+{{ stats.members.delta }}%</span>
          </div>
          <div class="mt-2 text-3xl font-extrabold">{{ stats.members.count }}</div>
          <div class="mt-1 text-xs/5 opacity-80">sur {{ stats.members.total }} inscrits</div>
        </div>
      </div>

      <div class="col-span-12 md:col-span-6 lg:col-span-3">
        <div class="rounded-2xl bg-gradient-to-br from-emerald-600 to-emerald-700 text-white p-5 shadow-lg ring-1 ring-white/10">
          <div class="flex items-start justify-between">
            <div class="text-sm/5 opacity-90">Cours / semaine</div>
            <span class="inline-flex items-center rounded-full bg-white/15 px-2 py-0.5 text-xs/5">Aujourd’hui {{ stats.courses.today }}</span>
          </div>
          <div class="mt-2 text-3xl font-extrabold">{{ stats.courses.perWeek }}</div>
          <div class="mt-1 text-xs/5 opacity-80">{{ stats.courses.active }} actifs</div>
        </div>
      </div>

      <div class="col-span-12 md:col-span-6 lg:col-span-3">
        <div class="rounded-2xl bg-gradient-to-br from-fuchsia-600 to-fuchsia-700 text-white p-5 shadow-lg ring-1 ring-white/10">
          <div class="flex items-start justify-between">
            <div class="text-sm/5 opacity-90">Taux de présence</div>
            <span class="inline-flex items-center rounded-full bg-white/15 px-2 py-0.5 text-xs/5">7 derniers jours</span>
          </div>
          <div class="mt-2 text-3xl font-extrabold">{{ stats.attendance.rate }}%</div>
          <div class="mt-1 text-xs/5 opacity-80">objectif {{ stats.attendance.target }}%</div>
        </div>
      </div>

      <div class="col-span-12 md:col-span-6 lg:col-span-3">
        <div class="rounded-2xl bg-gradient-to-br from-orange-500 to-orange-600 text-white p-5 shadow-lg ring-1 ring-white/10">
          <div class="flex items-start justify-between">
            <div class="text-sm/5 opacity-90">Examens</div>
            <span class="inline-flex items-center rounded-full bg-white/15 px-2 py-0.5 text-xs/5">prochain {{ stats.exams.nextIn }}</span>
          </div>
          <div class="mt-2 text-3xl font-extrabold">{{ stats.exams.thisMonth }}</div>
          <div class="mt-1 text-xs/5 opacity-80">ce mois</div>
        </div>
      </div>
    </section>

    <!-- Actions rapides -->
    <section aria-labelledby="actions" class="grid grid-cols-12 gap-6 mb-10">
      <button
        type="button"
        @click="go('membres.create')"
        class="col-span-12 md:col-span-6 lg:col-span-4 rounded-2xl bg-slate-900/60 text-white hover:bg-slate-900/80 p-5 shadow ring-1 ring-white/10 transition"
      >
        <div class="text-lg font-semibold">Nouveau Membre</div>
        <p class="text-sm opacity-80 mt-1">Inscriptions, profils, ceintures</p>
      </button>

      <button
        type="button"
        @click="go('presences.tablette')"
        class="col-span-12 md:col-span-6 lg:col-span-4 rounded-2xl bg-slate-900/60 text-white hover:bg-slate-900/80 p-5 shadow ring-1 ring-white/10 transition"
      >
        <div class="text-lg font-semibold">Prendre Présences</div>
        <p class="text-sm opacity-80 mt-1">Tablette, pointage rapide</p>
      </button>

      <button
        type="button"
        @click="go('cours.index')"
        class="col-span-12 md:col-span-6 lg:col-span-4 rounded-2xl bg-slate-900/60 text-white hover:bg-slate-900/80 p-5 shadow ring-1 ring-white/10 transition"
      >
        <div class="text-lg font-semibold">Gestion des Cours</div>
        <p class="text-sm opacity-80 mt-1">Horaires & instructeurs</p>
      </button>
    </section>

    <!-- Deux colonnes adaptatives (full width) -->
    <section class="grid grid-cols-12 gap-6">
      <!-- Gauche -->
      <div class="col-span-12 xl:col-span-8 space-y-6">
        <div class="rounded-2xl bg-slate-900/60 ring-1 ring-white/10 p-5 text-white shadow">
          <h2 class="text-lg font-semibold mb-4">Cours d’aujourd’hui</h2>
          <ul class="divide-y divide-white/10">
            <li v-for="c in todayCourses" :key="c.id" class="py-3 flex items-center justify-between">
              <div>
                <div class="font-medium">{{ c.title }}</div>
                <div class="text-sm opacity-80">{{ c.time }} • {{ c.instructor }}</div>
              </div>
              <div class="text-sm opacity-80">{{ c.room }}</div>
            </li>
          </ul>
        </div>

        <div class="rounded-2xl bg-slate-900/60 ring-1 ring-white/10 p-5 text-white shadow">
          <h2 class="text-lg font-semibold mb-4">Alertes</h2>
          <ul class="list-disc pl-5 space-y-2 text-sm">
            <li v-for="a in alerts" :key="a.id" class="opacity-90">{{ a.text }}</li>
          </ul>
        </div>
      </div>

      <!-- Droite -->
      <aside class="col-span-12 xl:col-span-4 space-y-6">
        <div class="rounded-2xl bg-slate-900/60 ring-1 ring-white/10 p-5 text-white shadow">
          <h2 class="text-lg font-semibold mb-4">Membres récents</h2>
          <ul class="space-y-3 text-sm">
            <li v-for="m in recentMembers" :key="m.id" class="flex items-center justify-between">
              <span class="opacity-90">{{ m.name }}</span>
              <span class="opacity-60">{{ m.belt }}</span>
            </li>
          </ul>
        </div>

        <div class="rounded-2xl bg-slate-900/60 ring-1 ring-white/10 p-5 text-white shadow">
          <h2 class="text-lg font-semibold mb-4">Paiements à traiter</h2>
          <ul class="space-y-3 text-sm">
            <li v-for="p in duePayments" :key="p.id" class="flex items-center justify-between">
              <span class="opacity-90">{{ p.member }}</span>
              <span class="opacity-60">{{ p.amount }}</span>
            </li>
          </ul>
        </div>
      </aside>
    </section>
  </div>
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3'
import { computed } from 'vue'

// Props minimales; la page reste fonctionnelle même si aucune data n'est passée
const props = defineProps({
  userName: { type: String, default: 'Admin' },
  stats: {
    type: Object,
    default: () => ({
      members: { count: 0, total: 0, delta: 0 },
      courses: { perWeek: 0, today: 0, active: 0 },
      attendance: { rate: 0, target: 90 },
      exams: { thisMonth: 0, nextIn: '—' },
    }),
  },
  todayCourses: { type: Array, default: () => [] },
  alerts: { type: Array, default: () => [] },
  recentMembers: { type: Array, default: () => [] },
  duePayments: { type: Array, default: () => [] },
})

const go = (name) => router.visit(route(name))
</script>
VUE

echo "✅ Nouveau Dashboard appliqué (full-width)."

# 4) Conseil optionnel: corriger un centrage forcé dans le layout si présent
LAY="resources/js/Layouts/AuthenticatedLayout.vue"
if [ -f "$LAY" ]; then
  if grep -q "max-w-7xl" "$LAY" || grep -q "container mx-auto" "$LAY"; then
    cp -v "$LAY" "$LAY.backup.$ts"
    sed -i \
      -e 's/max-w-7xl/max-w-none/g' \
      -e 's/container mx-auto/w-full/g' \
      "$LAY"
    echo "ℹ️ Layout ajusté (suppression du centrage contraint)."
  else
    echo "ℹ️ Layout OK (pas de max-w-7xl/container mx-auto détecté)."
  fi
else
  echo "ℹ️ Layout $LAY introuvable (pas nécessairement un problème)."
fi

echo "Terminé. Relance le build frontal si besoin (npm run dev)."
