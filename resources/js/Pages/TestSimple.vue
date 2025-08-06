<template>
  <div>
    <h1>✅ SUCCESS - DASHBOARD FONCTIONNE !</h1>
    <div style="background: #4CAF50; color: white; padding: 20px; margin: 20px; border-radius: 10px;">
      <h2>🎉 StudiosDB v5 Pro</h2>
      <p><strong>Utilisateur:</strong> {{ user_name }}</p>
      <p><strong>Email:</strong> {{ user_email }}</p>
      <p><strong>Message:</strong> {{ message }}</p>
      <p><strong>Horodatage:</strong> {{ timestamp }}</p>
      <button @click="logout" style="background: #f44336; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
        Se déconnecter
      </button>
    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'

defineProps({
  user_name: String,
  user_email: String,
  message: String,
  timestamp: String,
  success: Boolean
})

const logout = () => {
  router.post('/logout')
}
</script>
