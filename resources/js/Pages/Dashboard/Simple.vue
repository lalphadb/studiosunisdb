<template>
  <div class="min-h-screen bg-green-900 flex items-center justify-center">
    <div class="bg-green-800 text-white p-8 rounded-lg">
      <h1 class="text-3xl font-bold mb-4">✅ {{ message }}</h1>
      <div class="space-y-2">
        <p>Utilisateur: {{ user?.name || 'N/A' }}</p>
        <p>Email: {{ user?.email || 'N/A' }}</p>
        <p>ID: {{ user?.id || 'N/A' }}</p>
        <p>Timestamp: {{ timestamp }}</p>
      </div>
      
      <div class="mt-6">
        <a href="/dashboard" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded">
          Retour Dashboard
        </a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'DashboardSimple',
  props: {
    message: String,
    user: Object,
    timestamp: String
  }
}
</script>