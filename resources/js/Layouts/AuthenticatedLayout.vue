<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
    <!-- Sidebar moderne glassmorphism -->
    <div class="fixed inset-y-0 left-0 z-50 w-72 transform transition-transform duration-300 ease-in-out lg:translate-x-0" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
      
      <!-- Sidebar background with glassmorphism -->
      <div class="h-full bg-white/75 backdrop-blur-xl border-r border-slate-200/30 flex flex-col">
        
        <!-- Logo section -->
        <div class="flex items-center justify-center h-20 px-6 border-b border-slate-200/40">
          <Link href="/dashboard" class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-slate-200 to-slate-300 rounded-xl flex items-center justify-center">
              <span class="text-lg">🥋</span>
            </div>
            <div class="text-left">
              <h1 class="text-slate-800 font-semibold text-base">STUDIOS UNIS</h1>
              <p class="text-slate-500 text-xs">École de Karaté</p>
            </div>
          </Link>
        </div>

        <!-- Navigation moderne -->
        <nav class="flex-1 px-6 py-8 space-y-2">
          
          <!-- Dashboard -->
          <Link href="/dashboard" class="nav-link">
            <div class="flex items-center">
              <div class="w-8 h-8 rounded-lg bg-slate-100/50 hover:bg-slate-200/50 flex items-center justify-center mr-3 transition-colors">
                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
              </div>
              <span class="text-slate-700 font-medium">Dashboard</span>
            </div>
          </Link>
          
          <!-- Membres -->
          <Link href="/membres" class="nav-link">
            <div class="flex items-center">
              <div class="w-8 h-8 rounded-lg bg-slate-100/50 hover:bg-slate-200/50 flex items-center justify-center mr-3 transition-colors">
                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
              </div>
              <span class="text-slate-700 font-medium">Membres</span>
            </div>
            <span v-if="stats?.total_membres" class="badge">{{ stats.total_membres }}</span>
          </Link>
          
          <!-- Présences -->
          <Link href="/presences/tablette" class="nav-link">
            <div class="flex items-center">
              <div class="w-8 h-8 rounded-lg bg-slate-100/50 hover:bg-slate-200/50 flex items-center justify-center mr-3 transition-colors">
                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <span class="text-slate-700 font-medium">Présences</span>
            </div>
            <span class="text-xs px-2 py-1 bg-emerald-100 text-emerald-600 rounded-full font-medium">Tablette</span>
          </Link>
          
          <!-- Cours -->
          <Link href="/cours" class="nav-link">
            <div class="flex items-center">
              <div class="w-8 h-8 rounded-lg bg-slate-100/50 hover:bg-slate-200/50 flex items-center justify-center mr-3 transition-colors">
                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
              </div>
              <span class="text-slate-700 font-medium">Cours</span>
            </div>
            <span v-if="stats?.total_cours" class="badge">{{ stats.total_cours }}</span>
          </Link>
          
          <!-- Paiements -->
          <Link href="/paiements" class="nav-link">
            <div class="flex items-center">
              <div class="w-8 h-8 rounded-lg bg-slate-100/50 hover:bg-slate-200/50 flex items-center justify-center mr-3 transition-colors">
                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
              </div>
              <span class="text-slate-700 font-medium">Finances</span>
            </div>
            <span v-if="stats?.paiements_en_retard > 0" class="text-xs px-2 py-1 bg-orange-100 text-orange-600 rounded-full font-medium">{{ stats.paiements_en_retard }}</span>
          </Link>
          
          <!-- Séparateur -->
          <div class="py-6">
            <div class="border-t border-slate-200/40"></div>
          </div>
          
          <!-- Actions rapides -->
          <div class="space-y-2">
            <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-4">Actions Rapides</h3>
            
            <Link href="/membres/create" class="action-btn bg-slate-100 hover:bg-slate-200/70">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
              </svg>
              <span>Nouveau Membre</span>
            </Link>
            
            <Link href="/presences/tablette" class="action-btn bg-slate-100 hover:bg-slate-200/70">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
              </svg>
              <span>Prendre Présences</span>
            </Link>
          </div>
        </nav>

        <!-- User info moderne en bas -->
        <div class="p-6 border-t border-slate-200/40">
          <div class="glass-mini-card p-4">
            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 bg-gradient-to-br from-slate-200 to-slate-300 rounded-xl flex items-center justify-center">
                <span class="text-slate-700 font-semibold text-sm">{{ userInitials }}</span>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-slate-800 text-sm font-medium truncate">{{ userName }}</p>
                <p class="text-slate-500 text-xs truncate">Administrateur</p>
              </div>
            </div>
            <Link href="/logout" method="post" as="button" class="mt-3 w-full text-left text-xs text-slate-500 hover:text-slate-700 transition-colors">
              Déconnexion
            </Link>
          </div>
        </div>
      </div>
    </div>

    <!-- Contenu principal -->
    <div class="lg:pl-72">
      <!-- Top bar mobile -->
      <div class="lg:hidden sticky top-0 z-40 h-16 flex items-center px-4 bg-white/80 backdrop-blur-xl border-b border-slate-200/30">
        <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl text-slate-600 hover:text-slate-800 hover:bg-slate-100/50 transition-colors">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
        <h1 class="ml-4 text-lg font-medium text-slate-800">{{ pageTitle || 'Dashboard' }}</h1>
      </div>

      <!-- Contenu de la page -->
      <main class="min-h-screen">
        <header v-if="$slots.header" class="border-b border-slate-200/30">
          <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <slot name="header" />
          </div>
        </header>
        
        <slot />
      </main>
    </div>

    <!-- Overlay mobile -->
    <div v-if="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-slate-600/20 backdrop-blur-sm lg:hidden"></div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const props = defineProps({
  pageTitle: String,
  stats: {
    type: Object,
    default: () => ({})
  }
})

const sidebarOpen = ref(false)
const page = usePage()

const userName = computed(() => {
  try {
    return page.props?.auth?.user?.name || 'Utilisateur'
  } catch (e) {
    return 'Utilisateur'
  }
})

const userInitials = computed(() => {
  try {
    const name = userName.value
    return name.split(' ').map(n => n[0]).join('').toUpperCase()
  } catch (e) {
    return 'U'
  }
})
</script>

<style scoped>
/* Navigation moderne */
.nav-link {
  @apply flex items-center justify-between px-4 py-3 text-sm rounded-xl transition-all duration-200;
  @apply hover:bg-slate-100/50;
}

.nav-link:hover {
  transform: translateX(2px);
}

/* Action buttons */
.action-btn {
  @apply w-full flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200;
  @apply text-slate-700;
}

.action-btn:hover {
  transform: translateX(2px);
}

/* Mini glassmorphism card */
.glass-mini-card {
  @apply bg-white/40 backdrop-blur-sm rounded-xl border border-white/30;
  box-shadow: 0 4px 16px 0 rgba(31, 38, 135, 0.08);
}

/* Badges */
.badge {
  @apply px-2 py-1 text-xs font-medium rounded-full bg-slate-200/50 text-slate-600;
}

/* Responsive */
@media (max-width: 1024px) {
  .nav-link {
    @apply text-base py-4;
  }
  
  .action-btn {
    @apply text-base py-4;
  }
}
</style>
