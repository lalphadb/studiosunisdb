<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
      <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6 rounded-t-2xl">
        <div class="flex justify-between items-center">
          <h3 class="text-2xl font-bold text-white flex items-center">
            <PencilIcon class="w-8 h-8 mr-3" />
            Modifier le Cours
          </h3>
          <button @click="$emit('close')" 
                  class="text-white hover:text-gray-200 transition-colors">
            <XMarkIcon class="w-6 h-6" />
          </button>
        </div>
      </div>

      <form @submit.prevent="submitForm" class="p-8 space-y-8">
        <!-- Informations générales -->
        <div class="bg-blue-50 rounded-xl p-6">
          <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <BookOpenIcon class="w-5 h-5 mr-2 text-blue-600" />
            Informations Générales
          </h4>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                📚 Nom du cours *
              </label>
              <input v-model="form.nom" 
                     type="text" 
                     required
                     class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
              <span v-if="errors.nom" class="text-red-500 text-sm">{{ errors.nom }}</span>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                🌟 Niveau *
              </label>
              <select v-model="form.niveau" 
                      required
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Choisir le niveau</option>
                <option value="debutant">🌱 Débutant</option>
                <option value="intermediaire">⚡ Intermédiaire</option>
                <option value="avance">🔥 Avancé</option>
                <option value="expert">👑 Expert</option>
                <option value="mixte">🎯 Mixte</option>
              </select>
              <span v-if="errors.niveau" class="text-red-500 text-sm">{{ errors.niveau }}</span>
            </div>

            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                📝 Description
              </label>
              <textarea v-model="form.description" 
                        rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Description détaillée du cours..."></textarea>
              <span v-if="errors.description" class="text-red-500 text-sm">{{ errors.description }}</span>
            </div>
          </div>
        </div>

        <!-- Saison et contraintes -->
        <div class="bg-purple-50 rounded-xl p-6">
          <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <CalendarIcon class="w-5 h-5 mr-2 text-purple-600" />
            Saison et Contraintes
          </h4>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                🌍 Saison *
              </label>
              <select v-model="form.saison" 
                      required
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                <option value="">Choisir la saison</option>
                <option value="automne">🍂 Automne</option>
                <option value="hiver">❄️ Hiver</option>
                <option value="printemps">🌸 Printemps</option>
                <option value="ete">☀️ Été</option>
              </select>
              <span v-if="errors.saison" class="text-red-500 text-sm">{{ errors.saison }}</span>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                👶 Âge minimum
              </label>
              <input v-model.number="form.age_minimum" 
                     type="number" 
                     min="3" 
                     max="100"
                     class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
              <span v-if="errors.age_minimum" class="text-red-500 text-sm">{{ errors.age_minimum }}</span>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                👥 Capacité maximale *
              </label>
              <input v-model.number="form.capacite_max" 
                     type="number" 
                     min="1" 
                     max="50" 
                     required
                     class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
              <span v-if="errors.capacite_max" class="text-red-500 text-sm">{{ errors.capacite_max }}</span>
            </div>
          </div>
        </div>

        <!-- Tarification -->
        <div class="bg-green-50 rounded-xl p-6">
          <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <CurrencyDollarIcon class="w-5 h-5 mr-2 text-green-600" />
            Tarification (CAD)
          </h4>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                💰 Tarif mensuel *
              </label>
              <div class="relative">
                <input v-model.number="form.tarif_mensuel" 
                       type="number" 
                       step="0.01" 
                       min="0" 
                       required
                       class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                <span class="absolute right-3 top-3 text-gray-500">$</span>
              </div>
              <span v-if="errors.tarif_mensuel" class="text-red-500 text-sm">{{ errors.tarif_mensuel }}</span>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                🎫 Tarif par séance
              </label>
              <div class="relative">
                <input v-model.number="form.tarif_seance" 
                       type="number" 
                       step="0.01" 
                       min="0"
                       class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                <span class="absolute right-3 top-3 text-gray-500">$</span>
              </div>
              <span v-if="errors.tarif_seance" class="text-red-500 text-sm">{{ errors.tarif_seance }}</span>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                🎟️ Carte 10 cours
              </label>
              <div class="relative">
                <input v-model.number="form.tarif_carte" 
                       type="number" 
                       step="0.01" 
                       min="0"
                       class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                <span class="absolute right-3 top-3 text-gray-500">$</span>
              </div>
              <span v-if="errors.tarif_carte" class="text-red-500 text-sm">{{ errors.tarif_carte }}</span>
            </div>
          </div>
        </div>

        <!-- Statut et visibilité -->
        <div class="bg-yellow-50 rounded-xl p-6">
          <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <EyeIcon class="w-5 h-5 mr-2 text-yellow-600" />
            Statut et Visibilité
          </h4>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                📊 Statut du cours
              </label>
              <select v-model="form.statut" 
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                <option value="actif">✅ Actif</option>
                <option value="inactif">⏸️ Inactif</option>
                <option value="complet">🔒 Complet</option>
                <option value="annule">❌ Annulé</option>
              </select>
            </div>

            <div class="flex items-center mt-8">
              <label class="flex items-center">
                <input v-model="form.visible_inscription" 
                       type="checkbox" 
                       class="rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
                <span class="ml-2 text-sm text-gray-700">👁️ Visible pour les inscriptions</span>
              </label>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-between pt-6 border-t">
          <button type="button" 
                  @click="deleteCours" 
                  class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
            <TrashIcon class="w-5 h-5 mr-2" />
            Supprimer le cours
          </button>
          
          <div class="space-x-4">
            <button type="button" 
                    @click="$emit('close')" 
                    class="px-6 py-3 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
              Annuler
            </button>
            <button type="submit" 
                    :disabled="isSubmitting"
                    class="px-8 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 transform hover:scale-105 shadow-lg disabled:opacity-50">
              <span v-if="isSubmitting">⏳ Modification...</span>
              <span v-else">💾 Modifier le Cours</span>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import {
  PencilIcon,
  XMarkIcon,
  BookOpenIcon,
  CalendarIcon,
  CurrencyDollarIcon,
  EyeIcon,
  TrashIcon
} from '@heroicons/vue/24/outline'

