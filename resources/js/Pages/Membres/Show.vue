<template>
  <AuthenticatedLayout>
    <div class="p-6 space-y-8">
      <PageHeader :title="membre.nom_complet" :description="`Profil détaillé du membre (âge ${calculerAge(membre.date_naissance)} ans).`">
        <template #icon>
          <div class="w-12 h-12 rounded-xl bg-slate-800/70 border border-slate-700 flex items-center justify-center text-indigo-300 text-2xl">👤</div>
        </template>
        <template #actions>
          <Link :href="`/membres/${membre.id}/edit`" class="px-4 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-sm font-medium text-slate-200 transition">✏️ Modifier</Link>
          <Link href="/membres" class="px-4 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-sm font-medium text-slate-200 transition">← Retour</Link>
        </template>
      </PageHeader>

      <!-- Carte profil principal -->
      <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 p-6 flex items-start gap-6">
        <div class="flex-shrink-0">
          <div class="h-24 w-24 rounded-full bg-slate-800 flex items-center justify-center text-2xl font-bold text-indigo-300 shadow">
            {{ membre.prenom[0] }}{{ membre.nom[0] }}
          </div>
        </div>
        <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-6">
          <div>
            <h3 class="text-lg font-semibold text-slate-200">{{ membre.nom_complet }}</h3>
            <p class="text-xs text-slate-400">{{ calculerAge(membre.date_naissance) }} ans · {{ membre.sexe === 'M' ? 'Masculin' : membre.sexe === 'F' ? 'Féminin' : 'Autre' }}</p>
            <div class="mt-2">
              <span :class="statusClass(membre.statut)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                {{ membre.statut.charAt(0).toUpperCase() + membre.statut.slice(1) }}
              </span>
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
            <p class="text-[10px] text-slate-500 mt-1" v-if="membre.date_derniere_presence">
              Dernière présence: {{ formatDate(membre.date_derniere_presence) }}
            </p>
          </div>
        </div>
      </div>

      <!-- Actions rapides -->
      <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 p-6">
        <h3 class="text-sm font-semibold text-slate-300 mb-4">Actions rapides</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
          <button @click="openModalCeinture" class="action-btn">
            <span class="text-2xl">🥋</span>
            <span class="text-xs">Ceinture</span>
          </button>
          <button @click="openModalSeminaire" class="action-btn">
            <span class="text-2xl">🎓</span>
            <span class="text-xs">Séminaire</span>
          </button>
          <button @click="openModalPaiement" class="action-btn">
            <span class="text-2xl">💳</span>
            <span class="text-xs">Paiement</span>
          </button>
          <button @click="marquerPresence" class="action-btn">
            <span class="text-2xl">✅</span>
            <span class="text-xs">Présence</span>
          </button>
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
          </div>
        </div>

        <!-- Contact urgence -->
        <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 p-6">
          <h3 class="text-sm font-semibold text-slate-300 mb-4">🚨 Contact d'urgence</h3>
          <div class="space-y-2 text-xs">
            <div class="flex justify-between">
              <span class="text-slate-500">Nom</span>
              <span class="text-slate-200">{{ membre.contact_urgence_nom || '—' }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-500">Téléphone</span>
              <span class="text-slate-200">{{ membre.contact_urgence_telephone || '—' }}</span>
            </div>
            <div v-if="membre.contact_urgence_relation" class="flex justify-between">
              <span class="text-slate-500">Relation</span>
              <span class="text-slate-200">{{ membre.contact_urgence_relation }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Consentements -->
      <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 p-6">
        <h3 class="text-sm font-semibold text-slate-300 mb-4">🛡️ Consentements</h3>
        <div class="grid grid-cols-3 gap-3 text-center text-[10px]">
          <div class="p-3 rounded-lg bg-slate-800/40 border border-slate-700/40">
            <div class="text-2xl mb-1">{{ membre.consentement_donnees ? '✅' : '❌' }}</div>
            <div class="text-slate-400">Données</div>
          </div>
          <div class="p-3 rounded-lg bg-slate-800/40 border border-slate-700/40">
            <div class="text-2xl mb-1">{{ membre.consentement_communications ? '✅' : '❌' }}</div>
            <div class="text-slate-400">Communications</div>
          </div>
          <div class="p-3 rounded-lg bg-slate-800/40 border border-slate-700/40">
            <div class="text-2xl mb-1">{{ membre.consentement_photos ? '✅' : '❌' }}</div>
            <div class="text-slate-400">Photos</div>
          </div>
        </div>
        <p class="text-[10px] text-slate-500 mt-3">Conformité Loi 25 (Québec)</p>
      </div>
    </div>

    <!-- Modal Ceinture (style dark) -->
    <Teleport to="body">
      <div v-if="showModalCeinture" class="fixed inset-0 z-50 overflow-y-auto">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="showModalCeinture = false"></div>
        
        <!-- Modal centré -->
        <div class="relative min-h-screen flex items-center justify-center p-4">
          <div class="relative w-full max-w-md transform rounded-2xl bg-gradient-to-b from-slate-800 to-slate-900 p-6 shadow-2xl border border-slate-700/50">
            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
              <span class="text-2xl">🥋</span> Nouvelle Ceinture
            </h3>
            
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Nouvelle ceinture</label>
                <select v-model="formCeinture.ceinture_id" class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                  <option value="">Sélectionner...</option>
                  <option v-for="ceinture in props.ceintures" :key="ceinture.id" :value="ceinture.id">
                    {{ ceinture.name }}
                  </option>
                </select>
                <!-- Affichage visuel de la ceinture sélectionnée -->
                <div v-if="formCeinture.ceinture_id" class="mt-2 flex items-center gap-2">
                  <div class="w-4 h-4 rounded-full border border-slate-600" 
                       :style="`background-color: ${props.ceintures.find(c => c.id == formCeinture.ceinture_id)?.color_hex}`"></div>
                  <span class="text-xs text-slate-400">{{ props.ceintures.find(c => c.id == formCeinture.ceinture_id)?.name }}</span>
                </div>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Date d'obtention</label>
                <input v-model="formCeinture.date" type="date" class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
              </div>
              
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Notes</label>
                <textarea v-model="formCeinture.notes" rows="3" class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500" placeholder="Notes sur l'examen..."></textarea>
              </div>
            </div>
            
            <div class="mt-6 flex justify-end gap-3">
              <button @click="showModalCeinture = false" class="px-4 py-2 rounded-lg bg-slate-800/70 hover:bg-slate-700 text-slate-200 text-sm font-medium border border-slate-700">
                Annuler
              </button>
              <button @click="validerCeinture" class="px-4 py-2 rounded-lg bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-400 hover:to-amber-500 text-white text-sm font-medium">
                Valider Ceinture
              </button>
            </div>
          </div>
        </div>
      </div>
    </Teleport>

    <!-- Modal Séminaire (style dark) -->
    <Teleport to="body">
      <div v-if="showModalSeminaire" class="fixed inset-0 z-50 overflow-y-auto">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="showModalSeminaire = false"></div>
        
        <!-- Modal centré -->
        <div class="relative min-h-screen flex items-center justify-center p-4">
          <div class="relative w-full max-w-md transform rounded-2xl bg-gradient-to-b from-slate-800 to-slate-900 p-6 shadow-2xl border border-slate-700/50">
            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
              <span class="text-2xl">🎓</span> Inscription Séminaire
            </h3>
            
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Type de séminaire</label>
                <select v-model="formSeminaire.type" class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                  <option value="">Sélectionner...</option>
                  <option value="technique">Stage technique</option>
                  <option value="arbitrage">Stage arbitrage</option>
                  <option value="instructeur">Formation instructeur</option>
                  <option value="international">Séminaire international</option>
                </select>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Date du séminaire</label>
                <input v-model="formSeminaire.date" type="date" class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
              </div>
              
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Coût</label>
                <input v-model="formSeminaire.cout" type="number" step="0.01" class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500" placeholder="150.00">
              </div>
            </div>
            
            <div class="mt-6 flex justify-end gap-3">
              <button @click="showModalSeminaire = false" class="px-4 py-2 rounded-lg bg-slate-800/70 hover:bg-slate-700 text-slate-200 text-sm font-medium border border-slate-700">
                Annuler
              </button>
              <button @click="inscrireSeminaire" class="px-4 py-2 rounded-lg bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-400 hover:to-indigo-500 text-white text-sm font-medium">
                Inscrire
              </button>
            </div>
          </div>
        </div>
      </div>
    </Teleport>

    <!-- Modal Paiement (style dark) -->
    <Teleport to="body">
      <div v-if="showModalPaiement" class="fixed inset-0 z-50 overflow-y-auto">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="showModalPaiement = false"></div>
        
        <!-- Modal centré -->
        <div class="relative min-h-screen flex items-center justify-center p-4">
          <div class="relative w-full max-w-md transform rounded-2xl bg-gradient-to-b from-slate-800 to-slate-900 p-6 shadow-2xl border border-slate-700/50">
            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
              <span class="text-2xl">💳</span> Nouveau Paiement
            </h3>
            
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Type de paiement</label>
                <select v-model="formPaiement.type" class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                  <option value="">Sélectionner...</option>
                  <option value="cotisation">Cotisation mensuelle</option>
                  <option value="inscription">Inscription annuelle</option>
                  <option value="examen">Examen de ceinture</option>
                  <option value="seminaire">Séminaire</option>
                  <option value="equipement">Équipement</option>
                </select>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Montant</label>
                <input v-model="formPaiement.montant" type="number" step="0.01" class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500" placeholder="75.00">
              </div>
              
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Méthode de paiement</label>
                <select v-model="formPaiement.methode" class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                  <option value="">Sélectionner...</option>
                  <option value="comptant">Comptant</option>
                  <option value="carte">Carte de crédit</option>
                  <option value="debit">Débit</option>
                  <option value="virement">Virement</option>
                  <option value="cheque">Chèque</option>
                </select>
              </div>
            </div>
            
            <div class="mt-6 flex justify-end gap-3">
              <button @click="showModalPaiement = false" class="px-4 py-2 rounded-lg bg-slate-800/70 hover:bg-slate-700 text-slate-200 text-sm font-medium border border-slate-700">
                Annuler
              </button>
              <button @click="enregistrerPaiement" class="px-4 py-2 rounded-lg bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-400 hover:to-emerald-500 text-white text-sm font-medium">
                Enregistrer Paiement
              </button>
            </div>
          </div>
        </div>
      </div>
    </Teleport>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'

const props = defineProps({ 
  membre: Object, 
  progression: Object,
  ceintures: Array // Ajout des ceintures
})

// Modals states
const showModalCeinture = ref(false)
const showModalSeminaire = ref(false)
const showModalPaiement = ref(false)

// Forms
const formCeinture = ref({
  ceinture_id: '',
  date: new Date().toISOString().split('T')[0],
  notes: ''
})

const formSeminaire = ref({
  type: '',
  date: '',
  cout: ''
})

const formPaiement = ref({
  type: '',
  montant: '',
  methode: ''
})

// Helpers
const calculerAge = (dateNaissance) => {
  if (!dateNaissance) return 0
  return Math.floor((Date.now() - new Date(dateNaissance).getTime()) / (365.25 * 24 * 3600 * 1000))
}

const formatDate = (date) => {
  if (!date) return 'Non spécifié'
  return new Date(date).toLocaleDateString('fr-CA')
}

const formatMontant = (montant) => {
  return new Intl.NumberFormat('fr-CA', { 
    style: 'currency', 
    currency: 'CAD'
  }).format(montant)
}

// Status classes
function statusClass(statut) {
  return {
    'bg-green-500/20 text-green-300 border border-green-500/30': statut === 'actif',
    'bg-slate-600/30 text-slate-300 border border-slate-600/40': statut === 'inactif',
    'bg-red-500/20 text-red-300 border border-red-500/30': statut === 'suspendu'
  }
}

// Modal actions
const openModalCeinture = () => (showModalCeinture.value = true)
const openModalSeminaire = () => (showModalSeminaire.value = true)
const openModalPaiement = () => (showModalPaiement.value = true)

// Validation actions
const validerCeinture = () => {
  router.post(`/membres/${props.membre.id}/ceinture`, {
    ceinture_id: formCeinture.value.ceinture_id,
    note: formCeinture.value.notes,
    date: formCeinture.value.date
  }, {
    preserveScroll: true,
    onSuccess: () => {
      showModalCeinture.value = false
      formCeinture.value = { 
        ceinture_id: '', 
        date: new Date().toISOString().split('T')[0], 
        notes: '' 
      }
    },
    onError: (errors) => {
      console.error('Erreur lors du changement de ceinture:', errors)
    }
  })
}

const inscrireSeminaire = () => {
  router.post(`/membres/${props.membre.id}/seminaire`, formSeminaire.value, {
    onSuccess: () => {
      showModalSeminaire.value = false
      formSeminaire.value = { type: '', date: '', cout: '' }
    }
  })
}

const enregistrerPaiement = () => {
  router.post('/paiements', {
    membre_id: props.membre.id,
    ...formPaiement.value
  }, {
    onSuccess: () => {
      showModalPaiement.value = false
      formPaiement.value = { type: '', montant: '', methode: '' }
    }
  })
}

const marquerPresence = () => {
  router.post('/presences', {
    membre_id: props.membre.id,
    date_cours: new Date().toISOString().split('T')[0],
    statut: 'present'
  }, {
    preserveScroll: true,
    onSuccess: () => alert('Présence marquée avec succès!')
  })
}
</script>

<style scoped>
.action-btn {
  @apply flex flex-col items-center justify-center gap-1 px-3 py-3 rounded-lg bg-slate-800/60 border border-slate-700 text-slate-300 text-xs font-medium hover:bg-slate-700/60 transition;
}
</style>
