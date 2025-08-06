<template>
  <div class="min-h-screen bg-blue-900 flex items-center justify-center">
    <div class="bg-blue-800 text-white p-8 rounded-lg">
      <h1 class="text-3xl font-bold mb-4">✅ {{ message }}</h1>
      <p class="mb-4">Timestamp: {{ timestamp }}</p>
      <p class="text-sm text-blue-200">Vue.js + Inertia.js fonctionnent parfaitement !</p>
      
      <div class="mt-6 space-x-4">
        <a href="/test-json" class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded">Test JSON</a>
        <a href="/test-html" class="bg-yellow-600 hover:bg-yellow-700 px-4 py-2 rounded">Test HTML</a>
        <a href="/dashboard" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded">Dashboard</a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TestInertia',
  props: {
    message: String,
    timestamp: String
  }
}
</script>