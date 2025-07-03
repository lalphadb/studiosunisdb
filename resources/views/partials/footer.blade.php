<footer class="bg-slate-800 border-t border-slate-700 px-6 py-3" role="contentinfo">
    <div class="flex flex-col sm:flex-row justify-between items-center text-xs text-slate-400 space-y-2 sm:space-y-0">
        <div class="flex flex-col sm:flex-row items-center space-y-1 sm:space-y-0 sm:space-x-4">
            <span>StudiosDB v4.1.10.2 - Système de gestion d'école de karaté</span>
            <span class="hidden sm:inline" aria-hidden="true">•</span>
            <span>🇨🇦 Conforme Loi 25 (Protection des renseignements personnels)</span>
        </div>
        <div class="flex items-center space-x-4">
            <span>{{ now()->format('d/m/Y H:i') }}</span>
            @auth
                <span aria-hidden="true">•</span>
                <span>{{ auth()->user()->ecole->nom ?? 'École' }}</span>
            @endauth
        </div>
    </div>
    
    <!-- Accessibilité et légal -->
    <div class="mt-2 pt-2 border-t border-slate-700/50 text-center">
        <div class="flex flex-col sm:flex-row justify-center items-center space-y-1 sm:space-y-0 sm:space-x-6 text-xs text-slate-500">
            <a href="#" class="hover:text-slate-300 transition-colors">Politique de confidentialité</a>
            <a href="#" class="hover:text-slate-300 transition-colors">Accessibilité</a>
            <a href="#" class="hover:text-slate-300 transition-colors">Support technique</a>
        </div>
    </div>
</footer>
