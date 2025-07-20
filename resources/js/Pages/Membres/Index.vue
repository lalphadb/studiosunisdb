<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Gestion des Membres
        </h2>
        <Link
          :href="route('membres.create')"
          class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        >
          + Nouveau Membre
        </Link>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Filtres -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Recherche</label>
              <input
                v-model="filters.search"
                type="text"
                placeholder="Nom ou prénom..."
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                @input="debounceSearch"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Statut</label>
              <select
                v-model="filters.statut"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                @change="applyFilters"
              >
                <option value="">Tous les statuts</option>
                <option value="actif">Actif</option>
                <option value="inactif">Inactif</option>
                <option value="suspendu">Suspendu</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Ceinture</label>
              <select
                v-model="filters.ceinture"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                @change="applyFilters"
              >
                <option value="">Toutes les ceintures</option>
                <option
                  v-for="ceinture in ceintures"
                  :key="ceinture.id"
                  :value="ceinture.id"
                >
                  {{ ceinture.nom }}
                </option>
              </select>
            </div>
            <div class="flex items-end">
              <button
                @click="exportMembres"
                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
              >
                📊 Export Excel
              </button>
            </div>
          </div>
        </div>

        <!-- Liste des membres -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Membre
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Âge
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Ceinture
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Statut
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Dernière présence
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="membre in membres.data" :key="membre.id" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                          <span class="text-sm font-medium text-gray-700">
                            {{ membre.prenom[0] }}{{ membre.nom[0] }}
                          </span>
                        </div>
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">
                          {{ membre.prenom }} {{ membre.nom }}
                        </div>
                        <div class="text-sm text-gray-500">
                          {{ membre.telephone }}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ calculerAge(membre.date_naissance) }} ans
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                      :style="`background-color: ${membre.ceinture_actuelle?.couleur_hex}20; color: ${membre.ceinture_actuelle?.couleur_hex}`"
                    >
                      {{ membre.ceinture_actuelle?.nom }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span
                      :class="{
                        'bg-green-100 text-green-800': membre.statut === 'actif',
                        'bg-yellow-100 text-yellow-800': membre.statut === 'inactif',
                        'bg-red-100 text-red-800': membre.statut === 'suspendu'
                      }"
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    >
                      {{ membre.statut }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ formatDate(membre.date_derniere_presence) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2">
                      <Link
                        :href="route('membres.show', membre.id)"
                        class="text-blue-600 hover:text-blue-900"
                      >
                        Voir
                      </Link>
                      <Link
                        :href="route('membres.edit', membre.id)"
                        class="text-indigo-600 hover:text-indigo-900"
                      >
                        Modifier
                      </Link>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex justify-between items-center">
              <div class="text-sm text-gray-700">
                Affichage de {{ membres.from }} à {{ membres.to }} sur {{ membres.total }} membres
              </div>
              <div class="flex space-x-1">
                <Link
                  v-for="link in membres.links"
                  :key="link.label"
                  :href="link.url"
                  v-html="link.label"
                  :class="{
                    'bg-blue-500 text-white': link.active,
                    'bg-gray-200 text-gray-700': !link.active && link.url,
                    'bg-gray-100 text-gray-400': !link.url
                  }"
                  class="px-3 py-2 text-sm rounded"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  membres: Object,
  ceintures: Array,
  filters: Object
})

const filters = reactive({
  search: props.filters.search || '',
  statut: props.filters.statut || '',
  ceinture: props.filters.ceinture || ''
})

let searchTimeout = null

const debounceSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(applyFilters, 300)
}

const applyFilters = () => {
  router.get(route('membres.index'), filters, {
    preserveState: true,
    replace: true
  })
}

const calculerAge = (dateNaissance) => {
  return Math.floor((new Date() - new Date(dateNaissance)) / (365.25 * 24 * 60 * 60 * 1000))
}

const formatDate = (date) => {
  if (!date) return 'Jamais'
  return new Date(date).toLocaleDateString('fr-CA')
}

const exportMembres = () => {
  window.open(route('membres.export'), '_blank')
}
</script>
