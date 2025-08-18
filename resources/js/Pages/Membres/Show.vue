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
            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-200 flex items-center"
          >
            ✏️ Modifier
          </Link>
          <Link
            :href="route('membres.index')"
            class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-200 flex items-center"
          >
            ← Retour
          </Link>
        </div>
      </div>
    </template>

    <div class="py-12 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 min-h-screen">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Boutons d'action rapides -->
  <div class="bg-blue-900/80 border border-blue-800 rounded-xl p-6 mb-6 shadow-lg">
          <h3 class="text-lg font-semibold text-blue-100 mb-4 flex items-center">
            ⚡ Actions Rapides
          </h3>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Ajouter Ceinture -->
            <button
              @click="openModalCeinture"
              class="bg-gradient-to-r from-blue-700 to-indigo-800 hover:from-blue-800 hover:to-indigo-900 text-white font-semibold py-3 px-4 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200 flex flex-col items-center space-y-2"
            >
              <span class="text-2xl">�</span>
              <span class="text-sm">Nouvelle Ceinture</span>
            </button>

            <!-- Ajouter Séminaire -->
            <button
              @click="openModalSeminaire"
              class="bg-gradient-to-r from-blue-700 to-indigo-800 hover:from-blue-800 hover:to-indigo-900 text-white font-semibold py-3 px-4 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200 flex flex-col items-center space-y-2"
            >
              <span class="text-2xl">🎓</span>
              <span class="text-sm">Séminaire</span>
            </button>

            <!-- Ajouter Paiement -->
            <button
              @click="openModalPaiement"
              class="bg-gradient-to-r from-blue-700 to-indigo-800 hover:from-blue-800 hover:to-indigo-900 text-white font-semibold py-3 px-4 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200 flex flex-col items-center space-y-2"
            >
              <span class="text-2xl">💳</span>
              <span class="text-sm">Paiement</span>
            </button>

            <!-- Marquer Présence -->
            <button
              @click="marquerPresence"
              class="bg-gradient-to-r from-blue-700 to-indigo-800 hover:from-blue-800 hover:to-indigo-900 text-white font-semibold py-3 px-4 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200 flex flex-col items-center space-y-2"
            >
              <span class="text-2xl">✅</span>
              <span class="text-sm">Présence</span>
            </button>
          </div>
        </div>
        
        <!-- Carte profil principal -->
  <div class="bg-blue-900/80 border border-blue-800 overflow-hidden shadow-lg sm:rounded-xl mb-6">
          <div class="p-6 text-blue-100">
            <div class="flex items-start space-x-6">
              
              <!-- Avatar -->
              <div class="flex-shrink-0">
                <div class="h-24 w-24 rounded-full bg-blue-800 flex items-center justify-center text-2xl font-bold text-blue-100 shadow">
                  {{ membre.prenom[0] }}{{ membre.nom[0] }}
                </div>
              </div>

              <!-- Informations principales -->
              <div class="flex-1 min-w-0">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                  <div>
                    <h3 class="text-lg font-semibold text-blue-100">{{ membre.nom_complet }}</h3>
                    <p class="text-sm text-blue-200">{{ calculerAge(membre.date_naissance) }} ans</p>
                    <p class="text-sm text-blue-200">{{ membre.sexe === 'M' ? 'Masculin' : membre.sexe === 'F' ? 'Féminin' : 'Autre' }}</p>
                    
                    <div class="mt-2">
                      <span
                        :class="{
                          'bg-blue-700/30 text-blue-200 border border-blue-800': membre.statut === 'actif',
                          'bg-gray-700/30 text-gray-300 border border-blue-800': membre.statut === 'inactif',
                          'bg-red-700/30 text-red-200 border border-blue-800': membre.statut === 'suspendu',
                          'bg-indigo-700/30 text-indigo-200 border border-blue-800': membre.statut === 'diplome'
                        }"
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                      >
                        {{ membre.statut.charAt(0).toUpperCase() + membre.statut.slice(1) }}
                      </span>
                    </div>
                  </div>

                  <div>
                    <h4 class="font-medium text-blue-100">Ceinture actuelle</h4>
                    <div class="flex items-center mt-1">
                      <div
                        class="w-4 h-4 rounded-full mr-2"
                        :style="`background-color: ${membre.ceinture_actuelle?.couleur_hex || '#gray'}`"
                      ></div>
                      <span class="text-sm text-blue-200">{{ membre.ceinture_actuelle?.nom || 'Aucune' }}</span>
                    </div>
                    <p class="text-xs text-blue-300 mt-1">
                      Inscrit le {{ formatDate(membre.date_inscription) }}
                    </p>
                  </div>

                  <div>
                    <h4 class="font-medium text-blue-100">Contact</h4>
                    <p class="text-sm text-blue-200" v-if="membre.telephone">📞 {{ membre.telephone }}</p>
                    <p class="text-sm text-blue-200" v-if="membre.ville">📍 {{ membre.ville }}</p>
                    <p class="text-xs text-blue-300 mt-1" v-if="membre.date_derniere_presence">
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
          <div class="bg-blue-900/80 border border-blue-800 overflow-hidden shadow-lg sm:rounded-xl">
            <div class="p-6 text-blue-100">
              <h3 class="text-lg font-semibold text-blue-100 mb-4">
                🥋 Progression et Ceintures
              </h3>
              
              <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-blue-950/60 rounded">
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
                        :class="progression.peut_passer ? 'text-blue-300' : 'text-blue-700'"
                        class="text-sm font-medium"
                      >
                        {{ progression.peut_passer ? '✅ Éligible' : '⏳ En attente' }}
                      </span>
                    </div>
                    
                    <div class="mt-2 grid grid-cols-2 gap-2 text-xs">
                      <div class="text-center p-2 bg-blue-950/60 rounded">
                        <div class="font-medium text-blue-200">Présences</div>
                        <div>{{ progression.presences_actuelles }} / {{ progression.presences_requises }}</div>
                      </div>
                      <div class="text-center p-2 bg-blue-950/60 rounded">
                        <div class="font-medium text-blue-200">Temps minimum</div>
                        <div>{{ progression.mois_requis }} mois</div>
                      </div>
                    </div>
                  </div>

                  <button
                    @click="ouvrirModalChangementCeinture"
                    class="w-full bg-gradient-to-r from-blue-700 to-indigo-800 hover:from-blue-800 hover:to-indigo-900 text-white font-bold py-2 px-4 rounded text-sm shadow"
                  >
                    🎯 Changer de ceinture
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Contact d'urgence -->
          <div class="bg-blue-900/80 border border-blue-800 overflow-hidden shadow-lg sm:rounded-xl">
            <div class="p-6 text-blue-100">
              <h3 class="text-lg font-semibold text-blue-100 mb-4">
                🚨 Contact d'urgence
              </h3>
              
              <div class="space-y-3">
                <div class="flex justify-between">
                  <span class="text-blue-200">Nom:</span>
                  <span class="font-medium">{{ membre.contact_urgence_nom }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-blue-200">Téléphone:</span>
                  <span class="font-medium">{{ membre.contact_urgence_telephone }}</span>
                </div>
                <div class="flex justify-between" v-if="membre.contact_urgence_relation">
                  <span class="text-blue-200">Relation:</span>
                  <span class="font-medium">{{ membre.contact_urgence_relation }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Adresse complète -->
          <div class="bg-blue-900/80 border border-blue-800 overflow-hidden shadow-lg sm:rounded-xl" v-if="membre.adresse">
            <div class="p-6 text-blue-100">
              <h3 class="text-lg font-semibold text-blue-100 mb-4">
                📍 Adresse
              </h3>
              
              <div class="text-blue-200">
                <p>{{ membre.adresse }}</p>
                <p v-if="membre.ville || membre.code_postal">
                  <span v-if="membre.ville">{{ membre.ville }}</span>
                  <span v-if="membre.code_postal" class="ml-2">{{ membre.code_postal }}</span>
                </p>
              </div>
            </div>
          </div>

          <!-- Informations médicales -->
          <div class="bg-blue-900/80 border border-blue-800 overflow-hidden shadow-lg sm:rounded-xl" v-if="membre.notes_medicales || membre.allergies?.length">
            <div class="p-6 text-blue-100">
              <h3 class="text-lg font-semibold text-blue-100 mb-4">
                🏥 Informations médicales
              </h3>
              
              <div class="space-y-3">
                <div v-if="membre.notes_medicales">
                  <h4 class="font-medium text-blue-200">Notes médicales:</h4>
                  <p class="text-blue-200 text-sm bg-blue-950/60 p-2 rounded">{{ membre.notes_medicales }}</p>
                </div>
                
                <div v-if="membre.allergies?.length">
                  <h4 class="font-medium text-blue-200">Allergies:</h4>
                  <div class="flex flex-wrap gap-1 mt-1">
                    <span
                      v-for="allergie in membre.allergies"
                      :key="allergie"
                      class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-700/30 text-red-200 border border-blue-800"
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
          <div class="bg-blue-900/80 border border-blue-800 overflow-hidden shadow-lg sm:rounded-xl">
            <div class="p-6 text-blue-100">
              <h3 class="text-lg font-semibold text-blue-100 mb-4">
                📅 Présences récentes
              </h3>
              
              <div class="space-y-2" v-if="membre.presences?.length">
                <div
                  v-for="presence in membre.presences"
                  :key="presence.id"
                  class="flex justify-between items-center p-2 bg-blue-950/60 rounded"
                >
                  <div>
                    <div class="font-medium text-sm text-blue-200">{{ presence.cours?.nom || 'Cours supprimé' }}</div>
                    <div class="text-xs text-blue-300">{{ formatDate(presence.date_cours) }}</div>
                  </div>
                  <span
                    :class="{
                      'bg-blue-700/30 text-blue-200 border border-blue-800': presence.statut === 'present',
                      'bg-red-700/30 text-red-200 border border-blue-800': presence.statut === 'absent',
                      'bg-yellow-700/30 text-yellow-200 border border-blue-800': presence.statut === 'retard',
                      'bg-indigo-700/30 text-indigo-200 border border-blue-800': presence.statut === 'excuse'
                    }"
                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                  >
                    {{ presence.statut }}
                  </span>
                </div>
              </div>
              <p v-else class="text-blue-300 text-sm">Aucune présence enregistrée</p>
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
          <h3 class="text-lg font-medium text-gray-900">🥋 Nouvelle Ceinture</h3>
          <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">Nouvelle ceinture</label>
            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
              <option>Blanche</option>
              <option>Jaune</option>
              <option>Orange</option>
              <option>Verte</option>
              <option>Bleue</option>
              <option>Brune</option>
              <option>Noire</option>
            </select>
            
            <label class="block text-sm font-medium text-gray-700 mt-4">Date d'obtention</label>
            <input type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            
            <label class="block text-sm font-medium text-gray-700 mt-4">Notes</label>
            <textarea class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" rows="3" placeholder="Notes sur l'examen..."></textarea>
          </div>
          <div class="mt-4 flex justify-end space-x-2">
            <button
              @click="showModalCeinture = false"
              class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded text-sm"
            >
              Annuler
            </button>
            <button
              class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded text-sm"
            >
              Valider Ceinture
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal séminaire -->
    <div v-if="showModalSeminaire" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900">🎓 Inscription Séminaire</h3>
          <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">Type de séminaire</label>
            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
              <option>Stage technique</option>
              <option>Stage arbitrage</option>
              <option>Formation instructeur</option>
              <option>Séminaire international</option>
            </select>
            
            <label class="block text-sm font-medium text-gray-700 mt-4">Date du séminaire</label>
            <input type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            
            <label class="block text-sm font-medium text-gray-700 mt-4">Coût</label>
            <input type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="150.00">
          </div>
          <div class="mt-4 flex justify-end space-x-2">
            <button
              @click="showModalSeminaire = false"
              class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded text-sm"
            >
              Annuler
            </button>
            <button
              class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-4 rounded text-sm"
            >
              Inscrire
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal paiement -->
    <div v-if="showModalPaiement" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900">💳 Nouveau Paiement</h3>
          <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">Type de paiement</label>
            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
              <option>Cotisation mensuelle</option>
              <option>Inscription annuelle</option>
              <option>Examen de ceinture</option>
              <option>Séminaire</option>
              <option>Équipement</option>
            </select>
            
            <label class="block text-sm font-medium text-gray-700 mt-4">Montant</label>
            <input type="number" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="75.00">
            
            <label class="block text-sm font-medium text-gray-700 mt-4">Méthode de paiement</label>
            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
              <option>Comptant</option>
              <option>Carte de crédit</option>
              <option>Débit</option>
              <option>Virement</option>
              <option>Chèque</option>
            </select>
          </div>
          <div class="mt-4 flex justify-end space-x-2">
            <button
              @click="showModalPaiement = false"
              class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded text-sm"
            >
              Annuler
            </button>
            <button
              class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded text-sm"
            >
              Enregistrer Paiement
            </button>
          </div>
        </div>
      </div>
    </div>

  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  membre: Object,
  progression: Object
})

const showModalCeinture = ref(false)
const showModalSeminaire = ref(false)
const showModalPaiement = ref(false)

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

// Actions des boutons
const openModalCeinture = () => {
  showModalCeinture.value = true
}

const openModalSeminaire = () => {
  showModalSeminaire.value = true
}

const openModalPaiement = () => {
  showModalPaiement.value = true
}

const marquerPresence = () => {
  // Logique pour marquer la présence
  router.post(route('presences.store'), {
    membre_id: props.membre.id,
    date: new Date().toISOString().split('T')[0]
  }, {
    onSuccess: () => {
      alert('Présence marquée avec succès!')
    }
  })
}

const ouvrirModalChangementCeinture = () => {
  showModalCeinture.value = true
}
</script>
