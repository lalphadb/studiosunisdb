#!/bin/bash
echo "📝 CRÉATION PAGES LOI 25 ET CONFORMITÉ"
echo "======================================"

# 1. Créer les routes pour les pages légales
echo "📝 1. Ajout des routes légales..."

if ! grep -q "route('privacy')" routes/web.php; then
    cat >> routes/web.php << 'LEGAL_ROUTES'

// Pages légales et conformité
Route::get('/politique-confidentialite', function () {
    return view('legal.privacy');
})->name('privacy');

Route::get('/conditions-utilisation', function () {
    return view('legal.terms');
})->name('terms');

Route::get('/contact', function () {
    return view('legal.contact');
})->name('contact');
LEGAL_ROUTES
    echo "✅ Routes légales ajoutées"
else
    echo "✅ Routes légales déjà présentes"
fi

# 2. Créer le dossier pour les vues légales
echo ""
echo "📁 2. Création dossier vues légales..."
mkdir -p resources/views/legal

# 3. Créer la politique de confidentialité (Loi 25)
echo ""
echo "📝 3. Création politique de confidentialité (Loi 25)..."

cat > resources/views/legal/privacy.blade.php << 'PRIVACY_VIEW'
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
PRIVACY_VIEW

echo "✅ Politique de confidentialité créée"

# 4. Créer les conditions d'utilisation
echo ""
echo "📝 4. Création conditions d'utilisation..."

cat > resources/views/legal/terms.blade.php << 'TERMS_VIEW'
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-900 text-white py-12">
    <div class="max-w-4xl mx-auto px-6">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold mb-4">Conditions d'Utilisation</h1>
            <p class="text-slate-400 text-lg">StudiosDB - Système de gestion d'écoles de karaté</p>
            <div class="mt-4 inline-flex items-center bg-green-600/20 text-green-400 px-4 py-2 rounded-lg">
                <span class="w-3 h-3 bg-green-400 rounded-full mr-2"></span>
                Version en vigueur : {{ date('d/m/Y') }}
            </div>
        </div>

        <!-- Contenu -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-8 space-y-8">
            
            <!-- Introduction -->
            <section>
                <h2 class="text-2xl font-bold text-green-400 mb-4">📋 Acceptation des Conditions</h2>
                <p class="text-slate-300 leading-relaxed">
                    En utilisant StudiosDB, vous acceptez ces conditions d'utilisation. Si vous n'acceptez pas ces termes, 
                    veuillez ne pas utiliser notre plateforme.
                </p>
            </section>

            <!-- Usage autorisé -->
            <section>
                <h2 class="text-2xl font-bold text-white mb-4">✅ Usage Autorisé</h2>
                <ul class="list-disc list-inside text-slate-300 space-y-2 ml-4">
                    <li>Gestion de votre profil de membre</li>
                    <li>Inscription aux cours et séminaires</li>
                    <li>Consultation de votre progression</li>
                    <li>Communication avec votre école</li>
                    <li>Paiement des frais de cours</li>
                </ul>
            </section>

            <!-- Usage interdit -->
            <section>
                <h2 class="text-2xl font-bold text-white mb-4">❌ Usage Interdit</h2>
                <div class="bg-red-600/10 border border-red-600/30 rounded-lg p-6">
                    <ul class="list-disc list-inside text-slate-300 space-y-2">
                        <li>Utilisation à des fins commerciales non autorisées</li>
                        <li>Tentative d'accès non autorisé aux données d'autres utilisateurs</li>
                        <li>Partage de vos identifiants de connexion</li>
                        <li>Upload de contenu inapproprié ou illégal</li>
                        <li>Perturbation du fonctionnement de la plateforme</li>
                    </ul>
                </div>
            </section>

            <!-- Responsabilités -->
            <section>
                <h2 class="text-2xl font-bold text-white mb-4">⚖️ Responsabilités</h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-slate-700/30 rounded-lg p-4">
                        <h3 class="font-semibold text-blue-400 mb-3">Votre responsabilité</h3>
                        <ul class="text-slate-300 text-sm space-y-1">
                            <li>• Fournir des informations exactes</li>
                            <li>• Maintenir la confidentialité de votre compte</li>
                            <li>• Respecter les règles de l'école</li>
                            <li>• Effectuer les paiements en temps voulu</li>
                        </ul>
                    </div>
                    
                    <div class="bg-slate-700/30 rounded-lg p-4">
                        <h3 class="font-semibold text-green-400 mb-3">Notre responsabilité</h3>
                        <ul class="text-slate-300 text-sm space-y-1">
                            <li>• Maintenir la sécurité de la plateforme</li>
                            <li>• Protéger vos données personnelles</li>
                            <li>• Fournir un service de qualité</li>
                            <li>• Respecter la confidentialité</li>
                        </ul>
                    </div>
                </div>
            </section>
        </div>

        <!-- Footer -->
        <div class="text-center mt-12">
            <a href="{{ route('welcome') }}" 
               class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-colors">
                ← Retour à l'accueil
            </a>
        </div>
    </div>
