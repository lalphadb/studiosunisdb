<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-900 to-gray-800">
    <!-- Sidebar fixe avec design amélioré -->
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-gray-800 to-gray-900 transform transition-transform duration-300 ease-in-out lg:translate-x-0 shadow-2xl border-r border-gray-700/50" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
      
      <!-- Logo amélioré -->
      <div class="flex items-center justify-center h-16 px-4 bg-gradient-to-r from-gray-900 to-gray-800 border-b border-gray-700/50">
        <Link :href="route('dashboard')" class="flex items-center space-x-3 group">
          <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform">
            <span class="text-white font-bold text-lg">🥋</span>
          </div>
          <div class="text-left">
            <h1 class="text-white font-bold text-sm group-hover:text-blue-400 transition-colors">STUDIOS UNIS</h1>
            <p class="text-gray-400 text-xs">École de Karaté Pro</p>
          </div>
        </Link>
      </div>

      <!-- Navigation améliorée -->
      <nav class="mt-8 px-4 space-y-2">
        
        <!-- Dashboard -->
        <Link :href="route('dashboard')" :class="isActive('dashboard')" class="nav-link group">
          <div class="flex items-center space-x-3">
            <span class="text-lg group-hover:scale-110 transition-transform">📊</span>
            <span>Dashboard</span>
          </div>
          <div class="flex items-center space-x-2">
            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse" v-if="route().current('dashboard')"></div>
          </div>
        </Link>
        
        <!-- Membres -->
        <Link :href="route('membres.index')" :class="isActive('membres.*')" class="nav-link group">
          <div class="flex items-center space-x-3">
            <span class="text-lg group-hover:scale-110 transition-transform">👥</span>
            <span>Membres</span>
          </div>
          <div class="flex items-center space-x-2">
            <span v-if="stats?.total_membres" class="badge">{{ stats.total_membres }}</span>
            <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </div>
        </Link>
        
        <!-- Présences -->
        <button @click="navigateTo('presences.tablette')" class="nav-link w-full text-left group">
          <div class="flex items-center space-x-3">
            <span class="text-lg group-hover:scale-110 transition-transform">✅</span>
            <div>
              <span>Présences</span>
              <div class="text-xs text-green-400 font-medium">Mode tablette</div>
            </div>
          </div>
          <div class="flex items-center space-x-2">
            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
            <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </div>
        </button>
        
        <!-- Cours -->
        <Link :href="route('cours.index')" :class="isActive('cours.*')" class="nav-link group">
          <div class="flex items-center space-x-3">
            <span class="text-lg group-hover:scale-110 transition-transform">📚</span>
            <span>Cours</span>
          </div>
          <div class="flex items-center space-x-2">
            <span v-if="stats?.total_cours" class="badge">{{ stats.total_cours }}</span>
            <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </div>
        </Link>
        
        <!-- Finances -->
        <Link :href="route('paiements.index')" :class="isActive('paiements.*')" class="nav-link group">
          <div class="flex items-center space-x-3">
            <span class="text-lg group-hover:scale-110 transition-transform">💰</span>
            <span>Finances</span>
          </div>
          <div class="flex items-center space-x-2">
            <span v-if="stats?.paiements_en_retard > 0" class="badge badge-warning animate-pulse">{{ stats.paiements_en_retard }}</span>
            <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </div>
        </Link>
        
        <!-- Actions rapides améliorées -->
        <div class="pt-6 mt-6 border-t border-gray-700/50">
          <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4 flex items-center">
            <span class="mr-2">⚡</span>
            Actions rapides
          </h3>
          
          <button @click="navigateTo('membres.create')" class="action-btn bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 group mb-3">
            <div class="flex items-center space-x-3">
              <span class="text-lg group-hover:scale-110 transition-transform">👤</span>
              <div class="text-left">
                <div class="font-medium">Nouveau Membre</div>
                <div class="text-blue-200 text-xs">Inscription rapide</div>
              </div>
            </div>
            <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
          </button>
          
          <button @click="navigateTo('presences.tablette')" class="action-btn bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 group mb-3">
            <div class="flex items-center space-x-3">
              <span class="text-lg group-hover:scale-110 transition-transform">📋</span>
              <div class="text-left">
                <div class="font-medium">Prendre Présences</div>
                <div class="text-green-200 text-xs">Mode tablette actif</div>
              </div>
            </div>
            <div class="w-2 h-2 bg-green-300 rounded-full animate-pulse"></div>
          </button>
          
          <button @click="navigateTo('paiements.index')" class="action-btn bg-gradient-to-r from-yellow-600 to-yellow-700 hover:from-yellow-700 hover:to-yellow-800 group mb-3">
            <div class="flex items-center space-x-3">
              <span class="text-lg group-hover:scale-110 transition-transform">💳</span>
              <div class="text-left">
                <div class="font-medium">Gérer Paiements</div>
                <div class="text-yellow-200 text-xs" v-if="stats?.paiements_en_retard">{{ stats.paiements_en_retard }} en retard</div>
                <div class="text-yellow-200 text-xs" v-else>Tout à jour</div>
              </div>
            </div>
            <div v-if="stats?.paiements_en_retard > 0" class="w-2 h-2 bg-red-400 rounded-full animate-pulse"></div>
          </button>
          
          <button @click="navigateTo('cours.planning')" class="action-btn bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 group">
            <div class="flex items-center space-x-3">
              <span class="text-lg group-hover:scale-110 transition-transform">📅</span>
              <div class="text-left">
                <div class="font-medium">Planning</div>
                <div class="text-purple-200 text-xs">{{ stats?.total_cours || 0 }} cours actifs</div>
              </div>
            </div>
            <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
          </button>
        </div>
        
      </nav>

      <!-- User info en bas amélioré -->
      <div class="absolute bottom-0 left-0 right-0 p-4">
        <div class="bg-gradient-to-r from-gray-700 to-gray-800 rounded-xl p-4 border border-gray-600/50 shadow-lg">
          <div class="flex items-center space-x-3 mb-3">
            <div class="relative">
              <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center shadow-lg">
                <span class="text-white font-semibold text-sm">{{ userInitials }}</span>
              </div>
              <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-400 border-2 border-gray-800 rounded-full"></div>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-white text-sm font-medium truncate">{{ $page.props.auth.user.name }}</p>
              <p class="text-gray-400 text-xs truncate flex items-center">
                <span class="mr-1">👤</span>
                Administrateur
              </p>
            </div>
          </div>
          
          <!-- Statistiques rapides pour l'admin -->
          <div class="grid grid-cols-2 gap-2 mb-3 text-xs">
            <div class="bg-gray-600/30 rounded-lg p-2 text-center">
              <div class="text-green-400 font-semibold">{{ stats?.total_membres || 0 }}</div>
              <div class="text-gray-400">Membres</div>
            </div>
            <div class="bg-gray-600/30 rounded-lg p-2 text-center">
              <div class="text-blue-400 font-semibold">{{ stats?.presences_aujourd_hui || 0 }}</div>
              <div class="text-gray-400">Présents</div>
            </div>
          </div>
          
          <button @click="logout" class="w-full text-left text-xs text-gray-400 hover:text-white transition-colors flex items-center space-x-2 py-2 px-2 rounded-lg hover:bg-gray-600/30">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            <span>Déconnexion</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Contenu principal amélioré -->
    <div class="lg:pl-64">
      <!-- Top bar mobile amélioré -->
      <div class="lg:hidden sticky top-0 z-40 bg-gradient-to-r from-gray-800 to-gray-900 backdrop-blur-sm border-b border-gray-700/50 h-16 flex items-center px-4 shadow-lg">
        <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl text-gray-400 hover:text-white hover:bg-gray-700/50 transition-all">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
        <h1 class="ml-4 text-xl font-semibold text-white">{{ pageTitle || 'Dashboard' }}</h1>
        
        <!-- Notifications mobiles -->
        <div class="ml-auto flex items-center space-x-2">
          <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
          <span class="text-green-400 text-xs font-medium">En ligne</span>
        </div>
      </div>

      <!-- Contenu de la page avec fond amélioré -->
      <main class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-900 to-gray-800 relative">
        <!-- Overlay décoratif -->
        <div class="absolute inset-0 bg-grid-pattern opacity-5 pointer-events-none"></div>
        
        <header v-if="$slots.header" class="bg-gradient-to-r from-gray-800/80 to-gray-900/80 backdrop-blur-sm shadow-2xl border-b border-gray-700/50 relative">
          <div class="absolute inset-0 bg-gradient-to-r from-blue-600/5 to-purple-600/5"></div>
          <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 relative z-10">
            <slot name="header" />
          </div>
        </header>
        
        <div class="relative z-10">
          <slot />
        </div>
      </main>
    </div>

    <!-- Overlay mobile -->
    <div v-if="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"></div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'

