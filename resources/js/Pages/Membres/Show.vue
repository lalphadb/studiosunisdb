<template>
  <AuthenticatedLayout>
    <Head :title="`${membre.nom_complet} - Profil`" />
    
    <div class="min-h-screen max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <PageHeader :title="membre.nom_complet" :description="`Profil détaillé du membre - ${membre.age} ans`">
        <template #actions>
          <Link :href="`/membres/${membre.id}/edit`" 
                class="px-4 py-2 rounded-lg bg-gradient-to-r from-amber-500 to-orange-600 text-white text-sm font-medium hover:from-amber-400 hover:to-orange-500">
            Modifier
          </Link>
          <Link href="/membres" 
                class="px-4 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-white text-sm font-medium">
            ← Retour
          </Link>
        </template>
      </PageHeader>

      <!-- Stats Cards Rapides -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <StatCard title="Âge" :value="membre.age" tone="blue" description="ans" />
        <StatCard title="Statut" :value="membre.statut" tone="green" description="Membre actif" />
        <StatCard title="Ceinture" :value="membre.ceinture_actuelle?.nom || 'Aucune'" tone="purple" description="Niveau actuel" />
        <StatCard title="Progressions" :value="historiqueProgressions?.length || 0" tone="amber" description="Total" />
      </div>

      <!-- Progression Ceintures Section -->
      <div v-if="validationProgression" class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6 mb-6">
        <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
          🥋 Progression Ceintures
        </h3>
        
        <!-- Ceinture Actuelle + Prochaine -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
          <!-- Ceinture Actuelle -->
          <div class="bg-slate-900/50 rounded-xl p-4 border border-slate-700/50">
            <h4 class="text-sm font-medium text-slate-400 mb-3">Ceinture Actuelle</h4>
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-full border-2 border-slate-600" 
                   :style="`background-color: ${membre.ceinture_actuelle?.couleur_hex}`"></div>
              <div>
                <div class="text-white font-medium">{{ membre.ceinture_actuelle?.nom }}</div>
                <div class="text-xs text-slate-400">Ordre {{ membre.ceinture_actuelle?.order }}</div>
              </div>
            </div>
          </div>

          <!-- Prochaine Ceinture -->
          <div v-if="validationProgression.prochaine_ceinture" class="bg-slate-900/50 rounded-xl p-4 border border-slate-700/50">
            <h4 class="text-sm font-medium text-slate-400 mb-3">Prochaine Ceinture</h4>
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-full border-2 border-slate-600" 
                   :style="`background-color: ${validationProgression.prochaine_ceinture.couleur_hex}`"></div>
              <div>
                <div class="text-white font-medium">{{ validationProgression.prochaine_ceinture.nom }}</div>
                <div class="text-xs" :class="validationProgression.peut_progresser ? 'text-green-400' : 'text-amber-400'">
                  {{ validationProgression.peut_progresser ? 'Progression possible' : 'Validation requise' }}
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Critères de Progression -->
        <div v-if="validationProgression.criteres_requis" class="bg-slate-900/50 rounded-xl p-4 border border-slate-700/50 mb-4">
          <h4 class="text-sm font-medium text-slate-400 mb-3">Critères de Progression</h4>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div v-if="validationProgression.criteres_requis.temps_minimum" class="flex justify-between items-center">
              <span class="text-sm text-slate-300">Temps minimum</span>
              <span class="text-sm" :class="validationProgression.criteres_atteints.temps_ecoule >= validationProgression.criteres_requis.temps_minimum ? 'text-green-400' : 'text-amber-400'">
                {{ validationProgression.criteres_atteints.temps_ecoule || '0' }} / {{ validationProgression.criteres_requis.temps_minimum }}
              </span>
            </div>
            <div v-if="validationProgression.criteres_requis.presences_minimum" class="flex justify-between items-center">
              <span class="text-sm text-slate-300">Présences requises</span>
              <span class="text-sm" :class="validationProgression.criteres_atteints.presences_actuelles >= validationProgression.criteres_requis.presences_minimum ? 'text-green-400' : 'text-amber-400'">
                {{ validationProgression.criteres_atteints.presences_actuelles || 0 }} / {{ validationProgression.criteres_requis.presences_minimum }}
              </span>
            </div>
          </div>
        </div>

        <!-- Avertissements -->
        <div v-if="validationProgression.raisons_blocage?.length > 0" class="bg-amber-500/10 border border-amber-500/30 rounded-xl p-4 mb-4">
          <h4 class="text-sm font-medium text-amber-400 mb-2">⚠️ Avertissements</h4>
          <ul class="text-sm text-amber-300 space-y-1">
            <li v-for="raison in validationProgression.raisons_blocage" :key="raison" class="flex items-start gap-2">
              <span class="text-amber-500 mt-0.5">•</span>
              <span>{{ raison }}</span>
            </li>
          </ul>
        </div>

        <!-- Actions Progression -->
        <div class="flex gap-3">
          <button @click="openProgressionModal()" 
                  class="px-4 py-2 rounded-lg bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
            </svg>
            Faire Progresser
          </button>
          <button @click="openHistoriqueModal()" 
                  class="px-4 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-white text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Historique
          </button>
        </div>
      </div>

      <!-- Informations Générales -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Informations Personnelles -->
        <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6">
          <h3 class="text-lg font-semibold text-white mb-4">👤 Informations Personnelles</h3>
          <div class="space-y-3">
            <div class="flex justify-between">
              <span class="text-slate-400">Nom complet</span>
              <span class="text-white font-medium">{{ membre.nom_complet }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-400">Date de naissance</span>
              <span class="text-white">{{ formatDate(membre.date_naissance) }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-400">Âge</span>
              <span class="text-white">{{ membre.age }} ans</span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-400">Sexe</span>
              <span class="text-white">{{ membre.sexe }}</span>
            </div>
            <div v-if="membre.telephone" class="flex justify-between">
              <span class="text-slate-400">Téléphone</span>
              <span class="text-white">{{ membre.telephone }}</span>
            </div>
            <div v-if="membre.ville" class="flex justify-between">
              <span class="text-slate-400">Ville</span>
              <span class="text-white">{{ membre.ville }}</span>
            </div>
          </div>
        </div>

        <!-- Contact d'Urgence -->
        <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6">
          <h3 class="text-lg font-semibold text-white mb-4">🚨 Contact d'Urgence</h3>
          <div class="space-y-3">
            <div class="flex justify-between">
              <span class="text-slate-400">Nom</span>
              <span class="text-white">{{ membre.contact_urgence_nom || '—' }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-400">Téléphone</span>
              <span class="text-white">{{ membre.contact_urgence_telephone || '—' }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-400">Relation</span>
              <span class="text-white">{{ membre.contact_urgence_relation || '—' }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Historique des Progressions -->
      <div v-if="historiqueProgressions?.length > 0" class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6 mb-6">
        <h3 class="text-lg font-semibold text-white mb-4">📈 Historique des Progressions</h3>
        <div class="space-y-3">
          <div v-for="progression in historiqueProgressions.slice(0, 5)" :key="progression.id" 
               class="flex items-center justify-between p-3 bg-slate-900/50 rounded-lg border border-slate-700/30">
            <div class="flex items-center gap-3">
              <div class="w-6 h-6 rounded-full" :style="`background-color: ${progression.ceinture_nouvelle.couleur_hex}`"></div>
              <div>
                <div class="text-white font-medium">{{ progression.ceinture_nouvelle.nom }}</div>
                <div class="text-xs text-slate-400">{{ formatDate(progression.date_obtention) }}</div>
              </div>
            </div>
            <div class="text-right">
              <div class="text-sm text-slate-300">{{ progression.instructeur || 'Non spécifié' }}</div>
              <div class="text-xs text-slate-500">{{ progression.type_progression }}</div>
            </div>
          </div>
          <div v-if="historiqueProgressions.length > 5" class="text-center">
            <button @click="openHistoriqueModal()" class="text-blue-400 hover:text-blue-300 text-sm">
              Voir tout l'historique ({{ historiqueProgressions.length }} progressions)
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Progression -->
    <div v-if="showProgressionModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="closeProgressionModal"></div>
      <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative w-full max-w-lg bg-gradient-to-b from-slate-800 to-slate-900 rounded-2xl p-6 shadow-2xl border border-slate-700/50">
          <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
            🥋 Faire Progresser {{ membre.prenom }}
          </h3>
          
          <form @submit.prevent="validerProgression" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">Nouvelle Ceinture</label>
              <select v-model="formProgression.ceinture_id" required
                      class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Sélectionner une ceinture...</option>
                <option v-for="ceinture in ceinturesDisponibles" :key="ceinture.id" :value="ceinture.id">
                  {{ ceinture.nom }}
                </option>
              </select>
              <div v-if="formProgression.ceinture_id" class="mt-2 flex items-center gap-2">
                <div class="w-4 h-4 rounded-full border border-slate-600" 
                     :style="`background-color: ${ceinturesDisponibles.find(c => c.id == formProgression.ceinture_id)?.couleur_hex}`"></div>
                <span class="text-xs text-slate-400">{{ ceinturesDisponibles.find(c => c.id == formProgression.ceinture_id)?.nom }}</span>
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">Notes (optionnel)</label>
              <textarea v-model="formProgression.notes" rows="3"
                        class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Notes sur la progression, examens, recommandations..."></textarea>
            </div>
            
            <div class="flex items-center gap-2">
              <input type="checkbox" id="forcer" v-model="formProgression.forcer" 
                     class="rounded bg-slate-900 border-slate-700 text-blue-600 focus:ring-blue-500">
              <label for="forcer" class="text-sm text-slate-400">
                Forcer la progression (ignorer les avertissements)
              </label>
            </div>
            
            <div class="flex justify-end gap-3 pt-4">
              <button type="button" @click="closeProgressionModal"
                      class="px-4 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-white text-sm font-medium">
                Annuler
              </button>
              <button type="submit" :disabled="!formProgression.ceinture_id"
                      class="px-4 py-2 rounded-lg bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                Valider Progression
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal Historique Complet -->
    <div v-if="showHistoriqueModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="closeHistoriqueModal"></div>
      <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative w-full max-w-2xl bg-gradient-to-b from-slate-800 to-slate-900 rounded-2xl p-6 shadow-2xl border border-slate-700/50 max-h-[80vh] overflow-y-auto">
          <h3 class="text-xl font-bold text-white mb-6">📈 Historique Complet des Progressions</h3>
          
          <div v-if="historiqueProgressions?.length > 0" class="space-y-3">
            <div v-for="progression in historiqueProgressions" :key="progression.id" 
                 class="flex items-center justify-between p-4 bg-slate-900/50 rounded-lg border border-slate-700/30">
              <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                  <div v-if="progression.ceinture_precedente" class="w-5 h-5 rounded-full" 
                       :style="`background-color: ${progression.ceinture_precedente.couleur_hex}`"></div>
                  <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                  </svg>
                  <div class="w-5 h-5 rounded-full" :style="`background-color: ${progression.ceinture_nouvelle.couleur_hex}`"></div>
                </div>
                <div>
                  <div class="text-white font-medium">{{ progression.ceinture_nouvelle.nom }}</div>
                  <div class="text-xs text-slate-400">{{ formatDate(progression.date_obtention) }}</div>
                </div>
              </div>
              <div class="text-right">
                <div class="text-sm text-slate-300">{{ progression.instructeur || 'Non spécifié' }}</div>
                <div class="text-xs text-slate-500">{{ progression.type_progression }}</div>
                <div v-if="progression.notes" class="text-xs text-slate-400 italic mt-1">{{ progression.notes }}</div>
              </div>
            </div>
          </div>
          
          <div v-else class="text-center py-8">
            <div class="text-slate-400">Aucune progression enregistrée</div>
          </div>
          
          <div class="flex justify-end pt-4">
            <button @click="closeHistoriqueModal"
                    class="px-4 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-white text-sm font-medium">
              Fermer
            </button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import StatCard from '@/Components/UI/StatCard.vue'

const props = defineProps({
  membre: Object,
  ceintures: Array,
  historiqueProgressions: Array,
  validationProgression: Object,
})

// Modals
const showProgressionModal = ref(false)
const showHistoriqueModal = ref(false)

// Form pour progression
const formProgression = ref({
  ceinture_id: '',
  notes: '',
  forcer: false
})

// Computed
const ceinturesDisponibles = computed(() => {
  if (!props.membre.ceinture_actuelle) {
    return props.ceintures
  }
  // Filtrer les ceintures supérieures à la ceinture actuelle
  return props.ceintures.filter(c => c.order > props.membre.ceinture_actuelle.order)
})

// Methods
const formatDate = (date) => {
  if (!date) return '—'
  return new Date(date).toLocaleDateString('fr-CA')
}

const openProgressionModal = () => {
  // Pré-sélectionner la prochaine ceinture logique si disponible
  if (props.validationProgression?.prochaine_ceinture) {
    formProgression.value.ceinture_id = props.validationProgression.prochaine_ceinture.id
  }
  showProgressionModal.value = true
}

const closeProgressionModal = () => {
  showProgressionModal.value = false
  formProgression.value = { ceinture_id: '', notes: '', forcer: false }
}

const openHistoriqueModal = () => {
  showHistoriqueModal.value = true
}

const closeHistoriqueModal = () => {
  showHistoriqueModal.value = false
}

const validerProgression = () => {
  router.post(`/membres/${props.membre.id}/progresser-ceinture`, {
    ceinture_id: formProgression.value.ceinture_id,
    notes: formProgression.value.notes,
    forcer: formProgression.value.forcer
  }, {
    preserveScroll: true,
    onSuccess: () => {
      closeProgressionModal()
    },
    onError: (errors) => {
      console.error('Erreur progression:', errors)
    }
  })
}
</script>
