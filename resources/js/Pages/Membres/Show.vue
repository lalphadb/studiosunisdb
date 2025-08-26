<template>
  <AuthenticatedLayout>
    <div class="p-6 space-y-8">
      <PageHeader :title="membre.nom_complet" :description="`Profil détaillé du membre (âge ${calculerAge(membre.date_naissance)} ans).`">
        <template #icon>
          <div class="w-12 h-12 rounded-xl bg-slate-800/70 border border-slate-700 flex items-center justify-center text-indigo-300 text-2xl">👤</div>
        </template>
        <template #actions>
          <Link :href="route('membres.edit', membre.id)" class="px-4 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-sm font-medium text-slate-200 transition">✏️ Modifier</Link>
          <Link :href="route('membres.index')" class="px-4 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-sm font-medium text-slate-200 transition">← Retour</Link>
        </template>
      </PageHeader>
      <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 p-6 flex items-start gap-6">
        <div class="flex-shrink-0">
          <div class="h-24 w-24 rounded-full bg-slate-800 flex items-center justify-center text-2xl font-bold text-indigo-300 shadow">{{ membre.prenom[0] }}{{ membre.nom[0] }}</div>
        </div>
        <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-6">
          <div>
            <h3 class="text-lg font-semibold text-slate-200">{{ membre.nom_complet }}</h3>
            <p class="text-xs text-slate-400">{{ calculerAge(membre.date_naissance) }} ans · {{ membre.sexe === 'M' ? 'Masculin' : membre.sexe === 'F' ? 'Féminin' : 'Autre' }}</p>
            <div class="mt-2">
              <span :class="statusClass(membre.statut)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">{{ membre.statut.charAt(0).toUpperCase() + membre.statut.slice(1) }}</span>
            </div>
          </div>
          <div>
            <h4 class="font-medium text-slate-300">Ceinture actuelle</h4>
            <div class="flex items-center mt-1">
              <div class="w-4 h-4 rounded-full mr-2" :style="`background-color: ${membre.ceinture_actuelle?.couleur_hex || '#666'}`"></div>
              <span class="text-xs text-slate-400">{{ membre.ceinture_actuelle?.nom || 'Aucune' }}</span>
            </div>
            <p class="text-[10px] text-slate-500 mt-1">Inscrit le {{ formatDate(membre.date_inscription) }}</p>
          </div>
          <div>
            <h4 class="font-medium text-slate-300">Contact</h4>
            <p class="text-xs text-slate-400" v-if="membre.telephone">📞 {{ membre.telephone }}</p>
            <p class="text-xs text-slate-400" v-if="membre.ville">📍 {{ membre.ville }}</p>
            <p class="text-[10px] text-slate-500 mt-1" v-if="membre.date_derniere_presence">Dernière présence: {{ formatDate(membre.date_derniere_presence) }}</p>
          </div>
        </div>
      </div>
      <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 p-6">
        <h3 class="text-sm font-semibold text-slate-300 mb-4">Actions rapides</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
          <button @click="openModalCeinture" class="action-btn">🥋<span class="text-xs">Ceinture</span></button>
          <button @click="openModalSeminaire" class="action-btn">🎓<span class="text-xs">Séminaire</span></button>
          <button @click="openModalPaiement" class="action-btn">💳<span class="text-xs">Paiement</span></button>
          <button @click="marquerPresence" class="action-btn">✅<span class="text-xs">Présence</span></button>
        </div>
      </div>

      <!-- Grille info principale -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Progression -->
            <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 p-6">
              <h3 class="text-sm font-semibold text-slate-300 mb-4">🥋 Progression</h3>
              <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-slate-800/40 rounded border border-slate-700/40">
                  <span class="text-xs text-slate-400">Ceinture actuelle</span>
                  <div class="flex items-center text-sm text-slate-200">
                    <div class="w-3 h-3 rounded-full mr-2" :style="`background-color: ${membre.ceinture_actuelle?.couleur_hex || '#666'}`"></div>
                    {{ membre.ceinture_actuelle?.nom || 'Aucune' }}
                  </div>
                </div>
                <div v-if="progression" class="space-y-3">
                  <div class="grid grid-cols-2 gap-3 text-center">
                    <div class="p-3 rounded-lg bg-slate-800/40 border border-slate-700/40">
                      <div class="text-[10px] uppercase tracking-wide text-slate-500 mb-1">Présences</div>
                      <div class="text-sm font-medium text-slate-200">{{ progression.presences_actuelles }} / {{ progression.presences_requises }}</div>
                    </div>
                    <div class="p-3 rounded-lg bg-slate-800/40 border border-slate-700/40">
                      <div class="text-[10px] uppercase tracking-wide text-slate-500 mb-1">Temps</div>
                      <div class="text-sm font-medium text-slate-200">{{ progression.mois_requis }} mois</div>
                    </div>
                  </div>
                  <div class="flex items-center justify-between p-3 rounded-lg bg-slate-800/40 border border-slate-700/40">
                    <div class="flex items-center text-sm">
                      <div class="w-3 h-3 rounded-full mr-2" :style="`background-color: ${progression.ceinture.couleur_hex}`"></div>
                      {{ progression.ceinture.nom }}
                    </div>
                    <span class="text-xs font-medium" :class="progression.peut_passer ? 'text-indigo-300' : 'text-slate-500'">
                      {{ progression.peut_passer ? '✅ Éligible' : '⏳ En attente' }}
                    </span>
                  </div>
                  <button @click="ouvrirModalChangementCeinture" class="w-full mt-2 px-4 py-2 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-xs font-medium hover:from-indigo-400 hover:to-purple-500 transition">🎯 Changer de ceinture</button>
                </div>
              </div>
            </div>

            <!-- Contact urgence / Adresse -->
            <div class="space-y-6">
              <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 p-6">
                <h3 class="text-sm font-semibold text-slate-300 mb-4">🚨 Contact d'urgence</h3>
                <div class="space-y-2 text-xs">
                  <div class="flex justify-between"><span class="text-slate-500">Nom</span><span class="text-slate-200">{{ membre.contact_urgence_nom || '—' }}</span></div>
                  <div class="flex justify-between"><span class="text-slate-500">Téléphone</span><span class="text-slate-200">{{ membre.contact_urgence_telephone || '—' }}</span></div>
                  <div v-if="membre.contact_urgence_relation" class="flex justify-between"><span class="text-slate-500">Relation</span><span class="text-slate-200">{{ membre.contact_urgence_relation }}</span></div>
                </div>
              </div>
              <div v-if="membre.adresse" class="rounded-2xl border border-slate-700/50 bg-slate-900/60 p-6">
                <h3 class="text-sm font-semibold text-slate-300 mb-4">📍 Adresse</h3>
                <p class="text-xs text-slate-300">{{ membre.adresse }}</p>
                <p v-if="membre.ville || membre.code_postal" class="text-[10px] text-slate-500 mt-1">
                  <span v-if="membre.ville">{{ membre.ville }}</span>
                  <span v-if="membre.code_postal" class="ml-1">{{ membre.code_postal }}</span>
                </p>
              </div>
            </div>
          </div>

          <!-- Médical / Présences / Paiements / Consentements -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-6">
              <div v-if="membre.notes_medicales || membre.allergies?.length" class="rounded-2xl border border-slate-700/50 bg-slate-900/60 p-6">
                <h3 class="text-sm font-semibold text-slate-300 mb-4">🏥 Informations médicales</h3>
                <div class="space-y-3">
                  <div v-if="membre.notes_medicales">
                    <p class="text-xs text-slate-300 bg-slate-800/50 p-3 rounded border border-slate-700/50">{{ membre.notes_medicales }}</p>
                  </div>
                  <div v-if="membre.allergies?.length" class="flex flex-wrap gap-1">
                    <span v-for="a in membre.allergies" :key="a" class="px-2 py-1 rounded-full text-[10px] font-medium bg-red-500/20 text-red-300 border border-red-500/30">⚠️ {{ a }}</span>
                  </div>
                </div>
              </div>
              <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 p-6">
                <h3 class="text-sm font-semibold text-slate-300 mb-4">📅 Présences récentes</h3>
                <div v-if="membre.presences?.length" class="space-y-2 max-h-64 overflow-y-auto pr-1">
                  <div v-for="presence in membre.presences" :key="presence.id" class="flex justify-between items-center p-2 rounded bg-slate-800/40 border border-slate-700/40">
                    <div>
                      <div class="text-xs font-medium text-slate-200">{{ presence.cours?.nom || 'Cours supprimé' }}</div>
                      <div class="text-[10px] text-slate-500">{{ formatDate(presence.date_cours) }}</div>
                    </div>
                    <span :class="presenceBadge(presence.statut)" class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-medium">{{ presence.statut }}</span>
                  </div>
                </div>
                <p v-else class="text-xs text-slate-500">Aucune présence enregistrée</p>
              </div>
            </div>
            <div class="space-y-6">
              <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 p-6">
                <h3 class="text-sm font-semibold text-slate-300 mb-4">💰 Paiements récents</h3>
                <div v-if="membre.paiements?.length" class="space-y-2 max-h-64 overflow-y-auto pr-1">
                  <div v-for="paiement in membre.paiements" :key="paiement.id" class="flex justify-between items-center p-2 rounded bg-slate-800/40 border border-slate-700/40">
                    <div>
                      <div class="text-xs font-medium text-slate-200">{{ paiement.description }}</div>
                      <div class="text-[10px] text-slate-500">Échéance: {{ formatDate(paiement.date_echeance) }}</div>
                    </div>
                    <div class="text-right">
                      <div class="text-xs font-medium text-slate-300">{{ formatMontant(paiement.montant) }}</div>
                      <span :class="paiementBadge(paiement.statut)" class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium">{{ paiement.statut.replace('_',' ') }}</span>
                    </div>
                  </div>
                </div>
                <p v-else class="text-xs text-slate-500">Aucun paiement enregistré</p>
              </div>
              <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 p-6">
                <h3 class="text-sm font-semibold text-slate-300 mb-4">🛡️ Consentements</h3>
                <div class="grid grid-cols-3 gap-3 text-center text-[10px]">
                  <div class="p-3 rounded-lg bg-slate-800/40 border border-slate-700/40">
                    <div class="text-2xl mb-1">{{ membre.consentement_donnees ? '✅' : '❌' }}</div>
                    <div class="text-slate-400">Données</div>
                  </div>
                  <div class="p-3 rounded-lg bg-slate-800/40 border border-slate-700/40">
                    <div class="text-2xl mb-1">{{ membre.consentement_communications ? '✅' : '❌' }}</div>
                    <div class="text-slate-400">Comm.</div>
                  </div>
                  <div class="p-3 rounded-lg bg-slate-800/40 border border-slate-700/40">
                    <div class="text-2xl mb-1">{{ membre.consentement_photos ? '✅' : '❌' }}</div>
                    <div class="text-slate-400">Photos</div>
                  </div>
                </div>
                <p class="text-[10px] text-slate-500 mt-3">Conformité Loi 25 (Québec)</p>
              </div>
            </div>
          </div>
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900">🥋 Nouvelle Ceinture</h3>
          <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">Nouvelle ceinture</label>
            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
              <option>Blanche</option>
              <option>Jaune</option>
              <option>Orange</option>
              <option>Verte</option>
              <option>Bleue</option>
              <option>Brune</option>
              <option>Noire</option>
            </select>
            
            <label class="block text-sm font-medium text-gray-700 mt-4">Date d'obtention</label>
            <input type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            
            <label class="block text-sm font-medium text-gray-700 mt-4">Notes</label>
            <textarea class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" rows="3" placeholder="Notes sur l'examen..."></textarea>
          </div>
          <div class="mt-4 flex justify-end space-x-2">
            <button
              @click="showModalCeinture = false"
              class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded text-sm"
            >
              Annuler
            </button>
            <button
              class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded text-sm"
            >
              Valider Ceinture
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal séminaire -->
    <div v-if="showModalSeminaire" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900">🎓 Inscription Séminaire</h3>
          <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">Type de séminaire</label>
            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
              <option>Stage technique</option>
              <option>Stage arbitrage</option>
              <option>Formation instructeur</option>
              <option>Séminaire international</option>
            </select>
            
            <label class="block text-sm font-medium text-gray-700 mt-4">Date du séminaire</label>
            <input type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            
            <label class="block text-sm font-medium text-gray-700 mt-4">Coût</label>
            <input type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="150.00">
          </div>
          <div class="mt-4 flex justify-end space-x-2">
            <button
              @click="showModalSeminaire = false"
              class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded text-sm"
            >
              Annuler
            </button>
            <button
              class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-4 rounded text-sm"
            >
              Inscrire
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal paiement -->
    <div v-if="showModalPaiement" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900">💳 Nouveau Paiement</h3>
          <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">Type de paiement</label>
            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
              <option>Cotisation mensuelle</option>
              <option>Inscription annuelle</option>
              <option>Examen de ceinture</option>
              <option>Séminaire</option>
              <option>Équipement</option>
            </select>
            
            <label class="block text-sm font-medium text-gray-700 mt-4">Montant</label>
            <input type="number" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="75.00">
            
            <label class="block text-sm font-medium text-gray-700 mt-4">Méthode de paiement</label>
            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
              <option>Comptant</option>
              <option>Carte de crédit</option>
              <option>Débit</option>
              <option>Virement</option>
              <option>Chèque</option>
            </select>
          </div>
          <div class="mt-4 flex justify-end space-x-2">
            <button
              @click="showModalPaiement = false"
              class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded text-sm"
            >
              Annuler
            </button>
            <button
              class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded text-sm"
            >
              Enregistrer Paiement
            </button>
          </div>
        </div>
      </div>
    </div>

  </AuthenticatedLayout>
 </template>

