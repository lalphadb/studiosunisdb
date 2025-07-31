<template>
    <GuestLayout>
        <Head title="Inscription" />

        <!-- Titre -->
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Inscription</h1>
            <p class="text-sm text-gray-600">Créez votre compte StudiosDB</p>
        </div>

        <!-- Messages d'erreur -->
        <div v-if="Object.keys(errors).length > 0" class="mb-4 p-3 bg-red-50 border border-red-200 rounded">
            <div v-for="(error, field) in errors" :key="field" class="text-sm text-red-600">
                {{ error }}
            </div>
        </div>

        <!-- Formulaire -->
        <form @submit.prevent="submit">
            <!-- Nom -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                    Nom complet
                </label>
                <input
                    id="name"
                    type="text"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Jean Dupont"
                />
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                    Email
                </label>
                <input
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    placeholder="jean@example.com"
                />
            </div>

            <!-- Mot de passe -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                    Mot de passe
                </label>
                <input
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    placeholder="••••••••"
                />
            </div>

            <!-- Confirmer mot de passe -->
            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                    Confirmer le mot de passe
                </label>
                <input
                    id="password_confirmation"
                    type="password"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    placeholder="••••••••"
                />
            </div>

            <!-- Bouton -->
            <button
                type="submit"
                :disabled="form.processing"
                class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 mb-4"
            >
                <span v-if="form.processing">Inscription...</span>
                <span v-else">S'inscrire</span>
            </button>
        </form>

        <!-- Lien login -->
        <div class="text-center text-sm text-gray-600">
            <span>Déjà un compte ?</span>
            <Link href="/login" class="text-blue-600 hover:text-blue-800 ml-1">
                Se connecter
            </Link>
        </div>
    </GuestLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'

defineProps({
    errors: {
        type: Object,
        default: () => ({}),
    },
})

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
})

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    })
}
</script>
