<template>
  <AuthenticatedLayout>
    <div class="p-6 space-y-8 max-w-5xl mx-auto">
      <PageHeader :title="`Modifier ${user.nom_complet}`" description="Mise à jour des informations du user.">
        <template #icon>
          <div class="w-12 h-12 rounded-xl bg-slate-800/70 border border-slate-700 flex items-center justify-center">
            <svg class="w-7 h-7 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11 18l-4 1 1-4 9.586-9.586z" /></svg>
          </div>
        </template>
        <template #actions>
          <Link :href="`/membres/${user.id}`" class="px-4 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-sm font-medium text-slate-200 transition">Profil</Link>
          <Link href="/membres" class="px-4 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-sm font-medium text-slate-200 transition">Liste</Link>
        </template>
      </PageHeader>

      <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 p-6">
        <p class="mb-6 text-sm text-slate-300">Modification du profil de <strong class="text-slate-200">{{ user.nom_complet }}</strong>. Les changements seront sauvegardés immédiatement.</p>
        <form @submit.prevent="submit" class="space-y-10">
          <!-- Informations personnelles -->
          <section>
            <h3 class="text-lg font-semibold text-slate-200 mb-4 flex items-center gap-2">
              <span class="w-8 h-8 rounded-lg bg-slate-800/70 border border-slate-700 flex items-center justify-center text-indigo-300">👤</span>
              Informations personnelles
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <FormField label="Prénom" required :error="errors.prenom">
                <input v-model="form.prenom" type="text" class="field" required />
              </FormField>
              <FormField label="Nom" required :error="errors.nom">
                <input v-model="form.nom" type="text" class="field" required />
              </FormField>
              <FormField label="Date de naissance" required :error="errors.date_naissance">
                <input v-model="form.date_naissance" type="date" class="field" required />
              </FormField>
              <FormField label="Sexe" required :error="errors.sexe">
                <select v-model="form.sexe" class="field" required>
                  <option value="">Sélectionner...</option>
                  <option value="M">Masculin</option>
                  <option value="F">Féminin</option>
                  <option value="Autre">Autre</option>
                </select>
              </FormField>
              <FormField label="Téléphone">
                <input v-model="form.telephone" type="tel" class="field" placeholder="(514) 555-0123" />
              </FormField>
              <FormField label="Statut" required :error="errors.statut">
                <select v-model="form.statut" class="field" required>
                  <option value="actif">Actif</option>
                  <option value="inactif">Inactif</option>
                  <option value="suspendu">Suspendu</option>
                  <option value="diplome">Diplômé</option>
                </select>
              </FormField>
            </div>
          </section>

          <section>
            <h3 class="text-lg font-semibold text-slate-200 mb-4 flex items-center gap-2">
              <span class="w-8 h-8 rounded-lg bg-slate-800/70 border border-slate-700 flex items-center justify-center text-indigo-300">🥋</span>
              Progression martiale
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
              <FormField label="Ceinture actuelle" required :error="errors.ceinture_actuelle_id">
                <select v-model="form.ceinture_actuelle_id" class="field" required>
                  <option value="">Sélectionner une ceinture...</option>
                  <option v-for="c in ceintures" :key="c.id" :value="c.id">{{ c.nom }}</option>
                </select>
              </FormField>
              <div class="text-xs text-slate-400 space-y-1">
                <p>Membre depuis : <span class="text-slate-300">{{ formatDate(user.date_inscription) }}</span></p>
                <p v-if="user.date_derniere_presence">Dernière présence : <span class="text-slate-300">{{ formatDate(user.date_derniere_presence) }}</span></p>
              </div>
            </div>
          </section>

          <section>
            <h3 class="text-lg font-semibold text-slate-200 mb-4 flex items-center gap-2">
              <span class="w-8 h-8 rounded-lg bg-slate-800/70 border border-slate-700 flex items-center justify-center text-indigo-300">🏠</span>
              Adresse
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <FormField class="md:col-span-2" label="Adresse">
                <textarea v-model="form.adresse" rows="2" class="field resize-none" placeholder="123 Rue Example" />
              </FormField>
              <FormField label="Ville">
                <input v-model="form.ville" type="text" class="field" placeholder="Montréal" />
              </FormField>
              <FormField label="Code postal" :error="errors.code_postal">
                <input v-model="form.code_postal" type="text" class="field" placeholder="H1H 1H1" pattern="[A-Za-z]\d[A-Za-z][\s\-]?\d[A-Za-z]\d" />
              </FormField>
            </div>
          </section>

          <section>
            <h3 class="text-lg font-semibold text-slate-200 mb-4 flex items-center gap-2">
              <span class="w-8 h-8 rounded-lg bg-slate-800/70 border border-slate-700 flex items-center justify-center text-indigo-300">⚠️</span>
              Contact d'urgence
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <FormField label="Nom complet" required :error="errors.contact_urgence_nom">
                <input v-model="form.contact_urgence_nom" type="text" class="field" required />
              </FormField>
              <FormField label="Téléphone" required :error="errors.contact_urgence_telephone">
                <input v-model="form.contact_urgence_telephone" type="tel" class="field" required />
              </FormField>
              <FormField label="Relation">
                <select v-model="form.contact_urgence_relation" class="field">
                  <option value="">Sélectionner...</option>
                  <option value="Parent">Parent</option>
                  <option value="Conjoint">Conjoint(e)</option>
                  <option value="Frère/Sœur">Frère/Sœur</option>
                  <option value="Ami">Ami(e)</option>
                  <option value="Autre">Autre</option>
                </select>
              </FormField>
            </div>
          </section>

          <section>
            <h3 class="text-lg font-semibold text-slate-200 mb-4 flex items-center gap-2">
              <span class="w-8 h-8 rounded-lg bg-slate-800/70 border border-slate-700 flex items-center justify-center text-indigo-300">🏥</span>
              Informations médicales
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <FormField label="Notes médicales">
                <textarea v-model="form.notes_medicales" rows="3" class="field resize-none" placeholder="Conditions médicales, limitations, recommandations..." />
              </FormField>
              <FormField label="Allergies">
                <input v-model="allergiesText" @input="updateAllergies" type="text" class="field" placeholder="Arachides, lactose, pollen..." />
              </FormField>
            </div>
          </section>

            <section>
            <h3 class="text-lg font-semibold text-slate-200 mb-4 flex items-center gap-2">
              <span class="w-8 h-8 rounded-lg bg-slate-800/70 border border-slate-700 flex items-center justify-center text-indigo-300">🗒️</span>
              Notes administratives
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <FormField label="Notes instructeur">
                <textarea v-model="form.notes_instructeur" rows="3" class="field resize-none" placeholder="Notes pédagogiques, progression technique, comportement..." />
              </FormField>
              <FormField label="Notes administratives">
                <textarea v-model="form.notes_admin" rows="3" class="field resize-none" placeholder="Notes internes..." />
              </FormField>
            </div>
          </section>

          <section>
            <h3 class="text-lg font-semibold text-slate-200 mb-4 flex items-center gap-2">
              <span class="w-8 h-8 rounded-lg bg-slate-800/70 border border-slate-700 flex items-center justify-center text-indigo-300">🛡️</span>
              Consentements
            </h3>
            <div class="space-y-4">
              <label class="flex items-start gap-3 p-4 rounded-xl bg-slate-800/40 border border-slate-700/40 cursor-pointer hover:bg-slate-800/60 transition">
                <input v-model="form.consentement_communications" type="checkbox" class="mt-1 w-5 h-5 text-indigo-500 bg-slate-900 border-slate-600 rounded focus:ring-indigo-500" />
                <span class="text-sm text-slate-300">Accepte de recevoir des communications par courriel ou SMS</span>
              </label>
              <label class="flex items-start gap-3 p-4 rounded-xl bg-slate-800/40 border border-slate-700/40 cursor-pointer hover:bg-slate-800/60 transition">
                <input v-model="form.consentement_photos" type="checkbox" class="mt-1 w-5 h-5 text-indigo-500 bg-slate-900 border-slate-600 rounded focus:ring-indigo-500" />
                <span class="text-sm text-slate-300">Autorise l'utilisation de photos/vidéos pour les activités du dojo</span>
              </label>
            </div>
          </section>

          <!-- NOUVELLE SECTION: Accès système et rôles -->
          <section>
            <h3 class="text-lg font-semibold text-slate-200 mb-4 flex items-center gap-2">
              <span class="w-8 h-8 rounded-lg bg-slate-800/70 border border-slate-700 flex items-center justify-center text-indigo-300">🔐</span>
              Accès système et permissions
            </h3>
            
            <!-- Toggle accès système -->
            <div class="mb-6">
              <label class="flex items-center gap-3 p-4 rounded-xl bg-slate-800/40 border border-slate-700/40 cursor-pointer hover:bg-slate-800/60 transition">
                <input 
                  v-model="form.has_system_access" 
                  type="checkbox" 
                  class="w-5 h-5 text-indigo-500 bg-slate-900 border-slate-600 rounded focus:ring-indigo-500" 
                />
                <div>
                  <div class="text-sm font-medium text-slate-200">Autoriser l'accès au système StudiosDB</div>
                  <div class="text-xs text-slate-400">Active un compte de connexion pour ce membre</div>
                </div>
              </label>
            </div>

            <!-- Détails du compte (si accès activé) -->
            <div v-if="form.has_system_access" class="space-y-6 border-t border-slate-700/50 pt-6">
              
              <!-- Email et mot de passe -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <FormField label="Email de connexion" :error="errors.user_email">
                  <input 
                    v-model="form.user_email" 
                    type="email" 
                    class="field"
                    placeholder="email@exemple.com"
                  />
                </FormField>
                
                <FormField :label="user.user ? 'Nouveau mot de passe' : 'Mot de passe'" :error="errors.user_password">
                  <input 
                    v-model="form.user_password" 
                    type="password" 
                    class="field"
                    :placeholder="user.user ? 'Laisser vide pour conserver' : 'Minimum 8 caractères'"
                  />
                </FormField>
              </div>

              <!-- Niveau d'accès / Rôles -->
              <div>
                <label class="block text-xs font-medium uppercase tracking-wide text-slate-400 mb-3">Niveau d'accès</label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                  
                  <!-- Membre -->
                  <label class="flex items-center gap-3 p-4 rounded-xl border cursor-pointer transition-all"
                         :class="form.user_roles.includes('membre') 
                           ? 'bg-green-500/20 border-green-500/50 text-green-200'
                           : 'bg-slate-800/60 border-slate-700/60 text-slate-300 hover:bg-slate-700/60'">
                    <input 
                      type="checkbox" 
                      value="membre" 
                      v-model="form.user_roles"
                      class="w-4 h-4 text-green-500 bg-slate-900 border-slate-600 rounded focus:ring-green-500" 
                    />
                    <div>
                      <div class="font-medium text-sm">Membre</div>
                      <div class="text-xs opacity-75">Consulter ses cours, paiements</div>
                    </div>
                  </label>

                  <!-- Instructeur -->
                  <label class="flex items-center gap-3 p-4 rounded-xl border cursor-pointer transition-all"
                         :class="form.user_roles.includes('instructeur') 
                           ? 'bg-purple-500/20 border-purple-500/50 text-purple-200'
                           : 'bg-slate-800/60 border-slate-700/60 text-slate-300 hover:bg-slate-700/60'">
                    <input 
                      type="checkbox" 
                      value="instructeur" 
                      v-model="form.user_roles"
                      class="w-4 h-4 text-purple-500 bg-slate-900 border-slate-600 rounded focus:ring-purple-500" 
                    />
                    <div>
                      <div class="font-medium text-sm">Instructeur</div>
                      <div class="text-xs opacity-75">Enseigner + gérer présences</div>
                    </div>
                  </label>

                  <!-- Admin école -->
                  <label class="flex items-center gap-3 p-4 rounded-xl border cursor-pointer transition-all"
                         :class="form.user_roles.includes('admin_ecole') 
                           ? 'bg-amber-500/20 border-amber-500/50 text-amber-200'
                           : 'bg-slate-800/60 border-slate-700/60 text-slate-300 hover:bg-slate-700/60'">
                    <input 
                      type="checkbox" 
                      value="admin_ecole" 
                      v-model="form.user_roles"
                      class="w-4 h-4 text-amber-500 bg-slate-900 border-slate-600 rounded focus:ring-amber-500" 
                    />
                    <div>
                      <div class="font-medium text-sm">Admin école</div>
                      <div class="text-xs opacity-75">Gestion complète</div>
                    </div>
                  </label>
                </div>
              </div>

              <!-- Statut du compte -->
              <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 text-sm text-slate-300">
                  <input 
                    v-model="form.user_active" 
                    type="checkbox" 
                    class="w-4 h-4 text-blue-500 bg-slate-900 border-slate-600 rounded focus:ring-blue-500" 
                  />
                  Compte actif
                </label>
                
                <label class="flex items-center gap-2 text-sm text-slate-300">
                  <input 
                    v-model="form.user_email_verified" 
                    type="checkbox" 
                    class="w-4 h-4 text-green-500 bg-slate-900 border-slate-600 rounded focus:ring-green-500" 
                  />
                  Email vérifié
                </label>
              </div>

              <!-- Info compte existant -->
              <div v-if="user.user" class="bg-slate-900/50 rounded-lg p-3">
                <div class="text-xs text-slate-400">
                  Compte créé le {{ formatDate(user.user?.created_at) }}
                  <span v-if="user.user?.last_login_at">
                    • Dernière connexion : {{ formatDate(user.user.last_login_at) }}
                  </span>
                </div>
              </div>
            </div>
          </section>

          <div class="flex items-center justify-between pt-4 border-t border-slate-700/50">
            <button type="button" @click="confirmerSuppression" class="px-4 py-2 rounded-lg bg-red-600/80 hover:bg-red-600 text-sm font-medium text-white transition">🗑️ Supprimer</button>
            <div class="flex gap-3">
              <Link :href="`/membres/${user.id}`" class="px-4 py-2 rounded-lg bg-slate-800/70 hover:bg-slate-700 text-sm font-medium text-slate-200 border border-slate-700">Annuler</Link>
              <button type="submit" :disabled="processing" class="px-5 py-2 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-400 hover:to-purple-500 text-white text-sm font-medium shadow disabled:opacity-50 flex items-center gap-2">
                <span v-if="processing" class="animate-pulse">Sauvegarde...</span>
                <span v-else>💾 Sauvegarder</span>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal confirmation suppression -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-900 text-center mt-2">Confirmer la suppression</h3>
          <div class="mt-4">
            <p class="text-sm text-gray-500 text-center">
              Êtes-vous sûr de vouloir supprimer le membre <strong>{{ user.nom_complet }}</strong> ?
              Cette action est définitive et toutes les données associées seront supprimées.
            </p>
          </div>
          <div class="mt-4 flex justify-center space-x-2">
            <button
              @click="showDeleteModal = false"
              class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded text-sm"
            >
              Annuler
            </button>
            <button
              @click="supprimerMembre"
              class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm"
            >
              🗑️ Supprimer définitivement
            </button>
          </div>
        </div>
      </div>
    </div>

  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { ref, onMounted, defineComponent, h } from 'vue'
