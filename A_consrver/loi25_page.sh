# 📜 CRÉATION PAGE LOI 25 - PROTECTION DES DONNÉES QUÉBEC

cat > resources/js/Pages/Loi25.vue << 'EOH'
<template>
    <Head title="Loi 25 - Protection des Renseignements Personnels | StudiosDB" />
    
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900">
        <!-- Navigation simple -->
        <nav class="bg-black/20 backdrop-blur-lg border-b border-white/10">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <Link :href="route('welcome')" class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <span class="text-white font-bold">🥋</span>
                        </div>
                        <span class="text-white font-semibold">Studiosunis St-Émile</span>
                    </Link>
                    
                    <Link :href="route('welcome')" class="text-blue-300 hover:text-white transition">
                        ← Retour à l'accueil
                    </Link>
                </div>
            </div>
        </nav>

        <!-- Contenu principal -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Header -->
            <div class="text-center mb-12">
                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-0.257-.257A6 6 0 1118 8zM10 4a4 4 0 100 8 4 4 0 000-8z"/>
                        <path fill-rule="evenodd" d="M10 6a2 2 0 100 4 2 2 0 000-4z"/>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-white mb-4">
                    Protection des Renseignements Personnels
                </h1>
                <p class="text-blue-200 text-lg">
                    Conformité à la Loi 25 du Québec
                </p>
                <p class="text-blue-300 text-sm mt-2">
                    Dernière mise à jour: {{ formatDate(new Date()) }}
                </p>
            </div>

            <!-- Contenu principal -->
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8 mb-8">
                <!-- Introduction -->
                <section class="mb-10">
                    <h2 class="text-2xl font-bold text-white mb-4 flex items-center">
                        <span class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3 text-sm font-bold">1</span>
                        Introduction
                    </h2>
                    <div class="text-blue-200 space-y-4">
                        <p>
                            L'École de Karaté Studiosunis St-Émile s'engage à protéger la confidentialité et la sécurité 
                            des renseignements personnels de ses membres, conformément à la <strong>Loi 25</strong> 
                            (Loi modernisant des dispositions législatives en matière de protection des renseignements personnels).
                        </p>
                        <p>
                            Cette politique explique comment nous collectons, utilisons, divulguons et protégeons vos 
                            renseignements personnels dans le cadre de nos services d'enseignement des arts martiaux.
                        </p>
                    </div>
                </section>

                <!-- Définitions -->
                <section class="mb-10">
                    <h2 class="text-2xl font-bold text-white mb-4 flex items-center">
                        <span class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center mr-3 text-sm font-bold">2</span>
                        Définitions
                    </h2>
                    <div class="bg-white/5 rounded-lg p-6">
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-white font-semibold">Renseignements personnels :</dt>
                                <dd class="text-blue-200 ml-4">
                                    Toute information qui permet d'identifier une personne physique ou de la rendre identifiable.
                                </dd>
                            </div>
                            <div>
                                <dt class="text-white font-semibold">StudiosDB :</dt>
                                <dd class="text-blue-200 ml-4">
                                    Notre système de gestion informatique pour l'administration de l'école.
                                </dd>
                            </div>
                            <div>
                                <dt class="text-white font-semibold">Responsable de la protection :</dt>
                                <dd class="text-blue-200 ml-4">
                                    La personne désignée pour veiller au respect de cette politique.
                                </dd>
                            </div>
                        </dl>
                    </div>
                </section>

                <!-- Collecte des renseignements -->
                <section class="mb-10">
                    <h2 class="text-2xl font-bold text-white mb-4 flex items-center">
                        <span class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center mr-3 text-sm font-bold">3</span>
                        Collecte des Renseignements
                    </h2>
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-xl font-semibold text-white mb-3">3.1 Renseignements collectés</h3>
                            <div class="bg-white/5 rounded-lg p-4">
                                <ul class="text-blue-200 space-y-2">
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        Informations d'identification (nom, prénom, date de naissance, sexe)
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        Coordonnées (adresse, téléphone, courriel)
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        Contact d'urgence (nom, téléphone)
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        Informations médicales pertinentes (si divulguées volontairement)
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        Progression technique et présences aux cours
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        Informations de paiement et facturation
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-xl font-semibold text-white mb-3">3.2 Méthodes de collecte</h3>
                            <div class="text-blue-200 space-y-2">
                                <p>• Directement auprès de vous lors de l'inscription</p>
                                <p>• Via notre système StudiosDB lors de votre participation aux cours</p>
                                <p>• Par notre personnel administratif dans le cadre normal de nos activités</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Utilisation -->
                <section class="mb-10">
                    <h2 class="text-2xl font-bold text-white mb-4 flex items-center">
                        <span class="w-8 h-8 bg-orange-600 rounded-lg flex items-center justify-center mr-3 text-sm font-bold">4</span>
                        Utilisation des Renseignements
                    </h2>
                    <div class="bg-white/5 rounded-lg p-6">
                        <p class="text-blue-200 mb-4">
                            Nous utilisons vos renseignements personnels uniquement aux fins suivantes :
                        </p>
                        <ul class="text-blue-200 space-y-3">
                            <li class="flex items-start">
                                <span class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center mr-3 flex-shrink-0 text-xs">✓</span>
                                Administration des inscriptions et gestion des comptes membres
                            </li>
                            <li class="flex items-start">
                                <span class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center mr-3 flex-shrink-0 text-xs">✓</span>
                                Planification des cours et suivi des présences
                            </li>
                            <li class="flex items-start">
                                <span class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center mr-3 flex-shrink-0 text-xs">✓</span>
                                Suivi de la progression technique et examens de ceinture
                            </li>
                            <li class="flex items-start">
                                <span class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center mr-3 flex-shrink-0 text-xs">✓</span>
                                Facturation et gestion des paiements
                            </li>
                            <li class="flex items-start">
                                <span class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center mr-3 flex-shrink-0 text-xs">✓</span>
                                Communication d'informations importantes relatives aux cours
                            </li>
                            <li class="flex items-start">
                                <span class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center mr-3 flex-shrink-0 text-xs">✓</span>
                                Assurer la sécurité lors des activités (contacts d'urgence)
                            </li>
                        </ul>
                    </div>
                </section>

                <!-- Consentement -->
                <section class="mb-10">
                    <h2 class="text-2xl font-bold text-white mb-4 flex items-center">
                        <span class="w-8 h-8 bg-pink-600 rounded-lg flex items-center justify-center mr-3 text-sm font-bold">5</span>
                        Consentement
                    </h2>
                    <div class="space-y-4 text-blue-200">
                        <p>
                            Votre consentement est requis pour la collecte, l'utilisation et la divulgation de vos 
                            renseignements personnels, sauf dans les cas prévus par la loi.
                        </p>
                        <div class="bg-blue-900/30 border border-blue-500/30 rounded-lg p-4">
                            <h3 class="text-white font-semibold mb-2">Types de consentement</h3>
                            <ul class="space-y-2">
                                <li>• <strong>Consentement explicite</strong> : Pour les photos/vidéos promotionnelles</li>
                                <li>• <strong>Consentement implicite</strong> : Pour l'administration normale des cours</li>
                                <li>• <strong>Consentement parental</strong> : Requis pour les mineurs de moins de 14 ans</li>
                            </ul>
                        </div>
                        <p>
                            Vous pouvez retirer votre consentement en tout temps, sous réserve de restrictions légales 
                            ou contractuelles et moyennant un préavis raisonnable.
                        </p>
                    </div>
                </section>

                <!-- Sécurité -->
                <section class="mb-10">
                    <h2 class="text-2xl font-bold text-white mb-4 flex items-center">
                        <span class="w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center mr-3 text-sm font-bold">6</span>
                        Sécurité et Conservation
                    </h2>
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-xl font-semibold text-white mb-3">6.1 Mesures de sécurité</h3>
                            <div class="bg-white/5 rounded-lg p-4">
                                <ul class="text-blue-200 space-y-2">
                                    <li>• Chiffrement des données sensibles dans StudiosDB</li>
                                    <li>• Accès restreint selon les fonctions du personnel</li>
                                    <li>• Sauvegardes sécurisées et régulières</li>
                                    <li>• Formation du personnel sur la confidentialité</li>
                                    <li>• Surveillance et journalisation des accès</li>
                                </ul>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-xl font-semibold text-white mb-3">6.2 Conservation</h3>
                            <div class="text-blue-200">
                                <p class="mb-2">
                                    Nous conservons vos renseignements personnels seulement le temps nécessaire 
                                    aux fins pour lesquelles ils ont été collectés :
                                </p>
                                <ul class="ml-4 space-y-1">
                                    <li>• Membres actifs : Durée de l'adhésion + 2 ans</li>
                                    <li>• Anciens membres : 7 ans après la fin de l'adhésion</li>
                                    <li>• Informations financières : 7 ans (exigences fiscales)</li>
                                    <li>• Informations médicales : 10 ans ou selon les directives médicales</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Vos droits -->
                <section class="mb-10">
                    <h2 class="text-2xl font-bold text-white mb-4 flex items-center">
                        <span class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center mr-3 text-sm font-bold">7</span>
                        Vos Droits
                    </h2>
                    <div class="bg-gradient-to-r from-blue-900/30 to-purple-900/30 border border-blue-500/30 rounded-lg p-6">
                        <p class="text-blue-200 mb-4">Conformément à la Loi 25, vous disposez des droits suivants :</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <span class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mr-3 flex-shrink-0 text-xs">→</span>
                                    <span class="text-blue-200">Accès à vos renseignements personnels</span>
                                </div>
                                <div class="flex items-start">
                                    <span class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mr-3 flex-shrink-0 text-xs">→</span>
                                    <span class="text-blue-200">Rectification des informations inexactes</span>
                                </div>
                                <div class="flex items-start">
                                    <span class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mr-3 flex-shrink-0 text-xs">→</span>
                                    <span class="text-blue-200">Retrait du consentement</span>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <span class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mr-3 flex-shrink-0 text-xs">→</span>
                                    <span class="text-blue-200">Portabilité de vos données</span>
                                </div>
                                <div class="flex items-start">
                                    <span class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mr-3 flex-shrink-0 text-xs">→</span>
                                    <span class="text-blue-200">Cessation d'utilisation (déréférencement)</span>
                                </div>
                                <div class="flex items-start">
                                    <span class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mr-3 flex-shrink-0 text-xs">→</span>
                                    <span class="text-blue-200">Porter plainte auprès de la CAI</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Contact -->
                <section class="mb-10">
                    <h2 class="text-2xl font-bold text-white mb-4 flex items-center">
                        <span class="w-8 h-8 bg-cyan-600 rounded-lg flex items-center justify-center mr-3 text-sm font-bold">8</span>
                        Nous Contacter
                    </h2>
                    <div class="bg-white/5 rounded-lg p-6">
                        <p class="text-blue-200 mb-4">
                            Pour exercer vos droits ou pour toute question concernant cette politique :
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-white font-semibold mb-2">Responsable de la protection</h3>
                                <div class="text-blue-200 space-y-1">
                                    <p>Direction - École Studiosunis</p>
                                    <p>123 Rue Principale</p>
                                    <p>St-Émile, QC G3E 1A1</p>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold mb-2">Contact</h3>
                                <div class="text-blue-200 space-y-1">
                                    <p>📞 (418) 555-0123</p>
                                    <p>📧 protection@studiosunis.ca</p>
                                    <p>🌐 www.studiosunis.ca</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- CAI -->
                <section class="mb-10">
                    <h2 class="text-2xl font-bold text-white mb-4 flex items-center">
                        <span class="w-8 h-8 bg-yellow-600 rounded-lg flex items-center justify-center mr-3 text-sm font-bold">9</span>
                        Commission d'Accès à l'Information (CAI)
                    </h2>
                    <div class="bg-yellow-900/20 border border-yellow-500/30 rounded-lg p-6">
                        <p class="text-blue-200 mb-4">
                            Si vous n'êtes pas satisfait de notre réponse à votre demande, vous pouvez déposer 
                            une plainte auprès de la Commission d'accès à l'information du Québec :
                        </p>
                        <div class="text-blue-200">
                            <p><strong>Commission d'accès à l'information</strong></p>
                            <p>525, boul. René-Lévesque Est, bureau 1.80</p>
                            <p>Québec (Québec) G1R 5S9</p>
                            <p>Téléphone : 1 888 528-7741</p>
                            <p>Site web : <span class="text-blue-300">www.cai.gouv.qc.ca</span></p>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Footer de la page -->
            <div class="text-center">
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-lg p-6">
                    <p class="text-blue-300 text-sm mb-2">
                        Cette politique est conforme à la Loi 25 du Québec
                    </p>
                    <p class="text-blue-400 text-xs">
                        Dernière révision : {{ formatDate(new Date()) }} | Version 1.0
                    </p>
                    <div class="mt-4">
                        <Link :href="route('welcome')" 
                              class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                            Retour à l'accueil
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'

const formatDate = (date) => {
    return date.toLocaleDateString('fr-CA', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}
</script>
EOH

# Route pour la page Loi 25
cat >> routes/web.php << 'EOH'

// Route Loi 25
Route::get('/loi25', function() {
    return Inertia::render('Loi25');
})->name('loi25');
EOH

echo "✅ Page Loi 25 conforme au Québec créée!"
