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
