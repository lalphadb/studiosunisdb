<template>
  <GuestLayout>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950">
      <!-- Background pattern -->
      <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=%2260%22 height=%2260%22 viewBox=%220 0 60 60%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cg fill=%22none%22 fill-rule=%22evenodd%22%3E%3Cg fill=%22%239C92AC%22 fill-opacity=%220.03%22%3E%3Cpath d=%22M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')"></div>
      </div>
      
      <div class="relative max-w-md w-full space-y-8">
        <!-- Logo/Header -->
        <div class="text-center">
          <h1 class="text-5xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent mb-2">
            StudiosDB
          </h1>
          <h2 class="mt-6 text-3xl font-extrabold text-white">
            Connexion sécurisée
          </h2>
          <p class="mt-2 text-sm text-slate-400">
            Ou
            <Link :href="route('register')" class="font-medium text-blue-400 hover:text-blue-300 transition-colors">
              créer un nouveau compte
            </Link>
          </p>
        </div>
        
        <!-- Form Card -->
        <div class="bg-slate-900/50 backdrop-blur-xl rounded-2xl shadow-2xl border border-slate-700/50 p-8">
          <form class="space-y-6" @submit.prevent="submit">
            <!-- Email -->
            <div>
              <label for="email" class="block text-sm font-medium text-slate-300">
                Adresse email
              </label>
              <div class="mt-1 relative">
                <input
                  id="email"
                  v-model="form.email"
                  type="email"
                  autocomplete="email"
                  required
                  class="appearance-none block w-full px-4 py-3 bg-slate-800/50 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                  :class="{ 'border-red-500': form.errors.email }"
                  placeholder="votre@email.com"
                />
                <div v-if="form.errors.email" class="absolute inset-y-0 right-0 flex items-center pr-3">
                  <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                  </svg>
                </div>
              </div>
              <p v-if="form.errors.email" class="mt-2 text-sm text-red-400">
                {{ form.errors.email }}
              </p>
            </div>
            
            <!-- Password -->
            <div>
              <label for="password" class="block text-sm font-medium text-slate-300">
                Mot de passe
              </label>
              <div class="mt-1 relative">
                <input
                  id="password"
                  v-model="form.password"
                  :type="showPassword ? 'text' : 'password'"
                  autocomplete="current-password"
                  required
                  class="appearance-none block w-full px-4 py-3 bg-slate-800/50 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                  :class="{ 'border-red-500': form.errors.password }"
                  placeholder="••••••••"
                />
                <button
                  type="button"
                  @click="showPassword = !showPassword"
                  class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-white transition-colors"
                >
                  <svg v-if="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                  <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                  </svg>
                </button>
              </div>
              <p v-if="form.errors.password" class="mt-2 text-sm text-red-400">
                {{ form.errors.password }}
              </p>
            </div>
            
            <!-- Remember me & Forgot password -->
            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <input
                  id="remember"
                  v-model="form.remember"
                  type="checkbox"
                  class="h-4 w-4 bg-slate-800 border-slate-600 text-blue-500 focus:ring-blue-500 rounded transition-all"
                />
                <label for="remember" class="ml-2 block text-sm text-slate-300">
                  Se souvenir de moi
                </label>
              </div>
              
              <div class="text-sm">
                <Link :href="route('password.request')" class="font-medium text-blue-400 hover:text-blue-300 transition-colors">
                  Mot de passe oublié?
                </Link>
              </div>
            </div>
            
            <!-- reCAPTCHA Checkbox -->
            <div class="py-3 border-t border-slate-700">
              <RecaptchaCheckbox
                v-model="form['g-recaptcha-response']"
                :theme="'dark'"
                @verified="onRecaptchaVerified"
                @expired="onRecaptchaExpired"
                @error="onRecaptchaError"
                ref="recaptchaRef"
              />
              <p v-if="form.errors['g-recaptcha-response']" class="mt-2 text-sm text-red-400">
                {{ form.errors['g-recaptcha-response'] }}
              </p>
            </div>
            
            <!-- Submit button -->
            <div>
              <button
                type="submit"
                :disabled="form.processing || !form['g-recaptcha-response']"
                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white transition-all duration-200"
                :class="{
                  'bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 transform hover:scale-105': !form.processing && form['g-recaptcha-response'],
                  'bg-slate-700 cursor-not-allowed opacity-50': form.processing || !form['g-recaptcha-response']
                }"
              >
                <span v-if="form.processing" class="absolute left-0 inset-y-0 flex items-center pl-3">
                  <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                </span>
                {{ form.processing ? 'Connexion en cours...' : 'Se connecter' }}
              </button>
            </div>
            
            <!-- Error message -->
            <div v-if="loginError" class="rounded-lg bg-red-500/10 border border-red-500/50 p-4">
              <div class="flex">
                <div class="flex-shrink-0">
                  <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                  </svg>
                </div>
                <div class="ml-3">
                  <p class="text-sm text-red-400">
                    {{ loginError }}
                  </p>
                </div>
              </div>
            </div>
          </form>
        </div>
        
        <!-- Footer -->
        <p class="text-center text-xs text-slate-500">
          Connexion sécurisée avec SSL et reCAPTCHA v2
        </p>
      </div>
    </div>
  </GuestLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import RecaptchaCheckbox from '@/Components/RecaptchaCheckbox.vue'

const form = useForm({
  email: '',
  password: '',
  remember: false,
  'g-recaptcha-response': ''
})

const showPassword = ref(false)
const loginError = ref('')
const recaptchaRef = ref(null)

const submit = () => {
  loginError.value = ''
  
  form.post(route('login'), {
    onFinish: () => {
      // Reset reCAPTCHA après soumission
      if (recaptchaRef.value) {
        recaptchaRef.value.reset()
      }
      form['g-recaptcha-response'] = ''
    },
    onError: (errors) => {
      // Gérer les erreurs globales
      if (errors.email && errors.email.includes('credentials')) {
        loginError.value = 'Email ou mot de passe incorrect.'
      }
    }
  })
}

const onRecaptchaVerified = (token) => {
  console.log('reCAPTCHA vérifié avec succès')
  loginError.value = ''
}

const onRecaptchaExpired = () => {
  form.errors['g-recaptcha-response'] = 'Le captcha a expiré. Veuillez cocher à nouveau la case.'
}

const onRecaptchaError = (error) => {
  console.error('Erreur reCAPTCHA:', error)
  form.errors['g-recaptcha-response'] = 'Une erreur est survenue avec le captcha. Veuillez rafraîchir la page.'
}
</script>

<style scoped>
/* Animation for gradient background */
@keyframes gradient {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}

.bg-gradient-animated {
  background-size: 200% 200%;
  animation: gradient 15s ease infinite;
}
</style>
