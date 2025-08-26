<template>
        <GuestLayout>
        <Head :title="`Connexion - ${$appName}`" />
                <div class="space-y-8">
                    <div class="text-center">
                        <h1 class="text-2xl font-bold tracking-tight bg-gradient-to-r from-indigo-300 to-purple-300 bg-clip-text text-transparent">Connexion</h1>
                        <p class="text-xs text-slate-400 mt-2">Accédez à votre espace personnel</p>
                    </div>
                    <!-- Messages -->
                    <div v-if="$page.props.errors && Object.keys($page.props.errors).length" class="p-3 rounded-lg border border-rose-500/30 bg-rose-500/10 text-xs text-rose-300">
                        <ul class="space-y-1">
                            <li v-for="(error, key) in $page.props.errors" :key="key">{{ error }}</li>
                        </ul>
                    </div>
                    <div v-if="$page.props.flash?.success" class="p-3 rounded-lg border border-emerald-500/30 bg-emerald-500/10 text-xs text-emerald-300">
                        {{ $page.props.flash.success }}
                    </div>

                    <form @submit.prevent="submit" class="space-y-5">
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-xs font-medium text-slate-400 mb-1 tracking-wide uppercase">Adresse courriel</label>
                            <div class="relative">
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                    autocomplete="username"
                                    class="w-full px-4 py-2.5 bg-slate-800/60 border border-slate-700 rounded-lg text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                    placeholder="votre@courriel.com"
                                />
                                <svg class="absolute right-3 top-3 w-5 h-5 text-slate-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Mot de passe -->
                        <div>
                            <label for="password" class="block text-xs font-medium text-slate-400 mb-1 tracking-wide uppercase">Mot de passe</label>
                            <div class="relative">
                                <input
                                    id="password"
                                    v-model="form.password"
                                    :type="showPassword ? 'text' : 'password'"
                                    required
                                    autocomplete="current-password"
                                    class="w-full px-4 py-2.5 bg-slate-800/60 border border-slate-700 rounded-lg text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition pr-12"
                                    placeholder="••••••••"
                                />
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute right-3 top-2.5 text-slate-500 hover:text-slate-300 transition"
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
                                    class="rounded border-slate-600 bg-slate-800 text-indigo-500 focus:ring-indigo-500 focus:ring-offset-0"
                                />
                                <span class="ml-2 text-slate-300">Se souvenir de moi</span>
                            </label>
                            <Link
                                v-if="canResetPassword"
                                href="/forgot-password"
                                class="text-xs text-slate-400 hover:text-slate-200 transition underline"
                            >
                                Mot de passe oublié?
                            </Link>
                        </div>

                                                <!-- Bouton connexion -->
                                                <button type="submit" :disabled="form.processing" class="w-full px-6 py-2.5 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-400 hover:to-purple-500 text-white text-sm font-medium shadow disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                                                    <svg v-if="form.processing" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                                    <span>{{ form.processing ? 'Connexion...' : 'Se connecter' }}</span>
                                                </button>
                    </form>
                    <div class="pt-4 border-t border-slate-700/60 text-center text-xs text-slate-500">© {{ new Date().getFullYear() }} École Studiosunis</div>
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

<style scoped></style>
