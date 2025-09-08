<template>
  <Head title="Présences - Mode Tablette" />

  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-slate-900 to-gray-900 text-white">
    <!-- Mode tablette full screen -->
    <div class="relative z-10 max-w-full mx-auto px-6 py-8">

      <!-- Header avec sélection du cours -->
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
        <div class="mb-4 lg:mb-0">
          <div class="flex items-center space-x-3 mb-2">
            <div class="w-12 h-12 bg-gradient-to-br from-green-600 to-emerald-600 rounded-xl flex items-center justify-center">
              <ClipboardDocumentCheckIcon class="h-7 w-7 text-white" />
            </div>
            <div>
              <h1 class="text-4xl font-bold bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent">
                Présences - Mode Tablette
              </h1>
              <p class="text-gray-400 font-medium">Enregistrement des présences en temps réel</p>
            </div>
          </div>
        </div>

        <div class="flex items-center space-x-3">
          <button
            @click="synchroniser"
            :disabled="syncing"
            class="bg-blue-600 hover:bg-blue-700 disabled:opacity-50 px-6 py-3 rounded-lg transition-all duration-200 flex items-center space-x-2"
          >
            <ArrowPathIcon class="h-5 w-5" :class="{ 'animate-spin': syncing }" />
            <span>{{ syncing ? 'Synchro...' : 'Synchroniser' }}</span>
          </button>

          <Link
            :href="route('presences.index')"
            class="bg-gray-600 hover:bg-gray-700 px-6 py-3 rounded-lg transition-all duration-200 flex items-center space-x-2"
          >
            <HomeIcon class="h-5 w-5" />
            <span>Retour</span>
          </Link>
        </div>
      </div>

      <!-- Sélection du cours si aucun sélectionné -->
      <div v-if="!coursSelectionne" class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-xl p-8 mb-8">
        <h2 class="text-2xl font-bold text-white mb-6 text-center">Sélectionnez un cours</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <button
            v-for="cours in coursDisponibles"
            :key="cours.id"
            @click="selectionnerCours(cours)"
            class="bg-gray-700/50 hover:bg-gray-600/50 border border-gray-600 rounded-xl p-6 transition-all duration-200 hover:scale-105 text-left"
          >
            <div class="flex items-center justify-between mb-2">
              <h3 class="text-lg font-semibold text-white">{{ cours.nom }}</h3>
              <span class="text-sm text-green-400">{{ cours.horaire_complet }}</span>
            </div>
            <p class="text-gray-400 text-sm mb-2">{{ cours.instructeur?.name }}</p>
            <p class="text-xs text-gray-500">{{ cours.membres_count }} membres inscrits</p>
          </button>
        </div>
      </div>

      <!-- Interface de présences -->
      <div v-if="coursSelectionne" class="space-y-6">

        <!-- Info du cours sélectionné -->
        <div class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-xl p-6">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-2xl font-bold text-white mb-1">{{ coursSelectionne.nom }}</h2>
              <p class="text-gray-400">{{ coursSelectionne.horaire_complet }} - {{ coursSelectionne.instructeur?.name }}</p>
            </div>

            <div class="text-right">
              <div class="text-3xl font-bold text-white">{{ new Date().toLocaleDateString('fr-CA') }}</div>
              <div class="text-lg text-gray-400">{{ currentTime }}</div>
            </div>
          </div>

          <!-- Statistiques rapides -->
          <div class="grid grid-cols-4 gap-4 mt-6">
            <div class="text-center">
              <div class="text-2xl font-bold text-green-400">{{ presentsCount }}</div>
              <div class="text-sm text-gray-400">Présents</div>
            </div>
            <div class="text-center">
              <div class="text-2xl font-bold text-yellow-400">{{ retardsCount }}</div>
              <div class="text-sm text-gray-400">Retards</div>
            </div>
            <div class="text-center">
              <div class="text-2xl font-bold text-blue-400">{{ excusesCount }}</div>
              <div class="text-sm text-gray-400">Excusés</div>
            </div>
            <div class="text-center">
              <div class="text-2xl font-bold text-red-400">{{ absentsCount }}</div>
              <div class="text-sm text-gray-400">Absents</div>
            </div>
          </div>
        </div>

        <!-- Liste des membres avec présences -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
          <div
            v-for="membre in membresInscrits"
            :key="user.id"
            class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-xl p-4 hover:border-green-500/30 transition-all duration-300"
          >
            <!-- Photo et nom du membre -->
            <div class="flex items-center space-x-3 mb-4">
              <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center">
                <span class="text-white font-bold text-lg">
                  {{ user.prenom.charAt(0) }}{{ user.nom.charAt(0) }}
                </span>
              </div>
              <div class="flex-1 min-w-0">
                <h3 class="text-lg font-semibold text-white truncate">{{ user.nom_complet }}</h3>
                <p class="text-sm text-gray-400">{{ user.ceinture_actuelle?.nom || 'Aucune ceinture' }}</p>
              </div>
            </div>

            <!-- Boutons de statut présence -->
            <div class="grid grid-cols-2 gap-2">
              <button
                @click="marquerPresence(membre, 'present')"
                :class="{
                  'bg-green-600 text-white': getStatutPresence(membre) === 'present',
                  'bg-green-600/20 text-green-300 hover:bg-green-600/30': getStatutPresence(membre) !== 'present'
                }"
                class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center justify-center space-x-1"
              >
                <CheckIcon class="h-4 w-4" />
                <span>Présent</span>
              </button>

              <button
                @click="marquerPresence(membre, 'retard')"
                :class="{
                  'bg-yellow-600 text-white': getStatutPresence(membre) === 'retard',
                  'bg-yellow-600/20 text-yellow-300 hover:bg-yellow-600/30': getStatutPresence(membre) !== 'retard'
                }"
                class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center justify-center space-x-1"
              >
                <ClockIcon class="h-4 w-4" />
                <span>Retard</span>
              </button>

              <button
                @click="marquerPresence(membre, 'excuse')"
                :class="{
                  'bg-blue-600 text-white': getStatutPresence(membre) === 'excuse',
                  'bg-blue-600/20 text-blue-300 hover:bg-blue-600/30': getStatutPresence(membre) !== 'excuse'
                }"
                class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center justify-center space-x-1"
              >
                <DocumentTextIcon class="h-4 w-4" />
                <span>Excusé</span>
              </button>

              <button
                @click="marquerPresence(membre, 'absent')"
                :class="{
                  'bg-red-600 text-white': getStatutPresence(membre) === 'absent',
                  'bg-red-600/20 text-red-300 hover:bg-red-600/30': getStatutPresence(membre) !== 'absent'
                }"
                class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center justify-center space-x-1"
              >
                <XMarkIcon class="h-4 w-4" />
                <span>Absent</span>
              </button>
            </div>

            <!-- Heure d'arrivée si présent -->
            <div v-if="getStatutPresence(membre) === 'present' || getStatutPresence(membre) === 'retard'" class="mt-3 text-center">
              <p class="text-xs text-gray-400">Arrivée: {{ getHeureArrivee(membre) }}</p>
            </div>
          </div>
        </div>

        <!-- Actions finales -->
        <div class="bg-gray-800/30 backdrop-blur-xl border border-gray-700/50 rounded-xl p-6">
          <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0 sm:space-x-4">
            <div class="text-center sm:text-left">
              <p class="text-lg font-semibold text-white">Cours terminé ?</p>
              <p class="text-sm text-gray-400">Sauvegardez les présences avant de quitter</p>
            </div>

            <div class="flex space-x-3">
              <button
                @click="sauvegarderPresences"
                :disabled="saving"
                class="bg-green-600 hover:bg-green-700 disabled:opacity-50 px-6 py-3 rounded-lg transition-all duration-200 flex items-center space-x-2"
              >
                <CheckIcon class="h-5 w-5" />
                <span>{{ saving ? 'Sauvegarde...' : 'Sauvegarder' }}</span>
              </button>

              <button
                @click="reinitialiserCours"
                class="bg-gray-600 hover:bg-gray-700 px-6 py-3 rounded-lg transition-all duration-200 flex items-center space-x-2"
              >
                <ArrowPathIcon class="h-5 w-5" />
                <span>Nouveau Cours</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'

