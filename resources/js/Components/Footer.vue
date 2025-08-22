<template>
  <footer class="relative bg-gradient-to-r from-slate-900 via-slate-950 to-slate-900 border-t border-slate-800/50">
    <!-- Ligne lumineuse décorative -->
    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-blue-500/30 to-transparent"></div>
    
    <!-- Footer compact -->
    <div class="px-6 py-4">
      <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
        
        <!-- Gauche: Logo et copyright -->
        <div class="flex items-center space-x-4">
          <div class="flex items-center space-x-2">
            <div class="w-8 h-8 bg-gradient-to-br from-blue-500/20 to-purple-600/20 rounded-lg flex items-center justify-center">
              <span class="text-sm">🥋</span>
            </div>
            <div class="flex items-center space-x-3 text-xs">
              <span class="text-slate-400">© {{ currentYear }} Studios Unis</span>
              <span class="text-slate-700">•</span>
              <span class="inline-flex items-center px-2 py-0.5 bg-blue-500/10 text-blue-400 rounded font-semibold">
                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                Loi 25
              </span>
            </div>
          </div>
        </div>

        <!-- Centre: Liens rapides (desktop only) -->
        <div class="hidden md:flex items-center space-x-4 text-xs">
          <button @click="openModal('privacy')" 
                  class="text-slate-500 hover:text-blue-400 transition-colors">
            Confidentialité
          </button>
          <span class="text-slate-700">•</span>
          <button @click="openModal('consent')" 
                  class="text-slate-500 hover:text-blue-400 transition-colors">
            Consentements
          </button>
          <span class="text-slate-700">•</span>
          <button @click="openModal('data')" 
                  class="text-slate-500 hover:text-blue-400 transition-colors">
            Protection données
          </button>
          <span class="text-slate-700">•</span>
          <a href="mailto:dpo@studiosdb.ca" 
             class="text-slate-500 hover:text-blue-400 transition-colors">
            Contact DPO
          </a>
        </div>

        <!-- Droite: Version et menu mobile -->
        <div class="flex items-center space-x-3">
          <!-- Version et statut -->
          <div class="flex items-center space-x-3 text-xs text-slate-500">
            <span class="hidden sm:inline-flex items-center">
              <span class="w-2 h-2 bg-green-400 rounded-full mr-1 animate-pulse"></span>
              En ligne
            </span>
            <span class="text-slate-700 hidden sm:inline">•</span>
            <span>v{{ $appVersion }}</span>
          </div>
          
          <!-- Menu dropdown mobile -->
          <div class="relative md:hidden">
            <button @click="mobileMenuOpen = !mobileMenuOpen"
                    class="p-1.5 rounded-lg hover:bg-slate-800/50 text-slate-400 hover:text-white transition-all">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
              </svg>
            </button>
            
            <!-- Dropdown menu -->
            <transition
              enter-active-class="transition ease-out duration-100"
              enter-from-class="transform opacity-0 scale-95"
              enter-to-class="transform opacity-100 scale-100"
              leave-active-class="transition ease-in duration-75"
              leave-from-class="transform opacity-100 scale-100"
              leave-to-class="transform opacity-0 scale-95"
            >
              <div v-if="mobileMenuOpen" 
                   class="absolute bottom-full right-0 mb-2 w-48 rounded-lg bg-slate-800 shadow-xl border border-slate-700/50 py-1">
                <button @click="openModal('privacy'); mobileMenuOpen = false" 
                        class="w-full text-left px-4 py-2 text-sm text-slate-300 hover:bg-slate-700/50 hover:text-white transition-colors">
                  Politique de confidentialité
                </button>
                <button @click="openModal('consent'); mobileMenuOpen = false" 
                        class="w-full text-left px-4 py-2 text-sm text-slate-300 hover:bg-slate-700/50 hover:text-white transition-colors">
                  Gestion des consentements
                </button>
                <button @click="openModal('data'); mobileMenuOpen = false" 
                        class="w-full text-left px-4 py-2 text-sm text-slate-300 hover:bg-slate-700/50 hover:text-white transition-colors">
                  Protection des données
                </button>
                <hr class="my-1 border-slate-700/50">
                <a href="mailto:dpo@studiosdb.ca" 
                   class="block px-4 py-2 text-sm text-slate-300 hover:bg-slate-700/50 hover:text-white transition-colors">
                  Contact DPO
                </a>
              </div>
            </transition>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de conformité (inchangé mais optimisé) -->
    <TransitionRoot appear :show="isModalOpen" as="template">
      <Dialog as="div" @close="closeModal" class="relative z-50">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black/80 backdrop-blur-sm" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-center justify-center p-4">
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
            >
              <DialogPanel class="w-full max-w-lg transform overflow-hidden rounded-2xl bg-gradient-to-b from-slate-800 to-slate-900 p-5 shadow-2xl transition-all border border-slate-700/50">
                <div class="flex items-center justify-between mb-4">
                  <DialogTitle as="h3" class="text-lg font-bold text-white flex items-center">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500/20 to-purple-600/20 flex items-center justify-center mr-2">
                      <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                      </svg>
                    </div>
                    {{ modalTitle }}
                  </DialogTitle>
                  <button @click="closeModal" 
                          class="p-1.5 rounded-lg hover:bg-slate-700/50 text-slate-400 hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
                
                <div class="text-slate-300 text-sm space-y-3 max-h-[50vh] overflow-y-auto pr-2">
                  <!-- Privacy -->
                  <div v-if="modalType === 'privacy'">
                    <p class="text-blue-400 font-semibold mb-2">Loi 25 - Protection des renseignements personnels</p>
                    <ul class="space-y-1 text-xs">
                      <li class="flex items-start">
                        <span class="text-green-400 mr-2">✓</span>
                        <span>Collecte minimale des données nécessaires</span>
                      </li>
                      <li class="flex items-start">
                        <span class="text-green-400 mr-2">✓</span>
                        <span>Chiffrement et sécurité renforcée</span>
                      </li>
                      <li class="flex items-start">
                        <span class="text-green-400 mr-2">✓</span>
                        <span>Aucune vente ou partage sans consentement</span>
                      </li>
                      <li class="flex items-start">
                        <span class="text-green-400 mr-2">✓</span>
                        <span>Droit d'accès, rectification et suppression</span>
                      </li>
                      <li class="flex items-start">
                        <span class="text-green-400 mr-2">✓</span>
                        <span>Notification d'incident sous 72h</span>
                      </li>
                    </ul>
                  </div>

                  <!-- Consent -->
                  <div v-if="modalType === 'consent'">
                    <p class="text-blue-400 font-semibold mb-2">Gestion de vos consentements</p>
                    <div class="space-y-2">
                      <div class="p-2 bg-slate-800/50 rounded-lg">
                        <p class="text-xs">Vous pouvez à tout moment :</p>
                        <ul class="mt-1 space-y-0.5 text-xs text-slate-400">
                          <li>• Consulter vos consentements actifs</li>
                          <li>• Retirer un consentement donné</li>
                          <li>• Demander une copie de vos données</li>
                          <li>• Exercer votre droit à l'oubli</li>
                        </ul>
                      </div>
                      <div class="p-2 bg-blue-500/10 rounded-lg border border-blue-500/20">
                        <p class="text-xs text-blue-400">📧 Contact DPO : dpo@studiosdb.ca</p>
                      </div>
                    </div>
                  </div>

                  <!-- Data -->
                  <div v-if="modalType === 'data'">
                    <p class="text-blue-400 font-semibold mb-2">Données collectées</p>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                      <div class="p-2 bg-slate-800/50 rounded-lg">
                        <p class="font-semibold text-white">👤 Identité</p>
                        <p class="text-slate-400 text-[10px]">Nom, prénom, date naissance</p>
                      </div>
                      <div class="p-2 bg-slate-800/50 rounded-lg">
                        <p class="font-semibold text-white">📞 Contact</p>
                        <p class="text-slate-400 text-[10px]">Adresse, tél, courriel</p>
                      </div>
                      <div class="p-2 bg-yellow-500/10 rounded-lg border border-yellow-500/20">
                        <p class="font-semibold text-yellow-400">⚕️ Santé</p>
                        <p class="text-slate-400 text-[10px]">Données sensibles protégées</p>
                      </div>
                      <div class="p-2 bg-slate-800/50 rounded-lg">
                        <p class="font-semibold text-white">📊 Progression</p>
                        <p class="text-slate-400 text-[10px]">Ceintures, présences</p>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="mt-4 flex justify-end">
                  <button
                    type="button"
                    class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg text-sm font-medium shadow-lg shadow-blue-500/25 transition-all"
                    @click="closeModal"
                  >
                    J'ai compris
                  </button>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </footer>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'

const currentYear = computed(() => new Date().getFullYear())

const isModalOpen = ref(false)
const mobileMenuOpen = ref(false)
const modalType = ref('')
const modalTitle = ref('')

const openModal = (type) => {
  modalType.value = type
  const titles = {
    privacy: 'Politique de confidentialité',
    consent: 'Gestion des consentements',
    data: 'Protection des données'
  }
  modalTitle.value = titles[type] || 'Information'
  isModalOpen.value = true
}

const closeModal = () => {
  isModalOpen.value = false
}

// Fermer le menu mobile si on clique ailleurs
if (typeof window !== 'undefined') {
  window.addEventListener('click', (e) => {
    if (!e.target.closest('.relative')) {
      mobileMenuOpen.value = false
    }
  })
}
</script>
