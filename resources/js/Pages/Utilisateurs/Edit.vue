<template>
  <AuthenticatedLayout>
    <Head :title="`Modifier ${form.name}`" />
    <div class="max-w-3xl mx-auto p-6">
      <PageHeader :title="`Modifier ${form.name}`" subtitle="Mettre à jour les informations" />
      <form @submit.prevent="submit" class="mt-8 space-y-6 bg-slate-900/50 border border-slate-700/50 rounded-2xl p-6 backdrop-blur-sm">
        <!-- Identité -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <FormField label="Nom" required>
            <input v-model="form.name" type="text" class="sb-input" />
            <FormError :message="form.errors.name" />
          </FormField>
          <FormField label="Email" required>
            <input v-model="form.email" type="email" class="sb-input" />
            <FormError :message="form.errors.email" />
          </FormField>
        </div>

        <!-- Mot de passe -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <FormField label="Nouveau mot de passe">
            <input v-model="form.password" type="password" class="sb-input" autocomplete="new-password" />
            <FormError :message="form.errors.password" />
          </FormField>
          <FormField label="Confirmation">
            <input v-model="form.password_confirmation" type="password" class="sb-input" autocomplete="new-password" />
          </FormField>
        </div>

        <!-- Rôles -->
        <FormField label="Rôles" v-if="can.manageRoles">
          <div class="mb-2 text-xs text-slate-400">
            Cochez les rôles à assigner. Le rôle "membre" est attribué par défaut.
          </div>
          <div class="grid grid-cols-2 gap-2">
            <label v-for="r in rolesList" :key="r" 
                   class="flex items-center gap-2 px-3 py-2 rounded-xl border transition-all cursor-pointer"
                   :class="form.roles.includes(r) 
                     ? 'bg-indigo-500/20 border-indigo-500/50 text-indigo-200' 
                     : 'bg-slate-800/60 border-slate-700/60 text-slate-200 hover:bg-slate-700/60'">
              <input type="checkbox" :value="r" v-model="form.roles" class="accent-indigo-600" />
              <span class="text-sm font-medium">{{ r }}</span>
              <span v-if="r === 'instructeur'" class="ml-auto text-xs px-1.5 py-0.5 bg-purple-500/20 text-purple-300 rounded-full">Staff</span>
              <span v-if="r === 'admin_ecole'" class="ml-auto text-xs px-1.5 py-0.5 bg-amber-500/20 text-amber-300 rounded-full">Admin</span>
              <span v-if="r === 'superadmin'" class="ml-auto text-xs px-1.5 py-0.5 bg-red-500/20 text-red-300 rounded-full">Super</span>
            </label>
          </div>
          <FormError :message="form.errors.roles" />
        </FormField>

        <!-- Statuts -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="flex items-center gap-3">
            <label class="inline-flex items-center gap-2 text-sm text-slate-300">
              <input type="checkbox" v-model="form.email_verified" class="accent-indigo-600" />
              Email vérifié
            </label>
          </div>
          <div class="flex items-center gap-3">
            <label class="inline-flex items-center gap-2 text-sm text-slate-300">
              <input type="checkbox" v-model="form.active" class="accent-green-600" />
              Compte actif
            </label>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-between items-center pt-4">
          <Link :href="route('utilisateurs.index')" class="sb-btn-secondary">Retour</Link>
          <div class="flex gap-3">
            <button v-if="can.delete" type="button" @click="disableUser" class="px-5 py-2.5 rounded-xl bg-red-600/80 hover:bg-red-600 text-white font-medium shadow">Désactiver</button>
            <button :disabled="form.processing" class="sb-btn-primary">Enregistrer</button>
          </div>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { inject } from 'vue'
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import FormField from '@/Components/Forms/FormField.vue'
import FormError from '@/Components/Forms/FormError.vue'

// Injecter la fonction route
const route = inject('route') || window.route

const props = defineProps({
  user: { type: Object, required: true },
  roles: { type: Array, default: () => [] },
  can: { type: Object, default: () => ({ manageRoles:false, delete:false }) }
})

// Liste des rôles disponibles (passés depuis le controller)
const rolesList = props.roles || []

// Rôles actuellement assignés à l'utilisateur
const currentUserRoles = props.user.roles?.map(r => r.name) || []

const form = useForm({
  name: props.user.name || '',
  email: props.user.email || '',
  password: '',
  password_confirmation: '',
  roles: currentUserRoles, // Utiliser les rôles actuels
  email_verified: !!props.user.email_verified_at,
  active: props.user.active !== undefined ? props.user.active : true,
})

const submit = () => {
  form.put(route('utilisateurs.update', props.user.id))
}

const disableUser = () => {
  if (confirm('Désactiver cet utilisateur ?')) {
    router.delete(route('utilisateurs.destroy', props.user.id))
  }
}
</script>

<style scoped>
.sb-input { @apply w-full bg-slate-900/60 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent placeholder-slate-500; }
.sb-btn-primary { @apply px-6 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-medium shadow disabled:opacity-50 disabled:cursor-not-allowed transition; }
.sb-btn-secondary { @apply px-6 py-2.5 rounded-xl bg-slate-800/60 hover:bg-slate-700/60 text-slate-200 font-medium transition; }
</style>
