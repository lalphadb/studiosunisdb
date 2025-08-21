<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-blue-100 leading-tight">
          Planning des Cours
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
      <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-blue-900/80 border border-blue-800 shadow-lg sm:rounded-xl">
          <div class="p-6 text-blue-100">
            <h3 class="text-lg font-semibold text-blue-100 mb-4">Calendrier des séances</h3>
            <!-- Ici, insérer un composant calendrier ou une grille des sessions -->
            <div class="bg-blue-950/60 rounded-xl p-8 text-center text-blue-300">
              <span>Le calendrier des séances sera affiché ici.</span>
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
</script>
