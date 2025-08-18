<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
          Gestion des Membres
        </h1>
        <Link
          v-if="can.create"
          :href="route('members.create')"
          class="btn-primary"
        >
          <PlusIcon class="w-5 h-5 mr-2" />
          Nouveau membre
        </Link>
      </div>
    </template>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
      <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white">
        <div class="text-3xl font-bold">{{ stats.total }}</div>
        <div class="text-sm opacity-90">Total membres</div>
      </div>
      <div class="card bg-gradient-to-br from-green-500 to-green-600 text-white">
        <div class="text-3xl font-bold">{{ stats.active }}</div>
        <div class="text-sm opacity-90">Membres actifs</div>
      </div>
      <div class="card bg-gradient-to-br from-purple-500 to-purple-600 text-white">
        <div class="text-3xl font-bold">{{ stats.new_this_month }}</div>
        <div class="text-sm opacity-90">Nouveaux ce mois</div>
      </div>
      <div class="card bg-gradient-to-br from-orange-500 to-orange-600 text-white">
        <div class="text-3xl font-bold">{{ stats.minors }}</div>
        <div class="text-sm opacity-90">Mineurs</div>
      </div>
    </div>

    <!-- Filters -->
    <div class="card mb-6">
      <form @submit.prevent="applyFilters" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
          <div class="lg:col-span-2">
            <label class="block text-sm font-medium mb-1">Recherche</label>
            <input
              v-model="form.q"
              type="text"
              class="input w-full"
              placeholder="Nom, email, téléphone..."
              @input="debouncedSearch"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium mb-1">Statut</label>
            <select v-model="form.status" class="input w-full">
              <option value="">Tous</option>
              <option value="active">Actif</option>
              <option value="inactive">Inactif</option>
              <option value="suspended">Suspendu</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium mb-1">Ceinture</label>
            <select v-model="form.belt_id" class="input w-full">
              <option value="">Toutes</option>
              <option v-for="belt in belts" :key="belt.id" :value="belt.id">
                {{ belt.name }}
              </option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium mb-1">Âge</label>
            <select v-model="form.age_group" class="input w-full">
              <option value="">Tous</option>
              <option value="minor">Mineurs</option>
              <option value="adult">Adultes</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium mb-1">Tri</label>
            <select v-model="form.sort" class="input w-full">
              <option value="created_at">Date création</option>
              <option value="last_name">Nom</option>
              <option value="first_name">Prénom</option>
              <option value="registration_date">Date inscription</option>
              <option value="last_attendance_date">Dernière présence</option>
            </select>
          </div>
        </div>
        
        <div class="flex gap-2">
          <button type="submit" class="btn-primary">
            <FunnelIcon class="w-5 h-5 mr-2" />
            Filtrer
          </button>
          <button type="button" @click="resetFilters" class="btn-ghost">
            <XMarkIcon class="w-5 h-5 mr-2" />
            Réinitialiser
          </button>
          <button
            v-if="can.export"
            type="button"
            @click="exportData"
            class="btn-secondary ml-auto"
          >
            <ArrowDownTrayIcon class="w-5 h-5 mr-2" />
            Exporter
          </button>
        </div>
      </form>
    </div>

    <!-- Members Table -->
    <div class="card overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                <input
                  type="checkbox"
                  v-model="selectAll"
                  @change="toggleSelectAll"
                  class="rounded"
                />
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Membre
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Contact
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Ceinture
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Statut
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Cours
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
            <tr
              v-for="member in members.data"
              :key="member.id"
              class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
            >
              <td class="px-6 py-4 whitespace-nowrap">
                <input
                  type="checkbox"
                  :value="member.id"
                  v-model="selectedIds"
                  class="rounded"
                />
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold">
                      {{ member.first_name[0] }}{{ member.last_name[0] }}
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                      {{ member.full_name }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                      {{ member.age }} ans
                      <span v-if="member.is_minor" class="text-orange-500 font-medium">(Mineur)</span>
                    </div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900 dark:text-white">{{ member.email }}</div>
                <div class="text-sm text-gray-500">{{ member.phone }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  v-if="member.current_belt"
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                  :style="{
                    backgroundColor: member.current_belt.color_hex + '20',
                    color: member.current_belt.color_hex
                  }"
                >
                  {{ member.current_belt.name }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                  :class="{
                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': member.status === 'active',
                    'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300': member.status === 'inactive',
                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': member.status === 'suspended'
                  }"
                >
                  {{ statusLabels[member.status] }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ member.courses_count }} cours
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex items-center gap-2">
                  <Link
                    :href="route('members.show', member.id)"
                    class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300"
                  >
                    <EyeIcon class="w-5 h-5" />
                  </Link>
                  <Link
                    v-if="can.update"
                    :href="route('members.edit', member.id)"
                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                  >
                    <PencilIcon class="w-5 h-5" />
                  </Link>
                  <button
                    v-if="can.delete"
                    @click="confirmDelete(member)"
                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                  >
                    <TrashIcon class="w-5 h-5" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
        <Pagination :links="members.links" />
      </div>
    </div>

    <!-- Bulk Actions -->
    <div v-if="selectedIds.length > 0" class="fixed bottom-4 right-4 card shadow-lg">
      <div class="flex items-center gap-4">
        <span class="text-sm font-medium">
          {{ selectedIds.length }} sélectionné(s)
        </span>
        <button @click="bulkAction('activate')" class="btn-ghost text-green-600">
          Activer
        </button>
        <button @click="bulkAction('deactivate')" class="btn-ghost text-gray-600">
          Désactiver
        </button>
        <button @click="bulkAction('suspend')" class="btn-ghost text-orange-600">
          Suspendre
        </button>
        <button @click="bulkAction('delete')" class="btn-ghost text-red-600">
          Supprimer
        </button>
      </div>
    </div>

    <!-- Delete Modal -->
    <ConfirmModal
      :show="showDeleteModal"
      @close="showDeleteModal = false"
      @confirm="deleteMember"
      title="Supprimer le membre"
      :message="`Êtes-vous sûr de vouloir supprimer ${memberToDelete?.full_name} ?`"
      confirmText="Supprimer"
      danger
    />
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { router, useForm, Link, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import ConfirmModal from '@/Components/ConfirmModal.vue'
import {
  PlusIcon,
  FunnelIcon,
  XMarkIcon,
  ArrowDownTrayIcon,
  EyeIcon,
  PencilIcon,
  TrashIcon
} from '@heroicons/vue/24/outline'
import { debounce } from 'lodash'

const props = defineProps({
  members: Object,
  filters: Object,
  belts: Array,
  stats: Object
})

const page = usePage()
const can = computed(() => ({
  create: page.props.auth.can?.create_members ?? false,
  update: page.props.auth.can?.update_members ?? false,
  delete: page.props.auth.can?.delete_members ?? false,
  export: page.props.auth.can?.export_members ?? false
}))

const form = useForm({
  q: props.filters?.q ?? '',
  status: props.filters?.status ?? '',
  belt_id: props.filters?.belt_id ?? '',
  age_group: props.filters?.age_group ?? '',
  from_date: props.filters?.from_date ?? '',
  to_date: props.filters?.to_date ?? '',
  sort: props.filters?.sort ?? 'created_at',
  dir: props.filters?.dir ?? 'desc',
  per_page: props.filters?.per_page ?? 15
})

const selectedIds = ref([])
const selectAll = ref(false)
const showDeleteModal = ref(false)
const memberToDelete = ref(null)

const statusLabels = {
  active: 'Actif',
  inactive: 'Inactif',
  suspended: 'Suspendu'
}

const debouncedSearch = debounce(() => {
  applyFilters()
}, 300)

const applyFilters = () => {
  router.get(route('members.index'), form.data(), {
    preserveState: true,
    replace: true
  })
}

const resetFilters = () => {
  form.reset()
  applyFilters()
}

const toggleSelectAll = () => {
  if (selectAll.value) {
    selectedIds.value = props.members.data.map(m => m.id)
  } else {
    selectedIds.value = []
  }
}

const confirmDelete = (member) => {
  memberToDelete.value = member
  showDeleteModal.value = true
}

const deleteMember = () => {
  router.delete(route('members.destroy', memberToDelete.value.id), {
    onSuccess: () => {
      showDeleteModal.value = false
      memberToDelete.value = null
    }
  })
}

const bulkAction = (action) => {
  if (!confirm(`Voulez-vous ${action} ${selectedIds.value.length} membre(s) ?`)) {
    return
  }
  
  router.post(route('members.bulk-update'), {
    ids: selectedIds.value,
    action: action
  }, {
    onSuccess: () => {
      selectedIds.value = []
      selectAll.value = false
    }
  })
}

const exportData = () => {
  window.location.href = route('members.export', form.data())
}

watch(() => props.members.data, () => {
  selectAll.value = false
  selectedIds.value = []
})
</script>
