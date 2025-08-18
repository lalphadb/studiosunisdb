<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-blue-100 leading-tight">
          Nouveau Membre
        </h2>
        <Link
          :href="route('membres.index')"
          class="bg-gradient-to-r from-blue-700 to-indigo-800 hover:from-blue-800 hover:to-indigo-900 text-white font-bold py-2 px-4 rounded shadow"
        >
          ← Retour à la liste
        </Link>
      </div>
    </template>

    <div class="py-12 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 min-h-screen">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-blue-900/80 border border-blue-800 shadow-lg sm:rounded-xl">
          <div class="p-6">
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
                    <label class="block text-sm font-medium text-blue-200">Ville</label>
                    <input
                      v-model="form.ville"
                      type="text"
                      class="mt-1 block w-full rounded-md border-blue-800 bg-blue-950/80 text-blue-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 placeholder-blue-300"
                      placeholder="Montréal"
                    />
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-blue-200">Code postal</label>
                    <input
                      v-model="form.code_postal"
                      type="text"
                      class="mt-1 block w-full rounded-md border-blue-800 bg-blue-950/80 text-blue-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 placeholder-blue-300"
                      placeholder="H1H 1H1"
                      pattern="[A-Za-z]\d[A-Za-z][\s\-]?\d[A-Za-z]\d"
                    />
                    <div v-if="errors.code_postal" class="text-red-400 text-sm mt-1">
                      {{ errors.code_postal }}
                    </div>
                  </div>
                </div>
              </div>

              <!-- Contact d'urgence -->
              <div class="mb-8">
                <h3 class="text-lg font-semibold text-blue-100 mb-4">
                  Contact d'urgence *
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                  <div>
                    <label class="block text-sm font-medium text-blue-200">Nom complet *</label>
                    <input
                      v-model="form.contact_urgence_nom"
                      type="text"
                      required
                      class="mt-1 block w-full rounded-md border-blue-800 bg-blue-950/80 text-blue-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 placeholder-blue-300"
                      :class="{ 'border-red-500': errors.contact_urgence_nom }"
                    />
                    <div v-if="errors.contact_urgence_nom" class="text-red-400 text-sm mt-1">
                      {{ errors.contact_urgence_nom }}
                    </div>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-blue-200">Téléphone *</label>
                    <input
                      v-model="form.contact_urgence_telephone"
                      type="tel"
                      required
                      class="mt-1 block w-full rounded-md border-blue-800 bg-blue-950/80 text-blue-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 placeholder-blue-300"
                      :class="{ 'border-red-500': errors.contact_urgence_telephone }"
                    />
                    <div v-if="errors.contact_urgence_telephone" class="text-red-400 text-sm mt-1">
                      {{ errors.contact_urgence_telephone }}
                    </div>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-blue-200">Relation</label>
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

              <!-- Consentements -->
              <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                  Consentements (Loi 25 - Québec)
                </h3>
                <div class="space-y-4">
                  <div class="flex items-start">
                    <input
                      v-model="form.consentement_donnees"
                      type="checkbox"
                      required
                      class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    />
                    <label class="ml-3 text-sm text-gray-700">
                      <strong>J'accepte le traitement de mes données personnelles *</strong><br>
                      <span class="text-xs text-gray-500">
                        Conformément à la Loi 25 sur la protection des renseignements personnels (Québec)
                      </span>
                    </label>
                  </div>

                  <div class="flex items-start">
                    <input
                      v-model="form.consentement_communications"
                      type="checkbox"
                      class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    />
                    <label class="ml-3 text-sm text-gray-700">
                      J'accepte de recevoir des communications par courriel ou SMS
                    </label>
                  </div>

                  <div class="flex items-start">
                    <input
                      v-model="form.consentement_photos"
                      type="checkbox"
                      class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    />
                    <label class="ml-3 text-sm text-gray-700">
                      J'autorise la prise et l'utilisation de photos/vidéos pour les activités du dojo
                    </label>
                  </div>
                </div>
                <div v-if="errors.consentement_donnees" class="text-red-500 text-sm mt-2">
                  {{ errors.consentement_donnees }}
                </div>
              </div>

              <!-- Boutons -->
              <div class="flex justify-end space-x-3">
                <Link
                  :href="route('membres.index')"
                  class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded"
                >
                  Annuler
                </Link>
                <button
                  type="submit"
                  :disabled="processing"
                  class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                >
                  <span v-if="processing">Création...</span>
                  <span v-else>Créer le membre</span>
                </button>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  ceintures: Array,
  errors: Object
})

const allergiesText = ref('')

const form = useForm({
  prenom: '',
  nom: '',
  date_naissance: '',
  sexe: '',
  telephone: '',
  adresse: '',
  ville: '',
  code_postal: '',
  contact_urgence_nom: '',
  contact_urgence_telephone: '',
  contact_urgence_relation: '',
  ceinture_actuelle_id: '',
  notes_medicales: '',
  allergies: [],
  consentement_photos: false,
  consentement_communications: true,
  consentement_donnees: false
})

const updateAllergies = () => {
  form.allergies = allergiesText.value
    .split(',')
    .map(a => a.trim())
    .filter(a => a.length > 0)
}

const submit = () => {
  form.post(route('membres.store'), {
    onSuccess: () => {
      // Redirection automatique gérée par le contrôleur
    }
  })
}
</script>
