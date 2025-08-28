<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
    <div class="max-w-md w-full mx-4">
      <!-- Logo/Titre -->
      <div class="text-center mb-8">
        <div class="mx-auto w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mb-4">
          <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m4-6V9a2 2 0 00-2-2H8a2 2 0 00-2 2v2"></path>
          </svg>
        </div>
        <h1 class="text-4xl font-bold text-white mb-2">403</h1>
        <p class="text-slate-400 text-lg">Accès non autorisé</p>
      </div>

      <!-- Message d'erreur -->
      <div class="bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6 mb-6">
        <h2 class="text-white font-semibold mb-3">🚫 Permission refusée</h2>
        <p class="text-slate-300 mb-4">
          Vous n'avez pas les permissions nécessaires pour accéder à cette page.
        </p>
        
        <div class="bg-slate-900/50 rounded-lg p-4 mb-4">
          <h3 class="text-slate-400 text-sm font-medium mb-2">Causes possibles :</h3>
          <ul class="text-slate-400 text-sm space-y-1">
            <li>• Session expirée</li>
            <li>• Rôle insuffisant (admin/instructeur requis)</li>
            <li>• Accès restreint à votre école uniquement</li>
            <li>• Cours inexistant ou supprimé</li>
          </ul>
        </div>

        <div v-if="diagnostic" class="bg-amber-500/10 border border-amber-500/20 rounded-lg p-3 mb-4">
          <p class="text-amber-300 text-xs font-medium mb-1">🔍 Diagnostic (dev) :</p>
          <pre class="text-amber-200 text-xs">{{ JSON.stringify(diagnostic, null, 2) }}</pre>
        </div>
      </div>

      <!-- Actions -->
      <div class="space-y-3">
        <a href="/dashboard" 
           class="block w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium py-3 px-4 rounded-xl text-center transition-all">
          🏠 Retour au tableau de bord
        </a>
        
        <button @click="reconnect" 
                class="block w-full bg-slate-700/50 hover:bg-slate-600/50 text-white font-medium py-3 px-4 rounded-xl text-center border border-slate-600 transition-all">
          🔄 Se reconnecter
        </button>
        
        <a href="/debug/cours-access" 
           target="_blank"
           class="block w-full bg-slate-800/50 hover:bg-slate-700/50 text-slate-300 font-medium py-2 px-4 rounded-xl text-center text-sm border border-slate-700 transition-all">
          🛠️ Diagnostic technique
        </a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  status: {
    type: Number,
    default: 403
  },
  message: {
    type: String,
    default: 'Accès non autorisé'
  },
  diagnostic: {
    type: Object,
    default: null
  }
})

const reconnect = () => {
  // Forcer une nouvelle authentification
  window.location.href = '/logout'
}
</script>
