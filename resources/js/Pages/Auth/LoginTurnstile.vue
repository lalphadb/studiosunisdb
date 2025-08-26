<template>
  <GuestLayout>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950">
      <div class="relative max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
          <h1 class="text-5xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent mb-2">
            StudiosDB
          </h1>
          <h2 class="mt-6 text-3xl font-extrabold text-white">
            Connexion sécurisée
          </h2>
          <p class="mt-2 text-sm text-slate-400">
            Protection par Cloudflare Turnstile
          </p>
        </div>
        
        <!-- Form -->
        <div class="bg-slate-900/50 backdrop-blur-xl rounded-2xl shadow-2xl border border-slate-700/50 p-8">
          <form class="space-y-6" @submit.prevent="submit">
            <!-- Email -->
            <div>
              <label for="email" class="block text-sm font-medium text-slate-300">
                Email
              </label>
              <input
                id="email"
                v-model="form.email"
                type="email"
                required
                class="mt-1 block w-full px-4 py-3 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500"
              />
              <p v-if="form.errors.email" class="mt-2 text-sm text-red-400">
                {{ form.errors.email }}
              </p>
            </div>
            
            <!-- Password -->
            <div>
              <label for="password" class="block text-sm font-medium text-slate-300">
                Mot de passe
              </label>
              <input
                id="password"
                v-model="form.password"
                type="password"
                required
                class="mt-1 block w-full px-4 py-3 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500"
              />
              <p v-if="form.errors.password" class="mt-2 text-sm text-red-400">
                {{ form.errors.password }}
              </p>
            </div>
            
            <!-- Turnstile Widget -->
            <div class="py-3 border-t border-slate-700">
              <TurnstileWidget
                v-model="form['cf-turnstile-response']"
                :theme="'dark'"
                :appearance="'always'"
                @verified="onTurnstileVerified"
                @expired="onTurnstileExpired"
                ref="turnstileRef"
              />
              <p v-if="form.errors['cf-turnstile-response']" class="mt-2 text-sm text-red-400">
                {{ form.errors['cf-turnstile-response'] }}
              </p>
            </div>
            
            <!-- Submit -->
            <button
              type="submit"
              :disabled="form.processing || !form['cf-turnstile-response']"
              class="w-full py-3 px-4 rounded-lg font-medium text-white transition-all"
              :class="{
                'bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700': !form.processing && form['cf-turnstile-response'],
                'bg-slate-700 cursor-not-allowed opacity-50': form.processing || !form['cf-turnstile-response']
              }"
            >
              {{ form.processing ? 'Connexion...' : 'Se connecter' }}
            </button>
          </form>
        </div>
        
        <!-- Footer -->
        <p class="text-center text-xs text-slate-500">
          Protégé par Cloudflare Turnstile - Alternative gratuite à reCAPTCHA
        </p>
      </div>
    </div>
  </GuestLayout>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import TurnstileWidget from '@/Components/TurnstileWidget.vue'

const form = useForm({
  email: '',
  password: '',
  'cf-turnstile-response': ''
})

const turnstileRef = ref(null)

const submit = () => {
  form.post('/login', {
    onFinish: () => {
      if (turnstileRef.value) {
        turnstileRef.value.reset()
      }
      form['cf-turnstile-response'] = ''
    }
  })
}

const onTurnstileVerified = (token) => {
  console.log('Turnstile vérifié avec succès')
}

const onTurnstileExpired = () => {
  form.errors['cf-turnstile-response'] = 'La vérification a expiré. Veuillez réessayer.'
}
</script>