const props = defineProps({
  pageTitle: String,
  stats: {
    type: Object,
    default: () => ({})
  }
})

const page = usePage()
const sidebarOpen = ref(false)

const userInitials = computed(() => {
  const name = page.props.auth.user.name
  return name.split(' ').map(n => n[0]).join('').toUpperCase()
})

const isActive = (routeName) => {
  return route().current(routeName) 
    ? 'nav-link active' 
    : 'nav-link'
}

const navigateTo = (routeName, params = {}) => {
  router.visit(route(routeName, params))
  sidebarOpen.value = false
}

const logout = () => {
  router.post(route('logout'))
}
</script>

<style scoped>
/* Navigation links améliorés */
.nav-link {
  @apply flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 mb-1;
  @apply text-gray-300 hover:bg-gray-700 hover:text-white;
}

.nav-link.active {
  @apply bg-blue-600 text-white shadow-lg;
}

.nav-link span:first-child {
  @apply mr-3;
}

/* Action buttons améliorés */
.action-btn {
  @apply w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 mb-2;
  @apply text-white shadow-lg;
}

.action-btn span:first-child {
  @apply mr-3;
}

/* Badges améliorés */
.badge {
  @apply px-2 py-1 text-xs font-semibold rounded-full bg-gray-600 text-gray-300;
}

.badge-warning {
  @apply bg-orange-500 text-white;
}

/* Motif de grille décoratif */
.bg-grid-pattern {
  background-image: 
    linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
  background-size: 50px 50px;
}

/* Animations personnalisées */
@keyframes slideInLeft {
  from {
    opacity: 0;
    transform: translateX(-20px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.nav-link, .action-btn {
  animation: slideInLeft 0.3s ease-out forwards;
}

.nav-link:nth-child(1) { animation-delay: 0ms; }
.nav-link:nth-child(2) { animation-delay: 50ms; }
.nav-link:nth-child(3) { animation-delay: 100ms; }
.nav-link:nth-child(4) { animation-delay: 150ms; }
.nav-link:nth-child(5) { animation-delay: 200ms; }

.action-btn:nth-child(1) { animation-delay: 250ms; }
.action-btn:nth-child(2) { animation-delay: 300ms; }
.action-btn:nth-child(3) { animation-delay: 350ms; }
.action-btn:nth-child(4) { animation-delay: 400ms; }

/* Hover effects avancés */
.nav-link:hover, .action-btn:hover {
  transform: translateX(2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

/* Responsive amélioré */
@media (max-width: 1024px) {
  .nav-link {
    @apply text-base py-3;
  }
  
  .action-btn {
    @apply text-base py-3;
  }
}
</style>
