<template>
  <div class="min-h-screen bg-gray-900 text-white">
    <!-- Sidebar -->
    <div
      :class="[
        'fixed inset-y-0 left-0 z-50 w-64 bg-gray-800 transform transition-transform duration-300 ease-in-out',
        sidebarOpen ? 'translate-x-0' : '-translate-x-full',
        'lg:translate-x-0 lg:static lg:inset-0'
      ]"
    >
      <!-- Logo -->
      <div class="flex items-center justify-center h-16 px-4 bg-gray-900">
        <Link href="/dashboard" class="flex items-center space-x-3">
          <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
            <span class="text-white font-bold text-xl">🥋</span>
          </div>
          <div>
            <h1 class="text-white font-bold text-lg">STUDIOS UNIS</h1>
            <p class="text-gray-400 text-xs">École de Karaté</p>
          </div>
        </Link>
      </div>

      <!-- Navigation -->
      <nav class="mt-8 px-4">
        <div class="space-y-2">
          <!-- Dashboard -->
          <NavItem
            href="/dashboard"
            :active="route().current('dashboard')"
            icon="📊"
            label="Dashboard"
          />
          
          <!-- Membres -->
          <NavItem
            href="/membres"
            :active="route().current('membres.*')"
            icon="👥"
            label="Membres"
            :badge="stats?.membres_actifs"
          />
          
          <!-- Présences -->
          <NavItem
            href="/presences"
            :active="route().current('presences.*')"
            icon="✅"
            label="Présences"
          />
          
          <!-- Cours -->
          <NavItem
            href="/cours"
            :active="route().current('cours.*')"
            icon="📚"
            label="Cours"
            :badge="stats?.total_cours"
          />
          
          <!-- Finances -->
          <NavItem
            href="/paiements"
            :active="route().current('paiements.*')"
            icon="💰"
            label="Finances"
            :badge="stats?.paiements_en_retard > 0 ? stats.paiements_en_retard : null"
            badgeType="warning"
          />
          
          <!-- Ceintures -->
          <NavItem
            v-if="canManageBelts"
            href="/ceintures"
            :active="route().current('ceintures.*')"
            icon="🥋"
            label="Ceintures"
          />
          
          <!-- Rapports -->
          <NavItem
            v-if="canViewReports"
            href="/rapports"
            :active="route().current('rapports.*')"
            icon="📈"
            label="Rapports"
          />
          
          <!-- Administration -->
          <NavItem
            v-if="canAdminister"
            href="/admin"
            :active="route().current('admin.*')"
            icon="⚙️"
            label="Administration"
          />
        </div>
      </nav>

      <!-- User section -->
      <div class="absolute bottom-0 left-0 right-0 p-4">
        <div class="bg-gray-700 rounded-lg p-3">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
              <span class="text-white font-semibold text-sm">
                {{ userInitials }}
              </span>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-white text-sm font-medium truncate">
                {{ $page.props.auth.user.name }}
              </p>
              <p class="text-gray-400 text-xs truncate">
                {{ userRole }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <div class="lg:pl-64">
      <!-- Top bar -->
      <div class="sticky top-0 z-40 bg-gray-800 border-b border-gray-700 h-16">
        <div class="flex items-center justify-between h-full px-4 sm:px-6 lg:px-8">
          <!-- Mobile menu button -->
          <button
            @click="sidebarOpen = !sidebarOpen"
            class="lg:hidden p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
          >
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>

          <!-- Page title -->
          <div class="flex-1 lg:flex-none">
            <h1 v-if="pageTitle" class="text-xl font-semibold text-white">
              {{ pageTitle }}
            </h1>
          </div>

          <!-- Top bar actions -->
          <div class="flex items-center space-x-4">
            <!-- Role selector -->
            <div class="relative">
              <Dropdown align="right" width="48">
                <template #trigger>
                  <button
                    type="button"
                    class="bg-gray-700 text-white text-sm rounded-lg px-3 py-2 hover:bg-gray-600 transition-colors duration-200 flex items-center space-x-2"
                  >
                    <span>{{ userRole }}</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </button>
                </template>

                <template #content>
                  <div class="py-1 bg-gray-700 rounded-md shadow-lg">
                    <a
                      v-for="role in availableRoles"
                      :key="role.value"
                      href="#"
                      @click.prevent="switchRole(role.value)"
                      class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-600 hover:text-white"
                    >
                      {{ role.label }}
                    </a>
                  </div>
                </template>
              </Dropdown>
            </div>

            <!-- Notifications -->
            <button
              class="p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-colors duration-200 relative"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
              </svg>
              <!-- Badge de notification -->
              <span
                v-if="notificationCount > 0"
                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"
              >
                {{ notificationCount > 9 ? '9+' : notificationCount }}
              </span>
            </button>

            <!-- User menu -->
            <Dropdown align="right" width="48">
              <template #trigger>
                <button
                  type="button"
                  class="flex items-center space-x-3 text-sm rounded-lg hover:bg-gray-700 p-2 transition-colors duration-200"
                >
                  <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                    <span class="text-white font-semibold text-sm">
                      {{ userInitials }}
                    </span>
                  </div>
                  <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
              </template>

              <template #content>
                <div class="py-1 bg-gray-700 rounded-md shadow-lg">
                  <DropdownLink :href="route('profile.edit')" class="text-gray-300 hover:bg-gray-600 hover:text-white">
                    Mon Profil
                  </DropdownLink>
                  <DropdownLink href="#" class="text-gray-300 hover:bg-gray-600 hover:text-white">
                    Paramètres
                  </DropdownLink>
                  <hr class="border-gray-600 my-1">
                  <DropdownLink
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="text-gray-300 hover:bg-gray-600 hover:text-white w-full text-left"
                  >
                    Déconnexion
                  </DropdownLink>
                </div>
              </template>
            </Dropdown>
          </div>
        </div>
      </div>

      <!-- Page content -->
      <main class="min-h-screen bg-gray-900">
        <slot />
      </main>
    </div>

    <!-- Mobile sidebar overlay -->
    <div
      v-if="sidebarOpen"
      @click="sidebarOpen = false"
      class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"
    ></div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'

const props = defineProps({
  pageTitle: {
    type: String,
    default: ''
  },
  stats: {
    type: Object,
    default: () => ({})
  }
})

const page = usePage()
const sidebarOpen = ref(false)
const notificationCount = ref(3) // À remplacer par de vraies données

// Computed properties
const userInitials = computed(() => {
  const name = page.props.auth.user.name
  return name.split(' ').map(n => n[0]).join('').toUpperCase()
})

const userRole = computed(() => {
  const user = page.props.auth.user
  if (user.roles && user.roles.length > 0) {
    const roleNames = {
      'superadmin': 'Super Admin',
      'admin': 'Administrateur',
      'instructeur': 'Instructeur',
      'membre': 'Membre'
    }
    return roleNames[user.roles[0].name] || 'Utilisateur'
  }
  return 'Utilisateur'
})

const availableRoles = computed(() => {
  // Retourne les rôles disponibles pour l'utilisateur
  const user = page.props.auth.user
  const roles = []
  
  if (user.roles) {
    user.roles.forEach(role => {
      const roleLabels = {
        'superadmin': 'Super Admin',
        'admin': 'Administrateur',
        'instructeur': 'Instructeur',
        'membre': 'Membre'
      }
      
      roles.push({
        value: role.name,
        label: roleLabels[role.name] || role.name
      })
    })
  }
  
  return roles
})

const canManageBelts = computed(() => {
  return hasRole(['admin', 'superadmin', 'instructeur'])
})

const canViewReports = computed(() => {
  return hasRole(['admin', 'superadmin'])
})

const canAdminister = computed(() => {
  return hasRole(['admin', 'superadmin'])
})

// Helper function
const hasRole = (roles) => {
  const user = page.props.auth.user
  if (!user.roles) return false
  
  return user.roles.some(role => roles.includes(role.name))
}

const switchRole = (role) => {
  // Logique pour changer de rôle si nécessaire
  console.log('Switching to role:', role)
}

// Navigation Item Component
const NavItem = {
  props: {
    href: String,
    active: Boolean,
    icon: String,
    label: String,
    badge: [String, Number],
    badgeType: {
      type: String,
      default: 'info'
    }
  },
  template: `
    <Link
      :href="href"
      :class="[
        'flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200',
        active
          ? 'bg-blue-600 text-white shadow-lg transform scale-105'
          : 'text-gray-300 hover:bg-gray-700 hover:text-white hover:scale-105'
      ]"
    >
      <div class="flex items-center space-x-3">
        <span class="text-lg">{{ icon }}</span>
        <span>{{ label }}</span>
      </div>
      <span
        v-if="badge"
        :class="[
          'px-2 py-1 text-xs font-semibold rounded-full',
          badgeType === 'warning' ? 'bg-orange-500 text-white' : 'bg-gray-600 text-gray-300'
        ]"
      >
        {{ badge }}
      </span>
    </Link>
  `
}

// Auto-close sidebar on route change
onMounted(() => {
  // Fermer la sidebar sur mobile lors du changement de route
  page.props.auth && (sidebarOpen.value = false)
})
</script>

<style scoped>
/* Transition fluide pour la sidebar */
.sidebar-transition {
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Effet de blur pour l'overlay */
.backdrop-blur {
  backdrop-filter: blur(4px);
}

/* Animation pour les liens de navigation */
.nav-link {
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.nav-link:hover {
  transform: translateX(4px);
}

/* Scrollbar personnalisée */
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: #374151;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #6B7280;
  border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #9CA3AF;
}
</style>
