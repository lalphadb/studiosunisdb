<script setup lang="ts">
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatsCard from '@/Components/Dashboard/StatsCard.vue';
import ProgressBar from '@/Components/Dashboard/ProgressBar.vue';

// Props reçues du DashboardController
const props = defineProps<{
  stats: {
    total_membres: number;
    nouveaux_membres_mois: number;
    membres_actifs: number;
    cours_semaine: number;
    prochains_cours: Array<{
      id: number;
      nom: string;
      date_debut: string;
      heure_debut: string;
      participants: number;
    }>;
    activites_recentes: Array<{
      id: number;
      type: string;
      description: string;
      date: string;
    }>;
    graphique_presences: {
      labels: string[];
      data: number[];
    };
    evolution_membres: number;
    paiements_en_retard: number;
    examens_a_venir: number;
  }
}>();

// Calculs pour les statistiques
const tauxActivite = computed(() => {
  if (props.stats.total_membres === 0) return 0;
  return Math.round((props.stats.membres_actifs / props.stats.total_membres) * 100);
});

const evolutionColor = computed(() => {
  return props.stats.evolution_membres >= 0 ? 'text-green-600' : 'text-red-600';
});

</script>

<template>
    <Head title="Dashboard - StudiosDB v5 Pro" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-2xl text-white leading-tight">
                Tableau de Bord
            </h2>
            <p class="text-sm text-gray-400 mt-1">
                Bienvenue, {{ $page.props.auth.user.name }}. Voici le résumé de l'activité de votre école.
            </p>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <StatsCard title="Membres Actifs" :value="stats.total_membres" icon="👥" :change="stats.nouveaux_membres_mois" />
                    <StatsCard title="Présences / Semaine" :value="stats.presences_semaine" icon="✅" />
                    <StatsCard title="Revenus du Mois" :value="formatMoney(stats.revenus_mois)" icon="💰" />
                    <StatsCard title="Prochains Cours" :value="stats.prochains_cours.length" icon="📅" />
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-700/50">
                        <h3 class="text-lg font-semibold text-white">Évolution des Présences</h3>
                        <p class="text-sm text-gray-400 mb-4">7 derniers jours</p>
                        <div class="h-80 relative">
                            <canvas ref="chartRef"></canvas>
                        </div>
                    </div>

                    <div class="bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-700/50">
                        <h3 class="text-lg font-semibold text-white mb-4">Répartition par Ceintures</h3>
                        <div v-if="stats.progression_ceintures.length > 0" class="space-y-4">
                            <div v-for="ceinture in stats.progression_ceintures" :key="ceinture.ceinture">
                                <div class="flex justify-between items-center mb-1 text-sm">
                                    <span class="font-medium text-gray-300">{{ ceinture.ceinture }}</span>
                                    <span class="text-gray-400 font-semibold">{{ ceinture.count }}</span>
                                </div>
                                <ProgressBar :percentage="(ceinture.count / maxCeinturesCount) * 100" :color="ceinture.couleur" />
                            </div>
                        </div>
                        <div v-else class="text-center py-8">
                            <p class="text-gray-500">Aucune donnée de progression disponible.</p>
                        </div>
                    </div>
                </div>

                 <div class="bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-700/50">
                    <h3 class="text-lg font-semibold text-white mb-4">Événements à Venir</h3>
                     <div v-if="stats.prochains_cours.length > 0" class="divide-y divide-gray-700">
                        <div v-for="cours in stats.prochains_cours" :key="cours.nom" class="py-3 flex items-center justify-between">
                            <div>
                               <p class="font-semibold text-white">{{ cours.nom }}</p>
                               <p class="text-sm text-gray-400">{{ formatDate(cours.date_debut) }} à {{ cours.heure_debut }}</p>
                            </div>
                            <button class="px-3 py-1 text-xs bg-blue-600 hover:bg-blue-500 text-white rounded-full transition">Voir</button>
                        </div>
                    </div>
                    <div v-else class="text-center py-8">
                        <p class="text-gray-500">Aucun cours à venir planifié.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
