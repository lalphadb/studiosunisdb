<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
  users: Object,
  filters: Object,
  roles: Array,
  isSuper: Boolean,
  schools: Array,
  canCreate: Boolean,
})

const q = ref(props.filters?.search || '')
const role = ref(props.filters?.role || '')
const statut = ref(props.filters?.statut || '')

const createOpen = ref(false)
const editOpen = ref(null)

const createForm = useForm({
  name: '', email: '', password: '',
  role: 'instructeur', statut: 'actif',
  ecole_id: props.isSuper ? (props.schools[0]?.id ?? null) : null
})

function applyFilters(){
  const params = new URLSearchParams()
  if (q.value) params.set('search', q.value)
  if (role.value) params.set('role', role.value)
  if (statut.value) params.set('statut', statut.value)
  window.location.search = params.toString()
}

const editForm = useForm({ name:'', email:'', password:'', role:'', statut:'', ecole_id:null })
function openEdit(user){
  editOpen.value = user
  editForm.name = user.name
  editForm.email = user.email
  editForm.password = ''
  editForm.role = user.roles?.[0]?.name ?? 'instructeur'
  editForm.statut = user.statut
  editForm.ecole_id = user.ecole_id
}
</script>

<template>
  <Head title="Utilisateurs" />
  <div class="min-h-screen bg-gray-950 text-gray-100 px-6 py-10">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-semibold">Utilisateurs</h1>
      <button v-if="canCreate" @click="createOpen = true"
              class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500">+ Nouvel utilisateur</button>
    </div>

    <div class="flex gap-2 mb-4">
      <input v-model="q" @keyup.enter="applyFilters" placeholder="Recherche (nom, email)"
             class="bg-gray-900 rounded-lg px-3 py-2 w-64 ring-1 ring-white/10" />
      <select v-model="role" @change="applyFilters" class="bg-gray-900 rounded-lg px-3 py-2 ring-1 ring-white/10">
        <option value="">Tous rôles</option>
        <option v-for="r in roles" :key="r" :value="r">{{ r }}</option>
      </select>
      <select v-model="statut" @change="applyFilters" class="bg-gray-900 rounded-lg px-3 py-2 ring-1 ring-white/10">
        <option value="">Tous statuts</option>
        <option value="actif">actif</option>
        <option value="suspendu">suspendu</option>
        <option value="inactif">inactif</option>
      </select>
    </div>

    <div class="overflow-x-auto rounded-xl ring-1 ring-white/10">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-900/60">
          <tr>
            <th class="text-left px-4 py-3">Nom</th>
            <th class="text-left px-4 py-3">Email</th>
            <th class="text-left px-4 py-3">Rôle</th>
            <th class="text-left px-4 py-3">Statut</th>
            <th class="text-left px-4 py-3">Dernière connexion</th>
            <th class="text-right px-4 py-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="u in users.data" :key="u.id" class="border-t border-white/5">
            <td class="px-4 py-3">{{ u.name }}</td>
            <td class="px-4 py-3">{{ u.email }}</td>
            <td class="px-4 py-3"><span class="badge">{{ (u.roles?.[0]?.name)||'—' }}</span></td>
            <td class="px-4 py-3">
              <span :class="['badge',
                u.statut==='actif'?'badge--actif':u.statut==='suspendu'?'badge--suspendu':'badge--inactif'
              ]">{{ u.statut }}</span>
            </td>
            <td class="px-4 py-3">{{ u.last_login_at ? new Date(u.last_login_at).toLocaleString() : '—' }}</td>
            <td class="px-4 py-3 text-right">
              <button class="px-3 py-1 rounded-lg bg-gray-800 hover:bg-gray-700 mr-2" @click="openEdit(u)">Éditer</button>
              <Link as="button" method="delete" :href="`/admin/users/${u.id}`"
                    class="px-3 py-1 rounded-lg bg-red-700 hover:bg-red-600"
                    preserve-scroll>Supprimer</Link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal Création -->
    <dialog v-if="createOpen" open class="fixed inset-0 bg-black/60 flex items-center justify-center">
      <div class="bg-gray-900 rounded-2xl w-full max-w-lg p-6 ring-1 ring-white/10">
        <h2 class="text-lg font-semibold mb-4">Créer un utilisateur</h2>
        <form @submit.prevent="createForm.post('/admin/users', { onSuccess: () => (createOpen=false) })" class="space-y-3">
          <input v-model="createForm.name" placeholder="Nom" class="w-full bg-gray-950 px-3 py-2 rounded-lg ring-1 ring-white/10">
          <input v-model="createForm.email" placeholder="Email" class="w-full bg-gray-950 px-3 py-2 rounded-lg ring-1 ring-white/10">
          <input v-model="createForm.password" type="password" placeholder="Mot de passe" class="w-full bg-gray-950 px-3 py-2 rounded-lg ring-1 ring-white/10">
          <div class="flex gap-2">
            <select v-model="createForm.role" class="flex-1 bg-gray-950 px-3 py-2 rounded-lg ring-1 ring-white/10">
              <option v-for="r in roles" :key="r" :value="r">{{ r }}</option>
            </select>
            <select v-if="isSuper" v-model="createForm.ecole_id" class="flex-1 bg-gray-950 px-3 py-2 rounded-lg ring-1 ring-white/10">
              <option v-for="s in schools" :key="s.id" :value="s.id">{{ s.nom }}</option>
            </select>
            <select v-model="createForm.statut" class="flex-1 bg-gray-950 px-3 py-2 rounded-lg ring-1 ring-white/10">
              <option value="actif">actif</option>
              <option value="suspendu">suspendu</option>
              <option value="inactif">inactif</option>
            </select>
          </div>
          <div class="flex justify-end gap-2 pt-2">
            <button type="button" class="px-3 py-2 rounded-lg bg-gray-800" @click="createOpen=false">Annuler</button>
            <button class="px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-500">Créer</button>
          </div>
        </form>
      </div>
    </dialog>

    <!-- Modal Édition -->
    <dialog v-if="editOpen" open class="fixed inset-0 bg-black/60 flex items-center justify-center">
      <div class="bg-gray-900 rounded-2xl w-full max-w-lg p-6 ring-1 ring-white/10">
        <h2 class="text-lg font-semibold mb-4">Modifier utilisateur</h2>
        <form :action="`/admin/users/${editOpen.id}`" method="post" @submit.prevent="editForm.put(`/admin/users/${editOpen.id}`, { onSuccess: () => (editOpen=null) })" class="space-y-3">
          <input v-model="editForm.name" placeholder="Nom" class="w-full bg-gray-950 px-3 py-2 rounded-lg ring-1 ring-white/10">
          <input v-model="editForm.email" placeholder="Email" class="w-full bg-gray-950 px-3 py-2 rounded-lg ring-1 ring-white/10">
          <input v-model="editForm.password" type="password" placeholder="Nouveau mot de passe (optionnel)" class="w-full bg-gray-950 px-3 py-2 rounded-lg ring-1 ring-white/10">
          <div class="flex gap-2">
            <select v-model="editForm.role" class="flex-1 bg-gray-950 px-3 py-2 rounded-lg ring-1 ring-white/10">
              <option v-for="r in roles" :key="r" :value="r">{{ r }}</option>
            </select>
            <select v-model="editForm.statut" class="flex-1 bg-gray-950 px-3 py-2 rounded-lg ring-1 ring-white/10">
              <option value="actif">actif</option>
              <option value="suspendu">suspendu</option>
              <option value="inactif">inactif</option>
            </select>
          </div>
          <div class="flex justify-end gap-2 pt-2">
            <button type="button" class="px-3 py-2 rounded-lg bg-gray-800" @click="editOpen=null">Annuler</button>
            <button class="px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-500">Enregistrer</button>
          </div>
        </form>
      </div>
    </dialog>
  </div>
</template>
