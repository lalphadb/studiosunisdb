<template>
    <AuthenticatedLayout>
        <Head title="Dashboard Simple Test" />
        
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-green-100 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-8 text-center">
                        <h1 class="text-4xl font-bold text-green-800 mb-4">✅ TEST RÉUSSI !</h1>
                        <h2 class="text-2xl text-green-700 mb-6">{{ message }}</h2>
                        
                        <div class="bg-white rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations Test</h3>
                            <div class="text-left space-y-2">
                                <p><strong>Utilisateur:</strong> {{ user.name }}</p>
                                <p><strong>Email:</strong> {{ user.email }}</p>
                                <p><strong>Timestamp:</strong> {{ timestamp }}</p>
                                <p><strong>Statut:</strong> <span class="text-green-600 font-bold">Laravel + Inertia + Vue OK</span></p>
                            </div>
                        </div>
                        
                        <div class="space-x-4">
                            <Link :href="route('dashboard')" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                                Dashboard Original
                            </Link>
                            <Link :href="route('membres.index')" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                                Membres
                            </Link>
                        </div>
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
    message: String,
    user: Object,
    timestamp: String,
});
</script>
