<template>
  <div class="min-h-screen bg-gray-900 text-white flex flex-col">
    
    <!-- Header -->
    <header class="bg-gray-800 border-b border-gray-700 shadow-sm z-50 sticky top-0 w-full">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex justify-between items-center">
        
        <!-- Logo + Titre -->
        <div class="flex items-center space-x-4">
          <div class="w-10 h-10 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center font-bold text-white">
            S
          </div>
          <div>
            <h1 class="text-lg font-semibold leading-tight">StudiosDB Pro</h1>
            <p class="text-xs text-gray-400">Gestion de dojo v5.1</p>
          </div>
        </div>

        <!-- Utilisateur connecté -->
        <div class="flex items-center space-x-3">
          <span class="text-sm hidden md:block">{{ $page.props.auth.user.name }}</span>
          <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center font-medium">
            {{ $page.props.auth.user.name.charAt(0) }}
          </div>
        </div>
      </div>
    </header>

    <!-- Contenu global avec sidebar -->
    <div class="flex flex-1 overflow-hidden">
      
      <!-- Sidebar -->
      <aside class="w-64 bg-gray-800 border-r border-gray-700 p-4 space-y-4 hidden md:block">
        <nav class="space-y-1">
          <NavLink :href="route('dashboard')" :active="$page.url === '/dashboard'" icon="🏠">Dashboard</NavLink>
          <NavLink :href="route('membres.index')" :active="$page.url.startsWith('/membres')" icon="👥">Membres</NavLink>
          <NavLink :href="route('cours.index')" :active="$page.url.startsWith('/cours')" icon="📚">Cours</NavLink>
          <NavLink :href="route('presences.index')" :active="$page.url.startsWith('/presences')" icon="✅">Présences</NavLink>
          <NavLink :href="route('paiements.index')" :active="$page.url.startsWith('/paiements')" icon="💰">Paiements</NavLink>
        </nav>
        <div class="pt-4 border-t border-gray-700">
          <button @click="logout" class="w-full text-left text-sm text-red-400 hover:text-white transition">
            🔓 Déconnexion
          </button>
        </div>
      </aside>

      <!-- Main content -->
      <main class="flex-1 overflow-y-auto p-6">
        <slot />
      </main>

    </div>

    <!-- Footer simple -->
    <footer class="bg-gray-800 text-center text-sm text-gray-400 py-3 border-t border-gray-700">
      © {{ new Date().getFullYear() }} StudiosDB Pro · Tous droits réservés
    </footer>

  </div>
</template>

<script setup lang="ts">
import NavLink from '@/Components/Dashboard/NavLink.vue'
import { router } from '@inertiajs/vue3'

const logout = () => {
  router.post(route('logout'))
}
</script>