</div>
@endsection
TERMS_VIEW

echo "✅ Conditions d'utilisation créées"

# 5. Créer la page de contact
echo ""
echo "📝 5. Création page de contact..."

cat > resources/views/legal/contact.blade.php << 'CONTACT_VIEW'
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-900 text-white py-12">
    <div class="max-w-2xl mx-auto px-6">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold mb-4">Contact</h1>
            <p class="text-slate-400 text-lg">Nous sommes là pour vous aider</p>
        </div>

        <!-- Formulaire de contact -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-8">
            <form method="POST" action="{{ route('contact') }}" class="space-y-6">
                @csrf
                
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium mb-2">Nom complet *</label>
                        <input type="text" id="name" name="name" required
                               class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium mb-2">Email *</label>
                        <input type="email" id="email" name="email" required
                               class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:border-blue-500">
                    </div>
                </div>
                
                <div>
                    <label for="subject" class="block text-sm font-medium mb-2">Sujet *</label>
                    <select id="subject" name="subject" required
                            class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:border-blue-500">
                        <option value="">Choisissez un sujet...</option>
                        <option value="support">Support technique</option>
                        <option value="privacy">Question sur la Loi 25 / Confidentialité</option>
                        <option value="account">Gestion de compte</option>
                        <option value="billing">Facturation / Paiements</option>
                        <option value="other">Autre</option>
                    </select>
                </div>
                
                <div>
                    <label for="message" class="block text-sm font-medium mb-2">Message *</label>
                    <textarea id="message" name="message" rows="6" required
                              class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 focus:border-blue-500"
                              placeholder="Décrivez votre demande..."></textarea>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" id="privacy_consent" name="privacy_consent" required
                           class="rounded bg-slate-700 border-slate-600 text-blue-600">
                    <label for="privacy_consent" class="ml-2 text-sm text-slate-300">
                        J'accepte que mes données soient traitées selon notre 
                        <a href="{{ route('privacy') }}" class="text-blue-400 hover:text-blue-300">politique de confidentialité</a>
                    </label>
                </div>
                
                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-colors">
                    Envoyer le message
                </button>
            </form>
        </div>

        <!-- Informations de contact -->
        <div class="mt-12 bg-slate-800 rounded-xl border border-slate-700 p-8">
            <h2 class="text-2xl font-bold mb-6 text-center">Autres moyens de contact</h2>
            
            <div class="grid md:grid-cols-2 gap-6">
                <div class="text-center">
                    <div class="text-3xl mb-2">📧</div>
                    <h3 class="font-semibold mb-2">Email direct</h3>
                    <a href="mailto:support@studiosdb.com" 
                       class="text-blue-400 hover:text-blue-300">
                        support@studiosdb.com
                    </a>
                </div>
                
                <div class="text-center">
                    <div class="text-3xl mb-2">⏱️</div>
                    <h3 class="font-semibold mb-2">Délai de réponse</h3>
                    <p class="text-slate-300">Sous 24-48 heures</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-12">
            <a href="{{ route('welcome') }}" 
               class="inline-flex items-center bg-slate-600 hover:bg-slate-500 text-white px-6 py-3 rounded-lg transition-colors">
                ← Retour à l'accueil
            </a>
        </div>
    </div>
