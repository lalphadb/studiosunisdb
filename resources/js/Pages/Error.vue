<template>
  <div class="min-h-screen bg-gradient-to-br from-red-900 via-gray-900 to-red-900 flex items-center justify-center">
    <div class="max-w-md mx-auto text-center p-8 bg-white/10 backdrop-blur-lg rounded-xl border border-white/20">
      <div class="text-6xl mb-4">⚠️</div>
      <h1 class="text-2xl font-bold text-white mb-4">Erreur de Composant</h1>
      <p class="text-red-200 mb-6">Le composant demandé est introuvable ou corrompu.</p>
      
      <div class="space-y-4">
        <button 
          @click="goHome"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors"
        >
          🏠 Retour à l'accueil
        </button>
        
        <button 
          @click="reload"
          class="w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors"
        >
          🔄 Recharger la page
        </button>
      </div>
      
      <div class="mt-6 text-xs text-gray-400">
  <p>{{ $appName }} - Système de récupération d'erreur</p>
        <p>{{ new Date().toLocaleString() }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'

const goHome = () => {
  router.visit('/dashboard')
}

const reload = () => {
  window.location.reload()
}
</script>
