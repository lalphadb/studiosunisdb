<template>
  <AuthenticatedLayout>
    <Head title="Sessions Multiples" />
    
    <div class="min-h-screen max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <PageHeader title="Sessions Multiples" :description="`Créer des sessions supplémentaires pour : ${cours.nom}`">
        <template #actions>
          <Link :href="`/cours/${cours.id}`"
                class="px-4 py-2 rounded-lg bg-slate-700/50 hover:bg-slate-600/50 text-white text-sm font-medium border border-slate-600">
            ← Retour au cours
          </Link>
        </template>
      </PageHeader>
      
      <!-- Informations cours actuel -->
      <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6 mb-6">
        <h3 class="text-lg font-semibold text-white mb-4">Cours de référence</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <div class="text-sm text-slate-400">Cours</div>
            <div class="text-white font-medium">{{ cours.nom }}</div>
          </div>
          <div>
            <div class="text-sm text-slate-400">Horaire actuel</div>
            <div class="text-white font-medium capitalize">{{ cours.jour_semaine }} {{ formatHeure(cours.heure_debut) }}-{{ formatHeure(cours.heure_fin) }}</div>
          </div>
          <div>
            <div class="text-sm text-slate-400">Instructeur</div>
            <div class="text-white font-medium">{{ cours.instructeur?.name || 'Non assigné' }}</div>
          </div>
        </div>
      </div>
      
      <!-- Formulaire création sessions -->
      <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6 mb-6">
        <h3 class="text-lg font-semibold text-white mb-4">Créer des sessions supplémentaires</h3>
        
        <form @submit.prevent="submit">
          <div class="space-y-6">
            <!-- Jours de la semaine -->
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-3">Jours de la semaine *</label>
              <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <div v-for="(label, value) in joursDisponibles" :key="value"
                     class="flex items-center">
                  <input
                    :id="`jour-${value}`"
                    v-model="form.jours_semaine"
                    type="checkbox"
                    :value="value"
                    class="w-4 h-4 text-blue-600 bg-slate-900 border-slate-700 rounded focus:ring-blue-500 focus:ring-2"
                  />
                  <label :for="`jour-${value}`" class="ml-2 text-sm text-slate-300">{{ label }}</label>
                </div>
              </div>
              <div v-if="errors.jours_semaine" class="text-red-400 text-sm mt-1">{{ errors.jours_semaine }}</div>
            </div>
            
            <!-- Horaires -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Heure de début *</label>
                <input
                  v-model="form.heure_debut"
                  type="time"
                  required
                  class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  :class="{ 'border-red-500': errors.heure_debut }"
                />
                <div v-if="errors.heure_debut" class="text-red-400 text-sm mt-1">{{ errors.heure_debut }}</div>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Heure de fin *</label>
                <input
                  v-model="form.heure_fin"
                  type="time"
                  required
                  class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  :class="{ 'border-red-500': errors.heure_fin }"
                />
                <div v-if="errors.heure_fin" class="text-red-400 text-sm mt-1">{{ errors.heure_fin }}</div>
              </div>
            </div>
            
            <!-- Périodes -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Date de début *</label>
                <input
                  v-model="form.date_debut"
                  type="date"
                  required
                  class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  :class="{ 'border-red-500': errors.date_debut }"
                />
                <div v-if="errors.date_debut" class="text-red-400 text-sm mt-1">{{ errors.date_debut }}</div>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Date de fin</label>
                <input
                  v-model="form.date_fin"
                  type="date"
                  class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  :class="{ 'border-red-500': errors.date_fin }"
                />
                <div v-if="errors.date_fin" class="text-red-400 text-sm mt-1">{{ errors.date_fin }}</div>
              </div>
            </div>
            
            <!-- Options avancées -->
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-3">Options</label>
              <div class="space-y-3">
                <div class="flex items-center">
                  <input
                    id="dupliquer-inscriptions"
                    v-model="form.dupliquer_inscriptions"
                    type="checkbox"
                    class="w-4 h-4 text-blue-600 bg-slate-900 border-slate-700 rounded focus:ring-blue-500 focus:ring-2"
                  />
                  <label for="dupliquer-inscriptions" class="ml-2 text-sm text-slate-300">
                    Dupliquer les inscriptions existantes
                  </label>
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-400 mb-2">Fréquence</label>
                  <select
                    v-model="form.frequence"
                    class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  >
                    <option value="hebdomadaire">Hebdomadaire</option>
                    <option value="bihebdomadaire">Bihebdomadaire</option>
                  </select>
                </div>
              </div>
            </div>
            
            <!-- Actions -->
            <div class="flex justify-end gap-3">
              <Link :href="`/cours/${cours.id}`"
                    class="px-5 py-2.5 bg-slate-800/50 hover:bg-slate-700/50 text-white rounded-xl border border-slate-700 transition-all">
                Annuler
              </Link>
              <button
                type="submit"
                :disabled="form.processing"
                class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-medium shadow-lg transition-all disabled:opacity-50"
              >
                <span v-if="form.processing">Création...</span>
                <span v-else>Créer les sessions</span>
              </button>
            </div>
          </div>
        </form>
      </div>
      
      <!-- Aide -->
      <div class="bg-gradient-to-br from-amber-500/10 to-orange-500/10 backdrop-blur-sm rounded-2xl border border-amber-500/20 p-4">
        <div class="flex items-start gap-3">
          <svg class="w-5 h-5 text-amber-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div class="text-sm text-amber-200">
            <p class="font-medium mb-1">Information</p>
            <p>Chaque jour sélectionné créera une nouvelle session du cours avec les mêmes paramètres. Les conflits d'horaire seront automatiquement ignorés.</p>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'

const props = defineProps({
  cours: {
    type: Object,
    required: true
  },
  joursDisponibles: {
    type: Object,
    required: true
  }
})

const form = useForm({
  jours_semaine: [],
  heure_debut: props.cours.heure_debut || '09:00',
  heure_fin: props.cours.heure_fin || '10:00',
  date_debut: props.cours.date_debut || '',
  date_fin: props.cours.date_fin || '',
  frequence: 'hebdomadaire',
  dupliquer_inscriptions: false
})

const errors = ref({})

const formatHeure = (heure) => {
  return heure?.substring(0, 5) || ''
}

const submit = () => {
  form.post(`/cours/${props.cours.id}/sessions`, {
    onError: (e) => { errors.value = e },
    onSuccess: () => { errors.value = {} }
  })
}
</script>
