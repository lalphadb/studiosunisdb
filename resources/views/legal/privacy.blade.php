<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Politique de confidentialité - StudiosUnisDB</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-white">
    <div class="min-h-screen bg-gradient-to-br from-slate-900 to-slate-800">
        <!-- Header -->
        <header class="bg-slate-800 border-b border-slate-700">
            <div class="max-w-6xl mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <a href="{{ url('/') }}" class="text-2xl font-bold">
                        Studios<span class="text-blue-400">Unis</span>DB
                    </a>
                    <nav class="space-x-6">
                        <a href="{{ url('/') }}" class="text-slate-300 hover:text-white">Accueil</a>
                        <a href="{{ route('contact') }}" class="text-slate-300 hover:text-white">Contact</a>
                        <a href="{{ route('login') }}" class="bg-blue-600 px-4 py-2 rounded">Connexion</a>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Contenu -->
        <div class="max-w-4xl mx-auto px-6 py-12">
            <h1 class="text-4xl font-bold mb-8">Politique de confidentialité</h1>
            
            <div class="prose prose-invert max-w-none">
                <!-- Loi 25 Notice -->
                <div class="bg-blue-900/30 border border-blue-400 rounded-lg p-6 mb-8">
                    <h2 class="text-xl font-semibold text-blue-400 mb-3">Conformité Loi 25 - Québec</h2>
                    <p class="text-sm">Cette politique respecte la Loi modernisant des dispositions législatives en matière de protection des renseignements personnels (Loi 25) du Québec.</p>
                </div>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4">But de cette politique</h2>
                    <p class="mb-4">Cette politique informe les utilisateurs de StudiosUnisDB des données personnelles que nous collectons, notamment :</p>
                    <ul class="list-disc ml-6 space-y-1">
                        <li>Les données personnelles collectées</li>
                        <li>L'utilisation des données recueillies</li>
                        <li>Qui a accès aux données</li>
                        <li>Les droits des utilisateurs</li>
                        <li>Notre politique de cookies</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4">Lois applicables</h2>
                    <p class="mb-4">Cette politique se conforme aux lois suivantes :</p>
                    <ul class="list-disc ml-6 space-y-2">
                        <li><strong>Loi 25 du Québec</strong> - Protection des renseignements personnels dans le secteur privé</li>
                        <li><strong>LPRPDE</strong> - Loi sur la protection des renseignements personnels et les documents électroniques (Canada)</li>
                        <li><strong>RGPD</strong> - Pour les résidents de l'UE</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4">Données collectées</h2>
                    
                    <h3 class="text-xl font-medium mb-3">Données collectées automatiquement</h3>
                    <ul class="list-disc ml-6 mb-4">
                        <li>Adresse IP</li>
                        <li>Localisation générale</li>
                        <li>Pages visitées</li>
                        <li>Durée de session</li>
                    </ul>

                    <h3 class="text-xl font-medium mb-3">Données collectées manuellement</h3>
                    <ul class="list-disc ml-6">
                        <li>Nom et prénom</li>
                        <li>Adresse courriel</li>
                        <li>Numéro de téléphone</li>
                        <li>Informations de profil membre</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4">Utilisation des données</h2>
                    <p class="mb-4">Vos données sont utilisées uniquement pour :</p>
                    <ul class="list-disc ml-6">
                        <li>Gestion des comptes utilisateurs</li>
                        <li>Administration des écoles de karaté</li>
                        <li>Communication avec les membres</li>
                        <li>Amélioration du système</li>
                        <li>Conformité légale</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4">Protection des données</h2>
                    <div class="bg-slate-800 p-4 rounded">
                        <ul class="space-y-2">
                            <li>✅ Chiffrement SSL/TLS</li>
                            <li>✅ Hébergement sécurisé au Québec</li>
                            <li>✅ Accès restreint aux employés autorisés</li>
                            <li>✅ Surveillance continue de la sécurité</li>
                            <li>✅ Sauvegarde régulière</li>
                        </ul>
                    </div>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4">Vos droits</h2>
                    <p class="mb-4">Conformément à la Loi 25, vous avez le droit de :</p>
                    <ul class="list-disc ml-6">
                        <li>Accéder à vos données personnelles</li>
                        <li>Rectifier vos données</li>
                        <li>Demander la suppression de vos données</li>
                        <li>Retirer votre consentement</li>
                        <li>Porter plainte auprès de la CAI (Commission d'accès à l'information)</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4">Contact pour protection des données</h2>
                    <div class="bg-slate-800 p-6 rounded">
                        <p class="font-medium mb-2">Responsable de la protection des données :</p>
                        <p>StudiosUnisDB - Protection des données</p>
                        <p>Email : <a href="mailto:lalpha@4lb.ca" class="text-blue-400">lalpha@4lb.ca</a></p>
                        <p class="mt-4">
                            <a href="{{ route('contact') }}" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded inline-block">
                                Formulaire de contact
                            </a>
                        </p>
                    </div>
                </section>
            </div>
        </div>

        <!-- Footer -->
        @include('partials.footer')
    </div>
</body>
</html>
