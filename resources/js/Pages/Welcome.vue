<template>
    <div class="min-h-screen relative overflow-hidden bg-gradient-to-br from-gray-900 via-slate-900 to-gray-950 flex flex-col">
        <header class="px-6 py-5 flex items-center justify-between relative z-10">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow">
                    <span class="text-white font-bold">🥋</span>
                </div>
                <div>
                    <h1 class="text-lg font-semibold text-white">{{ $appName }}</h1>
                    <p class="text-[11px] uppercase tracking-wider text-slate-400">École de Karaté</p>
                </div>
            </div>
            <nav class="flex items-center gap-3 text-xs">
                <a href="/login" class="px-4 py-2 rounded-lg bg-slate-800/60 hover:bg-slate-700/60 text-slate-200 font-medium transition">Connexion</a>
                <a href="/register" class="px-4 py-2 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-400 hover:to-purple-500 text-white font-medium transition">Inscription</a>
            </nav>
        </header>
        <main class="flex-1 flex items-center justify-center px-6 pb-24 relative z-10">
            <div class="max-w-3xl text-center">
                <h2 class="text-4xl md:text-5xl font-bold tracking-tight bg-gradient-to-r from-indigo-300 via-purple-300 to-indigo-300 bg-clip-text text-transparent">
                    Plateforme de gestion moderne pour votre dojo
                </h2>
                <p class="mt-6 text-slate-400 text-lg max-w-2xl mx-auto">Centralisez membres, cours, présences, paiements et progression technique dans un environnement unifié, rapide et sécurisé.</p>
                <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
                    <a href="/login" class="px-6 py-3 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-400 hover:to-purple-500 text-white text-sm font-medium shadow">Se connecter</a>
                    <a href="/register" class="px-6 py-3 rounded-lg bg-slate-800/60 hover:bg-slate-700/60 text-slate-200 text-sm font-medium border border-slate-700/70">Créer un compte</a>
                    <a href="/loi-25" class="px-6 py-3 rounded-lg bg-slate-800/40 hover:bg-slate-700/50 text-slate-300 text-sm font-medium">Loi 25</a>
                </div>
                <div class="mt-12 grid grid-cols-1 sm:grid-cols-3 gap-4 text-left">
                    <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-700/50">
                        <p class="text-sm font-semibold text-slate-200 mb-1">Membres & Présences</p>
                        <p class="text-xs text-slate-400 leading-relaxed">Suivi précis des inscriptions et participation quotidienne.</p>
                    </div>
                    <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-700/50">
                        <p class="text-sm font-semibold text-slate-200 mb-1">Finances</p>
                        <p class="text-xs text-slate-400 leading-relaxed">Facturation, paiements et indicateurs consolidés.</p>
                    </div>
                        <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-700/50">
                        <p class="text-sm font-semibold text-slate-200 mb-1">Progression</p>
                        <p class="text-xs text-slate-400 leading-relaxed">Gestion des ceintures, examens et progression technique.</p>
                    </div>
                </div>
            </div>
        </main>
        <footer class="relative z-10 px-6 py-6 text-center text-[11px] text-slate-500">
            <p>© {{ new Date().getFullYear() }} Studiosunis • v{{ $appVersion }} • <a href="/loi-25" class="hover:text-slate-300">Loi 25</a></p>
            <p class="mt-1">Propulsé par Laravel • Vue 3 • Inertia.js</p>
        </footer>
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute -top-40 -left-40 w-[38rem] h-[38rem] bg-indigo-600/15 rounded-full blur-3xl" />
            <div class="absolute -bottom-40 -right-40 w-[42rem] h-[42rem] bg-purple-600/15 rounded-full blur-3xl" />
        </div>
    </div>
</template>
