<template>
  <AuthenticatedLayout>
    <Head title="Nouvel Utilisateur" />
    <div class="max-w-3xl mx-auto p-6">
      <PageHeader title="Créer un utilisateur" subtitle="Ajouter un nouvel accès" />
      <form @submit.prevent="submit" class="mt-8 space-y-6 bg-slate-900/50 border border-slate-700/50 rounded-2xl p-6 backdrop-blur-sm">
        <!-- Identité -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <FormField label="Nom" required>
            <input v-model="form.name" type="text" class="sb-input" placeholder="Nom complet" />
            <FormError :message="form.errors.name" />
          </FormField>
          <FormField label="Email" required>
            <input v-model="form.email" type="email" class="sb-input" placeholder="email@domaine.com" />
            <FormError :message="form.errors.email" />
          </FormField>
        </div>
        <!-- Sécurité -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <FormField label="Mot de passe" required>
            <input v-model="form.password" type="password" class="sb-input" autocomplete="new-password" />
            <FormError :message="form.errors.password" />
          </FormField>
          <FormField label="Confirmation" required>
            <input v-model="form.password_confirmation" type="password" class="sb-input" autocomplete="new-password" />
          </FormField>
        </div>
        <!-- Rôles -->
        <FormField label="Rôles">
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
        <div class="flex justify-end gap-3 pt-4">
          <Link :href="route('utilisateurs.index')" class="sb-btn-secondary">Annuler</Link>
          <button :disabled="form.processing" class="sb-btn-primary">Créer</button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { inject } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import FormField from '@/Components/Forms/FormField.vue'
import FormError from '@/Components/Forms/FormError.vue'

// Injecter la fonction route
const route = inject('route') || window.route

const props = defineProps({ roles: { type: Array, default: () => [] } })
const rolesList = props.roles

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  roles: [],
  email_verified: false,
})

const submit = () => { form.post(route('utilisateurs.store')) }
</script>

<style scoped>
.sb-input { @apply w-full bg-slate-900/60 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent placeholder-slate-500; }
.sb-btn-primary { @apply px-6 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-medium shadow disabled:opacity-50 disabled:cursor-not-allowed transition; }
.sb-btn-secondary { @apply px-6 py-2.5 rounded-xl bg-slate-800/60 hover:bg-slate-700/60 text-slate-200 font-medium transition; }
</style>
