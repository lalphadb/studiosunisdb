<template>
  <GuestLayout>
    <Head title="Test Login - StudiosDB" />
    
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-4">Test de Connexion StudiosDB</h1>
      
      <!-- Status de test -->
      <div class="mb-4 p-4 bg-blue-50 rounded">
        <h3 class="font-semibold">Statut du test:</h3>
        <p class="text-sm text-gray-600">{{ testStatus }}</p>
      </div>

      <!-- Formulaire de test -->
      <form @submit.prevent="testLogin" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Email:</label>
          <input 
            v-model="credentials.email" 
            type="email" 
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
            placeholder="louis@4lb.ca"
          />
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">Mot de passe:</label>
          <input 
            v-model="credentials.password" 
            type="password" 
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
            placeholder="password123"
          />
        </div>

        <button 
          type="submit" 
          :disabled="processing"
          class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 disabled:opacity-50"
        >
          {{ processing ? 'Test en cours...' : 'Tester la connexion' }}
        </button>
      </form>

      <!-- Résultats -->
      <div v-if="result" class="mt-4 p-4 rounded" :class="result.success ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800'">
        <h3 class="font-semibold">Résultat:</h3>
        <p>{{ result.message }}</p>
        <pre v-if="result.details" class="mt-2 text-xs">{{ result.details }}</pre>
      </div>

      <!-- Credentials disponibles -->
      <div class="mt-6 p-4 bg-gray-50 rounded">
        <h3 class="font-semibold mb-2">Credentials de test disponibles:</h3>
        <div class="space-y-2 text-sm">
          <div>
            <strong>Louis (Superadmin):</strong><br>
            Email: louis@4lb.ca<br>
            Mot de passe: password123
          </div>
          <div>
            <strong>Admin:</strong><br>
            Email: admin@studiosdb.com<br>
            Mot de passe: admin123
          </div>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import GuestLayout from '@/Layouts/GuestLayout.vue'

const credentials = ref({
  email: 'louis@4lb.ca',
  password: 'password123'
})

const processing = ref(false)
const result = ref(null)
const testStatus = ref('Prêt pour le test')

const testLogin = async () => {
  processing.value = true
  result.value = null
  testStatus.value = 'Test en cours...'

  try {
    await router.post('/login', {
      email: credentials.value.email,
      password: credentials.value.password,
      remember: false
    }, {
      onSuccess: (page) => {
        result.value = {
          success: true,
          message: 'Connexion réussie ! Redirection vers le dashboard...',
          details: 'User connecté, redirection en cours...'
        }
        testStatus.value = 'Test réussi'
        
        // Rediriger vers le dashboard après 2 secondes
        setTimeout(() => {
          router.visit('/dashboard')
        }, 2000)
      },
      onError: (errors) => {
        result.value = {
          success: false,
          message: 'Échec de la connexion',
          details: JSON.stringify(errors, null, 2)
        }
        testStatus.value = 'Test échoué'
      },
      onFinish: () => {
        processing.value = false
      }
    })
  } catch (error) {
    result.value = {
      success: false,
      message: 'Erreur lors du test: ' + error.message,
      details: error.toString()
    }
    testStatus.value = 'Erreur'
    processing.value = false
  }
}
</script>
