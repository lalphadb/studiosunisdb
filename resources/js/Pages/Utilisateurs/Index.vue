<template>
  <Head title="Gestion des Utilisateurs" />
  
  <AuthenticatedLayout>
    <div class="min-h-screen max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 overflow-x-hidden">
      <!-- Messages de feedback -->
      <div v-if="$page.props.flash?.success" class="mb-4 rounded-xl border border-green-500/40 bg-green-500/10 text-green-300 px-4 py-3 text-sm font-medium">
        {{$page.props.flash.success}}
      </div>
      <div v-if="$page.props.errors?.delete" class="mb-4 rounded-xl border border-red-500/40 bg-red-500/10 text-red-300 px-4 py-3 text-sm font-medium">
        {{$page.props.errors.delete}}
      </div>

      <!-- Header avec PageHeader pour cohérence -->
      <PageHeader title="Utilisateurs" :description="`${users.total || 0} utilisateurs inscrits`">
        <template #actions>
          <Link
            v-if="can.create"
            :href="route('utilisateurs.create')"
            class="px-4 py-2 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-medium hover:from-indigo-400 hover:to-purple-500"
          >
            Nouvel utilisateur
          </Link>
        </template>
      </PageHeader>

      <!-- Stats Cards -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6">
        <StatCard title="Total" :value="stats.total" tone="blue" description="Tous utilisateurs" />
        <StatCard title="Actifs" :value="stats.actifs" tone="green" description="Connectés récemment" />
        <StatCard title="Instructeurs" :value="stats.instructeurs" tone="purple" description="Personnel enseignant" />
        <StatCard title="Admins" :value="stats.admins" tone="amber" description="Administrateurs" />
      </div>

      <!-- Filtres -->
      <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-4 md:p-6 mb-6">
        <form @submit.prevent="applyFilters" class="space-y-4">
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 md:gap-4">
            <!-- Recherche -->
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-slate-400 mb-2">Recherche</label>
              <div class="relative">
                <input
                  v-model="form.q"
                  type="text"
                  class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent placeholder-slate-500"
                  placeholder="Nom ou email..."
                  @input="debouncedSearch"
                />
                <svg class="absolute left-3 top-3 w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
            </div>
            
            <!-- Rôle -->
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">Rôle</label>
              <select v-model="form.role" class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <option value="">Tous les rôles</option>
                <option v-for="role in roles" :key="role" :value="role">{{ role }}</option>
              </select>
            </div>
          </div>
          
          <!-- Actions -->
          <div class="flex flex-wrap gap-3">
            <button type="submit" class="px-4 md:px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl flex items-center gap-2 transition-all font-medium shadow-lg">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
              </svg>
              Filtrer
            </button>
            <button type="button" @click="resetFilters" class="px-4 md:px-5 py-2.5 bg-slate-800/50 hover:bg-slate-700/50 text-white rounded-xl flex items-center gap-2 transition-all border border-slate-700">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
              Réinitialiser
            </button>
          </div>
        </form>
      </div>

      <!-- Table -->
      <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 overflow-hidden mb-6">
        <div class="overflow-x-auto -mx-4 sm:mx-0">
          <table class="w-full">
            <thead>
              <tr class="bg-slate-900/50 border-b border-slate-700/50">
                <th class="px-4 md:px-6 py-3 md:py-4 text-left text-[10px] md:text-xs font-semibold text-slate-400 uppercase tracking-wider">
                  Utilisateur
                </th>
                <th class="px-4 md:px-6 py-3 md:py-4 text-left text-[10px] md:text-xs font-semibold text-slate-400 uppercase tracking-wider hidden sm:table-cell">
                  Email
                </th>
                <th class="px-4 md:px-6 py-3 md:py-4 text-left text-[10px] md:text-xs font-semibold text-slate-400 uppercase tracking-wider hidden md:table-cell">
                  Rôles
                </th>
                <th class="px-4 md:px-6 py-3 md:py-4 text-left text-[10px] md:text-xs font-semibold text-slate-400 uppercase tracking-wider hidden lg:table-cell">
                  Statut
                </th>
                <th class="px-4 md:px-6 py-3 md:py-4 text-left text-[10px] md:text-xs font-semibold text-slate-400 uppercase tracking-wider hidden sm:table-cell">
                  Dernière connexion
                </th>
                <th class="px-4 md:px-6 py-3 md:py-4 text-right text-[10px] md:text-xs font-semibold text-slate-400 uppercase tracking-wider w-20 md:w-24">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/30">
              <tr
                v-for="user in users.data"
                :key="user.id"
                class="hover:bg-slate-800/30 transition-all duration-200 group"
              >
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap max-w-[160px] md:max-w-none">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8">
                      <div class="h-8 w-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-semibold shadow-lg text-xs">
                        {{ user.name.charAt(0).toUpperCase() }}
                      </div>
                    </div>
                    <div class="ml-3 min-w-0 flex-1">
                      <div class="text-sm font-medium text-white truncate">{{ user.name }}</div>
                      <div class="text-xs text-slate-400 sm:hidden truncate">{{ user.email }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap hidden sm:table-cell">
                  <div class="text-sm text-slate-300 truncate max-w-[200px]">{{ user.email }}</div>
                </td>
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap hidden md:table-cell">
                  <div class="flex gap-1 flex-wrap">
                    <span
                      v-for="role in user.roles"
                      :key="role.id"
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                      :class="getRoleBadgeClass(role.name)"
                    >
                      {{ role.name }}
                    </span>
                  </div>
                </td>
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap hidden lg:table-cell">
                  <div class="flex flex-col gap-1">
                    <span
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                      :class="user.active 
                        ? 'bg-green-500/20 text-green-400 border border-green-500/30'
                        : 'bg-red-500/20 text-red-400 border border-red-500/30'"
                    >
                      {{ user.active ? 'Actif' : 'Inactif' }}
                    </span>
                    <span
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                      :class="user.email_verified_at 
                        ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' 
                        : 'bg-amber-500/20 text-amber-400 border border-amber-500/30'"
                    >
                      {{ user.email_verified_at ? 'Vérifié' : 'Non vérifié' }}
                    </span>
                  </div>
                </td>
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap hidden sm:table-cell">
                  <div class="text-xs text-slate-400">
                    {{ user.last_login_at ? new Date(user.last_login_at).toLocaleDateString('fr-CA') : 'Jamais' }}
                  </div>
                </td>
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <!-- Actions avec hover uniformisé -->
                    <Link
                      :href="route('utilisateurs.edit', user.id)"
                      class="p-1.5 text-amber-400 hover:text-amber-300 hover:bg-amber-500/10 rounded-lg transition-all"
                      title="Modifier"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </Link>
                    
                    <button
                      v-if="canDelete(user)"
                      @click="confirmDelete(user)"
                      class="p-1.5 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition-all"
                      title="Désactiver"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                    
                    <!-- Reset password -->
                    <button
                      v-if="canResetPassword(user)"
                      @click="resetPassword(user)"
                      class="p-1.5 text-purple-400 hover:text-purple-300 hover:bg-purple-500/10 rounded-lg transition-all"
                      title="Réinitialiser mot de passe"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-4 md:px-6 py-4 border-t border-slate-700/50 bg-slate-900/30">
          <Pagination 
            :links="users.links || []" 
            :from="users.from || null" 
            :to="users.to || null" 
            :total="users.total || null" 
          />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, inject } from 'vue'
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import StatCard from '@/Components/UI/StatCard.vue'
import { debounce } from 'lodash'

