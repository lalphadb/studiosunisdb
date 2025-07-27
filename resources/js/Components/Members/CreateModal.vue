<template>
  <div class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div 
        class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75 backdrop-blur-sm"
        @click="$emit('close')"
      ></div>

      <!-- Modal -->
      <div class="inline-block w-full max-w-4xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-gray-800 shadow-xl rounded-2xl border border-gray-700">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-2xl font-bold text-white flex items-center">
            <UserPlusIcon class="h-6 w-6 text-blue-400 mr-3" />
            Nouveau Membre
          </h3>
          <button
            @click="$emit('close')"
            class="text-gray-400 hover:text-white transition-colors p-2 rounded-lg hover:bg-gray-700"
          >
            <XMarkIcon class="h-6 w-6" />
          </button>
        </div>

        <!-- Formulaire -->
        <form @submit.prevent="submitForm" class="space-y-6">
          <!-- Informations personnelles -->
          <div class="bg-gray-700/30 rounded-xl p-6">
            <h4 class="text-lg font-semibold text-white mb-4 flex items-center">
              <UserIcon class="h-5 w-5 text-blue-400 mr-2" />
              Informations Personnelles
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Prénom -->
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">
                  Prénom <span class="text-red-400">*</span>
                </label>
                <input
                  v-model="form.prenom"
                  type="text"
                  required
                  class="w-full px-4 py-3 bg-gray-600/50 border border-gray-500 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Prénom du membre"
                />
                <div v-if="errors.prenom" class="text-red-400 text-sm mt-1">{{ errors.prenom }}</div>
              </div>

              <!-- Nom -->
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">
                  Nom de famille <span class="text-red-400">*</span>
                </label>
                <input
                  v-model="form.nom"
                  type="text"
                  required
                  class="w-full px-4 py-3 bg-gray-600/50 border border-gray-500 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Nom de famille"
                />
                <div v-if="errors.nom" class="text-red-400 text-sm mt-1">{{ errors.nom }}</div>
              </div>

              <!-- Date de naissance -->
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">
                  Date de naissance <span class="text-red-400">*</span>
                </label>
                <input
                  v-model="form.date_naissance"
                  type="date"
                  required
                  class="w-full px-4 py-3 bg-gray-600/50 border border-gray-500 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                <div v-if="errors.date_naissance" class="text-red-400 text-sm mt-1">{{ errors.date_naissance }}</div>
              </div>

              <!-- Sexe -->
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">
                  Sexe <span class="text-red-400">*</span>
                </label>
                <select
                  v-model="form.sexe"
                  required
                  class="w-full px-4 py-3 bg-gray-600/50 border border-gray-500 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                  <option value="">Sélectionner...</option>
                  <option value="M">👨 Masculin</option>
                  <option value="F">👩 Féminin</option>
                  <option value="A">🏳️‍⚧️ Autre</option>
                </select>
                <div v-if="errors.sexe" class="text-red-400 text-sm mt-1">{{ errors.sexe }}</div>
              </div>
            </div>
          </div>

          <!-- Contact -->
          <div class="bg-gray-700/30 rounded-xl p-6">
            <h4 class="text-lg font-semibold text-white mb-4 flex items-center">
              <PhoneIcon class="h-5 w-5 text-green-400 mr-2" />
              Informations de Contact
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Email -->
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-300 mb-2">
                  Adresse email <span class="text-red-400">*</span>
                </label>
                <input
                  v-model="form.email"
                  type="email"
                  required
                  class="w-full px-4 py-3 bg-gray-600/50 border border-gray-500 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="email@exemple.com"
                />
                <div v-if="errors.email" class="text-red-400 text-sm mt-1">{{ errors.email }}</div>
              </div>

              <!-- Téléphone -->
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">
                  Téléphone <span class="text-red-400">*</span>
                </label>
                <input
                  v-model="form.telephone"
                  type="tel"
                  required
                  class="w-full px-4 py-3 bg-gray-600/50 border border-gray-500 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="(514) 123-4567"
                />
                <div v-if="errors.telephone" class="text-red-400 text-sm mt-1">{{ errors.telephone }}</div>
              </div>

              <!-- Téléphone d'urgence -->
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">
                  Contact d'urgence
                </label>
                <input
                  v-model="form.telephone_urgence"
                  type="tel"
                  class="w-full px-4 py-3 bg-gray-600/50 border border-gray-500 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="(514) 987-6543"
                />
              </div>
            </div>

            <!-- Adresse -->
            <div class="mt-4">
              <label class="block text-sm font-medium text-gray-300 mb-2">
                Adresse complète <span class="text-red-400">*</span>
              </label>
              <textarea
                v-model="form.adresse"
                required
                rows="3"
                class="w-full px-4 py-3 bg-gray-600/50 border border-gray-500 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="123 Rue Exemple, Ville, Province, Code Postal"
              ></textarea>
              <div v-if="errors.adresse" class="text-red-400 text-sm mt-1">{{ errors.adresse }}</div>
            </div>
          </div>

          <!-- Informations Karaté -->
          <div class="bg-gray-700/30 rounded-xl p-6">
            <h4 class="text-lg font-semibold text-white mb-4 flex items-center">
              <AcademicCapIcon class="h-5 w-5 text-yellow-400 mr-2" />
              Informations Karaté
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Ceinture actuelle -->
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">
                  Ceinture actuelle <span class="text-red-400">*</span>
                </label>
                <select
                  v-model="form.ceinture_actuelle_id"
                  required
                  class="w-full px-4 py-3 bg-gray-600/50 border border-gray-500 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                  <option value="">Sélectionner la ceinture...</option>
                  <option v-for="ceinture in ceintures" :key="ceinture.id" :value="ceinture.id">
                    🥋 {{ ceinture.nom }} ({{ ceinture.ordre }})
                  </option>
                </select>
                <div v-if="errors.ceinture_actuelle_id" class="text-red-400 text-sm mt-1">{{ errors.ceinture_actuelle_id }}</div>
              </div>

              <!-- Statut -->
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">
                  Statut initial
                </label>
                <select
                  v-model="form.statut"
                  class="w-full px-4 py-3 bg-gray-600/50 border border-gray-500 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                  <option value="actif">✅ Actif</option>
                  <option value="inactif">⏸️ Inactif</option>
                  <option value="suspendu">⛔ Suspendu</option>
                </select>
              </div>
            </div>

            <!-- Remarques -->
            <div class="mt-4">
              <label class="block text-sm font-medium text-gray-300 mb-2">
                Remarques administratives
              </label>
              <textarea
                v-model="form.remarques"
                rows="3"
                class="w-full px-4 py-3 bg-gray-600/50 border border-gray-500 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Notes, allergies, informations importantes..."
              ></textarea>
            </div>
          </div>

          <!-- Liens familiaux -->
          <div class="bg-gray-700/30 rounded-xl p-6">
            <h4 class="text-lg font-semibold text-white mb-4 flex items-center">
              <HomeIcon class="h-5 w-5 text-purple-400 mr-2" />
              Liens Familiaux (Optionnel)
            </h4>
            
            <div class="space-y-3">
              <div v-for="(lien, index) in form.liens_familiaux" :key="index" class="flex items-center space-x-3">
                <!-- Type de relation -->
                <select
                  v-model="lien.type_relation"
                  class="px-3 py-2 bg-gray-600/50 border border-gray-500 rounded-lg text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="">Type...</option>
                  <option value="parent">👨‍👩‍👧‍👦 Parent</option>
                  <option value="enfant">👶 Enfant</option>
                  <option value="frere_soeur">👫 Frère/Sœur</option>
                  <option value="conjoint">💑 Conjoint(e)</option>
                  <option value="grand_parent">👴 Grand-parent</option>
                  <option value="autre">🤝 Autre</option>
                </select>
                
                <!-- Membre lié -->
                <select
                  v-model="lien.membre_lie_id"
                  class="flex-1 px-3 py-2 bg-gray-600/50 border border-gray-500 rounded-lg text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="">Membre lié...</option>
                  <option v-for="membre in availableMembers" :key="membre.id" :value="membre.id">
                    {{ membre.prenom }} {{ membre.nom }}
                  </option>
                </select>
                
                <!-- Supprimer -->
                <button
                  type="button"
                  @click="removeFamilyLink(index)"
                  class="text-red-400 hover:text-red-300 p-2 rounded-lg hover:bg-red-500/10"
                >
                  <TrashIcon class="h-4 w-4" />
                </button>
              </div>
              
              <button
                type="button"
                @click="addFamilyLink"
                class="text-blue-400 hover:text-blue-300 text-sm flex items-center space-x-1"
              >
                <PlusIcon class="h-4 w-4" />
                <span>Ajouter un lien familial</span>
              </button>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex items-center justify-end space-x-4 pt-6">
            <button
              type="button"
              @click="$emit('close')"
              class="px-6 py-3 text-gray-300 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors duration-200"
            >
              Annuler
            </button>
            
            <button
              type="submit"
              :disabled="submitting"
              class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 flex items-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <UserPlusIcon v-if="!submitting" class="h-4 w-4" />
              <div v-else class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
              <span>{{ submitting ? 'Création...' : 'Créer le Membre' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import {
  UserPlusIcon, XMarkIcon, UserIcon, PhoneIcon, AcademicCapIcon,
  HomeIcon, TrashIcon, PlusIcon
} from '@heroicons/vue/24/outline'

// Props
const props = defineProps({
  ceintures: {
    type: Array,
    default: () => []
  },
  availableMembers: {
    type: Array,
    default: () => []
  }
})

// Emits
const emit = defineEmits(['close', 'created'])

// State
const submitting = ref(false)
const errors = ref({})

// Form data
const form = ref({
  prenom: '',
  nom: '',
  date_naissance: '',
  sexe: '',
  email: '',
  telephone: '',
  telephone_urgence: '',
  adresse: '',
  ceinture_actuelle_id: '',
  statut: 'actif',
  remarques: '',
  liens_familiaux: []
})

// Methods
const addFamilyLink = () => {
  form.value.liens_familiaux.push({
    type_relation: '',
    membre_lie_id: ''
  })
}

const removeFamilyLink = (index) => {
  form.value.liens_familiaux.splice(index, 1)
}

const submitForm = async () => {
  submitting.value = true
  errors.value = {}

  try {
    await router.post('/membres', form.value, {
      onSuccess: (response) => {
        emit('created', response.props.membre)
      },
      onError: (error) => {
        errors.value = error
      },
      onFinish: () => {
        submitting.value = false
      }
    })
  } catch (error) {
    console.error('Erreur lors de la création:', error)
    submitting.value = false
  }
}
</script>
