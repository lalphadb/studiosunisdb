<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-950 via-gray-900 to-slate-950">
    <!-- Sidebar Premium avec Glassmorphism -->
  <div class="fixed inset-y-0 left-0 z-50 transition-all duration-500 ease-out"
     :class="[sidebarOpen ? 'w-72 translate-x-0' : 'w-20 -translate-x-full lg:translate-x-0']">
      
      <!-- Effet de lumière décoratif -->
      <div class="absolute inset-0 bg-gradient-to-b from-blue-600/10 via-transparent to-purple-600/10 pointer-events-none"></div>
      
      <!-- Sidebar Content -->
  <div class="relative h-full bg-slate-900/95 backdrop-blur-2xl border-r border-slate-800/50 flex flex-col shadow-2xl">
        
        <!-- Header avec logo premium -->
  <div class="relative h-24 flex items-center justify-center px-2 overflow-hidden">
    <!-- Gradient statique de fond -->
    <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 via-indigo-600/20 to-purple-600/20"></div>
    <Link href="/dashboard" class="relative flex items-center group">
      <div class="relative">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl blur-lg opacity-75 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative w-14 h-14 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-xl transform group-hover:scale-110 transition-transform">
          <span class="text-white text-3xl font-bold">🥋</span>
        </div>
      </div>
      <transition name="fade">
        <div v-if="sidebarOpen" class="ml-3 flex flex-col justify-center">
          <h1 class="text-white font-extrabold text-xl tracking-tight leading-tight">StudiosDB</h1>
          <p class="text-indigo-200 text-xs font-medium leading-tight">École de Karaté</p>
        </div>
      </transition>
    </Link>
  </div>

        <!-- Navigation élégante -->
  <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
          
          <!-- Menu principal -->
          <div class="space-y-1">
            <!-- Dashboard -->
            <Link 
              href="/dashboard" 
              class="nav-item group"
              :class="{ 'active': currentPath === '/dashboard' }"
            >
              <div class="nav-icon bg-gradient-to-br from-blue-500/20 to-blue-600/20 group-hover:from-blue-500/30 group-hover:to-blue-600/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" />
                </svg>
              </div>
              <span v-if="sidebarOpen" class="nav-label">Dashboard</span>
              <div v-if="sidebarOpen && currentPath === '/dashboard'" class="nav-indicator"></div>
            </Link>

            <!-- Membres -->
            <Link 
              href="/membres" 
              class="nav-item group"
              :class="{ 'active': currentPath.startsWith('/membres') }"
            >
              <div class="nav-icon bg-gradient-to-br from-emerald-500/20 to-emerald-600/20 group-hover:from-emerald-500/30 group-hover:to-emerald-600/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
              </div>
              <span v-if="sidebarOpen" class="nav-label">Membres</span>
              <span v-if="sidebarOpen && stats?.total_membres" class="nav-badge bg-emerald-500/20 text-emerald-400">
                {{ stats.total_membres }}
              </span>
            </Link>

            <!-- Cours -->
            <Link 
              href="/cours" 
              class="nav-item group"
              :class="{ 'active': currentPath.startsWith('/cours') }"
            >
              <div class="nav-icon bg-gradient-to-br from-purple-500/20 to-purple-600/20 group-hover:from-purple-500/30 group-hover:to-purple-600/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
              </div>
              <span v-if="sidebarOpen" class="nav-label">Cours</span>
              <span v-if="sidebarOpen && stats?.total_cours" class="nav-badge bg-purple-500/20 text-purple-400">
                {{ stats.total_cours }}
              </span>
            </Link>

            <!-- Présences -->
            <Link 
              href="/presences/tablette" 
              class="nav-item group"
              :class="{ 'active': currentPath.startsWith('/presences') }"
            >
              <div class="nav-icon bg-gradient-to-br from-green-500/20 to-green-600/20 group-hover:from-green-500/30 group-hover:to-green-600/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <span v-if="sidebarOpen" class="nav-label">Présences</span>
              <span v-if="sidebarOpen" class="ml-auto text-[10px] px-2 py-0.5 bg-green-500/20 text-green-400 rounded-full font-bold uppercase tracking-wider">
                Tablette
              </span>
            </Link>

            <!-- Finances -->
            <Link 
              href="/paiements" 
              class="nav-item group"
              :class="{ 'active': currentPath.startsWith('/paiements') }"
            >
              <div class="nav-icon bg-gradient-to-br from-amber-500/20 to-amber-600/20 group-hover:from-amber-500/30 group-hover:to-amber-600/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <span v-if="sidebarOpen" class="nav-label">Finances</span>
              <span v-if="sidebarOpen && stats?.paiements_en_retard > 0" class="nav-badge bg-red-500/20 text-red-400 animate-pulse">
                {{ stats.paiements_en_retard }}
              </span>
            </Link>
          </div>

          <!-- Séparateur élégant -->
          <div class="my-6 px-3">
            <div class="h-px bg-gradient-to-r from-transparent via-slate-700 to-transparent"></div>
          </div>

          <!-- Actions rapides -->
          <div v-if="sidebarOpen" class="space-y-1">
            <p class="px-3 mb-2 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Actions rapides</p>
            
            <button @click="navigateTo('/membres/create')" 
                    class="quick-action group">
              <div class="quick-action-icon">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
              </div>
              <span>Nouveau membre</span>
              <svg class="w-4 h-4 ml-auto opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all" 
                   fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </button>

            <button @click="navigateTo('/presences/tablette')" 
                    class="quick-action group">
              <div class="quick-action-icon">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
              </div>
              <span>Prendre présences</span>
              <svg class="w-4 h-4 ml-auto opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all" 
                   fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </button>
          </div>
        </nav>

        <!-- Profil utilisateur en bas (avec Footer compact intégré) -->
        <div class="border-t border-slate-800/50">
          <!-- Profil -->
          <div class="p-4">
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-xl p-4">
              <!-- Effet lumineux -->
              <div class="absolute inset-0 bg-gradient-to-br from-blue-600/5 via-transparent to-purple-600/5"></div>
              
              <div class="relative flex items-center space-x-3">
                <div class="relative group">
                  <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl blur opacity-50 group-hover:opacity-75 transition-opacity"></div>
                  <div class="relative w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold shadow-lg">
                    {{ userInitials }}
                  </div>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-semibold text-white truncate">{{ userName }}</p>
                  <p class="text-xs text-slate-400 truncate">{{ userRole }}</p>
                </div>
                <button @click="logout" 
                        class="p-2 rounded-lg hover:bg-red-500/10 text-slate-400 hover:text-red-400 transition-all group">
                  <svg class="w-4 h-4 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
          
          <!-- Footer compact intégré -->
          <div class="px-4 pb-4">
            <div class="text-center space-y-1">
              <div class="flex items-center justify-center space-x-2 text-[10px] text-slate-500">
                <span>© 2025 Studios Unis</span>
                <span>•</span>
                <button @click="openLoi25Modal" class="hover:text-blue-400 transition-colors">
                  Loi 25
                </button>
                <span>•</span>
                <span>v5.0</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Zone de contenu principal collé au sidebar -->
    <div class="transition-all duration-500 ease-out" :class="[sidebarOpen ? 'lg:pl-72' : 'lg:pl-20']">
      <!-- Header pour mobile et desktop -->
  <div class="sticky top-0 z-40 flex items-center h-16 px-4 bg-slate-900/95 backdrop-blur-xl border-b border-slate-800/50">
    <button @click="toggleSidebar" 
            class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/50 transition-all"
            :title="sidebarOpen ? 'Réduire la sidebar' : 'Agrandir la sidebar'">
      <!-- Icône hamburger sur mobile, chevron sur desktop -->
      <svg v-if="isMobile" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
      <svg v-else class="h-6 w-6 transition-transform" :class="{'rotate-180': !sidebarOpen}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
      </svg>
    </button>
    <div class="flex items-center ml-4 space-x-3 lg:hidden">
      <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-xl">
        <span class="text-white text-2xl font-bold">🥋</span>
      </div>
      <div class="flex flex-col justify-center">
  <h1 class="text-white font-extrabold text-lg leading-tight">StudiosDB</h1>
        <p class="text-indigo-200 text-xs leading-tight">École de Karaté</p>
      </div>
    </div>
    <div class="ml-auto flex items-center space-x-4">
      <div class="text-right hidden md:block">
        <p class="text-base font-semibold text-white">{{ userName }}</p>
        <p class="text-xs text-indigo-200">{{ userRole }}</p>
      </div>
      <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg">
        <span class="text-white text-lg font-bold">{{ userInitials }}</span>
      </div>
    </div>
  </div>

      <!-- Contenu de la page -->
      <main>
        <!-- Header de page optionnel -->
        <header v-if="$slots.header" class="border-b border-slate-800/50 bg-slate-900/50 backdrop-blur-sm">
          <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <slot name="header" />
          </div>
        </header>
        
        <!-- Contenu principal sans padding horizontal sur les bords -->
        <div>
          <slot />
        </div>
      </main>
    </div>

    <!-- Overlay mobile seulement -->
    <transition
      enter-active-class="transition-opacity ease-out duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity ease-in duration-200"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div v-if="sidebarOpen && isMobile" @click="sidebarOpen = false" 
           class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm lg:hidden"></div>
    </transition>

    <!-- Modal Loi 25 simple -->
    <TransitionRoot appear :show="showLoi25Modal" as="template">
      <Dialog as="div" @close="showLoi25Modal = false" class="relative z-50">
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
              <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-gradient-to-b from-slate-800 to-slate-900 p-6 shadow-2xl transition-all border border-slate-700/50">
                <DialogTitle as="h3" class="text-lg font-bold text-white mb-4">
                  Conformité Loi 25
                </DialogTitle>
                
                <div class="text-sm text-slate-300 space-y-3">
                  <p>StudiosDB est conforme à la Loi 25 du Québec sur la protection des renseignements personnels.</p>
                  <ul class="space-y-2">
                    <li class="flex items-start">
                      <span class="text-green-400 mr-2">✓</span>
                      <span>Collecte minimale des données</span>
                    </li>
                    <li class="flex items-start">
                      <span class="text-green-400 mr-2">✓</span>
                      <span>Consentement explicite requis</span>
                    </li>
                    <li class="flex items-start">
                      <span class="text-green-400 mr-2">✓</span>
                      <span>Droit d'accès et de rectification</span>
                    </li>
                    <li class="flex items-start">
                      <span class="text-green-400 mr-2">✓</span>
                      <span>Sécurité renforcée des données</span>
                    </li>
                  </ul>
                  <p class="text-xs text-slate-500 mt-4">
                    Contact DPO : dpo@studiosdb.ca
                  </p>
                </div>

                <div class="mt-6">
                  <button
                    type="button"
                    class="w-full px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-medium transition-all"
                    @click="showLoi25Modal = false"
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
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'

