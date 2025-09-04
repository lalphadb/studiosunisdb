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
          <div class="flex flex-wrap gap-2">
            <label v-for="r in rolesList" :key="r" class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-800/60 border border-slate-700/60 text-slate-200 text-sm cursor-pointer hover:bg-slate-700/60">
              <input type="checkbox" :value="r" v-model="form.roles" class="accent-indigo-600" />
              <span>{{ r }}</span>
            </label>
          </div>
          <FormError :message="form.errors.roles" />
        </FormField>

        <!-- Email vérifié -->
        <div class="flex items-center gap-3">
          <label class="inline-flex items-center gap-2 text-sm text-slate-300">
            <input type="checkbox" v-model="form.email_verified" class="accent-indigo-600" />
            Email vérifié
          </label>
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
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import FormField from '@/Components/Forms/FormField.vue'
import FormError from '@/Components/Forms/FormError.vue'

const props = defineProps({
  user: { type: Object, required: true },
  roles: { type: Array, default: () => [] },
  can: { type: Object, default: () => ({ manageRoles:false, delete:false }) }
})

const rolesList = props.roles.length ? props.roles : (props.user.roles?.map(r=>r.name) || [])

const form = useForm({
  name: props.user.name || '',
  email: props.user.email || '',
  password: '',
  password_confirmation: '',
  roles: props.user.roles?.map(r => r.name) || rolesList,
  email_verified: !!props.user.email_verified_at,
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
