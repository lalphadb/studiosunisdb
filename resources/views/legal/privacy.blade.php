@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-900 text-white py-12">
    <div class="max-w-4xl mx-auto px-6">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold mb-4">Politique de Confidentialité</h1>
            <p class="text-slate-400 text-lg">Conforme à la Loi 25 du Québec</p>
            <div class="mt-4 inline-flex items-center bg-blue-600/20 text-blue-400 px-4 py-2 rounded-lg">
                <span class="w-3 h-3 bg-blue-400 rounded-full mr-2"></span>
                Mise à jour : {{ date('d/m/Y') }}
            </div>
        </div>

        <!-- Contenu -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-8 space-y-8">
            
            <!-- Introduction Loi 25 -->
            <section>
                <h2 class="text-2xl font-bold text-blue-400 mb-4 flex items-center">
                    <span class="text-3xl mr-3">🛡️</span>
                    Conformité à la Loi 25
                </h2>
                <div class="bg-blue-600/10 border border-blue-600/30 rounded-lg p-6">
                    <p class="text-slate-300 leading-relaxed">
                        StudiosDB respecte intégralement la <strong>Loi 25 du Québec</strong> (Loi modernisant des dispositions législatives 
                        en matière de protection des renseignements personnels). Cette politique explique comment nous collectons, 
                        utilisons et protégeons vos données personnelles.
                    </p>
                </div>
            </section>

            <!-- Collecte de données -->
            <section>
                <h2 class="text-2xl font-bold text-white mb-4">📋 Collecte de Données</h2>
                <div class="space-y-4">
                    <h3 class="text-xl font-semibold text-slate-300">Données collectées :</h3>
                    <ul class="list-disc list-inside text-slate-300 space-y-2 ml-4">
                        <li><strong>Informations personnelles</strong> : Nom, email, téléphone, date de naissance</li>
                        <li><strong>Adresse</strong> : Adresse complète pour la gestion de votre dossier</li>
                        <li><strong>Contact d'urgence</strong> : Nom et téléphone (sécurité des cours)</li>
                        <li><strong>Progression karaté</strong> : Ceintures, présences, examens</li>
                        <li><strong>Informations de paiement</strong> : Historique des transactions</li>
                    </ul>
                    
                    <div class="bg-yellow-600/10 border border-yellow-600/30 rounded-lg p-4 mt-4">
                        <p class="text-yellow-400 font-medium">
                            ⚠️ Collecte minimale : Nous ne collectons que les données strictement nécessaires 
                            à la gestion de votre inscription et participation aux activités de karaté.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Utilisation des données -->
            <section>
                <h2 class="text-2xl font-bold text-white mb-4">🎯 Utilisation des Données</h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-slate-700/50 rounded-lg p-4">
                        <h3 class="font-semibold text-green-400 mb-2">✅ Utilisations autorisées</h3>
                        <ul class="text-slate-300 text-sm space-y-1">
                            <li>• Gestion de votre inscription</li>
                            <li>• Suivi de votre progression</li>
                            <li>• Communication des cours/événements</li>
                            <li>• Gestion des paiements</li>
                            <li>• Contact d'urgence si nécessaire</li>
                        </ul>
                    </div>
                    
                    <div class="bg-slate-700/50 rounded-lg p-4">
                        <h3 class="font-semibold text-red-400 mb-2">❌ Utilisations interdites</h3>
                        <ul class="text-slate-300 text-sm space-y-1">
                            <li>• Vente à des tiers</li>
                            <li>• Marketing non-consenti</li>
                            <li>• Profilage commercial</li>
                            <li>• Partage sans autorisation</li>
                            <li>• Usage hors contexte karaté</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Droits des utilisateurs -->
            <section>
                <h2 class="text-2xl font-bold text-white mb-4">⚖️ Vos Droits (Loi 25)</h2>
                <div class="bg-slate-700/30 rounded-lg p-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="font-semibold text-blue-400 mb-3">Droits garantis :</h3>
                            <ul class="text-slate-300 space-y-2">
                                <li class="flex items-start">
                                    <span class="text-green-400 mr-2">📋</span>
                                    <span><strong>Accès</strong> : Consulter vos données</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-green-400 mr-2">✏️</span>
                                    <span><strong>Rectification</strong> : Corriger vos données</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-green-400 mr-2">🗑️</span>
                                    <span><strong>Suppression</strong> : Effacer vos données</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-green-400 mr-2">📤</span>
                                    <span><strong>Portabilité</strong> : Récupérer vos données</span>
                                </li>
                            </ul>
                        </div>
                        
                        <div>
                            <h3 class="font-semibold text-blue-400 mb-3">Comment exercer vos droits :</h3>
                            <div class="text-slate-300 space-y-2">
                                <p>📧 <strong>Email</strong> : support@studiosdb.com</p>
                                <p>⏱️ <strong>Délai</strong> : Réponse sous 30 jours</p>
                                <p>🆔 <strong>Identification</strong> : Requise pour sécurité</p>
                                <p>💰 <strong>Gratuit</strong> : Exercice des droits sans frais</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sécurité -->
            <section>
                <h2 class="text-2xl font-bold text-white mb-4">🔒 Sécurité et Protection</h2>
                <div class="grid md:grid-cols-3 gap-4">
                    <div class="bg-green-600/10 border border-green-600/30 rounded-lg p-4 text-center">
                        <div class="text-2xl mb-2">🔐</div>
                        <h3 class="font-semibold text-green-400 mb-1">Chiffrement</h3>
                        <p class="text-slate-400 text-sm">SSL/TLS pour toutes les communications</p>
                    </div>
                    
                    <div class="bg-blue-600/10 border border-blue-600/30 rounded-lg p-4 text-center">
                        <div class="text-2xl mb-2">🏠</div>
                        <h3 class="font-semibold text-blue-400 mb-1">Hébergement</h3>
                        <p class="text-slate-400 text-sm">Serveurs au Québec (conformité Loi 25)</p>
                    </div>
                    
                    <div class="bg-purple-600/10 border border-purple-600/30 rounded-lg p-4 text-center">
                        <div class="text-2xl mb-2">👥</div>
                        <h3 class="font-semibold text-purple-400 mb-1">Accès limité</h3>
                        <p class="text-slate-400 text-sm">Personnel autorisé uniquement</p>
                    </div>
                </div>
            </section>

            <!-- Conservation -->
            <section>
                <h2 class="text-2xl font-bold text-white mb-4">📅 Conservation des Données</h2>
                <div class="bg-slate-700/30 rounded-lg p-6">
                    <table class="w-full text-slate-300">
                        <thead>
                            <tr class="border-b border-slate-600">
                                <th class="text-left py-2">Type de données</th>
                                <th class="text-left py-2">Durée de conservation</th>
                                <th class="text-left py-2">Raison</th>
                            </tr>
                        </thead>
                        <tbody class="space-y-2">
                            <tr class="border-b border-slate-700">
                                <td class="py-2">Données d'inscription</td>
                                <td class="py-2">Pendant la période d'activité + 1 an</td>
                                <td class="py-2">Gestion administrative</td>
                            </tr>
                            <tr class="border-b border-slate-700">
                                <td class="py-2">Historique des ceintures</td>
                                <td class="py-2">Permanent (avec consentement)</td>
                                <td class="py-2">Certification officielle</td>
                            </tr>
                            <tr class="border-b border-slate-700">
                                <td class="py-2">Données de paiement</td>
                                <td class="py-2">7 ans</td>
                                <td class="py-2">Obligations fiscales</td>
                            </tr>
                            <tr>
                                <td class="py-2">Logs de sécurité</td>
                                <td class="py-2">1 an</td>
                                <td class="py-2">Sécurité informatique</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Contact -->
            <section>
                <h2 class="text-2xl font-bold text-white mb-4">📞 Contact et Réclamations</h2>
                <div class="bg-blue-600/10 border border-blue-600/30 rounded-lg p-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="font-semibold text-blue-400 mb-3">Responsable de la protection des données</h3>
                            <div class="text-slate-300 space-y-1">
                                <p>📧 <strong>Email</strong> : support@studiosdb.com</p>
                                <p>🏢 <strong>Organisation</strong> : StudiosDB</p>
                                <p>📍 <strong>Localisation</strong> : Québec, Canada</p>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="font-semibold text-blue-400 mb-3">Autorité de contrôle</h3>
                            <div class="text-slate-300 space-y-1">
                                <p>🏛️ <strong>CAI</strong> : Commission d'accès à l'information</p>
                                <p>🌐 <strong>Site</strong> : cai.gouv.qc.ca</p>
                                <p>📞 <strong>Téléphone</strong> : 1-888-528-7741</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Footer -->
        <div class="text-center mt-12">
            <a href="{{ route('welcome') }}" 
               class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors">
                ← Retour à l'accueil
            </a>
        </div>
    </div>
</div>
@endsection
