<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Modifier {{ membre.nom_complet }}
        </h2>
        <div class="flex space-x-2">
          <Link
            :href="route('membres.show', membre.id)"
            class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
          >
            ← Voir le profil
          </Link>
          <Link
            :href="route('membres.index')"
            class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
          >
            Liste
          </Link>
        </div>
      </div>
    </template>

    <div class="py-12 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 min-h-screen">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-blue-900/80 border border-blue-800 overflow-hidden shadow-lg sm:rounded-xl">
          <div class="p-6">
            
            <!-- Alert de modification -->
            <div class="mb-6 bg-blue-950/60 border border-blue-800 rounded-md p-4 text-blue-200">
              <div class="flex">
                <div class="flex-shrink-0">
                  <svg class="h-5 w-5 text-blue-300" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                  </svg>
                </div>
                <div class="ml-3">
                  <p class="text-sm">
                    Modification du profil de <strong>{{ membre.nom_complet }}</strong>. 
                    Les changements seront sauvegardés immédiatement.
                  </p>
                </div>
              </div>
            </div>

            <form @submit.prevent="submit">
              
              <!-- Informations personnelles -->
              <div class="mb-8">
                <h3 class="text-lg font-semibold text-blue-100 mb-4">
                  Informations personnelles
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-medium text-blue-200">Prénom *</label>
                    <input
                      v-model="form.prenom"
                      type="text"
                      required
                      class="mt-1 block w-full rounded-md border-blue-800 bg-blue-950/80 text-blue-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 placeholder-blue-300"
                      :class="{ 'border-red-500': errors.prenom }"
                    />
                    <div v-if="errors.prenom" class="text-red-400 text-sm mt-1">
                      {{ errors.prenom }}
                    </div>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-blue-200">Nom *</label>
                    <input
                      v-model="form.nom"
                      type="text"
                      required
                      class="mt-1 block w-full rounded-md border-blue-800 bg-blue-950/80 text-blue-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 placeholder-blue-300"
                      :class="{ 'border-red-500': errors.nom }"
                    />
                    <div v-if="errors.nom" class="text-red-400 text-sm mt-1">
                      {{ errors.nom }}
                    </div>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-blue-200">Date de naissance *</label>
                    <input
                      v-model="form.date_naissance"
                      type="date"
                      required
                      class="mt-1 block w-full rounded-md border-blue-800 bg-blue-950/80 text-blue-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 placeholder-blue-300"
                      :class="{ 'border-red-500': errors.date_naissance }"
                    />
                    <div v-if="errors.date_naissance" class="text-red-400 text-sm mt-1">
                      {{ errors.date_naissance }}
                    </div>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-blue-200">Sexe *</label>
                    <select
                      v-model="form.sexe"
                      required
                      class="mt-1 block w-full rounded-md border-blue-800 bg-blue-950/80 text-blue-100 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      :class="{ 'border-red-500': errors.sexe }"
                    >
                      <option value="">Sélectionner...</option>
                      <option value="M">Masculin</option>
                      <option value="F">Féminin</option>
                      <option value="Autre">Autre</option>
                    </select>
                    <div v-if="errors.sexe" class="text-red-400 text-sm mt-1">
                      {{ errors.sexe }}
                    </div>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-blue-200">Téléphone</label>
                    <input
                      v-model="form.telephone"
                      type="tel"
                      class="mt-1 block w-full rounded-md border-blue-800 bg-blue-950/80 text-blue-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 placeholder-blue-300"
                      placeholder="(514) 555-0123"
                    />
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-blue-200">Statut *</label>
                    <select
                      v-model="form.statut"
                      required
                      class="mt-1 block w-full rounded-md border-blue-800 bg-blue-950/80 text-blue-100 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      :class="{ 'border-red-500': errors.statut }"
                    >
                      <option value="actif">Actif</option>
                      <option value="inactif">Inactif</option>
                      <option value="suspendu">Suspendu</option>
                      <option value="diplome">Diplômé</option>
                    </select>
                    <div v-if="errors.statut" class="text-red-400 text-sm mt-1">
                      {{ errors.statut }}
                    </div>
                  </div>
                </div>
              </div>

              <!-- Ceinture actuelle -->
              <div class="mb-8">
                <h3 class="text-lg font-semibold text-blue-100 mb-4">
                  Progression martiale
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-medium text-blue-200">Ceinture actuelle *</label>
                    <select
                      v-model="form.ceinture_actuelle_id"
                      required
                      class="mt-1 block w-full rounded-md border-blue-800 bg-blue-950/80 text-blue-100 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      :class="{ 'border-red-500': errors.ceinture_actuelle_id }"
                    >
                      <option value="">Sélectionner une ceinture...</option>
                      <option
                        v-for="ceinture in ceintures"
                        :key="ceinture.id"
                        :value="ceinture.id"
                      >
                        {{ ceinture.nom }}
                      </option>
                    </select>
                    <div v-if="errors.ceinture_actuelle_id" class="text-red-400 text-sm mt-1">
                      {{ errors.ceinture_actuelle_id }}
                    </div>
                  </div>
                  
                  <div class="flex items-end">
                    <div class="text-sm text-gray-600">
                      <p>Membre depuis: {{ formatDate(membre.date_inscription) }}</p>
                      <p v-if="membre.date_derniere_presence">
                        Dernière présence: {{ formatDate(membre.date_derniere_presence) }}
                      </p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Adresse -->
              <div class="mb-8">
                <h3 class="text-lg font-semibold text-blue-100 mb-4">
                  Adresse
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                  <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-blue-200">Adresse</label>
                    <textarea
                      v-model="form.adresse"
                      rows="2"
                      class="mt-1 block w-full rounded-md border-blue-800 bg-blue-950/80 text-blue-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 placeholder-blue-300"
                      placeholder="123 Rue Example"
                    ></textarea>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700">Ville</label>
                    <input
                      v-model="form.ville"
                      type="text"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      placeholder="Montréal"
                    />
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700">Code postal</label>
                    <input
                      v-model="form.code_postal"
                      type="text"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      placeholder="H1H 1H1"
                      pattern="[A-Za-z]\d[A-Za-z][\s\-]?\d[A-Za-z]\d"
                    />
                    <div v-if="errors.code_postal" class="text-red-500 text-sm mt-1">
                      {{ errors.code_postal }}
                    </div>
                  </div>
                </div>
              </div>

              <!-- Contact d'urgence -->
              <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                  Contact d'urgence *
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Nom complet *</label>
                    <input
                      v-model="form.contact_urgence_nom"
                      type="text"
                      required
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      :class="{ 'border-red-500': errors.contact_urgence_nom }"
                    />
                    <div v-if="errors.contact_urgence_nom" class="text-red-500 text-sm mt-1">
                      {{ errors.contact_urgence_nom }}
                    </div>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700">Téléphone *</label>
                    <input
                      v-model="form.contact_urgence_telephone"
                      type="tel"
                      required
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      :class="{ 'border-red-500': errors.contact_urgence_telephone }"
                    />
                    <div v-if="errors.contact_urgence_telephone" class="text-red-500 text-sm mt-1">
                      {{ errors.contact_urgence_telephone }}
                    </div>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700">Relation</label>
                    <select
                      v-model="form.contact_urgence_relation"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                      <option value="">Sélectionner...</option>
                      <option value="Parent">Parent</option>
                      <option value="Conjoint">Conjoint(e)</option>
                      <option value="Frère/Sœur">Frère/Sœur</option>
                      <option value="Ami">Ami(e)</option>
                      <option value="Autre">Autre</option>
                    </select>
                  </div>
                </div>
              </div>

              <!-- Informations médicales -->
              <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                  Informations médicales
                </h3>
                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Notes médicales</label>
                    <textarea
                      v-model="form.notes_medicales"
                      rows="3"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      placeholder="Conditions médicales, limitations, recommandations..."
                    ></textarea>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700">Allergies</label>
                    <input
                      v-model="allergiesText"
                      type="text"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      placeholder="Arachides, lactose, pollen... (séparées par des virgules)"
                      @input="updateAllergies"
                    />
                  </div>
                </div>
              </div>

              <!-- Notes administratives -->
              <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                  Notes administratives
                </h3>
                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Notes instructeur</label>
                    <textarea
                      v-model="form.notes_instructeur"
                      rows="3"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      placeholder="Notes pédagogiques, progression technique, comportement..."
                    ></textarea>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700">Notes administratives</label>
                    <textarea
                      v-model="form.notes_admin"
                      rows="2"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      placeholder="Notes administratives internes..."
                    ></textarea>
                  </div>
                </div>
              </div>

              <!-- Consentements -->
              <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                  Consentements
                </h3>
                <div class="space-y-4">
                  <div class="flex items-start">
                    <input
                      v-model="form.consentement_communications"
                      type="checkbox"
                      class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    />
                    <label class="ml-3 text-sm text-gray-700">
                      Accepte de recevoir des communications par courriel ou SMS
                    </label>
                  </div>

                  <div class="flex items-start">
                    <input
                      v-model="form.consentement_photos"
                      type="checkbox"
                      class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    />
                    <label class="ml-3 text-sm text-gray-700">
                      Autorise la prise et l'utilisation de photos/vidéos pour les activités du dojo
                    </label>
                  </div>
                </div>
              </div>

              <!-- Boutons -->
              <div class="flex justify-between">
                <button
                  type="button"
                  @click="confirmerSuppression"
                  class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                >
                  🗑️ Supprimer le membre
                </button>

                <div class="flex space-x-3">
                  <Link
                    :href="route('membres.show', membre.id)"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded"
                  >
                    Annuler
                  </Link>
                  <button
                    type="submit"
                    :disabled="processing"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                  >
                    <span v-if="processing">Sauvegarde...</span>
                    <span v-else">💾 Sauvegarder</span>
                  </button>
                </div>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal confirmation suppression -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-900 text-center mt-2">Confirmer la suppression</h3>
          <div class="mt-4">
            <p class="text-sm text-gray-500 text-center">
              Êtes-vous sûr de vouloir supprimer le membre <strong>{{ membre.nom_complet }}</strong> ?
              Cette action est définitive et toutes les données associées seront supprimées.
            </p>
          </div>
          <div class="mt-4 flex justify-center space-x-2">
            <button
              @click="showDeleteModal = false"
              class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded text-sm"
            >
              Annuler
            </button>
            <button
              @click="supprimerMembre"
              class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm"
            >
              🗑️ Supprimer définitivement
            </button>
          </div>
        </div>
      </div>
    </div>

  </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  membre: Object,
  ceintures: Array,
  errors: Object
})

