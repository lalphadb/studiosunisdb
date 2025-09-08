<template>
    <AuthenticatedLayout>
        <Head :title="`Modifier ${user.name}`" />
        
        <div class="container mx-auto px-4 py-6">
            <div class="max-w-3xl mx-auto">
                <!-- Header -->
                <div class="mb-6">
                    <h1 class="text-xl sm:text-2xl xl:text-3xl font-bold text-gray-900">
                        Modifier l'utilisateur
                    </h1>
                    <p class="mt-2 text-sm text-gray-600">
                        Modifier les informations de {{ user.name }}
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
                                    Nouveau mot de passe
                                </label>
                                <input
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    :class="{ 'border-red-500': form.errors.password }"
                                    placeholder="Laisser vide pour ne pas changer"
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
                                    :disabled="isOwnAccount"
                                    required
                                >
                                    <option value="">Sélectionner un rôle</option>
                                    <option v-for="role in roles" :key="role" :value="role">
                                        {{ formatRole(role) }}
                                    </option>
                                </select>
                                <p v-if="isOwnAccount" class="mt-1 text-xs text-gray-500">
                                    Vous ne pouvez pas modifier votre propre rôle
                                </p>
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
                                        :disabled="isOwnAccount"
                                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
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
                                <p v-if="isOwnAccount" class="mt-1 text-xs text-gray-500">
                                    Vous ne pouvez pas désactiver votre propre compte
                                </p>
                            </div>
                        </div>
                        
                        <!-- Alerte pour compte propre -->
                        <div v-if="isOwnAccount" class="mt-4 p-4 bg-yellow-50 rounded-lg">
                            <p class="text-sm text-yellow-800">
                                <strong>Note :</strong> Vous modifiez votre propre compte. Certaines options sont désactivées pour des raisons de sécurité.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Informations supplémentaires -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">
                            Informations du compte
                        </h2>
                        
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date de création</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ formatDate(user.created_at) }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Dernière connexion</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ user.last_login_at ? formatDate(user.last_login_at) : 'Jamais' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">École</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ user.ecole?.nom || '-' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">ID utilisateur</dt>
                                <dd class="mt-1 text-sm text-gray-900 font-mono">
                                    #{{ user.id }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex items-center justify-between">
                        <button
                            v-if="!isOwnAccount"
                            type="button"
                            @click="confirmDelete"
                            class="px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                        >
                            Supprimer l'utilisateur
                        </button>
                        <div v-else></div>
                        
                        <div class="flex items-center space-x-4">
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
                                <span v-if="!form.processing">Enregistrer les modifications</span>
                                <span v-else>Enregistrement...</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Head, Link, useForm, usePage, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    user: Object,
    roles: Array,
    currentRole: String
})

const page = usePage()
const isOwnAccount = computed(() => page.props.auth.user.id === props.user.id)

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    telephone: props.user.telephone || '',
    password: '',
    role: props.currentRole || '',
    is_active: props.user.is_active
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

const formatDate = (date) => {
    if (!date) return '-'
    return new Date(date).toLocaleDateString('fr-CA', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

const submit = () => {
    form.put(route('users.update', props.user))
}

const confirmDelete = () => {
    if (confirm(`Êtes-vous sûr de vouloir supprimer l'utilisateur ${props.user.name} ? Cette action est irréversible.`)) {
        router.delete(route('users.destroy', props.user))
    }
}
</script>
