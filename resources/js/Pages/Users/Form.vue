<template>
  <AuthenticatedLayout>
    <Head :title="user ? 'Modifier utilisateur' : 'Nouvel utilisateur'" />
    
    <div class="min-h-screen max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <!-- Header -->
      <div class="mb-6">
        <div class="flex items-center justify-between">
          <h1 class="text-xl sm:text-2xl xl:text-3xl font-bold text-white">
            {{ user ? 'Modifier l\'utilisateur' : 'Nouvel utilisateur' }}
          </h1>
          <Link
            :href="user ? `/users/${user.id}` : '/users'"
            class="px-4 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-white text-sm font-medium transition-all flex items-center gap-2"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour
          </Link>
        </div>
      </div>

      <!-- Formulaire -->
      <form @submit.prevent="submitForm" class="space-y-6">
        <!-- Informations personnelles -->
        <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6">
          <h2 class="text-lg font-semibold text-white mb-4">Informations personnelles</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">Prénom *</label>
              <input
                v-model="form.prenom"
                type="text"
                required
                class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">Nom *</label>
              <input
                v-model="form.nom"
                type="text"
                required
                class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">Email *</label>
              <input
                v-model="form.email"
                type="email"
                required
                class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">Téléphone</label>
              <input
                v-model="form.telephone"
                type="tel"
                class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">Date de naissance</label>
              <input
                v-model="form.date_naissance"
                type="date"
                class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">Sexe</label>
              <select
                v-model="form.sexe"
                class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="">Sélectionner</option>
                <option value="M">Masculin</option>
                <option value="F">Féminin</option>
                <option value="Autre">Autre</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Compte et Accès -->
        <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6">
          <h2 class="text-lg font-semibold text-white mb-4">Compte et Accès</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">Rôle *</label>
              <select
                v-model="form.role"
                required
                class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="">Sélectionner un rôle</option>
                <option v-for="role in roles" :key="role" :value="role">
                  {{ formatRole(role) }}
                </option>
              </select>
            </div>
            
            <div v-if="form.role === 'membre'">
              <label class="block text-sm font-medium text-slate-400 mb-2">Ceinture</label>
              <select
                v-model="form.ceinture_actuelle_id"
                class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="">Aucune</option>
                <option v-for="ceinture in ceintures" :key="ceinture.id" :value="ceinture.id">
                  {{ ceinture.name }}
                </option>
              </select>
            </div>
            
            <div v-if="!user">
              <label class="block text-sm font-medium text-slate-400 mb-2">Mot de passe *</label>
              <input
                v-model="form.password"
                type="password"
                required
                minlength="8"
                class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              />
            </div>
            
            <div v-if="!user">
              <label class="block text-sm font-medium text-slate-400 mb-2">Confirmer le mot de passe *</label>
              <input
                v-model="form.password_confirmation"
                type="password"
                required
                minlength="8"
                class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              />
            </div>
            
            <div v-if="user">
              <label class="block text-sm font-medium text-slate-400 mb-2">Statut</label>
              <select
                v-model="form.statut"
                class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="actif">Actif</option>
                <option value="inactif">Inactif</option>
                <option value="suspendu">Suspendu</option>
              </select>
            </div>
            
            <div v-if="user" class="flex items-center">
              <label class="flex items-center cursor-pointer">
                <input
                  v-model="form.active"
                  type="checkbox"
                  class="mr-2 w-4 h-4 text-blue-600 bg-slate-900 border-slate-700 rounded focus:ring-blue-500"
                />
                <span class="text-sm text-slate-300">Compte actif</span>
              </label>
            </div>
          </div>
        </div>

        <!-- Adresse -->
        <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6">
          <h2 class="text-lg font-semibold text-white mb-4">Adresse</h2>
          
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">Adresse</label>
              <input
                v-model="form.adresse"
                type="text"
                class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              />
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Ville</label>
                <input
                  v-model="form.ville"
                  type="text"
                  class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
              </div>
              
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Code postal</label>
                <input
                  v-model="form.code_postal"
                  type="text"
                  class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
              </div>
              
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Province</label>
                <select
                  v-model="form.province"
                  class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                  <option value="QC">Québec</option>
                  <option value="ON">Ontario</option>
                  <option value="NB">Nouveau-Brunswick</option>
                  <option value="NS">Nouvelle-Écosse</option>
                  <option value="BC">Colombie-Britannique</option>
                  <option value="AB">Alberta</option>
                  <option value="MB">Manitoba</option>
                  <option value="SK">Saskatchewan</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3">
          <Link
            href="/users"
            class="px-6 py-2.5 rounded-lg bg-slate-700 hover:bg-slate-600 text-white font-medium transition-all"
          >
            Annuler
          </Link>
          <button
            type="submit"
            :disabled="processing"
            class="px-6 py-2.5 rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium transition-all disabled:opacity-50"
          >
            {{ processing ? 'En cours...' : (user ? 'Mettre à jour' : 'Créer') }}
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  user: {
    type: Object,
    default: null
  },
  roles: {
    type: Array,
    default: () => []
  },
  ceintures: {
    type: Array,
    default: () => []
  },
  ecoles: {
    type: Array,
    default: () => []
  }
})

const processing = ref(false)

const form = reactive({
  prenom: '',
  nom: '',
  email: '',
  telephone: '',
  date_naissance: '',
  sexe: '',
  role: '',
  ceinture_actuelle_id: '',
  password: '',
  password_confirmation: '',
  adresse: '',
  ville: '',
  code_postal: '',
  province: 'QC',
  statut: 'actif',
  active: true,
  contact_urgence_nom: '',
  contact_urgence_telephone: '',
  contact_urgence_relation: '',
  notes_medicales: '',
  consentement_photos: false,
  consentement_communications: true
})

onMounted(() => {
  if (props.user) {
    // Pré-remplir le formulaire avec les données existantes
    Object.keys(form).forEach(key => {
      if (props.user[key] !== undefined && key !== 'password' && key !== 'password_confirmation') {
        form[key] = props.user[key]
      }
    })
    
    // Gérer le rôle
    if (props.user.roles && props.user.roles.length > 0) {
      form.role = props.user.roles[0].name
    }
  }
})

const formatRole = (role) => {
  const roleLabels = {
    'superadmin': 'Super Admin',
    'admin_ecole': 'Administrateur',
    'instructeur': 'Instructeur',
    'membre': 'Membre'
  }
  return roleLabels[role] || role
}

const submitForm = () => {
  processing.value = true
  
  if (props.user) {
    // Mise à jour
    router.put(`/users/${props.user.id}`, form, {
      onFinish: () => {
        processing.value = false
      }
    })
  } else {
    // Création
    router.post('/users', form, {
      onFinish: () => {
        processing.value = false
      }
    })
  }
}
</script>
