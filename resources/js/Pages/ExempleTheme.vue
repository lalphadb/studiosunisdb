<template>
  <div class="min-h-screen">
    <!-- Utilisation du thème centralisé -->
    <div :class="theme.backgrounds.gradient" class="fixed inset-0"></div>
    
    <div class="relative max-w-7xl mx-auto p-6">
      <!-- Header avec thème -->
      <div :class="theme.components.statCard" class="mb-8">
        <h1 :class="theme.text.primary" class="text-2xl font-bold">
          Exemple avec Thème Centralisé
        </h1>
        <p :class="theme.text.secondary">
          Utilisation du fichier theme.ts
        </p>
      </div>
      
      <!-- Boutons avec classes du thème -->
      <div class="flex gap-4 mb-8">
        <button :class="buttonPrimaryClasses">
          Bouton Principal
        </button>
        <button :class="[theme.buttons.secondary, theme.layout.buttonRadius, 'px-4 py-2']">
          Bouton Secondaire
        </button>
      </div>
      
      <!-- Formulaire avec thème -->
      <div :class="cardClasses">
        <label :class="theme.text.label" class="block mb-2">
          Champ de formulaire
        </label>
        <input 
          type="text" 
          :class="fieldClasses"
          placeholder="Exemple..."
        />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
// Import du thème centralisé
import { theme, fieldClasses, cardClasses, buttonPrimaryClasses } from '@/theme'
</script>