const props = defineProps({
  pageTitle: String,
  stats: {
    type: Object,
    default: () => ({})
  }
})

// Détection mobile/desktop
const isMobile = ref(typeof window !== 'undefined' ? window.innerWidth < 1024 : false)

// État de la sidebar avec persistance localStorage
const sidebarOpen = ref(
  !isMobile.value 
    ? (localStorage.getItem('sidebarOpen') !== 'false') // Desktop: true par défaut
    : false // Mobile: fermé par défaut
)
const showLoi25Modal = ref(false)
const page = usePage()

// Gestion du resize
const handleResize = () => {
  const wasMobile = isMobile.value
  isMobile.value = window.innerWidth < 1024
  
  // Si on passe de mobile à desktop, restaurer l'état sauvegardé
  if (wasMobile && !isMobile.value) {
    sidebarOpen.value = localStorage.getItem('sidebarOpen') !== 'false'
  }
  // Si on passe de desktop à mobile, fermer la sidebar
  else if (!wasMobile && isMobile.value) {
    sidebarOpen.value = false
  }
}

onMounted(() => {
  window.addEventListener('resize', handleResize)
})

onUnmounted(() => {
  window.removeEventListener('resize', handleResize)
})

// Fonction pour toggle la sidebar avec logique responsive
const toggleSidebar = () => {
  sidebarOpen.value = !sidebarOpen.value
  // Sauvegarder la préférence sur desktop seulement
  if (!isMobile.value) {
    localStorage.setItem('sidebarOpen', sidebarOpen.value.toString())
  }
}