<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'

const props = defineProps({ membre: Object, progression: Object })

const showModalCeinture = ref(false)
const showModalSeminaire = ref(false)
const showModalPaiement = ref(false)

const calculerAge = (dateNaissance) => Math.floor((Date.now() - new Date(dateNaissance).getTime()) / (365.25 * 24 * 3600 * 1000))
const formatDate = (date) => date ? new Date(date).toLocaleDateString('fr-CA') : 'Non spécifié'
const formatMontant = (montant) => new Intl.NumberFormat('fr-CA',{ style:'currency', currency:'CAD'}).format(montant)

function statusClass(statut){
  return {
    'bg-indigo-500/20 text-indigo-300 border border-indigo-500/30': statut==='actif'||statut==='diplome',
    'bg-slate-600/30 text-slate-300 border border-slate-600/40': statut==='inactif',
    'bg-red-500/20 text-red-300 border border-red-500/30': statut==='suspendu'
  }
}
const presenceBadge = (s) => ({
  'bg-green-500/20 text-green-300 border border-green-500/30': s==='present',
  'bg-red-500/20 text-red-300 border border-red-500/30': s==='absent',
  'bg-amber-500/20 text-amber-300 border border-amber-500/30': s==='retard',
  'bg-indigo-500/20 text-indigo-300 border border-indigo-500/30': s==='excuse'
})
const paiementBadge = (s) => ({
  'bg-green-500/20 text-green-300 border border-green-500/30': s==='paye',
  'bg-amber-500/20 text-amber-300 border border-amber-500/30': s==='en_attente',
  'bg-red-500/20 text-red-300 border border-red-500/30': s==='en_retard'
})

const openModalCeinture = () => (showModalCeinture.value = true)
const openModalSeminaire = () => (showModalSeminaire.value = true)
const openModalPaiement = () => (showModalPaiement.value = true)
const ouvrirModalChangementCeinture = () => (showModalCeinture.value = true)

const marquerPresence = () => {
  router.post(route('presences.store'), {
    membre_id: props.membre.id,
    date: new Date().toISOString().split('T')[0]
  }, { onSuccess: () => alert('Présence marquée avec succès!') })
}
</script>

<style scoped>
.action-btn{ @apply flex flex-col items-center justify-center gap-1 px-3 py-3 rounded-lg bg-slate-800/60 border border-slate-700 text-slate-300 text-xs font-medium hover:bg-slate-700/60 transition; }
</style>
