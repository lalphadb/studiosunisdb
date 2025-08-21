<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-blue-100 leading-tight">
          Modifier {{ cours.nom }}
        </h2>
        <Link
          :href="route('cours.show', cours.id)"
          class="bg-gradient-to-r from-blue-700 to-indigo-800 hover:from-blue-800 hover:to-indigo-900 text-white font-bold py-2 px-4 rounded shadow"
        >
          ← Voir le cours
        </Link>
      </div>
    </template>
    <div class="py-12 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 min-h-screen">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-blue-900/80 border border-blue-800 shadow-lg sm:rounded-xl">
          <div class="p-6">
            <form @submit.prevent="submit">
              <div class="mb-8">
                <h3 class="text-lg font-semibold text-blue-100 mb-4">Informations du cours</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-medium text-blue-200">Nom du cours *</label>
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
                    <label class="block text-sm font-medium text-blue-200">Niveau *</label>
                    <select
                      v-model="form.niveau"
                      required
                      class="mt-1 block w-full rounded-md border-blue-800 bg-blue-950/80 text-blue-100 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      :class="{ 'border-red-500': errors.niveau }"
                    >
                      <option value="">Sélectionner...</option>
                      <option value="tous">Tous</option>
                      <option value="debutant">Débutant</option>
                      <option value="intermediaire">Intermédiaire</option>
                      <option value="avance">Avancé</option>
                      <option value="prive">Privé</option>
                      <option value="combat">Combat</option>
                    </select>
                    <div v-if="errors.niveau" class="text-red-400 text-sm mt-1">
                      {{ errors.niveau }}
                    </div>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-blue-200">Type d'art martial *</label>
                    <input
                      v-model="form.type_art_martial"
                      type="text"
                      required
                      class="mt-1 block w-full rounded-md border-blue-800 bg-blue-950/80 text-blue-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 placeholder-blue-300"
                      :class="{ 'border-red-500': errors.type_art_martial }"
                    />
                    <div v-if="errors.type_art_martial" class="text-red-400 text-sm mt-1">
                      {{ errors.type_art_martial }}
                    </div>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-blue-200">Capacité maximale *</label>
                    <input
                      v-model="form.capacite_max"
                      type="number"
                      min="1"
                      required
                      class="mt-1 block w-full rounded-md border-blue-800 bg-blue-950/80 text-blue-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 placeholder-blue-300"
                      :class="{ 'border-red-500': errors.capacite_max }"
                    />
                    <div v-if="errors.capacite_max" class="text-red-400 text-sm mt-1">
                      {{ errors.capacite_max }}
                    </div>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-blue-200">Tarif mensuel *</label>
                    <input
                      v-model="form.tarif_mensuel"
                      type="number"
                      min="0"
                      step="0.01"
                      required
                      class="mt-1 block w-full rounded-md border-blue-800 bg-blue-950/80 text-blue-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 placeholder-blue-300"
                      :class="{ 'border-red-500': errors.tarif_mensuel }"
                    />
                    <div v-if="errors.tarif_mensuel" class="text-red-400 text-sm mt-1">
                      {{ errors.tarif_mensuel }}
                    </div>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-blue-200">Saison *</label>
                    <select
                      v-model="form.saison"
                      required
                      class="mt-1 block w-full rounded-md border-blue-800 bg-blue-950/80 text-blue-100 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      :class="{ 'border-red-500': errors.saison }"
                    >
                      <option value="">Sélectionner...</option>
                      <option value="automne">Automne</option>
                      <option value="hiver">Hiver</option>
                      <option value="printemps">Printemps</option>
                      <option value="ete">Été</option>
                    </select>
                    <div v-if="errors.saison" class="text-red-400 text-sm mt-1">
                      {{ errors.saison }}
                    </div>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-blue-200">Année *</label>
                    <input
                      v-model="form.annee"
                      type="number"
                      min="2020"
                      required
                      class="mt-1 block w-full rounded-md border-blue-800 bg-blue-950/80 text-blue-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 placeholder-blue-300"
                      :class="{ 'border-red-500': errors.annee }"
                    />
                    <div v-if="errors.annee" class="text-red-400 text-sm mt-1">
                      {{ errors.annee }}
                    </div>
                  </div>
                </div>
              </div>
              <div class="mb-8">
                <h3 class="text-lg font-semibold text-blue-100 mb-4">Description</h3>
                <textarea
                  v-model="form.description"
                  rows="3"
                  class="mt-1 block w-full rounded-md border-blue-800 bg-blue-950/80 text-blue-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 placeholder-blue-300"
                  placeholder="Description du cours..."
                ></textarea>
              </div>
              <div class="flex justify-end">
                <button
                  type="submit"
                  class="bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition-all duration-200"
                >
                  Sauvegarder
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
import { ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

defineProps({
  cours: {
    type: Object,
    required: true
  }
})

const form = useForm({
  nom: '',
  niveau: '',
  type_art_martial: '',
  capacite_max: '',
  tarif_mensuel: '',
  saison: '',
  annee: '',
  description: ''
})

const errors = ref({})

function submit() {
  form.put(route('cours.update', cours.id), {
    onError: (e) => { errors.value = e },
    onSuccess: () => { errors.value = {} }
  })
}
</script>
