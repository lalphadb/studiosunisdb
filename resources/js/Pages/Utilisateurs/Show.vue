<template>
  <Head :title="`Utilisateur - ${user.name}`" />
  
  <AuthenticatedLayout>
    <div class="min-h-screen max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Messages de feedback -->
      <div v-if="$page.props.flash?.success" class="mb-4 rounded-xl border border-green-500/40 bg-green-500/10 text-green-300 px-4 py-3 text-sm font-medium">
        {{$page.props.flash.success}}
      </div>

      <!-- Header -->
      <PageHeader :title="user.name" description="Détails de l'utilisateur">
        <template #actions>
          <Link
            :href="route('utilisateurs.edit', user.id)"
            class="px-4 py-2 rounded-lg bg-gradient-to-r from-amber-500 to-orange-600 text-white text-sm font-medium hover:from-amber-400 hover:to-orange-500"
          >
            Modifier
          </Link>
          <Link
            :href="route('utilisateurs.index')"
            class="px-4 py-2 rounded-lg bg-slate-700 text-white text-sm font-medium hover:bg-slate-600"
          >
            Retour
          </Link>
        </template>
      </PageHeader>

      <!-- Contenu principal -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Profil -->
          <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Informations générales</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-1">Nom complet</label>
                <div class="text-white font-medium">{{ user.name }}</div>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-1">Email</label>
                <div class="text-white">{{ user.email }}</div>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-1">Date de création</label>
                <div class="text-white">{{ new Date(user.created_at).toLocaleDateString('fr-CA') }}</div>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-1">Dernière modification</label>
                <div class="text-white">{{ new Date(user.updated_at).toLocaleDateString('fr-CA') }}</div>
              </div>
            </div>
          </div>

          <!-- Rôles et permissions -->
          <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Rôles et permissions</h3>
            
            <!-- Rôles -->
            <div class="mb-6">
              <label class="block text-sm font-medium text-slate-400 mb-2">Rôles assignés</label>
              <div class="flex flex-wrap gap-2">
                <span
                  v-for="role in user.roles"
                  :key="role.id"
                  class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                  :class="getRoleBadgeClass(role.name)"
                >
                  {{ role.name }}
                </span>
                <span v-if="!user.roles?.length" class="text-slate-500 text-sm">Aucun rôle assigné</span>
              </div>
            </div>

            <!-- Permissions directes -->
            <div v-if="user.permissions?.length">
              <label class="block text-sm font-medium text-slate-400 mb-2">Permissions directes</label>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                <span
                  v-for="permission in user.permissions"
                  :key="permission.id"
                  class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-blue-500/20 text-blue-400 border border-blue-500/30"
                >
                  {{ permission.name }}
                </span>
              </div>
            </div>
          </div>

          <!-- Membre associé -->
          <div v-if="user.membre" class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Membre associé</h3>
            <div class="flex items-center gap-4">
              <div class="flex-shrink-0 h-12 w-12">
                <div class="h-12 w-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-semibold shadow-lg">
                  {{ user.membre.prenom.charAt(0).toUpperCase() }}{{ user.membre.nom.charAt(0).toUpperCase() }}
                </div>
              </div>
              <div>
                <div class="text-white font-medium">{{ user.membre.prenom }} {{ user.membre.nom }}</div>
                <div class="text-slate-400 text-sm">{{ user.membre.email }}</div>
                <div class="text-slate-500 text-xs">Ceinture: {{ user.membre.ceinture_actuelle || 'Aucune' }}</div>
              </div>
              <div class="ml-auto">
                <Link
                  :href="route('membres.show', user.membre.id)"
                  class="px-3 py-1.5 text-xs bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 rounded-lg hover:bg-indigo-500/30 transition-all"
                >
                  Voir le profil
                </Link>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
          <!-- Statut -->
          <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Statut</h3>
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <span class="text-slate-400 text-sm">Compte actif</span>
                <span
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                  :class="user.active 
                    ? 'bg-green-500/20 text-green-400 border border-green-500/30'
                    : 'bg-red-500/20 text-red-400 border border-red-500/30'"
                >
                  {{ user.active ? 'Actif' : 'Inactif' }}
                </span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-slate-400 text-sm">Email vérifié</span>
                <span
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                  :class="user.email_verified_at 
                    ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' 
                    : 'bg-amber-500/20 text-amber-400 border border-amber-500/30'"
                >
                  {{ user.email_verified_at ? 'Vérifié' : 'Non vérifié' }}
                </span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-slate-400 text-sm">Dernière connexion</span>
                <span class="text-white text-sm">
                  {{ user.last_login_at ? new Date(user.last_login_at).toLocaleDateString('fr-CA') : 'Jamais' }}
                </span>
              </div>
            </div>
          </div>

          <!-- Actions rapides -->
          <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Actions rapides</h3>
            <div class="space-y-3">
              <button
                @click="resetPassword"
                class="w-full px-4 py-2 bg-purple-500/20 text-purple-400 border border-purple-500/30 rounded-lg hover:bg-purple-500/30 transition-all text-sm font-medium"
              >
                Réinitialiser le mot de passe
              </button>
              
              <button
                v-if="user.active"
                @click="toggleActive(false)"
                class="w-full px-4 py-2 bg-red-500/20 text-red-400 border border-red-500/30 rounded-lg hover:bg-red-500/30 transition-all text-sm font-medium"
              >
                Désactiver le compte
              </button>
              
              <button
                v-else
                @click="toggleActive(true)"
                class="w-full px-4 py-2 bg-green-500/20 text-green-400 border border-green-500/30 rounded-lg hover:bg-green-500/30 transition-all text-sm font-medium"
              >
                Activer le compte
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { inject } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'

// Injecter la fonction route
const route = inject('route') || window.route

const props = defineProps({
  user: {
    type: Object,
    required: true
  }
})

const getRoleBadgeClass = (role) => {
  const classes = {
    'superadmin': 'bg-red-500/20 text-red-400 border border-red-500/30',
    'admin_ecole': 'bg-purple-500/20 text-purple-400 border border-purple-500/30',
    'instructeur': 'bg-blue-500/20 text-blue-400 border border-blue-500/30',
    'membre': 'bg-green-500/20 text-green-400 border border-green-500/30'
  }
  return classes[role] || 'bg-slate-500/20 text-slate-400 border border-slate-500/30'
}

const resetPassword = () => {
  const newPassword = prompt(`Nouveau mot de passe pour ${props.user.name}:`)
  if (newPassword && newPassword.length >= 8) {
    const confirmPassword = prompt('Confirmer le mot de passe:')
    if (newPassword === confirmPassword) {
      router.post(route('utilisateurs.reset-password', props.user.id), {
        password: newPassword,
        password_confirmation: confirmPassword
      }, {
        preserveScroll: true
      })
    } else {
      alert('Les mots de passe ne correspondent pas.')
    }
  } else {
    alert('Le mot de passe doit contenir au moins 8 caractères.')
  }
}

const toggleActive = (active) => {
  const action = active ? 'activer' : 'désactiver'
  if (confirm(`Êtes-vous sûr de vouloir ${action} ${props.user.name} ?`)) {
    router.patch(route('utilisateurs.update', props.user.id), {
      active: active
    }, {
      preserveScroll: true
    })
  }
}
</script>
