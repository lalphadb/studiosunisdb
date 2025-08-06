<template>
  <Head title="Gestion des Membres" />

  <AuthenticatedLayout>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-slate-900 to-gray-900 text-white">
      <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header avec actions -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
          <div class="mb-4 lg:mb-0">
            <div class="flex items-center space-x-3 mb-2">
              <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center">
                <UsersIcon class="h-7 w-7 text-white" />
              </div>
              <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent">
                  Gestion des Membres
                </h1>
                <p class="text-gray-400 font-medium">Administration des membres et inscriptions</p>
              </div>
            </div>
          </div>

          <div class="flex items-center space-x-3">
            <button
              @click="exporterDonnees"
              class="bg-green-600 hover:bg-green-700 px-6 py-3 rounded-lg transition-all duration-200 flex items-center space-x-2"
            >
              <DocumentArrowDownIcon class="h-5 w-5" />
              <span>Exporter</span>
            </button>

            <Link
              :href="route('membres.create')"
              class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-lg transition-all duration-200 flex items-center space-x-2"
            >
              <PlusIcon class="h-5 w-5" />
              <span>Nouveau Membre</span>
            </Link>
          </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <ModernStatsCard
            title="Total Membres"
            :value="stats.total_membres"
            icon-type="heroicon"
            icon-name="users"
            format="number"
            description="Membres inscrits"
            :change="stats.evolution_membres"
          />

          <ModernStatsCard
            title="Membres Actifs"
            :value="stats.membres_actifs"
            icon-type="heroicon"
            icon-name="check"
            format="number"
            description="Statut actif"
          />

          <ModernStatsCard
            title="Nouveaux ce mois"
            :value="stats.nouveaux_mois"
            icon-type="heroicon"
            icon-name="plus"
            format="number"
            description="Inscriptions récentes"
          />

          <ModernStatsCard
            title="Taux d'activité"
            :value="stats.taux_activite"
            icon-type="heroicon"
            icon-name="chart"
            format="percentage"
            description="Membres présents"
          />
        </div>

        <!-- Filtres -->
        <div class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-xl p-6 mb-8">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Statut</label>
              <select
                v-model="filters.statut"
                class="w-full bg-gray-700/50 border border-gray-600 rounded-lg px-3 py-2 text-white"
              >
                <option value="">Tous</option>
                <option value="actif">Actif</option>
                <option value="inactif">Inactif</option>
                <option value="suspendu">Suspendu</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Ceinture</label>
              <select
                v-model="filters.ceinture"
                class="w-full bg-gray-700/50 border border-gray-600 rounded-lg px-3 py-2 text-white"
              >
                <option value="">Toutes</option>
                <option v-for="ceinture in ceintures" :key="ceinture.id" :value="ceinture.id">
                  {{ ceinture.nom }}
                </option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Recherche</label>
              <input
                v-model="filters.recherche"
                type="text"
                placeholder="Nom du membre..."
                class="w-full bg-gray-700/50 border border-gray-600 rounded-lg px-3 py-2 text-white placeholder-gray-400"
              >
            </div>

            <div class="flex items-end">
              <button
                @click="resetFilters"
                class="w-full bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded-lg transition-all duration-200"
              >
                Réinitialiser
              </button>
            </div>
          </div>
        </div>

        <!-- Liste des membres -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
          <div
            v-for="membre in filteredMembres"
            :key="membre.id"
            class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-xl p-6 hover:border-blue-500/30 transition-all duration-300 group"
          >
            <!-- Header du membre -->
            <div class="flex items-start justify-between mb-4">
              <div class="flex items-center space-x-3">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center">
                  <span class="text-white font-bold text-lg">
                    {{ getInitials(membre.prenom, membre.nom) }}
                  </span>
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-white">{{ membre.prenom }} {{ membre.nom }}</h3>
                  <p class="text-sm text-gray-400">{{ membre.email }}</p>
                </div>
              </div>

              <div class="flex items-center space-x-2">
                <span
                  :class="{
                    'bg-green-500/20 text-green-300': membre.statut === 'actif',
                    'bg-gray-500/20 text-gray-300': membre.statut === 'inactif',
                    'bg-red-500/20 text-red-300': membre.statut === 'suspendu'
                  }"
                  class="px-2 py-1 rounded text-xs font-medium"
                >
                  {{ membre.statut }}
                </span>
              </div>
            </div>

            <!-- Détails du membre -->
            <div class="space-y-3 mb-4">
              <div class="flex items-center text-sm text-gray-300">
                <CalendarDaysIcon class="h-4 w-4 mr-2 text-blue-400" />
                <span>Inscrit le {{ formatDate(membre.date_inscription) }}</span>
              </div>

              <div class="flex items-center text-sm text-gray-300">
                <StarIcon class="h-4 w-4 mr-2 text-yellow-400" />
                <span>{{ membre.ceinture_actuelle?.nom || 'Aucune ceinture' }}</span>
              </div>

              <div class="flex items-center text-sm text-gray-300">
                <ClockIcon class="h-4 w-4 mr-2 text-green-400" />
                <span>Dernière présence: {{ formatDate(membre.date_derniere_presence) || 'Jamais' }}</span>
              </div>

              <div class="flex items-center text-sm text-gray-300">
                <PhoneIcon class="h-4 w-4 mr-2 text-purple-400" />
                <span>{{ membre.telephone || 'Non renseigné' }}</span>
              </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-wrap gap-2">
              <Link
                :href="route('membres.show', membre.id)"
                class="flex-1 bg-blue-600/20 hover:bg-blue-600/30 text-blue-300 px-3 py-2 rounded-lg text-sm transition-all duration-200 text-center"
              >
                Voir Profil
              </Link>

              <Link
                :href="route('membres.edit', membre.id)"
                class="flex-1 bg-green-600/20 hover:bg-green-600/30 text-green-300 px-3 py-2 rounded-lg text-sm transition-all duration-200 text-center"
              >
                Modifier
              </Link>

              <button
                @click="toggleStatut(membre)"
                :class="{
                  'bg-red-600/20 hover:bg-red-600/30 text-red-300': membre.statut === 'actif',
                  'bg-green-600/20 hover:bg-green-600/30 text-green-300': membre.statut !== 'actif'
                }"
                class="flex-1 px-3 py-2 rounded-lg text-sm transition-all duration-200"
              >
                {{ membre.statut === 'actif' ? 'Suspendre' : 'Activer' }}
              </button>
            </div>
          </div>
        </div>

        <!-- Message si aucun membre -->
        <div v-if="filteredMembres.length === 0" class="text-center py-12">
          <UsersIcon class="h-16 w-16 text-gray-500 mx-auto mb-4" />
          <h3 class="text-lg font-medium text-gray-400 mb-2">Aucun membre trouvé</h3>
          <p class="text-gray-500 mb-4">Commencez par ajouter votre premier membre.</p>
          <Link
            :href="route('membres.create')"
            class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-lg transition-all duration-200 inline-flex items-center space-x-2"
          >
            <PlusIcon class="h-5 w-5" />
            <span>Créer un membre</span>
          </Link>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { computed, ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import ModernStatsCard from '@/Components/ModernStatsCard.vue'

import {
  UsersIcon,
  PlusIcon,
  DocumentArrowDownIcon,
  CalendarDaysIcon,
  StarIcon,
  ClockIcon,
  PhoneIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
  membres: {
    type: Object,
    required: true
  },
  ceintures: {
    type: Array,
    default: () => []
  },
  stats: {
    type: Object,
    default: () => ({
      total_membres: 0,
      membres_actifs: 0,
      nouveaux_mois: 0,
      taux_activite: 0,
      evolution_membres: 0
    })
  }
})

