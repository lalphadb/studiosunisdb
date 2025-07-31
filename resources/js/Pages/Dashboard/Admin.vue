<template>
    <AuthenticatedLayout>
        <Head title="Dashboard StudiosDB v5" />
        
        <!-- Container principal avec glassmorphism subtil -->
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-gray-50 to-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                
                <!-- Header ultra-moderne -->
                <div class="mb-12">
                    <div class="glass-card p-8 border border-white/20">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-3xl font-light text-slate-800 mb-2">StudiosDB v5</h1>
                                <p class="text-slate-500 font-light">École de Karaté · Gestion Moderne</p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-slate-200 to-slate-300 flex items-center justify-center">
                                    <span class="text-xl">🥋</span>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-slate-700">{{ user.name }}</p>
                                    <p class="text-xs text-slate-500">Administrateur</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Métriques principales - Design glassmorphism moderne -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                    
                    <!-- Membres -->
                    <div class="glass-card p-8 border border-white/20 hover:border-slate-200/50 transition-all duration-300">
                        <div class="flex items-start justify-between mb-6">
                            <div class="w-12 h-12 rounded-xl bg-slate-100/50 flex items-center justify-center">
                                <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="text-xs px-3 py-1 bg-slate-100/50 text-slate-600 rounded-full font-medium">
                                +{{ stats.evolution_membres || 0 }}%
                            </div>
                        </div>
                        <div class="space-y-2">
                            <h3 class="text-sm font-medium text-slate-600 uppercase tracking-wide">Membres Actifs</h3>
                            <p class="text-4xl font-light text-slate-800">{{ stats.membres_actifs || 0 }}</p>
                            <p class="text-sm text-slate-500">{{ stats.total_membres || 0 }} membres au total</p>
                        </div>
                    </div>

                    <!-- Cours -->
                    <div class="glass-card p-8 border border-white/20 hover:border-slate-200/50 transition-all duration-300">
                        <div class="flex items-start justify-between mb-6">
                            <div class="w-12 h-12 rounded-xl bg-slate-100/50 flex items-center justify-center">
                                <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            </div>
                            <div class="text-xs px-3 py-1 bg-slate-100/50 text-slate-600 rounded-full font-medium">
                                {{ stats.cours_aujourd_hui || 0 }} aujourd'hui
                            </div>
                        </div>
                        <div class="space-y-2">
                            <h3 class="text-sm font-medium text-slate-600 uppercase tracking-wide">Cours Actifs</h3>
                            <p class="text-4xl font-light text-slate-800">{{ stats.cours_actifs || 0 }}</p>
                            <p class="text-sm text-slate-500">{{ stats.total_cours || 0 }} cours planifiés</p>
                        </div>
                    </div>

                    <!-- Présences -->
                    <div class="glass-card p-8 border border-white/20 hover:border-slate-200/50 transition-all duration-300">
                        <div class="flex items-start justify-between mb-6">
                            <div class="w-12 h-12 rounded-xl bg-slate-100/50 flex items-center justify-center">
                                <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="text-xs px-3 py-1 bg-slate-100/50 text-slate-600 rounded-full font-medium">
                                {{ Math.round(stats.taux_presence || 87) }}%
                            </div>
                        </div>
                        <div class="space-y-2">
                            <h3 class="text-sm font-medium text-slate-600 uppercase tracking-wide">Présences</h3>
                            <p class="text-4xl font-light text-slate-800">{{ stats.presences_aujourd_hui || 0 }}</p>
                            <p class="text-sm text-slate-500">Aujourd'hui</p>
                        </div>
                    </div>
                </div>

                <!-- Section utilisateur moderne -->
                <div class="glass-card p-8 border border-white/20 mb-12">
                    <div class="flex items-start justify-between">
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-lg font-medium text-slate-700 mb-2">Informations Utilisateur</h3>
                                <div class="space-y-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <span class="text-slate-700 font-medium">{{ user.name }}</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                            </svg>
                                        </div>
                                        <span class="text-slate-600">{{ user.email }}</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <span class="text-slate-600">{{ user.roles ? user.roles.join(', ') : 'Utilisateur' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions rapides modernes -->
                        <div class="flex flex-wrap gap-3">
                            <Link href="/membres" class="modern-button">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Membres
                            </Link>
                            <Link href="/presences/tablette" class="modern-button">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Présences
                            </Link>
                            <Link href="/profile" class="modern-button">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Profil
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Footer info minimal -->
                <div class="glass-card p-6 border border-white/20">
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center space-x-6 text-slate-500">
                            <span>Version {{ meta.version || '5.0.0' }}</span>
                            <span>{{ new Date(meta.timestamp * 1000).toLocaleDateString() }}</span>
                            <span class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-emerald-400 rounded-full"></div>
                                <span>Système Opérationnel</span>
                            </span>
                        </div>
                        <div class="text-slate-400">
                            StudiosDB v5 Pro
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
    stats: Object,
    user: Object,
    meta: Object,
});
</script>

<style scoped>
/* Glassmorphism ultra-moderne */
.glass-card {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-radius: 20px;
    box-shadow: 
        0 8px 32px 0 rgba(31, 38, 135, 0.1),
        inset 0 1px 0 0 rgba(255, 255, 255, 0.5);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.glass-card:hover {
    transform: translateY(-2px);
    box-shadow: 
        0 12px 40px 0 rgba(31, 38, 135, 0.15),
        inset 0 1px 0 0 rgba(255, 255, 255, 0.6);
}

/* Boutons modernes */
.modern-button {
    @apply flex items-center space-x-2 px-4 py-2.5 bg-white/60 hover:bg-white/80 
           text-slate-700 hover:text-slate-900 text-sm font-medium rounded-xl 
           border border-white/30 hover:border-slate-200/50 
           transition-all duration-300 backdrop-blur-sm;
    box-shadow: 0 2px 8px 0 rgba(31, 38, 135, 0.08);
}

.modern-button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px 0 rgba(31, 38, 135, 0.12);
}

/* Animations subtiles */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.glass-card {
    animation: fadeInUp 0.6s ease-out;
}

/* Responsive optimisé */
@media (max-width: 768px) {
    .glass-card {
        border-radius: 16px;
        padding: 1.5rem;
    }
    
    .modern-button {
        @apply px-3 py-2 text-xs;
    }
}

/* Dégradé de fond ultra-subtil */
.bg-gradient-to-br {
    background-image: linear-gradient(
        to bottom right,
        rgb(248, 250, 252),
        rgb(249, 250, 251),
        rgb(241, 245, 249)
    );
}
</style>