const showDeleteModal = ref(false)
const allergiesText = ref('')

const form = useForm({
  prenom: props.membre.prenom,
  nom: props.membre.nom,
  date_naissance: props.membre.date_naissance,
  sexe: props.membre.sexe,
  telephone: props.membre.telephone,
  adresse: props.membre.adresse,
  ville: props.membre.ville,
  code_postal: props.membre.code_postal,
  contact_urgence_nom: props.membre.contact_urgence_nom,
  contact_urgence_telephone: props.membre.contact_urgence_telephone,
  contact_urgence_relation: props.membre.contact_urgence_relation,
  statut: props.membre.statut,
  ceinture_actuelle_id: props.membre.ceinture_actuelle_id,
  notes_medicales: props.membre.notes_medicales,
  allergies: props.membre.allergies || [],
  notes_instructeur: props.membre.notes_instructeur,
  notes_admin: props.membre.notes_admin,
  consentement_photos: props.membre.consentement_photos,
  consentement_communications: props.membre.consentement_communications
})

onMounted(() => {
  if (props.membre.allergies && props.membre.allergies.length > 0) {
    allergiesText.value = props.membre.allergies.join(', ')
  }
})

const updateAllergies = () => {
  form.allergies = allergiesText.value
    .split(',')
    .map(a => a.trim())
    .filter(a => a.length > 0)
}

const formatDate = (date) => {
  if (!date) return 'Non spécifié'
  return new Date(date).toLocaleDateString('fr-CA')
}

const submit = () => {
  form.put(route('membres.update', props.membre.id), {
    onSuccess: () => {
      // Redirection automatique gérée par le contrôleur
    }
  })
}

const confirmerSuppression = () => {
  showDeleteModal.value = true
}

const supprimerMembre = () => {
  router.delete(route('membres.destroy', props.membre.id), {
    onSuccess: () => {
      showDeleteModal.value = false
    }
  })
}
</script>
