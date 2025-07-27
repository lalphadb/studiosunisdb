<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
      <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-6 rounded-t-2xl">
        <div class="flex justify-between items-center">
          <h3 class="text-2xl font-bold text-white flex items-center">
            <PlusIcon class="w-8 h-8 mr-3" />
            Créer un Nouveau Cours
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
                     class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                     placeholder="Ex: Karaté Débutant Enfants">
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
                     class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                     placeholder="Ex: 6">
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
                     class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                     placeholder="Ex: 15">
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
                       class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                       placeholder="120.00">
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
                       class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                       placeholder="15.00">
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
                       class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                       placeholder="130.00">
                <span class="absolute right-3 top-3 text-gray-500">$</span>
              </div>
              <span v-if="errors.tarif_carte" class="text-red-500 text-sm">{{ errors.tarif_carte }}</span>
            </div>
          </div>
        </div>

        <!-- Horaires de base -->
        <div class="bg-yellow-50 rounded-xl p-6">
          <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <ClockIcon class="w-5 h-5 mr-2 text-yellow-600" />
            Horaires de Base
          </h4>
          
          <div class="space-y-4">
            <div v-for="(horaire, index) in form.horaires" :key="index" 
                 class="flex items-center space-x-4 p-4 bg-white rounded-lg border">
              <select v-model="horaire.jour" 
                      class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                <option value="">Jour</option>
                <option value="lundi">🌅 Lundi</option>
                <option value="mardi">🌞 Mardi</option>
                <option value="mercredi">⭐ Mercredi</option>
                <option value="jeudi">🌙 Jeudi</option>
                <option value="vendredi">✨ Vendredi</option>
                <option value="samedi">🌈 Samedi</option>
                <option value="dimanche">🌸 Dimanche</option>
              </select>

              <input v-model="horaire.heure_debut" 
                     type="time" 
                     class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500"
                     placeholder="Début">

              <input v-model="horaire.heure_fin" 
                     type="time" 
                     class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500"
                     placeholder="Fin">

              <button type="button" 
                      @click="removeHoraire(index)"
                      class="text-red-500 hover:text-red-700 p-2">
                <TrashIcon class="w-4 h-4" />
              </button>
            </div>

            <button type="button" 
                    @click="addHoraire" 
                    class="flex items-center px-4 py-2 text-yellow-600 border border-yellow-300 rounded-lg hover:bg-yellow-100 transition-colors">
              <PlusIcon class="w-4 h-4 mr-2" />
              Ajouter un horaire
            </button>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-4 pt-6 border-t">
          <button type="button" 
                  @click="$emit('close')" 
                  class="px-6 py-3 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
            Annuler
          </button>
          <button type="submit" 
                  :disabled="isSubmitting"
                  class="px-8 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all duration-200 transform hover:scale-105 shadow-lg disabled:opacity-50">
            <span v-if="isSubmitting">⏳ Création...</span>
            <span v-else>✅ Créer le Cours</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import {
  PlusIcon,
  XMarkIcon,
  BookOpenIcon,
  CalendarIcon,
  CurrencyDollarIcon,
  ClockIcon,
  TrashIcon
} from '@heroicons/vue/24/outline'

// Events
const emit = defineEmits(['close', 'created'])

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
  horaires: [
    { jour: '', heure_debut: '', heure_fin: '' }
  ]
})

// Méthodes
const addHoraire = () => {
  form.horaires.push({ jour: '', heure_debut: '', heure_fin: '' })
}

const removeHoraire = (index) => {
  if (form.horaires.length > 1) {
    form.horaires.splice(index, 1)
  }
}

const submitForm = () => {
  isSubmitting.value = true
  errors.value = {}

  // Filtrer les horaires vides
  const horairesFiltres = form.horaires.filter(h => h.jour && h.heure_debut && h.heure_fin)

  const dataToSubmit = {
    ...form,
    horaires: horairesFiltres
  }

  router.post('/cours', dataToSubmit, {
    onSuccess: (page) => {
      emit('created', page.props.cours)
    },
    onError: (pageErrors) => {
      errors.value = pageErrors
    },
    onFinish: () => {
      isSubmitting.value = false
    }
  })
}
</script>
