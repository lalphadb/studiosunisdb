<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
  members: Object,
  filters: Object,
  belts: Array,
  canCreate: Boolean,
})

const q = ref(props.filters?.search || '')
const statut = ref(props.filters?.statut || '')
const belt = ref(props.filters?.belt || '')

const createOpen = ref(false)
const form = useForm({
  prenom:'', nom:'', courriel:'', telephone:'',
  adresse:'', date_naissance:'', date_inscription:'',
  statut:'actif', ceinture_actuelle_id:null
})

function applyFilters(){
  const params = new URLSearchParams()
  if (q.value) params.set('search', q.value)
  if (statut.value) params.set('statut', statut.value)
  if (belt.value) params.set('belt', belt.value)
  window.location.search = params.toString()
}
</script>

<template>
  <Head title="Membres" />
  <div class="min-h-screen bg-gray-950 text-gray-100 px-6 py-10">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-semibold">Membres</h1>
      <button v-if="canCreate" @click="createOpen = true" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500">+ Créer membre</button>
    </div>

    <div class="flex gap-2 mb-4">
      <input v-model="q" @keyup.enter="applyFilters" placeholder="Recherche (nom, prénom, numéro)"
             class="bg-gray-900 rounded-lg px-3 py-2 w-64 ring-1 ring-white/10" />
      <select v-model="statut" @change="applyFilters" class="bg-gray-900 rounded-lg px-3 py-2 ring-1 ring-white/10">
        <option value="">Tous statuts</option>
        <option value="actif">actif</option>
        <option value="suspendu">suspendu</option>
        <option value="inactif">inactif</option>
      </select>
      <select v-model="belt" @change="applyFilters" class="bg-gray-900 rounded-lg px-3 py-2 ring-1 ring-white/10">
        <option value="">Toutes ceintures</option>
        <option v-for="b in belts" :key="b.id" :value="b.id">{{ b.nom }}</option>
      </select>
    </div>

    <div class="overflow-x-auto rounded-xl ring-1 ring-white/10">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-900/60">
          <tr>
            <th class="text-left px-4 py-3">Numéro</th>
            <th class="text-left px-4 py-3">Nom</th>
            <th class="text-left px-4 py-3">Ceinture</th>
            <th class="text-left px-4 py-3">Statut</th>
            <th class="text-right px-4 py-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="m in members.data" :key="m.id" class="border-t border-white/5">
            <td class="px-4 py-3">{{ m.numero }}</td>
            <td class="px-4 py-3">{{ m.prenom }} {{ m.nom }}</td>
            <td class="px-4 py-3">{{ m.ceinture_actuelle?.nom || '—' }}</td>
            <td class="px-4 py-3">
              <span :class="['badge',
                m.statut==='actif'?'badge--actif':m.statut==='suspendu'?'badge--suspendu':'badge--inactif'
              ]">{{ m.statut }}</span>
            </td>
            <td class="px-4 py-3 text-right">
              <span class="text-gray-400">À venir</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal création -->
    <dialog v-if="createOpen" open class="fixed inset-0 bg-black/60 flex items-center justify-center">
      <div class="bg-gray-900 rounded-2xl w-full max-w-lg p-6 ring-1 ring-white/10">
        <h2 class="text-lg font-semibold mb-4">Créer un membre</h2>
        <form @submit.prevent="form.post('/members', { onSuccess: () => (createOpen=false) })" class="space-y-3">
          <div class="grid grid-cols-2 gap-2">
            <input v-model="form.prenom" placeholder="Prénom" class="bg-gray-950 px-3 py-2 rounded-lg ring-1 ring-white/10">
            <input v-model="form.nom" placeholder="Nom" class="bg-gray-950 px-3 py-2 rounded-lg ring-1 ring-white/10">
          </div>
          <input v-model="form.courriel" placeholder="Courriel" class="w-full bg-gray-950 px-3 py-2 rounded-lg ring-1 ring-white/10">
          <div class="grid grid-cols-2 gap-2">
            <input v-model="form.telephone" placeholder="Téléphone" class="bg-gray-950 px-3 py-2 rounded-lg ring-1 ring-white/10">
            <input v-model="form.adresse" placeholder="Adresse" class="bg-gray-950 px-3 py-2 rounded-lg ring-1 ring-white/10">
          </div>
          <div class="grid grid-cols-2 gap-2">
            <input v-model="form.date_naissance" type="date" class="bg-gray-950 px-3 py-2 rounded-lg ring-1 ring-white/10">
            <input v-model="form.date_inscription" type="date" class="bg-gray-950 px-3 py-2 rounded-lg ring-1 ring-white/10">
          </div>
          <div class="grid grid-cols-2 gap-2">
            <select v-model="form.statut" class="bg-gray-950 px-3 py-2 rounded-lg ring-1 ring-white/10">
              <option value="actif">actif</option>
              <option value="suspendu">suspendu</option>
              <option value="inactif">inactif</option>
            </select>
            <select v-model="form.ceinture_actuelle_id" class="bg-gray-950 px-3 py-2 rounded-lg ring-1 ring-white/10">
              <option :value="null">— Ceinture —</option>
              <option v-for="b in belts" :key="b.id" :value="b.id">{{ b.nom }}</option>
            </select>
          </div>
          <div class="flex justify-end gap-2 pt-2">
            <button type="button" class="px-3 py-2 rounded-lg bg-gray-800" @click="createOpen=false">Annuler</button>
            <button class="px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-500">Créer</button>
          </div>
        </form>
      </div>
    </dialog>
  </div>
</template>
