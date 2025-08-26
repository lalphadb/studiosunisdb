<template>
  <Head title="Nouveau Membre" />
  <AuthenticatedLayout>
    <div class="p-6 space-y-8">
      <PageHeader title="Nouveau membre" description="Inscription d'un nouveau membre à l'école.">
        <template #icon>
          <div class="w-12 h-12 rounded-xl bg-slate-800/70 border border-slate-700 flex items-center justify-center">
            <svg class="w-7 h-7 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
          </div>
        </template>
        <template #actions>
          <Link :href="route('membres.index')" class="px-4 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-sm font-medium text-slate-200 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Retour
          </Link>
        </template>
      </PageHeader>

      <form @submit.prevent="submit" class="space-y-8">
        <!-- Section Informations personnelles -->
        <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 p-6">
          <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-3">
            <div class="w-10 h-10 bg-slate-800/70 border border-slate-700 rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </div>
            Informations personnelles
          </h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Prénom -->
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">
                Prénom <span class="text-red-400">*</span>
              </label>
              <input
                v-model="form.prenom"
                type="text"
                class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-slate-500 transition-all"
                placeholder="Jean"
                required
              />
              <p v-if="form.errors.prenom" class="mt-1 text-sm text-red-400">{{ form.errors.prenom }}</p>
            </div>

            <!-- Nom -->
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">
                Nom <span class="text-red-400">*</span>
              </label>
              <input
                v-model="form.nom"
                type="text"
                class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-slate-500 transition-all"
                placeholder="Dupont"
                required
              />
              <p v-if="form.errors.nom" class="mt-1 text-sm text-red-400">{{ form.errors.nom }}</p>
            </div>

            <!-- Date de naissance -->
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">
                Date de naissance <span class="text-red-400">*</span>
              </label>
              <input
                v-model="form.date_naissance"
                type="date"
                class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                required
              />
              <p v-if="form.errors.date_naissance" class="mt-1 text-sm text-red-400">{{ form.errors.date_naissance }}</p>
            </div>

            <!-- Sexe -->
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">
                Sexe <span class="text-red-400">*</span>
              </label>
              <select
                v-model="form.sexe"
                class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                required
              >
                <option value="">Sélectionner...</option>
                <option value="M">Masculin</option>
                <option value="F">Féminin</option>
                <option value="Autre">Autre</option>
              </select>
              <p v-if="form.errors.sexe" class="mt-1 text-sm text-red-400">{{ form.errors.sexe }}</p>
            </div>

            <!-- Email -->
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">
                Courriel <span class="text-red-400">*</span>
              </label>
              <input
                v-model="form.email"
                type="email"
                class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-slate-500 transition-all"
                placeholder="jean.dupont@email.com"
                required
              />
              <p v-if="form.errors.email" class="mt-1 text-sm text-red-400">{{ form.errors.email }}</p>
            </div>

            <!-- Téléphone -->
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">
                Téléphone
              </label>
              <input
                v-model="form.telephone"
                type="tel"
                class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-slate-500 transition-all"
                placeholder="(514) 555-0123"
              />
              <p v-if="form.errors.telephone" class="mt-1 text-sm text-red-400">{{ form.errors.telephone }}</p>
            </div>

            <!-- Ceinture actuelle -->
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">
                Ceinture actuelle
              </label>
              <select
                v-model="form.ceinture_actuelle_id"
                class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
              >
                <option value="">Sélectionner une ceinture...</option>
                <option v-for="ceinture in ceintures" :key="ceinture.id" :value="ceinture.id">
                  {{ ceinture.name_fr }}
                </option>
              </select>
            </div>

            <!-- Statut -->
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">
                Statut <span class="text-red-400">*</span>
              </label>
              <select
                v-model="form.statut"
                class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                required
              >
                <option value="actif">Actif</option>
                <option value="inactif">Inactif</option>
                <option value="suspendu">Suspendu</option>
              </select>
              <p v-if="form.errors.statut" class="mt-1 text-sm text-red-400">{{ form.errors.statut }}</p>
            </div>
          </div>
        </div>

        <!-- Section Adresse -->
        <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 p-6">
          <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-3">
            <div class="w-10 h-10 bg-slate-800/70 border border-slate-700 rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </div>
            Adresse
          </h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Adresse -->
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">
                Adresse
              </label>
              <input
                v-model="form.adresse"
                type="text"
                class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-slate-500 transition-all"
                placeholder="123 Rue Example"
              />
            </div>

            <!-- Ville -->
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">
                Ville
              </label>
              <input
                v-model="form.ville"
                type="text"
                class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-slate-500 transition-all"
                placeholder="Montréal"
              />
            </div>

            <!-- Code postal -->
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">
                Code postal
              </label>
              <input
                v-model="form.code_postal"
                type="text"
                class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-slate-500 transition-all"
                placeholder="H1H 1H1"
                maxlength="7"
              />
            </div>

            <!-- Province -->
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">
                Province
              </label>
              <select
                v-model="form.province"
                class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
              >
                <option value="">Sélectionner...</option>
                <option value="QC">Québec</option>
                <option value="ON">Ontario</option>
                <option value="NB">Nouveau-Brunswick</option>
                <option value="NS">Nouvelle-Écosse</option>
                <option value="PE">Île-du-Prince-Édouard</option>
                <option value="NL">Terre-Neuve-et-Labrador</option>
                <option value="MB">Manitoba</option>
                <option value="SK">Saskatchewan</option>
                <option value="AB">Alberta</option>
                <option value="BC">Colombie-Britannique</option>
                <option value="YT">Yukon</option>
                <option value="NT">Territoires du Nord-Ouest</option>
                <option value="NU">Nunavut</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Section Contact d'urgence -->
        <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 p-6">
          <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-3">
            <div class="w-10 h-10 bg-slate-800/70 border border-slate-700 rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
            </div>
            Contact d'urgence
          </h2>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Nom complet -->
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">
                Nom complet
              </label>
              <input
                v-model="form.contact_urgence_nom"
                type="text"
                class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-slate-500 transition-all"
                placeholder="Marie Dupont"
              />
            </div>

            <!-- Téléphone -->
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">
                Téléphone
              </label>
              <input
                v-model="form.contact_urgence_telephone"
                type="tel"
                class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-slate-500 transition-all"
                placeholder="(514) 555-9999"
              />
            </div>

            <!-- Relation -->
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">
                Relation
              </label>
              <select
                v-model="form.contact_urgence_relation"
                class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
              >
                <option value="">Sélectionner...</option>
                <option value="parent">Parent</option>
                <option value="conjoint">Conjoint(e)</option>
                <option value="frere_soeur">Frère/Sœur</option>
                <option value="ami">Ami(e)</option>
                <option value="autre">Autre</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Section Consentements -->
        <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 p-6">
          <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-3">
            <div class="w-10 h-10 bg-slate-800/70 border border-slate-700 rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
            </div>
            Consentements (Loi 25)
          </h2>
          
          <div class="space-y-4">
            <!-- Consentement photos -->
            <label class="flex items-start gap-3 p-4 bg-slate-900/30 rounded-xl border border-slate-700/30 hover:border-blue-500/50 transition-all cursor-pointer">
              <input
                v-model="form.consentement_photos"
                type="checkbox"
                class="mt-1 w-5 h-5 text-blue-600 bg-slate-800 border-slate-600 rounded focus:ring-blue-500 focus:ring-2"
              />
              <div>
                <p class="text-white font-medium">Autorisation de photos et vidéos</p>
                <p class="text-sm text-slate-400 mt-1">
                  J'autorise l'école à prendre et utiliser des photos/vidéos à des fins promotionnelles et pédagogiques.
                </p>
              </div>
            </label>

            <!-- Consentement communications -->
            <label class="flex items-start gap-3 p-4 bg-slate-900/30 rounded-xl border border-slate-700/30 hover:border-blue-500/50 transition-all cursor-pointer">
              <input
                v-model="form.consentement_communications"
                type="checkbox"
                class="mt-1 w-5 h-5 text-blue-600 bg-slate-800 border-slate-600 rounded focus:ring-blue-500 focus:ring-2"
              />
              <div>
                <p class="text-white font-medium">Communications marketing</p>
                <p class="text-sm text-slate-400 mt-1">
                  J'accepte de recevoir des communications sur les événements, promotions et nouvelles de l'école.
                </p>
              </div>
            </label>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3">
          <Link :href="route('membres.index')" class="px-4 py-2 rounded-lg bg-slate-800/70 hover:bg-slate-700 text-slate-200 text-sm font-medium border border-slate-700 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            Annuler
          </Link>
          <button type="submit" :disabled="form.processing" class="px-5 py-2 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-400 hover:to-purple-500 text-white text-sm font-medium shadow disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
            <svg v-if="!form.processing" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            <svg v-else class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
            {{ form.processing ? 'Création...' : 'Créer le membre' }}
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'

interface Ceinture { id: number; name_fr: string }
const props = defineProps<{ ceintures: Ceinture[] }>()

const form = useForm({
  prenom: '',
  nom: '',
  email: '',
  telephone: '',
  date_naissance: '',
  sexe: '',
  adresse: '',
  ville: '',
  code_postal: '',
  province: 'QC',
  contact_urgence_nom: '',
  contact_urgence_telephone: '',
  contact_urgence_relation: '',
  ceinture_actuelle_id: null as number | null,
  statut: 'actif',
  consentement_photos: false,
  consentement_communications: false
})

function submit() {
  form.post(route('membres.store'), { preserveScroll: true })
}
</script>
