<template>
  <Head title="Gestion des Cours" />
  
  <AuthenticatedLayout>
    <!-- Container principal SANS padding -->
    <div class="min-h-screen">
      <!-- Header premium style Dashboard pleine largeur -->
      <div class="relative overflow-hidden bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 shadow-2xl mb-6">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
        
        <!-- Pattern décoratif -->
        <div class="absolute inset-0">
          <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
          <div class="absolute -bottom-10 -left-10 w-60 h-60 bg-indigo-500/20 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Contenu du header -->
        <div class="relative px-6 py-8">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                  <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                  </svg>
                </div>
                Gestion des Cours
              </h1>
              <p class="mt-1 text-indigo-100">
                Planning et gestion des séances de karaté
              </p>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center gap-3">
              <button @click="showCalendarView = !showCalendarView"
                      class="px-5 py-3 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white rounded-xl flex items-center gap-2 transition-all font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Vue Calendrier
              </button>
              <Link :href="route('cours.create')"
                    class="px-6 py-3 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white rounded-xl flex items-center gap-2 transition-all font-medium shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouveau Cours
              </Link>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Stats Cards avec padding horizontal -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 px-6">
        <!-- Total Cours -->
        <div class="relative overflow-hidden bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl p-6 border border-slate-700/50 hover:border-indigo-500/50 transition-all duration-300 group">
          <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
          <div class="relative">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-slate-400 text-sm font-medium">Total Cours</p>
                <p class="text-3xl font-bold text-white mt-1">{{ stats.totalCours }}</p>
                <p class="text-xs text-slate-500 mt-1">Tous niveaux confondus</p>
              </div>
              <div class="w-12 h-12 bg-gradient-to-br from-indigo-500/20 to-indigo-600/20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Cours Actifs -->
        <div class="relative overflow-hidden bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl p-6 border border-slate-700/50 hover:border-green-500/50 transition-all duration-300 group">
          <div class="absolute inset-0 bg-gradient-to-br from-green-600/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
          <div class="relative">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-slate-400 text-sm font-medium">Cours Actifs</p>
                <p class="text-3xl font-bold text-white mt-1">{{ stats.coursActifs }}</p>
                <p class="text-xs text-green-400 mt-1">En cours cette semaine</p>
              </div>
              <div class="w-12 h-12 bg-gradient-to-br from-green-500/20 to-green-600/20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Instructeurs -->
        <div class="relative overflow-hidden bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl p-6 border border-slate-700/50 hover:border-purple-500/50 transition-all duration-300 group">
          <div class="absolute inset-0 bg-gradient-to-br from-purple-600/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
          <div class="relative">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-slate-400 text-sm font-medium">Instructeurs</p>
                <p class="text-3xl font-bold text-white mt-1">{{ stats.totalInstructeurs }}</p>
                <p class="text-xs text-purple-400 mt-1">Actifs cette saison</p>
              </div>
              <div class="w-12 h-12 bg-gradient-to-br from-purple-500/20 to-purple-600/20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Planning -->
        <div class="relative overflow-hidden bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl p-6 border border-slate-700/50 hover:border-amber-500/50 transition-all duration-300 group">
          <div class="absolute inset-0 bg-gradient-to-br from-amber-600/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
          <div class="relative">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-slate-400 text-sm font-medium">Séances/Semaine</p>
                <p class="text-3xl font-bold text-white mt-1">{{ stats.seancesParSemaine }}</p>
                <p class="text-xs text-amber-400 mt-1">Planning hebdomadaire</p>
              </div>
              <div class="w-12 h-12 bg-gradient-to-br from-amber-500/20 to-amber-600/20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Section principale avec margin horizontal -->
      <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 overflow-hidden mx-6 mb-6">
        <!-- Barre d'outils -->
        <div class="p-6 border-b border-slate-700/50 bg-slate-900/30">
          <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <!-- Filtres -->
            <div class="flex flex-wrap items-center gap-3">
              <!-- Filtre Saison -->
              <div class="relative">
                <select v-model="filters.saison" 
                        class="appearance-none bg-slate-900/50 text-white border border-slate-700 rounded-xl pl-10 pr-8 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                  <option value="">Toutes les saisons</option>
                  <option value="automne">Automne 2024</option>
                  <option value="hiver">Hiver 2025</option>
                  <option value="printemps">Printemps 2025</option>
                  <option value="ete">Été 2025</option>
                </select>
                <svg class="absolute left-3 top-3 w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
              
              <!-- Filtre Niveau -->
              <div class="relative">
                <select v-model="filters.niveau"
                        class="appearance-none bg-slate-900/50 text-white border border-slate-700 rounded-xl pl-10 pr-8 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                  <option value="">Tous les niveaux</option>
                  <option value="debutant">Débutant</option>
                  <option value="intermediaire">Intermédiaire</option>
                  <option value="avance">Avancé</option>
                  <option value="competition">Compétition</option>
                </select>
                <svg class="absolute left-3 top-3 w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                </svg>
              </div>
              
              <!-- Recherche -->
              <div class="relative flex-1 min-w-[300px]">
                <input type="text" 
                       v-model="searchTerm"
                       placeholder="Rechercher un cours..."
                       class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent placeholder-slate-500">
                <svg class="absolute left-3 top-3 w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center gap-3">
              <button @click="exportCours"
                      class="px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl flex items-center gap-2 transition-all font-medium shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Exporter
              </button>
            </div>
          </div>
        </div>
        
        <!-- Table des cours -->
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="bg-slate-900/50 border-b border-slate-700/50">
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                  Cours
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                  Instructeur
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                  Horaire
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                  Niveau
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                  Inscrits
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                  Statut
                </th>
                <th class="px-6 py-4 text-right text-xs font-semibold text-slate-400 uppercase tracking-wider">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/30">
              <tr v-for="cours in filteredCours" 
                  :key="cours.id"
                  class="hover:bg-slate-800/30 transition-all duration-200 group">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-indigo-500/20 to-purple-600/20 rounded-xl flex items-center justify-center">
                      <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                      </svg>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-white">
                        {{ cours.nom }}
                      </div>
                      <div class="text-xs text-slate-400">
                        {{ cours.age_min }}-{{ cours.age_max }} ans
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-slate-300">{{ cours.instructeur?.name }}</div>
                  <div class="text-xs text-slate-500">{{ cours.instructeur?.email }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-slate-300">{{ cours.jour_semaine }}</div>
                  <div class="text-xs text-slate-500">{{ cours.heure_debut }} - {{ cours.heure_fin }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="getNiveauBadgeClass(cours.niveau)"
                        class="px-3 py-1 inline-flex text-xs font-medium rounded-full border">
                    {{ cours.niveau }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="text-sm text-slate-300">
                      {{ cours.inscrits_count }}/{{ cours.places_max }}
                    </div>
                    <div class="ml-3 w-16 bg-slate-700 rounded-full h-2">
                      <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2 rounded-full transition-all" 
                           :style="`width: ${Math.min((cours.inscrits_count / cours.places_max) * 100, 100)}%`"></div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span v-if="cours.actif" 
                        class="px-3 py-1 inline-flex text-xs font-medium rounded-full bg-green-500/20 text-green-400 border border-green-500/30">
                    Actif
                  </span>
                  <span v-else 
                        class="px-3 py-1 inline-flex text-xs font-medium rounded-full bg-red-500/20 text-red-400 border border-red-500/30">
                    Inactif
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button @click="viewCours(cours)"
                            class="p-2 text-blue-400 hover:text-blue-300 hover:bg-blue-500/10 rounded-lg transition-all"
                            title="Voir">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    </button>
                    <button @click="editCours(cours)"
                            class="p-2 text-amber-400 hover:text-amber-300 hover:bg-amber-500/10 rounded-lg transition-all"
                            title="Modifier">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </button>
                    <button @click="duplicateCours(cours)"
                            class="p-2 text-purple-400 hover:text-purple-300 hover:bg-purple-500/10 rounded-lg transition-all"
                            title="Dupliquer">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                      </svg>
                    </button>
                    <button @click="deleteCours(cours)"
                            class="p-2 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition-all"
                            title="Supprimer">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-slate-700/50 bg-slate-900/30">
          <Pagination :links="cours.links || []" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps({
  cours: {
    type: [Array, Object],
    default: () => ({ data: [], links: [] })
  },
  instructeurs: {
    type: Array,
    default: () => []
  },
  stats: {
    type: Object,
    default: () => ({
      totalCours: 0,
      coursActifs: 0,
      totalInstructeurs: 0,
      seancesParSemaine: 0
    })
  }
})

const searchTerm = ref('')
const showCalendarView = ref(false)
const filters = ref({
  saison: '',
  niveau: ''
})

const coursData = computed(() => {
  if (Array.isArray(props.cours)) {
    return props.cours
  }
  return props.cours.data || []
})

const filteredCours = computed(() => {
  let result = [...coursData.value]
  
  if (searchTerm.value) {
    const search = searchTerm.value.toLowerCase()
    result = result.filter(c => 
      c.nom?.toLowerCase().includes(search) ||
      c.instructeur?.name?.toLowerCase().includes(search)
    )
  }
  
  if (filters.value.niveau) {
    result = result.filter(c => c.niveau === filters.value.niveau)
  }
  
  return result
})

const getNiveauBadgeClass = (niveau) => {
  const classes = {
    'debutant': 'bg-green-500/20 text-green-400 border-green-500/30',
    'intermediaire': 'bg-blue-500/20 text-blue-400 border-blue-500/30',
    'avance': 'bg-purple-500/20 text-purple-400 border-purple-500/30',
    'competition': 'bg-red-500/20 text-red-400 border-red-500/30'
  }
  return classes[niveau] || 'bg-slate-500/20 text-slate-400 border-slate-500/30'
}

const viewCours = (cours) => {
  router.get(route('cours.show', cours.id))
}

const editCours = (cours) => {
  router.get(route('cours.edit', cours.id))
}

const duplicateCours = (cours) => {
  if (confirm(`Voulez-vous dupliquer le cours "${cours.nom}" ?`)) {
    router.post(route('cours.duplicate', cours.id))
  }
}

const deleteCours = (cours) => {
  if (confirm(`Êtes-vous sûr de vouloir supprimer le cours "${cours.nom}" ?`)) {
    router.delete(route('cours.destroy', cours.id))
  }
}

const exportCours = () => {
  window.open(route('cours.export'))
}
</script>
