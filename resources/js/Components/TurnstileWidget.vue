<template>
  <div class="turnstile-container">
    <!-- Container pour le widget Turnstile -->
    <div 
      :id="turnstileId"
      class="cf-turnstile"
      :data-sitekey="siteKey"
      :data-theme="theme"
      :data-language="language"
      :data-size="size"
      :data-appearance="appearance"
      :data-response-field-name="fieldName"
      :data-retry="retry"
      :data-retry-interval="retryInterval"
    ></div>
    
    <!-- Message d'état -->
    <div v-if="statusMessage" class="mt-2 text-sm" :class="statusClass">
      {{ statusMessage }}
    </div>
    
    <!-- Debug info (dev only) -->
    <div v-if="debug && testMode" class="mt-2 p-2 bg-slate-800 rounded text-xs text-slate-400">
      <div>🧪 Mode Test Turnstile</div>
      <div>Site Key: {{ siteKey.substring(0, 10) }}...</div>
      <div>Mode: {{ appearance }}</div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
  siteKey: {
    type: String,
    default: () => {
      // Récupérer depuis les props Inertia ou ENV
      const page = usePage()
      return page.props.turnstile?.site_key || import.meta.env.VITE_TURNSTILE_SITE_KEY
    }
  },
  theme: {
    type: String,
    default: 'dark', // 'light', 'dark', 'auto'
    validator: (value) => ['light', 'dark', 'auto'].includes(value)
  },
  size: {
    type: String,
    default: 'normal', // 'normal' ou 'compact'
    validator: (value) => ['normal', 'compact'].includes(value)
  },
  appearance: {
    type: String,
    default: 'always', // 'always', 'execute', 'interaction-only'
    validator: (value) => ['always', 'execute', 'interaction-only'].includes(value)
  },
  language: {
    type: String,
    default: 'fr' // Français par défaut
  },
  retry: {
    type: String,
    default: 'auto' // 'auto' ou 'never'
  },
  retryInterval: {
    type: Number,
    default: 8000 // 8 secondes
  },
  fieldName: {
    type: String,
    default: 'cf-turnstile-response'
  },
  modelValue: {
    type: String,
    default: ''
  },
  required: {
    type: Boolean,
    default: true
  },
  debug: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['update:modelValue', 'verified', 'expired', 'error', 'before-interactive', 'after-interactive', 'unsupported'])

// État local
const turnstileId = ref(`turnstile-${Math.random().toString(36).substr(2, 9)}`)
const widgetId = ref(null)
const statusMessage = ref('')
const statusClass = ref('')
const isLoaded = ref(false)

// Détection du mode test
const testMode = computed(() => {
  return props.siteKey?.startsWith('1x') || 
         props.siteKey?.startsWith('2x') || 
         props.siteKey?.startsWith('3x')
})

// Messages de statut
const setStatus = (message, type = 'info') => {
  statusMessage.value = message
  statusClass.value = {
    'text-green-500': type === 'success',
    'text-red-500': type === 'error',
    'text-yellow-500': type === 'warning',
    'text-blue-500': type === 'info'
  }
  
  // Auto-clear après 5 secondes pour les succès
  if (type === 'success') {
    setTimeout(() => {
      statusMessage.value = ''
    }, 5000)
  }
}

// Charger le script Turnstile
const loadTurnstileScript = () => {
  return new Promise((resolve, reject) => {
    // Si déjà chargé
    if (window.turnstile) {
      resolve()
      return
    }
    
    // Créer le callback global
    window.onloadTurnstileCallback = () => {
      resolve()
      delete window.onloadTurnstileCallback
    }
    
    // Charger le script Cloudflare Turnstile
    const script = document.createElement('script')
    script.src = 'https://challenges.cloudflare.com/turnstile/v0/api.js?onload=onloadTurnstileCallback'
    script.async = true
    script.defer = true
    script.onerror = () => {
      reject(new Error('Failed to load Turnstile script'))
    }
    document.head.appendChild(script)
  })
}

