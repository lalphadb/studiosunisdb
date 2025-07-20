<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ membre.nom_complet }}
        </h2>
        <div class="flex space-x-2">
          <Link
            :href="route('membres.edit', membre.id)"
            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
          >
            ✏️ Modifier
          </Link>
          <Link
            :href="route('membres.index')"
            class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
          >
            ← Retour
          </Link>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Carte profil principal -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
          <div class="p-6">
            <div class="flex items-start space-x-6">
              
              <!-- Avatar -->
              <div class="flex-shrink-0">
                <div class="h-24 w-24 rounded-full bg-gray-300 flex items-center justify-center text-2xl font-bold text-gray-700">
                  {{ membre.prenom[0] }}{{ membre.nom[0] }}
                </div>
              </div>

              <!-- Informations principales -->
              <div class="flex-1 min-w-0">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                  <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ membre.nom_complet }}</h3>
                    <p class="text-sm text-gray-600">{{ calculerAge(membre.date_naissance) }} ans</p>
                    <p class="text-sm text-gray-600">{{ membre.sexe === 'M' ? 'Masculin' : membre.sexe === 'F' ? 'Féminin' : 'Autre' }}</p>
                    
                    <div class="mt-2">
                      <span
                        :class="{
                          'bg-green-100 text-green-800': membre.statut === 'actif',
                          'bg-yellow-100 text-yellow-800': membre.statut === 'inactif',
                          'bg-red-100 text-red-800': membre.statut === 'suspendu',
                          'bg-blue-100 text-blue-800': membre.statut === 'diplome'
                        }"
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                      >
                        {{ membre.statut.charAt(0).toUpperCase() + membre.statut.slice(1) }}
                      </span>
                    </div>
                  </div>

                  <div>
                    <h4 class="font-medium text-gray-900">Ceinture actuelle</h4>
                    <div class="flex items-center mt-1">
                      <div
                        class="w-4 h-4 rounded-full mr-2"
                        :style="`background-color: ${membre.ceinture_actuelle?.couleur_hex || '#gray'}`"
                      ></div>
                      <span class="text-sm text-gray-600">{{ membre.ceinture_actuelle?.nom || 'Aucune' }}</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                      Inscrit le {{ formatDate(membre.date_inscription) }}
                    </p>
                  </div>

                  <div>
                    <h4 class="font-medium text-gray-900">Contact</h4>
                    <p class="text-sm text-gray-600" v-if="membre.telephone">📞 {{ membre.telephone }}</p>
                    <p class="text-sm text-gray-600" v-if="membre.ville">📍 {{ membre.ville }}</p>
                    <p class="text-xs text-gray-500 mt-1" v-if="membre.date_derniere_presence">
                      Dernière présence: {{ formatDate(membre.date_derniere_presence) }}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Grille d'informations détaillées -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          
          <!-- Progression et ceintures -->
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">
                🥋 Progression et Ceintures
              </h3>
              
              <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                  <span class="font-medium">Ceinture actuelle:</span>
                  <div class="flex items-center">
                    <div
                      class="w-3 h-3 rounded-full mr-2"
                      :style="`background-color: ${membre.ceinture_actuelle?.couleur_hex}`"
                    ></div>
                    {{ membre.ceinture_actuelle?.nom }}
                  </div>
                </div>

                <div v-if="progression" class="space-y-3">
                  <div class="border-t pt-3">
                    <h4 class="font-medium text-gray-700 mb-2">Prochaine progression possible:</h4>
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded">
                      <div class="flex items-center">
                        <div
                          class="w-3 h-3 rounded-full mr-2"
                          :style="`background-color: ${progression.ceinture.couleur_hex}`"
                        ></div>
                        {{ progression.ceinture.nom }}
                      </div>
                      <span
                        :class="progression.peut_passer ? 'text-green-600' : 'text-orange-600'"
                        class="text-sm font-medium"
                      >
                        {{ progression.peut_passer ? '✅ Éligible' : '⏳ En attente' }}
                      </span>
                    </div>
                    
                    <div class="mt-2 grid grid-cols-2 gap-2 text-xs">
                      <div class="text-center p-2 bg-gray-100 rounded">
                        <div class="font-medium">Présences</div>
                        <div>{{ progression.presences_actuelles }} / {{ progression.presences_requises }}</div>
                      </div>
                      <div class="text-center p-2 bg-gray-100 rounded">
                        <div class="font-medium">Temps minimum</div>
                        <div>{{ progression.mois_requis }} mois</div>
                      </div>
                    </div>
                  </div>

                  <button
                    @click="ouvrirModalChangementCeinture"
                    class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded text-sm"
                  >
                    🎯 Changer de ceinture
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Contact d'urgence -->
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">
                🚨 Contact d'urgence
              </h3>
              
              <div class="space-y-3">
                <div class="flex justify-between">
                  <span class="text-gray-600">Nom:</span>
                  <span class="font-medium">{{ membre.contact_urgence_nom }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Téléphone:</span>
                  <span class="font-medium">{{ membre.contact_urgence_telephone }}</span>
                </div>
                <div class="flex justify-between" v-if="membre.contact_urgence_relation">
                  <span class="text-gray-600">Relation:</span>
                  <span class="font-medium">{{ membre.contact_urgence_relation }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Adresse complète -->
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" v-if="membre.adresse">
            <div class="p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">
                📍 Adresse
              </h3>
              
              <div class="text-gray-700">
                <p>{{ membre.adresse }}</p>
                <p v-if="membre.ville || membre.code_postal">
                  <span v-if="membre.ville">{{ membre.ville }}</span>
                  <span v-if="membre.code_postal" class="ml-2">{{ membre.code_postal }}</span>
                </p>
              </div>
            </div>
          </div>

          <!-- Informations médicales -->
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" v-if="membre.notes_medicales || membre.allergies?.length">
            <div class="p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">
                🏥 Informations médicales
              </h3>
              
              <div class="space-y-3">
                <div v-if="membre.notes_medicales">
                  <h4 class="font-medium text-gray-700">Notes médicales:</h4>
                  <p class="text-gray-600 text-sm bg-yellow-50 p-2 rounded">{{ membre.notes_medicales }}</p>
                </div>
                
                <div v-if="membre.allergies?.length">
                  <h4 class="font-medium text-gray-700">Allergies:</h4>
                  <div class="flex flex-wrap gap-1 mt-1">
                    <span
                      v-for="allergie in membre.allergies"
                      :key="allergie"
                      class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800"
                    >
                      ⚠️ {{ allergie }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Historique récent -->
        <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
          
          <!-- Présences récentes -->
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">
                📅 Présences récentes
              </h3>
              
              <div class="space-y-2" v-if="membre.presences?.length">
                <div
                  v-for="presence in membre.presences"
                  :key="presence.id"
                  class="flex justify-between items-center p-2 bg-gray-50 rounded"
                >
                  <div>
                    <div class="font-medium text-sm">{{ presence.cours?.nom || 'Cours supprimé' }}</div>
                    <div class="text-xs text-gray-500">{{ formatDate(presence.date_cours) }}</div>
                  </div>
                  <span
                    :class="{
                      'bg-green-100 text-green-800': presence.statut === 'present',
                      'bg-red-100 text-red-800': presence.statut === 'absent',
                      'bg-yellow-100 text-yellow-800': presence.statut === 'retard',
                      'bg-blue-100 text-blue-800': presence.statut === 'excuse'
                    }"
                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                  >
                    {{ presence.statut }}
                  </span>
                </div>
              </div>
              <p v-else class="text-gray-500 text-sm">Aucune présence enregistrée</p>
            </div>
          </div>

          <!-- Paiements récents -->
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">
                💰 Paiements récents
              </h3>
              
              <div class="space-y-2" v-if="membre.paiements?.length">
                <div
                  v-for="paiement in membre.paiements"
                  :key="paiement.id"
                  class="flex justify-between items-center p-2 bg-gray-50 rounded"
                >
                  <div>
                    <div class="font-medium text-sm">{{ paiement.description }}</div>
                    <div class="text-xs text-gray-500">Échéance: {{ formatDate(paiement.date_echeance) }}</div>
                  </div>
                  <div class="text-right">
                    <div class="font-medium">{{ formatMontant(paiement.montant) }}</div>
                    <span
                      :class="{
                        'bg-green-100 text-green-800': paiement.statut === 'paye',
                        'bg-yellow-100 text-yellow-800': paiement.statut === 'en_attente',
                        'bg-red-100 text-red-800': paiement.statut === 'en_retard'
                      }"
                      class="inline-flex items-center px-1 py-0.5 rounded text-xs font-medium"
                    >
                      {{ paiement.statut.replace('_', ' ') }}
                    </span>
                  </div>
                </div>
              </div>
              <p v-else class="text-gray-500 text-sm">Aucun paiement enregistré</p>
            </div>
          </div>
        </div>

        <!-- Consentements -->
        <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
              🛡️ Consentements et confidentialité
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="flex items-center space-x-2">
                <span class="text-2xl">{{ membre.consentement_donnees ? '✅' : '❌' }}</span>
                <span class="text-sm">Traitement des données</span>
              </div>
              <div class="flex items-center space-x-2">
                <span class="text-2xl">{{ membre.consentement_communications ? '✅' : '❌' }}</span>
                <span class="text-sm">Communications</span>
              </div>
              <div class="flex items-center space-x-2">
                <span class="text-2xl">{{ membre.consentement_photos ? '✅' : '❌' }}</span>
                <span class="text-sm">Photos/Vidéos</span>
              </div>
            </div>
            
            <p class="text-xs text-gray-500 mt-3">
              Conformément à la Loi 25 sur la protection des renseignements personnels (Québec)
            </p>
          </div>
        </div>

      </div>
    </div>

    <!-- Modal changement de ceinture -->
    <div v-if="showModalCeinture" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900">Changer de ceinture</h3>
          <div class="mt-4">
            <!-- Contenu du modal à implémenter -->
            <p class="text-sm text-gray-500">Fonctionnalité à développer...</p>
          </div>
          <div class="mt-4 flex justify-end space-x-2">
            <button
              @click="showModalCeinture = false"
              class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded text-sm"
            >
              Annuler
            </button>
          </div>
        </div>
      </div>
    </div>

  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  membre: Object,
  progression: Object
})

const showModalCeinture = ref(false)

const calculerAge = (dateNaissance) => {
  return Math.floor((new Date() - new Date(dateNaissance)) / (365.25 * 24 * 60 * 60 * 1000))
}

const formatDate = (date) => {
  if (!date) return 'Non spécifié'
  return new Date(date).toLocaleDateString('fr-CA')
}

const formatMontant = (montant) => {
  return new Intl.NumberFormat('fr-CA', {
    style: 'currency',
    currency: 'CAD'
  }).format(montant)
}

const ouvrirModalChangementCeinture = () => {
  showModalCeinture.value = true
}
</script>
