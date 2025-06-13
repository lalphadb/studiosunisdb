<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Conditions d'utilisation - StudiosUnisDB</title>
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
            <h1 class="text-4xl font-bold mb-8">Conditions d'utilisation</h1>
            
            <div class="prose prose-invert max-w-none">
                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4">Acceptation des conditions</h2>
                    <p>En utilisant StudiosUnisDB, vous acceptez les présentes conditions d'utilisation. Ce système est destiné exclusivement aux Studios Unis du Québec et leurs membres autorisés.</p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4">Utilisation autorisée</h2>
                    <ul class="list-disc ml-6 space-y-2">
                        <li>Gestion administrative des écoles de karaté</li>
                        <li>Suivi des membres et de leurs progressions</li>
                        <li>Organisation des cours et présences</li>
                        <li>Consultation des données personnelles avec autorisation</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4">Responsabilités de l'utilisateur</h2>
                    <ul class="list-disc ml-6 space-y-2">
                        <li>Maintenir la confidentialité de vos identifiants</li>
                        <li>Utiliser le système de manière éthique et légale</li>
                        <li>Respecter la vie privée des membres</li>
                        <li>Signaler tout problème de sécurité</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4">Protection des données</h2>
                    <p>StudiosUnisDB respecte strictement la Loi 25 du Québec. Toutes les données personnelles sont traitées conformément à notre politique de confidentialité.</p>
                </section>
            </div>
        </div>

        <!-- Footer -->
        @include('partials.footer')
    </div>
</body>
</html>