const userName = computed(() => page.props?.auth?.user?.name || 'Utilisateur')
const userRole = computed(() => {
  const roles = page.props?.auth?.user?.roles || []
  if (roles.includes('superadmin')) return 'Super Admin'
  if (roles.includes('admin_ecole')) return 'Administrateur'
  if (roles.includes('instructeur')) return 'Instructeur'
  if (roles.includes('membre')) return 'Membre'
  return 'Utilisateur'
})

const userInitials = computed(() => {
  return userName.value
    .split(' ')
    .map(n => n[0])
    .join('')
    .toUpperCase()
    .slice(0, 2)
})

// Navigation simplifiée
const navigateTo = (url) => {
  router.visit(url)
}

const logout = () => {
  router.post('/logout')
}

const openLoi25Modal = () => {
  showLoi25Modal.value = true
}

// Path actuel pour détection active
const currentPath = computed(() => {
  return typeof window !== 'undefined' ? window.location.pathname : '/'
})
</script>

<style scoped>
/* Gradient statique - animation supprimée */

/* Navigation items */
.nav-item {
  @apply relative flex items-center px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 transition-all duration-200;
  @apply hover:bg-slate-800/50 hover:text-white;
}

.nav-item.active {
  @apply bg-gradient-to-r from-slate-800/50 to-slate-800/30 text-white shadow-lg;
}

.nav-item:hover {
  transform: translateX(4px);
}

.nav-icon {
  @apply w-9 h-9 rounded-xl flex items-center justify-center mr-3 transition-all duration-200;
}

.nav-label {
  @apply flex-1;
}

.nav-badge {
  @apply ml-auto px-2 py-0.5 text-[11px] font-bold rounded-full;
}

.nav-indicator {
  @apply absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-gradient-to-b from-blue-400 to-purple-600 rounded-r-full;
  box-shadow: 0 0 12px rgba(96, 165, 250, 0.5);
}

/* Quick actions */
.quick-action {
  @apply w-full flex items-center px-3 py-2.5 rounded-xl text-sm text-slate-400 transition-all duration-200;
  @apply hover:bg-slate-800/50 hover:text-white;
}

.quick-action:hover {
  transform: translateX(4px);
}

.quick-action-icon {
  @apply w-8 h-8 rounded-lg bg-slate-800/50 flex items-center justify-center mr-3;
  @apply group-hover:bg-blue-500/20;
}

/* Modern transitions */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