import { useForm, router, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'

// Types
interface Ceinture { id: number; nom: string }
interface Membre { id: number; nom_complet: string; prenom: string; nom: string; date_naissance: string; sexe: string; telephone?: string; adresse?: string; ville?: string; code_postal?: string; contact_urgence_nom?: string; contact_urgence_telephone?: string; contact_urgence_relation?: string; statut: string; ceinture_actuelle_id?: number; notes_medicales?: string; allergies?: string[]; notes_instructeur?: string; notes_admin?: string; consentement_photos: boolean; consentement_communications: boolean; date_inscription?: string; date_derniere_presence?: string }

// Props
const props = defineProps<{ membre: Membre; ceintures: Ceinture[]; errors: Record<string, string> }>()
const { membre, ceintures, errors } = props

// Inline lightweight FormField wrapper for consistent label + error styling
const FormField = defineComponent({
  name: 'FormField',
  props: { label: { type: String, required: true }, error: { type: String, default: '' }, required: { type: Boolean, default: false } },
  setup(p, { slots }) {
    return () => h('div', null, [
      h('label', { class: 'block text-xs font-medium uppercase tracking-wide text-slate-400 mb-1' }, p.label + (p.required ? ' *' : '')),
      slots.default ? slots.default() : null,
      p.error ? h('p', { class: 'mt-1 text-xs text-red-400' }, p.error) : null
    ])
  }
})

// State
const showDeleteModal = ref(false)
const allergiesText = ref('')
const processing = ref(false)

// Form
const form = useForm({
  prenom: user.prenom,
  nom: user.nom,
  date_naissance: user.date_naissance,
  sexe: user.sexe,
  telephone: user.telephone || '',
  adresse: user.adresse || '',
  ville: user.ville || '',
  code_postal: user.code_postal || '',
  contact_urgence_nom: user.contact_urgence_nom || '',
  contact_urgence_telephone: user.contact_urgence_telephone || '',
  contact_urgence_relation: user.contact_urgence_relation || '',
  statut: user.statut,
  ceinture_actuelle_id: user.ceinture_actuelle_id || null,
  notes_medicales: user.notes_medicales || '',
  allergies: user.allergies || [],
  notes_instructeur: user.notes_instructeur || '',
  notes_admin: user.notes_admin || '',
  consentement_photos: user.consentement_photos,
  consentement_communications: user.consentement_communications,
  
  // NOUVEAUX CHAMPS: Accès système et rôles
  has_system_access: !!user.user,
  user_email: user.user?.email || user.email || '',
  user_password: '',
  user_roles: user.user?.roles?.map(r => r.name) || [],
  user_active: user.user?.active ?? true,
  user_email_verified: !!user.user?.email_verified_at,
})

onMounted(() => { if (user.allergies?.length) allergiesText.value = user.allergies.join(', ') })

function updateAllergies() { form.allergies = allergiesText.value.split(',').map(a => a.trim()).filter(Boolean) }
function formatDate(date?: string) { return date ? new Date(date).toLocaleDateString('fr-CA') : 'Non spécifié' }
function submit() { 
  processing.value = true
  form.put(`/membres/${user.id}`, {
    preserveScroll: true,
    onFinish: () => (processing.value = false)
  }) 
}
function confirmerSuppression() { showDeleteModal.value = true }
function supprimerMembre() { router.delete(`/membres/${user.id}`, { onSuccess: () => (showDeleteModal.value = false) }) }
</script>

<style scoped>
.field { @apply w-full rounded-lg bg-slate-800/60 border border-slate-700 text-slate-100 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent placeholder-slate-500 transition; }
textarea.field { @apply align-top; }
</style>