import {
  ClipboardDocumentCheckIcon,
  ArrowPathIcon,
  HomeIcon,
  CheckIcon,
  ClockIcon,
  DocumentTextIcon,
  XMarkIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
  cours: {
    type: Array,
    default: () => []
  },
  presences: {
    type: Array,
    default: () => []
  }
})

// État local
const coursSelectionne = ref(null)
const presencesLocales = ref(new Map())
const syncing = ref(false)
const saving = ref(false)
const currentTime = ref('')

// Cours disponibles pour aujourd'hui
const coursDisponibles = computed(() => {
  const aujourdhui = new Date().toLocaleDateString('fr-CA', { weekday: 'long' })
  return props.cours.filter(cours =>
    cours.jour_semaine.toLowerCase() === aujourdhui.toLowerCase() && cours.actif
  )
})

// Membres inscrits au cours sélectionné
const membresInscrits = computed(() => {
  return coursSelectionne.value?.membres || []
})

// Compteurs de présences
const presentsCount = computed(() => {
  return Array.from(presencesLocales.value.values()).filter(p => p.statut === 'present').length
})

const retardsCount = computed(() => {
  return Array.from(presencesLocales.value.values()).filter(p => p.statut === 'retard').length
})

const excusesCount = computed(() => {
  return Array.from(presencesLocales.value.values()).filter(p => p.statut === 'excuse').length
})

