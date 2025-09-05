<template>
  <AuthenticatedLayout>
    <Head :title="`Modifier ${cours.nom}`" />
    
    <div class="min-h-screen max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <PageHeader :title="`Modifier ${cours.nom}`" :description="`Édition du cours - ${cours.niveau} - ${cours.age_min}-${cours.age_max} ans`">
        <template #actions>
          <Link :href="`/cours/${cours.id}`"
                class="px-4 py-2 rounded-lg bg-slate-700/50 hover:bg-slate-600/50 text-white text-sm font-medium border border-slate-600">
            ← Voir le cours
          </Link>
        </template>
      </PageHeader>
      
      <!-- Formulaire -->
      <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6 mb-6">
        <form @submit.prevent="submit">
          <!-- Informations principales -->
          <div class="mb-8">
            <h3 class="text-lg font-semibold text-white mb-4">Informations du cours</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Nom du cours *</label>
                <input
                  v-model="form.nom"
                  type="text"
                  required
                  class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-slate-500"
                  :class="{ 'border-red-500': errors.nom }"
                  placeholder="Ex: Karaté Débutants Enfants"
                />
                <div v-if="errors.nom" class="text-red-400 text-sm mt-1">{{ errors.nom }}</div>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Niveau *</label>
                <select
                  v-model="form.niveau"
                  required
                  class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  :class="{ 'border-red-500': errors.niveau }"
                >
                  <option value="">Sélectionner...</option>
                  <option value="tous">Tous</option>
                  <option value="debutant">Débutant</option>
                  <option value="intermediaire">Intermédiaire</option>
                  <option value="avance">Avancé</option>
                  <option value="prive">Privé</option>
                  <option value="competition">Compétition</option>
                  <option value="a_la_carte">À la carte</option>
                </select>
                <div v-if="errors.niveau" class="text-red-400 text-sm mt-1">{{ errors.niveau }}</div>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Âge minimum *</label>
                <input
                  v-model="form.age_min"
                  type="number"
                  min="3"
                  max="99"
                  required
                  class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  :class="{ 'border-red-500': errors.age_min }"
                />
                <div v-if="errors.age_min" class="text-red-400 text-sm mt-1">{{ errors.age_min }}</div>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Âge maximum</label>
                <input
                  v-model="form.age_max"
                  type="number"
                  min="3"
                  max="99"
                  class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  :class="{ 'border-red-500': errors.age_max }"
                  placeholder="Optionnel (pour tous âges, laisser vide)"
                />
                <div v-if="errors.age_max" class="text-red-400 text-sm mt-1">{{ errors.age_max }}</div>
                <div class="text-xs text-slate-500 mt-1">Laisser vide pour "tous âges"</div>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Places maximum *</label>
                <input
                  v-model="form.places_max"
                  type="number"
                  min="1"
                  max="50"
                  required
                  class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  :class="{ 'border-red-500': errors.places_max }"
                />
                <div v-if="errors.places_max" class="text-red-400 text-sm mt-1">{{ errors.places_max }}</div>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Instructeur</label>
                <select
                  v-model="form.instructeur_id"
                  class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  :class="{ 'border-red-500': errors.instructeur_id }"
                >
                  <option value="">Aucun (à assigner plus tard)</option>
                  <option v-for="inst in instructeurs" :key="inst.id" :value="inst.id">{{ inst.name }}</option>
                </select>
                <div v-if="errors.instructeur_id" class="text-red-400 text-sm mt-1">{{ errors.instructeur_id }}</div>
              </div>
            </div>
          </div>
          
          <!-- Horaires -->
          <div class="mb-8">
            <h3 class="text-lg font-semibold text-white mb-4">Horaires</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Jour de la semaine *</label>
                <select
                  v-model="form.jour_semaine"
                  required
                  class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  :class="{ 'border-red-500': errors.jour_semaine }"
                >
                  <option value="">Sélectionner...</option>
                  <option v-for="jour in joursDisponibles" :key="jour.value" :value="jour.value">{{ jour.label }}</option>
                </select>
                <div v-if="errors.jour_semaine" class="text-red-400 text-sm mt-1">{{ errors.jour_semaine }}</div>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Heure de début *</label>
                <input
                  v-model="form.heure_debut"
                  type="time"
                  required
                  step="900"
                  class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent
                         calendar-enhanced"
                  :class="{ 'border-red-500': errors.heure_debut }"
                />
                <div v-if="errors.heure_debut" class="text-red-400 text-sm mt-1">{{ errors.heure_debut }}</div>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Heure de fin *</label>
                <input
                  v-model="form.heure_fin"
                  type="time"
                  required
                  step="900"
                  class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent
                         calendar-enhanced"
                  :class="{ 'border-red-500': errors.heure_fin }"
                />
                <div v-if="errors.heure_fin" class="text-red-400 text-sm mt-1">{{ errors.heure_fin }}</div>
              </div>
            </div>
          </div>
          
          <!-- Période et tarification flexible -->
          <div class="mb-8">
            <h3 class="text-lg font-semibold text-white mb-4">Période et tarification</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Date de début *</label>
                <input
                  v-model="form.date_debut"
                  type="date"
                  required
                  class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent
                         calendar-enhanced"
                  :class="{ 'border-red-500': errors.date_debut }"
                />
                <div v-if="errors.date_debut" class="text-red-400 text-sm mt-1">{{ errors.date_debut }}</div>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Date de fin</label>
                <input
                  v-model="form.date_fin"
                  type="date"
                  class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent
                         calendar-enhanced"
                  :class="{ 'border-red-500': errors.date_fin }"
                />
                <div v-if="errors.date_fin" class="text-red-400 text-sm mt-1">{{ errors.date_fin }}</div>
              </div>
            </div>
            
            <!-- Tarification flexible -->
            <div class="bg-slate-800/50 rounded-lg p-6">
              <h4 class="text-md font-semibold text-white mb-4">💰 Système de tarification</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-slate-300 mb-2">Type de tarification *</label>
                  <select
                    v-model="form.type_tarif"
                    required
                    class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    :class="{ 'border-red-500': errors.type_tarif }"
                  >
                    <option value="mensuel">Mensuel</option>
                    <option value="trimestriel">Trimestriel (3 mois)</option>
                    <option value="horaire">À l'heure</option>
                    <option value="a_la_carte">À la carte (10 samedis)</option>
                    <option value="autre">Autre (préciser)</option>
                  </select>
                  <div v-if="errors.type_tarif" class="text-red-400 text-sm mt-1">{{ errors.type_tarif }}</div>
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-slate-300 mb-2">
                    Montant (CAD) * 
                    <span class="text-xs text-slate-500">{{ getTarifHint() }}</span>
                  </label>
                  <input
                    v-model="form.montant"
                    type="number"
                    min="0"
                    step="0.01"
                    required
                    class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    :class="{ 'border-red-500': errors.montant }"
                    placeholder="0.00"
                  />
                  <div v-if="errors.montant" class="text-red-400 text-sm mt-1">{{ errors.montant }}</div>
                </div>
                
                <div v-if="form.type_tarif === 'autre'" class="md:col-span-2">
                  <label class="block text-sm font-medium text-slate-300 mb-2">Détails de la tarification *</label>
                  <textarea
                    v-model="form.details_tarif"
                    rows="3"
                    required
                    class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    :class="{ 'border-red-500': errors.details_tarif }"
                    placeholder="Précisez les modalités de paiement, fréquence, conditions..."
                  ></textarea>
                  <div v-if="errors.details_tarif" class="text-red-400 text-sm mt-1">{{ errors.details_tarif }}</div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Description et options -->
          <div class="mb-8">
            <h3 class="text-lg font-semibold text-white mb-4">Détails supplémentaires</h3>
            <div class="space-y-6">
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Description</label>
                <textarea
                  v-model="form.description"
                  rows="3"
                  class="w-full bg-slate-900/50 text-white border border-slate-700 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-slate-500"
                  placeholder="Description du cours, objectifs, particularités..."
                ></textarea>
              </div>
              
              <div class="flex items-center">
                <input
                  id="actif"
                  v-model="form.actif"
                  type="checkbox"
                  class="w-4 h-4 text-blue-600 bg-slate-900 border-slate-700 rounded focus:ring-blue-500 focus:ring-2"
                />
                <label for="actif" class="ml-2 text-sm text-slate-300">
                  Cours actif (disponible pour les inscriptions)
                </label>
              </div>
            </div>
          </div>
          
          <!-- Actions -->
          <div class="flex justify-end gap-3">
            <Link :href="`/cours/${cours.id}`"
                  class="px-5 py-2.5 bg-slate-800/50 hover:bg-slate-700/50 text-white rounded-xl border border-slate-700 transition-all">
              Annuler
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-medium shadow-lg transition-all disabled:opacity-50"
            >
              <span v-if="form.processing">Sauvegarde...</span>
              <span v-else>Sauvegarder</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'