// Injecter la fonction route
const route = inject('route') || window.route

const props = defineProps({
  users: {
    type: Object,
    default: () => ({ data: [], links: [] })
  },
  roles: {
    type: Array,
    default: () => []
  },
  filters: {
    type: Object,
    default: () => ({})
  },
  can: {
    type: Object,
    default: () => ({})
  }
})

const page = usePage()

const form = useForm({
  q: props.filters?.q ?? '',
  role: props.filters?.role ?? '',
})

// Stats calculées
const stats = computed(() => {
  const data = props.users.data || []
  return {
    total: props.users.total || data.length,
    actifs: data.filter(u => u.active).length,
    instructeurs: data.filter(u => u.roles?.some(r => r.name === 'instructeur')).length,
    admins: data.filter(u => u.roles?.some(r => ['superadmin', 'admin_ecole'].includes(r.name))).length,
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

const canDelete = (user) => {
  const currentUser = page.props.auth?.user
  return props.can.delete && currentUser?.id !== user.id
}

const canResetPassword = (user) => {
  return props.can.manageRoles
}

const debouncedSearch = debounce(() => {
  applyFilters()
}, 300)

const applyFilters = () => {
  router.get(route('utilisateurs.index'), form.data(), {
    preserveState: true,
    replace: true
  })
}

const resetFilters = () => {
  form.q = ''
  form.role = ''
  applyFilters()
}

const confirmDelete = (user) => {
  if (confirm(`Êtes-vous sûr de vouloir désactiver ${user.name} ?`)) {
    router.delete(route('utilisateurs.destroy', user.id), {
      preserveScroll: true
    })
  }
}

const resetPassword = (user) => {
  const newPassword = prompt(`Nouveau mot de passe pour ${user.name}:`)
  if (newPassword && newPassword.length >= 8) {
    const confirmPassword = prompt('Confirmer le mot de passe:')
    if (newPassword === confirmPassword) {
      router.post(route('utilisateurs.reset-password', user.id), {
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
</script>
