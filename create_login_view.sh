#!/bin/bash

echo "🖼️ CRÉATION VUE LOGIN STUDIOSDB"
echo "=============================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# Création dossier Auth s'il n'existe pas
mkdir -p resources/js/Pages/Auth

# Création vue Login.vue moderne
cat > resources/js/Pages/Auth/Login.vue << 'LOGIN_VUE'
<template>
    <GuestLayout>
        <!-- Header StudiosDB -->
        <div class="text-center mb-8">
            <ApplicationLogo class="mx-auto h-20 w-auto" />
            <h1 class="mt-4 text-3xl font-bold text-gray-900">StudiosDB v5 Pro</h1>
            <p class="mt-2 text-gray-600">Gestion École de Karaté</p>
        </div>

        <!-- Formulaire Login -->
        <div class="bg-white py-8 px-6 shadow rounded-lg sm:px-10">
            <form @submit.prevent="submit" class="space-y-6">
                <!-- Email -->
                <div>
                    <InputLabel for="email" value="Email" />
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <!-- Password -->
                <div>
                    <InputLabel for="password" value="Mot de passe" />
                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <Checkbox
                            name="remember"
                            v-model:checked="form.remember"
                        />
                        <span class="ms-2 text-sm text-gray-600">Se souvenir de moi</span>
                    </label>

                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-sm text-blue-600 hover:text-blue-500"
                    >
                        Mot de passe oublié?
                    </Link>
                </div>

                <!-- Submit Button -->
                <div>
                    <PrimaryButton
                        class="w-full flex justify-center"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        <span v-if="form.processing" class="mr-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                        Se connecter
                    </PrimaryButton>
                </div>

                <!-- Error Message -->
                <div v-if="form.errors.email || form.errors.password" 
                     class="bg-red-50 border border-red-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                Erreur de connexion
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                Vérifiez vos identifiants et réessayez.
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-sm text-gray-500">
            © 2025 StudiosDB v5 Pro - École Studiosunis St-Émile
        </div>
    </GuestLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import Checkbox from '@/Components/Checkbox.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
})

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    })
}
</script>
LOGIN_VUE

echo "✅ Vue Login.vue créée"

# Vérification GuestLayout existe
if [ ! -f "resources/js/Layouts/GuestLayout.vue" ]; then
    echo "⚠️ GuestLayout.vue manquant - Création basique..."
    mkdir -p resources/js/Layouts
    
    cat > resources/js/Layouts/GuestLayout.vue << 'GUEST_LAYOUT'
<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <slot />
        </div>
    </div>
</template>

<script setup>
// Layout minimal pour auth
</script>
GUEST_LAYOUT
    
    echo "✅ GuestLayout.vue créé"
fi

echo "✅ Vues Auth créées"
