<template>
    <GuestLayout>
        <Head title="Connexion - StudiosDB v5" />

        <!-- Background avec gradient moderne -->
        <div class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900 flex items-center justify-center p-4">
            <!-- Particules animées (optionnel) -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-600 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse"></div>
                <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-600 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse animation-delay-2000"></div>
            </div>

            <!-- Container principal -->
            <div class="relative w-full max-w-md">
                <!-- Logo et titre -->
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-2xl">
                        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-white mb-2">StudiosDB v5</h1>
                    <p class="text-blue-200">Système de gestion professionnel</p>
                    <p class="text-sm text-blue-300 mt-1">École Studiosunis St-Émile</p>
                </div>

                <!-- Formulaire de connexion -->
                <div class="bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl p-8 border border-white/20">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-white text-center mb-2">Connexion</h2>
                        <p class="text-blue-200 text-center text-sm">Accédez à votre espace personnel</p>
                    </div>

                    <!-- Messages d'erreur -->
                    <div v-if="$page.props.errors && Object.keys($page.props.errors).length > 0"
                         class="mb-4 p-4 bg-red-500/20 border border-red-500/30 rounded-lg">
                        <div class="flex items-center space-x-2 text-red-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                            </svg>
                            <span class="text-sm font-medium">Erreur de connexion</span>
                        </div>
                        <ul class="mt-2 text-sm text-red-200">
                            <li v-for="(error, key) in $page.props.errors" :key="key">{{ error }}</li>
                        </ul>
                    </div>

                    <!-- Message de succès -->
                    <div v-if="$page.props.flash?.success"
                         class="mb-4 p-4 bg-green-500/20 border border-green-500/30 rounded-lg">
                        <div class="flex items-center space-x-2 text-green-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                            </svg>
                            <span class="text-sm">{{ $page.props.flash.success }}</span>
                        </div>
                    </div>

                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-blue-200 mb-2">
                                Adresse courriel
                            </label>
                            <div class="relative">
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                    autocomplete="username"
                                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition backdrop-blur-sm"
                                    placeholder="votre@courriel.com"
                                />
                                <svg class="absolute right-3 top-3.5 w-5 h-5 text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Mot de passe -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-blue-200 mb-2">
                                Mot de passe
                            </label>
                            <div class="relative">
                                <input
                                    id="password"
                                    v-model="form.password"
                                    :type="showPassword ? 'text' : 'password'"
                                    required
                                    autocomplete="current-password"
                                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition backdrop-blur-sm pr-12"
                                    placeholder="••••••••"
                                />
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute right-3 top-3.5 text-blue-300 hover:text-white transition"
                                >
                                    <svg v-if="!showPassword" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    <svg v-else class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z"/>
                                        <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Se souvenir de moi -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center text-sm">
                                <input
                                    v-model="form.remember"
                                    type="checkbox"
                                    class="rounded border-white/20 bg-white/10 text-blue-600 focus:ring-blue-500 focus:ring-offset-0"
                                />
                                <span class="ml-2 text-blue-200">Se souvenir de moi</span>
                            </label>

                            <Link
                                v-if="canResetPassword"
                                href="/forgot-password"
                                class="text-sm text-blue-300 hover:text-white transition underline"
                            >
                                Mot de passe oublié?
                            </Link>
                        </div>

                        <!-- Bouton connexion -->
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg shadow-lg transform transition hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center space-x-2"
                        >
                            <svg v-if="form.processing" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <svg v-else class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z"/>
                            </svg>
                            <span>{{ form.processing ? 'Connexion...' : 'Se connecter' }}</span>
                        </button>
                    </form>

                    <!-- Footer -->
                    <div class="mt-8 pt-6 border-t border-white/10">
                        <div class="text-center text-sm text-blue-300">
                            <p>StudiosDB v5.0.0 Pro</p>
                            <p class="mt-1">© 2024 École Studiosunis St-Émile</p>
                        </div>
                    </div>
                </div>

                <!-- Informations d'aide -->
                <div class="mt-6 text-center">
                    <p class="text-blue-300 text-sm">
                        Besoin d'aide? Contactez votre administrateur
                    </p>
                    <p class="text-blue-400 text-xs mt-2">
                        Version: 5.0.0 | Build: {{ new Date().getFullYear() }}
                    </p>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import GuestLayout from '@/Layouts/GuestLayout.vue'

defineProps({
    canResetPassword: Boolean,
    status: String,
})

const showPassword = ref(false)

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    })
}
</script>

<style scoped>
.animation-delay-2000 {
    animation-delay: 2s;
}

/* Animation gradient pour le fond */
@keyframes gradient {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
</style>
