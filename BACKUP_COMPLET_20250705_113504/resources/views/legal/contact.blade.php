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
