<template>
  <Head title="Gestion des Paiements" />

  <AuthenticatedLayout>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-slate-900 to-gray-900 text-white">
      <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header avec actions -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
          <div class="mb-4 lg:mb-0">
            <div class="flex items-center space-x-3 mb-2">
              <div class="w-12 h-12 bg-gradient-to-br from-yellow-600 to-orange-600 rounded-xl flex items-center justify-center">
                <CurrencyDollarIcon class="h-7 w-7 text-white" />
              </div>
              <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent">
                  Gestion des Paiements
                </h1>
                <p class="text-gray-400 font-medium">Suivi financier et facturation</p>
              </div>
            </div>
          </div>

          <div class="flex items-center space-x-3">
            <button
              @click="exporterRapport"
              class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-lg transition-all duration-200 flex items-center space-x-2"
            >
              <DocumentArrowDownIcon class="h-5 w-5" />
              <span>Exporter</span>
            </button>

            <button
              @click="envoyerRappels"
              :disabled="sendingReminders"
              class="bg-orange-600 hover:bg-orange-700 disabled:opacity-50 px-6 py-3 rounded-lg transition-all duration-200 flex items-center space-x-2"
            >
              <BellIcon class="h-5 w-5" :class="{ 'animate-pulse': sendingReminders }" />
              <span>{{ sendingReminders ? 'Envoi...' : 'Rappels' }}</span>
            </button>
          </div>
        </div>

        <!-- Métriques financières -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <StatCard title="Revenus du Mois" :value="stats.revenus_mois" :change="stats.evolution_revenus" format="currency" tone="purple" description="Revenus confirmés ce mois" />
          <StatCard title="En Attente" :value="stats.paiements_en_attente" format="currency" tone="amber" description="Paiements en cours de traitement" />
          <StatCard title="En Retard" :value="stats.paiements_en_retard" :change="stats.retards_evolution" format="currency" tone="red" description="Paiements échéances dépassées" />
          <StatCard title="Taux de Recouvrement" :value="stats.taux_recouvrement" format="percentage" tone="green" description="Pourcentage de paiements reçus" />
        </div>

        <!-- Objectifs financiers -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
          <div class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
              <CurrencyDollarIcon class="h-5 w-5 text-yellow-400 mr-2" />
              Objectif Mensuel
            </h3>
            <ModernProgressBar
              :percentage="(stats.revenus_mois / stats.objectif_mois) * 100"
              color="yellow"
              size="lg"
              :current="stats.revenus_mois"
              :total="stats.objectif_mois"
              format="fraction"
              :glow-effect="true"
              :show-stats="true"
              animated
            />
          </div>

          <div class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
              <ChartBarIcon class="h-5 w-5 text-green-400 mr-2" />
              Prévisions
            </h3>
            <div class="space-y-3">
              <div class="flex justify-between">
                <span class="text-gray-400">Fin de mois:</span>
                <span class="text-white font-semibold">{{ formatCurrency(stats.prevision_fin_mois) }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-400">Mois prochain:</span>
                <span class="text-white font-semibold">{{ formatCurrency(stats.prevision_mois_suivant) }}</span>
              </div>
            </div>
          </div>

          <div class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
              <ExclamationTriangleIcon class="h-5 w-5 text-red-400 mr-2" />
              Alertes
            </h3>
            <div class="space-y-2">
              <div class="flex items-center justify-between">
                <span class="text-gray-400 text-sm">Retards +30 jours:</span>
                <span class="text-red-400 font-semibold">{{ stats.retards_30_jours }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-gray-400 text-sm">Échéances cette semaine:</span>
                <span class="text-yellow-400 font-semibold">{{ stats.echeances_semaine }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Filtres -->
        <div class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-xl p-6 mb-8">
          <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Statut</label>
              <select
                v-model="filters.statut"
                class="w-full bg-gray-700/50 border border-gray-600 rounded-lg px-3 py-2 text-white"
              >
                <option value="">Tous</option>
                <option value="en_attente">En attente</option>
                <option value="paye">Payé</option>
                <option value="en_retard">En retard</option>
                <option value="annule">Annulé</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Période</label>
              <select
                v-model="filters.periode"
                class="w-full bg-gray-700/50 border border-gray-600 rounded-lg px-3 py-2 text-white"
              >
                <option value="mois_courant">Mois courant</option>
                <option value="mois_dernier">Mois dernier</option>
                <option value="trimestre">Trimestre</option>
                <option value="annee">Année</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Méthode</label>
              <select
                v-model="filters.methode"
                class="w-full bg-gray-700/50 border border-gray-600 rounded-lg px-3 py-2 text-white"
              >
                <option value="">Toutes</option>
                <option value="especes">Espèces</option>
                <option value="cheque">Chèque</option>
                <option value="virement">Virement</option>
                <option value="carte">Carte</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Recherche</label>
              <input
                v-model="filters.recherche"
                type="text"
                placeholder="Nom du user..."
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

        <!-- Tableau des paiements -->
        <div class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-xl overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-700/50 border-b border-gray-600">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Membre</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Type</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Montant</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Échéance</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Statut</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Méthode</th>
                  <th class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-700">
                <tr
                  v-for="paiement in filteredPaiements"
                  :key="paiement.id"
                  class="hover:bg-gray-700/30 transition-all duration-200"
                >
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center mr-3">
                        <span class="text-white font-bold text-sm">
                          {{ paiement.membre?.prenom?.charAt(0) }}{{ paiement.membre?.nom?.charAt(0) }}
                        </span>
                      </div>
                      <div>
                        <div class="text-sm font-medium text-white">{{ paiement.membre?.nom_complet }}</div>
                        <div class="text-sm text-gray-400">{{ paiement.membre?.email }}</div>
                      </div>
                    </div>
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm text-gray-300">{{ paiement.type_paiement }}</span>
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-lg font-bold text-white">{{ formatCurrency(paiement.montant) }}</span>
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm text-gray-300">{{ formatDate(paiement.date_echeance) }}</span>
                    <div v-if="isEnRetard(paiement)" class="text-xs text-red-400">
                      Retard: {{ getRetardJours(paiement) }} jours
                    </div>
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap">
                    <span
                      :class="getStatutClasses(paiement.statut)"
                      class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                    >
                      <span class="w-2 h-2 rounded-full mr-2" :class="getStatutDotClasses(paiement.statut)"></span>
                      {{ getStatutLabel(paiement.statut) }}
                    </span>
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm text-gray-300">{{ paiement.methode_paiement || 'Non définie' }}</span>
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end space-x-2">
                      <button
                        v-if="paiement.statut === 'en_attente'"
                        @click="marquerPaye(paiement)"
                        class="bg-green-600/20 hover:bg-green-600/30 text-green-300 px-3 py-1 rounded text-xs transition-all duration-200"
                      >
                        Marquer payé
                      </button>

                      <button
                        @click="voirDetails(paiement)"
                        class="bg-blue-600/20 hover:bg-blue-600/30 text-blue-300 px-3 py-1 rounded text-xs transition-all duration-200"
                      >
                        Détails
                      </button>

                      <button
                        v-if="paiement.statut === 'en_retard'"
                        @click="envoyerRappel(paiement)"
                        class="bg-orange-600/20 hover:bg-orange-600/30 text-orange-300 px-3 py-1 rounded text-xs transition-all duration-200"
                      >
                        Rappel
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="bg-gray-700/30 px-6 py-3 border-t border-gray-600">
            <div class="flex items-center justify-between">
              <div class="text-sm text-gray-400">
                Affichage de {{ (currentPage - 1) * itemsPerPage + 1 }} à {{ Math.min(currentPage * itemsPerPage, filteredPaiements.length) }}
                sur {{ filteredPaiements.length }} paiements
              </div>

              <div class="flex items-center space-x-2">
                <button
                  @click="previousPage"
                  :disabled="currentPage === 1"
                  class="px-3 py-1 bg-gray-600 hover:bg-gray-700 disabled:opacity-50 rounded text-sm transition-all duration-200"
                >
                  Précédent
                </button>

                <span class="text-sm text-gray-300">
                  Page {{ currentPage }} sur {{ totalPages }}
                </span>

                <button
                  @click="nextPage"
                  :disabled="currentPage === totalPages"
                  class="px-3 py-1 bg-gray-600 hover:bg-gray-700 disabled:opacity-50 rounded text-sm transition-all duration-200"
                >
                  Suivant
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { computed, ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import StatCard from '@/Components/UI/StatCard.vue'
import ModernProgressBar from '@/Components/ModernProgressBar.vue' // conserver temporairement jusqu'à nouvelle ProgressBar

import {
  CurrencyDollarIcon,
  DocumentArrowDownIcon,
  BellIcon,
  ChartBarIcon,
  ExclamationTriangleIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
  paiements: {
    type: Array,
    required: true
  },
  stats: {
    type: Object,
    default: () => ({
      revenus_mois: 0,
      paiements_en_attente: 0,
      paiements_en_retard: 0,
      taux_recouvrement: 0,
      evolution_revenus: 0,
      retards_evolution: 0,
      objectif_mois: 10000,
      prevision_fin_mois: 0,
      prevision_mois_suivant: 0,
      retards_30_jours: 0,
      echeances_semaine: 0
    })
  }
})

// État local
const filters = ref({
  statut: '',
  periode: 'mois_courant',
  methode: '',
  recherche: ''
})

const currentPage = ref(1)
const itemsPerPage = ref(20)
const sendingReminders = ref(false)

// Paiements filtrés
const filteredPaiements = computed(() => {
  return props.paiements.filter(paiement => {
    if (filters.value.statut && paiement.statut !== filters.value.statut) return false
    if (filters.value.methode && paiement.methode_paiement !== filters.value.methode) return false
    if (filters.value.recherche) {
      const recherche = filters.value.recherche.toLowerCase()
      const nom = (paiement.membre?.nom_complet || '').toLowerCase()
      if (!nom.includes(recherche)) return false
    }
    return true
  })
})

// Pagination
const totalPages = computed(() => {
  return Math.ceil(filteredPaiements.value.length / itemsPerPage.value)
})

// Méthodes utilitaires
const formatCurrency = (amount) => {
  return new Intl.NumberFormat('fr-CA', {
    style: 'currency',
    currency: 'CAD'
  }).format(amount)
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-CA')
}

const isEnRetard = (paiement) => {
  return new Date(paiement.date_echeance) < new Date() && paiement.statut !== 'paye'
}

const getRetardJours = (paiement) => {
  const diff = new Date() - new Date(paiement.date_echeance)
  return Math.floor(diff / (1000 * 60 * 60 * 24))
}

const getStatutLabel = (statut) => {
  const labels = {
    'en_attente': 'En attente',
    'paye': 'Payé',
    'en_retard': 'En retard',
    'annule': 'Annulé'
  }
  return labels[statut] || statut
}

const getStatutClasses = (statut) => {
  const classes = {
    'en_attente': 'bg-yellow-500/20 text-yellow-300',
    'paye': 'bg-green-500/20 text-green-300',
    'en_retard': 'bg-red-500/20 text-red-300',
    'annule': 'bg-gray-500/20 text-gray-300'
  }
  return classes[statut] || 'bg-gray-500/20 text-gray-300'
}

const getStatutDotClasses = (statut) => {
  const classes = {
    'en_attente': 'bg-yellow-400',
    'paye': 'bg-green-400',
    'en_retard': 'bg-red-400',
    'annule': 'bg-gray-400'
  }
  return classes[statut] || 'bg-gray-400'
}

// Actions
const resetFilters = () => {
  filters.value = {
    statut: '',
    periode: 'mois_courant',
    methode: '',
    recherche: ''
  }
}

const previousPage = () => {
  if (currentPage.value > 1) currentPage.value--
}

const nextPage = () => {
  if (currentPage.value < totalPages.value) currentPage.value++
}

const marquerPaye = (paiement) => {
  router.patch(`/paiements/${paiement.id}/marquer-paye`)
}

const voirDetails = (paiement) => {
  router.visit(`/paiements/${paiement.id}`)
}

const envoyerRappel = (paiement) => {
  router.post(`/paiements/${paiement.id}/rappel`)
}

const envoyerRappels = () => {
  sendingReminders.value = true
  router.post('/paiements/rappels-globaux', {}, {
    onFinish: () => sendingReminders.value = false
  })
}

const exporterRapport = () => {
  window.open('/paiements/export?' + new URLSearchParams(filters.value).toString())
}
</script>
