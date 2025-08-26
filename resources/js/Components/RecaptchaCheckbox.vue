<template>
  <div class="recaptcha-container">
    <!-- Checkbox reCAPTCHA v2 -->
    <div 
      :id="recaptchaId"
      class="g-recaptcha"
      :data-sitekey="siteKey"
      :data-theme="theme"
      :data-size="size"
    ></div>
    
    <!-- Message d'erreur -->
    <div v-if="error" class="mt-2 text-sm text-red-600">
      {{ error }}
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
  siteKey: {
    type: String,
    default: () => {
      // Récupérer depuis les props Inertia ou ENV
      const page = usePage()
      return page.props.recaptcha?.site_key || import.meta.env.VITE_RECAPTCHA_SITE_KEY
    }
  },
  theme: {
    type: String,
    default: 'dark', // 'light' ou 'dark'
    validator: (value) => ['light', 'dark'].includes(value)
  },
  size: {
    type: String,
    default: 'normal', // 'normal' ou 'compact'
    validator: (value) => ['normal', 'compact'].includes(value)
  },
  modelValue: {
    type: String,
    default: ''
  },
  required: {
    type: Boolean,
    default: true
  }
})

const emit = defineEmits(['update:modelValue', 'verified', 'expired', 'error'])

const recaptchaId = ref(`recaptcha-${Math.random().toString(36).substr(2, 9)}`)
const widgetId = ref(null)
const error = ref('')
const isLoaded = ref(false)

// Charger le script reCAPTCHA
const loadRecaptchaScript = () => {
  return new Promise((resolve) => {
    // Si déjà chargé
    if (window.grecaptcha) {
      resolve()
      return
    }
    
    // Créer le callback global
    window.recaptchaOnloadCallback = () => {
      resolve()
      delete window.recaptchaOnloadCallback
    }
    
    // Charger le script
    const script = document.createElement('script')
    script.src = 'https://www.google.com/recaptcha/api.js?onload=recaptchaOnloadCallback&render=explicit'
    script.async = true
    script.defer = true
    document.head.appendChild(script)
  })
}

// Initialiser le widget reCAPTCHA
const initRecaptcha = async () => {
  try {
    await loadRecaptchaScript()
    
    if (!window.grecaptcha || !window.grecaptcha.render) {
      throw new Error('grecaptcha not loaded')
    }
    
    // Render le widget
    widgetId.value = window.grecaptcha.render(recaptchaId.value, {
      sitekey: props.siteKey,
      theme: props.theme,
      size: props.size,
      callback: onVerified,
      'expired-callback': onExpired,
      'error-callback': onError
    })
    
    isLoaded.value = true
    error.value = ''
    
  } catch (err) {
    console.error('Erreur lors de l\'initialisation de reCAPTCHA:', err)
    error.value = 'Impossible de charger reCAPTCHA. Veuillez rafraîchir la page.'
    emit('error', err)
  }
}

// Callbacks
const onVerified = (response) => {
  error.value = ''
  emit('update:modelValue', response)
  emit('verified', response)
}

const onExpired = () => {
  error.value = 'La vérification reCAPTCHA a expiré. Veuillez cocher à nouveau.'
  emit('update:modelValue', '')
  emit('expired')
}

const onError = (err) => {
  error.value = 'Une erreur est survenue avec reCAPTCHA. Veuillez réessayer.'
  emit('update:modelValue', '')
  emit('error', err)
}

// Reset le widget
const reset = () => {
  if (widgetId.value !== null && window.grecaptcha) {
    window.grecaptcha.reset(widgetId.value)
    emit('update:modelValue', '')
    error.value = ''
  }
}

// Exposer la méthode reset
defineExpose({
  reset
})

// Lifecycle
onMounted(() => {
  if (props.siteKey) {
    initRecaptcha()
  } else {
    console.warn('reCAPTCHA: Aucune clé de site fournie')
    error.value = 'Configuration reCAPTCHA manquante'
  }
})

onUnmounted(() => {
  // Nettoyer si nécessaire
  if (widgetId.value !== null && window.grecaptcha) {
    try {
      // Note: grecaptcha ne fournit pas de méthode destroy
      // On laisse juste le DOM se nettoyer
    } catch (err) {
      console.error('Erreur lors du nettoyage de reCAPTCHA:', err)
    }
  }
})

// Si la clé change, réinitialiser
watch(() => props.siteKey, (newKey) => {
  if (newKey && isLoaded.value) {
    reset()
    initRecaptcha()
  }
})
</script>

<style scoped>
.recaptcha-container {
  min-height: 78px; /* Hauteur du widget normal */
}

.recaptcha-container[data-size="compact"] {
  min-height: 50px; /* Hauteur du widget compact */
}

/* Dark theme adjustments */
:deep(.g-recaptcha) {
  transform-origin: left top;
}

/* Responsive */
@media (max-width: 480px) {
  :deep(.g-recaptcha) {
    transform: scale(0.9);
  }
}
</style>
