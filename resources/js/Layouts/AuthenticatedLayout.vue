<template>
  <div class="min-h-screen bg-gray-900">
    <!-- Sidebar fixe -->
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-800 transform transition-transform duration-300 ease-in-out lg:translate-x-0" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
      
      <!-- Logo -->
      <div class="flex items-center justify-center h-16 px-4 bg-gray-900 border-b border-gray-700">
        <Link :href="route('dashboard')" class="flex items-center space-x-3">
          <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
            <span class="text-white font-bold text-lg">🥋</span>
          </div>
          <div class="text-left">
            <h1 class="text-white font-bold text-sm">STUDIOS UNIS</h1>
            <p class="text-gray-400 text-xs">École de Karaté</p>
          </div>
        </Link>
      </div>

      <!-- Navigation -->
      <nav class="mt-8 px-4 space-y-2">
        
        <!-- Dashboard -->
        <Link :href="route('dashboard')" :class="isActive('dashboard')" class="nav-link">
          <span class="text-lg">📊</span>
          <span>Dashboard</span>
        </Link>
        
        <!-- Membres -->
        <Link :href="route('membres.index')" :class="isActive('membres.*')" class="nav-link">
          <span class="text-lg">👥</span>
          <span>Membres</span>
          <span v-if="stats?.total_membres" class="badge">{{ stats.total_membres }}</span>
        </Link>
        
        <!-- Présences -->
        <button @click="navigateTo('presences.tablette')" class="nav-link w-full text-left">
          <span class="text-lg">✅</span>
          <span>Présences</span>
          <span class="text-xs text-green-400">Tablette</span>
        </button>
        
        <!-- Cours -->
        <Link :href="route('cours.index')" :class="isActive('cours.*')" class="nav-link">
          <span class="text-lg">📚</span>
          <span>Cours</span>
          <span v-if="stats?.total_cours" class="badge">{{ stats.total_cours }}</span>
        </Link>
        
        <!-- Finances -->
        <Link :href="route('paiements.index')" :class="isActive('paiements.*')" class="nav-link">
          <span class="text-lg">💰</span>
          <span>Finances</span>
          <span v-if="stats?.paiements_en_retard > 0" class="badge badge-warning">{{ stats.paiements_en_retard }}</span>
        </Link>
        
        <!-- Actions rapides -->
        <div class="pt-6 mt-6 border-t border-gray-700">
          <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Actions rapides</h3>
          
          <button @click="navigateTo('membres.create')" class="action-btn bg-blue-600 hover:bg-blue-700">
            <span class="text-lg">👤</span>
            <span>Nouveau Membre</span>
          </button>
          
          <button @click="navigateTo('presences.tablette')" class="action-btn bg-green-600 hover:bg-green-700">
            <span class="text-lg">📋</span>
            <span>Prendre Présences</span>
          </button>
          
          <button @click="navigateTo('paiements.index')" class="action-btn bg-yellow-600 hover:bg-yellow-700">
            <span class="text-lg">💳</span>
            <span>Gérer Paiements</span>
          </button>
          
          <button @click="navigateTo('cours.planning')" class="action-btn bg-purple-600 hover:bg-purple-700">
            <span class="text-lg">📅</span>
            <span>Planning</span>
          </button>
        </div>
        
      </nav>

      <!-- User info en bas -->
      <div class="absolute bottom-0 left-0 right-0 p-4">
        <div class="bg-gray-700 rounded-lg p-3">
          <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
              <span class="text-white font-semibold text-sm">{{ userInitials }}</span>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-white text-sm font-medium truncate">{{ $page.props.auth.user.name }}</p>
              <p class="text-gray-400 text-xs truncate">Administrateur</p>
            </div>
          </div>
          <button @click="logout" class="mt-2 w-full text-left text-xs text-gray-400 hover:text-white transition-colors">
            Déconnexion
          </button>
        </div>
      </div>
    </div>

    <!-- Contenu principal -->
    <div class="lg:pl-64">
      <!-- Top bar mobile -->
      <div class="lg:hidden sticky top-0 z-40 bg-gray-800 border-b border-gray-700 h-16 flex items-center px-4">
        <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
        <h1 class="ml-4 text-xl font-semibold text-white">{{ pageTitle || 'Dashboard' }}</h1>
      </div>

      <!-- Contenu de la page -->
      <main class="min-h-screen bg-gray-900">
        <header v-if="$slots.header" class="bg-gray-800 shadow border-b border-gray-700">
          <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <slot name="header" />
          </div>
        </header>
        
        <slot />
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
/* Navigation links */
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

/* Action buttons */
.action-btn {
  @apply w-full flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 mb-2;
  @apply text-white;
}

.action-btn span:first-child {
  @apply mr-3;
}

/* Badges */
.badge {
  @apply px-2 py-1 text-xs font-semibold rounded-full bg-gray-600 text-gray-300;
}

.badge-warning {
  @apply bg-orange-500 text-white;
}

/* Responsive sidebar */
@media (max-width: 1024px) {
  .nav-link {
    @apply text-base py-3;
  }
  
  .action-btn {
    @apply text-base py-3;
  }
}
</style>
