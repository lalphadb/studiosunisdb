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
            <LinkIcon class="h-6 w-6 text-purple-400 mr-3" />
            Liens Familiaux - {{ member.prenom }} {{ member.nom }}
          </h3>
          <button
            @click="$emit('close')"
            class="text-gray-400 hover:text-white transition-colors p-2 rounded-lg hover:bg-gray-700"
          >
            <XMarkIcon class="h-6 w-6" />
          </button>
        </div>

        <!-- Liens existants -->
        <div class="mb-6">
          <h4 class="text-lg font-semibold text-white mb-4 flex items-center">
            <UsersIcon class="h-5 w-5 text-blue-400 mr-2" />
            Liens Familiaux Existants
          </h4>

          <div v-if="existingLinks.length === 0" class="text-gray-400 text-center py-8 bg-gray-700/30 rounded-xl">
            <HomeIcon class="h-12 w-12 mx-auto mb-4 text-gray-500" />
            <p>Aucun lien familial défini pour ce membre</p>
          </div>

          <div v-else class="space-y-3">
            <div
              v-for="link in existingLinks"
              :key="link.id"
              class="flex items-center justify-between p-4 bg-gray-700/30 rounded-xl hover:bg-gray-700/50 transition-colors"
            >
              <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-full bg-purple-500/20 flex items-center justify-center">
                  <span class="text-lg">{{ getRelationIcon(link.type_relation) }}</span>
                </div>

                <div>
                  <div class="text-white font-medium">
                    {{ link.membre_lie.prenom }} {{ link.membre_lie.nom }}
                  </div>
                  <div class="text-sm text-gray-400">
                    {{ getRelationLabel(link.type_relation) }}
                  </div>
                  <div class="text-xs text-gray-500">
                    {{ link.membre_lie.email }}
                  </div>
                </div>
              </div>

              <div class="flex items-center space-x-2">
                <button
                  @click="viewLinkedMember(link.membre_lie)"
                  class="text-blue-400 hover:text-blue-300 p-2 rounded-lg hover:bg-blue-500/10 transition-colors"
                  title="Voir le profil"
                >
                  <EyeIcon class="h-4 w-4" />
                </button>

                <button
                  @click="editLink(link)"
                  class="text-yellow-400 hover:text-yellow-300 p-2 rounded-lg hover:bg-yellow-500/10 transition-colors"
                  title="Modifier le lien"
                >
                  <PencilIcon class="h-4 w-4" />
                </button>

                <button
                  @click="deleteLink(link)"
                  class="text-red-400 hover:text-red-300 p-2 rounded-lg hover:bg-red-500/10 transition-colors"
                  title="Supprimer le lien"
                >
                  <TrashIcon class="h-4 w-4" />
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Ajouter nouveau lien -->
        <div class="bg-gray-700/30 rounded-xl p-6">
          <h4 class="text-lg font-semibold text-white mb-4 flex items-center">
            <PlusIcon class="h-5 w-5 text-green-400 mr-2" />
            Ajouter un Nouveau Lien
          </h4>

          <form @submit.prevent="addNewLink" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Type de relation -->
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">
                  Type de relation <span class="text-red-400">*</span>
                </label>
                <select
                  v-model="newLink.type_relation"
                  required
                  class="w-full px-4 py-3 bg-gray-600/50 border border-gray-500 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="">Sélectionner le type...</option>
                  <option value="parent">👨‍👩‍👧‍👦 Parent</option>
                  <option value="enfant">👶 Enfant</option>
                  <option value="frere_soeur">👫 Frère/Sœur</option>
                  <option value="conjoint">💑 Conjoint(e)</option>
                  <option value="grand_parent">👴 Grand-parent</option>
                  <option value="petit_enfant">👶 Petit-enfant</option>
                  <option value="oncle_tante">👨‍👩‍👧 Oncle/Tante</option>
                  <option value="neveu_niece">👦 Neveu/Nièce</option>
                  <option value="cousin">🤝 Cousin(e)</option>
                  <option value="autre">🔗 Autre</option>
                </select>
                <div v-if="errors.type_relation" class="text-red-400 text-sm mt-1">{{ errors.type_relation }}</div>
              </div>

              <!-- Membre à lier -->
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">
                  Membre à lier <span class="text-red-400">*</span>
                </label>
                <div class="relative">
                  <select
                    v-model="newLink.membre_lie_id"
                    required
                    class="w-full px-4 py-3 bg-gray-600/50 border border-gray-500 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                  >
                    <option value="">Sélectionner un membre...</option>
                    <option
                      v-for="membre in availableMembers"
                      :key="membre.id"
                      :value="membre.id"
                    >
                      {{ membre.prenom }} {{ membre.nom }} - {{ membre.email }}
                    </option>
                  </select>
                </div>
                <div v-if="errors.membre_lie_id" class="text-red-400 text-sm mt-1">{{ errors.membre_lie_id }}</div>
              </div>
            </div>

            <!-- Description optionnelle -->
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">
                Description (optionnelle)
              </label>
              <input
                v-model="newLink.description"
                type="text"
                class="w-full px-4 py-3 bg-gray-600/50 border border-gray-500 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Ex: Père biologique, Belle-mère, etc."
              />
            </div>

            <!-- Bidirectionnel -->
            <div class="flex items-center space-x-3">
              <input
                v-model="newLink.bidirectionnel"
                type="checkbox"
                id="bidirectionnel"
                class="w-4 h-4 text-blue-600 bg-gray-600 border-gray-500 rounded focus:ring-blue-500"
              />
              <label for="bidirectionnel" class="text-sm text-gray-300">
                Créer automatiquement le lien inverse
                <span class="text-xs text-gray-500 block">
                  (Ex: Si vous ajoutez "Parent", cela créera automatiquement "Enfant" pour l'autre membre)
                </span>
              </label>
            </div>

            <!-- Boutons -->
            <div class="flex items-center justify-end space-x-3 pt-4">
              <button
                type="button"
                @click="resetNewLink"
                class="px-4 py-2 text-gray-300 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors"
              >
                Annuler
              </button>

              <button
                type="submit"
                :disabled="submitting"
                class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center space-x-2 disabled:opacity-50"
              >
                <PlusIcon v-if="!submitting" class="h-4 w-4" />
                <div v-else class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                <span>{{ submitting ? 'Ajout...' : 'Ajouter le Lien' }}</span>
              </button>
            </div>
          </form>
        </div>

        <!-- Suggestions intelligentes -->
        <div v-if="suggestions.length > 0" class="mt-6 bg-blue-500/10 border border-blue-500/30 rounded-xl p-4">
          <h5 class="text-sm font-medium text-blue-300 mb-3 flex items-center">
            <LightBulbIcon class="h-4 w-4 mr-2" />
            Suggestions basées sur les liens existants
          </h5>

          <div class="space-y-2">
            <div
              v-for="suggestion in suggestions"
              :key="suggestion.id"
              class="flex items-center justify-between text-sm"
            >
              <span class="text-blue-200">
                {{ suggestion.message }}
              </span>
              <button
                @click="applySuggestion(suggestion)"
                class="text-blue-400 hover:text-blue-300 font-medium"
              >
                Appliquer
              </button>
            </div>
          </div>
        </div>

        <!-- Actions globales -->
        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-700">
          <button
            @click="$emit('close')"
            class="px-6 py-3 text-gray-300 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors"
          >
            Fermer
          </button>

          <button
            @click="exportFamilyTree"
            class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors flex items-center space-x-2"
          >
            <DocumentArrowDownIcon class="h-4 w-4" />
            <span>Exporter Arbre Familial</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import {
  LinkIcon, XMarkIcon, UsersIcon, HomeIcon, PlusIcon, EyeIcon,
  PencilIcon, TrashIcon, LightBulbIcon, DocumentArrowDownIcon
} from '@heroicons/vue/24/outline'

// Props
const props = defineProps({
  member: Object,
  availableMembers: Array
})

// Emits
const emit = defineEmits(['close', 'updated'])

// State
const submitting = ref(false)
const errors = ref({})
const existingLinks = ref([])
const suggestions = ref([])

// Form pour nouveau lien
const newLink = ref({
  type_relation: '',
  membre_lie_id: '',
  description: '',
  bidirectionnel: true
})

// Computed
const filteredAvailableMembers = computed(() => {
  return props.availableMembers?.filter(m =>
    m.id !== props.member.id &&
    !existingLinks.value.some(link => link.membre_lie_id === m.id)
  ) || []
})

// Methods
const loadExistingLinks = async () => {
  try {
    const response = await fetch(`/membres/${props.member.id}/liens-familiaux`)
    const data = await response.json()
    existingLinks.value = data.liens || []
    suggestions.value = data.suggestions || []
  } catch (error) {
    console.error('Erreur lors du chargement des liens:', error)
  }
}

const getRelationIcon = (type) => {
  const icons = {
    'parent': '👨‍👩‍👧‍👦',
    'enfant': '👶',
    'frere_soeur': '👫',
    'conjoint': '💑',
    'grand_parent': '👴',
    'petit_enfant': '👶',
    'oncle_tante': '👨‍👩‍👧',
    'neveu_niece': '👦',
    'cousin': '🤝',
    'autre': '🔗'
  }
  return icons[type] || '🔗'
}

const getRelationLabel = (type) => {
  const labels = {
    'parent': 'Parent',
    'enfant': 'Enfant',
    'frere_soeur': 'Frère/Sœur',
    'conjoint': 'Conjoint(e)',
    'grand_parent': 'Grand-parent',
    'petit_enfant': 'Petit-enfant',
    'oncle_tante': 'Oncle/Tante',
    'neveu_niece': 'Neveu/Nièce',
    'cousin': 'Cousin(e)',
    'autre': 'Autre'
  }
  return labels[type] || type
}

const addNewLink = async () => {
  submitting.value = true
  errors.value = {}

  try {
    await router.post(`/membres/${props.member.id}/liens-familiaux`, newLink.value, {
      onSuccess: () => {
        resetNewLink()
        loadExistingLinks()
        emit('updated')
      },
      onError: (error) => {
        errors.value = error
      },
      onFinish: () => {
        submitting.value = false
      }
    })
  } catch (error) {
    console.error('Erreur lors de l\'ajout du lien:', error)
    submitting.value = false
  }
}

const editLink = (link) => {
  newLink.value = {
    id: link.id,
    type_relation: link.type_relation,
    membre_lie_id: link.membre_lie_id,
    description: link.description || '',
    bidirectionnel: false
  }
}

const deleteLink = async (link) => {
  if (confirm(`Êtes-vous sûr de vouloir supprimer ce lien familial ?`)) {
    try {
      await router.delete(`/liens-familiaux/${link.id}`, {
        onSuccess: () => {
          loadExistingLinks()
          emit('updated')
        }
      })
    } catch (error) {
      console.error('Erreur lors de la suppression:', error)
    }
  }
}

const viewLinkedMember = (member) => {
  router.visit(`/membres/${member.id}`)
}

const resetNewLink = () => {
  newLink.value = {
    type_relation: '',
    membre_lie_id: '',
    description: '',
    bidirectionnel: true
  }
  errors.value = {}
}

const applySuggestion = (suggestion) => {
  newLink.value.type_relation = suggestion.type_relation
  newLink.value.membre_lie_id = suggestion.membre_id
  newLink.value.description = suggestion.description
}

const exportFamilyTree = () => {
  window.open(`/membres/${props.member.id}/arbre-familial/export`, '_blank')
}

// Lifecycle
onMounted(() => {
  loadExistingLinks()
})
</script>
