<template>
  <Head title="Mes Cours" />
  
  <AuthenticatedLayout>
    <!-- Container principal -->
    <div class="min-h-screen">
      <!-- Header Member Dashboard -->
      <div class="relative overflow-hidden bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 shadow-2xl mb-6">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
        
        <!-- Pattern décoratif -->
        <div class="absolute inset-0">
          <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
          <div class="absolute -bottom-10 -left-10 w-60 h-60 bg-teal-500/20 rounded-full blur-3xl"></div>
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
                Mes Cours
              </h1>
              <p class="mt-1 text-teal-100">
                Planning personnel et prochaines séances
              </p>
            </div>
            
            <!-- Profil rapide -->
            <div class="text-right text-white">
              <p class="text-lg font-medium">{{ $page.props.auth.user.name }}</p>
              <p class="text-sm text-teal-100">{{ getUserRole() }}</p>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Stats personnelles -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 px-6">
        <!-- Cours inscrits -->
        <StatsCard
          title="Mes Cours"
          :value="stats.coursInscrits"
          icon="users"
          color="from-emerald-500 to-emerald-600"
        />
        
        <!-- Prochaine séance -->
        <StatsCard
          title="Prochaine Séance"
          :value="stats.prochaineSeance || 'Aucune'"
          icon="calendar"
          color="from-blue-500 to-blue-600"
        />
        
        <!-- Présences ce mois -->
        <StatsCard
          title="Présences"
          :value="stats.presencesMois + '%'"
          :change="stats.changementPresences"
          icon="chart"
          color="from-purple-500 to-purple-600"
          :showProgress="true"
          :progressValue="stats.presencesMois"
        />
        
        <!-- Prochaine ceinture -->
        <StatsCard
          title="Progression"
          :value="stats.prochaineCeinture || 'En cours'"
          icon="chart"
          color="from-amber-500 to-amber-600"
        />
      </div>
      
      <!-- Planning personnel -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 px-6">
        <!-- Mes cours -->
        <div class="lg:col-span-2">
          <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 overflow-hidden">
            <div class="p-6 border-b border-slate-700/50 bg-slate-900/30">
              <h3 class="text-xl font-bold text-white flex items-center gap-2">
                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                Mes Cours Inscrits
              </h3>
            </div>
            
            <div class="p-6">
              <div v-if="mesCoursInscrits.length === 0" class="text-center py-12 text-slate-400">
                <svg class="w-16 h-16 mx-auto mb-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <p>Aucun cours inscrit</p>
                <Link :href="route('cours.index')" 
                      class="mt-4 inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors">
                  Découvrir les cours
                </Link>
              </div>
              
              <div v-else class="space-y-4">
                <div v-for="cours in mesCoursInscrits" 
                     :key="cours.id"
                     class="bg-slate-900/50 rounded-xl p-4 border border-slate-700/30 hover:border-emerald-500/30 transition-all">
                  <div class="flex items-start justify-between">
                    <div class="flex-1">
                      <h4 class="text-lg font-semibold text-white">{{ cours.nom }}</h4>
                      <div class="mt-2 flex items-center gap-4 text-sm text-slate-400">
                        <div class="flex items-center gap-1">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                          </svg>
                          {{ cours.horaire_complet }}
                        </div>
                        <div class="flex items-center gap-1">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                          </svg>
                          {{ cours.instructeur_nom }}
                        </div>
                      </div>
                      
                      <div class="mt-3">
                        <span :class="getNiveauBadgeClass(cours.niveau)"
                              class="px-3 py-1 text-xs font-medium rounded-full border">
                          {{ cours.niveau }}
                        </span>
                      </div>
                    </div>
                    
                    <div class="text-right">
                      <button @click="voirCours(cours)"
                              class="px-4 py-2 bg-emerald-600/20 text-emerald-400 border border-emerald-600/30 hover:bg-emerald-600/30 rounded-lg transition-all text-sm">
                        Voir détails
                      </button>
                    </div>
                  </div>
                  
                  <!-- Prochaine séance -->
                  <div v-if="cours.prochaine_seance" 
                       class="mt-4 p-3 bg-slate-800/50 rounded-lg border border-slate-600/30">
                    <div class="flex items-center justify-between">
                      <div class="flex items-center gap-2 text-sm text-slate-300">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-medium">Prochaine séance :</span>
                        <span>{{ formatDate(cours.prochaine_seance) }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Cours disponibles -->
        <div>
          <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 overflow-hidden">
            <div class="p-6 border-b border-slate-700/50 bg-slate-900/30">
              <h3 class="text-xl font-bold text-white flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Cours Disponibles
              </h3>
            </div>
            
            <div class="p-6">
              <div class="space-y-3">
                <div v-for="cours in coursDisponibles.slice(0, 5)" 
                     :key="cours.id"
                     class="bg-slate-900/50 rounded-lg p-3 border border-slate-700/30">
                  <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                      <h5 class="font-medium text-white truncate">{{ cours.nom }}</h5>
                      <p class="text-xs text-slate-400 mt-1">{{ cours.horaire_complet }}</p>
                      <p class="text-xs text-slate-500">{{ cours.places_disponibles }} places</p>
                    </div>
                    <span :class="getNiveauBadgeClass(cours.niveau)"
                          class="ml-2 px-2 py-1 text-xs font-medium rounded-full border">
                      {{ cours.niveau }}
                    </span>
                  </div>
                </div>
                
                <div class="pt-4 border-t border-slate-700/50">
                  <Link :href="route('cours.index')" 
                        class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors text-center font-medium flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tous les cours
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import StatsCard from '@/Components/StatsCard.vue'

const props = defineProps({
  mesCoursInscrits: {
    type: Array,
    default: () => []
  },
  coursDisponibles: {
    type: Array,
    default: () => []
  },
  stats: {
    type: Object,
    default: () => ({
      coursInscrits: 0,
      prochaineSeance: null,
      presencesMois: 0,
      changementPresences: null,
      prochaineCeinture: null
    })
  }
})

const getUserRole = () => {
  const roles = {
    'superadmin': 'Super Administrateur',
    'admin_ecole': 'Administrateur École',
    'instructeur': 'Instructeur',
    'membre': 'Membre'
  }
  const userRoles = window.Laravel?.user?.roles || []
  const primaryRole = userRoles[0] || 'membre'
  return roles[primaryRole] || 'Membre'
}

const getNiveauBadgeClass = (niveau) => {
  const classes = {
    'debutant': 'bg-green-500/20 text-green-400 border-green-500/30',
    'intermediaire': 'bg-blue-500/20 text-blue-400 border-blue-500/30',
    'avance': 'bg-purple-500/20 text-purple-400 border-purple-500/30',
    'competition': 'bg-red-500/20 text-red-400 border-red-500/30'
  }
  return classes[niveau] || 'bg-slate-500/20 text-slate-400 border-slate-500/30'
}

const formatDate = (date) => {
  if (!date) return ''
  return new Intl.DateTimeFormat('fr-FR', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(new Date(date))
}

const voirCours = (cours) => {
  router.get(route('cours.show', cours.id))
}
</script>
