<template>
    <AuthenticatedLayout>
        <Head title="Nouvel utilisateur" />
        
        <div class="container mx-auto px-4 py-6">
            <div class="max-w-3xl mx-auto">
                <!-- Header -->
                <div class="mb-6">
                    <h1 class="text-xl sm:text-2xl xl:text-3xl font-bold text-gray-900">
                        Nouvel utilisateur
                    </h1>
                    <p class="mt-2 text-sm text-gray-600">
                        Créer un nouveau compte utilisateur dans votre école
                    </p>
                </div>
                
                <!-- Formulaire -->
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="bg-white shadow rounded-lg p-6">
                        <!-- Informations personnelles -->
                        <h2 class="text-lg font-medium text-gray-900 mb-4">
                            Informations personnelles
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nom complet <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    :class="{ 'border-red-500': form.errors.name }"
                                    required
                                />
                                <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.name }}
                                </p>
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    :class="{ 'border-red-500': form.errors.email }"
                                    required
                                />
                                <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.email }}
                                </p>
                            </div>
                            
                            <div>
                                <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1">
                                    Téléphone
                                </label>
                                <input
                                    id="telephone"
                                    v-model="form.telephone"
                                    type="tel"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    :class="{ 'border-red-500': form.errors.telephone }"
                                    placeholder="(514) 555-0123"
                                />
                                <p v-if="form.errors.telephone" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.telephone }}
                                </p>
                            </div>
                            
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                    Mot de passe <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    :class="{ 'border-red-500': form.errors.password }"
                                    required
                                />
                                <p class="mt-1 text-xs text-gray-500">
                                    Minimum 8 caractères avec lettres, chiffres et majuscules
                                </p>
                                <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.password }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Rôle et permissions -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">
                            Rôle et permissions
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                                    Rôle <span class="text-red-500">*</span>
                                </label>
                                <select
                                    id="role"
                                    v-model="form.role"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    :class="{ 'border-red-500': form.errors.role }"
                                    required
                                >
                                    <option value="">Sélectionner un rôle</option>
                                    <option v-for="role in roles" :key="role" :value="role">
                                        {{ formatRole(role) }}
                                    </option>
                                </select>
                                <p v-if="form.errors.role" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.role }}
                                </p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Statut du compte
                                </label>
                                <div class="flex items-center">
                                    <button
                                        type="button"
                                        @click="form.is_active = !form.is_active"
                                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                        :class="form.is_active ? 'bg-blue-600' : 'bg-gray-200'"
                                    >
                                        <span
                                            class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
                                            :class="form.is_active ? 'translate-x-6' : 'translate-x-1'"
                                        />
                                    </button>
                                    <span class="ml-3 text-sm font-medium text-gray-700">
                                        {{ form.is_active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Description des rôles -->
                        <div v-if="form.role" class="mt-4 p-4 bg-blue-50 rounded-lg">
                            <p class="text-sm text-blue-900 font-medium mb-2">
                                Permissions du rôle {{ formatRole(form.role) }} :
                            </p>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <template v-if="form.role === 'admin_ecole'">
                                    <li>• Gestion complète de l'école</li>
                                    <li>• Création et gestion des utilisateurs</li>
                                    <li>• Gestion des cours et présences</li>
                                    <li>• Accès aux rapports financiers</li>
                                </template>
                                <template v-else-if="form.role === 'instructeur'">
                                    <li>• Gestion de ses propres cours</li>
                                    <li>• Prise de présences</li>
                                    <li>• Consultation des membres</li>
                                    <li>• Gestion des progressions</li>
                                </template>
                                <template v-else-if="form.role === 'membre'">
                                    <li>• Consultation de son profil</li>
                                    <li>• Inscription aux cours</li>
                                    <li>• Suivi de sa progression</li>
                                    <li>• Consultation de ses paiements</li>
                                </template>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex items-center justify-end space-x-4">
                        <Link
                            :href="route('users.index')"
                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            Annuler
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="!form.processing">Créer l'utilisateur</span>
                            <span v-else>Création en cours...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    roles: Array,
    ecoles: Array
})

const form = useForm({
    name: '',
    email: '',
    telephone: '',
    password: '',
    role: '',
    is_active: true
})

const formatRole = (role) => {
    const roleLabels = {
        'superadmin': 'Super Admin',
        'admin_ecole': 'Administrateur',
        'instructeur': 'Instructeur',
        'membre': 'Membre'
    }
    return roleLabels[role] || role
}

const submit = () => {
    form.post(route('users.store'))
}
</script>