// Props
const props = defineProps({
  cours: Object
})

// Events
const emit = defineEmits(['close', 'updated'])

// État
const isSubmitting = ref(false)
const errors = ref({})

const form = reactive({
  nom: '',
  description: '',
  niveau: '',
  saison: '',
  age_minimum: null,
  capacite_max: null,
  tarif_mensuel: null,
  tarif_seance: null,
  tarif_carte: null,
  statut: 'actif',
  visible_inscription: true
})

// Méthodes
const initializeForm = () => {
  if (props.cours) {
    Object.assign(form, {
      nom: props.cours.nom || '',
      description: props.cours.description || '',
      niveau: props.cours.niveau || '',
      saison: props.cours.saison || '',
      age_minimum: props.cours.age_minimum,
      capacite_max: props.cours.capacite_max,
      tarif_mensuel: props.cours.tarif_mensuel,
      tarif_seance: props.cours.tarif_seance,
      tarif_carte: props.cours.tarif_carte,
      statut: props.cours.statut || 'actif',
      visible_inscription: props.cours.visible_inscription !== false
    })
  }
}

const submitForm = () => {
  isSubmitting.value = true
  errors.value = {}

  router.put(`/cours/${props.cours.id}`, form, {
    onSuccess: (page) => {
      emit('updated', page.props.cours)
    },
    onError: (pageErrors) => {
      errors.value = pageErrors
    },
    onFinish: () => {
      isSubmitting.value = false
    }
  })
}

const deleteCours = () => {
  if (confirm(`Êtes-vous sûr de vouloir supprimer définitivement le cours "${props.cours.nom}" ?\n\nCette action supprimera également:\n- Tous les horaires\n- Toutes les inscriptions\n- Toutes les sessions\n\nCette action est irréversible.`)) {
    router.delete(`/cours/${props.cours.id}`, {
      onSuccess: () => {
        emit('close')
        // Redirection sera gérée par le contrôleur
      }
    })
  }
}

// Lifecycle
onMounted(() => {
  initializeForm()
})
</script>