// Initialiser le widget Turnstile
const initTurnstile = async () => {
  try {
    setStatus('Chargement de la vérification...', 'info')
    
    await loadTurnstileScript()
    
    if (!window.turnstile) {
      throw new Error('Turnstile not loaded')
    }
    
    // Configuration du widget
    const config = {
      sitekey: props.siteKey,
      theme: props.theme,
      language: props.language,
      size: props.size,
      appearance: props.appearance,
      'response-field-name': props.fieldName,
      retry: props.retry,
      'retry-interval': props.retryInterval,
      callback: onVerified,
      'expired-callback': onExpired,
      'error-callback': onError,
      'before-interactive-callback': onBeforeInteractive,
      'after-interactive-callback': onAfterInteractive,
      'unsupported-callback': onUnsupported
    }
    
    // Render le widget
    widgetId.value = window.turnstile.render(`#${turnstileId.value}`, config)
    
    isLoaded.value = true
    setStatus('', 'info')
    
    if (testMode.value) {
      setStatus('Mode test activé', 'warning')
    }
    
  } catch (err) {
    console.error('Erreur lors de l\'initialisation de Turnstile:', err)
    setStatus('Impossible de charger la vérification. Veuillez rafraîchir la page.', 'error')
    emit('error', err)
  }
}

// Callbacks Turnstile
const onVerified = (token) => {
  setStatus('✓ Vérification réussie', 'success')
  emit('update:modelValue', token)
  emit('verified', token)
  
  if (props.debug) {
    console.log('Turnstile verified:', token.substring(0, 20) + '...')
  }
}

const onExpired = () => {
  setStatus('La vérification a expiré. Veuillez réessayer.', 'warning')
  emit('update:modelValue', '')
  emit('expired')
}

const onError = (error) => {
  setStatus('Une erreur est survenue. Veuillez réessayer.', 'error')
  emit('update:modelValue', '')
  emit('error', error)
  
  if (props.debug) {
    console.error('Turnstile error:', error)
  }
}

const onBeforeInteractive = () => {
  setStatus('Vérification en cours...', 'info')
  emit('before-interactive')
}

const onAfterInteractive = () => {
  emit('after-interactive')
}

const onUnsupported = () => {
  setStatus('Votre navigateur n\'est pas supporté. Veuillez utiliser un navigateur moderne.', 'error')
  emit('unsupported')
}

// Reset le widget
const reset = () => {
  if (widgetId.value !== null && window.turnstile) {
    window.turnstile.reset(widgetId.value)
    emit('update:modelValue', '')
    setStatus('', 'info')
  }
}

// Remove le widget
const remove = () => {
  if (widgetId.value !== null && window.turnstile) {
    window.turnstile.remove(widgetId.value)
    widgetId.value = null
  }
}

// Exposer les méthodes
defineExpose({
  reset,
  remove,
  isTestMode: testMode
})

// Lifecycle
onMounted(() => {
  if (props.siteKey) {
    initTurnstile()
  } else {
    console.warn('Turnstile: Aucune clé de site fournie')
    setStatus('Configuration Turnstile manquante', 'error')
  }
})

onUnmounted(() => {
  remove()
})

// Si la clé change, réinitialiser
watch(() => props.siteKey, (newKey) => {
  if (newKey && isLoaded.value) {
    remove()
    initTurnstile()
  }
})
</script>

<style scoped>
.turnstile-container {
  min-height: 65px; /* Hauteur du widget Turnstile */
  position: relative;
}

/* Animation de chargement */
.turnstile-container:not(.loaded) {
  background: linear-gradient(90deg, 
    rgba(59, 130, 246, 0.1) 0%,
    rgba(59, 130, 246, 0.2) 50%,
    rgba(59, 130, 246, 0.1) 100%
  );
  background-size: 200% 100%;
  animation: shimmer 1.5s ease-in-out infinite;
  border-radius: 0.5rem;
}

@keyframes shimmer {
  0% {
    background-position: -200% 0;
  }
  100% {
    background-position: 200% 0;
  }
}

/* Style pour le widget compact */
.turnstile-container[data-size="compact"] {
  min-height: 50px;
}

/* Responsive */
@media (max-width: 480px) {
  :deep(.cf-turnstile) {
    transform: scale(0.95);
    transform-origin: left center;
  }
}

/* Dark mode adjustments */
:deep(.cf-turnstile[data-theme="dark"]) {
  /* Styles spécifiques au dark mode si nécessaire */
}
</style>