const props = defineProps({
  cours: {
    type: Object,
    required: true
  },
  instructeurs: {
    type: Array,
    default: () => []
  },
  niveaux: {
    type: Array,
    default: () => ['debutant', 'intermediaire', 'avance', 'competition']
  },
  joursDisponibles: {
    type: Array,
    default: () => [
      { value: 'lundi', label: 'Lundi' },
      { value: 'mardi', label: 'Mardi' },
      { value: 'mercredi', label: 'Mercredi' },
      { value: 'jeudi', label: 'Jeudi' },
      { value: 'vendredi', label: 'Vendredi' },
      { value: 'samedi', label: 'Samedi' },
      { value: 'dimanche', label: 'Dimanche' }
    ]
  }
})

const form = useForm({
  nom: props.cours.nom || '',
  description: props.cours.description || '',
  instructeur_id: props.cours.instructeur_id || '',
  niveau: props.cours.niveau || '',
  age_min: props.cours.age_min || '',
  age_max: props.cours.age_max || '',
  places_max: props.cours.places_max || '',
  jour_semaine: props.cours.jour_semaine || '',
  heure_debut: props.cours.heure_debut || '',
  heure_fin: props.cours.heure_fin || '',
  date_debut: props.cours.date_debut || '',
  date_fin: props.cours.date_fin || '',
  // Nouveau système de tarification flexible
  type_tarif: props.cours.type_tarif || 'mensuel',
  montant: props.cours.montant || props.cours.tarif_mensuel || '', // Migration auto si ancien système
  details_tarif: props.cours.details_tarif || '',
  // Ancien système (conservé pour compatibilité - sera null si non mensuel)
  tarif_mensuel: props.cours.tarif_mensuel || null,
  actif: props.cours.actif ?? true
})