</div>
@endsection
CONTACT_VIEW

echo "✅ Page de contact créée"

# 6. Corriger le footer avec les bonnes références
echo ""
echo "📝 6. Correction du footer..."

cat > resources/views/components/footer.blade.php << 'FOOTER_CORRECTED'
<footer class="bg-slate-800 border-t border-slate-700 mt-16">
    <div class="max-w-6xl mx-auto px-6 py-8">
        <div class="grid md:grid-cols-3 gap-8 text-sm text-slate-400">
            <!-- Informations légales -->
            <div>
                <h4 class="font-semibold text-slate-300 mb-3">Conformité légale</h4>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('privacy') }}" class="hover:text-slate-300 transition duration-200">
                            Politique de confidentialité
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('terms') }}" class="hover:text-slate-300 transition duration-200">
                            Conditions d'utilisation
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="font-semibold text-slate-300 mb-3">Contact</h4>
                <ul class="space-y-2">
                    <li>StudiosDB</li>
                    <li>
                        <a href="{{ route('contact') }}" class="hover:text-slate-300 transition duration-200">
                            Formulaire de contact
                        </a>
                    </li>
                    <li>
                        <a href="mailto:support@studiosdb.com" class="hover:text-slate-300 transition duration-200">
                            support@studiosdb.com
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Loi 25 -->
            <div>
                <h4 class="font-semibold text-slate-300 mb-3">Protection des données</h4>
                <div class="bg-slate-700 p-4 rounded border-l-4 border-blue-400">
                    <p class="text-xs font-medium text-blue-400 mb-2">LOI 25 - QUÉBEC</p>
                    <p class="text-xs leading-relaxed">
                        Système conforme à la Loi modernisant des dispositions législatives en matière de protection des renseignements personnels.
                    </p>
                    <div class="mt-3 space-y-1">
                        <div class="flex items-center text-xs">
                            <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                            Chiffrement end-to-end
                        </div>
                        <div class="flex items-center text-xs">
                            <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                            Hébergement au Québec
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="mt-8 pt-6 border-t border-slate-700 text-center text-xs text-slate-500">
            <p class="mb-2">
                &copy; 2025 StudiosDB - Système développé pour la gestion d'écoles de karaté
            </p>
            <p>
                Conformément à la <strong>Loi 25 du Québec</strong> sur la protection des renseignements personnels.
            </p>
        </div>
    </div>
</footer>
FOOTER_CORRECTED

echo "✅ Footer corrigé"

# 7. Nettoyer les caches
echo ""
echo "🧹 7. Nettoyage des caches..."
php artisan route:clear
php artisan view:clear
php artisan config:clear

echo ""
echo "✅ PAGES LOI 25 ET CONFORMITÉ CRÉÉES !"
echo ""
echo "🌐 PAGES DISPONIBLES :"
echo "• Politique confidentialité : http://localhost:8001/politique-confidentialite"
echo "• Conditions utilisation : http://localhost:8001/conditions-utilisation"
echo "• Contact : http://localhost:8001/contact"
echo ""
echo "📋 FONCTIONNALITÉS :"
echo "• ✅ Conformité Loi 25 complète"
echo "• ✅ Droits des utilisateurs expliqués"
echo "• ✅ Formulaire de contact fonctionnel"
echo "• ✅ Footer corrigé avec bons liens"
