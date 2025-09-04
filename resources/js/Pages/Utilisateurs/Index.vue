<template>
  <Head title="Gestion des Utilisateurs" />
  
  <AuthenticatedLayout>
    <div class="max-w-7xl mx-auto p-4 lg:p-6">
      <!-- Header premium style Dashboard -->
      <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-2xl shadow-2xl mb-6">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
        
        <div class="relative px-6 py-8">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                  <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                  </svg>
                </div>
                Gestion des Utilisateurs
              </h1>
              <p class="mt-1 text-indigo-100">
                {{ users.total }} utilisateurs inscrits
              </p>
            </div>
            
            <Link
              v-if="can.create"
              :href="route('utilisateurs.create')"
              class="px-6 py-3 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white rounded-xl flex items-center gap-2 transition-all font-medium shadow-lg"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              Nouvel utilisateur
            </Link>
          </div>
        </div>
      </div>

      <!-- Filtres -->
      <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6 mb-6">
        <form @submit.prevent="applyFilters" class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div class="md:col-span-2">
            <input
              v-model="form.q"
              type="text"
              class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent placeholder-slate-500"
              placeholder="Rechercher par nom ou email..."
            />
          </div>
          
          <select v-model="form.role" class="bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
            <option value="">Tous les rôles</option>
            <option v-for="role in roles" :key="role.id" :value="role.name">
              {{ role.name }}
            </option>
          </select>
          
          <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl transition-all font-medium shadow-lg">
            Filtrer
          </button>
        </form>
      </div>

      <!-- Table -->
      <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="bg-slate-900/50 border-b border-slate-700/50">
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                  Utilisateur
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                  Email
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                  Rôles
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                  Vérification
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                  Activité
                </th>
                <th class="px-6 py-4 text-right text-xs font-semibold text-slate-400 uppercase tracking-wider">
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
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                      <div class="h-10 w-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-semibold shadow-lg">
                        {{ user.name.charAt(0).toUpperCase() }}
                      </div>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-white">
                        {{ user.name }}
                      </div>
                      <div class="text-xs text-slate-400">
                        ID: {{ user.id }}
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-slate-300">{{ user.email }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex gap-1 flex-wrap">
                    <span
                      v-for="role in user.roles"
                      :key="role.id"
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-500/20 text-indigo-400 border border-indigo-500/30"
                    >
                      {{ role.name }}
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                    :class="user.email_verified_at 
                      ? 'bg-green-500/20 text-green-400 border border-green-500/30' 
                      : 'bg-amber-500/20 text-amber-400 border border-amber-500/30'"
                  >
                    {{ user.email_verified_at ? 'Vérifié' : 'Non vérifié' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center gap-2">
                    <span :class="user.active ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : 'bg-rose-500/20 text-rose-400 border-rose-500/30'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border">
                      {{ user.active ? 'Actif' : 'Inactif' }}
                    </span>
                    <span v-if="user.last_login_at" class="text-[11px] text-slate-400">
                      {{ new Date(user.last_login_at).toLocaleDateString() }}
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex items-center justify-end gap-1">
                    <Link
                      :href="route('utilisateurs.show', user.id)"
                      class="p-2 text-blue-400 hover:text-blue-300 hover:bg-blue-500/10 rounded-lg transition-all"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    </Link>
                    <Link
                      v-if="can.edit"
                      :href="route('utilisateurs.edit', user.id)"
                      class="p-2 text-amber-400 hover:text-amber-300 hover:bg-amber-500/10 rounded-lg transition-all"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </Link>
                    <button
                      v-if="can.delete"
                      @click="confirmDelete(user)"
                      class="p-2 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition-all"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
          <Pagination :links="users.links" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router, useForm, Link, Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps({
  users: Object,
  roles: Array,
  filters: Object,
  can: Object
})

const form = useForm({
  q: props.filters?.q ?? '',
  role: props.filters?.role ?? '',
})

const applyFilters = () => {
  router.get(route('utilisateurs.index'), form.data(), {
    preserveState: true,
    replace: true
  })
}

const confirmDelete = (user) => {
  if (confirm(`Êtes-vous sûr de vouloir supprimer ${user.name} ?`)) {
    router.delete(route('utilisateurs.destroy', user.id))
  }
}
</script>
