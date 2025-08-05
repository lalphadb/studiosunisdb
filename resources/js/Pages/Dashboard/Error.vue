<template>
  <div class="min-h-screen bg-red-900 flex items-center justify-center">
    <div class="bg-red-800 text-white p-8 rounded-lg max-w-2xl">
      <h1 class="text-2xl font-bold mb-4">🚨 Erreur Dashboard</h1>
      <p class="mb-4">{{ error }}</p>
      <p class="text-sm text-red-200">Timestamp: {{ timestamp }}</p>
      
      <div class="mt-6">
        <a href="/dashboard-simple" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded text-white">
          Test Vue Simple
        </a>
        <a href="/dashboard-json" class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded text-white ml-2">
          Test JSON
        </a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'DashboardError',
  props: {
    error: String,
    timestamp: String
  }
}
</script>