<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-blue-100 leading-tight">
          {{ cours.nom }}
        </h2>
        <Link
          :href="route('cours.index')"
          class="bg-gradient-to-r from-blue-700 to-indigo-800 hover:from-blue-800 hover:to-indigo-900 text-white font-bold py-2 px-4 rounded shadow"
        >
          ← Retour à la liste
        </Link>
      </div>
    </template>
    <div class="py-12 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 min-h-screen">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-blue-900/80 border border-blue-800 shadow-lg sm:rounded-xl">
          <div class="p-6 text-blue-100">
            <div class="mb-8">
              <h3 class="text-lg font-semibold text-blue-100 mb-4">Informations du cours</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <div class="font-medium text-blue-200">Niveau :</div>
                  <div>{{ cours.niveau }}</div>
                </div>
                <div>
                  <div class="font-medium text-blue-200">Type d'art martial :</div>
                  <div>{{ cours.type_art_martial }}</div>
                </div>
                <div>
                  <div class="font-medium text-blue-200">Capacité maximale :</div>
                  <div>{{ cours.capacite_max }}</div>
                </div>
                <div>
                  <div class="font-medium text-blue-200">Tarif mensuel :</div>
                  <div>{{ cours.tarif_mensuel }} $</div>
                </div>
                <div>
                  <div class="font-medium text-blue-200">Saison :</div>
                  <div>{{ cours.saison }}</div>
                </div>
                <div>
                  <div class="font-medium text-blue-200">Année :</div>
                  <div>{{ cours.annee }}</div>
                </div>
              </div>
            </div>
            <div class="mb-8">
              <h3 class="text-lg font-semibold text-blue-100 mb-4">Description</h3>
              <div class="text-blue-200">{{ cours.description }}</div>
            </div>
            <div class="flex justify-end">
              <Link
                :href="route('cours.edit', cours.id)"
                class="bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold py-2 px-6 rounded-lg shadow-lg transition-all duration-200"
              >
                Modifier
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

defineProps({
  cours: {
    type: Object,
    required: true
  }
})
</script>
