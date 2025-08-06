# 🏠 CRÉATION PAGE WELCOME - ACCUEIL ÉCOLE

cat > resources/js/Pages/Welcome.vue << 'EOH'
<template>
    <Head title="École Studiosunis St-Émile - StudiosDB v5" />
    
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900">
        <!-- Navigation -->
        <nav class="fixed w-full z-50 bg-black/20 backdrop-blur-lg border-b border-white/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <span class="text-white font-bold text-xl">🥋</span>
                        </div>
                        <div>
                            <h1 class="text-white font-bold text-lg">Studiosunis St-Émile</h1>
                            <p class="text-blue-200 text-xs">École de Karaté</p>
                        </div>
                    </div>

                    <!-- Navigation principale -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="#accueil" class="text-white hover:text-blue-300 transition">Accueil</a>
                        <a href="#cours" class="text-white hover:text-blue-300 transition">Nos Cours</a>
                        <a href="#instructeurs" class="text-white hover:text-blue-300 transition">Instructeurs</a>
                        <a href="#contact" class="text-white hover:text-blue-300 transition">Contact</a>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex items-center space-x-4">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition"
                        >
                            Mon Espace
                        </Link>
                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="text-white hover:text-blue-300 transition"
                            >
                                Connexion
                            </Link>
                            <Link
                                :href="route('register')"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition"
                            >
                                S'inscrire
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section id="accueil" class="pt-20 pb-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <!-- Badge -->
                    <div class="inline-flex items-center px-4 py-2 bg-blue-600/20 border border-blue-500/30 rounded-full text-blue-300 text-sm mb-8">
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                        Nouveau système de gestion StudiosDB v5
                    </div>

                    <!-- Titre principal -->
                    <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 leading-tight">
                        École de Karaté
                        <span class="bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
                            Studiosunis
                        </span>
                    </h1>
                    
                    <p class="text-xl text-blue-200 mb-8 max-w-3xl mx-auto">
                        Découvrez l'art martial traditionnel dans un environnement moderne et bienveillant. 
                        Formation pour tous les âges avec des instructeurs certifiés.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6 mb-16">
                        <button class="px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg transform transition hover:scale-105">
                            Essai Gratuit
                        </button>
                        <button class="px-8 py-4 bg-white/10 backdrop-blur-sm border border-white/20 text-white hover:bg-white/20 rounded-xl transition">
                            Voir nos Cours
                        </button>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 max-w-4xl mx-auto">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-2">15+</div>
                            <div class="text-blue-300 text-sm">Années d'expérience</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-2">200+</div>
                            <div class="text-blue-300 text-sm">Élèves actifs</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-2">5</div>
                            <div class="text-blue-300 text-sm">Instructeurs certifiés</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-2">98%</div>
                            <div class="text-blue-300 text-sm">Satisfaction</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Cours -->
        <section id="cours" class="py-20 bg-black/20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-white mb-4">Nos Programmes</h2>
                    <p class="text-blue-200 text-lg">Des cours adaptés à tous les niveaux et tous les âges</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Cours Enfants -->
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8 hover:bg-white/10 transition">
                        <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-blue-500 rounded-xl flex items-center justify-center mb-6">
                            <span class="text-2xl">👶</span>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4">Karaté Enfants</h3>
                        <p class="text-blue-200 mb-6">Programme spécialement conçu pour les 5-12 ans. Développement de la coordination, discipline et confiance en soi.</p>
                        <ul class="text-blue-300 space-y-2 text-sm mb-6">
                            <li>• Ages: 5-12 ans</li>
                            <li>• Durée: 45 minutes</li>
                            <li>• Groupes de 12 max</li>
                            <li>• Ceintures adaptées</li>
                        </ul>
                        <div class="text-2xl font-bold text-white">45$ <span class="text-sm text-blue-300">/mois</span></div>
                    </div>

                    <!-- Cours Ados -->
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8 hover:bg-white/10 transition">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mb-6">
                            <span class="text-2xl">🥋</span>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4">Karaté Ados</h3>
                        <p class="text-blue-200 mb-6">Formation technique approfondie pour adolescents. Focus sur la maîtrise de soi et l'excellence martiale.</p>
                        <ul class="text-blue-300 space-y-2 text-sm mb-6">
                            <li>• Ages: 13-17 ans</li>
                            <li>• Durée: 60 minutes</li>
                            <li>• Techniques avancées</li>
                            <li>• Compétitions optionnelles</li>
                        </ul>
                        <div class="text-2xl font-bold text-white">55$ <span class="text-sm text-blue-300">/mois</span></div>
                    </div>

                    <!-- Cours Adultes -->
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8 hover:bg-white/10 transition">
                        <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl flex items-center justify-center mb-6">
                            <span class="text-2xl">🏆</span>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4">Karaté Adultes</h3>
                        <p class="text-blue-200 mb-6">Programme complet pour adultes débutants et avancés. Remise en forme et art martial traditionnel.</p>
                        <ul class="text-blue-300 space-y-2 text-sm mb-6">
                            <li>• Ages: 18+ ans</li>
                            <li>• Durée: 75 minutes</li>
                            <li>• Tous niveaux</li>
                            <li>• Préparation examens</li>
                        </ul>
                        <div class="text-2xl font-bold text-white">65$ <span class="text-sm text-blue-300">/mois</span></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Instructeurs -->
        <section id="instructeurs" class="py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-white mb-4">Nos Instructeurs</h2>
                    <p class="text-blue-200 text-lg">Une équipe passionnée et expérimentée</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Instructeur 1 -->
                    <div class="text-center">
                        <div class="w-32 h-32 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-4xl text-white font-bold">SM</span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Sensei Martin</h3>
                        <p class="text-blue-300 mb-2">Directeur technique</p>
                        <p class="text-blue-400 text-sm">Ceinture noire 6e dan • 25 ans d'expérience</p>
                    </div>

                    <!-- Instructeur 2 -->
                    <div class="text-center">
                        <div class="w-32 h-32 bg-gradient-to-r from-green-500 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-4xl text-white font-bold">AL</span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Sensei Anna</h3>
                        <p class="text-blue-300 mb-2">Instructeure enfants</p>
                        <p class="text-blue-400 text-sm">Ceinture noire 3e dan • Spécialiste jeunesse</p>
                    </div>

                    <!-- Instructeur 3 -->
                    <div class="text-center">
                        <div class="w-32 h-32 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-4xl text-white font-bold">PL</span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Sensei Pierre</h3>
                        <p class="text-blue-300 mb-2">Instructeur adultes</p>
                        <p class="text-blue-400 text-sm">Ceinture noire 4e dan • Compétition</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Contact -->
        <section id="contact" class="py-20 bg-black/20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-white mb-4">Nous Contacter</h2>
                    <p class="text-blue-200 text-lg">Rejoignez notre école et commencez votre parcours martial</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <!-- Informations -->
                    <div class="space-y-8">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold mb-1">Adresse</h3>
                                <p class="text-blue-200">123 Rue Principale<br>St-Émile, QC G3E 1A1</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold mb-1">Téléphone</h3>
                                <p class="text-blue-200">(418) 555-0123</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold mb-1">Courriel</h3>
                                <p class="text-blue-200">info@studiosunis.ca</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-orange-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold mb-1">Horaires</h3>
                                <p class="text-blue-200">Lun-Ven: 16h-21h<br>Sam: 9h-16h</p>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire de contact -->
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8">
                        <form class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-blue-200 mb-2">Nom complet</label>
                                <input type="text" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Votre nom">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-blue-200 mb-2">Courriel</label>
                                <input type="email" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="votre@courriel.com">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-blue-200 mb-2">Message</label>
                                <textarea rows="4" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Votre message..."></textarea>
                            </div>
                            <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg transition">
                                Envoyer le message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-12 border-t border-white/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Logo et description -->
                    <div class="md:col-span-2">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                                <span class="text-white font-bold text-xl">🥋</span>
                            </div>
                            <div>
                                <h3 class="text-white font-bold text-lg">Studiosunis St-Émile</h3>
                                <p class="text-blue-300 text-sm">École de Karaté</p>
                            </div>
                        </div>
                        <p class="text-blue-200 text-sm mb-4">
                            Formation en arts martiaux traditionnels depuis plus de 15 ans. 
                            Développez votre potentiel dans un environnement respectueux et professionnel.
                        </p>
                        <div class="text-xs text-blue-400">
                            Propulsé par StudiosDB v5.0.0 Pro
                        </div>
                    </div>

                    <!-- Liens rapides -->
                    <div>
                        <h4 class="text-white font-semibold mb-4">Liens rapides</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#cours" class="text-blue-300 hover:text-white transition">Nos cours</a></li>
                            <li><a href="#instructeurs" class="text-blue-300 hover:text-white transition">Instructeurs</a></li>
                            <li><a href="#contact" class="text-blue-300 hover:text-white transition">Contact</a></li>
                            <li><Link :href="route('loi25')" class="text-blue-300 hover:text-white transition">Loi 25</Link></li>
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div>
                        <h4 class="text-white font-semibold mb-4">Contact</h4>
                        <ul class="space-y-2 text-sm text-blue-300">
                            <li>123 Rue Principale</li>
                            <li>St-Émile, QC G3E 1A1</li>
                            <li>(418) 555-0123</li>
                            <li>info@studiosunis.ca</li>
                        </ul>
                    </div>
                </div>

                <!-- Copyright -->
                <div class="mt-12 pt-8 border-t border-white/10 text-center">
                    <p class="text-blue-400 text-sm">
                        © {{ new Date().getFullYear() }} École Studiosunis St-Émile. Tous droits réservés.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'

// Smooth scroll pour la navigation
const scrollTo = (elementId) => {
    document.getElementById(elementId)?.scrollIntoView({
        behavior: 'smooth'
    })
}
</script>
EOH

echo "✅ Page Welcome professionnelle créée!"
