<template>
  <AuthenticatedLayout>
    <Head :title="cours.nom" />
    
    <div class="min-h-screen max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <PageHeader :title="cours.nom" :description="`Détails du cours - ${cours.niveau} - ${cours.age_min}-${cours.age_max} ans`">
        <template #actions>
          <Link href="/cours"
                class="px-4 py-2 rounded-lg bg-slate-700/50 hover:bg-slate-600/50 text-white text-sm font-medium border border-slate-600">
            ← Retour à la liste
          </Link>
          <Link :href="`/cours/${cours.id}/edit`"
                class="px-4 py-2 rounded-lg bg-gradient-to-r from-amber-500 to-orange-600 text-white text-sm font-medium hover:from-amber-400 hover:to-orange-500">
            Modifier
          </Link>
        </template>
      </PageHeader>
      
      <!-- Stats du cours -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <StatCard title="Inscrits" :value="stats.totalInscrits" tone="blue" :description="`${stats.placesDisponibles} places libres`" />
        <StatCard title="Taux remplissage" :value="stats.tauxRemplissage" format="percentage" tone="green" description="Capacité utilisée" />
        <StatCard title="Présences moyennes" :value="stats.presencesMoyenne" format="percentage" tone="purple" description="Participation" />
        <StatCard title="Sessions liées" :value="sessionsLiees.length" tone="amber" description="Même cours" />
      </div>
      
      <!-- Actions rapides améliorées -->
      <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6 mb-6">
        <h3 class="text-lg font-semibold text-white mb-4">Actions</h3>
        <div class="flex flex-wrap gap-3">
          <Link :href="`/cours/${cours.id}/sessions`"
                class="px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white rounded-lg flex items-center gap-2 transition-all text-sm font-medium shadow-lg">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Sessions multiples
          </Link>
          
          <!-- Duplication avec formulaire (recommandé) -->
          <Link :href="`/cours/${cours.id}/duplicate-form`"
                class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white rounded-lg flex items-center gap-2 transition-all text-sm font-medium shadow-lg">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
            Dupliquer (avec formulaire)
          </Link>
          
          <!-- Duplication rapide -->
          <button @click="duplicateCours"
                  class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white rounded-lg flex items-center gap-2 transition-all text-sm font-medium shadow-lg">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Dupliquer (rapide)
          </button>
          
          <Link href="#"
                class="px-4 py-2 bg-gradient-to-r from-cyan-600 to-teal-600 hover:from-cyan-700 hover:to-teal-700 text-white rounded-lg flex items-center gap-2 transition-all text-sm font-medium shadow-lg">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
            </svg>
            Gérer inscriptions
          </Link>
        </div>
      </div>
      
      <!-- Informations principales -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Détails cours -->
        <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6">
          <h3 class="text-lg font-semibold text-white mb-4">Informations</h3>
          <div class="space-y-3">
            <div class="flex justify-between items-center">
              <span class="text-slate-400">Niveau</span>
              <span :class="getNiveauBadgeClass(cours.niveau)" class="px-3 py-1 text-xs font-medium rounded-full">
                {{ cours.niveau }}
              </span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-400">Age</span>
              <span class="text-white">{{ cours.age_min }}-{{ cours.age_max }} ans</span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-400">Capacité</span>
              <span class="text-white">{{ cours.places_max }} places</span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-400">Tarif</span>
              <span class="text-white font-medium">
                {{ cours.montant || cours.tarif_mensuel }}$ 
                <span class="text-xs text-slate-500">({{ cours.type_tarif || 'mensuel' }})</span>
              </span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-400">Période</span>
              <span class="text-white">{{ formatDate(cours.date_debut) }} - {{ formatDate(cours.date_fin) || 'En cours' }}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-slate-400">Statut</span>
              <span v-if="cours.actif" class="px-3 py-1 text-xs font-medium rounded-full bg-green-500/20 text-green-400 border border-green-500/30">
                Actif
              </span>
              <span v-else class="px-3 py-1 text-xs font-medium rounded-full bg-red-500/20 text-red-400 border border-red-500/30">
                Inactif
              </span>
            </div>
          </div>
        </div>
        
        <!-- Horaires et instructeur -->
        <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6">
          <h3 class="text-lg font-semibold text-white mb-4">Horaires</h3>
          <div class="space-y-4">
            <div class="flex items-center gap-3">
              <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
              <div>
                <div class="text-white font-medium capitalize">{{ cours.jour_semaine }}</div>
                <div class="text-slate-400 text-sm">{{ formatHeure(cours.heure_debut) }} - {{ formatHeure(cours.heure_fin) }}</div>
              </div>
            </div>
            
            <div v-if="cours.instructeur" class="flex items-center gap-3">
              <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center text-white font-semibold text-sm">
                {{ cours.instructeur.name[0] }}
              </div>
              <div>
                <div class="text-white font-medium">{{ cours.instructeur.name }}</div>
                <div class="text-slate-400 text-sm">{{ cours.instructeur.email }}</div>
              </div>
            </div>
            <div v-else class="flex items-center gap-3">
              <div class="w-12 h-12 bg-slate-700 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
              </div>
              <div>
                <div class="text-slate-400">Aucun instructeur assigné</div>
                <div class="text-slate-500 text-sm">Cours en attente</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Description -->
      <div v-if="cours.description" class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6 mb-6">
        <h3 class="text-lg font-semibold text-white mb-4">Description</h3>
        <p class="text-slate-300">{{ cours.description }}</p>
      </div>
      
      <!-- Sessions liées -->
      <div v-if="sessionsLiees.length > 0" class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6 mb-6">
        <h3 class="text-lg font-semibold text-white mb-4">Sessions liées ({{ sessionsLiees.length }})</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div v-for="session in sessionsLiees" :key="session.id"
               class="bg-slate-900/50 rounded-xl border border-slate-700/50 p-4 hover:bg-slate-800/50 transition-all">
            <div class="flex items-center justify-between mb-2">
              <div class="text-white font-medium capitalize">{{ session.jour_semaine }}</div>
              <span v-if="session.actif" class="w-2 h-2 bg-green-400 rounded-full"></span>
              <span v-else class="w-2 h-2 bg-red-400 rounded-full"></span>
            </div>
            <div class="text-sm text-slate-400">{{ formatHeure(session.heure_debut) }} - {{ formatHeure(session.heure_fin) }}</div>
            <div class="text-xs text-slate-500 mt-1">{{ session.inscrits_count || 0 }}/{{ session.places_max }} inscrits</div>
            <div class="flex gap-1 mt-3">
              <Link :href="`/cours/${session.id}`" class="p-1 text-blue-400 hover:text-blue-300 hover:bg-blue-500/10 rounded transition-all text-xs">
                Voir
              </Link>
              <Link :href="`/cours/${session.id}/edit`" class="p-1 text-amber-400 hover:text-amber-300 hover:bg-amber-500/10 rounded transition-all text-xs">
                Éditer
              </Link>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Historique présences (si données disponibles) -->
      <div v-if="presencesHistory && presencesHistory.length > 0" class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Historique des présences (4 dernières semaines)</h3>
        <div class="grid grid-cols-4 gap-4">
          <div v-for="semaine in presencesHistory" :key="semaine.semaine" class="text-center">
            <div class="text-2xl font-bold text-white">{{ semaine.presences }}</div>
            <div class="text-xs text-slate-400">{{ semaine.semaine }}</div>
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
import PageHeader from '@/Components/UI/PageHeader.vue'
import StatCard from '@/Components/UI/StatCard.vue'

