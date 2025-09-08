<template>
  <AuthenticatedLayout>
    <Head :title="`Utilisateur - ${user?.name || 'Détails'}`" />
    
    <div class="min-h-screen max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <!-- Header -->
      <div class="mb-6">
        <div class="flex items-center justify-between">
          <h1 class="text-xl sm:text-2xl xl:text-3xl font-bold text-white">
            Détails de l'utilisateur
          </h1>
          <div class="flex gap-3">
            <Link
              href="/users"
              class="px-4 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-white text-sm font-medium transition-all flex items-center gap-2"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
              Retour
            </Link>
            <Link
              :href="`/users/${user.id}/edit`"
              v-if="can?.edit"
              class="px-4 py-2 rounded-lg bg-gradient-to-r from-amber-500 to-amber-600 text-white text-sm font-medium hover:from-amber-400 hover:to-amber-500 shadow-lg transition-all flex items-center gap-2"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              Modifier
            </Link>
          </div>
        </div>
      </div>

      <!-- Contenu principal -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Carte Info Personnelles -->
          <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Informations personnelles</h2>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <dt class="text-sm font-medium text-slate-400">Nom complet</dt>
                <dd class="mt-1 text-sm text-white">{{ user.name || '-' }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-slate-400">Email</dt>
                <dd class="mt-1 text-sm text-white">{{ user.email || '-' }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-slate-400">Téléphone</dt>
                <dd class="mt-1 text-sm text-white">{{ user.telephone || '-' }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-slate-400">Date de naissance</dt>
                <dd class="mt-1 text-sm text-white">{{ formatDate(user.date_naissance) }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-slate-400">Sexe</dt>
                <dd class="mt-1 text-sm text-white">{{ user.sexe || '-' }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-slate-400">Date d'inscription</dt>
                <dd class="mt-1 text-sm text-white">{{ formatDate(user.created_at) }}</dd>
              </div>
            </dl>
          </div>

          <!-- Carte Adresse -->
          <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Adresse</h2>
            <dl class="space-y-3">
              <div>
                <dt class="text-sm font-medium text-slate-400">Adresse</dt>
                <dd class="mt-1 text-sm text-white">{{ user.adresse || '-' }}</dd>
              </div>
              <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div>
                  <dt class="text-sm font-medium text-slate-400">Ville</dt>
                  <dd class="mt-1 text-sm text-white">{{ user.ville || '-' }}</dd>
                </div>
                <div>
                  <dt class="text-sm font-medium text-slate-400">Code postal</dt>
                  <dd class="mt-1 text-sm text-white">{{ user.code_postal || '-' }}</dd>
                </div>
                <div>
                  <dt class="text-sm font-medium text-slate-400">Province</dt>
                  <dd class="mt-1 text-sm text-white">{{ user.province || 'QC' }}</dd>
                </div>
              </div>
            </dl>
          </div>

          <!-- Contact d'urgence -->
          <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Contact d'urgence</h2>
            <dl class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <dt class="text-sm font-medium text-slate-400">Nom</dt>
                <dd class="mt-1 text-sm text-white">{{ user.contact_urgence_nom || '-' }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-slate-400">Téléphone</dt>
                <dd class="mt-1 text-sm text-white">{{ user.contact_urgence_telephone || '-' }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-slate-400">Relation</dt>
                <dd class="mt-1 text-sm text-white">{{ user.contact_urgence_relation || '-' }}</dd>
              </div>
            </dl>
          </div>
        </div>

        <!-- Colonne de droite -->
        <div class="space-y-6">
          <!-- Statut et Rôle -->
          <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Statut</h2>
            <div class="space-y-4">
              <div>
                <dt class="text-sm font-medium text-slate-400 mb-2">Rôle</dt>
                <dd>
                  <span
                    v-if="user.roles && user.roles.length > 0"
                    :class="getRoleBadgeClass(user.roles[0].name)"
                    class="px-3 py-1 inline-flex text-xs font-medium rounded-full"
                  >
                    {{ formatRole(user.roles[0].name) }}
                  </span>
                </dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-slate-400 mb-2">Statut</dt>
                <dd>
                  <span
                    v-if="user.active"
                    class="px-3 py-1 inline-flex text-xs font-medium rounded-full bg-green-500/20 text-green-400 border border-green-500/30"
                  >
                    Actif
                  </span>
                  <span
                    v-else
                    class="px-3 py-1 inline-flex text-xs font-medium rounded-full bg-red-500/20 text-red-400 border border-red-500/30"
                  >
                    Inactif
                  </span>
                </dd>
              </div>
              <div v-if="user.ecole">
                <dt class="text-sm font-medium text-slate-400 mb-2">École</dt>
                <dd class="text-sm text-white">{{ user.ecole.name || '-' }}</dd>
              </div>
            </div>
          </div>

          <!-- Karaté (si membre) -->
          <div v-if="user.ceinture_actuelle" class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Karaté</h2>
            <div>
              <dt class="text-sm font-medium text-slate-400 mb-2">Ceinture actuelle</dt>
              <dd>
                <span
                  class="px-3 py-1 inline-flex text-xs font-medium rounded-full"
                  :style="{
                    backgroundColor: (user.ceinture_actuelle.color_hex || '#666') + '30',
                    color: user.ceinture_actuelle.color_hex || '#666',
                    border: `1px solid ${user.ceinture_actuelle.color_hex || '#666'}50`
                  }"
                >
                  {{ user.ceinture_actuelle.name || 'Non définie' }}
                </span>
              </dd>
            </div>
          </div>

          <!-- Actions -->
          <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Actions</h2>
            <div class="space-y-3">
              <button
                @click="toggleStatus"
                :class="user.active ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-600 hover:bg-green-700'"
                class="w-full px-4 py-2 rounded-lg text-white text-sm font-medium transition-all"
              >
                {{ user.active ? 'Désactiver' : 'Activer' }}
              </button>
              <button
                v-if="can?.delete"
                @click="confirmDelete"
                class="w-full px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-medium transition-all"
              >
                Supprimer
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  user: {
    type: Object,
    required: true
  },
  can: {
    type: Object,
    default: () => ({ edit: false, delete: false })
  }
})

const formatDate = (date) => {
  if (!date) return '-'
  return new Date(date).toLocaleDateString('fr-CA', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const formatRole = (role) => {
  const roleLabels = {
    'superadmin': 'Super Admin',
    'admin_ecole': 'Administrateur',
    'instructeur': 'Instructeur',
    'membre': 'Membre'
  }
  return roleLabels[role] || role || '-'
}

const getRoleBadgeClass = (role) => {
  const classes = {
    'superadmin': 'bg-purple-500/20 text-purple-400 border border-purple-500/30',
    'admin_ecole': 'bg-blue-500/20 text-blue-400 border border-blue-500/30',
    'instructeur': 'bg-amber-500/20 text-amber-400 border border-amber-500/30',
    'membre': 'bg-slate-500/20 text-slate-400 border border-slate-500/30'
  }
  return classes[role] || 'bg-slate-500/20 text-slate-400 border border-slate-500/30'
}

const toggleStatus = () => {
  router.post(`/users/${props.user.id}/toggle-status`, {}, {
    preserveScroll: true
  })
}

const confirmDelete = () => {
  if (confirm(`Êtes-vous sûr de vouloir supprimer l'utilisateur "${props.user.name}" ?`)) {
    router.delete(`/users/${props.user.id}`, {
      onSuccess: () => {
        router.get('/users')
      }
    })
  }
}
</script>
