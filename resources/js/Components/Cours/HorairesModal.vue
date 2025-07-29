<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-2xl shadow-2xl max-w-5xl w-full max-h-[90vh] overflow-y-auto">
      <div class="bg-gradient-to-r from-purple-500 to-indigo-600 p-6 rounded-t-2xl">
        <div class="flex justify-between items-center">
          <h3 class="text-2xl font-bold text-white flex items-center">
            <CalendarIcon class="w-8 h-8 mr-3" />
            Gestion des Horaires - {{ cours.nom }}
          </h3>
          <button @click="$emit('close')"
                  class="text-white hover:text-gray-200 transition-colors">
            <XMarkIcon class="w-6 h-6" />
          </button>
        </div>
      </div>

      <div class="p-8">
        <!-- Horaires actuels -->
        <div class="mb-8">
          <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <ClockIcon class="w-5 h-5 mr-2 text-purple-600" />
            Horaires Actuels
          </h4>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div v-for="horaire in horaires" :key="horaire.id"
                 class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl p-4 border border-purple-200">
              <div class="flex justify-between items-start mb-3">
                <div>
                  <div class="text-lg font-bold text-purple-800 flex items-center">
                    {{ getJourEmoji(horaire.jour) }} {{ formatJour(horaire.jour) }}
                  </div>
                  <div class="text-sm text-purple-600">
                    {{ formatHeure(horaire.heure_debut) }} - {{ formatHeure(horaire.heure_fin) }}
                  </div>
                </div>
                <div class="flex space-x-1">
                  <button @click="editHoraire(horaire)"
                          class="text-blue-600 hover:text-blue-800 p-1">
                    <PencilIcon class="w-4 h-4" />
                  </button>
                  <button @click="deleteHoraire(horaire)"
                          class="text-red-600 hover:text-red-800 p-1">
                    <TrashIcon class="w-4 h-4" />
                  </button>
                </div>
              </div>

              <div v-if="horaire.instructeur" class="text-sm text-gray-600 mb-2">
                👨‍🏫 {{ horaire.instructeur.nom }} {{ horaire.instructeur.prenom }}
              </div>

              <div class="text-xs text-purple-500">
                📍 {{ horaire.salle || 'Salle non définie' }}
              </div>
            </div>
          </div>
        </div>

        <!-- Planificateur de saison -->
        <div class="mb-8 bg-yellow-50 rounded-xl p-6">
          <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <CalendarDaysIcon class="w-5 h-5 mr-2 text-yellow-600" />
            Planificateur de Saison {{ getSaisonEmoji(cours.saison) }}
          </h4>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                📅 Date de début de saison
              </label>
              <input v-model="saisonPlanning.date_debut"
                     type="date"
                     class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                🏁 Date de fin de saison
              </label>
              <input v-model="saisonPlanning.date_fin"
                     type="date"
                     class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
            </div>
          </div>

          <div class="mt-4 flex items-center space-x-4">
            <label class="flex items-center">
              <input v-model="saisonPlanning.exclure_conges"
                     type="checkbox"
                     class="rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
              <span class="ml-2 text-sm text-gray-700">🏖️ Exclure les congés scolaires</span>
            </label>

            <button @click="genererSessionsSaison"
                    class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
              ⚡ Générer les Sessions
            </button>
          </div>
        </div>

        <!-- Formulaire nouvel horaire -->
        <div class="bg-green-50 rounded-xl p-6 mb-8">
          <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <PlusIcon class="w-5 h-5 mr-2 text-green-600" />
            {{ editingHoraire ? 'Modifier l\'horaire' : 'Ajouter un horaire' }}
          </h4>

          <form @submit.prevent="saveHoraire" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  📅 Jour de la semaine
                </label>
                <select v-model="horaireForm.jour"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                  <option value="">Choisir un jour</option>
                  <option value="lundi">🌅 Lundi</option>
                  <option value="mardi">🌞 Mardi</option>
                  <option value="mercredi">⭐ Mercredi</option>
                  <option value="jeudi">🌙 Jeudi</option>
                  <option value="vendredi">✨ Vendredi</option>
                  <option value="samedi">🌈 Samedi</option>
                  <option value="dimanche">🌸 Dimanche</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  ⏰ Heure de début
                </label>
                <input v-model="horaireForm.heure_debut"
                       type="time"
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  ⏰ Heure de fin
                </label>
                <input v-model="horaireForm.heure_fin"
                       type="time"
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  📍 Salle
                </label>
                <input v-model="horaireForm.salle"
                       type="text"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                       placeholder="Ex: Dojo A">
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                👨‍🏫 Instructeur (optionnel)
              </label>
              <select v-model="horaireForm.instructeur_id"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                <option value="">Aucun instructeur assigné</option>
                <option v-for="instructeur in instructeurs"
                        :key="instructeur.id"
                        :value="instructeur.id">
                  {{ instructeur.nom }} {{ instructeur.prenom }}
                </option>
              </select>
            </div>

            <div class="flex justify-end space-x-4">
              <button v-if="editingHoraire"
                      type="button"
                      @click="cancelEdit"
                      class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                Annuler
              </button>
              <button type="submit"
                      class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all">
                {{ editingHoraire ? '💾 Modifier' : '➕ Ajouter' }}
              </button>
            </div>
          </form>
        </div>

        <!-- Calendrier de la semaine -->
        <div class="bg-blue-50 rounded-xl p-6">
          <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <CalendarIcon class="w-5 h-5 mr-2 text-blue-600" />
            Vue d'ensemble de la semaine
          </h4>

          <div class="grid grid-cols-7 gap-2 text-center">
            <div v-for="jour in joursWeek" :key="jour.key"
                 class="bg-white rounded-lg p-4 border">
              <div class="font-bold text-sm text-gray-800 mb-2">
                {{ jour.emoji }} {{ jour.nom }}
              </div>
              <div v-for="horaire in getHorairesJour(jour.key)"
                   :key="horaire.id"
                   class="text-xs bg-purple-100 text-purple-800 rounded p-1 mb-1">
                {{ formatHeure(horaire.heure_debut) }}-{{ formatHeure(horaire.heure_fin) }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Actions finales -->
      <div class="flex justify-end space-x-4 p-6 border-t bg-gray-50 rounded-b-2xl">
        <button @click="$emit('close')"
                class="px-6 py-3 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">
          Fermer
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import {
  CalendarIcon,
  ClockIcon,
  XMarkIcon,
  PlusIcon,
  PencilIcon,
  TrashIcon,
  CalendarDaysIcon
} from '@heroicons/vue/24/outline'

// Props
const props = defineProps({
  cours: Object
})

// Events
const emit = defineEmits(['close', 'updated'])

// État
const horaires = ref([])
const instructeurs = ref([])
const editingHoraire = ref(null)

const horaireForm = reactive({
  jour: '',
  heure_debut: '',
  heure_fin: '',
  salle: '',
  instructeur_id: null
})

const saisonPlanning = reactive({
  date_debut: '',
  date_fin: '',
  exclure_conges: true
})

const joursWeek = [
  { key: 'lundi', nom: 'Lundi', emoji: '🌅' },
  { key: 'mardi', nom: 'Mardi', emoji: '🌞' },
  { key: 'mercredi', nom: 'Mercredi', emoji: '⭐' },
  { key: 'jeudi', nom: 'Jeudi', emoji: '🌙' },
  { key: 'vendredi', nom: 'Vendredi', emoji: '✨' },
  { key: 'samedi', nom: 'Samedi', emoji: '🌈' },
  { key: 'dimanche', nom: 'Dimanche', emoji: '🌸' }
]

// Computed
const getHorairesJour = computed(() => {
  return (jour) => horaires.value.filter(h => h.jour === jour)
})

// Méthodes
const loadHoraires = async () => {
  try {
    const response = await fetch(`/api/cours/${props.cours.id}/horaires`)
    horaires.value = await response.json()
  } catch (error) {
    console.error('Erreur chargement horaires:', error)
  }
}

const loadInstructeurs = async () => {
  try {
    const response = await fetch('/api/instructeurs')
    instructeurs.value = await response.json()
  } catch (error) {
    console.error('Erreur chargement instructeurs:', error)
  }
}

const saveHoraire = () => {
  const data = { ...horaireForm, cours_id: props.cours.id }

  if (editingHoraire.value) {
    router.put(`/cours/${props.cours.id}/horaires/${editingHoraire.value.id}`, data, {
      onSuccess: () => {
        loadHoraires()
        resetForm()
      }
    })
  } else {
    router.post(`/cours/${props.cours.id}/horaires`, data, {
      onSuccess: () => {
        loadHoraires()
        resetForm()
      }
    })
  }
}

const editHoraire = (horaire) => {
  editingHoraire.value = horaire
  Object.assign(horaireForm, {
    jour: horaire.jour,
    heure_debut: horaire.heure_debut,
    heure_fin: horaire.heure_fin,
    salle: horaire.salle || '',
    instructeur_id: horaire.instructeur_id
  })
}

const deleteHoraire = (horaire) => {
  if (confirm('Supprimer cet horaire ?')) {
    router.delete(`/cours/${props.cours.id}/horaires/${horaire.id}`, {
      onSuccess: () => loadHoraires()
    })
  }
}

const cancelEdit = () => {
  editingHoraire.value = null
  resetForm()
}

const resetForm = () => {
  Object.assign(horaireForm, {
    jour: '',
    heure_debut: '',
    heure_fin: '',
    salle: '',
    instructeur_id: null
  })
}

const genererSessionsSaison = () => {
  router.post(`/cours/${props.cours.id}/generer-sessions`, saisonPlanning, {
    onSuccess: () => {
      alert('Sessions générées avec succès!')
      emit('updated')
    }
  })
}

// Utilitaires
const getJourEmoji = (jour) => {
  const emojis = {
    'lundi': '🌅',
    'mardi': '🌞',
    'mercredi': '⭐',
    'jeudi': '🌙',
    'vendredi': '✨',
    'samedi': '🌈',
    'dimanche': '🌸'
  }
  return emojis[jour] || '📅'
}

const formatJour = (jour) => {
  return jour.charAt(0).toUpperCase() + jour.slice(1)
}

const formatHeure = (heure) => {
  return heure ? heure.slice(0, 5) : ''
}

const getSaisonEmoji = (saison) => {
  const emojis = {
    'automne': '🍂',
    'hiver': '❄️',
    'printemps': '🌸',
    'ete': '☀️'
  }
  return emojis[saison] || '🌍'
}

// Lifecycle
onMounted(() => {
  loadHoraires()
  loadInstructeurs()
})
</script>