const props = defineProps({
  cours: {
    type: Object,
    required: true
  },
  stats: {
    type: Object,
    default: () => ({
      totalInscrits: 0,
      placesDisponibles: 0,
      tauxRemplissage: 0,
      presencesMoyenne: 0
    })
  },
  presencesHistory: {
    type: Array,
    default: () => []
  }
})

// Simuler des sessions liées (basé sur le même nom de base)
const sessionsLiees = computed(() => {
  // Pour la démo, on simule des sessions liées
  // En réalité, celles-ci viendraient du backend
  return []
})

const getNiveauBadgeClass = (niveau) => {
  const classes = {
    'debutant': 'bg-green-500/20 text-green-400 border border-green-500/30',
    'intermediaire': 'bg-blue-500/20 text-blue-400 border border-blue-500/30',
    'avance': 'bg-purple-500/20 text-purple-400 border border-purple-500/30',
    'competition': 'bg-red-500/20 text-red-400 border border-red-500/30'
  }
  return classes[niveau] || 'bg-slate-500/20 text-slate-400 border border-slate-500/30'
}

const formatDate = (date) => {
  if (!date) return null
  return new Date(date).toLocaleDateString('fr-CA')
}

const formatHeure = (heure) => {
  return heure?.substring(0, 5) || ''
}

const duplicateCours = () => {
  if (confirm(`Voulez-vous dupliquer rapidement le cours "${props.cours.nom}" ?`)) {
    router.post(`/cours/${props.cours.id}/duplicate`)
  }
}
</script>
