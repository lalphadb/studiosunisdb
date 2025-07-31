<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-8 rounded-lg shadow-lg w-96">
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">StudiosDB v5</h1>
                <p class="text-green-600 font-medium">✅ Vite 5.x + Vue 3 Stable</p>
            </div>
            
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input 
                        type="email" 
                        v-model="form.email" 
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                        placeholder="louis@4lb.ca"
                    />
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
                    <input 
                        type="password" 
                        v-model="form.password" 
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                        placeholder="password"
                    />
                </div>
                
                <button 
                    type="submit"
                    :disabled="form.processing"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 disabled:opacity-50"
                >
                    <span v-if="form.processing">Connexion...</span>
                    <span v-else>Se connecter</span>
                </button>
            </form>
            
            <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-md">
                <div class="text-center">
                    <p class="text-sm font-medium text-green-800 mb-2">🎉 Versions Compatibles</p>
                    <div class="text-xs text-green-700 space-y-1">
                        <div>Vite: 5.x (compatible)</div>
                        <div>Vue: 3.4.x</div>
                        <div>Laravel: 12.20</div>
                    </div>
                    <div class="mt-2 text-xs text-blue-600">
                        Test: louis@4lb.ca / password
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    email: 'louis@4lb.ca',
    password: 'password',
    remember: false,
})

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    })
}
</script>
