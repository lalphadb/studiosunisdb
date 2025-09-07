<template>
  <AuthenticatedLayout>
    <!-- Background gradient sombre -->
    <div class="fixed inset-0 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 -z-10"></div>
    
    <!-- Pattern overlay -->
    <div class="fixed inset-0 opacity-20 -z-10" style="background-image: url('data:image/svg+xml,%3Csvg width=%2260%22 height=%2260%22 viewBox=%220 0 60 60%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cg fill=%22none%22 fill-rule=%22evenodd%22%3E%3Cg fill=%22%239C92AC%22 fill-opacity=%220.03%22%3E%3Cpath d=%22M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')"></div>
    
    <div class="p-6 max-w-7xl mx-auto">
      <!-- Header adaptatif -->
      <div class="mb-8">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
          {{ dashboardTitle }}
        </h1>
        <p class="mt-2 text-slate-400">
          {{ dashboardSubtitle }}
        </p>
      </div>
      
      <!-- DASHBOARD ADMIN/INSTRUCTEUR -->
      <div v-if="isAdminOrInstructor">
        <!-- Stats principales ADMIN -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
          <!-- Membres actifs -->
          <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 backdrop-blur-xl p-6">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500/20 to-blue-600/20 flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
              </div>
              <span class="text-xs px-2 py-1 rounded-full bg-emerald-500/20 text-emerald-400">+12%</span>
            </div>
            <div class="text-xl sm:text-2xl xl:text-3xl font-bold text-white mb-1 truncate">{{ stats.membres_actifs }}</div>
            <div class="text-sm text-slate-400">Membres actifs</div>
            <div class="text-xs text-slate-500 mt-1">Actifs 30 derniers jours</div>
          </div>
          
          <!-- Cours actifs -->
          <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 backdrop-blur-xl p-6">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500/20 to-purple-600/20 flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
              </div>
              <span class="text-xs px-2 py-1 rounded-full bg-purple-500/20 text-purple-400">+5</span>
            </div>
            <div class="text-xl sm:text-2xl xl:text-3xl font-bold text-white mb-1 truncate">{{ stats.cours_actifs }}</div>
            <div class="text-sm text-slate-400">Cours actifs</div>
            <div class="text-xs text-slate-500 mt-1">Sessions planifiées</div>
          </div>
          
          <!-- Taux de présence -->
          <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 backdrop-blur-xl p-6">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500/20 to-emerald-600/20 flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <span class="text-xs px-2 py-1 rounded-full bg-emerald-500/20 text-emerald-400">+3%</span>
            </div>
            <div class="text-xl sm:text-2xl xl:text-3xl font-bold text-white mb-1 truncate">{{ stats.taux_presence }}%</div>
            <div class="text-sm text-slate-400">Taux de présence</div>
            <div class="text-xs text-slate-500 mt-1">Semaine en cours</div>
          </div>
          
          <!-- Paiements en retard (admin seulement) -->
          <div v-if="role === 'admin' || role === 'superadmin'" class="rounded-2xl border border-slate-700/50 bg-slate-900/60 backdrop-blur-xl p-6">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500/20 to-amber-600/20 flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <span class="text-xs px-2 py-1 rounded-full bg-red-500/20 text-red-400">-2</span>
            </div>
            <div class="text-xl sm:text-2xl xl:text-3xl font-bold text-white mb-1 truncate">{{ stats.paiements_retard }}</div>
            <div class="text-sm text-slate-400">Paiements en retard</div>
            <div class="text-xs text-slate-500 mt-1">À relancer</div>
          </div>
          
          <!-- Revenus mois (admin seulement) -->
          <div v-if="role === 'admin' || role === 'superadmin'" class="rounded-2xl border border-slate-700/50 bg-slate-900/60 backdrop-blur-xl p-6">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500/20 to-indigo-600/20 flex items-center justify-center">
                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
              </div>
              <span class="text-xs px-2 py-1 rounded-full bg-emerald-500/20 text-emerald-400">+18%</span>
            </div>
            <div class="text-xl sm:text-2xl xl:text-3xl font-bold text-white mb-1 truncate">{{ formatCurrency(stats.revenus_mois) }}</div>
            <div class="text-sm text-slate-400">Revenus mois</div>
            <div class="text-xs text-slate-500 mt-1">Chiffre d'affaires</div>
          </div>
        </div>
        
        <!-- Actions rapides ADMIN -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Actions rapides admin -->
          <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 backdrop-blur-xl p-6">
            <h2 class="text-xl font-semibold text-white mb-6">Actions rapides</h2>
            
            <div class="grid grid-cols-2 gap-4">
              <Link href="/membres/create"
                    class="p-4 rounded-xl bg-slate-800/50 hover:bg-slate-700/50 border border-slate-700 transition group cursor-pointer">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500/20 to-blue-600/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                  </div>
                  <div>
                    <div class="text-sm font-medium text-white">Nouveau membre</div>
                    <div class="text-xs text-slate-400">Inscription</div>
                  </div>
                </div>
              </Link>
              
              <Link href="/presences"
                    class="p-4 rounded-xl bg-slate-800/50 hover:bg-slate-700/50 border border-slate-700 transition group cursor-pointer">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-emerald-500/20 to-emerald-600/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                  </div>
                  <div>
                    <div class="text-sm font-medium text-white">Présences</div>
                    <div class="text-xs text-slate-400">Prendre présences</div>
                  </div>
                </div>
              </Link>
              
              <Link href="/cours"
                    class="p-4 rounded-xl bg-slate-800/50 hover:bg-slate-700/50 border border-slate-700 transition group cursor-pointer">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500/20 to-purple-600/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                  </div>
                  <div>
                    <div class="text-sm font-medium text-white">Cours</div>
                    <div class="text-xs text-slate-400">Gérer planning</div>
                  </div>
                </div>
              </Link>
              
              <Link href="/membres"
                   class="p-4 rounded-xl bg-slate-800/50 hover:bg-slate-700/50 border border-slate-700 transition group cursor-pointer">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500/20 to-indigo-600/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                  </div>
                  <div>
                    <div class="text-sm font-medium text-white">Membres</div>
                    <div class="text-xs text-slate-400">Gérer membres</div>
                  </div>
                </div>
              </Link>
            </div>
          </div>
          
          <!-- Activité récente admin -->
          <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 backdrop-blur-xl p-6">
            <h2 class="text-xl font-semibold text-white mb-6">Activité récente</h2>
            
            <div class="space-y-4 max-h-80 overflow-y-auto scrollbar-hide">
              <div v-for="activity in recentActivities" :key="activity.id"
                   class="flex items-start gap-3 p-3 rounded-xl hover:bg-slate-800/30 transition cursor-pointer group">
                <div :class="`p-2 rounded-lg ${activity.color} group-hover:scale-110 transition-transform`">
                  <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                  </svg>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm text-white font-medium truncate">{{ activity.title }}</p>
                  <p class="text-xs text-slate-400">{{ activity.time }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- DASHBOARD MEMBRE -->
      <div v-else>
        <!-- Section Mon Profil -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
          <!-- Carte profil principal -->
          <div class="lg:col-span-2 rounded-2xl border border-slate-700/50 bg-slate-900/60 backdrop-blur-xl p-6">
            <div class="flex items-start justify-between mb-6">
              <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-2xl font-bold">
                  {{ auth.user.name.charAt(0).toUpperCase() }}
                </div>
                <div>
                  <h2 class="text-2xl font-bold text-white">{{ auth.user.name }}</h2>
                  <p class="text-slate-400">Membre depuis {{ memberSince }}</p>
                  <div class="flex items-center gap-2 mt-2">
                    <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                    <span class="text-sm text-emerald-400">Compte actif</span>
                  </div>
                </div>
              </div>
              <Link href="/profile"
                    class="px-4 py-2 rounded-lg bg-blue-600/20 hover:bg-blue-600/30 text-blue-300 text-sm font-medium border border-blue-600/30 transition">
                Modifier mon profil
              </Link>
            </div>
            
            <!-- Progression actuelle -->
            <div class="bg-slate-800/50 rounded-xl p-4 mb-4">
              <h3 class="text-lg font-semibold text-white mb-3">🥋 Ma progression</h3>
              <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center">
                  <span class="text-white font-bold text-lg">5</span>
                </div>
                <div>
                  <div class="text-white font-medium">Ceinture Bleue</div>
                  <div class="text-sm text-slate-400">Obtenue le 15 mars 2024</div>
                </div>
              </div>
            </div>
            
            <!-- Prochaine séance -->
            <div class="bg-slate-800/50 rounded-xl p-4">
              <h3 class="text-lg font-semibold text-white mb-3">📅 Prochaine séance</h3>
              <div class="flex items-center justify-between">
                <div>
                  <div class="text-white font-medium">Karaté Intermédiaire</div>
                  <div class="text-sm text-slate-400">Mardi 10 septembre • 19h00 - 20h30</div>
                  <div class="text-xs text-slate-500 mt-1">Instructeur: Sensei Martin</div>
                </div>
                <div class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></div>
              </div>
            </div>
          </div>
          
          <!-- Stats membres -->
          <div class="space-y-6">
            <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 backdrop-blur-xl p-6">
              <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500/20 to-emerald-600/20 flex items-center justify-center">
                  <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
              </div>
              <div class="text-2xl font-bold text-white mb-1">87%</div>
              <div class="text-sm text-slate-400">Taux de présence</div>
              <div class="text-xs text-slate-500 mt-1">Ce mois-ci</div>
            </div>
            
            <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 backdrop-blur-xl p-6">
              <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500/20 to-purple-600/20 flex items-center justify-center">
                  <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                  </svg>
                </div>
              </div>
              <div class="text-2xl font-bold text-white mb-1">24</div>
              <div class="text-sm text-slate-400">Cours suivis</div>
              <div class="text-xs text-slate-500 mt-1">Ce trimestre</div>
            </div>
          </div>
        </div>
        
        <!-- Actions membres -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Actions rapides membre -->
          <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 backdrop-blur-xl p-6">
            <h2 class="text-xl font-semibold text-white mb-6">Actions rapides</h2>
            
            <div class="grid grid-cols-2 gap-4">
              <Link href="/mes-cours"
                    class="p-4 rounded-xl bg-slate-800/50 hover:bg-slate-700/50 border border-slate-700 transition group cursor-pointer">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500/20 to-purple-600/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                  </div>
                  <div>
                    <div class="text-sm font-medium text-white">Mes cours</div>
                    <div class="text-xs text-slate-400">Planning</div>
                  </div>
                </div>
              </Link>
              
              <Link href="/ma-progression"
                    class="p-4 rounded-xl bg-slate-800/50 hover:bg-slate-700/50 border border-slate-700 transition group cursor-pointer">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-amber-500/20 to-amber-600/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                  </div>
                  <div>
                    <div class="text-sm font-medium text-white">Ma progression</div>
                    <div class="text-xs text-slate-400">Ceintures</div>
                  </div>
                </div>
              </Link>
              
              <Link href="/mes-paiements"
                    class="p-4 rounded-xl bg-slate-800/50 hover:bg-slate-700/50 border border-slate-700 transition group cursor-pointer">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-emerald-500/20 to-emerald-600/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                  </div>
                  <div>
                    <div class="text-sm font-medium text-white">Mes paiements</div>
                    <div class="text-xs text-slate-400">Factures</div>
                  </div>
                </div>
              </Link>
              
              <Link href="/parametres"
                    class="p-4 rounded-xl bg-slate-800/50 hover:bg-slate-700/50 border border-slate-700 transition group cursor-pointer">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-slate-500/20 to-slate-600/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                  </div>
                  <div>
                    <div class="text-sm font-medium text-white">Paramètres</div>
                    <div class="text-xs text-slate-400">Compte</div>
                  </div>
                </div>
              </Link>
            </div>
          </div>
          
          <!-- Historique des ceintures -->
          <div class="rounded-2xl border border-slate-700/50 bg-slate-900/60 backdrop-blur-xl p-6">
            <h2 class="text-xl font-semibold text-white mb-6">🏆 Mes ceintures</h2>
            
            <div class="space-y-3 max-h-80 overflow-y-auto scrollbar-hide">
              <div v-for="belt in beltsHistory" :key="belt.id"
                   class="flex items-center gap-3 p-3 rounded-xl bg-slate-800/30">
                <div class="w-8 h-8 rounded-full" :style="`background-color: ${belt.color}`"></div>
                <div class="flex-1">
                  <div class="text-sm font-medium text-white">{{ belt.name }}</div>
                  <div class="text-xs text-slate-400">{{ belt.date }}</div>
                </div>
                <div v-if="belt.current" class="text-xs px-2 py-1 rounded-full bg-blue-500/20 text-blue-400">
                  Actuelle
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const page = usePage()
const props = defineProps({
  role: {
    type: String,
    default: 'membre'
  },
  stats: {
    type: Object,
    default: () => ({
      membres_actifs: 248,
      cours_actifs: 18,
      taux_presence: 92,
      paiements_retard: 7,
      revenus_mois: 12450
    })
  },
  recentActivities: {
    type: Array,
    default: () => []
  }
})

// Auth user
const auth = computed(() => page.props.auth || { user: { name: 'Utilisateur' } })

// Role-based computed properties
const isAdminOrInstructor = computed(() => 
  ['admin', 'superadmin', 'admin_ecole', 'instructeur'].includes(props.role)
)

const dashboardTitle = computed(() => {
  if (props.role === 'membre') return 'Mon espace personnel'
  return 'Tableau de bord'
})

const dashboardSubtitle = computed(() => {
  const name = auth.value.user.name
  const date = new Date().toLocaleDateString('fr-CA', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
  
  if (props.role === 'membre') {
    return `Bienvenue ${name} · ${date}`
  }
  return `Bienvenue ${name} · ${date}`
})

const memberSince = computed(() => {
  // Simulé - devrait venir des props
  return 'mars 2024'
})

// Historique des ceintures pour les membres
const beltsHistory = ref([
  { id: 1, name: 'Ceinture Blanche', color: '#FFFFFF', date: '15 janv. 2024', current: false },
  { id: 2, name: 'Ceinture Jaune', color: '#FFD700', date: '15 févr. 2024', current: false },
  { id: 3, name: 'Ceinture Orange', color: '#FFA500', date: '15 mars 2024', current: false },
  { id: 4, name: 'Ceinture Violette', color: '#8A2BE2', date: '15 avril 2024', current: false },
  { id: 5, name: 'Ceinture Bleue', color: '#0066CC', date: '15 mai 2024', current: true }
])

// Format currency
function formatCurrency(amount) {
  return new Intl.NumberFormat('fr-CA', {
    style: 'currency',
    currency: 'CAD'
  }).format(amount)
}
</script>

<style scoped>
/* Hide scrollbar but keep functionality */
.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}
</style>