// Filtres réactifs
const filters = ref({
  statut: '',
  ceinture: '',
  recherche: ''
})

// Membres filtrés
const filteredMembres = computed(() => {
  return props.membres.data?.filter(membre => {
    if (filters.value.statut && membre.statut !== filters.value.statut) return false
    if (filters.value.ceinture && membre.ceinture_actuelle_id !== parseInt(filters.value.ceinture)) return false
    if (filters.value.recherche) {
      const recherche = filters.value.recherche.toLowerCase()
      const nom = `${membre.prenom} ${membre.nom}`.toLowerCase()
      if (!nom.includes(recherche)) return false
    }
    return true
  }) || []
})

// Méthodes utilitaires
const getInitials = (prenom, nom) => {
  return (prenom?.charAt(0) || '') + (nom?.charAt(0) || '')
}

const formatDate = (date) => {
  if (!date) return null
  return new Date(date).toLocaleDateString('fr-CA')
}

const resetFilters = () => {
  filters.value = {
    statut: '',
    ceinture: '',
    recherche: ''
  }
}

const toggleStatut = (membre) => {
  // Implementation à ajouter - appel API pour changer le statut
  console.log('Toggle statut membre:', membre.id)
}

const exporterDonnees = () => {
  // Implementation à ajouter - export des données
  console.log('Export des données membres')
}
</script>
