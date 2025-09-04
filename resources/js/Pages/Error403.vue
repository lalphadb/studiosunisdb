<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950">
    <!-- Pattern overlay -->
    <div class="fixed inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=%2260%22 height=%2260%22 viewBox=%220 0 60 60%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cg fill=%22none%22 fill-rule=%22evenodd%22%3E%3Cg fill=%22%239C92AC%22 fill-opacity=%220.03%22%3E%3Cpath d=%22M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')"></div>
    
    <div class="relative max-w-xl mx-auto px-6 py-12 text-center">
      <!-- Icône d'erreur -->
      <div class="mx-auto w-20 h-20 mb-8 rounded-full bg-red-500/20 flex items-center justify-center">
        <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
        </svg>
      </div>
      
      <!-- Code d'erreur -->
      <h1 class="text-6xl font-bold bg-gradient-to-r from-red-400 to-pink-400 bg-clip-text text-transparent mb-4">
        403
      </h1>
      
      <!-- Titre -->
      <h2 class="text-2xl font-semibold text-white mb-4">
        Accès non autorisé
      </h2>
      
      <!-- Description -->
      <p class="text-slate-400 mb-8 leading-relaxed">
        Vous n'avez pas les permissions nécessaires pour accéder à cette page.
      </p>
      
      <!-- Causes possibles -->
      <div class="bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6 mb-8 text-left">
        <h3 class="text-lg font-medium text-white mb-4 flex items-center">
          <svg class="w-5 h-5 text-amber-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          Causes possibles :
        </h3>
        <ul class="space-y-2 text-sm text-slate-300">
          <li class="flex items-start">
            <span class="w-2 h-2 bg-amber-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
            Session expirée
          </li>
          <li class="flex items-start">
            <span class="w-2 h-2 bg-amber-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
            Rôle insuffisant (admin/instructeur requis)
          </li>
          <li class="flex items-start">
            <span class="w-2 h-2 bg-amber-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
            Accès restreint à votre école uniquement
          </li>
          <li class="flex items-start">
            <span class="w-2 h-2 bg-amber-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
            Ressource inexistante ou supprimée
          </li>
        </ul>
      </div>
      
      <!-- Actions -->
      <div class="space-y-4">
        <a href="/dashboard" 
           class="inline-flex items-center px-6 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium transition-all shadow-lg">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
          Retour au tableau de bord
        </a>
        
        <div class="text-center">
          <a href="/login" 
             class="inline-flex items-center text-sm text-slate-400 hover:text-white transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Se reconnecter
          </a>
        </div>
      </div>
      
      <!-- Contact support -->
      <div class="mt-8 pt-6 border-t border-slate-700/50 text-center">
        <p class="text-xs text-slate-500">
          Problème persistant ? 
          <a href="mailto:support@studiosdb.com" class="text-blue-400 hover:text-blue-300 transition-colors">
            Contactez le support
          </a>
        </p>
      </div>
    </div>
  </div>
</template>
