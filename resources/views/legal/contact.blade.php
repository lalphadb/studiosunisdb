<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact - StudiosUnisDB</title>
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
                        <a href="{{ route('privacy') }}" class="text-slate-300 hover:text-white">Confidentialité</a>
                        <a href="{{ route('login') }}" class="bg-blue-600 px-4 py-2 rounded">Connexion</a>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Contenu -->
        <div class="max-w-4xl mx-auto px-6 py-12">
            <h1 class="text-4xl font-bold mb-8">Contactez-nous</h1>

            @if(session('success'))
                <div class="bg-green-900/30 border border-green-400 text-green-400 rounded-lg p-4 mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid md:grid-cols-2 gap-12">
                <!-- Formulaire -->
                <div>
                    <h2 class="text-2xl font-semibold mb-6">Envoyez-nous un message</h2>
                    
                    <form method="POST" action="{{ route('contact.send') }}" class="space-y-6">
                        @csrf
                        
                        <div>
                            <label for="name" class="block text-sm font-medium mb-2">Nom complet</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                   class="w-full px-4 py-3 bg-slate-800 border border-slate-600 rounded-lg focus:border-blue-400 focus:outline-none">
                            @error('name')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium mb-2">Adresse courriel</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                   class="w-full px-4 py-3 bg-slate-800 border border-slate-600 rounded-lg focus:border-blue-400 focus:outline-none">
                            @error('email')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-medium mb-2">Sujet</label>
                            <select id="subject" name="subject" required
                                    class="w-full px-4 py-3 bg-slate-800 border border-slate-600 rounded-lg focus:border-blue-400 focus:outline-none">
                                <option value="">Sélectionnez un sujet</option>
                                <option value="Protection des données" {{ old('subject') == 'Protection des données' ? 'selected' : '' }}>Protection des données</option>
                                <option value="Demande d'accès aux données" {{ old('subject') == 'Demande d\'accès aux données' ? 'selected' : '' }}>Demande d'accès aux données</option>
                                <option value="Suppression de données" {{ old('subject') == 'Suppression de données' ? 'selected' : '' }}>Suppression de données</option>
                                <option value="Support technique" {{ old('subject') == 'Support technique' ? 'selected' : '' }}>Support technique</option>
                                <option value="Question générale" {{ old('subject') == 'Question générale' ? 'selected' : '' }}>Question générale</option>
                                <option value="Autre" {{ old('subject') == 'Autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('subject')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium mb-2">Message</label>
                            <textarea id="message" name="message" rows="6" required
                                      class="w-full px-4 py-3 bg-slate-800 border border-slate-600 rounded-lg focus:border-blue-400 focus:outline-none"
                                      placeholder="Décrivez votre demande en détail...">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200">
                            Envoyer le message
                        </button>
                    </form>
                </div>

                <!-- Informations de contact -->
                <div>
                    <h2 class="text-2xl font-semibold mb-6">Autres moyens de contact</h2>
                    
                    <div class="space-y-6">
                        <div class="bg-slate-800 p-6 rounded-lg">
                            <h3 class="font-semibold mb-3">Protection des données</h3>
                            <p class="text-slate-300 mb-2">Pour toute question relative à vos données personnelles :</p>
                            <p class="text-blue-400">lalpha@4lb.ca</p>
                        </div>

                        <div class="bg-slate-800 p-6 rounded-lg">
                            <h3 class="font-semibold mb-3">Support technique</h3>
                            <p class="text-slate-300 mb-2">Pour l'assistance technique :</p>
                            <p class="text-blue-400">lalpha@4lb.ca</p>
                        </div>

                        <div class="bg-slate-800 p-6 rounded-lg">
                            <h3 class="font-semibold mb-3">Studios UnisDB</h3>
                            <p class="text-slate-300 mb-2">Administration générale :</p>
                            <p class="text-blue-400">lalpha@4lb.ca</p>
                        </div>

                        <div class="bg-blue-900/30 border border-blue-400 p-4 rounded-lg">
                            <h4 class="font-medium text-blue-400 mb-2">Délai de réponse</h4>
                            <p class="text-sm">Conformément à la Loi 25, nous répondons aux demandes dans un délai maximum de 30 jours.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        @include('partials.footer')
    </div>
</body>
</html>
