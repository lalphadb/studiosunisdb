<template>
    <AuthenticatedLayout>
        <Head title="Erreur Dashboard" />
        
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-red-50 border border-red-200 rounded-lg p-8 text-center">
                    <h1 class="text-2xl font-bold text-red-900 mb-4">❌ Erreur Dashboard</h1>
                    <p class="text-red-800 mb-6">{{ error }}</p>
                    
                    <div v-if="debug" class="bg-red-100 rounded-lg p-4 mb-6 text-left">
                        <pre class="text-sm text-red-800">{{ debug }}</pre>
                    </div>
                    
                    <div class="space-x-4">
                        <Link :href="route('dashboard')" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Réessayer
                        </Link>
                        <Link :href="route('membres.index')" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Aller aux Membres
                        </Link>
                    </div>
                    
                    <div v-if="user" class="mt-8 pt-8 border-t border-red-200">
                        <p class="text-sm text-red-700">
                            Connecté : <strong>{{ user.name }}</strong> ({{ user.email }})
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    error: String,
    debug: String,
    user: Object,
});
</script>
