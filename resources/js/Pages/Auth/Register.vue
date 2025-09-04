<template>
        <GuestLayout>
            <Head title="Inscription" />
            <div class="space-y-8">
                <div class="text-center">
                    <h1 class="text-2xl font-bold tracking-tight bg-gradient-to-r from-indigo-300 to-purple-300 bg-clip-text text-transparent">Créer un compte</h1>
                    <p class="text-xs text-slate-400 mt-2">Accès membre à la plateforme StudiosDB</p>
                </div>
                <div v-if="Object.keys(errors).length" class="p-3 rounded-lg border border-rose-500/30 bg-rose-500/10 text-xs text-rose-300">
                    <div v-for="(error, field) in errors" :key="field">{{ error }}</div>
                </div>
                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label for="name" class="block text-xs font-medium text-slate-400 mb-1 tracking-wide uppercase">Nom complet</label>
                        <input id="name" v-model="form.name" type="text" required autocomplete="name" placeholder="Jean Dupont" class="w-full px-4 py-2.5 bg-slate-800/60 border border-slate-700 rounded-lg text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                    </div>
                    <div>
                        <label for="email" class="block text-xs font-medium text-slate-400 mb-1 tracking-wide uppercase">Email</label>
                        <input id="email" v-model="form.email" type="email" required autocomplete="username" placeholder="jean@example.com" class="w-full px-4 py-2.5 bg-slate-800/60 border border-slate-700 rounded-lg text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                    </div>
                    <div>
                        <label for="password" class="block text-xs font-medium text-slate-400 mb-1 tracking-wide uppercase">Mot de passe</label>
                        <input id="password" v-model="form.password" type="password" required autocomplete="new-password" class="w-full px-4 py-2.5 bg-slate-800/60 border border-slate-700 rounded-lg text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="••••••••" />
                    </div>
                        <div>
                        <label for="password_confirmation" class="block text-xs font-medium text-slate-400 mb-1 tracking-wide uppercase">Confirmer le mot de passe</label>
                        <input id="password_confirmation" v-model="form.password_confirmation" type="password" required autocomplete="new-password" class="w-full px-4 py-2.5 bg-slate-800/60 border border-slate-700 rounded-lg text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="••••••••" />
                    </div>
                    <button type="submit" :disabled="form.processing" class="w-full px-6 py-2.5 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-400 hover:to-purple-500 text-white text-sm font-medium shadow disabled:opacity-50 disabled:cursor-not-allowed">{{ form.processing ? 'Inscription...' : "S'inscrire" }}</button>
                </form>
                <div class="text-center text-xs text-slate-500">
                    <span>Déjà un compte ?</span>
                    <Link href="/login" class="text-indigo-400 hover:text-indigo-300 ml-1 underline">Se connecter</Link>
                </div>
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
