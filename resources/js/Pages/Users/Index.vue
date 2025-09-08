<template>
  <AuthenticatedLayout>
    <Head title="Gestion des Utilisateurs" />
    
    <div class="min-h-screen max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 overflow-x-hidden">
      <!-- Messages de feedback -->
      <div v-if="$page.props.flash?.success" class="mb-4 rounded-xl border border-green-500/40 bg-green-500/10 text-green-300 px-4 py-3 text-sm font-medium">
        {{ $page.props.flash.success }}
      </div>
      <div v-if="$page.props.errors?.delete" class="mb-4 rounded-xl border border-red-500/40 bg-red-500/10 text-red-300 px-4 py-3 text-sm font-medium">
        {{ $page.props.errors.delete }}
      </div>

      <PageHeader title="Utilisateurs" description="Gestion des comptes et des accès">
        <template #actions>
          <Link href="/users/create"
                class="px-4 py-2 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-medium hover:from-indigo-400 hover:to-purple-500 shadow-lg transition-all">
            Nouvel Utilisateur
          </Link>
        </template>
      </PageHeader>
      
      <!-- Stats Cards -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6">
        <StatCard title="Total Utilisateurs" :value="stats.total" tone="blue" description="Tous rôles confondus" />
        <StatCard title="Actifs" :value="stats.actifs" tone="green" description="Comptes actifs" />
        <StatCard title="Membres" :value="stats.membres" tone="purple" description="Membres karaté" />
        <StatCard title="Admins" :value="stats.admins" tone="amber" description="Admin et instructeurs" />
      </div>
      
      <!-- Filtres -->
      <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-4 md:p-6 mb-6">
        <form @submit.prevent="applyFilters" class="space-y-4">
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-3 md:gap-4">
            <!-- Recherche -->
            <div class="lg:col-span-2">
              <label class="block text-sm font-medium text-slate-400 mb-2">Recherche</label>
              <div class="relative">
                <input
                  v-model="searchTerm"
                  type="text"
                  class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-slate-500"
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
              <select v-model="filterRole" class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Tous les rôles</option>
                <option v-for="role in roles" :key="role" :value="role">{{ formatRole(role) }}</option>
              </select>
            </div>
            
            <!-- Statut -->
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">Statut</label>
              <select v-model="filterStatut" class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Tous</option>
                <option value="active">Actifs</option>
                <option value="inactive">Inactifs</option>
              </select>
            </div>
            
            <!-- Tri -->
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">Tri par</label>
              <select v-model="sortBy" class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="name">Nom</option>
                <option value="created_at">Date création</option>
                <option value="last_login_at">Dernière connexion</option>
              </select>
            </div>
          </div>
          
          <!-- Actions -->
          <div class="flex flex-wrap gap-3">
            <button type="submit" class="px-4 md:px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl flex items-center gap-2 transition-all font-medium shadow-lg">
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
            <button
              v-if="can?.export"
              type="button"
              @click="exportData"
              class="ml-auto px-4 md:px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl flex items-center gap-2 transition-all font-medium shadow-lg"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
              </svg>
              Exporter
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
                <th class="px-4 md:px-6 py-3 md:py-4 text-left text-[10px] md:text-xs font-semibold text-slate-400 uppercase tracking-wider">
                  Rôle
                </th>
                <th class="px-4 md:px-6 py-3 md:py-4 text-left text-[10px] md:text-xs font-semibold text-slate-400 uppercase tracking-wider hidden lg:table-cell">
                  École
                </th>
                <th class="px-4 md:px-6 py-3 md:py-4 text-left text-[10px] md:text-xs font-semibold text-slate-400 uppercase tracking-wider hidden sm:table-cell">
                  Statut
                </th>
                <th class="px-4 md:px-6 py-3 md:py-4 text-left text-[10px] md:text-xs font-semibold text-slate-400 uppercase tracking-wider hidden md:table-cell">
                  Dernière connexion
                </th>
                <th class="px-4 md:px-6 py-3 md:py-4 text-right text-[10px] md:text-xs font-semibold text-slate-400 uppercase tracking-wider w-20 md:w-24">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/30">
              <tr v-for="user in filteredUsers" 
                  :key="user.id"
                  class="hover:bg-slate-800/30 transition-all duration-200 group">
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap max-w-[160px] md:max-w-none">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8">
                      <div class="h-8 w-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-semibold shadow-lg text-xs">
                        {{ (user.name || 'U')[0].toUpperCase() }}
                      </div>
                    </div>
                    <div class="ml-3">
                      <div class="text-sm font-medium text-white">{{ user.name || 'Sans nom' }}</div>
                      <div class="text-xs text-slate-400">{{ user.telephone || 'Pas de téléphone' }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap hidden sm:table-cell">
                  <div class="text-sm text-slate-300">{{ user.email }}</div>
                </td>
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                  <span v-if="user.roles && user.roles.length > 0"
                        :class="getRoleBadgeClass(user.roles[0].name)"
                        class="px-3 py-1 inline-flex text-xs font-medium rounded-full">
                    {{ formatRole(user.roles[0].name) }}
                  </span>
                  <span v-else class="text-slate-400 text-xs">Non défini</span>
                </td>
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap hidden lg:table-cell">
                  <div class="text-sm text-slate-300">{{ user.ecole?.name || '-' }}</div>
                </td>
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap hidden sm:table-cell">
                  <span v-if="user.active" 
                        class="px-3 py-1 inline-flex text-xs font-medium rounded-full bg-green-500/20 text-green-400 border border-green-500/30">
                    Actif
                  </span>
                  <span v-else 
                        class="px-3 py-1 inline-flex text-xs font-medium rounded-full bg-red-500/20 text-red-400 border border-red-500/30">
                    Inactif
                  </span>
                </td>
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap hidden md:table-cell">
                  <div class="text-sm text-slate-300">{{ formatDate(user.last_login_at) }}</div>
                </td>
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex items-center justify-end gap-1 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-200">
                    <button @click="viewUser(user)"
                            class="p-1.5 text-blue-400 hover:text-blue-300 hover:bg-blue-500/10 rounded-lg transition-all"
                            title="Voir">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    </button>
                    <button @click="editUser(user)"
                            class="p-1.5 text-amber-400 hover:text-amber-300 hover:bg-amber-500/10 rounded-lg transition-all"
                            title="Modifier">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </button>
                    <button @click="toggleStatus(user)"
                            :class="user.active ? 'text-orange-400 hover:text-orange-300 hover:bg-orange-500/10' : 'text-green-400 hover:text-green-300 hover:bg-green-500/10'"
                            class="p-1.5 rounded-lg transition-all"
                            :title="user.active ? 'Désactiver' : 'Activer'">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              :d="user.active ? 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'" />
                      </svg>
                    </button>
                    <button @click="deleteUser(user)"
                            class="p-1.5 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition-all"
                            title="Supprimer">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import StatCard from '@/Components/UI/StatCard.vue'
import { debounce } from 'lodash'

const props = defineProps({
  users: {
    type: Object,
    default: () => ({ data: [], links: [] })
  },
  filters: {
    type: Object,
    default: () => ({})
  },
  roles: {
    type: Array,
    default: () => []
  },
  ceintures: {
    type: Array,
    default: () => []
  },
  stats: {
    type: Object,
    default: () => ({
      total: 0,
      actifs: 0,
      membres: 0,
      admins: 0
    })
  },
  can: {
    type: Object,
    default: () => ({ create: false, update: false, delete: false, export: false })
  }
})

const searchTerm = ref('')
const filterRole = ref('')
const filterStatut = ref('')
const sortBy = ref('name')

const usersData = computed(() => {
  return props.users?.data || []
})

const filteredUsers = computed(() => {
  let result = [...usersData.value]
  
  if (searchTerm.value) {
    const search = searchTerm.value.toLowerCase()
    result = result.filter(u => 
      u.name?.toLowerCase().includes(search) ||
      u.email?.toLowerCase().includes(search)
    )
  }
  
  if (filterRole.value) {
    result = result.filter(u => u.roles?.some(r => r.name === filterRole.value))
  }
  
  if (filterStatut.value) {
    result = result.filter(u => {
      if (filterStatut.value === 'active') return u.active
      if (filterStatut.value === 'inactive') return !u.active
      return true
    })
  }
  
  return result
})

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

const formatDate = (date) => {
  if (!date) return 'Jamais'
  return new Date(date).toLocaleDateString('fr-CA', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const debouncedSearch = debounce(() => {
  applyFilters()
}, 300)

const applyFilters = () => {
  // Filtres appliqués côté client pour l'instant
}

const resetFilters = () => {
  searchTerm.value = ''
  filterRole.value = ''
  filterStatut.value = ''
  sortBy.value = 'name'
}

const viewUser = (user) => {
  router.get(`/users/${user.id}`)
}

const editUser = (user) => {
  router.get(`/users/${user.id}/edit`)
}

const toggleStatus = (user) => {
  router.post(`/users/${user.id}/toggle-status`, {}, {
    preserveScroll: true
  })
}

const deleteUser = (user) => {
  if (!confirm(`Êtes-vous sûr de vouloir supprimer l'utilisateur "${user.name}" ?`)) return
  
  router.delete(`/users/${user.id}`, {
    preserveScroll: true,
    onError: (errors) => {
      console.error('Erreur suppression utilisateur:', errors)
      alert(errors?.delete || 'Impossible de supprimer cet utilisateur.')
    }
  })
}

const exportData = () => {
  const params = new URLSearchParams({
    search: searchTerm.value,
    role: filterRole.value,
    statut: filterStatut.value,
    sort: sortBy.value
  }).toString()
  window.open(`/users-export/xlsx?${params}`)
}
</script>
