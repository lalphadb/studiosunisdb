<template>
  <AuthenticatedLayout>
  <Head title="Gestion des Membres" />
  <div class="min-h-screen max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <PageHeader title="Membres" description="Gestion centralisée des membres et de leur progression.">
      <template #actions>
        <Link href="/membres/create" class="px-4 py-2 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-medium hover:from-indigo-400 hover:to-purple-500">Nouveau membre</Link>
      </template>
    </PageHeader>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <StatCard title="Total membres" :value="stats.total" tone="blue" description="Tous statuts" />
      <StatCard title="Membres actifs" :value="stats.actifs" :change="Math.round((stats.actifs / stats.total) * 100)" format="percentage" tone="green" description="Actifs / total" />
      <StatCard title="Nouveaux ce mois" :value="stats.nouveaux_mois" tone="purple" description="Inscriptions récentes" />
      <StatCard title="Présences aujourd'hui" :value="stats.presences_jour" tone="amber" description="Participation active" />
    </div>
      </div>

      <!-- Filtres style Dashboard avec margin horizontal -->
      <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6 mb-6 mx-6">
        <form @submit.prevent="applyFilters" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <!-- Recherche -->
            <div class="lg:col-span-2">
              <label class="block text-sm font-medium text-slate-400 mb-2">Recherche</label>
              <div class="relative">
                <input
                  v-model="form.q"
                  type="text"
                  class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-slate-500"
                  placeholder="Nom, email, téléphone..."
                  @input="debouncedSearch"
                />
                <svg class="absolute left-3 top-3 w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
            </div>
            
            <!-- Statut -->
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">Statut</label>
              <select v-model="form.statut" class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Tous</option>
                <option value="actif">Actif</option>
                <option value="inactif">Inactif</option>
                <option value="suspendu">Suspendu</option>
              </select>
            </div>
            
            <!-- Ceinture -->
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">Ceinture</label>
              <select v-model="form.ceinture_id" class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Toutes</option>
                <option v-for="ceinture in ceintures" :key="ceinture.id" :value="ceinture.id">
                  {{ ceinture.nom }}
                </option>
              </select>
            </div>
            
            <!-- Âge -->
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">Âge</label>
              <select v-model="form.age_group" class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Tous</option>
                <option value="mineur">Mineurs</option>
                <option value="adulte">Adultes</option>
              </select>
            </div>
            
            <!-- Tri -->
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">Tri par</label>
              <select v-model="form.sort" class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="created_at">Date création</option>
                <option value="nom">Nom</option>
                <option value="prenom">Prénom</option>
                <option value="date_inscription">Inscription</option>
              </select>
            </div>
          </div>
          
          <!-- Actions -->
          <div class="flex gap-3">
            <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl flex items-center gap-2 transition-all font-medium shadow-lg">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
              </svg>
              Filtrer
            </button>
            <button type="button" @click="resetFilters" class="px-5 py-2.5 bg-slate-800/50 hover:bg-slate-700/50 text-white rounded-xl flex items-center gap-2 transition-all border border-slate-700">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
              Réinitialiser
            </button>
            <button
              v-if="can.export"
              type="button"
              @click="exportData"
              class="ml-auto px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl flex items-center gap-2 transition-all font-medium shadow-lg"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
              </svg>
              Exporter
            </button>
          </div>
        </form>
      </div>

      <!-- Table style Dashboard avec margin horizontal -->
      <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 overflow-hidden mx-6 mb-6">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="bg-slate-900/50 border-b border-slate-700/50">
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                  Membre
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                  Contact
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                  Ceinture
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                  Statut
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                  Cours
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                  Présences
                </th>
                <th class="px-6 py-4 text-right text-xs font-semibold text-slate-400 uppercase tracking-wider">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/30">
              <tr
                v-for="membre in membres.data"
                :key="membre.id"
                class="hover:bg-slate-800/30 transition-all duration-200 group"
              >
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                      <div class="h-10 w-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-semibold shadow-lg">
                        {{ membre.prenom[0] }}{{ membre.nom[0] }}
                      </div>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-white">
                        {{ membre.nom_complet }}
                      </div>
                      <div class="text-xs text-slate-400">
                        {{ membre.age }} ans
                        <span v-if="membre.is_minor" class="ml-1 text-amber-400 font-medium">(Mineur)</span>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-slate-300">{{ membre.user?.email }}</div>
                  <div class="text-xs text-slate-500">{{ membre.telephone }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    v-if="membre.ceinture_actuelle"
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                    :style="{
                      backgroundColor: membre.ceinture_actuelle.couleur_hex + '30',
                      color: membre.ceinture_actuelle.couleur_hex,
                      border: `1px solid ${membre.ceinture_actuelle.couleur_hex}50`
                    }"
                  >
                    {{ membre.ceinture_actuelle.nom }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                    :class="{
                      'bg-green-500/20 text-green-400 border border-green-500/30': membre.statut === 'actif',
                      'bg-slate-500/20 text-slate-400 border border-slate-500/30': membre.statut === 'inactif',
                      'bg-red-500/20 text-red-400 border border-red-500/30': membre.statut === 'suspendu'
                    }"
                  >
                    {{ statusLabels[membre.statut] }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">
                  {{ membre.cours_count || 0 }} cours
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <span class="text-sm text-slate-400">{{ membre.presences_mois || 0 }}</span>
                    <span class="ml-2 text-xs text-slate-500">/ mois</span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <Link
                      :href="`/membres/${membre.id}`"
                      class="p-2 text-blue-400 hover:text-blue-300 hover:bg-blue-500/10 rounded-lg transition-all"
                      title="Voir"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    </Link>
                    <Link
                      v-if="can.update"
                      :href="`/membres/${membre.id}/edit`"
                      class="p-2 text-amber-400 hover:text-amber-300 hover:bg-amber-500/10 rounded-lg transition-all"
                      title="Modifier"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </Link>
                    <button
                      v-if="can.delete"
                      @click="confirmDelete(membre)"
                      class="p-2 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition-all"
                      title="Supprimer"
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
          <Pagination :links="membres.links" />
        </div>
  </div>

    <!-- Delete Modal -->
    <ConfirmModal
      :show="showDeleteModal"
      @close="showDeleteModal = false"
      @confirm="deleteMembre"
      title="Supprimer le membre"
      :message="`Êtes-vous sûr de vouloir supprimer ${membreToDelete?.nom_complet} ?`"
      confirmText="Supprimer"
      danger
    />
  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router, useForm, Link, Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import ConfirmModal from '@/Components/ConfirmModal.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import StatCard from '@/Components/UI/StatCard.vue'
import { debounce } from 'lodash'

const props = defineProps({
  membres: Object,
  filters: Object,
  ceintures: Array,
  stats: Object,
  can: Object
})

const form = useForm({
  q: props.filters?.q ?? '',
  statut: props.filters?.statut ?? '',
  ceinture_id: props.filters?.ceinture_id ?? '',
  age_group: props.filters?.age_group ?? '',
  sort: props.filters?.sort ?? 'created_at',
  dir: props.filters?.dir ?? 'desc',
  per_page: props.filters?.per_page ?? 15
})

const showDeleteModal = ref(false)
const membreToDelete = ref(null)

const statusLabels = {
  actif: 'Actif',
  inactif: 'Inactif',
  suspendu: 'Suspendu'
}

const debouncedSearch = debounce(() => {
  applyFilters()
}, 300)

const applyFilters = () => {
  router.get('/membres', form.data(), {
    preserveState: true,
    replace: true
  })
}

const resetFilters = () => {
  form.reset()
  applyFilters()
}

const confirmDelete = (membre) => {
  membreToDelete.value = membre
  showDeleteModal.value = true
}

const deleteMembre = () => {
  router.delete(`/membres/${membreToDelete.value.id}`, {
    onSuccess: () => {
      showDeleteModal.value = false
      membreToDelete.value = null
    }
  })
}

const exportData = () => {
  const params = new URLSearchParams(form.data()).toString()
  window.open(`/membres-export/xlsx?${params}`)
}
</script>