const errors = ref({})

// Format français Canada pour les dates
onMounted(() => {
  // Configurer le navigateur pour format canadien français
  document.documentElement.lang = 'fr-CA'
  
  // Formatter les dates existantes en format YYYY-MM-DD pour les inputs HTML
  if (form.date_debut && !form.date_debut.includes('-')) {
    form.date_debut = formatDateForInput(form.date_debut)
  }
  if (form.date_fin && !form.date_fin.includes('-')) {
    form.date_fin = formatDateForInput(form.date_fin)
  }
})

const formatDateForInput = (dateStr) => {
  try {
    const date = new Date(dateStr)
    return date.toISOString().split('T')[0] // YYYY-MM-DD
  } catch (e) {
    return dateStr
  }
}

// Aide contextuelle pour le montant selon le type de tarif
const getTarifHint = () => {
  const hints = {
    mensuel: 'par mois',
    trimestriel: 'pour 3 mois',
    horaire: 'par séance',
    a_la_carte: 'pour 10 samedis',
    autre: 'selon détails'
  }
  return hints[form.type_tarif] || ''
}

const submit = () => {
  // Synchroniser ancien système si mensuel (compatibilité)
  if (form.type_tarif === 'mensuel') {
    form.tarif_mensuel = form.montant
  } else {
    // Explicitement envoyer null pour les autres types
    form.tarif_mensuel = null
  }
  
  form.put(`/cours/${props.cours.id}`, {
    onError: (e) => { errors.value = e },
    onSuccess: () => { errors.value = {} }
  })
}
</script>

<style scoped>
/* CSS amélioré pour calendriers - contraste maximal */
.calendar-enhanced {
  color-scheme: dark;
}

.calendar-enhanced::-webkit-calendar-picker-indicator {
  /* Icône calendrier en blanc pur avec fond contrasté */
  filter: invert(1) brightness(2) contrast(2);
  cursor: pointer;
  background: rgba(255, 255, 255, 0.15);
  border-radius: 6px;
  padding: 4px;
  border: 1px solid rgba(255, 255, 255, 0.2);
  transition: all 0.2s ease;
}

.calendar-enhanced:hover::-webkit-calendar-picker-indicator {
  background: rgba(255, 255, 255, 0.25);
  transform: scale(1.05);
}

.calendar-enhanced::-webkit-datetime-edit {
  color: white;
  font-weight: 500;
}

.calendar-enhanced::-webkit-datetime-edit-fields-wrapper {
  background: transparent;
  padding: 2px 4px;
  border-radius: 4px;
}

.calendar-enhanced::-webkit-datetime-edit-text {
  color: rgb(148 163 184); /* slate-400 */
  padding: 0 3px;
}

.calendar-enhanced::-webkit-datetime-edit-month-field,
.calendar-enhanced::-webkit-datetime-edit-day-field,
.calendar-enhanced::-webkit-datetime-edit-year-field,
.calendar-enhanced::-webkit-datetime-edit-hour-field,
.calendar-enhanced::-webkit-datetime-edit-minute-field {
  color: white;
  background: transparent;
  font-weight: 500;
  padding: 1px 2px;
  border-radius: 2px;
}

.calendar-enhanced:focus::-webkit-datetime-edit-fields-wrapper {
  background: rgba(59, 130, 246, 0.15);
  border-radius: 6px;
  outline: 1px solid rgba(59, 130, 246, 0.5);
}

/* Support pour Firefox */
.calendar-enhanced::-moz-calendar-picker-indicator {
  filter: invert(1) brightness(2);
  background: rgba(255, 255, 255, 0.15);
  border-radius: 4px;
  padding: 2px;
}
</style>
