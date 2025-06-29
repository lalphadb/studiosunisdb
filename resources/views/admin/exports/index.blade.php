@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <x-module-header 
        module="presence"
        title="Exports de Données" 
        subtitle="Exports conformes Loi 25 - Transparence et audit"
    />

    <div class="mt-6 space-y-6">
        
        <!-- Export Logs Système -->
        @if(auth()->user()->hasRole('superadmin'))
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <h2 class="text-xl font-bold text-white mb-4 flex items-center">
                <span class="text-2xl mr-3">📋</span>
                Export Logs Système (Loi 25 - Transparence)
            </h2>
            
            <div class="bg-purple-600/10 border border-purple-600/30 rounded-lg p-4 mb-4">
                <p class="text-purple-400 text-sm">
                    <strong>Article 9 - Transparence :</strong> Export des logs d'accès et actions pour audit et conformité réglementaire.
                </p>
            </div>
            
            <form method="GET" action="{{ route('admin.exports.logs') }}" class="grid md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Date début</label>
                    <input type="date" name="start_date" value="{{ date('Y-m-d', strtotime('-30 days')) }}"
                           class="bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 w-full">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Date fin</label>
                    <input type="date" name="end_date" value="{{ date('Y-m-d') }}"
                           class="bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 w-full">
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg w-full transition-colors">
                        📊 Télécharger CSV
                    </button>
                </div>
            </form>

            <div class="mt-4 text-sm text-slate-400">
                <p>💡 <strong>Information :</strong> Ce fichier CSV contient les logs d'audit pour la période sélectionnée, conforme aux exigences de transparence de la Loi 25.</p>
            </div>
        </div>
        @else
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <h2 class="text-xl font-bold text-white mb-4 flex items-center">
                <span class="text-2xl mr-3">🔒</span>
                Accès Restreint
            </h2>
            <p class="text-slate-300">L'export des logs système est réservé aux superadministrateurs.</p>
        </div>
        @endif

        <!-- Informations Loi 25 -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <h2 class="text-xl font-bold text-white mb-4 flex items-center">
                <span class="text-2xl mr-3">⚖️</span>
                Conformité Loi 25 du Québec
            </h2>
            
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold text-slate-300 mb-3">Articles respectés</h3>
                    <div class="space-y-2">
                        <div class="flex items-center text-sm">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                            <span class="text-slate-300">Article 8 - Droit à la portabilité</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                            <span class="text-slate-300">Article 9 - Transparence des traitements</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                            <span class="text-slate-300">Article 14 - Audit et traçabilité</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="font-semibold text-slate-300 mb-3">Mesures techniques</h3>
                    <div class="space-y-2">
                        <div class="flex items-center text-sm">
                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                            <span class="text-slate-300">Logs horodatés avec IP</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                            <span class="text-slate-300">Export sécurisé des données</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                            <span class="text-slate-300">Accès contrôlé par rôles</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Accès Telescope pour Superadmin -->
        @if(auth()->user()->hasRole('superadmin'))
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
            <h2 class="text-xl font-bold text-white mb-4 flex items-center">
                <span class="text-2xl mr-3">🔭</span>
                Monitoring Temps Réel
            </h2>
            
            <div class="grid md:grid-cols-2 gap-4">
                <a href="/telescope" target="_blank"
                   class="flex items-center bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-lg transition-colors">
                    <span class="text-2xl mr-4">🔭</span>
                    <div>
                        <div class="font-semibold">Laravel Telescope</div>
                        <div class="text-sm text-blue-100">Dashboard complet de monitoring</div>
                    </div>
                </a>
                
                <a href="/telescope/requests" target="_blank"
                   class="flex items-center bg-teal-600 hover:bg-teal-700 text-white p-4 rounded-lg transition-colors">
                    <span class="text-2xl mr-4">📡</span>
                    <div>
                        <div class="font-semibold">Requêtes HTTP</div>
                        <div class="text-sm text-teal-100">Analyse détaillée des requêtes</div>
                    </div>
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
