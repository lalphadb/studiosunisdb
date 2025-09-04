<template>
    <Head title="Loi 25 - Protection des Renseignements Personnels | StudiosDB" />
    
        <div class="min-h-screen bg-gradient-to-br from-gray-900 via-slate-900 to-gray-950 text-slate-200">
                <nav class="border-b border-slate-800/60 bg-slate-900/60 backdrop-blur-xl">
                    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                        <Link :href="route('welcome')" class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                <span class="text-white text-sm font-bold">🥋</span>
                            </div>
                            <span class="text-sm font-semibold text-white">Studiosunis St-Émile</span>
                        </Link>
                        <Link :href="route('welcome')" class="text-xs text-slate-400 hover:text-slate-200 transition">← Retour</Link>
                    </div>
                </nav>

        <!-- Contenu principal -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Header -->
                        <div class="text-center mb-12">
                            <h1 class="text-4xl font-bold tracking-tight bg-gradient-to-r from-indigo-300 to-purple-300 bg-clip-text text-transparent mb-3">Protection des Renseignements Personnels</h1>
                            <p class="text-slate-400 text-sm">Conformité à la Loi 25 du Québec • Mise à jour: {{ formatDate(new Date()) }}</p>
                        </div>

            <!-- Contenu principal -->
            <div class="bg-slate-900/60 backdrop-blur-xl border border-slate-800/60 rounded-2xl p-8 mb-8">
                <!-- Introduction -->
                <section class="mb-10">
                    <h2 class="text-xl font-semibold text-white mb-4 flex items-center tracking-tight">
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
                    <h2 class="text-xl font-semibold text-white mb-4 flex items-center tracking-tight">
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
                    <h2 class="text-xl font-semibold text-white mb-4 flex items-center tracking-tight">
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
                    <h2 class="text-xl font-semibold text-white mb-4 flex items-center tracking-tight">
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
                    <h2 class="text-xl font-semibold text-white mb-4 flex items-center tracking-tight">
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
                    <h2 class="text-xl font-semibold text-white mb-4 flex items-center tracking-tight">
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
                    <h2 class="text-xl font-semibold text-white mb-4 flex items-center tracking-tight">
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
                            <div class="bg-slate-900/60 backdrop-blur-xl border border-slate-800/60 rounded-lg p-6">
                                <p class="text-slate-400 text-xs mb-2">Cette politique est conforme à la Loi 25 du Québec • Révision {{ formatDate(new Date()) }}</p>
                                <Link :href="route('welcome')" class="inline-flex items-center px-5 py-2 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-400 hover:to-purple-500 text-white text-xs font-medium">Retour à l'accueil</Link>
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