const absentsCount = computed(() => {
  return Array.from(presencesLocales.value.values()).filter(p => p.statut === 'absent').length
})

// Méthodes
const updateTime = () => {
  currentTime.value = new Date().toLocaleTimeString('fr-CA', {
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit'
  })
}

const selectionnerCours = (cours) => {
  coursSelectionne.value = cours
  // Initialiser les présences avec les données existantes
  presencesLocales.value.clear()
  cours.membres?.forEach(membre => {
    const presenceExistante = props.presences.find(p =>
      p.user_id === user.id && p.cours_id === cours.id
    )
    if (presenceExistante) {
      presencesLocales.value.set(user.id, { ...presenceExistante })
    }
  })
}

const marquerPresence = (membre, statut) => {
  const presence = {
    user_id: user.id,
    cours_id: coursSelectionne.value.id,
    statut: statut,
    heure_arrivee: statut === 'present' || statut === 'retard' ? new Date().toLocaleTimeString() : null,
    date_cours: new Date().toISOString().split('T')[0]
  }

  presencesLocales.value.set(user.id, presence)
}

const getStatutPresence = (membre) => {
  const presence = presencesLocales.value.get(user.id)
  return presence?.statut || 'absent'
}

const getHeureArrivee = (membre) => {
  const presence = presencesLocales.value.get(user.id)
  return presence?.heure_arrivee || '--:--'
}

const sauvegarderPresences = async () => {
  if (!coursSelectionne.value) return

  saving.value = true
  try {
    const presencesArray = Array.from(presencesLocales.value.values())

    await router.post('/presences/sauvegarder', {
      cours_id: coursSelectionne.value.id,
      presences: presencesArray
    })

    // Afficher un message de succès
    console.log('Présences sauvegardées avec succès')
  } catch (error) {
    console.error('Erreur lors de la sauvegarde:', error)
  } finally {
    saving.value = false
  }
}

const synchroniser = async () => {
  syncing.value = true
  try {
    await router.reload()
  } finally {
    syncing.value = false
  }
}

const reinitialiserCours = () => {
  coursSelectionne.value = null
  presencesLocales.value.clear()
}

// Lifecycle
onMounted(() => {
  updateTime()
  const interval = setInterval(updateTime, 1000)
  onUnmounted(() => clearInterval(interval))
})
</script>

<style scoped>
/* Optimisations pour tablette */
@media (min-width: 768px) {
  .grid-cols-1 {
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  }
}

/* Animation pour les boutons de présence */
button:active {
  transform: scale(0.95);
}

/* Couleurs de statut */
.statut-present {
  background: linear-gradient(135deg, #10B981, #065F46);
}

.statut-retard {
  background: linear-gradient(135deg, #F59E0B, #92400E);
}

.statut-excuse {
  background: linear-gradient(135deg, #3B82F6, #1E40AF);
}

.statut-absent {
  background: linear-gradient(135deg, #EF4444, #991B1B);
}
</style>
